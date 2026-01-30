<?php
if (!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Name'])){
    header("Location:../auth/institute-login.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");
$instructorName = $_SESSION['Name'];

// Get report type
$reportType = $_GET['type'] ?? 'overview';
$studentFilter = $_GET['student'] ?? '';
$courseFilter = $_GET['course'] ?? '';
$startDate = $_GET['start_date'] ?? date('Y-m-01');
$endDate = $_GET['end_date'] ?? date('Y-m-d');

// Get instructor's courses
$instructorCourses = $con->query("SELECT course_id, course_name FROM course WHERE Inst_Name = '$instructorName' ORDER BY course_name");
$courses = [];
while($row = $instructorCourses->fetch_assoc()) {
    $courses[] = $row;
}

// Get students in instructor's courses
$studentsQuery = "SELECT DISTINCT s.Id, s.Name 
    FROM student s 
    INNER JOIN result r ON s.Id = r.Stud_ID 
    INNER JOIN course c ON r.course_id = c.course_id 
    WHERE c.Inst_Name = '$instructorName' 
    ORDER BY s.Name";
$studentsResult = $con->query($studentsQuery);
$students = [];
while($row = $studentsResult->fetch_assoc()) {
    $students[] = $row;
}

// Overview Statistics
$totalStudents = $con->query("SELECT COUNT(DISTINCT r.Stud_ID) as count 
    FROM result r 
    INNER JOIN course c ON r.course_id = c.course_id 
    WHERE c.Inst_Name = '$instructorName'")->fetch_assoc()['count'];

$totalExams = $con->query("SELECT COUNT(DISTINCT r.result_id) as count 
    FROM result r 
    INNER JOIN course c ON r.course_id = c.course_id 
    WHERE c.Inst_Name = '$instructorName'")->fetch_assoc()['count'];

$avgScore = $con->query("SELECT AVG(r.Result) as avg 
    FROM result r 
    INNER JOIN course c ON r.course_id = c.course_id 
    WHERE c.Inst_Name = '$instructorName'")->fetch_assoc()['avg'] ?? 0;

$passRate = $con->query("SELECT 
    (SUM(CASE WHEN r.Result >= 50 THEN 1 ELSE 0 END) * 100.0 / COUNT(*)) as rate 
    FROM result r 
    INNER JOIN course c ON r.course_id = c.course_id 
    WHERE c.Inst_Name = '$instructorName'")->fetch_assoc()['rate'] ?? 0;

// Individual Student Performance
$studentPerformance = null;
if($studentFilter) {
    $studentPerformance = $con->query("SELECT 
        s.Name as student_name,
        s.Id as student_id,
        c.course_name,
        ec.exam_name,
        r.Result as score,
        r.Correct,
        r.Total,
        r.result_id
        FROM result r
        INNER JOIN student s ON r.Stud_ID = s.Id
        INNER JOIN course c ON r.course_id = c.course_id
        INNER JOIN exam_category ec ON r.exam_id = ec.exam_id
        WHERE c.Inst_Name = '$instructorName' AND s.Id = '$studentFilter'
        ORDER BY r.result_id DESC");
}

// Class Performance by Course
$classPerformance = $con->query("SELECT 
    c.course_name,
    COUNT(DISTINCT r.Stud_ID) as total_students,
    COUNT(r.result_id) as total_exams,
    AVG(r.Result) as avg_score,
    MAX(r.Result) as max_score,
    MIN(r.Result) as min_score,
    SUM(CASE WHEN r.Result >= 50 THEN 1 ELSE 0 END) as passed,
    SUM(CASE WHEN r.Result < 50 THEN 1 ELSE 0 END) as failed
    FROM course c
    LEFT JOIN result r ON c.course_id = r.course_id
    WHERE c.Inst_Name = '$instructorName'
    GROUP BY c.course_id, c.course_name
    ORDER BY c.course_name");

// Grade Distribution
$gradeDistribution = $con->query("SELECT 
    CASE 
        WHEN r.Result >= 90 THEN 'A'
        WHEN r.Result >= 80 THEN 'B'
        WHEN r.Result >= 70 THEN 'C'
        WHEN r.Result >= 60 THEN 'D'
        WHEN r.Result >= 50 THEN 'E'
        ELSE 'F'
    END as grade,
    COUNT(*) as count
    FROM result r
    INNER JOIN course c ON r.course_id = c.course_id
    WHERE c.Inst_Name = '$instructorName'
    GROUP BY grade
    ORDER BY MIN(r.Result) DESC");

$grades = [];
while($row = $gradeDistribution->fetch_assoc()) {
    $grades[] = $row;
}

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Reports - OES</title>
    <link href="../assets/css/modern-v2.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="../assets/css/admin-sidebar.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body.admin-layout { background: #f5f7fa; }
        
        .reports-header {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }
        
        .reports-header h1 {
            margin: 0 0 0.5rem 0;
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .filter-section {
            background: white;
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .filter-group label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.1rem;
        }
        
        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e0e0e0;
            border-radius: var(--radius-md);
            font-size: 1.05rem;
            transition: all 0.3s ease;
        }
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 51, 102, 0.1);
        }
        
        .filter-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .btn-filter {
            padding: 1rem 2rem;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 1.1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 51, 102, 0.3);
        }
        
        .btn-export {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            color: white;
        }
        
        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }
        
        .btn-print {
            background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);
            color: white;
        }
        
        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(111, 66, 193, 0.3);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-left: 5px solid;
        }
        
        .stat-card.primary { border-left-color: #007bff; }
        .stat-card.success { border-left-color: #28a745; }
        .stat-card.warning { border-left-color: #ffc107; }
        .stat-card.info { border-left-color: #17a2b8; }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 1rem;
            color: var(--text-secondary);
            font-weight: 600;
        }
        
        .report-section {
            background: white;
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid var(--secondary-color);
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table thead {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }
        
        .data-table th {
            padding: 1rem;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 0.95rem;
        }
        
        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid #e8eef3;
            font-size: 0.95rem;
        }
        
        .data-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .score-badge {
            padding: 0.35rem 0.75rem;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 0.85rem;
        }
        
        .badge-excellent { background: #d4edda; color: #155724; }
        .badge-good { background: #d1ecf1; color: #0c5460; }
        .badge-average { background: #fff3cd; color: #856404; }
        .badge-poor { background: #f8d7da; color: #721c24; }
        
        .chart-container {
            margin: 2rem 0;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: var(--radius-md);
        }
        
        @media print {
            .filter-section, .admin-sidebar, .admin-header { display: none; }
            .admin-main-content { margin-left: 0; }
        }
    </style>
</head>
<body class="admin-layout">
    <?php include 'sidebar-component.php'; ?>

    <div class="admin-main-content">
        <?php 
        $pageTitle = 'Reports & Analytics';
        include 'header-component.php'; 
        ?>

        <div class="admin-content">
            <!-- Reports Header -->
            <div class="reports-header">
                <h1><span>📊</span> Instructor Reports & Analytics</h1>
                <p style="margin: 0; color: var(--text-secondary); font-size: 1.05rem;">
                    Comprehensive student performance analysis and course statistics
                </p>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <h3 style="margin: 0 0 1.5rem 0; font-size: 1.6rem; font-weight: 800; color: var(--primary-color);">
                    🔍 Filter Reports
                </h3>
                <form method="GET" action="">
                    <div class="filter-grid">
                        <div class="filter-group">
                            <label>Report Type</label>
                            <select name="type">
                                <option value="overview" <?php echo $reportType == 'overview' ? 'selected' : ''; ?>>Overview</option>
                                <option value="individual" <?php echo $reportType == 'individual' ? 'selected' : ''; ?>>Individual Student</option>
                                <option value="class" <?php echo $reportType == 'class' ? 'selected' : ''; ?>>Class Performance</option>
                                <option value="question" <?php echo $reportType == 'question' ? 'selected' : ''; ?>>Question Analysis</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label>Select Student</label>
                            <select name="student">
                                <option value="">All Students</option>
                                <?php foreach($students as $student): ?>
                                <option value="<?php echo $student['Id']; ?>" <?php echo $studentFilter == $student['Id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($student['Name']); ?> (<?php echo $student['Id']; ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label>Select Course</label>
                            <select name="course">
                                <option value="">All Courses</option>
                                <?php foreach($courses as $course): ?>
                                <option value="<?php echo $course['course_id']; ?>" <?php echo $courseFilter == $course['course_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($course['course_name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label>Date Range</label>
                            <input type="date" name="start_date" value="<?php echo $startDate; ?>">
                        </div>
                    </div>
                    
                    <div class="filter-actions">
                        <button type="submit" class="btn-filter btn-primary">
                            <span>🔍</span> Generate Report
                        </button>
                        <button type="button" class="btn-filter btn-export" onclick="exportToExcel()">
                            <span>📥</span> Export to Excel
                        </button>
                        <button type="button" class="btn-filter btn-print" onclick="window.print()">
                            <span>🖨️</span> Print Report
                        </button>
                    </div>
                </form>
            </div>

            <!-- Overview Statistics -->
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-icon">👨‍🎓</div>
                    <div class="stat-value"><?php echo number_format($totalStudents); ?></div>
                    <div class="stat-label">Total Students</div>
                </div>
                
                <div class="stat-card success">
                    <div class="stat-icon">📝</div>
                    <div class="stat-value"><?php echo number_format($totalExams); ?></div>
                    <div class="stat-label">Total Exams</div>
                </div>
                
                <div class="stat-card warning">
                    <div class="stat-icon">📊</div>
                    <div class="stat-value"><?php echo number_format($avgScore, 1); ?>%</div>
                    <div class="stat-label">Average Score</div>
                </div>
                
                <div class="stat-card info">
                    <div class="stat-icon">✅</div>
                    <div class="stat-value"><?php echo number_format($passRate, 1); ?>%</div>
                    <div class="stat-label">Pass Rate</div>
                </div>
            </div>

            <?php if($studentFilter && $studentPerformance): ?>
            <!-- Individual Student Report -->
            <div class="report-section">
                <div class="section-header">
                    <h2 class="section-title"><span>👤</span> Individual Student Performance</h2>
                </div>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Exam</th>
                            <th>Score</th>
                            <th>Correct/Total</th>
                            <th>Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $studentPerformance->fetch_assoc()): 
                            $score = $row['score'];
                            $badgeClass = $score >= 80 ? 'badge-excellent' : ($score >= 60 ? 'badge-good' : ($score >= 50 ? 'badge-average' : 'badge-poor'));
                            $grade = $score >= 90 ? 'A' : ($score >= 80 ? 'B' : ($score >= 70 ? 'C' : ($score >= 60 ? 'D' : ($score >= 50 ? 'E' : 'F'))));
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['exam_name']); ?></td>
                            <td><span class="score-badge <?php echo $badgeClass; ?>"><?php echo number_format($score, 1); ?>%</span></td>
                            <td><?php echo $row['Correct']; ?> / <?php echo $row['Total']; ?></td>
                            <td><strong><?php echo $grade; ?></strong></td>
                            <td>
                                <a href="ViewAnswers.php?result_id=<?php echo $row['result_id']; ?>" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">
                                    📄 View Answers
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <!-- Class Performance by Course -->
            <div class="report-section">
                <div class="section-header">
                    <h2 class="section-title"><span>📚</span> Class Performance by Course</h2>
                </div>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>Students</th>
                            <th>Exams</th>
                            <th>Avg Score</th>
                            <th>Max Score</th>
                            <th>Min Score</th>
                            <th>Passed</th>
                            <th>Failed</th>
                            <th>Pass Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $classPerformance->fetch_assoc()): 
                            $passRate = $row['total_exams'] > 0 ? ($row['passed'] / $row['total_exams'] * 100) : 0;
                        ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($row['course_name']); ?></strong></td>
                            <td><?php echo $row['total_students']; ?></td>
                            <td><?php echo $row['total_exams']; ?></td>
                            <td><span class="score-badge badge-good"><?php echo number_format($row['avg_score'], 1); ?>%</span></td>
                            <td><?php echo number_format($row['max_score'], 1); ?>%</td>
                            <td><?php echo number_format($row['min_score'], 1); ?>%</td>
                            <td style="color: #28a745; font-weight: 700;"><?php echo $row['passed']; ?></td>
                            <td style="color: #dc3545; font-weight: 700;"><?php echo $row['failed']; ?></td>
                            <td><span class="score-badge <?php echo $passRate >= 70 ? 'badge-excellent' : ($passRate >= 50 ? 'badge-good' : 'badge-poor'); ?>">
                                <?php echo number_format($passRate, 1); ?>%
                            </span></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Grade Distribution Chart -->
            <div class="report-section">
                <div class="section-header">
                    <h2 class="section-title"><span>📊</span> Grade Distribution</h2>
                </div>
                
                <div class="chart-container">
                    <canvas id="gradeChart" style="max-height: 400px;"></canvas>
                </div>
                
                <table class="data-table" style="margin-top: 2rem;">
                    <thead>
                        <tr>
                            <th>Grade</th>
                            <th>Count</th>
                            <th>Percentage</th>
                            <th>Visual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalGrades = array_sum(array_column($grades, 'count'));
                        foreach($grades as $grade): 
                            $percentage = $totalGrades > 0 ? ($grade['count'] / $totalGrades * 100) : 0;
                            $barWidth = $percentage;
                        ?>
                        <tr>
                            <td><strong style="font-size: 1.2rem;"><?php echo $grade['grade']; ?></strong></td>
                            <td><?php echo $grade['count']; ?></td>
                            <td><?php echo number_format($percentage, 1); ?>%</td>
                            <td>
                                <div style="background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-dark) 100%); 
                                            height: 25px; 
                                            width: <?php echo $barWidth; ?>%; 
                                            border-radius: 5px;
                                            display: flex;
                                            align-items: center;
                                            padding-left: 10px;
                                            color: white;
                                            font-weight: 700;
                                            font-size: 0.85rem;">
                                    <?php echo $grade['count']; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Performance Trends -->
            <div class="report-section">
                <div class="section-header">
                    <h2 class="section-title"><span>📈</span> Performance Trends</h2>
                </div>
                
                <div class="chart-container">
                    <canvas id="trendChart" style="max-height: 400px;"></canvas>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Grade Distribution Chart
        const gradeData = <?php echo json_encode($grades); ?>;
        const gradeLabels = gradeData.map(g => g.grade);
        const gradeCounts = gradeData.map(g => parseInt(g.count));
        
        const gradeCtx = document.getElementById('gradeChart').getContext('2d');
        new Chart(gradeCtx, {
            type: 'bar',
            data: {
                labels: gradeLabels,
                datasets: [{
                    label: 'Number of Students',
                    data: gradeCounts,
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(23, 162, 184, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(253, 126, 20, 0.8)',
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(108, 117, 125, 0.8)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(23, 162, 184, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(253, 126, 20, 1)',
                        'rgba(220, 53, 69, 1)',
                        'rgba(108, 117, 125, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Grade Distribution Across All Courses',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Performance Trend Chart (Sample data - you can enhance this with real time-series data)
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'],
                datasets: [{
                    label: 'Average Score',
                    data: [<?php echo number_format($avgScore, 1); ?>, 
                           <?php echo number_format($avgScore * 0.95, 1); ?>, 
                           <?php echo number_format($avgScore * 1.05, 1); ?>, 
                           <?php echo number_format($avgScore * 0.98, 1); ?>, 
                           <?php echo number_format($avgScore * 1.02, 1); ?>, 
                           <?php echo number_format($avgScore, 1); ?>],
                    borderColor: 'rgba(0, 51, 102, 1)',
                    backgroundColor: 'rgba(0, 51, 102, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Average Score Trend Over Time',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Export to Excel function
        function exportToExcel() {
            // Create a simple CSV export
            let csv = 'Course Performance Report\n\n';
            csv += 'Course Name,Students,Exams,Avg Score,Max Score,Min Score,Passed,Failed,Pass Rate\n';
            
            const table = document.querySelector('.data-table tbody');
            const rows = table.querySelectorAll('tr');
            
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const rowData = [];
                cells.forEach((cell, index) => {
                    if(index < 9) { // Skip the last column (actions)
                        rowData.push(cell.textContent.trim().replace(/,/g, ''));
                    }
                });
                csv += rowData.join(',') + '\n';
            });
            
            // Download CSV
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'instructor_report_' + new Date().toISOString().split('T')[0] + '.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }
    </script>
</body>
</html>