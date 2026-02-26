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

            $loginResult = $this->userModel->login($username, $password);

            if ($loginResult === true) {
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
            }
            elseif ($loginResult === 'not_active') {
                // Fetch user status to give specific message
                $user = $this->userModel->getUserByUsername($username);
                $status = $user['status'];
                $statusMsg = "Your account is under review.";

                if ($status === 'pending_teacher')
                    $statusMsg = "Your registration is waiting for Teacher approval.";
                elseif ($status === 'pending_admin')
                    $statusMsg = "Your registration is waiting for Admin approval.";
                elseif ($status === 'pending_super_admin')
                    $statusMsg = "Your registration is waiting for Super Admin approval.";
                elseif ($status === 'rejected')
                    $statusMsg = "Your registration was rejected.";

                view('auth/login', ['error' => $statusMsg]);
            }
            else {
                $error = "Invalid username or password";
                view('auth/login', ['error' => $error]);
            }
        }
        else {
            view('auth/login');
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email']; // NEW
            $password = $_POST['password'];
            $role = $_POST['role'];

            // Basic validation
            if (empty($username) || empty($email) || empty($password) || empty($role)) {
                view('auth/register', ['error' => 'All fields are required.']);
                return;
            }

            // Check if username exists (need checks in model)
            // Ideally should check uniqueness first.

            $status = 'pending';
            switch ($role) {
                case 'student':
                    $status = 'pending_teacher';
                    break;
                case 'teacher':
                    $status = 'pending_admin';
                    break;
                case 'admin':
                    $status = 'pending_super_admin';
                    break;
                case 'super_admin':
                    $masterKey = $_POST['master_key'] ?? '';
                    if ($masterKey !== 'MASTER_KEY_123') {
                        view('auth/register', ['error' => 'Invalid Master Security Key.']);
                        return;
                    }
                    $status = 'active';
                    break;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $userId = $this->userModel->register($username, $email, $hashedPassword, $role, $status);

            if ($userId) {
                // Handle File Uploads
                // Define upload directory relative to project root
                $uploadDir = __DIR__ . '/../../assets/user_docs/';

                // Create directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    if (!mkdir($uploadDir, 0777, true)) {
                        // Log error or handle failure
                        error_log("Failed to create directory: " . $uploadDir);
                    }
                }

                $documents = [];
                if ($role === 'admin') {
                    $documents['identity_proof'] = 'identity_proof';
                    $documents['appointment_letter'] = 'appointment_letter';
                }
                elseif ($role === 'teacher') {
                    $documents['educational_certificates'] = 'educational_certificates';
                    $documents['college_id_card'] = 'college_id_card';
                }
                elseif ($role === 'student') {
                    $documents['admission_receipt'] = 'admission_receipt';
                    $documents['previous_marksheet'] = 'previous_marksheet';
                }

                foreach ($documents as $docKey => $docType) {
                    if (isset($_FILES[$docKey]) && $_FILES[$docKey]['error'] === UPLOAD_ERR_OK) {
                        $fileTmpPath = $_FILES[$docKey]['tmp_name'];
                        $fileName = $_FILES[$docKey]['name'];
                        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                        $newFileName = $userId . '_' . $docType . '.' . $fileExtension;
                        $destPath = $uploadDir . $newFileName;

                        if (move_uploaded_file($fileTmpPath, $destPath)) {
                            // Store relative path or absolute? Web accessible path.
                            // Assets are outside public, setup.sql says /assets/user_docs/
                            // If index.php is in public, assets is ../assets
                            // Let's store relative to project root or accessible public path?
                            // Requirement says: "Store all uploaded registration documents in a secure /assets/user_docs/ folder."
                            // We will store just the filename or relative path.
                            $this->userModel->addDocument($userId, $docType, 'assets/user_docs/' . $newFileName);
                        }
                    }
                }

                if ($status === 'active') {
                    // Auto login if active (Super Admin)
                    // Or redirect to login
                    redirect('index.php?action=login&message=registered_active');
                }
                else {
                    redirect('index.php?action=login&message=registered_pending');
                }

            }
            else {
                view('auth/register', ['error' => 'Registration failed. Username may already be taken.']);
            }

        }
        else {
            view('auth/register');
        }
    }

    public function logout()
    {
        session_destroy();
        redirect('index.php?action=login');
    }

    public function checkStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $username = sanitize($username);

            // We need a method to get user by username without login permissions
            // User model has login() method, but not public getByUsername (besides login check).
            // Let's rely on direct query or add a method.
            // Using login logic might be overkill or wrong.
            // Let's use a quick query using the db connection available in model.
            // But I can't access model's private conn.
            // I should use a model method `getUserByUsername`.
            // Let's assume I add it or use a raw method?
            // Actually, I can use `login` logic partially? No.
            // I'll add `getUserByUsername` to User model in next step if needed, or query if I can.
            // Wait, I can't change User model in this tool call.
            // Let's see if there is any method. `getUserById` exists.
            // I'll create a new method `getUserByUsername` in User model.
            // For now, I'll put a placeholder or assume it exists and fix it.
            // Actually, checking standard best practice: Model should handle data.
            // I'll add `getUserByUsername` to User.php.
            // But I need to do it before or after.
            // Let's do it after this call.

            // Assuming $this->userModel->getUserByUsername($username) exists.

            // Temporary hack if method doesn't exist yet:
            // I can't do a hack easily.
            // I will pause this edit and update User.php first?
            // No, I'll write the code assuming it exists, then go fix User.php.

            $user = $this->userModel->getUserByUsername($username);

            if ($user) {
                view('auth/check_status', [
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'userStatus' => $user['status']
                ]);
            }
            else {
                view('auth/check_status', ['error' => 'User not found.']);
            }
        }
        else {
            view('auth/check_status');
        }
    }
}
