<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware
{
    private $rateLimiter = [];
    private $maxRequests = 60; // Max requests per minute (reduced for better security)
    private $resetTime = 60; // Reset window in seconds
    private $registrationMaxRequests = 5; // Max registration attempts per minute (reduced)
    private $loginMaxRequests = 5; // Max login attempts per minute
    private $sessionTimeout = 1800; // 30 minutes session timeout

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // Ensure secure session configuration
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_only_cookies', '1');
            ini_set('session.cookie_samesite', 'Lax');
            ini_set('session.use_strict_mode', '1');
            session_start();
        }

        // Rate limiting
        $clientIp = $request->getServerParams()['REMOTE_ADDR'] ?? '0.0.0.0';
        $path = $request->getUri()->getPath();
        $isLogin = strpos($path, '/api/auth/login') !== false;

        if (!$this->checkRateLimit($clientIp, $isLogin)) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Too many requests']));
            return $response->withStatus(429)
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Retry-After', $this->resetTime);
        }

        // CSRF Protection
        if ($request->getMethod() === 'POST' || $request->getMethod() === 'PUT' || $request->getMethod() === 'DELETE') {
            $csrfToken = $request->getHeaderLine('X-CSRF-TOKEN');
            if (!isset($_SESSION['csrf_token']) || $csrfToken !== $_SESSION['csrf_token']) {
                $response = new \Slim\Psr7\Response();
                $response = $response->withStatus(403)
                    ->withHeader('Content-Type', 'application/json');
                $response->getBody()->write(json_encode(['error' => 'Invalid CSRF token']));
                return $response;
            }
        }

        // Session-based Authentication
        $path = $request->getUri()->getPath();
        
        // Skip authentication for public routes and API endpoints
        $publicRoutes = [
            '/', '/login', '/register', 
            '/api/auth/login.php', '/api/auth/register', '/api/auth/check-session.php',
            '/css', '/js', '/images', '/assets', '/fonts'
        ];
        
        // Protected routes that require authentication
        $protectedRoutes = [
            '/admin_applications',
            '/admin_review',
            '/application_form',
            '/applications_list',
            '/certificate_template',
            '/certificates_list',
            '/dashboard',
            '/verify'
        ];

        // Check for session timeout
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            unset($_SESSION['user']);
            unset($_SESSION['last_activity']);
        }
        if (isset($_SESSION['user'])) {
            $_SESSION['last_activity'] = time();
        }
        
        // Check if current path is a protected route
        foreach ($protectedRoutes as $route) {
            if (strpos($path, $route) !== false) {
                if (!isset($_SESSION['user'])) {
                    $response = new \Slim\Psr7\Response();
                    return $response->withHeader('Location', '/login')
                        ->withStatus(302);
                }
                break;
            }
        }
        
        // Allow access to static assets with any path extension
        $path_parts = explode('/', $path);
        $file_extension = pathinfo(end($path_parts), PATHINFO_EXTENSION);
        $static_extensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'eot'];
        
        // Check if path contains static asset directories
        $static_dirs = ['css', 'js', 'images', 'assets', 'fonts'];
        $path_contains_static = false;
        foreach ($static_dirs as $dir) {
            if (strpos($path, '/' . $dir . '/') !== false) {
                $path_contains_static = true;
                break;
            }
        }
        
        // Allow access to static assets by extension or directory
        if (in_array($file_extension, $static_extensions) || $path_contains_static) {
            return $handler->handle($request);
        }

        if (!in_array($path, $publicRoutes)) {
            if (!isset($_SESSION['user'])) {
                $response = new \Slim\Psr7\Response();
                $returnUrl = urlencode($path);
                return $response->withHeader('Location', "/login?return_url={$returnUrl}")
                    ->withStatus(302);
            }
            
            // Set user data in request attributes
            $request = $request->withAttribute('user', $_SESSION['user']);
        }

        $response = $handler->handle($request);

        // Security Headers (OWASP Recommendations)
        return $response
            ->withHeader('Access-Control-Allow-Origin', $_ENV['APP_URL'] ?? '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('X-Content-Type-Options', 'nosniff')
            ->withHeader('X-Frame-Options', 'DENY')
            ->withHeader('X-XSS-Protection', '1; mode=block')
            ->withHeader('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; img-src 'self' https://ui-avatars.com; connect-src 'self';")
            ->withHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains')
            ->withHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }



    private function checkRateLimit(string $clientIp, bool $isLogin = false): bool
    {
        $now = time();
        $path = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $isRegistration = strpos($path, '/api/auth/register') !== false;
        
        if ($isLogin) {
            $maxAllowedRequests = $this->loginMaxRequests;
        } elseif ($isRegistration) {
            $maxAllowedRequests = $this->registrationMaxRequests;
        } else {
            $maxAllowedRequests = $this->maxRequests;
        }

        if (!isset($_SESSION['rate_limiter'][$clientIp])) {
            $_SESSION['rate_limiter'][$clientIp] = [
                'count' => 1,
                'reset' => $now + $this->resetTime,
                'registration_count' => $isRegistration ? 1 : 0
            ];
            return true;
        }

        if ($now > $_SESSION['rate_limiter'][$clientIp]['reset']) {
            $_SESSION['rate_limiter'][$clientIp] = [
                'count' => 1,
                'reset' => $now + $this->resetTime,
                'registration_count' => $isRegistration ? 1 : 0
            ];
            return true;
        }

        if ($isRegistration) {
            if (!isset($_SESSION['rate_limiter'][$clientIp]['registration_count'])) {
                $_SESSION['rate_limiter'][$clientIp]['registration_count'] = 0;
            }
            if ($_SESSION['rate_limiter'][$clientIp]['registration_count'] >= $maxAllowedRequests) {
                return false;
            }
            $_SESSION['rate_limiter'][$clientIp]['registration_count']++;
        }

        if ($_SESSION['rate_limiter'][$clientIp]['count'] >= $maxAllowedRequests) {
            return false;
        }

        $_SESSION['rate_limiter'][$clientIp]['count']++;
        return true;
    }
}
