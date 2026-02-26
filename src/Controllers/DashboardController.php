<?php

class DashboardController
{

    public function registrationRequests()
    {
        requireLogin();
        $role = $_SESSION['role'];
        requireRole(['super_admin', 'admin', 'teacher']); // Teacher might need access if they approve students?

        require_once __DIR__ . '/../Models/User.php';
        global $conn;
        $userModel = new User($conn);

        $pendingUsers = [];
        if ($role === 'super_admin') {
            // Super Admin sees 'pending_super_admin' (Everyone at final stage)
            // This includes: Admin registrations, Teacher registrations (step 2), Student registrations (step 3)
            $pendingUsers = $userModel->getPendingUsers(null, 'pending_super_admin');
        }
        elseif ($role === 'admin') {
            // Admin sees 'pending_admin'
            // This includes: Teacher registrations (step 1), Student registrations (step 2)
            $pendingUsers = $userModel->getPendingUsers(null, 'pending_admin');
        }
        elseif ($role === 'teacher') {
            // Teacher sees 'pending_teacher'
            // This includes: Student registrations (step 1)
            $pendingUsers = $userModel->getPendingUsers(null, 'pending_teacher');
        }

        // Fetch documents for all
        foreach ($pendingUsers as &$u) {
            $u['documents'] = $userModel->getDocuments($u['id']);
        }
        unset($u);

        view('dashboard/registration_requests', ['pendingUsers' => $pendingUsers]);
    }

    public function noticeApprovals()
    {
        requireLogin();
        $role = $_SESSION['role'];
        requireRole(['admin', 'teacher']);

        require_once __DIR__ . '/../Models/Notice.php';
        global $conn;
        $noticeModel = new Notice($conn);

        $notices = [];
        if ($role === 'admin') {
            $notices = $noticeModel->getAllByStatus('pending_admin');
        }
        elseif ($role === 'teacher') {
            $notices = $noticeModel->getAllByStatus('admin_approved');
        }

        view('dashboard/notice_approvals', ['notices' => $notices]);
    }

    public function superAdmin()
    {
        requireLogin();
        requireRole(['super_admin']);

        require_once __DIR__ . '/../Models/Notice.php';
        global $conn;
        $noticeModel = new Notice($conn);
        $counts = $noticeModel->getCountsByStatus();

        view('dashboard/super_admin', ['counts' => $counts]);
    }

    public function admin()
    {
        requireLogin();
        requireRole(['admin']);
        view('dashboard/admin');
    }

    public function teacher()
    {
        requireLogin();
        requireRole(['teacher']);
        view('dashboard/teacher');
    }

    public function student()
    {
        requireLogin();
        requireRole(['student']);
        view('dashboard/student');
    }
}
