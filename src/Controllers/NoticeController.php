<?php

require_once __DIR__ . '/../Models/Notice.php';
require_once __DIR__ . '/../Models/Log.php';
require_once __DIR__ . '/../Models/Notification.php';

class NoticeController
{
    private $noticeModel;
    private $logModel;
    private $notifModel;

    public function __construct($db)
    {
        $this->noticeModel = new Notice($db);
        $this->logModel = new Log($db);
        $this->notifModel = new Notification($db);
    }

    private function logAndNotify($noticeId, $action, $oldStatus, $newStatus, $details = null)
    {
        $user = currentUser();
        $role = currentRole();

        // Log
        $this->logModel->create($noticeId, $user, $role, $action, $oldStatus, $newStatus, $details);

        // Notify
        switch ($action) {
            case 'notice_created':
                $this->notifModel->createForRole('admin', "New Notice Created: #$noticeId");
                break;
            case 'admin_approved':
                $this->notifModel->createForRole('teacher', "Notice Approved by Admin: #$noticeId");
                break;
            case 'admin_rejected':
                $notice = $this->noticeModel->getById($noticeId);
                if ($notice) {
                    $this->notifModel->create($notice['created_by'], "Notice Rejected by Admin: #$noticeId. Reason: $details");
                }
                break;
            case 'teacher_published':
                $this->notifModel->createForRole('student', "New Notice Published: #$noticeId");
                break;
            case 'teacher_rejected':
                $this->notifModel->createForRole('admin', "Notice Rejected by Teacher: #$noticeId. Reason: $details");
                break;
            case 'notice_resubmitted':
                $this->notifModel->createForRole('admin', "Notice Resubmitted: #$noticeId");
                break;
        }
    }

    public function create()
    {
        requireLogin();
        requireRole(['super_admin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $priority = $_POST['priority'] ?? 'Low';
            $createdBy = currentUser();

            $noticeId = $this->noticeModel->create($title, $content, $createdBy, $priority);
            if ($noticeId) {
                $this->logAndNotify($noticeId, 'notice_created', null, 'pending_admin');
                redirect('index.php?action=super_admin_dashboard');
            }
            else {
                view('notices/create', ['error' => 'Failed to create notice.']);
            }
        }
        else {
            view('notices/create');
        }
    }

    public function approve()
    {
        requireLogin();
        requireRole(['admin']);

        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->noticeModel->updateStatus($id, 'admin_approved');
            $this->logAndNotify($id, 'admin_approved', 'pending_admin', 'admin_approved');
            redirect('index.php?action=notice_approvals&success=Notice approved successfully');
        }
    }

    public function reject()
    {
        requireLogin();

        $id = $_GET['id'] ?? null;
        $reason = $_POST['reason'] ?? '';

        if (!$id)
            die("Invalid ID");

        $role = currentRole();
        $oldStatus = '';
        $targetStatus = '';

        if ($role === 'admin') {
            $oldStatus = 'pending_admin';
            $targetStatus = 'admin_rejected';
        }
        elseif ($role === 'teacher') {
            $oldStatus = 'admin_approved';
            $targetStatus = 'teacher_rejected';
        }
        else {
            die("Unauthorized");
        }

        if ($this->noticeModel->updateStatus($id, $targetStatus, $reason)) {
            $this->logAndNotify($id, "{$role}_rejected", $oldStatus, $targetStatus, $reason);
            redirect("index.php?action=notice_approvals&success=Notice rejected successfully");
        }
    }

    public function publish()
    {
        requireLogin();
        requireRole(['teacher']);

        $id = $_GET['id'] ?? null;
        if ($id && $this->noticeModel->updateStatus($id, 'teacher_published')) {
            $this->logAndNotify($id, 'teacher_published', 'admin_approved', 'teacher_published');
            redirect('index.php?action=notice_approvals&success=Notice published successfully');
        }
    }

    public function edit()
    {
        requireLogin();
        requireRole(['super_admin']);

        $id = $_GET['id'] ?? null;
        if (!$id)
            redirect('index.php?action=super_admin_dashboard');

        $notice = $this->noticeModel->getById($id);

        if (!$notice)
            die("Notice not found");
        if ($notice['status'] !== 'admin_rejected')
            die("Only rejected notices can be edited.");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $priority = $_POST['priority'] ?? 'Low';

            if ($this->noticeModel->update($id, $title, $content, $priority)) {
                $this->noticeModel->updateStatus($id, 'pending_admin');
                $this->logAndNotify($id, 'notice_resubmitted', 'admin_rejected', 'pending_admin');
                redirect('index.php?action=super_admin_dashboard');
            }
        }
        else {
            view('notices/edit', ['notice' => $notice]);
        }
    }

    public function resubmit()
    {
        $this->edit();
    }

    public function view()
    {
        requireLogin();
        $id = $_GET['id'] ?? null;
        if (!$id)
            redirect('index.php');

        $notice = $this->noticeModel->getById($id);
        if (!$notice)
            die("Notice not found");

        // Access Control for View
        $role = currentRole();
        $canView = false;
        if ($role === 'super_admin')
            $canView = true;
        if ($role === 'admin' && in_array($notice['status'], ['pending_admin', 'admin_approved', 'teacher_published', 'teacher_rejected', 'admin_rejected']))
            $canView = true;
        if ($role === 'teacher' && in_array($notice['status'], ['admin_approved', 'teacher_published', 'teacher_rejected']))
            $canView = true;
        if ($role === 'student' && $notice['status'] === 'teacher_published')
            $canView = true;

        if (!$canView)
            die("Access Denied");

        // Log View for Student
        if ($role === 'student') {
            // Check if already viewed to avoid duplicate logs if strict, but let's just log it.
            // Actually, requirements: "When student opens notice: Create log: student_viewed"
            // Using INSERT IGNORE or checking first.
            // Let's use the log model.
            $this->logModel->create($id, currentUser(), $role, 'student_viewed', $notice['status'], $notice['status']);

            // Also update read_receipts
            global $conn;
            $studentId = currentUser();
            $conn->query("INSERT IGNORE INTO read_receipts (notice_id, student_id) VALUES ($id, $studentId)");
        }

        // Fetch logs
        $logs = $this->logModel->getLogsByNoticeId($id);

        view('notices/view', ['notice' => $notice, 'logs' => $logs]);
    }
}
