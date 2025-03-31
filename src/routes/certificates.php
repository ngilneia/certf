<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controllers\CertificateController;
use App\Middleware\AuthMiddleware;

return function ($app) {
    $certificate = new CertificateController();

    $app->group('/api/certificates', function ($group) use ($certificate) {
        // Get available certificate types
        $group->get('/types', function (Request $request, Response $response) use ($certificate) {
            try {
                // Let the controller handle the Content-Type header
                return $certificate->getAvailableTypes($request, $response);
            } catch (\Exception $e) {
                $response->getBody()->write(json_encode(['error' => 'Failed to fetch certificate types']));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(500);
            }
        });

        // Get specific certificate type details
        $group->get('/types/{id}', function (Request $request, Response $response, array $args) use ($certificate) {
            return $certificate->getCertificateType($request, $response, $args);
        });

        // Submit new certificate application
        $group->post('/apply', function (Request $request, Response $response) use ($certificate) {
            return $certificate->submitApplication($request, $response);
        });

        // Get applications submitted by current user
        $group->get('/my-applications', function (Request $request, Response $response) use ($certificate) {
            return $certificate->getMyApplications($request, $response);
        });

        // Get specific application details
        $group->get('/applications/{id}', function (Request $request, Response $response, array $args) use ($certificate) {
            return $certificate->getApplicationDetails($request, $response, $args);
        });

        // Generate certificate PDF
        $group->get('/generate/{id}', function (Request $request, Response $response, array $args) use ($certificate) {
            return $certificate->generateCertificate($request, $response, $args);
        });

        // Verify certificate authenticity
        $group->get('/verify/{number}', function (Request $request, Response $response, array $args) use ($certificate) {
            return $certificate->verifyCertificate($request, $response, $args);
        });

        // Upload required documents
        $group->post('/upload-documents/{application_id}', function (Request $request, Response $response, array $args) use ($certificate) {
            return $certificate->uploadDocuments($request, $response, $args);
        });
        
        // Update application
        $group->put('/applications/{id}', function (Request $request, Response $response, array $args) use ($certificate) {
            return $certificate->updateApplication($request, $response, $args);
        });
        
        // Delete application
        $group->delete('/applications/{id}', function (Request $request, Response $response, array $args) use ($certificate) {
            return $certificate->deleteApplication($request, $response, $args);
        });

        // Get certificate preview
        $group->get('/preview/{id}', function (Request $request, Response $response, array $args) use ($certificate) {
            return $certificate->previewCertificate($request, $response, $args);
        });
    })->add(new AuthMiddleware());

    // Public route for certificate verification
    $app->get('/verify/{number}', function (Request $request, Response $response, array $args) use ($certificate) {
        return $certificate->publicVerifyCertificate($request, $response, $args);
    });
};
