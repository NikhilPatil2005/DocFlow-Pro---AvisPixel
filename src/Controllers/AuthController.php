<?php

require_once __DIR__ . '/../Models/User.php';

class AuthController
{
    private $userModel;

    public function __construct($db)
    {
        $this->userModel = new User($db);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if ($this->userModel->login($username, $password)) {
                // Verify Role
                $selectedRole = $_POST['role'] ?? '';
                if ($selectedRole && $selectedRole !== $_SESSION['role']) {
                    $actualRole = $_SESSION['role'];
                    session_destroy();
                    session_start();
                    $error = "Role mismatch. You are registered as " . ucfirst(str_replace('_', ' ', $actualRole));
                    view('auth/login', ['error' => $error]);
                    return;
                }

                // Redirect based on role
                switch ($_SESSION['role']) {
                    case 'super_admin':
                        redirect('index.php?action=super_admin_dashboard');
                        break;
                    case 'admin':
                        redirect('index.php?action=admin_dashboard');
                        break;
                    case 'teacher':
                        redirect('index.php?action=teacher_dashboard');
                        break;
                    case 'student':
                        redirect('index.php?action=student_dashboard');
                        break;
                    default:
                        redirect('index.php?action=login');
                }
            } else {
                $error = "Invalid username or password";
                view('auth/login', ['error' => $error]);
            }
        } else {
            view('auth/login');
        }
    }

    public function logout()
    {
        session_destroy();
        redirect('index.php?action=login');
    }
}
