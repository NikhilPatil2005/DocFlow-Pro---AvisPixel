<?php
require_once __DIR__ . '/config/db.php';

function describeTable($conn, $table)
{
    echo "--- STRUCTURE OF $table ---\n";
    $result = $conn->query("DESCRIBE $table");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo "{$row['Field']} - {$row['Type']}\n";
        }
    }
    else {
        echo "Error describing $table: " . $conn->error . "\n";
    }
    echo "\n";
}

describeTable($conn, 'users');
describeTable($conn, 'notices');
describeTable($conn, 'notice_logs');

$conn->close();
?>
