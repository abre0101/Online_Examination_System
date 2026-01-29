<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");

// Handle export request
if(isset($_GET['export'])) {
    $exportType = $_GET['export'];
    $filename = '';
    $data = array();
    
    switch($exportType) {
        case 'students':
            $filename = 'students_export_' . date('Y-m-d') . '.csv';
            $result = $con->query("SELECT Id, Name, dept, semister, year, email, Status FROM student ORDER BY Id");
            $headers = array('Student ID', 'Name', 'Department', 'Semester', 'Year', 'Email', 'Status');
            break;
            
        case 'instructors':
            $filename = 'instructors_export_' . date('Y-m-d') . '.csv';
            $result = $con->query("SELECT Inst_ID, Inst_Name, dept_name, email, Status FROM instructor ORDER BY Inst_ID");
            $headers = array('Instructor ID', 'Name', 'Department', 'Email', 'Status');
            break;
            
        case 'courses':
            $filename = 'courses_export_' . date('Y-m-d') . '.csv';
            $result = $con->query("SELECT course_id, course_name, dept_name FROM course ORDER BY course_id");
            $headers = array('Course ID', 'Course Name', 'Department');
            break;
            
        case 'departments':
            $filename = 'departments_export_' . date('Y-m-d') . '.csv';
            $result = $con->query("SELECT dept_id, dept_name, faculty_name FROM department ORDER BY dept_id");
            $headers = array('Department ID', 'Department Name', 'Faculty');
            break;
            
        case 'results':
            $filename = 'exam_results_export_' . date('Y-m-d') . '.csv';
            $result = $con->query("SELECT r.Result_ID, r.Stud_ID, s.Name, ec.exam_name, c.course_name, 
                r.Total, r.Correct, r.Wrong, r.Result 
                FROM result r
                INNER JOIN student s ON r.Stud_ID = s.Id
                INNER JOIN exam_category ec ON r.Exam_ID = ec.exam_id
                INNER JOIN course c ON r.Course_ID = c.course_id
                ORDER BY r.Result_ID DESC");
            $headers = array('Result ID', 'Student ID', 'Student Name', 'Exam', 'Course', 'Total Questions', 'Correct', 'Wrong', 'Score %');
            break;
            
        case 'exams':
            $filename = 'exams_export_' . date('Y-m-d') . '.csv';
            $result = $con->query("SELECT exam_id, exam_name FROM exam_category ORDER BY exam_id");
            $headers = array('Exam ID', 'Exam Name');
            break;
            
        case 'questions':
            $filename = 'questions_export_' . date('Y-m-d') . '.csv';
            $result = $con->query("SELECT question_id, exam_id, course_name, semester, question, 
                Option1, Option2, Option3, Option4, Answer 
                FROM question_page ORDER BY question_id");
            $headers = array('Question ID', 'Exam ID', 'Course', 'Semester', 'Question', 'Option A', 'Option B', 'Option C', 'Option D', 'Correct Answer');
            break;
            
        case 'schedule':
            $filename = 'exam_schedule_export_' . date('Y-m-d') . '.csv';
            $result = $con->query("SELECT s.schedule_id, s.exam_id, ec.exam_name, s.course_name, 
                s.exam_date, s.exam_time, s.duration, s.semister 
                FROM schedule s
                LEFT JOIN exam_category ec ON s.exam_id = ec.exam_id
                ORDER BY s.exam_date DESC");
            $headers = array('Schedule ID', 'Exam ID', 'Exam Name', 'Course', 'Date', 'Time', 'Duration (min)', 'Semester');
            break;
            
        default:
            die('Invalid export type');
    }
    
    // Generate CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    // Write headers
    fputcsv($output, $headers);
    
    // Write data
    while($row = $result->fetch_row()) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    $con->close();
    exit();
}

// Get statistics for each export type
$stats = array(
    'students' => $con->query("SELECT COUNT(*) as count FROM student")->fetch_assoc()['count'],
    'instructors' => $con->query("SELECT COUNT(*) as count FROM instructor")->fetch_assoc()['count'],
    'courses' => $con->query("SELECT COUNT(*) as count FROM course")->fetch_assoc()['count'],
    'departments' => $con->query("SELECT COUNT(*) as count FROM department")->fetch_assoc()['count'],
    'results' => $con->query("SELECT COUNT(*) as count FROM result")->fetch_assoc()['count'],
    'exams' => $con->query("SELECT COUNT(*) as count FROM exam_category")->fetch_assoc()['count'],
    'questions' => $con->query("SELECT COUNT(*) as count FROM question_page")->fetch_assoc()['count'],
    'schedule' => $con->query("SELECT COUNT(*) as count FROM schedule")->fetch_assoc()['count']
);

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Export - Admin</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-sidebar.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .export-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .export-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            border-color: var(--primary-color);
        }
        
        .export-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .export-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .export-count {
            font-size: 2rem;
            font-weight: 800;
            color: var(--secondary-color);
            margin: 1rem 0;
        }
        
        .export-desc {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
    </style>
</head>
<body class="admin-layout">
    <?php include 'sidebar-component.php'; ?>

    <div class="admin-main-content">
        <?php include 'header-component.php'; ?>

        <div class="admin-content">
            <div class="page-header">
                <h1>📥 Data Export</h1>
                <p>Export system data to CSV format for backup and analysis</p>
            </div>

            <!-- Export Options Grid -->
            <div class="grid grid-3">
                <!-- Students Export -->
                <div class="export-card">
                    <div class="export-icon">👨‍🎓</div>
                    <h3 class="export-title">Students</h3>
                    <div class="export-count"><?php echo number_format($stats['students']); ?></div>
                    <p class="export-desc">
                        Export all student records including ID, name, department, semester, year, email, and status.
                    </p>
                    <a href="?export=students" class="btn btn-primary btn-block">
                        📥 Export Students
                    </a>
                </div>

                <!-- Instructors Export -->
                <div class="export-card">
                    <div class="export-icon">👨‍🏫</div>
                    <h3 class="export-title">Instructors</h3>
                    <div class="export-count"><?php echo number_format($stats['instructors']); ?></div>
                    <p class="export-desc">
                        Export all instructor records including ID, name, department, email, and status.
                    </p>
                    <a href="?export=instructors" class="btn btn-primary btn-block">
                        📥 Export Instructors
                    </a>
                </div>

                <!-- Courses Export -->
                <div class="export-card">
                    <div class="export-icon">📚</div>
                    <h3 class="export-title">Courses</h3>
                    <div class="export-count"><?php echo number_format($stats['courses']); ?></div>
                    <p class="export-desc">
                        Export all course records including course ID, name, and department.
                    </p>
                    <a href="?export=courses" class="btn btn-primary btn-block">
                        📥 Export Courses
                    </a>
                </div>

                <!-- Departments Export -->
                <div class="export-card">
                    <div class="export-icon">🏛️</div>
                    <h3 class="export-title">Departments</h3>
                    <div class="export-count"><?php echo number_format($stats['departments']); ?></div>
                    <p class="export-desc">
                        Export all department records including department ID, name, and faculty.
                    </p>
                    <a href="?export=departments" class="btn btn-primary btn-block">
                        📥 Export Departments
                    </a>
                </div>

                <!-- Exam Results Export -->
                <div class="export-card">
                    <div class="export-icon">📊</div>
                    <h3 class="export-title">Exam Results</h3>
                    <div class="export-count"><?php echo number_format($stats['results']); ?></div>
                    <p class="export-desc">
                        Export all exam results including student info, exam details, scores, and performance.
                    </p>
                    <a href="?export=results" class="btn btn-success btn-block">
                        📥 Export Results
                    </a>
                </div>

                <!-- Exams Export -->
                <div class="export-card">
                    <div class="export-icon">📝</div>
                    <h3 class="export-title">Exams</h3>
                    <div class="export-count"><?php echo number_format($stats['exams']); ?></div>
                    <p class="export-desc">
                        Export all exam categories including exam ID and name.
                    </p>
                    <a href="?export=exams" class="btn btn-primary btn-block">
                        📥 Export Exams
                    </a>
                </div>

                <!-- Questions Export -->
                <div class="export-card">
                    <div class="export-icon">❓</div>
                    <h3 class="export-title">Questions</h3>
                    <div class="export-count"><?php echo number_format($stats['questions']); ?></div>
                    <p class="export-desc">
                        Export all questions including question text, options, correct answers, and metadata.
                    </p>
                    <a href="?export=questions" class="btn btn-warning btn-block">
                        📥 Export Questions
                    </a>
                </div>

                <!-- Schedule Export -->
                <div class="export-card">
                    <div class="export-icon">📅</div>
                    <h3 class="export-title">Exam Schedule</h3>
                    <div class="export-count"><?php echo number_format($stats['schedule']); ?></div>
                    <p class="export-desc">
                        Export exam schedule including dates, times, duration, and course information.
                    </p>
                    <a href="?export=schedule" class="btn btn-primary btn-block">
                        📥 Export Schedule
                    </a>
                </div>
            </div>

            <!-- Export Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">ℹ️ Export Information</h3>
                </div>
                <div style="padding: 2rem;">
                    <div class="grid grid-2">
                        <div>
                            <h4 style="color: var(--primary-color); margin-bottom: 1rem;">📋 What Gets Exported</h4>
                            <ul style="color: var(--text-secondary); line-height: 1.8;">
                                <li>All data is exported in CSV (Comma-Separated Values) format</li>
                                <li>Files include headers for easy identification</li>
                                <li>Data is exported as-is from the database</li>
                                <li>Filename includes export date for tracking</li>
                                <li>Compatible with Excel, Google Sheets, and other tools</li>
                            </ul>
                        </div>
                        <div>
                            <h4 style="color: var(--primary-color); margin-bottom: 1rem;">💡 Usage Tips</h4>
                            <ul style="color: var(--text-secondary); line-height: 1.8;">
                                <li>Export data regularly for backup purposes</li>
                                <li>Use exports for external analysis and reporting</li>
                                <li>Open CSV files in Excel or Google Sheets</li>
                                <li>Store exports securely (contains sensitive data)</li>
                                <li>Use for institutional record keeping</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Export -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">📦 Bulk Export Options</h3>
                </div>
                <div style="padding: 2rem;">
                    <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                        Export multiple datasets at once for comprehensive backup and analysis.
                    </p>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <button onclick="exportAll()" class="btn btn-success">
                            📥 Export All Data
                        </button>
                        <button onclick="exportUsers()" class="btn btn-primary">
                            👥 Export All Users
                        </button>
                        <button onclick="exportAcademic()" class="btn btn-warning">
                            📚 Export Academic Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
    <script>
        function exportAll() {
            if(confirm('Export all data? This will download 8 CSV files.')) {
                const exports = ['students', 'instructors', 'courses', 'departments', 'results', 'exams', 'questions', 'schedule'];
                exports.forEach((type, index) => {
                    setTimeout(() => {
                        window.location.href = '?export=' + type;
                    }, index * 500);
                });
            }
        }
        
        function exportUsers() {
            if(confirm('Export all user data? This will download 2 CSV files.')) {
                window.location.href = '?export=students';
                setTimeout(() => {
                    window.location.href = '?export=instructors';
                }, 500);
            }
        }
        
        function exportAcademic() {
            if(confirm('Export academic data? This will download 4 CSV files.')) {
                const exports = ['courses', 'departments', 'exams', 'questions'];
                exports.forEach((type, index) => {
                    setTimeout(() => {
                        window.location.href = '?export=' + type;
                    }, index * 500);
                });
            }
        }
    </script>
</body>
</html>
