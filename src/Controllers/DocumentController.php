<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DocumentController
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

    public function viewDocument(Request $request, Response $response, array $args)
    {
        try {
            // Get document path from the database
            $stmt = $this->db->prepare(
                'SELECT d.file_path, d.file_type 
                FROM application_documents d 
                JOIN applications a ON d.application_id = a.id 
                WHERE d.id = ? AND (a.status = "approved" OR a.status = "pending")'
            );
            $stmt->execute([$args['id']]);
            $document = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$document) {
                $response->getBody()->write(json_encode(['error' => 'Document not found or access denied']));
                return $response->withStatus(404);
            }

            $filePath = __DIR__ . '/../../public/uploads/' . $document['file_path'];
            
            if (!file_exists($filePath)) {
                $response->getBody()->write(json_encode(['error' => 'Document file not found']));
                return $response->withStatus(404);
            }

            // Set appropriate content type
            $contentType = $document['file_type'];
            if (!$contentType) {
                $contentType = mime_content_type($filePath);
            }

            // Read file content
            $fileContent = file_get_contents($filePath);
            
            return $response
                ->withHeader('Content-Type', $contentType)
                ->withHeader('Content-Disposition', 'inline; filename="' . basename($filePath) . '"')
                ->withBody(new \Slim\Psr7\Stream(fopen($filePath, 'r')));
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Failed to retrieve document']));
            return $response->withStatus(500);
        }
    }
}