<?php
require_once __DIR__ . '/../Models/Leave.php';
require_once __DIR__ . '/../Models/User.php';

class LeaveController
{
    private $leaveModel;
    private $userModel;

    public function __construct($db)
    {
        $this->leaveModel = new Leave($db);
        $this->userModel = new User($db);
    }

    public function apply()
    {
        requireLogin();
        $role = $_SESSION['role'];
        requireRole(['teacher', 'hod', 'principal']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            // Fetch user to get department_id
            $userInfo = $this->userModel->getUserById($user_id);
            $department_id = $userInfo['department_id'];

            if (!$department_id) {
                // Cannot apply without a department
                view('leaves/apply', ['error' => 'You must be assigned to a department to apply for leave. Please contact Admin.']);
                return;
            }

            $leave_date = $_POST['leave_date'];
            $time_from = $_POST['time_from'];
            $time_to = $_POST['time_to'];
            $venue = $_POST['venue'];
            $reason = $_POST['reason'];
            $workload = $_POST['workload_adjusted_with'];
            $designation = $_POST['designation'] ?? '';

            // Update user designation if they provided one
            if (!empty($designation) && $designation !== ($userInfo['designation'] ?? '')) {
                global $conn;
                $stmt = $conn->prepare("UPDATE users SET designation = ? WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("si", $designation, $user_id);
                    $stmt->execute();
                }
            }

            if ($this->leaveModel->createLeave($user_id, $department_id, $leave_date, $time_from, $time_to, $venue, $reason, $workload)) {
                redirect('index.php?action=my_leaves&success=Leave applied successfully');
            } else {
                view('leaves/apply', ['error' => 'Failed to submit leave application.']);
            }
        } else {
            view('leaves/apply');
        }
    }

    public function myHistory()
    {
        requireLogin();
        $user_id = $_SESSION['user_id'];
        $leaves = $this->leaveModel->getLeavesByUser($user_id);
        view('leaves/history', ['leaves' => $leaves]);
    }

    public function manage()
    {
        requireLogin();
        $role = $_SESSION['role'];
        requireRole(['hod', 'principal', 'admin']);

        $pendingLeaves = [];
        $title = "Manage Leaves";

        if ($role === 'hod') {
            // HOD sees leaves from their specific department
            $userInfo = $this->userModel->getUserById($_SESSION['user_id']);
            $dept_id = $userInfo['department_id'];
            if ($dept_id) {
                $pendingLeaves = $this->leaveModel->getPendingLeavesForHOD($dept_id);
            }
            $title = "Leave Requests (HOD)";
        } elseif ($role === 'principal') {
            $pendingLeaves = $this->leaveModel->getPendingLeavesForPrincipal();
            $title = "Leave Requests (Principal)";
        } elseif ($role === 'admin') {
            $pendingLeaves = $this->leaveModel->getAllLeavesForAdmin();
            $title = "All Leave Applications";
        }

        view('leaves/manage', ['leaves' => $pendingLeaves, 'title' => $title]);
    }

    public function approve()
    {
        requireLogin();
        $role = $_SESSION['role'];
        requireRole(['hod', 'principal']);

        $leave_id = $_POST['leave_id'] ?? null;
        if (!$leave_id) {
            redirect('index.php?action=manage_leaves&error=Invalid request');
            return;
        }

        $success = false;
        if ($role === 'hod') {
            $success = $this->leaveModel->approveByHOD($leave_id, $_SESSION['user_id']);
        } elseif ($role === 'principal') {
            $success = $this->leaveModel->approveByPrincipal($leave_id, $_SESSION['user_id']);
        }

        if ($success) {
            redirect('index.php?action=manage_leaves&success=Leave approved successfully');
        } else {
            redirect('index.php?action=manage_leaves&error=Failed to approve leave');
        }
    }

    public function reject()
    {
        requireLogin();
        $role = $_SESSION['role'];
        requireRole(['hod', 'principal']);

        $leave_id = $_POST['leave_id'] ?? null;
        if (!$leave_id) {
            redirect('index.php?action=manage_leaves&error=Invalid request');
            return;
        }

        if ($this->leaveModel->rejectLeave($leave_id, $_SESSION['user_id'], $role)) {
            redirect('index.php?action=manage_leaves&success=Leave rejected successfully');
        } else {
            redirect('index.php?action=manage_leaves&error=Failed to reject leave');
        }
    }
}
