<?php
require_once __DIR__ . '/../config/db.php';

$username = 'superadmin';
$password = 'password123';
$new_hash = password_hash($password, PASSWORD_DEFAULT);

echo "Updating password for user: $username\n";

$sql = "UPDATE users SET password = '$new_hash' WHERE username = '$username'";

if ($conn->query($sql) === TRUE) {
    echo "Password updated successfully.\n";
} else {
    echo "Error updating password: " . $conn->error . "\n";
}

$conn->close();
?>