<?php

class Notification
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($userId, $message)
    {
        $userId = (int) $userId;
        $message = sanitize($message);

        $sql = "INSERT INTO notifications (user_id, message) VALUES ($userId, '$message')";
        return $this->conn->query($sql);
    }

    public function createForRole($role, $message)
    {
        // Find all users with this role
        $role = sanitize($role);
        $sql = "SELECT id FROM users WHERE role = '$role'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $stmt = $this->conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
            while ($row = $result->fetch_assoc()) {
                $stmt->bind_param("is", $row['id'], $message);
                $stmt->execute();
            }
        }
    }

    public function getUnread($userId)
    {
        $userId = (int) $userId;
        $sql = "SELECT * FROM notifications WHERE user_id = $userId AND is_read = 0 ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function markAsRead($userId)
    {
        $userId = (int) $userId;
        $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = $userId";
        return $this->conn->query($sql);
    }
}
