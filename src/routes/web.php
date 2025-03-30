<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;
use App\Middleware\AuthMiddleware;

return function ($app) {
    // Homepage route
    $app->get('/', function (Request $request, Response $response, $args) use ($app) {
        $view = $app->getContainer()->get('view');
        return $view->render($response, 'login.php');
    });

    // Login route
    $app->get('/login', function (Request $request, Response $response, $args) use ($app) {
        $view = $app->getContainer()->get('view');
        return $view->render($response, 'login.php');
    });

    // Registration page route
    $app->get('/register', function (Request $request, Response $response, $args) use ($app) {
        $view = $app->getContainer()->get('view');
        return $view->render($response, 'register.php');
    });

    // Dashboard route
    $app->get('/dashboard', function (Request $request, Response $response, $args) use ($app) {
        $view = $app->getContainer()->get('view');
        return $view->render($response, 'dashboard.php');
    })->add(new \App\Middleware\AuthMiddleware());

    // Applications list route
    $app->get('/applications', function (Request $request, Response $response, $args) use ($app) {
        $view = $app->getContainer()->get('view');
        return $view->render($response, 'applications_list.php');
    })->add(new \App\Middleware\AuthMiddleware());

    // Application form route
    $app->get('/apply', function (Request $request, Response $response, $args) use ($app) {
        $view = $app->getContainer()->get('view');
        return $view->render($response, 'application_form.php');
    })->add(new \App\Middleware\AuthMiddleware());

    // Certificates list route
    $app->get('/certificates', function (Request $request, Response $response, $args) use ($app) {
        $view = $app->getContainer()->get('view');
        return $view->render($response, 'certificates_list.php');
    })->add(new \App\Middleware\AuthMiddleware());

    // Admin review route
    $app->get('/admin_review', function (Request $request, Response $response, $args) use ($app) {
        $view = $app->getContainer()->get('view');
        return $view->render($response, 'admin_review.php');
    })->add(new \App\Middleware\AuthMiddleware());

    // Fallback route for undefined routes
    $app->any('{route:.*}', function (Request $request, Response $response) {
        $response->getBody()->write('Page not found');
        return $response->withStatus(404);
    });
};
