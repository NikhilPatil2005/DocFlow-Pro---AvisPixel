<?php

class Notice
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($title, $content, $createdBy, $priority = 'Low')
    {
        $title = sanitize($title);
        $content = sanitize($content);
        $priority = sanitize($priority);
        $createdBy = (int)$createdBy;

        $sql = "INSERT INTO notices (title, content, created_by, status, priority) VALUES ('$title', '$content', $createdBy, 'pending_admin', '$priority')";

        if ($this->conn->query($sql)) {
            return $this->conn->insert_id;
        }
        return false;
    }

    public function update($id, $title, $content, $priority = 'Low')
    {
        $id = (int)$id;
        $title = sanitize($title);
        $content = sanitize($content);
        $priority = sanitize($priority);

        $sql = "UPDATE notices SET title = '$title', content = '$content', priority = '$priority' WHERE id = $id";
        return $this->conn->query($sql);
    }

    public function updateStatus($id, $status, $rejectionReason = null)
    {
        $id = (int)$id;
        $status = sanitize($status);

        if ($rejectionReason) {
            $rejectionReason = sanitize($rejectionReason);
            $sql = "UPDATE notices SET status = '$status', rejection_reason = '$rejectionReason' WHERE id = $id";
        }
        else {
            $sql = "UPDATE notices SET status = '$status', rejection_reason = NULL WHERE id = $id";
        }

        return $this->conn->query($sql);
    }

    public function getById($id)
    {
        $id = (int)$id;
        $sql = "SELECT n.*, u.username as creator_name FROM notices n JOIN users u ON n.created_by = u.id WHERE n.id = $id";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    public function getAllByStatus($status)
    {
        $status = sanitize($status);
        $sql = "SELECT n.*, u.username as creator_name FROM notices n JOIN users u ON n.created_by = u.id WHERE n.status = '$status' ORDER BY FIELD(priority, 'High', 'Medium', 'Low'), n.created_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllForSuperAdmin()
    {
        // Super Admin sees everything, maybe filter or paginate later
        $sql = "SELECT n.*, u.username as creator_name FROM notices n JOIN users u ON n.created_by = u.id ORDER BY FIELD(priority, 'High', 'Medium', 'Low'), n.created_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCountsByStatus()
    {
        $sql = "SELECT 
                    SUM(CASE WHEN status LIKE '%pending%' THEN 1 ELSE 0 END) as pending_count,
                    SUM(CASE WHEN status LIKE '%published%' THEN 1 ELSE 0 END) as published_count,
                    SUM(CASE WHEN status LIKE '%rejected%' THEN 1 ELSE 0 END) as rejected_count
                FROM notices";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
}
