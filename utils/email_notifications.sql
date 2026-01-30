-- Create notifications table
CREATE TABLE IF NOT EXISTS `notifications` (
    `notification_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` VARCHAR(100) NOT NULL,
    `user_type` ENUM('student', 'instructor', 'exam_committee', 'admin') NOT NULL,
    `notification_type` VARCHAR(50) NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `link` VARCHAR(255),
    `is_read` TINYINT(1) DEFAULT 0,
    `is_emailed` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_user` (`user_id`, `user_type`),
    INDEX `idx_read` (`is_read`),
    INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create email_settings table
CREATE TABLE IF NOT EXISTS `email_settings` (
    `setting_id` INT AUTO_INCREMENT PRIMARY KEY,
    `smtp_host` VARCHAR(100),
    `smtp_port` INT DEFAULT 587,
    `smtp_username` VARCHAR(100),
    `smtp_password` VARCHAR(255),
    `from_email` VARCHAR(100),
    `from_name` VARCHAR(100),
    `email_enabled` TINYINT(1) DEFAULT 0,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default email settings
INSERT INTO email_settings (from_email, from_name, email_enabled) 
VALUES ('noreply@dmu.edu', 'DMU Online Examination System', 0)
ON DUPLICATE KEY UPDATE from_email = from_email;

-- Create notification_preferences table
CREATE TABLE IF NOT EXISTS `notification_preferences` (
    `pref_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` VARCHAR(100) NOT NULL,
    `user_type` ENUM('student', 'instructor', 'exam_committee', 'admin') NOT NULL,
    `email_notifications` TINYINT(1) DEFAULT 1,
    `exam_scheduled` TINYINT(1) DEFAULT 1,
    `results_ready` TINYINT(1) DEFAULT 1,
    `revision_requested` TINYINT(1) DEFAULT 1,
    `question_approved` TINYINT(1) DEFAULT 1,
    `system_alerts` TINYINT(1) DEFAULT 1,
    UNIQUE KEY `unique_user` (`user_id`, `user_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
