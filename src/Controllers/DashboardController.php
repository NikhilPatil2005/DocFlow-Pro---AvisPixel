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
        requireRole(['principal', 'teacher']);

        require_once __DIR__ . '/../Models/Notice.php';
        global $conn;
        $noticeModel = new Notice($conn);

        $notices = [];
        if ($role === 'principal') {
            $notices = $noticeModel->getAllByStatus('pending_principal');
        } elseif ($role === 'teacher') {
            $notices = $noticeModel->getAllByStatus('principal_approved');
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

        view('dashboard/admin', ['counts' => $counts]);
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
        
        require_once __DIR__ . '/../Models/Exam.php';
        global $conn;
        $examModel = new Exam($conn);
        $exams = $examModel->getExamsByTeacher($_SESSION['user_id']);
        
        $activeExams = 0;
        $completedExams = 0;
        $totalExams = count($exams);
        foreach ($exams as $e) {
            if ($e['status'] === 'published') $activeExams++;
            if ($e['status'] === 'completed') $completedExams++;
        }
        
        $analytics = $examModel->getTeacherExamAnalytics($_SESSION['user_id']);
        
        view('dashboard/teacher', [
            'totalExams' => $totalExams,
            'activeExams' => $activeExams,
            'completedExams' => $completedExams,
            'exams' => array_slice($exams, 0, 5), // Recent 5 exams
            'analytics' => $analytics
        ]);
    }

    public function student()
    {
        requireLogin();
        requireRole(['student']);
        
        require_once __DIR__ . '/../Models/Exam.php';
        require_once __DIR__ . '/../Models/User.php';
        global $conn;
        
        $examModel = new Exam($conn);
        $userModel = new User($conn);
        $user = $userModel->getUserById($_SESSION['user_id']);
        
        $upcomingExams = $examModel->getAvailableExamsForStudent($user['department_id']);
        
        $statsQuery = "SELECT COUNT(id) as taken, SUM(score) as sum_score, SUM(total_marks) as sum_total FROM exam_attempts WHERE user_id = ? AND is_submitted = 1";
        $stmt = $conn->prepare($statsQuery);
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stats = $stmt->get_result()->fetch_assoc();
        
        $avgScore = '--';
        $examsTaken = $stats['taken'] > 0 ? (int)$stats['taken'] : '--';
        
        if ($stats['taken'] > 0 && $stats['sum_total'] > 0) {
            $avgScore = round(($stats['sum_score'] / $stats['sum_total']) * 100, 1) . '%';
        }
        
        view('dashboard/student', [
            'upcomingExams' => $upcomingExams,
            'avgScore' => $avgScore,
            'examsTaken' => $examsTaken
        ]);
    }
}
