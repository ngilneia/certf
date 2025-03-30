<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\AdminController;
use App\Middleware\AuthMiddleware;

return function ($app) {
    $admin = new AdminController();

    $app->group('/api/admin', function ($group) use ($admin) {
        // Certificate Types Management
        $group->get('/certificate-types', function (Request $request, Response $response) use ($admin) {
            return $admin->listCertificateTypes($request, $response);
        });

        $group->post('/certificate-types', function (Request $request, Response $response) use ($admin) {
            return $admin->createCertificateType($request, $response);
        });

        $group->put('/certificate-types/{id}', function (Request $request, Response $response, array $args) use ($admin) {
            return $admin->updateCertificateType($request, $response, $args);
        });

        $group->delete('/certificate-types/{id}', function (Request $request, Response $response, array $args) use ($admin) {
            return $admin->deleteCertificateType($request, $response, $args);
        });

        // User Management
        $group->get('/users', function (Request $request, Response $response) use ($admin) {
            return $admin->listUsers($request, $response);
        });

        $group->put('/users/{id}', function (Request $request, Response $response, array $args) use ($admin) {
            return $admin->updateUser($request, $response, $args);
        });

        $group->delete('/users/{id}', function (Request $request, Response $response, array $args) use ($admin) {
            return $admin->deleteUser($request, $response, $args);
        });

        // Application Management
        $group->get('/applications', function (Request $request, Response $response) use ($admin) {
            return $admin->listApplications($request, $response);
        });

        $group->get('/applications/{id}', function (Request $request, Response $response, array $args) use ($admin) {
            return $admin->getApplication($request, $response, $args);
        });

        $group->put('/applications/{id}/approve', function (Request $request, Response $response, array $args) use ($admin) {
            return $admin->approveApplication($request, $response, $args);
        });

        $group->put('/applications/{id}/reject', function (Request $request, Response $response, array $args) use ($admin) {
            return $admin->rejectApplication($request, $response, $args);
        });

        // Reports
        $group->get('/reports/certificates', function (Request $request, Response $response) use ($admin) {
            return $admin->getCertificateReport($request, $response);
        });

        $group->get('/reports/applications', function (Request $request, Response $response) use ($admin) {
            return $admin->getApplicationReport($request, $response);
        });
    })->add(new AuthMiddleware());
};
