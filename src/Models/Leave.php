<?php

class Leave
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createLeave($userId, $departmentId, $leaveDate, $timeFrom, $timeTo, $venue, $reason, $workloadAdjustedWith)
    {
        $userId = (int) $userId;
        $departmentId = (int) $departmentId;
        $leaveDate = sanitize($leaveDate);
        $timeFrom = sanitize($timeFrom);
        $timeTo = sanitize($timeTo);
        $venue = sanitize($venue);
        $reason = sanitize($reason);
        $workloadAdjustedWith = sanitize($workloadAdjustedWith);

        // Get user role to determine initial status
        global $conn;
        $userResult = $conn->query("SELECT role FROM users WHERE id = $userId");
        $userData = $userResult->fetch_assoc();
        $userRole = $userData['role'];

        // If user is HOD, leave goes directly to Principal (skip HOD approval)
        // If user is Teacher, leave goes to HOD first
        $initialStatus = ($userRole === 'hod') ? 'pending_principal' : 'pending_hod';

        // Uses a prepared statement to be safe
        $sql = "INSERT INTO leaves (user_id, department_id, leave_date, time_from, time_to, venue, reason, workload_adjusted_with, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iisssssss", $userId, $departmentId, $leaveDate, $timeFrom, $timeTo, $venue, $reason, $workloadAdjustedWith, $initialStatus);

        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    public function getLeavesByUser($userId)
    {
        $userId = (int) $userId;
        $sql = "SELECT l.*, d.name as department_name 
                FROM leaves l 
                LEFT JOIN departments d ON l.department_id = d.id 
                WHERE l.user_id = $userId ORDER BY l.created_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPendingLeavesForHOD($departmentId)
    {
        $departmentId = (int) $departmentId;
        $sql = "SELECT l.*, u.username, u.designation, d.name as department_name 
                FROM leaves l 
                JOIN users u ON l.user_id = u.id 
                JOIN departments d ON l.department_id = d.id 
                WHERE l.status = 'pending_hod' AND l.department_id = $departmentId
                ORDER BY l.created_at ASC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPendingLeavesForPrincipal()
    {
        $sql = "SELECT l.*, u.username, u.designation, d.name as department_name, h.username as hod_username 
                FROM leaves l 
                JOIN users u ON l.user_id = u.id 
                JOIN departments d ON l.department_id = d.id 
                LEFT JOIN users h ON l.hod_id = h.id 
                WHERE l.status = 'pending_principal'
                ORDER BY l.created_at ASC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllLeavesForAdmin()
    {
        $sql = "SELECT l.*, u.username, u.designation, d.name as department_name, 
                       h.username as hod_username, p.username as principal_username 
                FROM leaves l 
                JOIN users u ON l.user_id = u.id 
                JOIN departments d ON l.department_id = d.id 
                LEFT JOIN users h ON l.hod_id = h.id 
                LEFT JOIN users p ON l.principal_id = p.id 
                ORDER BY l.created_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function approveByHOD($leaveId, $hodId)
    {
        $leaveId = (int) $leaveId;
        $hodId = (int) $hodId;
        $sql = "UPDATE leaves SET status = 'pending_principal', hod_id = $hodId WHERE id = $leaveId AND status = 'pending_hod'";
        return $this->conn->query($sql);
    }

    public function approveByPrincipal($leaveId, $principalId)
    {
        $leaveId = (int) $leaveId;
        $principalId = (int) $principalId;
        $sql = "UPDATE leaves SET status = 'approved', principal_id = $principalId WHERE id = $leaveId AND status = 'pending_principal'";
        return $this->conn->query($sql);
    }

    public function rejectLeave($leaveId, $rejectorId, $role)
    {
        $leaveId = (int) $leaveId;
        $rejectorId = (int) $rejectorId;

        if ($role === 'hod') {
            $sql = "UPDATE leaves SET status = 'rejected', hod_id = $rejectorId WHERE id = $leaveId AND status = 'pending_hod'";
        } elseif ($role === 'principal') {
            $sql = "UPDATE leaves SET status = 'rejected', principal_id = $rejectorId WHERE id = $leaveId AND status = 'pending_principal'";
        } else {
            return false;
        }
        return $this->conn->query($sql);
    }

    public function getLeaveById($leaveId)
    {
        $leaveId = (int) $leaveId;
        $sql = "SELECT l.*, u.username, u.designation, d.name as department_name 
                FROM leaves l 
                JOIN users u ON l.user_id = u.id 
                JOIN departments d ON l.department_id = d.id 
                WHERE l.id = $leaveId";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
}
