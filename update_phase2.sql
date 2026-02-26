-- Phase 2 Database Changes

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
