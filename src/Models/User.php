<?php

class User
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function login($username, $password)
    {
        $username = sanitize($username);

        $sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                if ($user['status'] !== 'active') {
                    return 'not_active';
                }
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['status'] = $user['status'];
                return true;
            }
        }
        return false;
    }

    public function getUserById($id)
    {
        $id = (int)$id;
        $sql = "SELECT * FROM users WHERE id = $id";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    public function getUserByUsername($username)
    {
        $username = sanitize($username);
        $sql = "SELECT id, username, role, status FROM users WHERE username = '$username'";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
    public function getUserByEmail($email)
    {
        $email = sanitize($email);
        $sql = "SELECT id, username, role, status FROM users WHERE email = '$email'";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    public function register($username, $email, $password, $role, $status)
    {
        $username = sanitize($username);
        $email = sanitize($email);
        // Password should already be hashed

        // Check if email already exists
        if ($this->getUserByEmail($email)) {
            return false; // Email taken
        }

        $sql = "INSERT INTO users (username, email, password, role, status) VALUES ('$username', '$email', '$password', '$role', '$status')";
        if ($this->conn->query($sql)) {
            return $this->conn->insert_id;
        }
        return false;
    }

    // ... (rest of old methods: addDocument, getPendingUsers, updateStatus, logApproval, getDocuments, getPendingCount) ...
    // Note: I will use multi_replace to insert new methods at the end or use replacement chunks carefully.
    // The previous prompt context has 146 lines. register is at line 39. 
    // I will replace register method here and append new management methods at the end.

    // ... skipping intermediate methods to keep it simple for this replacement block if possible, 
    // but replace_file_content requires contiguous block. 
    // I'll use multi_replace instead? Or replace the register method first, then add others.
    // Let's replace register first.

    public function getAllUsers($search = '', $role = '', $status = '', $viewerRole = 'super_admin')
    {
        $sql = "SELECT * FROM users WHERE 1=1";

        // Role-based visibility
        if ($viewerRole === 'admin') {
            // Admin sees Teachers and Students
            // But if specific role is requested, validation is needed?
            // Let's just filter the base query.
            if ($role) {
                // If asking for 'admin' or 'super_admin' as an admin, return nothing or handle it.
                if (in_array($role, ['super_admin', 'admin'])) {
                    return [];
                }
            }
            else {
                $sql .= " AND role IN ('teacher', 'student')";
            }
        }
        elseif ($viewerRole === 'teacher') {
            // Teacher sees Students only
            if ($role && $role !== 'student') {
                return [];
            }
            $sql .= " AND role = 'student'";
        }
        elseif ($viewerRole !== 'super_admin') {
            // Unknown role sees nothing? Or just self?
            return [];
        }

        if ($search) {
            $search = sanitize($search);
            $sql .= " AND (username LIKE '%$search%' OR email LIKE '%$search%')";
        }

        if ($role) {
            $role = sanitize($role);
            $sql .= " AND role = '$role'";
        }

        if ($status) {
            $status = sanitize($status);
            $sql .= " AND status = '$status'";
        }

        $sql .= " ORDER BY created_at DESC";

        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateUserRole($userId, $role)
    {
        $userId = (int)$userId;
        $role = sanitize($role);
        $sql = "UPDATE users SET role = '$role' WHERE id = $userId";
        return $this->conn->query($sql);
    }

    public function deleteUser($userId)
    {
        $userId = (int)$userId;
        $sql = "DELETE FROM users WHERE id = $userId";
        return $this->conn->query($sql);
    }

    public function addDocument($userId, $type, $path)
    {
        $userId = (int)$userId;
        $type = sanitize($type);
        $path = sanitize($path);

        $sql = "INSERT INTO user_documents (user_id, document_type, file_path) VALUES ($userId, '$type', '$path')";
        return $this->conn->query($sql);
    }

    public function getPendingUsers($targetRole = null, $status = 'pending')
    {
        $status = sanitize($status);
        $sql = "SELECT * FROM users WHERE status = '$status'";
        if ($targetRole) {
            $targetRole = sanitize($targetRole);
            $sql .= " AND role = '$targetRole'";
        }
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateStatus($userId, $status)
    {
        $userId = (int)$userId;
        $status = sanitize($status);
        $sql = "UPDATE users SET status = '$status' WHERE id = $userId";
        return $this->conn->query($sql);
    }

    public function logApproval($userId, $approvedBy, $statusAssigned, $remarks = '')
    {
        $userId = (int)$userId;
        $approvedBy = (int)$approvedBy;
        $statusAssigned = sanitize($statusAssigned);
        $remarks = sanitize($remarks);

        // Fetch role at time of approval from session or user table?
        // The controller didn't pass it in the updated call. 
        // Let's get it from the user table for accuracy or pass it? 
        // The previous usage was: logApproval($userId, $_SESSION['user_id'], $currentUserRole, $newStatus, $remarks);
        // My updated controller call is: $this->userModel->logApproval($userId, $_SESSION['user_id'], $newStatus, $remarks);
        // So I removed $currentUserRole from the call. I should probably keep it or remove it from here.
        // Let's remove it from here and rely on who approved it (approved_by) to know the role if needed later, 
        // or just fetch it here. But simpler to just remove the column from insert if I changed the method.
        // Wait, I should probably NOT change the database schema if I can avoid it.
        // Let's revert the method signature change in my mind and fix the controller call OR fix this method to handle missing arg.

        // Actually, looking at the previous file content for User.php:
        // public function logApproval($userId, $approvedBy, $roleAtTime, $statusAssigned, $remarks = '')

        // I should update the Controller to pass the role again, OR update this method to get the role from session.
        // Getting from session is safer/easier here.
        $roleAtTime = $_SESSION['role'] ?? 'unknown';

        $sql = "INSERT INTO registration_approvals (user_id, approved_by, role_at_time_of_approval, status_assigned, remarks) 
                VALUES ($userId, $approvedBy, '$roleAtTime', '$statusAssigned', '$remarks')";
        return $this->conn->query($sql);
    }

    public function getDocuments($userId)
    {
        $userId = (int)$userId;
        $sql = "SELECT * FROM user_documents WHERE user_id = $userId";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPendingCount($role)
    {
        // For sidebar badges
        $count = 0;
        if ($role === 'super_admin') {
            // Count pending_super_admin (Admins directly, Teachers step 2, Students step 3)
            $sql = "SELECT COUNT(*) as count FROM users WHERE status = 'pending_super_admin'";
        }
        elseif ($role === 'admin') {
            // Count pending_admin (Teachers step 1, Students step 2)
            $sql = "SELECT COUNT(*) as count FROM users WHERE status = 'pending_admin'";
        }
        elseif ($role === 'teacher') {
            // Count pending_teacher (Students step 1)
            $sql = "SELECT COUNT(*) as count FROM users WHERE status = 'pending_teacher'";
        }
        else {
            return 0;
        }

        $result = $this->conn->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            $count = $row['count'];
        }
        return $count;
    }
}
