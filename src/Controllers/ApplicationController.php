<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ApplicationController
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

    public function getAdminApplications(Request $request, Response $response)
    {
        try {
            $stmt = $this->db->query('SELECT a.*, u.email, u.username FROM applications a JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC');
            $applications = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $response->getBody()->write(json_encode(['applications' => $applications]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to fetch applications']));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function getDeoApplications(Request $request, Response $response)
    {
        try {
            $userId = $_SESSION['user']['id'];
            $stmt = $this->db->prepare('SELECT * FROM applications WHERE user_id = ? ORDER BY created_at DESC');
            $stmt->execute([$userId]);
            $applications = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $response->getBody()->write(json_encode(['applications' => $applications]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to fetch applications']));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function reviewApplication(Request $request, Response $response, array $args)
    {
        try {
            $data = $request->getParsedBody();
            $applicationId = $args['id'];
            $status = $data['status'] ?? '';
            $remarks = $data['remarks'] ?? '';

            if (!in_array($status, ['approved', 'rejected'])) {
                $response->getBody()->write(json_encode(['error' => 'Invalid status']));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }

            $stmt = $this->db->prepare('UPDATE applications SET status = ?, remarks = ?, reviewed_at = NOW(), reviewed_by = ? WHERE id = ?');
            $stmt->execute([$status, $remarks, $_SESSION['user']['id'], $applicationId]);

            if ($stmt->rowCount() === 0) {
                $response->getBody()->write(json_encode(['error' => 'Application not found']));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode([
                'message' => 'Application ' . $status . ' successfully',
                'status' => $status
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to update application']));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function printCertificate(Request $request, Response $response, array $args)
    {
        try {
            $applicationId = $args['id'];
            $userId = $_SESSION['user']['id'];

            $stmt = $this->db->prepare('SELECT * FROM applications WHERE id = ? AND user_id = ? AND status = "approved"');
            $stmt->execute([$applicationId, $userId]);
            $application = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$application) {
                $response->getBody()->write(json_encode(['error' => 'Certificate not found or not approved']));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }

            // Generate certificate logic here
            // This would typically involve creating a PDF with the certificate data

            $response->getBody()->write(json_encode([
                'message' => 'Certificate generated successfully',
                'certificate_url' => '/certificates/' . $applicationId . '.pdf'
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to generate certificate']));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}