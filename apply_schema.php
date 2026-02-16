<?php
require_once __DIR__ . '/config/db.php';

$sql = "ALTER TABLE notices ADD COLUMN priority ENUM('Low', 'Medium', 'High') DEFAULT 'Low' AFTER status";

try {
    if ($conn->query($sql) === TRUE) {
        echo "Column 'priority' added successfully.";
    }
    else {
        echo "Error adding column: " . $conn->error;
    }
}
catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}

$conn->close();
?>
