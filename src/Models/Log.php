<?php

class Log
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($noticeId, $performedBy, $role, $action, $oldStatus = null, $newStatus = null, $details = null)
    {
        $noticeId = (int) $noticeId;
        $performedBy = (int) $performedBy;
        $role = sanitize($role);
        $action = sanitize($action);
        $oldStatus = $oldStatus ? "'" . sanitize($oldStatus) . "'" : "NULL";
        $newStatus = $newStatus ? "'" . sanitize($newStatus) . "'" : "NULL";
        $details = $details ? "'" . sanitize($details) . "'" : "NULL";

        $sql = "INSERT INTO notice_logs (notice_id, performed_by, role, action, old_status, new_status, details) 
                VALUES ($noticeId, $performedBy, '$role', '$action', $oldStatus, $newStatus, $details)";

        return $this->conn->query($sql);
    }
}
