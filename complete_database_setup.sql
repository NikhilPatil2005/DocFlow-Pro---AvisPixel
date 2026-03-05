-- ===========================================
-- COMPLETE DATABASE SETUP FOR ROLE BASED NOTICE SYSTEM
-- ===========================================
-- This file contains all database setup and updates in the correct order
-- Execute this file once to set up the complete database

-- Create Database
CREATE DATABASE IF NOT EXISTS notice_system;
USE notice_system;

-- ===========================================
-- PHASE 1: BASE TABLES (from setup.sql)
-- ===========================================

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'admin', 'teacher', 'student') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Notices Table
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
        'teacher_rejected'
    ) DEFAULT 'pending_admin',
    rejection_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Notice Logs Table (Audit Trail)
CREATE TABLE IF NOT EXISTS notice_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    notice_id INT NOT NULL,
    performed_by INT NOT NULL,
    role ENUM('super_admin', 'admin', 'teacher', 'student') NOT NULL,
    action VARCHAR(50) NOT NULL, -- e.g., 'notice_created', 'admin_approved'
    old_status VARCHAR(50),
    new_status VARCHAR(50),
    details TEXT, -- For rejection reasons or other notes
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (notice_id) REFERENCES notices(id) ON DELETE CASCADE,
    FOREIGN KEY (performed_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Notifications Table
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Read Receipts Table (For Students)
CREATE TABLE IF NOT EXISTS read_receipts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    notice_id INT NOT NULL,
    student_id INT NOT NULL,
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (notice_id) REFERENCES notices(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE(notice_id, student_id) -- Ensure one record per student per notice
);

-- ===========================================
-- PHASE 2: USER REGISTRATION SYSTEM (from update_phase2.sql)
-- ===========================================

-- 1. Modify users table
ALTER TABLE users
ADD COLUMN status ENUM('pending', 'active', 'rejected', 'pending_super_admin') DEFAULT 'pending' AFTER role;

-- Set existing users to active
UPDATE users SET status = 'active' WHERE status = 'pending';

-- 2. Create user_documents table
CREATE TABLE IF NOT EXISTS user_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    document_type VARCHAR(50) NOT NULL, -- e.g., 'identity_proof', 'educational_certificate'
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 3. Create registration_approvals table
CREATE TABLE IF NOT EXISTS registration_approvals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    approved_by INT NOT NULL,
    role_at_time_of_approval VARCHAR(50) NOT NULL, -- role of the approver
    status_assigned VARCHAR(50) NOT NULL, -- 'active' or 'pending_super_admin' or 'rejected'
    remarks TEXT,
    approval_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE CASCADE
);

-- ===========================================
-- PHASE 3: PRIORITY SYSTEM (from update_schema.sql)
-- ===========================================

ALTER TABLE notices ADD COLUMN priority ENUM('Low', 'Medium', 'High') DEFAULT 'Low' AFTER status;

-- ===========================================
-- PHASE 4: LEAVES MANAGEMENT & ROLE MIGRATION (from update_schema_leaves.sql)
-- ===========================================

CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Enlarge ENUMs to allow migration
ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'principal', 'hod', 'teacher', 'student') NOT NULL;
ALTER TABLE users MODIFY COLUMN status ENUM('pending', 'pending_teacher', 'pending_admin', 'pending_super_admin', 'pending_principal', 'pending_hod', 'active', 'rejected') DEFAULT 'pending';

-- Migrate users roles
UPDATE users SET role = 'principal' WHERE role = 'admin';
UPDATE users SET role = 'admin' WHERE role = 'super_admin';

-- Restrict ENUMs to final set
ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'principal', 'hod', 'teacher', 'student') NOT NULL;

-- Update status migrations for new registration hierarchy
UPDATE users SET status = 'pending_principal' WHERE status = 'pending_admin';
UPDATE users SET status = 'pending_admin' WHERE status = 'pending_super_admin';

-- Modify users table to add new columns
ALTER TABLE users ADD COLUMN department_id INT NULL DEFAULT NULL;
ALTER TABLE users ADD COLUMN designation VARCHAR(100) NULL DEFAULT NULL;
ALTER TABLE users ADD FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL;

-- Create leaves table
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

-- Update notices and logs for the new role system
ALTER TABLE notices MODIFY COLUMN status ENUM('pending_admin', 'admin_approved', 'admin_rejected', 'teacher_published', 'teacher_rejected', 'pending_principal', 'principal_approved', 'principal_rejected') DEFAULT 'pending_principal';
UPDATE notices SET status = 'pending_principal' WHERE status = 'pending_admin';
UPDATE notices SET status = 'principal_approved' WHERE status = 'admin_approved';
UPDATE notices SET status = 'principal_rejected' WHERE status = 'admin_rejected';

ALTER TABLE notice_logs MODIFY COLUMN role ENUM('super_admin', 'admin', 'principal', 'hod', 'teacher', 'student') NOT NULL;
UPDATE notice_logs SET role = 'principal' WHERE role = 'admin';
UPDATE notice_logs SET role = 'admin' WHERE role = 'super_admin';
ALTER TABLE notice_logs MODIFY COLUMN role ENUM('admin', 'principal', 'hod', 'teacher', 'student') NOT NULL;

-- Seed default departments
INSERT IGNORE INTO departments (name) VALUES
('Computer Engineering'),
('Electrical Engineering'),
('Mechanical Engineering'),
('Civil Engineering'),
('Electronics and Telecommunication'),
('First Year Engineering (FY)');

-- ===========================================
-- DEFAULT USERS (from setup.sql + HOD addition)
-- ===========================================

-- Insert Default Users (Password: password123)
-- Hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
INSERT IGNORE INTO users (username, password, role, status) VALUES
('superadmin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active'),
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'principal', 'active'),
('hod', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hod', 'active'),
('teacher', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'active'),
('student', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active');

-- ===========================================
-- SETUP COMPLETE
-- ===========================================

-- Display completion message
SELECT 'Database setup completed successfully!' as Status;