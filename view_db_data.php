<?php
require_once __DIR__ . '/config/db.php';

echo "=== DATABASE CONTENT ===\n";
echo "Database: " . DB_NAME . "\n\n";

// Users
echo "--- USERS ---\n";
$result = $conn->query("SELECT id, username, role FROM users");
while ($row = $result->fetch_assoc()) {
    echo "ID: {$row['id']} | User: {$row['username']} | Role: {$row['role']}\n";
}
echo "\n";

// Notices
echo "--- NOTICES ---\n";
$result = $conn->query("SELECT id, title, status, created_by FROM notices");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: {$row['id']} | Title: {$row['title']} | Status: {$row['status']} | Created By: {$row['created_by']}\n";
    }
}
else {
    echo "No notices found.\n";
}
echo "\n";

// Logs
echo "--- NOTICE LOGS ---\n";
$result = $conn->query("SELECT nl.id, n.title, u.username as actor, nl.action, nl.old_status, nl.new_status, nl.created_at 
                        FROM notice_logs nl 
                        JOIN notices n ON nl.notice_id = n.id 
                        JOIN users u ON nl.performed_by = u.id
                        ORDER BY nl.created_at DESC LIMIT 10");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "[{$row['created_at']}] {$row['actor']} did '{$row['action']}' on notice '{$row['title']}' ({$row['old_status']} -> {$row['new_status']})\n";
    }
}
else {
    echo "No logs found.\n";
}

$conn->close();
?>
