-- Create table to store student answers for review
CREATE TABLE IF NOT EXISTS `student_answers` (
  `answer_id` INT AUTO_INCREMENT PRIMARY KEY,
  `result_id` INT NOT NULL,
  `student_id` VARCHAR(50) NOT NULL,
  `question_id` INT NOT NULL,
  `selected_answer` CHAR(1),
  `is_correct` TINYINT(1) DEFAULT 0,
  `answered_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_result` (`result_id`),
  INDEX `idx_student` (`student_id`),
  INDEX `idx_question` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
