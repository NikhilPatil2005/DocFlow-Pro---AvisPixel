<?php

class DashboardController
{

    public function registrationRequests()
    {
        requireLogin();
        $role = $_SESSION['role'];
        requireRole(['admin', 'principal', 'hod', 'teacher']);

        require_once __DIR__ . '/../Models/User.php';
        global $conn;
        $userModel = new User($conn);

        $pendingUsers = [];
        if ($role === 'admin') {
            $pendingUsers = $userModel->getPendingUsers(null, 'pending_admin');
        } elseif ($role === 'principal') {
            $pendingUsers = $userModel->getPendingUsers(null, 'pending_principal');
        } elseif ($role === 'hod') {
            $pendingUsers = $userModel->getPendingUsers(null, 'pending_hod');
        } elseif ($role === 'teacher') {
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
        } elseif ($role === 'teacher') {
            $notices = $noticeModel->getAllByStatus('admin_approved');
        }

        view('dashboard/notice_approvals', ['notices' => $notices]);
    }

    public function admin()
    {
        requireLogin();
        requireRole(['admin']);

        require_once __DIR__ . '/../Models/Notice.php';
        global $conn;
        $noticeModel = new Notice($conn);
        $counts = $noticeModel->getCountsByStatus();

        view('dashboard/super_admin', ['counts' => $counts]); // Reusing the super_admin view for now
    }

    public function principal()
    {
        requireLogin();
        requireRole(['principal']);
        
        require_once __DIR__ . '/../Models/Leave.php';
        require_once __DIR__ . '/../Models/User.php';
        global $conn;
        
        $leaveModel = new Leave($conn);
        $userModel = new User($conn);
        
        // Get pending leave counts
        $pendingLeavesCount = 0;
        $pendingLeaves = $leaveModel->getPendingLeavesForPrincipal();
        $pendingLeavesCount = count($pendingLeaves);
        
        // Get pending registrations count
        $pendingRegCount = $userModel->getPendingCount('principal');
        
        view('dashboard/principal', [
            'pendingLeavesCount' => $pendingLeavesCount,
            'pendingRegCount' => $pendingRegCount,
            'pendingLeaves' => $pendingLeaves
        ]);
    }

    public function hod()
    {
        requireLogin();
        requireRole(['hod']);
        
        require_once __DIR__ . '/../Models/Leave.php';
        require_once __DIR__ . '/../Models/User.php';
        global $conn;
        
        $leaveModel = new Leave($conn);
        $userModel = new User($conn);
        
        // Get HOD's department
        $userInfo = $userModel->getUserById($_SESSION['user_id']);
        $deptId = $userInfo['department_id'];
        
        // Get pending leave counts for HOD
        $pendingLeavesCount = 0;
        $pendingLeaves = [];
        if ($deptId) {
            $pendingLeaves = $leaveModel->getPendingLeavesForHOD($deptId);
            $pendingLeavesCount = count($pendingLeaves);
        }
        
        // Get pending registrations count
        $pendingRegCount = $userModel->getPendingCount('hod');
        
        view('dashboard/hod', [
            'pendingLeavesCount' => $pendingLeavesCount,
            'pendingRegCount' => $pendingRegCount,
            'pendingLeaves' => $pendingLeaves,
            'departmentName' => $userInfo['department_id'] ? $conn->query("SELECT name FROM departments WHERE id = " . $userInfo['department_id'])->fetch_assoc()['name'] : 'Not Assigned'
        ]);
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
