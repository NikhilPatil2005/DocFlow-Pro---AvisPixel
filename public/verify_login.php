<?php
require_once __DIR__ . '/../config/db.php';

$username = 'superadmin';
$password = 'password123';

echo "Checking user: $username\n";

$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo "User found. ID: " . $user['id'] . ", Role: " . $user['role'] . "\n";
    echo "Stored Hash: " . $user['password'] . "\n";

    if (password_verify($password, $user['password'])) {
        echo "Password verification SUCCESS.\n";
    } else {
        echo "Password verification FAILED.\n";
        echo "Creating new hash for 'password123': " . password_hash('password123', PASSWORD_DEFAULT) . "\n";
    }
} else {
    echo "User '$username' NOT FOUND in database.\n";
}
?>