ALTER TABLE notices ADD COLUMN priority ENUM('Low', 'Medium', 'High') DEFAULT 'Low' AFTER status;
