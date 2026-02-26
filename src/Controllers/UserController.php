<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../functions.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        global $conn;
        $this->userModel = new User($conn);
    }

    public function index()
    {
        requireLogin();
        // Allow super_admin, admin, teacher
        requireRole(['super_admin', 'admin', 'teacher']);

        $search = $_GET['search'] ?? '';
        $role = $_GET['role'] ?? '';
        $status = $_GET['status'] ?? '';

        $currentUserRole = $_SESSION['role'];

        $users = $this->userModel->getAllUsers($search, $role, $status, $currentUserRole);

        view('dashboard/manage_users', [
            'users' => $users,
            'search' => $search,
            'role' => $role, // Current filter
            'status' => $status,
            'currentUserRole' => $currentUserRole // Pass to view for conditional UI
        ]);
    }

    public function viewUser()
    {
        requireLogin();
        requireRole(['super_admin', 'admin', 'teacher']);

        $id = $_GET['id'] ?? 0;
        $user = $this->userModel->getUserById($id);

        if (!$user) {
            redirect('index.php?action=manage_users&error=User not found');
            return;
        }

        // Access Control Logic
        $currentUserRole = $_SESSION['role'];
        if ($currentUserRole === 'admin') {
            if (in_array($user['role'], ['super_admin', 'admin'])) {
                die("Access Denied: You cannot view this profile.");
            }
        }
        elseif ($currentUserRole === 'teacher') {
            if ($user['role'] !== 'student') {
                die("Access Denied: You cannot view this profile.");
            }
        }

        $documents = $this->userModel->getDocuments($id);

        // Fetch email if not in getUserById yet?
        // Wait, I updated getUserById to return id, username, role, status. 
        // I need to update it to return email too!
        // I missed that in the User.php update. I only assumed it.
        // I should fix User.php::getUserById to return * or add email.

        view('dashboard/view_user', [
            'user' => $user,
            'documents' => $documents
        ]);
    }

    public function updateUserStatus()
    {
        requireLogin();
        requireRole(['super_admin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'];
            $status = $_POST['status'];
            // Validation?

            if ($this->userModel->updateStatus($userId, $status)) {
                redirect('index.php?action=manage_users&success=User status updated');
            }
            else {
                redirect('index.php?action=manage_users&error=Failed to update status');
            }
        }
    }

    public function updateUserRole()
    {
        requireLogin();
        requireRole(['super_admin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'];
            $role = $_POST['role'];

            if ($this->userModel->updateUserRole($userId, $role)) {
                redirect('index.php?action=manage_users&success=User role updated');
            }
            else {
                redirect('index.php?action=manage_users&error=Failed to update role');
            }
        }
    }

    public function deleteUser()
    {
        requireLogin();
        requireRole(['super_admin']);

        $userId = $_POST['user_id'] ?? 0;

        if ($this->userModel->deleteUser($userId)) {
            redirect('index.php?action=manage_users&success=User deleted');
        }
        else {
            redirect('index.php?action=manage_users&error=Failed to delete user');
        }
    }
}
?>
