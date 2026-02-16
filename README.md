# Role Based Notice System

A PHP-based web application for managing notices with a multi-role approval workflow.

## Features
-   **Multi-Role System**: Super Admin, Admin, Teacher, Student.
-   **Notice Workflow**: Creation -> Admin Approval -> Teacher Publishing -> Student Viewing.
-   **Rejection Handling**: Detailed feedback loops for rejected notices.
-   **Dashboard**: Role-specific dashboards with relevant actions.
-   **Notifications**: Real-time alerts for system events.

## Setup
1.  Import `setup.sql` into your MySQL database.
2.  Configure database credentials in `config/db.php`.
3.  Serve the application via XAMPP/Apache.

## Default Credentials
All users have the password: `password123`

-   **Super Admin**: `superadmin`
-   **Admin**: `admin`
-   **Teacher**: `teacher`
-   **Student**: `student`
