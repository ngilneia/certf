<?php
require __DIR__ . '/config/database.php';

$result = $conn->query("SELECT id, email, password FROM users WHERE email = 'admin@admin.com'");

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo 'ID: ' . $row['id'] . "\n";
        echo 'Email: ' . $row['email'] . "\n";
        echo 'Password Hash: ' . $row['password'] . "\n";
        
        // Test password verification
        $password = 'Admin@123';
        if (password_verify($password, $row['password'])) {
            echo "Password verification: SUCCESS\n";
        } else {
            echo "Password verification: FAILED\n";
        }
    }
} else {
    echo "No results found\n";
}

$conn->close();
?>