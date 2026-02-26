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

    public function getAllForAdmin()
    {
        // Admin sees everything for now, can filter in view
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

    public function getPendingCount($role)
    {
        // For sidebar badges
        $count = 0;
        if ($role === 'super_admin') {
            // Can see all notices? Wait, Super Admin usually doesn't approve notices in this flow?
            // "New Admin Request: Goes directly to the Super Admin" -> User approval
            // "New Teacher Request: ... moves to the Super Admin" -> User approval
            // What about notices?
            // "Teacher will only see notices on this page that have already been cleared by the Admin."
            // "Notice Approvals ... sequential approval workflow"
            // Usually: Teacher creates -> Admin Approves -> Published?
            // Or Teacher creates -> Admin Approves -> Super Admin Approves?
            // Let's assume for notices:
            // Admin sees 'pending_admin'.
            // Teacher sees 'admin_approved' (to Publish).
            // Super Admin? - Maybe just informational or override?
            // Let's stick to requirements:
            // "dedicated Notice Approval Page... Teachers will only see notices... cleared by Admin"

            // If Super Admin needs to approve notices, we'd need a status like 'pending_super_admin' for notices too.
            // For now, let's assume Super Admin doesn't have a specific "Notice Approval" queue unless specified.
            // But they likely want to see everything.
            return 0;
        }
        elseif ($role === 'admin') {
            $sql = "SELECT COUNT(*) as count FROM notices WHERE status = 'pending_admin'";
        }
        elseif ($role === 'teacher') {
            $sql = "SELECT COUNT(*) as count FROM notices WHERE status = 'admin_approved'";
        }
        else {
            return 0;
        }

        if (isset($sql)) {
            $result = $this->conn->query($sql);
            if ($result && $row = $result->fetch_assoc()) {
                $count = $row['count'];
            }
        }
        return $count;
    }
}
