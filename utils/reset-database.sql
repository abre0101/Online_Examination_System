-- ============================================
-- OES Database Reset Script
-- This script will clear all data from tables
-- while keeping the structure intact
-- ============================================

-- Disable foreign key checks temporarily
SET FOREIGN_KEY_CHECKS = 0;

-- Clear all data from tables
TRUNCATE TABLE `admin`;
TRUNCATE TABLE `ans_matching`;
TRUNCATE TABLE `choose`;
TRUNCATE TABLE `course`;
TRUNCATE TABLE `department`;
TRUNCATE TABLE `exam_category`;
TRUNCATE TABLE `exam_committee`;
TRUNCATE TABLE `faculty`;
TRUNCATE TABLE `instructor`;
TRUNCATE TABLE `matching`;
TRUNCATE TABLE `question_page`;
TRUNCATE TABLE `question_type`;
TRUNCATE TABLE `que_matching`;
TRUNCATE TABLE `result`;
TRUNCATE TABLE `schedule`;
TRUNCATE TABLE `student`;
TRUNCATE TABLE `truefalse_question`;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Reset auto-increment counters
ALTER TABLE `exam_category` AUTO_INCREMENT = 1;
ALTER TABLE `question_page` AUTO_INCREMENT = 1;
ALTER TABLE `question_type` AUTO_INCREMENT = 1;
ALTER TABLE `result` AUTO_INCREMENT = 1;
ALTER TABLE `schedule` AUTO_INCREMENT = 1;
ALTER TABLE `truefalse_question` AUTO_INCREMENT = 1;

-- Insert default admin account
INSERT INTO `admin` (`Admin_ID`, `Admin_Name`, `username`, `password`, `email`) VALUES
('admin1', 'System Admin', 'admin', 'admin123', 'admin@oes.edu');

-- Insert sample departments
INSERT INTO `department` (`deptno`, `dept_name`, `faculty_name`) VALUES
('DEPT001', 'Computer Science', 'Engineering'),
('DEPT002', 'Information Technology', 'Engineering'),
('DEPT003', 'Software Engineering', 'Engineering');

-- Insert sample faculty
INSERT INTO `faculty` (`faculty_id`, `faculty_name`) VALUES
('FAC001', 'Engineering'),
('FAC002', 'Science'),
('FAC003', 'Health Sciences');

-- Insert sample exam categories
INSERT INTO `exam_category` (`exam_id`, `exam_name`) VALUES
(1, 'Midterm Exam'),
(2, 'Final Exam'),
(3, 'Quiz'),
(4, 'Practice Test');

-- Insert sample courses
INSERT INTO `course` (`course_id`, `course_name`, `credit_hr`, `dept_name`, `semister`, `Inst_Name`) VALUES
('CS101', 'Introduction to Programming', 3, 'Computer Science', 1, 'Instructor 1'),
('CS102', 'Data Structures', 4, 'Computer Science', 2, 'Instructor 2'),
('IT101', 'Database Systems', 3, 'Information Technology', 1, 'Instructor 3'),
('SE101', 'Software Engineering', 4, 'Software Engineering', 2, 'Instructor 4');

-- Insert sample instructors
INSERT INTO `instructor` (`Inst_ID`, `Inst_Name`, `username`, `password`, `email`, `dept_name`, `faculty_name`) VALUES
('INST001', 'Instructor 1', 'inst1', 'pass123', 'inst1@oes.edu', 'Computer Science', 'Engineering'),
('INST002', 'Instructor 2', 'inst2', 'pass123', 'inst2@oes.edu', 'Computer Science', 'Engineering'),
('INST003', 'Instructor 3', 'inst3', 'pass123', 'inst3@oes.edu', 'Information Technology', 'Engineering'),
('INST004', 'Instructor 4', 'inst4', 'pass123', 'inst4@oes.edu', 'Software Engineering', 'Engineering');

-- Insert sample students
INSERT INTO `student` (`Id`, `Name`, `Sex`, `dept_name`, `semister`, `username`, `password`, `Status`, `email`, `year`) VALUES
('STU001', 'Student One', 'M', 'Computer Science', 1, 'student1', 'pass123', 'Active', 'student1@oes.edu', '2024'),
('STU002', 'Student Two', 'F', 'Computer Science', 2, 'student2', 'pass123', 'Active', 'student2@oes.edu', '2024'),
('STU003', 'Student Three', 'M', 'Information Technology', 1, 'student3', 'pass123', 'Active', 'student3@oes.edu', '2024');

-- Insert sample questions for practice
INSERT INTO `question_page` (`question_id`, `exam_id`, `course_name`, `semester`, `question`, `Option1`, `Option2`, `Option3`, `Option4`, `Answer`) VALUES
(1, 1, 'Introduction to Programming', 1, 'What is a variable in programming?', 'A storage location', 'A function', 'A loop', 'A class', 'A'),
(2, 1, 'Introduction to Programming', 1, 'Which of the following is a programming language?', 'HTML', 'CSS', 'Python', 'JSON', 'C'),
(3, 1, 'Introduction to Programming', 1, 'What does IDE stand for?', 'Integrated Development Environment', 'Internet Data Exchange', 'Internal Design Engine', 'Interactive Display Editor', 'A'),
(4, 1, 'Introduction to Programming', 1, 'What is the purpose of a loop?', 'To repeat code', 'To store data', 'To define functions', 'To create variables', 'A'),
(5, 2, 'Data Structures', 2, 'What is an array?', 'A collection of elements', 'A single value', 'A function', 'A class', 'A'),
(6, 2, 'Data Structures', 2, 'Which data structure uses LIFO?', 'Queue', 'Stack', 'Array', 'Tree', 'B'),
(7, 2, 'Data Structures', 2, 'What is the time complexity of binary search?', 'O(n)', 'O(log n)', 'O(n^2)', 'O(1)', 'B'),
(8, 2, 'Data Structures', 2, 'Which is a linear data structure?', 'Tree', 'Graph', 'Linked List', 'Hash Table', 'C'),
(9, 3, 'Database Systems', 1, 'What does SQL stand for?', 'Structured Query Language', 'Simple Question Language', 'System Quality Level', 'Standard Query List', 'A'),
(10, 3, 'Database Systems', 1, 'Which command is used to retrieve data?', 'INSERT', 'UPDATE', 'SELECT', 'DELETE', 'C'),
(11, 3, 'Database Systems', 1, 'What is a primary key?', 'A unique identifier', 'A foreign reference', 'An index', 'A constraint', 'A'),
(12, 3, 'Database Systems', 1, 'Which is a DDL command?', 'SELECT', 'INSERT', 'CREATE', 'UPDATE', 'C'),
(13, 4, 'Software Engineering', 2, 'What is SDLC?', 'Software Development Life Cycle', 'System Design Logic Code', 'Standard Data Link Control', 'Secure Development Language Compiler', 'A'),
(14, 4, 'Software Engineering', 2, 'Which is an Agile methodology?', 'Waterfall', 'Scrum', 'V-Model', 'Spiral', 'B'),
(15, 4, 'Software Engineering', 2, 'What is version control?', 'Managing code changes', 'Testing software', 'Deploying applications', 'Writing documentation', 'A'),
(16, 4, 'Software Engineering', 2, 'What does UML stand for?', 'Unified Modeling Language', 'Universal Machine Learning', 'User Management Logic', 'Utility Mapping Library', 'A');

-- Success message
SELECT 'Database has been reset successfully!' AS Status;
SELECT 'Default admin: username=admin, password=admin123' AS AdminCredentials;
SELECT 'Sample students: username=student1/student2/student3, password=pass123' AS StudentCredentials;
SELECT 'Sample instructors: username=inst1/inst2/inst3/inst4, password=pass123' AS InstructorCredentials;
