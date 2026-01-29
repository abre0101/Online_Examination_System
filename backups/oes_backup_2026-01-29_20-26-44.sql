-- OES Database Backup
-- Generated: 2026-01-29 20:26:44
-- Database: oes

SET FOREIGN_KEY_CHECKS=0;

-- Table: admin
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `Admin_ID` varchar(10) NOT NULL,
  `Admin_Name` varchar(30) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`Admin_ID`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `admin` VALUES
("admin1","Tolosa Tola","admin","admin12","tolosa.tola@gmail.com");

-- Table: ans_matching
DROP TABLE IF EXISTS `ans_matching`;
CREATE TABLE `ans_matching` (
  `ans_id` varchar(2) NOT NULL,
  `answer_list` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Table: choose
DROP TABLE IF EXISTS `choose`;
CREATE TABLE `choose` (
  `Answer` varchar(100) NOT NULL,
  `course_id` varchar(20) NOT NULL,
  `exam_id` varchar(20) NOT NULL,
  `Option1` varchar(100) NOT NULL,
  `Option2` varchar(100) NOT NULL,
  `Option3` varchar(100) NOT NULL,
  `Option4` varchar(100) NOT NULL,
  `question` varchar(1000) NOT NULL,
  `question_id` varchar(20) NOT NULL,
  `semister` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Table: course
DROP TABLE IF EXISTS `course`;
CREATE TABLE `course` (
  `course_id` varchar(10) NOT NULL,
  `course_name` varchar(50) NOT NULL,
  `credit_hr` int(2) NOT NULL,
  `dept_name` varchar(50) NOT NULL,
  `semister` int(11) NOT NULL,
  `Inst_Name` varchar(50) NOT NULL,
  PRIMARY KEY (`course_id`),
  UNIQUE KEY `course_name` (`course_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `course` VALUES
("CS101","Introduction to Programming","3","Computer Science","1","Instructor A"),
("CS102","Data Structures","4","Computer Science","2","Instructor B"),
("IT101","Database Systems","3","Information Technology","1","Instructor C");

-- Table: department
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `deptno` varchar(10) NOT NULL,
  `dept_name` varchar(50) NOT NULL,
  `faculty_name` varchar(50) NOT NULL,
  PRIMARY KEY (`deptno`),
  UNIQUE KEY `dept_name` (`dept_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Table: exam
DROP TABLE IF EXISTS `exam`;
CREATE TABLE `exam` (
  `exam_id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_title` varchar(100) NOT NULL,
  `dept_name` varchar(50) NOT NULL,
  `course_id` varchar(10) DEFAULT NULL,
  `exam_date` date DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`exam_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Table: exam_category
DROP TABLE IF EXISTS `exam_category`;
CREATE TABLE `exam_category` (
  `exam_id` int(11) NOT NULL,
  `exam_name` varchar(30) NOT NULL,
  PRIMARY KEY (`exam_id`),
  UNIQUE KEY `exam_name` (`exam_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `exam_category` VALUES
("2","Final Exam"),
("1","Midterm Exam"),
("4","Practice Test"),
("3","Quiz");

-- Table: exam_committee
DROP TABLE IF EXISTS `exam_committee`;
CREATE TABLE `exam_committee` (
  `EC_ID` varchar(10) NOT NULL,
  `EC_Name` varchar(50) NOT NULL,
  `dept_name` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `Status` varchar(10) NOT NULL,
  `last_password_change` datetime DEFAULT NULL,
  PRIMARY KEY (`EC_ID`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `exam_committee` VALUES
("EC05","Dr. Abebe Tadesse","Computer Science","abebe.ec","password123","abebe.tadesse@dmu.edu","Active","NULL"),
("EC06","Dr. Tigist Alemayehu","Nursing","tigist.ec","password123","tigist.alemayehu@dmu.edu","Active","NULL"),
("EC07","Dr. Mulugeta Bekele","Medicine","mulugeta.ec","password123","mulugeta.bekele@dmu.edu","Active","NULL"),
("EC08","Dr. Hanna Girma","Public Health","hanna.ec","password123","hanna.girma@dmu.edu","Active","NULL"),
("EC09","Dr. Yohannes Tesfaye","Pharmacy","yohannes.ec","password123","yohannes.tesfaye@dmu.edu","Active","NULL");

-- Table: faculty
DROP TABLE IF EXISTS `faculty`;
CREATE TABLE `faculty` (
  `faculty_id` varchar(10) NOT NULL,
  `faculty_name` varchar(50) NOT NULL,
  PRIMARY KEY (`faculty_id`),
  UNIQUE KEY `faculty_name` (`faculty_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Table: grading_config
DROP TABLE IF EXISTS `grading_config`;
CREATE TABLE `grading_config` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_key` varchar(50) DEFAULT NULL,
  `config_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`config_id`),
  UNIQUE KEY `config_key` (`config_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: instructor
DROP TABLE IF EXISTS `instructor`;
CREATE TABLE `instructor` (
  `Inst_ID` varchar(10) NOT NULL,
  `Inst_Name` varchar(30) NOT NULL,
  `dept_name` varchar(50) NOT NULL,
  `course_name` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `Status` varchar(10) NOT NULL,
  PRIMARY KEY (`Inst_ID`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `instructor` VALUES
("Inst05","Prof. Dawit Haile","Computer Science","Database Systems","dawit.inst","password123","dawit.haile@dmu.edu","Active"),
("Inst06","Dr. Marta Solomon","Computer Science","Web Development","marta.inst","password123","marta.solomon@dmu.edu","Active"),
("Inst07","Dr. Getachew Assefa","Nursing","Fundamentals of Nursing","getachew.inst","password123","getachew.assefa@dmu.edu","Active"),
("Inst08","Dr. Selamawit Kebede","Nursing","Medical-Surgical Nursing","selamawit.inst","password123","selamawit.kebede@dmu.edu","Active"),
("Inst09","Prof. Alemayehu Worku","Medicine","Anatomy","alemayehu.inst","password123","alemayehu.worku@dmu.edu","Active"),
("Inst10","Dr. Bethlehem Negash","Medicine","Physiology","bethlehem.inst","password123","bethlehem.negash@dmu.edu","Active"),
("Inst11","Dr. Tesfaye Mekonnen","Public Health","Epidemiology","tesfaye.inst","password123","tesfaye.mekonnen@dmu.edu","Active"),
("Inst12","Dr. Rahel Desta","Public Health","Health Education","rahel.inst","password123","rahel.desta@dmu.edu","Active"),
("Inst13","Dr. Yared Abera","Pharmacy","Pharmacology","yared.inst","password123","yared.abera@dmu.edu","Active"),
("Inst14","Dr. Meseret Tadesse","Pharmacy","Pharmaceutical Chemistry","meseret.inst","password123","meseret.tadesse@dmu.edu","Active");

-- Table: matching
DROP TABLE IF EXISTS `matching`;
CREATE TABLE `matching` (
  `answer` varchar(100) NOT NULL,
  `question` varchar(500) NOT NULL,
  `course_id` varchar(20) NOT NULL,
  `exam_id` varchar(20) NOT NULL,
  `question_id` varchar(20) NOT NULL,
  `semister` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Table: que_matching
DROP TABLE IF EXISTS `que_matching`;
CREATE TABLE `que_matching` (
  `question_id` varchar(20) NOT NULL,
  `question` varchar(500) NOT NULL,
  `answer` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Table: question_page
DROP TABLE IF EXISTS `question_page`;
CREATE TABLE `question_page` (
  `question_id` int(2) NOT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `submission_date` datetime DEFAULT current_timestamp(),
  `approval_date` datetime DEFAULT NULL,
  `approved_by` varchar(50) DEFAULT NULL,
  `review_date` datetime DEFAULT NULL,
  `dept_name` varchar(50) DEFAULT NULL,
  `course_id` varchar(10) DEFAULT NULL,
  `instructor_id` varchar(10) DEFAULT NULL,
  `question_text` text DEFAULT NULL,
  `question_count` int(11) DEFAULT 1,
  `exam_status` varchar(20) DEFAULT 'Live',
  `dept_id` varchar(10) DEFAULT NULL,
  `exam_id` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `course_name` varchar(10) NOT NULL,
  `question` varchar(1000) NOT NULL,
  `Option1` varchar(100) NOT NULL,
  `Option2` varchar(100) NOT NULL,
  `Option3` varchar(100) NOT NULL,
  `Option4` varchar(100) NOT NULL,
  `Answer` varchar(100) NOT NULL,
  PRIMARY KEY (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `question_page` VALUES
("1","Pending","2026-01-29 20:47:04","NULL","NULL","NULL","NULL","NULL","NULL","NULL","1","Live","NULL","1","1","Introducti","What is a variable?","A storage location","A function","A loop","A class","A"),
("2","Pending","2026-01-29 20:47:04","NULL","NULL","NULL","NULL","NULL","NULL","NULL","1","Live","NULL","1","1","Introducti","Which is a programming language?","HTML","CSS","Python","JSON","C"),
("3","Pending","2026-01-29 20:47:04","NULL","NULL","NULL","NULL","NULL","NULL","NULL","1","Live","NULL","1","1","Introducti","What does IDE stand for?","Integrated Development Environment","Internet Data Exchange","Internal Design Engine","Interactive Display","A"),
("4","Pending","2026-01-29 20:47:04","NULL","NULL","NULL","NULL","NULL","NULL","NULL","1","Live","NULL","1","1","Introducti","What is a loop used for?","To repeat code","To store data","To define functions","To create variables","A"),
("5","Pending","2026-01-29 20:47:04","NULL","NULL","NULL","NULL","NULL","NULL","NULL","1","Live","NULL","2","2","Data Struc","What is an array?","A collection of elements","A single value","A function","A class","A"),
("6","Pending","2026-01-29 20:47:04","NULL","NULL","NULL","NULL","NULL","NULL","NULL","1","Live","NULL","2","2","Data Struc","Which uses LIFO?","Queue","Stack","Array","Tree","B"),
("7","Pending","2026-01-29 20:47:04","NULL","NULL","NULL","NULL","NULL","NULL","NULL","1","Live","NULL","3","1","Database S","What does SQL stand for?","Structured Query Language","Simple Question Language","System Quality Level","Standard Query List","A"),
("8","Pending","2026-01-29 20:47:04","NULL","NULL","NULL","NULL","NULL","NULL","NULL","1","Live","NULL","3","1","Database S","Which retrieves data?","INSERT","UPDATE","SELECT","DELETE","C");

-- Table: question_type
DROP TABLE IF EXISTS `question_type`;
CREATE TABLE `question_type` (
  `type_id` int(11) NOT NULL,
  `question_name` varchar(20) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Table: result
DROP TABLE IF EXISTS `result`;
CREATE TABLE `result` (
  `Stud_ID` varchar(10) NOT NULL,
  `exam_id` varchar(20) NOT NULL,
  `result_id` varchar(20) NOT NULL,
  `Correct` int(11) NOT NULL,
  `Wrong` int(11) NOT NULL,
  `Result` int(11) NOT NULL,
  `Total` int(11) NOT NULL,
  `course_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `result` VALUES
("1","1","","2","6","4","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","0","8","0","8","CS101"),
("1","1","","1","7","2","8","CS101"),
("1","1","","0","8","0","8","CS101");

-- Table: schedule
DROP TABLE IF EXISTS `schedule`;
CREATE TABLE `schedule` (
  `schedule_id` int(11) NOT NULL,
  `exam_name` int(11) NOT NULL,
  `course_name` varchar(10) NOT NULL,
  `exam_date` date NOT NULL,
  `exam_time` time NOT NULL,
  `semister` int(11) NOT NULL,
  `duration_minutes` int(11) DEFAULT 60,
  `end_time` time DEFAULT NULL,
  PRIMARY KEY (`schedule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `schedule` VALUES
("1","1","Introducti","2026-01-29","08:00:00","1","30","23:59:00"),
("2","2","Introducti","2026-01-31","09:00:00","1","90","17:00:00"),
("3","3","Introducti","2026-02-03","10:00:00","2","120","16:00:00");

-- Table: security_logs
DROP TABLE IF EXISTS `security_logs`;
CREATE TABLE `security_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) DEFAULT NULL,
  `user_type` varchar(20) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`log_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_action` (`action`),
  KEY `idx_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: student
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
  `Id` varchar(20) NOT NULL,
  `Name` varchar(20) DEFAULT NULL,
  `Sex` varchar(2) NOT NULL,
  `dept_name` varchar(20) NOT NULL,
  `semister` int(5) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `Status` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `year` varchar(5) NOT NULL,
  PRIMARY KEY (`Id`(10)),
  UNIQUE KEY `UNIQUE KEY` (`username`(10))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `student` VALUES
("1","Abraham","M","Computer Science","1","abre","pass123","Active","abaraham@gmail.com","2024"),
("2","Fitse","F","Information Technolo","2","fiste","pass123","Active","student2@test.com","2024");

-- Table: student_answers
DROP TABLE IF EXISTS `student_answers`;
CREATE TABLE `student_answers` (
  `answer_id` int(11) NOT NULL AUTO_INCREMENT,
  `result_id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `question_id` int(11) NOT NULL,
  `selected_answer` char(1) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  `answered_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`answer_id`),
  KEY `idx_result` (`result_id`),
  KEY `idx_student` (`student_id`),
  KEY `idx_question` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `student_answers` VALUES
("1","0","1","0","C","0","2026-01-29 22:03:49"),
("2","0","1","1","C","0","2026-01-29 22:03:49"),
("3","0","1","2","D","0","2026-01-29 22:03:49"),
("4","0","1","0","B","0","2026-01-29 22:04:28"),
("5","0","1","6","D","0","2026-01-29 22:04:28");

-- Table: truefalse_question
DROP TABLE IF EXISTS `truefalse_question`;
CREATE TABLE `truefalse_question` (
  `question_id` int(10) NOT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `submission_date` datetime DEFAULT current_timestamp(),
  `dept_name` varchar(50) DEFAULT NULL,
  `exam_id` int(20) NOT NULL,
  `semester` int(10) NOT NULL,
  `Course_name` varchar(20) NOT NULL,
  `question` varchar(1000) NOT NULL,
  `Answer1` varchar(1000) NOT NULL,
  `Answer2` varchar(1000) NOT NULL,
  `Answer` varchar(1000) NOT NULL,
  PRIMARY KEY (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

SET FOREIGN_KEY_CHECKS=1;
