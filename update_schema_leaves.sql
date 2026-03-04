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
