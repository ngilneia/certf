<?php

use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Slim\Views\PhpRenderer;

require __DIR__ . '/../vendor/autoload.php';

// Start session and initialize CSRF token
session_start();

// Always regenerate CSRF token if it's a new session or if it doesn't exist
if (session_status() === PHP_SESSION_ACTIVE && (!isset($_SESSION['csrf_token']) || !isset($_SESSION['last_activity']) || (time() - $_SESSION['last_activity'] > 3600))) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Create Container
$containerBuilder = new ContainerBuilder();
$container = $containerBuilder->build();

// Set container to create App with on AppFactory
AppFactory::setContainer($container);
$app = AppFactory::create();

// Add Error Middleware
$app->addErrorMiddleware(true, true, true);

// Add Routing Middleware
$app->addRoutingMiddleware();

// Register view component
$container->set('view', function () {
    return new PhpRenderer(__DIR__ . '/../templates');
});

// Register routes
$routes = require __DIR__ . '/../src/routes/auth.php';
$routes($app);

$routes = require __DIR__ . '/../src/routes/admin.php';
$routes($app);

$routes = require __DIR__ . '/../src/routes/certificates.php';
$routes($app);

// Register web routes with catch-all route last
$routes = require __DIR__ . '/../src/routes/web.php';
$routes($app);

// Run app
$app->run();
