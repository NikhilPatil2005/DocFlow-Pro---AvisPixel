<?php
require_once __DIR__ . '/../config/db.php';

// Update users table status enum
$sql = "ALTER TABLE users MODIFY COLUMN status ENUM('pending', 'pending_teacher', 'pending_admin', 'pending_super_admin', 'active', 'rejected', 'archived') DEFAULT 'pending'";

if ($conn->query($sql) === TRUE) {
    echo "Table users updated successfully to include new status ENUM values.\n";

    // Optional: Migrate existing 'pending' users to specific new statuses if needed.
    // For now, we'll leave them as 'pending' and handle them or map them.
    // Actually, let's map 'pending' students to 'pending_teacher' and 'pending' teachers to 'pending_admin' to be safe?
    // Or just leave them and handle 'pending' as a fallback in logic. 
    // Let's migrate them to be clean.

    $updateStudents = "UPDATE users SET status = 'pending_teacher' WHERE status = 'pending' AND role = 'student'";
    if ($conn->query($updateStudents))
        echo "Migrated pending students to pending_teacher.\n";

    $updateTeachers = "UPDATE users SET status = 'pending_admin' WHERE status = 'pending' AND role = 'teacher'";
    if ($conn->query($updateTeachers))
        echo "Migrated pending teachers to pending_admin.\n";

    $updateAdmins = "UPDATE users SET status = 'pending_super_admin' WHERE status = 'pending' AND role = 'admin'";
    if ($conn->query($updateAdmins))
        echo "Migrated pending admins to pending_super_admin.\n";


}
else {
    echo "Error updating table: " . $conn->error . "\n";
}

$conn->close();
?>
