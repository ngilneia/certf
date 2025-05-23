# Government Certificate Management System

A web application for managing and issuing government certificates with digital signatures and QR code verification.

## Features

- User authentication with role-based access control (Admin and Data Entry Operator)
- Password management (reset, forgot, change)
- Certificate application submission and tracking
- Document upload and verification
- Digital certificate generation with QR codes
- Certificate verification system
- Administrative dashboard for application approval
- Comprehensive reporting system

## Certificate Types

1. Delayed Birth Registration (₹20)
2. Delayed Death Registration (₹20)
3. Tax Exemption (₹10)
4. Scheduled Tribe Certificate (₹10)
5. Scheduled Caste Certificate (₹10)
6. Residential Certificate (₹10)
7. Temporary Residential Certificate (₹10)
8. Income Certificate (₹10)
9. No Income Certificate (₹10)
10. Marriage Certificate (₹10)
11. Non-Marriage Certificate (₹10)
12. Character Certificate (₹10)
13. Religion Certificate (₹10)
14. Dependency Certificate (₹10)
15. Hailing Certificate (₹10)
16. Relationship Certificate (₹10)
17. Survival Certificate (₹10)
18. Next of Kin Certificate (₹10)
19. Other Backward Class (OBC) Certificate
20. Religious & Linguistic Minority Certificate
21. Study Break Certificate

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer
- Web server (Apache/Nginx)

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd certificate
```

2. Install dependencies:
```bash
composer install
```

3. Create and configure the environment file:
```bash
cp .env.example .env
# Edit .env with your database credentials and other settings
```

4. Create the database and run migrations:
```bash
mysql -u root -p < database/schema.sql
```

5. Generate application key:
```bash
# Update APP_KEY in .env file
```

6. Set up the web server:
- Point the document root to the `public` directory
- Ensure `mod_rewrite` is enabled for Apache

7. Set proper permissions:
```bash
chmod -R 755 storage
chmod -R 755 public/uploads
```

## API Documentation

### Authentication Endpoints
- POST /api/auth/login - User login
- POST /api/auth/register - User registration
- POST /api/auth/forgot-password - Request password reset
- POST /api/auth/reset-password - Reset password
- POST /api/auth/change-password - Change password

### Certificate Endpoints
- GET /api/certificates/types - List certificate types
- POST /api/certificates/apply - Submit certificate application
- GET /api/certificates/my-applications - View user's applications
- GET /api/certificates/applications/{id} - View application details
- POST /api/certificates/upload-documents/{id} - Upload documents
- GET /api/certificates/generate/{id} - Generate certificate PDF
- GET /api/certificates/verify/{number} - Verify certificate

### Admin Endpoints
- GET /api/admin/certificate-types - Manage certificate types
- GET /api/admin/applications - View all applications
- PUT /api/admin/applications/{id}/approve - Approve application
- PUT /api/admin/applications/{id}/reject - Reject application
- GET /api/admin/reports/certificates - Certificate reports
- GET /api/admin/reports/applications - Application reports

## Security

- JWT-based authentication
- Role-based access control
- Password hashing
- Digital signatures for certificates
- QR code verification
- Secure file uploads

## License

This project is proprietary and confidential.

## Support

For support and inquiries, please contact the system administrator.#   c e r t f  
 #   c e r t f  
 