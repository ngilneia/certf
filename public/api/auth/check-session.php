<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

header('Content-Type: application/json');

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is authenticated
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    http_response_code(200);
    echo json_encode(['status' => 'authenticated']);
} else {
    http_response_code(401);
    echo json_encode(['status' => 'unauthenticated']);
}