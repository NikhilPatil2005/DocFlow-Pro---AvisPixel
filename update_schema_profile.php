<?php
require 'config/db.php';

$queries = [
    "ALTER TABLE users ADD COLUMN full_name VARCHAR(100) NULL AFTER username",
    "ALTER TABLE users ADD COLUMN email VARCHAR(100) NULL UNIQUE AFTER full_name"
];

foreach ($queries as $query) {
    if ($conn->query($query)) {
        echo "Successfully executed: $query\n";
    } else {
        echo "Error or already exists for: $query -> " . $conn->error . "\n";
    }
}
?>