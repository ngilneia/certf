<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;
use Dompdf\Dompdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class CertificateController
{
    private $db;

    public function __construct()
    {
        $this->db = new \PDO(
            "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD']
        );
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function getAvailableTypes(Request $request, Response $response)
    {
        try {
            $stmt = $this->db->query('SELECT id, name, fee, required_documents FROM certificate_types WHERE status = "active"');
            $types = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $response = $response->withHeader('Content-Type', 'application/json');
            $responseBody = json_encode($types);
            if ($responseBody === false) {
                throw new \Exception('Failed to encode response as JSON');
            }
            $response->getBody()->write($responseBody);
            return $response;
        } catch (\Exception $e) {
            $response = $response->withStatus(500)->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode(['error' => 'Failed to fetch certificate types']));
            return $response;
        }
    }

    public function getCertificateType(Request $request, Response $response, array $args)
    {
        $stmt = $this->db->prepare('SELECT id, name, fee, required_documents FROM certificate_types WHERE id = ? AND status = "active"');
        $stmt->execute([$args['id']]);
        $type = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$type) {
            $response->getBody()->write(json_encode(['error' => 'Certificate type not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($type));
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function generateUniqueKey() {
        return 'APP-' . date('Y') . '-' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function submitApplication(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $user = $request->getAttribute('user');
        $uploadedFiles = $request->getUploadedFiles();
        $uniqueKey = $this->generateUniqueKey();
        
        // Debug logging
        error_log('Application submission data: ' . json_encode($data));
        error_log('Uploaded files count: ' . count($uploadedFiles));
        error_log('User data: ' . json_encode($user));

        // Validate user authentication
        if (!$user || !isset($user['id'])) {
            error_log('User not authenticated or invalid user data');
            $response->getBody()->write(json_encode(['error' => 'User authentication required']));
            return $response->withStatus(401);
        }

        if (!$this->validateApplicationData($data)) {
            error_log('Invalid application data: ' . json_encode($data));
            $response->getBody()->write(json_encode(['error' => 'Invalid application data']));
            return $response->withStatus(400);
        }

        try {
            // Begin transaction for data consistency
            $this->db->beginTransaction();
            
            // Generate unique key
            $uniqueKey = $this->generateUniqueKey();
            
            // Insert application record
            $stmt = $this->db->prepare(
                'INSERT INTO applications 
                (certificate_type_id, applicant_name, applicant_email, applicant_phone, form_data, documents, submitted_by, unique_key) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $data['certificate_type_id'],
                $data['applicant_name'],
                $data['applicant_email'],
                $data['applicant_phone'],
                json_encode($data['form_data'] ?? []),
                json_encode([]), // Empty documents array, will be updated later
                $user['id'],
                $uniqueKey
            ]);

            $applicationId = $this->db->lastInsertId();
            
            // Process uploaded documents if any
            $documents = [];
            if (!empty($uploadedFiles)) {
                foreach ($uploadedFiles as $key => $file) {
                    // Check if $file is an object before calling methods on it
                    if (is_object($file) && method_exists($file, 'getError')) {
                        if ($file->getError() === UPLOAD_ERR_OK) {
                            // Normalize document key to ensure consistent case
                            $normalizedKey = trim($key);
                            $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
                            $filename = $uniqueKey . '-' . $normalizedKey . '.' . $extension;
                            $uploadDir = __DIR__ . '/../../public/uploads/';
                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0777, true);
                            }
                            $file->moveTo($uploadDir . $filename);
                            $documents[$normalizedKey] = $filename;
                            error_log('Uploaded document: ' . $normalizedKey . ' => ' . $filename);
                        } else {
                            error_log('File upload error for key ' . $key . ': ' . $file->getError());
                        }
                    } else {
                        error_log('Invalid file object for key ' . $key . ': ' . (is_array($file) ? 'Array' : gettype($file)));
                    }
                }
                
                // Update the application with document information
                if (!empty($documents)) {
                    $stmt = $this->db->prepare('UPDATE applications SET documents = ? WHERE id = ?');
                    $stmt->execute([json_encode($documents), $applicationId]);
                    error_log('Updated application ' . $applicationId . ' with documents: ' . json_encode($documents));
                }
            }
            
            // Commit the transaction
            $this->db->commit();
            
            $response->getBody()->write(json_encode([
                'message' => 'Application submitted successfully',
                'application_id' => $applicationId
            ]));
            return $response->withStatus(201);
        } catch (\PDOException $e) {
            // Rollback transaction on error
            $this->db->rollBack();
            error_log('Database error during application submission: ' . $e->getMessage());
            $response->getBody()->write(json_encode(['error' => 'Failed to submit application: Database error']));
            return $response->withStatus(500);
        } catch (\Exception $e) {
            // Rollback transaction on error
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log('Unexpected error during application submission: ' . $e->getMessage());
            $response->getBody()->write(json_encode(['error' => 'Failed to submit application: ' . $e->getMessage()]));
            return $response->withStatus(500);
        }
    }

    public function getMyApplications(Request $request, Response $response)
    {
        $user = $request->getAttribute('user');

        $stmt = $this->db->prepare(
            'SELECT a.*, ct.name as certificate_type_name 
            FROM applications a 
            JOIN certificate_types ct ON a.certificate_type_id = ct.id 
            WHERE a.submitted_by = ? 
            ORDER BY a.created_at DESC'
        );
        $stmt->execute([$user['id']]);
        $applications = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $response->getBody()->write(json_encode($applications));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getApplicationDetails(Request $request, Response $response, array $args)
    {
        $user = $request->getAttribute('user');

        $stmt = $this->db->prepare(
            'SELECT a.*, ct.name as certificate_type_name, ct.required_documents 
            FROM applications a 
            JOIN certificate_types ct ON a.certificate_type_id = ct.id 
            WHERE a.id = ? AND (a.submitted_by = ? OR ? = 1)'
        );
        $stmt->execute([$args['id'], $user['id'], $user['role_id']]);
        $application = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$application) {
            $response->getBody()->write(json_encode(['error' => 'Application not found']));
            return $response->withStatus(404);
        }
        
        // Ensure documents and required_documents are properly decoded JSON
        if (isset($application['documents']) && is_string($application['documents'])) {
            $application['documents'] = json_decode($application['documents'], true) ?: [];
            // Ensure document paths are web-accessible
            foreach ($application['documents'] as $docType => $filename) {
                $application['documents'][$docType] = '/uploads/' . $filename;
            }
            error_log('Decoded documents for application ' . $args['id'] . ': ' . json_encode($application['documents']));
        } else if (!isset($application['documents'])) {
            $application['documents'] = [];
            error_log('No documents found for application ' . $args['id']);
        }
        
        if (isset($application['required_documents']) && is_string($application['required_documents'])) {
            $application['required_documents'] = json_decode($application['required_documents'], true) ?: [];
            error_log('Required documents for application ' . $args['id'] . ': ' . json_encode($application['required_documents']));
        } else if (!isset($application['required_documents'])) {
            $application['required_documents'] = [];
            error_log('No required documents defined for application ' . $args['id']);
        }

        $response->getBody()->write(json_encode($application));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function uploadDocuments(Request $request, Response $response, array $args)
    {
        $uploadedFiles = $request->getUploadedFiles();
        $user = $request->getAttribute('user');

        // Verify application ownership
        $stmt = $this->db->prepare('SELECT * FROM applications WHERE id = ? AND submitted_by = ?');
        $stmt->execute([$args['application_id'], $user['id']]);
        $application = $stmt->fetch();

        if (!$application) {
            $response->getBody()->write(json_encode(['error' => 'Application not found']));
            return $response->withStatus(404);
        }

        try {
            $documents = [];
            foreach ($uploadedFiles as $key => $file) {
                $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extension;
                $file->moveTo(__DIR__ . '/../../public/uploads/' . $filename);
                $documents[$key] = $filename;
            }

            $stmt = $this->db->prepare('UPDATE applications SET documents = ? WHERE id = ?');
            $stmt->execute([json_encode($documents), $args['application_id']]);

            $response->getBody()->write(json_encode([
                'message' => 'Documents uploaded successfully',
                'documents' => $documents
            ]));
            return $response->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to upload documents']));
            return $response->withStatus(500);
        }
    }

    public function generateCertificate(Request $request, Response $response, array $args)
    {
        $user = $request->getAttribute('user');

        // Get application and certificate details
        $stmt = $this->db->prepare(
            'SELECT a.*, c.*, ct.name as certificate_type_name 
            FROM applications a 
            JOIN certificates c ON a.id = c.application_id 
            JOIN certificate_types ct ON a.certificate_type_id = ct.id 
            WHERE a.id = ? AND a.status = "approved"'
        );
        $stmt->execute([$args['id']]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            $response->getBody()->write(json_encode(['error' => 'Certificate not found or application not approved']));
            return $response->withStatus(404);
        }

        // Generate PDF
        $dompdf = new Dompdf();
        $html = $this->generateCertificateHTML($data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $response = $response->withHeader('Content-Type', 'application/pdf');
        $response = $response->withHeader('Content-Disposition', 'attachment; filename="certificate.pdf"');
        $response->getBody()->write($dompdf->output());
        return $response;
    }

    public function verifyCertificate(Request $request, Response $response, array $args)
    {
        $stmt = $this->db->prepare(
            'SELECT c.*, a.applicant_name, ct.name as certificate_type_name 
            FROM certificates c 
            JOIN applications a ON c.application_id = a.id 
            JOIN certificate_types ct ON a.certificate_type_id = ct.id 
            WHERE c.certificate_number = ?'
        );
        $stmt->execute([$args['number']]);
        $certificate = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$certificate) {
            $response->getBody()->write(json_encode(['error' => 'Certificate not found']));
            return $response->withStatus(404);
        }

        $response->getBody()->write(json_encode([
            'valid' => true,
            'certificate' => $certificate
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function publicVerifyCertificate(Request $request, Response $response, array $args)
    {
        return $this->verifyCertificate($request, $response, $args);
    }
    
    public function updateApplication(Request $request, Response $response, array $args)
    {
        $data = $request->getParsedBody();
        $user = $request->getAttribute('user');
        
        // Verify application ownership
        $stmt = $this->db->prepare('SELECT * FROM applications WHERE id = ? AND submitted_by = ? AND status = "pending"');
        $stmt->execute([$args['id'], $user['id']]);
        $application = $stmt->fetch();
        
        if (!$application) {
            $response->getBody()->write(json_encode(['error' => 'Application not found or cannot be updated']));
            return $response->withStatus(404);
        }
        
        try {
            // Begin transaction
            $this->db->beginTransaction();
            
            // Update application record
            $stmt = $this->db->prepare(
                'UPDATE applications SET 
                applicant_name = ?, 
                applicant_email = ?, 
                applicant_phone = ?, 
                form_data = ? 
                WHERE id = ?'
            );
            $stmt->execute([
                $data['applicant_name'],
                $data['applicant_email'],
                $data['applicant_phone'],
                json_encode($data['form_data'] ?? []),
                $args['id']
            ]);
            
            // Commit the transaction
            $this->db->commit();
            
            $response->getBody()->write(json_encode([
                'message' => 'Application updated successfully',
                'application_id' => $args['id']
            ]));
            return $response->withStatus(200);
        } catch (\Exception $e) {
            // Rollback transaction on error
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            $response->getBody()->write(json_encode(['error' => 'Failed to update application: ' . $e->getMessage()]));
            return $response->withStatus(500);
        }
    }
    
    public function deleteApplication(Request $request, Response $response, array $args)
    {
        $user = $request->getAttribute('user');
        
        // Verify application ownership
        $stmt = $this->db->prepare('SELECT * FROM applications WHERE id = ? AND submitted_by = ? AND status = "pending"');
        $stmt->execute([$args['id'], $user['id']]);
        $application = $stmt->fetch();
        
        if (!$application) {
            $response->getBody()->write(json_encode(['error' => 'Application not found or cannot be deleted']));
            return $response->withStatus(404);
        }
        
        try {
            // Delete application
            $stmt = $this->db->prepare('DELETE FROM applications WHERE id = ?');
            $stmt->execute([$args['id']]);
            
            $response->getBody()->write(json_encode(['message' => 'Application deleted successfully']));
            return $response->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to delete application: ' . $e->getMessage()]));
            return $response->withStatus(500);
        }
    }

    public function previewCertificate(Request $request, Response $response, array $args)
    {
        $user = $request->getAttribute('user');

        $stmt = $this->db->prepare(
            'SELECT a.*, ct.name as certificate_type_name 
            FROM applications a 
            JOIN certificate_types ct ON a.certificate_type_id = ct.id 
            WHERE a.id = ? AND (a.submitted_by = ? OR ? = 1)'
        );
        $stmt->execute([$args['id'], $user['id'], $user['role_id']]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            $response->getBody()->write(json_encode(['error' => 'Application not found']));
            return $response->withStatus(404);
        }

        // Generate preview HTML
        $html = $this->generateCertificateHTML($data, true);
        $response->getBody()->write($html);
        return $response->withHeader('Content-Type', 'text/html');
    }

    private function validateApplicationData($data)
    {
        // Check if required fields exist
        if (empty($data['certificate_type_id'])) {
            error_log('Validation failed: Missing certificate_type_id');
            return false;
        }
        
        if (empty($data['applicant_name'])) {
            error_log('Validation failed: Missing applicant_name');
            return false;
        }
        
        if (!empty($data['applicant_email']) && !v::email()->validate($data['applicant_email'])) {
            error_log('Validation failed: Invalid email format: ' . $data['applicant_email']);
            return false;
        }
        
        if (empty($data['applicant_phone'])) {
            error_log('Validation failed: Missing applicant_phone');
            return false;
        }
        
        if (!isset($data['form_data']) || !is_array($data['form_data'])) {
            error_log('Validation failed: form_data is not an array');
            return false;
        }
        
        return true;
    }

    private function generateCertificateHTML($data, $isPreview = false)
    {
        // Generate QR code
        $qrCode = new QrCode($_ENV['APP_URL'] . '/verify/' . $data['certificate_number']);
        $writer = new PngWriter();
        $qrResult = $writer->write($qrCode);
        $qrDataUri = $qrResult->getDataUri();

        // Certificate HTML template
        $html = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .certificate { 
                    width: 100%; 
                    max-width: 800px; 
                    margin: 0 auto; 
                    padding: 20px; 
                    border: 2px solid #000; 
                }
                .header { text-align: center; margin-bottom: 30px; }
                .content { margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; }
                .qr-code { text-align: right; }
            </style>
        </head>
        <body>
            <div class="certificate">
                <div class="header">
                    <h1>Government of India</h1>
                    <h2>{$data['certificate_type_name']}</h2>
                </div>
                <div class="content">
                    <p>This is to certify that <strong>{$data['applicant_name']}</strong></p>
                    <p>Certificate Number: {$data['certificate_number']}</p>
                    <p>Issue Date: {$data['issued_at']}</p>
                </div>
                <div class="footer">
                    <p>This document is electronically generated and requires no signature.</p>
                    <div class="qr-code">
                        <img src="{$qrDataUri}" width="100">
                        <p>Scan to verify authenticity</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        HTML;

        return $html;
    }
}
