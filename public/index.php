<?php
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/functions.php';
require_once __DIR__ . '/../src/Controllers/AuthController.php';
require_once __DIR__ . '/../src/Controllers/DashboardController.php';
require_once __DIR__ . '/../src/Controllers/NoticeController.php';
require_once __DIR__ . '/../src/Controllers/ApprovalController.php';
require_once __DIR__ . '/../src/Controllers/UserController.php';

$action = $_GET['action'] ?? 'login';

$authController = new AuthController($conn);
$dashboardController = new DashboardController();
$noticeController = new NoticeController($conn);
$approvalController = new ApprovalController($conn);
$userController = new UserController($conn);

switch ($action) {
    case 'login':
        if (isLoggedIn()) {
            // Redirect to appropriate dashboard if already logged in
            switch (currentRole()) {
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

    // Dedicated Pages
    case 'registration_requests':
        $dashboardController->registrationRequests();
        break;
    case 'notice_approvals':
        $dashboardController->noticeApprovals();
        break;

    // Dashboards
    case 'super_admin_dashboard':
        $dashboardController->superAdmin();
        break;
    case 'admin_dashboard':
        $dashboardController->admin();
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
    case 'delete_user':
        $userController->deleteUser();
        break;

    default:
        // 404 Not Found
        echo "404 Not Found";
        break;
}
?>