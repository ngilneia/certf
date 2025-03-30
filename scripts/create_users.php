<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

try {
    $db = new PDO(
        "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_DATABASE']}",
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Create admin user
    $admin = [
        'username' => 'admin',
        'email' => 'admin@gov.in',
        'password' => password_hash('Admin@123', PASSWORD_DEFAULT),
        'role_id' => 1,
        'status' => 'active'
    ];

    // Create DEO user
    $deo = [
        'username' => 'deo',
        'email' => 'deo@gov.in',
        'password' => password_hash('Deo@123', PASSWORD_DEFAULT),
        'role_id' => 2,
        'status' => 'active'
    ];

    // Insert users
    $stmt = $db->prepare('INSERT INTO users (username, email, password, role_id, status) VALUES (?, ?, ?, ?, ?)');

    // Insert admin
    $stmt->execute([
        $admin['username'],
        $admin['email'],
        $admin['password'],
        $admin['role_id'],
        $admin['status']
    ]);
    echo "Admin user created successfully!\n";

    // Insert DEO
    $stmt->execute([
        $deo['username'],
        $deo['email'],
        $deo['password'],
        $deo['role_id'],
        $deo['status']
    ]);
    echo "DEO user created successfully!\n";

    echo "\nAdmin credentials:\n";
    echo "Username: admin@gov.in\n";
    echo "Password: Admin@123\n\n";

    echo "DEO credentials:\n";
    echo "Username: deo@gov.in\n";
    echo "Password: Deo@123\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
