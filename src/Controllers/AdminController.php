<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Respect\Validation\Validator as v;

class AdminController
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

    // Certificate Types Management
    public function listCertificateTypes(Request $request, Response $response)
    {
        $stmt = $this->db->query('SELECT * FROM certificate_types ORDER BY name');
        $types = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($types));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function createCertificateType(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        if (!$this->validateCertificateTypeData($data)) {
            $response->getBody()->write(json_encode(['error' => 'Invalid certificate type data']));
            return $response->withStatus(400);
        }

        try {
            $stmt = $this->db->prepare('INSERT INTO certificate_types (name, fee, required_documents) VALUES (?, ?, ?)');
            $stmt->execute([$data['name'], $data['fee'], json_encode($data['required_documents'])]);

            $response->getBody()->write(json_encode(['message' => 'Certificate type created successfully']));
            return $response->withStatus(201);
        } catch (\PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to create certificate type']));
            return $response->withStatus(500);
        }
    }

    public function updateCertificateType(Request $request, Response $response, array $args)
    {
        $data = $request->getParsedBody();

        if (!$this->validateCertificateTypeData($data)) {
            $response->getBody()->write(json_encode(['error' => 'Invalid certificate type data']));
            return $response->withStatus(400);
        }

        try {
            $stmt = $this->db->prepare('UPDATE certificate_types SET name = ?, fee = ?, required_documents = ? WHERE id = ?');
            $stmt->execute([$data['name'], $data['fee'], json_encode($data['required_documents']), $args['id']]);

            $response->getBody()->write(json_encode(['message' => 'Certificate type updated successfully']));
            return $response->withStatus(200);
        } catch (\PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to update certificate type']));
            return $response->withStatus(500);
        }
    }

    public function deleteCertificateType(Request $request, Response $response, array $args)
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM certificate_types WHERE id = ?');
            $stmt->execute([$args['id']]);

            $response->getBody()->write(json_encode(['message' => 'Certificate type deleted successfully']));
            return $response->withStatus(200);
        } catch (\PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to delete certificate type']));
            return $response->withStatus(500);
        }
    }

    // User Management
    public function listUsers(Request $request, Response $response)
    {
        $stmt = $this->db->query('SELECT id, username, email, role_id, status FROM users ORDER BY username');
        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($users));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function updateUser(Request $request, Response $response, array $args)
    {
        $data = $request->getParsedBody();

        if (!$this->validateUserUpdateData($data)) {
            $response->getBody()->write(json_encode(['error' => 'Invalid user data']));
            return $response->withStatus(400);
        }

        try {
            $stmt = $this->db->prepare('UPDATE users SET status = ?, role_id = ? WHERE id = ?');
            $stmt->execute([$data['status'], $data['role_id'], $args['id']]);

            $response->getBody()->write(json_encode(['message' => 'User updated successfully']));
            return $response->withStatus(200);
        } catch (\PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to update user']));
            return $response->withStatus(500);
        }
    }

    public function deleteUser(Request $request, Response $response, array $args)
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$args['id']]);

            $response->getBody()->write(json_encode(['message' => 'User deleted successfully']));
            return $response->withStatus(200);
        } catch (\PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to delete user']));
            return $response->withStatus(500);
        }
    }

    // Application Management
    public function listApplications(Request $request, Response $response)
    {
        $stmt = $this->db->query(
            'SELECT a.*, ct.name as certificate_type_name, u.username as submitted_by_name 
            FROM applications a 
            JOIN certificate_types ct ON a.certificate_type_id = ct.id 
            JOIN users u ON a.submitted_by = u.id 
            ORDER BY a.created_at DESC'
        );
        $applications = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($applications));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getApplication(Request $request, Response $response, array $args)
    {
        $stmt = $this->db->prepare(
            'SELECT a.*, ct.name as certificate_type_name, u.username as submitted_by_name 
            FROM applications a 
            JOIN certificate_types ct ON a.certificate_type_id = ct.id 
            JOIN users u ON a.submitted_by = u.id 
            WHERE a.id = ?'
        );
        $stmt->execute([$args['id']]);
        $application = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$application) {
            $response->getBody()->write(json_encode(['error' => 'Application not found']));
            return $response->withStatus(404);
        }

        // Get uploaded documents
        $stmt = $this->db->prepare(
            'SELECT id, document_type, file_path, file_type 
            FROM application_documents 
            WHERE application_id = ?'
        );
        $stmt->execute([$args['id']]);
        $documents = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $application['documents'] = [];
        foreach ($documents as $doc) {
            $application['documents'][$doc['document_type']] = [
                'id' => $doc['id'],
                'path' => $doc['file_path'],
                'type' => $doc['file_type']
            ];
        }

        $response->getBody()->write(json_encode($application));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function approveApplication(Request $request, Response $response, array $args)
    {
        $data = $request->getParsedBody();
        $user = $request->getAttribute('user');

        if (!isset($args['id'])) {
            $response->getBody()->write(json_encode(['error' => 'Application ID is required']));
            return $response->withStatus(400);
        }

        try {
            $this->db->beginTransaction();

            // Check if application exists and is in pending status
            $stmt = $this->db->prepare('SELECT status, submitted_by FROM applications WHERE id = ?');
            $stmt->execute([$args['id']]);
            $application = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$application) {
                throw new \Exception('Application not found');
            }

            if ($application['status'] !== 'pending') {
                throw new \Exception('Only pending applications can be approved');
            }

            // Update application status
            $stmt = $this->db->prepare(
                'UPDATE applications 
                SET status = "approved", reviewed_by = ?, review_comments = ?, review_date = NOW() 
                WHERE id = ?'
            );
            $stmt->execute([$user['id'], $data['comments'] ?? null, $args['id']]);

            // Generate certificate number
            $certificateNumber = 'CERT-' . date('Y') . '-' . str_pad($args['id'], 6, '0', STR_PAD_LEFT);
            $qrCode = $this->generateQRCode($certificateNumber);
            $digitalSignature = $this->generateDigitalSignature($certificateNumber);

            // Create certificate record
            $stmt = $this->db->prepare(
                'INSERT INTO certificates 
                (application_id, certificate_number, qr_code, digital_signature, issued_date, issued_by) 
                VALUES (?, ?, ?, ?, NOW(), ?)'
            );
            $stmt->execute([$args['id'], $certificateNumber, $qrCode, $digitalSignature, $user['id']]);

            // Get submitter's email
            $stmt = $this->db->prepare('SELECT email FROM users WHERE id = ?');
            $stmt->execute([$application['submitted_by']]);
            $submitterEmail = $stmt->fetchColumn();

            // Send notification email
            if ($submitterEmail) {
                $this->sendNotificationEmail(
                    $submitterEmail,
                    'Certificate Application Approved',
                    "Your certificate application (ID: {$args['id']}) has been approved.\n\n" .
                    "Certificate Number: {$certificateNumber}\n\n" .
                    "You can download your certificate from your dashboard."
                );
            }

            $this->db->commit();

            $response->getBody()->write(json_encode([
                'message' => 'Application approved successfully',
                'certificate_number' => $certificateNumber
            ]));
            return $response->withStatus(200);
        } catch (\Exception $e) {
            $this->db->rollBack();
            $response->getBody()->write(json_encode(['error' => 'Failed to approve application: ' . $e->getMessage()]));
            return $response->withStatus(500);
        }
    }

    public function rejectApplication(Request $request, Response $response, array $args)
    {
        $data = $request->getParsedBody();
        $user = $request->getAttribute('user');

        if (!isset($args['id'])) {
            $response->getBody()->write(json_encode(['error' => 'Application ID is required']));
            return $response->withStatus(400);
        }

        if (empty($data['comments'])) {
            $response->getBody()->write(json_encode(['error' => 'Review comments are required for rejection']));
            return $response->withStatus(400);
        }

        try {
            $this->db->beginTransaction();

            // Check if application exists and is in pending status
            $stmt = $this->db->prepare('SELECT status, submitted_by FROM applications WHERE id = ?');
            $stmt->execute([$args['id']]);
            $application = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$application) {
                throw new \Exception('Application not found');
            }

            if ($application['status'] !== 'pending') {
                throw new \Exception('Only pending applications can be rejected');
            }

            // Update application status
            $stmt = $this->db->prepare(
                'UPDATE applications 
                SET status = "rejected", reviewed_by = ?, review_comments = ?, review_date = NOW() 
                WHERE id = ?'
            );
            $stmt->execute([$user['id'], $data['comments'], $args['id']]);

            // Get submitter's email
            $stmt = $this->db->prepare('SELECT email FROM users WHERE id = ?');
            $stmt->execute([$application['submitted_by']]);
            $submitterEmail = $stmt->fetchColumn();

            // Send notification email to the submitter
            if ($submitterEmail) {
                $this->sendNotificationEmail(
                    $submitterEmail,
                    'Certificate Application Rejected',
                    "Your certificate application (ID: {$args['id']}) has been rejected.\n\nReason: {$data['comments']}\n\nPlease review the comments and submit a new application if needed."
                );
            }

            $this->db->commit();

            $response->getBody()->write(json_encode(['message' => 'Application rejected successfully']));
            return $response->withStatus(200);
        } catch (\Exception $e) {
            $this->db->rollBack();
            $response->getBody()->write(json_encode(['error' => 'Failed to reject application: ' . $e->getMessage()]));
            return $response->withStatus(500);
        }
    }

    // Reports
    public function getCertificateReport(Request $request, Response $response)
    {
        $stmt = $this->db->query(
            'SELECT ct.name, COUNT(c.id) as count 
            FROM certificate_types ct 
            LEFT JOIN applications a ON ct.id = a.certificate_type_id 
            LEFT JOIN certificates c ON a.id = c.application_id 
            GROUP BY ct.id, ct.name'
        );
        $report = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($report));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getApplicationReport(Request $request, Response $response)
    {
        $stmt = $this->db->query(
            'SELECT 
                ct.name,
                COUNT(a.id) as total,
                SUM(CASE WHEN a.status = "pending" THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN a.status = "approved" THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN a.status = "rejected" THEN 1 ELSE 0 END) as rejected
            FROM certificate_types ct
            LEFT JOIN applications a ON ct.id = a.certificate_type_id
            GROUP BY ct.id, ct.name'
        );
        $report = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $response->getBody()->write(json_encode($report));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Helper methods
    private function validateCertificateTypeData($data)
    {
        return (
            !empty($data['name']) &&
            is_numeric($data['fee']) &&
            $data['fee'] >= 0 &&
            is_array($data['required_documents'])
        );
    }

    private function validateUserUpdateData($data)
    {
        return (
            in_array($data['status'], ['active', 'inactive']) &&
            in_array($data['role_id'], [1, 2])
        );
    }

    private function generateCertificateNumber()
    {
        return 'CERT-' . date('Y') . '-' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function generateQRCode($certificateNumber)
    {
        // Generate QR code URL for certificate verification
        return $_ENV['APP_URL'] . '/verify/' . $certificateNumber;
    }

    private function generateDigitalSignature($certificateNumber)
    {
        // In a real application, this would use proper digital signature algorithms
        return hash('sha256', $certificateNumber . $_ENV['APP_KEY']);
    }

    private function sendNotificationEmail($to, $subject, $message)
    {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USERNAME'];
            $mail->Password = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['SMTP_PORT'];

            // Recipients
            $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
            return true;
        } catch (\Exception $e) {
            // Log the error but don't throw it to prevent disrupting the application flow
            error_log("Failed to send email: {$e->getMessage()}");
            return false;
        }
    }

    public function adminReview(Request $request, Response $response)
    {
        try {
            // Get pending applications with related information
            $stmt = $this->db->query(
                'SELECT a.*, ct.name as certificate_type_name, u.username as submitted_by_name 
                FROM applications a 
                JOIN certificate_types ct ON a.certificate_type_id = ct.id 
                JOIN users u ON a.submitted_by = u.id 
                WHERE a.status = "pending" 
                ORDER BY a.created_at DESC'
            );
            $applications = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Get documents for each application
            foreach ($applications as &$application) {
                $stmt = $this->db->prepare(
                    'SELECT document_type, file_path 
                    FROM application_documents 
                    WHERE application_id = ?'
                );
                $stmt->execute([$application['id']]);
                $application['documents'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }

            $response->getBody()->write(json_encode($applications));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\PDOException $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to fetch applications']));
            return $response->withStatus(500);
        }
    }
}
