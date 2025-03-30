<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Psr7\Response;

class RoleMiddleware implements MiddlewareInterface
{
    private $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role_id'])) {
            $response = new Response();
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate');
            $response->getBody()->write(json_encode([
                'error' => ['auth' => 'Unauthorized access'],
                'redirect' => '/login'
            ]));
            return $response->withStatus(401);
        }

        if ((string)$_SESSION['user']['role_id'] !== (string)$this->role) {
            $response = new Response();
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate');
            $response->getBody()->write(json_encode([
                'error' => ['auth' => 'Access forbidden'],
                'redirect' => (string)$_SESSION['user']['role_id'] === '1' ? '/admin/dashboard' : '/dashboard'
            ]));
            return $response->withStatus(403);
        }

        return $handler->handle($request);
    }
}