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
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                return true;
            }
        }
        return false;
    }

    public function getUserById($id)
    {
        $id = (int) $id;
        $sql = "SELECT id, username, role FROM users WHERE id = $id";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
}
