<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/src/Models/Notice.php';

// Mocking sanitize function if it's global
if (!function_exists('sanitize')) {
    function sanitize($data)
    {
        global $conn;
        return mysqli_real_escape_string($conn, trim($data));
    }
}

$noticeModel = new Notice($conn);
try {
    $notices = $noticeModel->getAllForSuperAdmin();
    echo "Successfully fetched " . count($notices) . " notices.";
    if (count($notices) > 0) {
        echo " First notice priority: " . ($notices[0]['priority'] ?? 'N/A');
    }
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
