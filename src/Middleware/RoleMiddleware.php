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
    private $permissions;

    public function __construct($role)
    {
        $this->role = $role;
        $this->permissions = [
            1 => [ // Admin permissions
                'can_enter_application' => true,
                'can_update_application' => true,
                'can_make_remarks' => true,
                'can_print_application' => true,
                'can_export_pdf' => true,
                'can_review_application' => true,
                'can_approve_application' => true,
                'can_reject_application' => true,
                'can_delete_application' => true,
                'can_manage_users' => true,
                'can_view_audit_logs' => true
            ],
            2 => [ // DEO permissions
                'can_enter_application' => true,
                'can_update_application' => true,
                'can_make_remarks' => true,
                'can_print_application' => true,
                'can_export_pdf' => true,
                'can_review_application' => false,
                'can_approve_application' => false,
                'can_reject_application' => false,
                'can_delete_application' => false,
                'can_manage_users' => false,
                'can_view_audit_logs' => false
            ]
        ];
    }

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Check if user is authenticated
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

        $userRole = (int)$_SESSION['user']['role_id'];
        $requestedRole = (int)$this->role;

        // Check if user has the required role
        if ($userRole !== $requestedRole) {
            $response = new Response();
            $response = $response->withHeader('Content-Type', 'application/json');
            $response = $response->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate');
            $response->getBody()->write(json_encode([
                'error' => ['auth' => 'Access forbidden'],
                'redirect' => $userRole === 1 ? '/admin/dashboard' : '/dashboard'
            ]));
            return $response->withStatus(403);
        }

        // Check specific permissions based on the request path and method
        $path = $request->getUri()->getPath();
        $method = $request->getMethod();

        // Add request permissions to the request attributes for use in controllers
        $request = $request->withAttribute('user_permissions', $this->permissions[$userRole]);

        // Specific permission checks based on path
        if (strpos($path, '/applications/delete') !== false && !$this->permissions[$userRole]['can_delete_application']) {
            $response = new Response();
            $response = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode([
                'error' => ['permission' => 'You do not have permission to delete applications']
            ]));
            return $response->withStatus(403);
        }

        if (strpos($path, '/applications/review') !== false && !$this->permissions[$userRole]['can_review_application']) {
            $response = new Response();
            $response = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode([
                'error' => ['permission' => 'You do not have permission to review applications']
            ]));
            return $response->withStatus(403);
        }

        if ((strpos($path, '/applications/approve') !== false || strpos($path, '/applications/reject') !== false) 
            && !$this->permissions[$userRole]['can_approve_application']) {
            $response = new Response();
            $response = $response->withHeader('Content-Type', 'application/json');
            $response->getBody()->write(json_encode([
                'error' => ['permission' => 'You do not have permission to approve/reject applications']
            ]));
            return $response->withStatus(403);
        }

        // Add more specific permission checks as needed

        return $handler->handle($request);
    }
}