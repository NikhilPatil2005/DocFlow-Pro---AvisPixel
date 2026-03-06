<?php
require_once __DIR__ . '/../Models/User.php';

class ProfileController
{
    private $userModel;

    public function __construct($db)
    {
        $this->userModel = new User($db);
    }

    public function index()
    {
        requireLogin();

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($userId);
        $departments = $this->userModel->getAllDepartments();

        view('profile/view', [
            'user' => $user,
            'departments' => $departments,
            'success' => $_GET['success'] ?? null,
            'error' => $_GET['error'] ?? null
        ]);
    }

    public function updateProfile()
    {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $fullName = $_POST['full_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $departmentId = $_POST['department_id'] ?? null;
            $designation = $_POST['designation'] ?? '';

            // Validation
            if (empty($fullName) || empty($email)) {
                redirect('index.php?action=profile&error=Full Name and Email are required');
                return;
            }

            $updateStatus = $this->userModel->updateProfile($userId, $fullName, $email, $departmentId, $designation);

            if ($updateStatus === 'email_taken') {
                redirect('index.php?action=profile&error=Email is already taken by another account');
            } elseif ($updateStatus === true) {
                redirect('index.php?action=profile&success=Profile updated successfully');
            } else {
                redirect('index.php?action=profile&error=Failed to update profile');
            }
        } else {
            redirect('index.php?action=profile');
        }
    }

    public function updatePassword()
    {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validation
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                redirect('index.php?action=profile&error=All password fields are required');
                return;
            }

            if ($newPassword !== $confirmPassword) {
                redirect('index.php?action=profile&error=New passwords do not match');
                return;
            }

            // Verify current password
            $user = $this->userModel->getUserById($userId);
            if (!password_verify($currentPassword, $user['password'])) {
                redirect('index.php?action=profile&error=Incorrect current password');
                return;
            }

            $hashedValue = password_hash($newPassword, PASSWORD_DEFAULT);
            if ($this->userModel->updatePassword($userId, $hashedValue)) {
                redirect('index.php?action=profile&success=Password updated successfully');
            } else {
                redirect('index.php?action=profile&error=Failed to update password');
            }
        } else {
            redirect('index.php?action=profile');
        }
    }
}
?>