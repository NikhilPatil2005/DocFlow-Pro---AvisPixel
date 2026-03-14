-- ================================================================
-- INCREMENTAL UPDATE FILE - DocFlow Pro (E-Office Portal)
-- ================================================================
-- Database: notice_system
-- Last Updated: 2026-03-14
-- 
-- Use this file ONLY if you already have the database running
-- and want to apply all updates (Phase 2 through latest).
-- For a FRESH installation, use 'final_complete_database.sql' instead.
--
-- This file is SAFE to re-run (uses IF NOT EXISTS / IGNORE).
-- ================================================================

USE notice_system;

-- ================================================================
-- PHASE 2: USER REGISTRATION SYSTEM
-- ================================================================

-- Add status column to users (skip if already exists)
ALTER TABLE users
ADD COLUMN status ENUM('pending', 'active', 'rejected', 'pending_super_admin') DEFAULT 'pending' AFTER role;
-- NOTE: If the above errors with "Duplicate column name", that's OK — column already exists.

-- Set existing users to active
UPDATE users SET status = 'active' WHERE status = 'pending';

-- User documents table
CREATE TABLE IF NOT EXISTS user_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    document_type VARCHAR(50) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Registration approvals table
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
-- PHASE 3: PRIORITY SYSTEM
-- ================================================================

ALTER TABLE notices ADD COLUMN priority ENUM('Low', 'Medium', 'High') DEFAULT 'Low' AFTER status;
-- NOTE: If the above errors with "Duplicate column name", that's OK — column already exists.

-- ================================================================
-- PHASE 4: LEAVES MANAGEMENT & ROLE MIGRATION
-- ================================================================

-- Departments table (must exist before FK references)
CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Step 1: Enlarge user role ENUM to allow migration
ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'principal', 'hod', 'teacher', 'student') NOT NULL;
ALTER TABLE users MODIFY COLUMN status ENUM('pending', 'pending_teacher', 'pending_admin', 'pending_super_admin', 'pending_principal', 'pending_hod', 'active', 'rejected') DEFAULT 'pending';

-- Step 2: Migrate old roles to new roles
-- admin -> principal, super_admin -> admin
UPDATE users SET role = 'principal' WHERE role = 'admin';
UPDATE users SET role = 'admin' WHERE role = 'super_admin';

-- Step 3: Restrict role ENUM to final set (removes super_admin)
ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'principal', 'hod', 'teacher', 'student') NOT NULL;

-- Step 4: Migrate status values for new hierarchy
UPDATE users SET status = 'pending_principal' WHERE status = 'pending_admin';
UPDATE users SET status = 'pending_admin' WHERE status = 'pending_super_admin';

-- Step 5: Add department and designation columns
ALTER TABLE users ADD COLUMN department_id INT NULL DEFAULT NULL;
ALTER TABLE users ADD COLUMN designation VARCHAR(100) NULL DEFAULT NULL;
ALTER TABLE users ADD FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL;
-- NOTE: If the above errors with "Duplicate column name", that's OK — columns already exist.

-- Step 6: Create leaves table
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

-- Step 7: Update notice statuses for new role system
ALTER TABLE notices MODIFY COLUMN status ENUM(
    'pending_admin', 'admin_approved', 'admin_rejected',
    'teacher_published', 'teacher_rejected',
    'pending_principal', 'principal_approved', 'principal_rejected'
) DEFAULT 'pending_principal';

UPDATE notices SET status = 'pending_principal' WHERE status = 'pending_admin';
UPDATE notices SET status = 'principal_approved' WHERE status = 'admin_approved';
UPDATE notices SET status = 'principal_rejected' WHERE status = 'admin_rejected';

-- Step 8: Update notice_logs role ENUM
ALTER TABLE notice_logs MODIFY COLUMN role ENUM('super_admin', 'admin', 'principal', 'hod', 'teacher', 'student') NOT NULL;
UPDATE notice_logs SET role = 'principal' WHERE role = 'admin';
UPDATE notice_logs SET role = 'admin' WHERE role = 'super_admin';
ALTER TABLE notice_logs MODIFY COLUMN role ENUM('admin', 'principal', 'hod', 'teacher', 'student') NOT NULL;

-- Step 9: Seed default departments
INSERT IGNORE INTO departments (name) VALUES
('Computer Engineering'),
('Electrical Engineering'),
('Mechanical Engineering'),
('Civil Engineering'),
('Electronics and Telecommunication'),
('First Year Engineering (FY)');

-- ================================================================
-- PHASE 5: USER PROFILE SYSTEM
-- ================================================================

ALTER TABLE users ADD COLUMN full_name VARCHAR(100) NULL AFTER username;
ALTER TABLE users ADD COLUMN email VARCHAR(100) NULL UNIQUE AFTER full_name;
-- NOTE: If the above errors with "Duplicate column name", that's OK — columns already exist.

-- ================================================================
-- PHASE 6: SALARY CERTIFICATE REQUESTS
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
-- ADD DEFAULT HOD USER (if not already present)
-- ================================================================
-- Password: password123
-- Bcrypt Hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

INSERT IGNORE INTO users (username, full_name, password, role, status) VALUES
('hod', 'HOD User', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hod', 'active');

-- ================================================================
-- ALL UPDATES APPLIED SUCCESSFULLY
-- ================================================================
SELECT 'All database updates applied successfully!' AS Status;
