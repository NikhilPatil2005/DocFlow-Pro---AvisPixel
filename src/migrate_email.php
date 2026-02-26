<?php
require_once __DIR__ . '/../config/db.php';

// Add email column to users table
$sql = "ALTER TABLE users ADD COLUMN email VARCHAR(100) UNIQUE NULL AFTER username";

if ($conn->query($sql) === TRUE) {
    echo "Column 'email' added successfully to 'users' table.\n";
}
else {
    echo "Error updating table: " . $conn->error . "\n";
}

$conn->close();
?>
