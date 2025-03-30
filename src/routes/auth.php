<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use App\Controllers\AuthController;

// Register auth routes
return function ($app) {
    $auth = new AuthController();

    // Enable CORS for public routes
    $app->options('/api/auth/{routes:.+}', function (Request $request, Response $response) {
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });

    // Public routes
    $app->post('/api/auth/login', function (Request $request, Response $response) use ($auth) {
        try {
            return $auth->login($request, $response)
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => 'Authentication failed']));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }
    });

    // Admin routes
    $app->group('/api/admin', function ($group) use ($auth) {
        $group->post('/applications/{id}/review', function (Request $request, Response $response, array $args) use ($auth) {
            return $auth->reviewApplication($request, $response, $args);
        });
    })->add(new RoleMiddleware(1)); // Admin role

    // DEO routes
    $app->group('/api/deo', function ($group) use ($auth) {
        $group->get('/applications', function (Request $request, Response $response) use ($auth) {
            return $auth->getDeoApplications($request, $response);
        });
        
        $group->get('/certificates/{id}', function (Request $request, Response $response, array $args) use ($auth) {
            return $auth->printCertificate($request, $response, $args);
        });
    })->add(new RoleMiddleware(2)); // DEO role

    $app->post('/api/auth/register', function (Request $request, Response $response) use ($auth) {
        return $auth->register($request, $response);
    });

    $app->post('/api/auth/forgot-password', function (Request $request, Response $response) use ($auth) {
        return $auth->forgotPassword($request, $response);
    });

    $app->post('/api/auth/reset-password', function (Request $request, Response $response) use ($auth) {
        return $auth->resetPassword($request, $response);
    });

    // Protected routes
    $app->group('/api', function ($group) use ($auth) {
        $group->get('/auth/me', function (Request $request, Response $response) use ($auth) {
            return $auth->me($request, $response);
        });

        $group->post('/auth/logout', function (Request $request, Response $response) use ($auth) {
            return $auth->logout($request, $response);
        });

        $group->put('/auth/change-password', function (Request $request, Response $response) use ($auth) {
            return $auth->changePassword($request, $response);
        });
    })->add(new AuthMiddleware());
};
