<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Enable error reporting and logging for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configure error log
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../../logs/auth.log');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Log request details
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestHeaders = getallheaders();
error_log(sprintf(
    "[%s] Login attempt - Method: %s, IP: %s, User-Agent: %s",
    date('Y-m-d H:i:s'),
    $requestMethod,
    $_SERVER['REMOTE_ADDR'],
    $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
));

// Handle preflight OPTIONS request
if ($requestMethod === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST method
if ($requestMethod !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

try {
    // Get and log JSON input (excluding sensitive data)
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    error_log(sprintf(
        "[%s] Request payload received - Email: %s",
        date('Y-m-d H:i:s'),
        $data['email'] ?? 'not provided'
    ));

    if (!$data) {
        error_log(sprintf(
            "[%s] Invalid JSON data received",
            date('Y-m-d H:i:s')
        ));
        throw new Exception('Invalid request data');
    }

    // Validate input
    if (empty($data['email']) || empty($data['password'])) {
        throw new Exception('Email and password are required');
    }

    // Database connection
    require_once __DIR__ . '/../../../config/database.php';
    
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $data['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        error_log(sprintf(
            "[%s] Failed login attempt - Email not found: %s",
            date('Y-m-d H:i:s'),
            $data['email']
        ));
        throw new Exception('Invalid credentials');
    }

    $user = $result->fetch_assoc();

    // Verify password
    if (!password_verify($data['password'], $user['password'])) {
        error_log(sprintf(
            "[%s] Failed login attempt - Invalid password for email: %s",
            date('Y-m-d H:i:s'),
            $data['email']
        ));
        throw new Exception('Invalid credentials');
    }

    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['authenticated'] = true;
    
    // Set session cookie parameters
    session_set_cookie_params([
        'lifetime' => 86400, // 24 hours
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    
    // Close session write
    session_write_close();

    // Log successful login
    error_log(sprintf(
        "[%s] Successful login - User ID: %s, Email: %s",
        date('Y-m-d H:i:s'),
        $user['id'],
        $user['email']
    ));

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'redirect' => '/dashboard'
    ]);

} catch (Exception $e) {
    $statusCode = 401;
    $errorMessage = $e->getMessage();
    
    // Log the error with stack trace
    error_log(sprintf(
        "[%s] Authentication error: %s\nStack trace: %s",
        date('Y-m-d H:i:s'),
        $errorMessage,
        $e->getTraceAsString()
    ));

    http_response_code($statusCode);
    echo json_encode([
        'success' => false,
        'message' => $errorMessage,
        'error_code' => $statusCode
    ]);
}

// Close database connection if it exists
if (isset($conn)) {
    $conn->close();
}
?>