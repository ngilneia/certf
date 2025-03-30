<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthMiddleware
{
    private $rateLimiter = [];
    private $maxRequests = 180; // Max requests per minute
    private $resetTime = 60; // Reset window in seconds
    private $registrationMaxRequests = 10; // Max registration attempts per minute

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // Rate limiting
        $clientIp = $request->getServerParams()['REMOTE_ADDR'] ?? '0.0.0.0';
        if (!$this->checkRateLimit($clientIp)) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Too many requests']));
            return $response->withStatus(429)
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Retry-After', $this->resetTime);
        }

        // Session-based Authentication
        $path = $request->getUri()->getPath();
        
        // Skip authentication for public routes
        $publicRoutes = ['/', '/login', '/register', '/api/auth/login', '/api/auth/register', '/css', '/js', '/images', '/assets'];
        
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
                return $response->withHeader('Location', '/login')
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



    private function checkRateLimit(string $clientIp): bool
    {
        $now = time();
        $path = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $isRegistration = strpos($path, '/api/auth/register') !== false;
        $maxAllowedRequests = $isRegistration ? $this->registrationMaxRequests : $this->maxRequests;

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
