-- ================================================================
-- FINAL COMPLETE DATABASE SETUP - DocFlow Pro (E-Office Portal)
-- ================================================================
-- Database: notice_system
-- Last Updated: 2026-03-14
-- 
-- This file creates the ENTIRE database from scratch.
-- Use this for a FRESH installation only.
-- If you already have data, use 'final_update_existing_database.sql' instead.
-- ================================================================

-- Create Database
CREATE DATABASE IF NOT EXISTS notice_system;
USE notice_system;

-- ================================================================
-- TABLE 1: departments
-- ================================================================
-- Must be created before users (foreign key dependency)

CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Seed default departments
INSERT IGNORE INTO departments (name) VALUES
('Computer Engineering'),
('Electrical Engineering'),
('Mechanical Engineering'),
('Civil Engineering'),
('Electronics and Telecommunication'),
('First Year Engineering (FY)');

-- ================================================================
-- TABLE 2: users
-- ================================================================
-- Roles: admin, principal, hod, teacher, student
-- Status: pending, pending_teacher, pending_admin, pending_principal, 
--         pending_hod, active, rejected

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(100) NULL,
    email VARCHAR(100) NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'principal', 'hod', 'teacher', 'student') NOT NULL,
    status ENUM('pending', 'pending_teacher', 'pending_admin', 'pending_super_admin', 'pending_principal', 'pending_hod', 'active', 'rejected') DEFAULT 'pending',
    department_id INT NULL DEFAULT NULL,
    designation VARCHAR(100) NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
);

-- ================================================================
-- TABLE 3: notices
-- ================================================================

CREATE TABLE IF NOT EXISTS notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_by INT NOT NULL,
    status ENUM(
        'pending_admin',
        'admin_approved',
        'admin_rejected',
        'teacher_published',
        'teacher_rejected',
        'pending_principal',
        'principal_approved',
        'principal_rejected'
    ) DEFAULT 'pending_principal',
    priority ENUM('Low', 'Medium', 'High') DEFAULT 'Low',
    rejection_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- ================================================================
-- TABLE 4: notice_logs (Audit Trail)
-- ================================================================

CREATE TABLE IF NOT EXISTS notice_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    notice_id INT NOT NULL,
    performed_by INT NOT NULL,
    role ENUM('admin', 'principal', 'hod', 'teacher', 'student') NOT NULL,
    action VARCHAR(50) NOT NULL,
    old_status VARCHAR(50),
    new_status VARCHAR(50),
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (notice_id) REFERENCES notices(id) ON DELETE CASCADE,
    FOREIGN KEY (performed_by) REFERENCES users(id) ON DELETE CASCADE
);

-- ================================================================
-- TABLE 5: notifications
-- ================================================================

CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ================================================================
-- TABLE 6: read_receipts (Student Notice View Tracking)
-- ================================================================

CREATE TABLE IF NOT EXISTS read_receipts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    notice_id INT NOT NULL,
    student_id INT NOT NULL,
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (notice_id) REFERENCES notices(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE(notice_id, student_id)
);

-- ================================================================
-- TABLE 7: user_documents (Registration Document Uploads)
-- ================================================================

CREATE TABLE IF NOT EXISTS user_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    document_type VARCHAR(50) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ================================================================
-- TABLE 8: registration_approvals (Approval Audit Trail)
-- ================================================================

CREATE TABLE IF NOT EXISTS registration_approvals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    approved_by INT NOT NULL,
    role_at_time_of_approval VARCHAR(50) NOT NULL,
    status_assigned VARCHAR(50) NOT NULL,
    remarks TEXT,
    approval_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE CASCADE
);

-- ================================================================
-- TABLE 9: leaves (Leave Management System)
-- ================================================================

CREATE TABLE IF NOT EXISTS leaves (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    department_id INT NOT NULL,
    leave_date DATE NOT NULL,
    time_from TIME NOT NULL,
    time_to TIME NOT NULL,
    venue VARCHAR(255) NOT NULL,
    reason TEXT NOT NULL,
    workload_adjusted_with VARCHAR(255),
    status ENUM('pending_hod', 'pending_principal', 'approved', 'rejected') DEFAULT 'pending_hod',
    hod_id INT,
    principal_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE,
    FOREIGN KEY (hod_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (principal_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ================================================================
-- TABLE 10: salary_certificate_requests
-- ================================================================

CREATE TABLE IF NOT EXISTS salary_certificate_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT NOT NULL,
    designation VARCHAR(100),
    department_id INT,
    from_date DATE NOT NULL,
    to_date DATE NOT NULL,
    purpose TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    principal_signature VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approved_at TIMESTAMP NULL,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
);

-- ================================================================
-- DEFAULT USERS (Password for all: password123)
-- ================================================================
-- Bcrypt Hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

INSERT IGNORE INTO users (username, full_name, password, role, status) VALUES
('superadmin', 'Super Admin',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin',     'active'),
('admin',      'Principal User', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'principal', 'active'),
('hod',        'HOD User',       '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hod',       'active'),
('teacher',    'Teacher User',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher',   'active'),
('student',    'Student User',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student',   'active');

-- ================================================================
-- SETUP COMPLETE
-- ================================================================
SELECT 'Database setup completed successfully! All 10 tables created.' AS Status;
