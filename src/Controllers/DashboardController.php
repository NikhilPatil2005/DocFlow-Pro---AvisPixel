<?php

class DashboardController
{

    public function superAdmin()
    {
        requireLogin();
        requireRole(['super_admin']);

        require_once __DIR__ . '/../Models/Notice.php';
        global $conn;
        $noticeModel = new Notice($conn);

        $counts = $noticeModel->getCountsByStatus();

        $filterStatus = $_GET['status'] ?? null;
        if ($filterStatus == 'pending')
            $filterStatus = 'pending_admin';
        if ($filterStatus == 'published')
            $filterStatus = 'teacher_published'; // or any published? Let's use strict for now or 'teacher_published'

        // If generic 'published' is requested, maybe we want all things visible to students?
        // But for counts we did LIKE '%published%'.
        // Let's stick to what NoticeModel supports.

        $notices = [];
        if ($filterStatus) {
            // Mapping for simpler URL params to DB status
            if ($filterStatus == 'rejected') {
                // rejected matches admin_rejected OR teacher_rejected
                // But getAllByStatus takes exact match?
                // Let's rely on dashboard view to filter or add a better method in model.
                // Actually, let's keep it simple: If filtered, we might need a custom query or strict match.
                // Let's just use getAllForSuperAdmin and filter in PHP for now to match the existing logic if we don't want to overcomplicate Model.
                // OR, improved `getAllByStatus`?
                // Let's use `getAllForSuperAdmin` as base, and filter.
                $allNotices = $noticeModel->getAllForSuperAdmin();
                foreach ($allNotices as $n) {
                    if (strpos($n['status'], $_GET['status']) !== false) {
                        $notices[] = $n;
                    }
                }
            }
            else {
                $notices = $noticeModel->getAllByStatus($filterStatus);
            }
        }
        else {
            $notices = $noticeModel->getAllForSuperAdmin();
        }

        view('dashboard/super_admin', ['counts' => $counts, 'notices' => $notices]);
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
