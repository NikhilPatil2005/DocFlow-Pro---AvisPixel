<?php
require_once __DIR__ . '/config/db.php';

$sqlFile = __DIR__ . '/update_schema_leaves.sql';
if (file_exists($sqlFile)) {
    $sqlContent = file_get_contents($sqlFile);
    if ($conn->multi_query($sqlContent)) {
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
        echo "Leave schema and role updates applied successfully\n";
    } else {
        echo "Error applying schema: " . $conn->error . "\n";
    }
} else {
    echo "update_schema_leaves.sql not found.\n";
}
$conn->close();
