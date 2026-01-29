<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");

// Get report type
$reportType = $_GET['type'] ?? 'overview';

// Get date range
$startDate = $_GET['start_date'] ?? date('Y-m-01');
$endDate = $_GET['end_date'] ?? date('Y-m-d');

// Overview Statistics
$stats = array(
    'total_students' => $con->query("SELECT COUNT(*) as count FROM student")->fetch_assoc()['count'],
    'active_students' => $con->query("SELECT COUNT(*) as count FROM student WHERE Status='Active'")->fetch_assoc()['count'],
    'total_instructors' => $con->query("SELECT COUNT(*) as count FROM instructor")->fetch_assoc()['count'],
    'active_instructors' => $con->query("SELECT COUNT(*) as count FROM instructor WHERE Status='Active'")->fetch_assoc()['count'],
    'total_courses' => $con->query("SELECT COUNT(*) as count FROM course")->fetch_assoc()['count'],
    'total_departments' => $con->query("SELECT COUNT(*) as count FROM department")->fetch_assoc()['count'],
    'total_exams' => $con->query("SELECT COUNT(*) as count FROM exam_category")->fetch_assoc()['count'],
    'total_questions' => $con->query("SELECT COUNT(*) as count FROM question_page")->fetch_assoc()['count'],
    'total_results' => $con->query("SELECT COUNT(*) as count FROM result")->fetch_assoc()['count'],
    'exams_today' => $con->query("SELECT COUNT(*) as count FROM schedule WHERE exam_date = CURDATE()")->fetch_assoc()['count']
);

// Exam Statistics
$examStats = $con->query("SELECT 
    ec.exam_name,
    COUNT(DISTINCT r.Result_ID) as total_attempts,
    AVG(r.Result) as avg_score,
    MAX(r.Result) as max_score,
    MIN(r.Result) as min_score
    FROM exam_category ec
    LEFT JOIN result r ON ec.exam_id = r.Exam_ID
    GROUP BY ec.exam_id, ec.exam_name
    ORDER BY total_attempts DESC
    LIMIT 10");

// Department Performance
$deptPerformance = $con->query("SELECT 
    d.dept_name,
    COUNT(DISTINCT s.Id) as student_count,
    COUNT(DISTINCT r.Result_ID) as exam_attempts,
    AVG(r.Result) as avg_score
    FROM department d
    LEFT JOIN student s ON d.dept_name = s.dept_name
    LEFT JOIN result r ON s.Id = r.Stud_ID
    GROUP BY d.dept_name
    ORDER BY avg_score DESC");

// Recent Exam Results
$recentResults = $con->query("SELECT 
    s.Name as student_name,
    s.Id as student_id,
    ec.exam_name,
    c.course_name,
    r.Result as score,
    r.Total as total_questions,
    r.Correct as correct_answers
    FROM result r
    INNER JOIN student s ON r.Stud_ID = s.Id
    INNER JOIN exam_category ec ON r.Exam_ID = ec.exam_id
    INNER JOIN course c ON r.Course_ID = c.course_id
    ORDER BY r.Result_ID DESC
    LIMIT 20");

// Pass/Fail Statistics
$passFailStats = $con->query("SELECT 
    CASE 
        WHEN Result >= 50 THEN 'Pass'
        ELSE 'Fail'
    END as status,
    COUNT(*) as count
    FROM result
    GROUP BY status");

$passCount = 0;
$failCount = 0;
while($row = $passFailStats->fetch_assoc()) {
    if($row['status'] == 'Pass') $passCount = $row['count'];
    else $failCount = $row['count'];
}

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics - Admin</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-sidebar.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-layout">
    <?php include 'sidebar-component.php'; ?>

    <div class="admin-main-content">
        <?php include 'header-component.php'; ?>

        <div class="admin-content">
            <div class="page-header">
                <h1>📊 Reports & Analytics</h1>
                <p>Comprehensive system reports and data analysis</p>
            </div>

            <!-- Report Type Selector -->
            <div class="card">
                <div style="padding: 1.5rem;">
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <a href="?type=overview" class="btn <?php echo $reportType == 'overview' ? 'btn-primary' : 'btn-secondary'; ?>">
                            📊 Overview
                        </a>
                        <a href="?type=exams" class="btn <?php echo $reportType == 'exams' ? 'btn-primary' : 'btn-secondary'; ?>">
                            📝 Exam Statistics
                        </a>
                        <a href="?type=students" class="btn <?php echo $reportType == 'students' ? 'btn-primary' : 'btn-secondary'; ?>">
                            👨‍🎓 Student Performance
                        </a>
                        <a href="?type=departments" class="btn <?php echo $reportType == 'departments' ? 'btn-primary' : 'btn-secondary'; ?>">
                            🏛️ Department Analysis
                        </a>
                        <button onclick="window.print()" class="btn btn-success">
                            🖨️ Print Report
                        </button>
                        <button onclick="exportToCSV()" class="btn btn-warning">
                            📥 Export CSV
                        </button>
                    </div>
                </div>
            </div>

            <!-- Overview Statistics -->
            <div class="stats-grid mt-4">
                <div class="stat-card stat-primary">
                    <div class="stat-icon">👨‍🎓</div>
                    <div class="stat-details">
                        <div class="stat-value"><?php echo number_format($stats['total_students']); ?></div>
                        <div class="stat-label">Total Students</div>
                        <div style="font-size: 0.85rem; color: var(--success-color); margin-top: 0.25rem;">
                            <?php echo $stats['active_students']; ?> Active
                        </div>
                    </div>
                </div>

                <div class="stat-card stat-success">
                    <div class="stat-icon">👨‍🏫</div>
                    <div class="stat-details">
                        <div class="stat-value"><?php echo number_format($stats['total_instructors']); ?></div>
                        <div class="stat-label">Total Instructors</div>
                        <div style="font-size: 0.85rem; color: var(--success-color); margin-top: 0.25rem;">
                            <?php echo $stats['active_instructors']; ?> Active
                        </div>
                    </div>
                </div>

                <div class="stat-card stat-warning">
                    <div class="stat-icon">📚</div>
                    <div class="stat-details">
                        <div class="stat-value"><?php echo number_format($stats['total_courses']); ?></div>
                        <div class="stat-label">Total Courses</div>
                        <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.25rem;">
                            <?php echo $stats['total_departments']; ?> Departments
                        </div>
                    </div>
                </div>

                <div class="stat-card stat-info">
                    <div class="stat-icon">📝</div>
                    <div class="stat-details">
                        <div class="stat-value"><?php echo number_format($stats['total_exams']); ?></div>
                        <div class="stat-label">Total Exams</div>
                        <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.25rem;">
                            <?php echo number_format($stats['total_questions']); ?> Questions
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-2 mt-4">
                <!-- Pass/Fail Chart -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">📈 Pass/Fail Distribution</h3>
                    </div>
                    <div style="padding: 2rem;">
                        <canvas id="passFailChart"></canvas>
                        <div style="display: flex; justify-content: center; gap: 2rem; margin-top: 1.5rem;">
                            <div style="text-align: center;">
                                <div style="font-size: 2rem; font-weight: 800; color: var(--success-color);">
                                    <?php echo $passCount; ?>
                                </div>
                                <div style="color: var(--text-secondary);">Passed</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 2rem; font-weight: 800; color: #dc3545;">
                                    <?php echo $failCount; ?>
                                </div>
                                <div style="color: var(--text-secondary);">Failed</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 2rem; font-weight: 800; color: var(--primary-color);">
                                    <?php echo $passCount + $failCount; ?>
                                </div>
                                <div style="color: var(--text-secondary);">Total</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Department Performance Chart -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">🏛️ Department Performance</h3>
                    </div>
                    <div style="padding: 2rem;">
                        <canvas id="deptChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Exam Statistics Table -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">📝 Top Exams by Attempts</h3>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Exam Name</th>
                                <th>Total Attempts</th>
                                <th>Average Score</th>
                                <th>Highest Score</th>
                                <th>Lowest Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($exam = $examStats->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($exam['exam_name']); ?></strong></td>
                                <td><?php echo number_format($exam['total_attempts']); ?></td>
                                <td>
                                    <span style="color: <?php echo $exam['avg_score'] >= 50 ? 'var(--success-color)' : '#dc3545'; ?>; font-weight: 700;">
                                        <?php echo number_format($exam['avg_score'], 1); ?>%
                                    </span>
                                </td>
                                <td><?php echo number_format($exam['max_score'], 1); ?>%</td>
                                <td><?php echo number_format($exam['min_score'], 1); ?>%</td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Department Performance Table -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">🏛️ Department Performance Analysis</h3>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Students</th>
                                <th>Exam Attempts</th>
                                <th>Average Score</th>
                                <th>Performance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($dept = $deptPerformance->fetch_assoc()): 
                                $performance = 'Excellent';
                                $performanceColor = 'var(--success-color)';
                                if($dept['avg_score'] < 50) {
                                    $performance = 'Needs Improvement';
                                    $performanceColor = '#dc3545';
                                } elseif($dept['avg_score'] < 70) {
                                    $performance = 'Good';
                                    $performanceColor = '#ffc107';
                                }
                            ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($dept['dept_name']); ?></strong></td>
                                <td><?php echo number_format($dept['student_count']); ?></td>
                                <td><?php echo number_format($dept['exam_attempts']); ?></td>
                                <td>
                                    <span style="color: <?php echo $dept['avg_score'] >= 50 ? 'var(--success-color)' : '#dc3545'; ?>; font-weight: 700;">
                                        <?php echo number_format($dept['avg_score'], 1); ?>%
                                    </span>
                                </td>
                                <td>
                                    <span style="color: <?php echo $performanceColor; ?>; font-weight: 700;">
                                        <?php echo $performance; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Results -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">🎯 Recent Exam Results</h3>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Student ID</th>
                                <th>Exam</th>
                                <th>Course</th>
                                <th>Score</th>
                                <th>Correct/Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($result = $recentResults->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($result['student_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($result['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($result['exam_name']); ?></td>
                                <td><?php echo htmlspecialchars($result['course_name']); ?></td>
                                <td>
                                    <span style="color: <?php echo $result['score'] >= 50 ? 'var(--success-color)' : '#dc3545'; ?>; font-weight: 700; font-size: 1.1rem;">
                                        <?php echo $result['score']; ?>%
                                    </span>
                                </td>
                                <td><?php echo $result['correct_answers']; ?>/<?php echo $result['total_questions']; ?></td>
                                <td>
                                    <?php if($result['score'] >= 50): ?>
                                    <span style="background: var(--success-color); color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-sm); font-weight: 700;">
                                        PASS
                                    </span>
                                    <?php else: ?>
                                    <span style="background: #dc3545; color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-sm); font-weight: 700;">
                                        FAIL
                                    </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
    <script>
        // Pass/Fail Pie Chart
        const passFailCtx = document.getElementById('passFailChart').getContext('2d');
        new Chart(passFailCtx, {
            type: 'doughnut',
            data: {
                labels: ['Passed', 'Failed'],
                datasets: [{
                    data: [<?php echo $passCount; ?>, <?php echo $failCount; ?>],
                    backgroundColor: ['#28a745', '#dc3545'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { size: 14, weight: 'bold' },
                            padding: 20
                        }
                    }
                }
            }
        });

        // Department Performance Bar Chart
        const deptCtx = document.getElementById('deptChart').getContext('2d');
        new Chart(deptCtx, {
            type: 'bar',
            data: {
                labels: [
                    <?php 
                    $deptPerformance->data_seek(0);
                    $labels = array();
                    while($d = $deptPerformance->fetch_assoc()) {
                        $labels[] = "'" . addslashes($d['dept_name']) . "'";
                    }
                    echo implode(',', $labels);
                    ?>
                ],
                datasets: [{
                    label: 'Average Score (%)',
                    data: [
                        <?php 
                        $deptPerformance->data_seek(0);
                        $scores = array();
                        while($d = $deptPerformance->fetch_assoc()) {
                            $scores[] = round($d['avg_score'], 1);
                        }
                        echo implode(',', $scores);
                        ?>
                    ],
                    backgroundColor: '#003366',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Export to CSV function
        function exportToCSV() {
            alert('CSV export functionality will download the current report data.');
            // Implementation would generate CSV from table data
        }
    </script>
</body>
</html>
