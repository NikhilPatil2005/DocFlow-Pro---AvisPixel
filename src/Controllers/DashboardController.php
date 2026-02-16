<?php

class DashboardController
{

    public function superAdmin()
    {
        requireLogin();
        requireRole(['super_admin']);
        view('dashboard/super_admin');
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
