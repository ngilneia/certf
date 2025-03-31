<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Src\Controllers\CertificateController;
use Src\Middleware\AuthMiddleware;

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    // Initialize auth middleware
    $authMiddleware = new AuthMiddleware();
    $authMiddleware->handle();

    // Initialize controller
    $controller = new CertificateController();

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            // Get user's applications
            $applications = $controller->getUserApplications();
            echo json_encode(['success' => true, 'data' => $applications]);
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while processing your request',
        'error' => $e->getMessage()
    ]);
}