-- Create question_topics table
CREATE TABLE IF NOT EXISTS `question_topics` (
    `topic_id` INT AUTO_INCREMENT PRIMARY KEY,
    `topic_name` VARCHAR(100) NOT NULL,
    `topic_description` TEXT,
    `course_name` VARCHAR(100),
    `chapter_number` INT,
    `created_by` VARCHAR(100),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_topic` (`topic_name`, `course_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add topic_id column to question_page table
ALTER TABLE question_page 
ADD COLUMN IF NOT EXISTS topic_id INT AFTER course_name,
ADD COLUMN IF NOT EXISTS topic_name VARCHAR(100) AFTER topic_id;

-- Add topic_id column to truefalse_question table
ALTER TABLE truefalse_question 
ADD COLUMN IF NOT EXISTS topic_id INT AFTER course_name,
ADD COLUMN IF NOT EXISTS topic_name VARCHAR(100) AFTER topic_id;

-- Insert some default topics
INSERT IGNORE INTO question_topics (topic_name, topic_description, course_name, chapter_number) VALUES
('Introduction', 'Basic concepts and fundamentals', 'General', 1),
('Theory', 'Theoretical concepts and principles', 'General', 2),
('Application', 'Practical applications and examples', 'General', 3),
('Analysis', 'Critical thinking and analysis', 'General', 4),
('Synthesis', 'Integration and problem solving', 'General', 5);
