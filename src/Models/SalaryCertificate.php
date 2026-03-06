<?php

class SalaryCertificate
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createRequest($teacherId, $departmentId, $designation, $fromDate, $toDate, $purpose)
    {
        $teacherId = (int) $teacherId;
        $departmentId = (int) $departmentId;
        $designation = sanitize($designation);
        $fromDate = sanitize($fromDate);
        $toDate = sanitize($toDate);
        $purpose = sanitize($purpose);

        $sql = "INSERT INTO salary_certificate_requests (teacher_id, department_id, designation, from_date, to_date, purpose, status) 
                VALUES (?, ?, ?, ?, ?, ?, 'pending')";

        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("iissss", $teacherId, $departmentId, $designation, $fromDate, $toDate, $purpose);
            if ($stmt->execute()) {
                return $this->conn->insert_id;
            }
        }
        return false;
    }

    public function getRequestsByTeacher($teacherId)
    {
        $teacherId = (int) $teacherId;
        $sql = "SELECT s.*, d.name as department_name 
                FROM salary_certificate_requests s 
                LEFT JOIN departments d ON s.department_id = d.id 
                WHERE s.teacher_id = $teacherId ORDER BY s.created_at DESC";
        $result = $this->conn->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    public function getPendingForPrincipal()
    {
        $sql = "SELECT s.*, u.username as teacher_name, d.name as department_name 
                FROM salary_certificate_requests s 
                JOIN users u ON s.teacher_id = u.id 
                JOIN departments d ON s.department_id = d.id 
                WHERE s.status = 'pending'
                ORDER BY s.created_at ASC";
        $result = $this->conn->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    public function getRequestById($requestId)
    {
        $requestId = (int) $requestId;
        $sql = "SELECT s.*, u.username as teacher_name, d.name as department_name 
                FROM salary_certificate_requests s 
                JOIN users u ON s.teacher_id = u.id 
                JOIN departments d ON s.department_id = d.id 
                WHERE s.id = $requestId";
        $result = $this->conn->query($sql);
        if ($result) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function approveRequest($requestId, $signature)
    {
        $requestId = (int) $requestId;
        $signature = sanitize($signature);
        $sql = "UPDATE salary_certificate_requests 
                SET status = 'approved', principal_signature = ?, approved_at = NOW() 
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("si", $signature, $requestId);
            return $stmt->execute();
        }
        return false;
    }

    public function rejectRequest($requestId)
    {
        $requestId = (int) $requestId;
        $sql = "UPDATE salary_certificate_requests SET status = 'rejected' WHERE id = $requestId";
        return $this->conn->query($sql);
    }
}
