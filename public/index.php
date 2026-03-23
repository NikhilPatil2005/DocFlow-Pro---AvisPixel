<?php
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/functions.php';
require_once __DIR__ . '/../src/Controllers/AuthController.php';
require_once __DIR__ . '/../src/Controllers/DashboardController.php';
require_once __DIR__ . '/../src/Controllers/NoticeController.php';
require_once __DIR__ . '/../src/Controllers/ApprovalController.php';
require_once __DIR__ . '/../src/Controllers/UserController.php';
require_once __DIR__ . '/../src/Controllers/LeaveController.php';
require_once __DIR__ . '/../src/Controllers/SalaryCertificateController.php';
require_once __DIR__ . '/../src/Controllers/ProfileController.php';
require_once __DIR__ . '/../src/Controllers/ExamController.php';

$action = $_GET['action'] ?? 'login';

$authController = new AuthController($conn);
$dashboardController = new DashboardController();
$noticeController = new NoticeController($conn);
$approvalController = new ApprovalController($conn);
$userController = new UserController($conn);
$leaveController = new LeaveController($conn);
$salaryController = new SalaryCertificateController($conn);
$profileController = new ProfileController($conn);
$examController = new ExamController($conn);

switch ($action) {
    case 'login':
        if (isLoggedIn()) {
            // Redirect to appropriate dashboard if already logged in
            switch (currentRole()) {
                case 'admin':
                    redirect('index.php?action=admin_dashboard');
                    break;
                case 'principal':
                    redirect('index.php?action=principal_dashboard');
                    break;
                case 'hod':
                    redirect('index.php?action=hod_dashboard');
                    break;
                case 'teacher':
                    redirect('index.php?action=teacher_dashboard');
                    break;
                case 'student':
                    redirect('index.php?action=student_dashboard');
                    break;
            }
        }
        $authController->login();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'register':
        $authController->register();
        break;
    case 'check_status':
        $authController->checkStatus();
        break;

    // Notices
    case 'create_notice':
        $noticeController->create();
        break;
    case 'approve_notice':
        $noticeController->approve();
        break;
    case 'reject_notice':
        $noticeController->reject();
        break;
    case 'publish_notice':
        $noticeController->publish();
        break;
    case 'resubmit_notice':
        $noticeController->resubmit();
        break;
    case 'edit_notice':
        $noticeController->edit();
        break;
    case 'view_notice':
        $noticeController->view();
        break;

    // Leave Management
    case 'apply_leave':
        $leaveController->apply();
        break;
    case 'manage_leaves':
        $leaveController->manage();
        break;
    case 'approve_leave':
        $leaveController->approve();
        break;
    case 'reject_leave':
        $leaveController->reject();
        break;
    case 'my_leaves':
        $leaveController->myHistory();
        break;

    // Salary Certificate System
    case 'apply_salary':
        $salaryController->apply();
        break;
    case 'my_salary_certificates':
        $salaryController->myCertificates();
        break;
    case 'manage_salary_requests':
        $salaryController->manage();
        break;
    case 'view_salary_request':
        $salaryController->viewRequest();
        break;
    case 'approve_salary_request':
        $salaryController->approve();
        break;
    case 'reject_salary_request':
        $salaryController->reject();
        break;
    case 'print_salary_certificate':
        $salaryController->printCertificate();
        break;

    // User Approvals
    case 'approve_user':
        $approvalController->approve();
        break;
    case 'reject_user':
        $approvalController->reject();
        break;

    // Notifications
    case 'notifications':
        requireLogin();
        view('dashboard/notifications');
        break;
    case 'mark_notifications_read':
        requireLogin();
        require_once __DIR__ . '/../src/Models/Notification.php';
        $notifModel = new Notification($conn);
        $notifModel->markAsRead(currentUser());
        redirect('index.php?action=notifications');
        break;

    // Profile Management
    case 'profile':
        $profileController->index();
        break;
    case 'update_profile':
        $profileController->updateProfile();
        break;
    case 'update_password':
        $profileController->updatePassword();
        break;

    // Examinations (Teacher)
    case 'teacher_exams':
        $examController->teacherIndex();
        break;
    case 'create_exam':
        $examController->create();
        break;
    case 'store_exam':
        $examController->store();
        break;
    case 'manage_exam_questions':
        $examController->manageQuestions();
        break;
    case 'store_exam_question':
        $examController->storeQuestion();
        break;
    case 'publish_exam':
        $examController->publish();
        break;
    case 'teacher_exam_results':
        $examController->teacherResults();
        break;

    // Examinations (Student)
    case 'student_exams':
        $examController->studentIndex();
        break;
    case 'attempt_exam':
        $examController->attempt();
        break;
    case 'save_exam_answer':
        $examController->saveAnswer();
        break;
    case 'submit_exam_attempt':
        $examController->submitAttempt();
        break;
    case 'student_exam_result':
        $examController->studentResult();
        break;

    // Dedicated Pages
    case 'registration_requests':
        $dashboardController->registrationRequests();
        break;
    case 'notice_approvals':
        $dashboardController->noticeApprovals();
        break;

    // Dashboards
    case 'admin_dashboard':
        $dashboardController->admin();
        break;
    case 'principal_dashboard':
        $dashboardController->principal();
        break;
    case 'hod_dashboard':
        $dashboardController->hod();
        break;
    case 'teacher_dashboard':
        $dashboardController->teacher();
        break;
    case 'student_dashboard':
        $dashboardController->student();
        break;

    // User Management
    case 'manage_users':
        $userController->index();
        break;
    case 'view_user':
        $userController->viewUser();
        break;
    case 'update_user_status':
        $userController->updateUserStatus();
        break;
    case 'update_user_role':
        $userController->updateUserRole();
        break;
    case 'update_user_department':
        $userController->updateUserDepartment();
        break;
    case 'delete_user':
        $userController->deleteUser();
        break;

    default:
        // 404 Not Found
        echo "404 Not Found";
        break;
}
?>