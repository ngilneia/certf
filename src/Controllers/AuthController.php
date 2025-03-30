<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use PHPMailer\PHPMailer\PHPMailer;
use Respect\Validation\Validator as v;

class AuthController
{
    private $db;
    private $mailer;

    public function __construct()
    {
        // Initialize secure session in constructor
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            ini_set('session.cookie_lifetime', '3600');
            ini_set('session.gc_maxlifetime', '3600');
            ini_set('session.use_strict_mode', '1');
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_secure', '1');
            ini_set('session.cookie_samesite', 'Strict');
            ini_set('session.use_only_cookies', '1');
            
            session_start();
            if (!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
        }
        
        $this->db = new \PDO(
            "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD']
        );
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $this->mailer = new PHPMailer(true);
        $this->setupMailer();
    }

    private function setupMailer()
    {
        $this->mailer->isSMTP();
        $this->mailer->Host = $_ENV['MAIL_HOST'];
        $this->mailer->Port = $_ENV['MAIL_PORT'];
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $_ENV['MAIL_USERNAME'];
        $this->mailer->Password = $_ENV['MAIL_PASSWORD'];
        $this->mailer->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
    }

    public function login(Request $request, Response $response)
    {
        try {
            // Ensure session is active and secure
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            
            // Rate limiting check
            $ip = $_SERVER['REMOTE_ADDR'];
            $timestamp = time();
            $attempts_key = "login_attempts_{$ip}";
            $lockout_key = "login_lockout_{$ip}";
            
            if (isset($_SESSION[$lockout_key]) && $_SESSION[$lockout_key] > $timestamp) {
                $wait_time = ceil(($_SESSION[$lockout_key] - $timestamp) / 60);
                $response->getBody()->write(json_encode(['error' => ['lockout' => "Too many failed attempts. Please wait {$wait_time} minutes before trying again."]]));
                return $response->withStatus(429);
            }
            
            // Track login attempts
            if (!isset($_SESSION[$attempts_key])) {
                $_SESSION[$attempts_key] = ['count' => 0, 'first_attempt' => $timestamp];
            }
            
            // Reset attempts if more than 30 minutes have passed
            if ($timestamp - $_SESSION[$attempts_key]['first_attempt'] > 1800) {
                $_SESSION[$attempts_key] = ['count' => 0, 'first_attempt' => $timestamp];
            }
            
            // Regenerate session ID to prevent session fixation
            if (!isset($_SESSION['last_regeneration']) || $timestamp - $_SESSION['last_regeneration'] >= 1800) {
                session_regenerate_id(true);
                $_SESSION['last_regeneration'] = $timestamp;
            }
            
            // Session-based authentication only
            $data = $request->getParsedBody();
            if (!is_array($data)) {
                $data = json_decode($request->getBody()->getContents(), true) ?? [];
            }
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate');

            // Validate input data
            $errors = [];
            if (empty($data['email'])) {
                $errors['email'] = 'Email is required';
            } elseif (!v::email()->validate($data['email'])) {
                $errors['email'] = 'Invalid email format';
            }

            if (empty($data['password'])) {
                $errors['password'] = 'Password is required';
            }

            if (!empty($errors)) {
                $response->getBody()->write(json_encode(['error' => $errors]));
                return $response->withStatus(400);
            }

            try {
                // Check if user exists and is active
                $stmt = $this->db->prepare('SELECT id, email, password, role_id, status FROM users WHERE email = ?');
                $stmt->execute([$data['email']]);
                $user = $stmt->fetch(\PDO::FETCH_ASSOC);

                if (!$user) {
                    // Use a generic error message for security (avoid email enumeration)
                    $response->getBody()->write(json_encode(['error' => ['credentials' => 'Invalid email or password']]));
                    return $response->withStatus(401);
                }

                if ($user['status'] !== 'active') {
                    $response->getBody()->write(json_encode(['error' => ['account' => 'Account is not active. Please contact administrator']]));
                    return $response->withStatus(401);
                }

                // Verify password with constant-time comparison
                if (!password_verify($data['password'], $user['password'])) {
                    error_log('Failed login attempt for email: ' . $data['email']);
                    
                    // Increment failed attempts counter
                    $_SESSION[$attempts_key]['count']++;
                    
                    // If too many failed attempts, implement lockout
                    if ($_SESSION[$attempts_key]['count'] >= 5) {
                        $_SESSION[$lockout_key] = $timestamp + 900; // 15 minutes lockout
                        $response->getBody()->write(json_encode(['error' => ['lockout' => 'Too many failed attempts. Please wait 15 minutes before trying again.']])); 
                        return $response->withStatus(429);
                    }
                    
                    // Use generic error message for wrong password to match our approach for invalid email
                    $response->getBody()->write(json_encode(['error' => ['credentials' => 'Invalid email or password']]));
                    return $response->withStatus(401);
                }
                
                // Reset login attempts on successful login
                unset($_SESSION[$attempts_key]);
                unset($_SESSION[$lockout_key]);

                // Initialize session with secure parameters and store user data
                session_regenerate_id(true); // Regenerate session ID after successful login
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'role_id' => $user['role_id'],
                    'last_activity' => time(),
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] // Store user agent for additional security
                ];
                
                session_write_close(); // Ensure session data is written
                
                // Determine redirect URL based on role
                $redirectUrl = $user['role_id'] === 1 ? '/admin/dashboard' : '/dashboard';

                $response->getBody()->write(json_encode([
                    'user' => [
                        'email' => $user['email'],
                        'role_id' => $user['role_id']
                    ],
                    'redirect' => $redirectUrl,
                    'message' => 'Login successful'
                ]));
                return $response->withStatus(200);
            } catch (\PDOException $e) {
                error_log('Database error during login: ' . $e->getMessage());
                $response->getBody()->write(json_encode(['error' => ['database' => 'Database connection error. Please try again later']]));
                return $response->withStatus(500);
            } catch (\Exception $e) {
                error_log('Unexpected error during login: ' . $e->getMessage());
                $response->getBody()->write(json_encode(['error' => ['system' => 'An unexpected error occurred. Please try again later']]));
                return $response->withStatus(500);
            }
        } catch (\Exception $e) {
            error_log('Outer error during login: ' . $e->getMessage());
            $response->getBody()->write(json_encode(['error' => ['system' => 'An unexpected error occurred. Please try again later']]));
            return $response->withStatus(500);
        }
    }

    public function register(Request $request, Response $response)
    {
        try {
            $data = $request->getParsedBody();
            if (!is_array($data)) {
                $data = json_decode($request->getBody()->getContents(), true) ?? [];
            }
            $response = $response->withHeader('Content-Type', 'application/json');

            if (!is_array($data)) {
                $response->getBody()->write(json_encode(['error' => ['request' => 'Invalid request format']]));
                return $response->withStatus(400);
            }

            $validationResult = $this->validateRegistrationData($data);

            if ($validationResult !== true) {
                $response->getBody()->write(json_encode($validationResult));
                return $response->withStatus(400);
            }

            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            // Check if role exists
            $roleStmt = $this->db->prepare('SELECT COUNT(*) FROM roles WHERE id = ?');
            $roleStmt->execute([$data['role_id']]);
            if ($roleStmt->fetchColumn() == 0) {
                $response = $response->withHeader('Content-Type', 'application/json');
                $response->getBody()->write(json_encode(['error' => ['role_id' => 'Invalid role selected. Please select a valid role.']]));
                return $response->withStatus(400);
            }

            // Check if email already exists
            $emailStmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
            $emailStmt->execute([$data['email']]);
            if ($emailStmt->fetchColumn() > 0) {
                $response->getBody()->write(json_encode(['error' => ['email' => 'This email address is already registered. Please use a different email or try logging in.']]));
                return $response->withStatus(409);
            }

            // Check if username already exists
            $usernameStmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
            $usernameStmt->execute([$data['username']]);
            if ($usernameStmt->fetchColumn() > 0) {
                $response->getBody()->write(json_encode(['error' => ['username' => 'This username is already taken. Please choose a different username.']]));
                return $response->withStatus(409);
            }

            // Attempt to insert the new user
            $stmt = $this->db->prepare('INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)');
            $stmt->execute([$data['username'], $data['email'], $hashedPassword, $data['role_id']]);

            $response = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode(['message' => 'Registration successful']));
            return $response->withStatus(201);
        } catch (\PDOException $e) {
            // Log the error for debugging
            error_log('Registration error: ' . $e->getMessage());
            $response = $response->withHeader('Content-Type', 'application/json');

            // Check for specific error conditions
            if ($e->getCode() == '23000') { // Duplicate entry error
                if (strpos($e->getMessage(), 'users_email_unique') !== false) {
                    $response->getBody()->write(json_encode(['error' => ['email' => 'Email address is already registered']]));
                    return $response->withStatus(409);
                } else if (strpos($e->getMessage(), 'users_username_unique') !== false) {
                    $response->getBody()->write(json_encode(['error' => ['username' => 'Username is already taken']]));
                    return $response->withStatus(409);
                }
            }

            // Handle database connection errors
            if ($e->getCode() == '2002' || $e->getCode() == '2003' || $e->getCode() == '2006') {
                $response->getBody()->write(json_encode(['error' => ['database' => 'Database connection error. Please try again later.']]));
                return $response->withStatus(503);
            }

            // Handle other database errors
            $response->getBody()->write(json_encode(['error' => ['general' => 'An error occurred during registration. Please try again later.']]));
            return $response->withStatus(500);
        }
    }

    public function forgotPassword(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        if (!v::email()->validate($data['email'])) {
            $response->getBody()->write(json_encode(['error' => 'Invalid email']));
            return $response->withStatus(400);
        }

        $token = bin2hex(random_bytes(32));
        $stmt = $this->db->prepare('INSERT INTO password_resets (email, token) VALUES (?, ?)');
        $stmt->execute([$data['email'], $token]);

        $resetLink = $_ENV['APP_URL'] . '/reset-password?token=' . $token;
        $this->sendPasswordResetEmail($data['email'], $resetLink);

        $response->getBody()->write(json_encode(['message' => 'Password reset instructions sent']));
        return $response->withStatus(200);
    }

    public function resetPassword(Request $request, Response $response)
    {
        $data = $request->getParsedBody();

        if (empty($data['token']) || empty($data['password'])) {
            $response->getBody()->write(json_encode(['error' => 'Invalid request']));
            return $response->withStatus(400);
        }

        $stmt = $this->db->prepare('SELECT email FROM password_resets WHERE token = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)');
        $stmt->execute([$data['token']]);
        $reset = $stmt->fetch();

        if (!$reset) {
            $response->getBody()->write(json_encode(['error' => 'Invalid or expired token']));
            return $response->withStatus(400);
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $this->db->prepare('UPDATE users SET password = ? WHERE email = ?');
        $stmt->execute([$hashedPassword, $reset['email']]);

        $stmt = $this->db->prepare('DELETE FROM password_resets WHERE email = ?');
        $stmt->execute([$reset['email']]);

        $response->getBody()->write(json_encode(['message' => 'Password reset successful']));
        return $response->withStatus(200);
    }

    public function changePassword(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $user = $request->getAttribute('user');

        if (empty($data['current_password']) || empty($data['new_password'])) {
            $response->getBody()->write(json_encode(['error' => 'Invalid request']));
            return $response->withStatus(400);
        }

        $stmt = $this->db->prepare('SELECT password FROM users WHERE id = ?');
        $stmt->execute([$user['id']]);
        $currentUser = $stmt->fetch();

        if (!password_verify($data['current_password'], $currentUser['password'])) {
            $response->getBody()->write(json_encode(['error' => 'Current password is incorrect']));
            return $response->withStatus(400);
        }

        $hashedPassword = password_hash($data['new_password'], PASSWORD_DEFAULT);
        $stmt = $this->db->prepare('UPDATE users SET password = ? WHERE id = ?');
        $stmt->execute([$hashedPassword, $user['id']]);

        $response->getBody()->write(json_encode(['message' => 'Password changed successfully']));
        return $response->withStatus(200);
    }



    private function validateRegistrationData($data)
    {
        $errors = [];

        // Sanitize and validate input data
        $username = htmlspecialchars(trim($data['username'] ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $email = filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $data['password'] ?? '';
        $roleId = filter_var($data['role_id'] ?? '', FILTER_SANITIZE_NUMBER_INT);

        // Username validation
        if (empty($username)) {
            $errors['username'] = 'Username is required';
        } elseif (strlen($username) < 3) {
            $errors['username'] = 'Username must be at least 3 characters long';
        } elseif (strlen($username) > 50) {
            $errors['username'] = 'Username cannot exceed 50 characters';
        }

        // Email validation
        if (!v::email()->validate($email)) {
            $errors['email'] = 'Please enter a valid email address';
        } else {
            // Check if email already exists
            try {
                $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
                $stmt->execute([$email]);
                if ($stmt->fetchColumn() > 0) {
                    $errors['email'] = 'Email address is already registered';
                }
            } catch (\PDOException $e) {
                error_log('Email validation error: ' . $e->getMessage());
                $errors['email'] = 'Error validating email. Please try again.';
            }
        }

        // Password strength validation (OWASP compliant)
        $passwordErrors = [];
        if (strlen($password) < 8) {
            $passwordErrors[] = 'at least 8 characters long';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $passwordErrors[] = 'one uppercase letter';
        }
        if (!preg_match('/[a-z]/', $password)) {
            $passwordErrors[] = 'one lowercase letter';
        }
        if (!preg_match('/[0-9]/', $password)) {
            $passwordErrors[] = 'one number';
        }
        if (!preg_match('/[!@#$%^&*()-_=+{};:,<.>]/', $password)) {
            $passwordErrors[] = 'one special character';
        }
        if (preg_match('/\s/', $password)) {
            $passwordErrors[] = 'no whitespace';
        }

        if (!empty($passwordErrors)) {
            $errors['password'] = 'Password must contain ' . implode(', ', $passwordErrors);
        }

        // Role validation
        if (!in_array($roleId, ['1', '2'], true)) {
            $errors['role_id'] = 'Invalid role selected';
        }

        return empty($errors) ? true : ['error' => $errors];
    }

    private function sendPasswordResetEmail($email, $resetLink)
    {
        try {
            $this->mailer->addAddress($email);
            $this->mailer->Subject = 'Password Reset Instructions';
            $this->mailer->Body = "Click the following link to reset your password: {$resetLink}";
            $this->mailer->send();
        } catch (\Exception $e) {
            // Log the error but don't expose it to the user
            error_log('Failed to send password reset email: ' . $e->getMessage());
        }
    }

    public function me(Request $request, Response $response)
    {
        $user = $request->getAttribute('user');
        $response->getBody()->write(json_encode($user));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function logout(Request $request, Response $response)
    {
        // Clear session data
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        // Clear any cookies
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        $response = $response->withHeader('Content-Type', 'application/json')
            ->withHeader('Clear-Site-Data', '"cache", "cookies", "storage"');

        $response->getBody()->write(json_encode([
            'message' => 'Logged out successfully',
            'status' => true
        ]));
        return $response->withStatus(200);
    }
}
