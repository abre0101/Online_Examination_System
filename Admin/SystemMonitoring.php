<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");

// Get system statistics
$stats = [];

// Active users count
$stats['active_students'] = $con->query("SELECT COUNT(*) as count FROM student WHERE Status='Active'")->fetch_assoc()['count'];
$stats['active_instructors'] = $con->query("SELECT COUNT(*) as count FROM instructor WHERE Status='Active'")->fetch_assoc()['count'];
$stats['active_committee'] = $con->query("SELECT COUNT(*) as count FROM exam_committee WHERE Status='Active'")->fetch_assoc()['count'];
$stats['total_active_users'] = $stats['active_students'] + $stats['active_instructors'] + $stats['active_committee'];

// Database statistics
$stats['total_questions'] = $con->query("SELECT COUNT(*) as count FROM question_page")->fetch_assoc()['count'];
$stats['total_exams'] = $con->query("SELECT COUNT(*) as count FROM exam_category")->fetch_assoc()['count'];
$stats['total_results'] = $con->query("SELECT COUNT(*) as count FROM result")->fetch_assoc()['count'];
$stats['pending_approvals'] = $con->query("SELECT COUNT(*) as count FROM question_page WHERE approval_status='pending' OR approval_status IS NULL")->fetch_assoc()['count'];

// Recent activity (last 24 hours)
$stats['exams_today'] = $con->query("SELECT COUNT(*) as count FROM result WHERE DATE(Result_ID) = CURDATE()")->fetch_assoc()['count'];
$stats['questions_today'] = $con->query("SELECT COUNT(*) as count FROM question_page WHERE DATE(question_id) = CURDATE()")->fetch_assoc()['count'];

// System health metrics
$stats['db_size'] = $con->query("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size FROM information_schema.TABLES WHERE table_schema = 'oes'")->fetch_assoc()['size'];
$stats['table_count'] = $con->query("SELECT COUNT(*) as count FROM information_schema.TABLES WHERE table_schema = 'oes'")->fetch_assoc()['count'];

// Performance metrics
$stats['avg_exam_score'] = $con->query("SELECT ROUND(AVG(Result), 2) as avg FROM result WHERE Result > 0")->fetch_assoc()['avg'] ?? 0;
$stats['pass_rate'] = $con->query("SELECT ROUND((SUM(CASE WHEN Result >= 50 THEN 1 ELSE 0 END) * 100.0 / COUNT(*)), 2) as rate FROM result WHERE Result > 0")->fetch_assoc()['rate'] ?? 0;

// Server information
$stats['php_version'] = phpversion();
$stats['server_software'] = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
$stats['max_upload'] = ini_get('upload_max_filesize');
$stats['max_execution'] = ini_get('max_execution_time');
$stats['memory_limit'] = ini_get('memory_limit');

// Get recent activity log
$recentActivity = $con->query("SELECT 'Exam Taken' as activity, s.Name as user, r.Result as details, r.Result_ID as timestamp 
    FROM result r 
    LEFT JOIN student s ON r.Stud_ID = s.Id 
    ORDER BY r.Result_ID DESC 
    LIMIT 10");

// Get system alerts
$alerts = [];
if($stats['pending_approvals'] > 10) {
    $alerts[] = ['type' => 'warning', 'message' => $stats['pending_approvals'] . ' questions pending approval'];
}
if($stats['db_size'] > 500) {
    $alerts[] = ['type' => 'warning', 'message' => 'Database size exceeds 500MB - consider cleanup'];
}
if($stats['active_students'] == 0) {
    $alerts[] = ['type' => 'danger', 'message' => 'No active students in system'];
}

// Get top performing students
$topStudents = $con->query("SELECT s.Name, AVG(r.Result) as avg_score, COUNT(r.Result_ID) as exam_count 
    FROM result r 
    LEFT JOIN student s ON r.Stud_ID = s.Id 
    WHERE r.Result > 0 
    GROUP BY r.Stud_ID 
    ORDER BY avg_score DESC 
    LIMIT 5");

// Get question distribution by status
$questionStats = $con->query("SELECT 
    SUM(CASE WHEN approval_status = 'approved' THEN 1 ELSE 0 END) as approved,
    SUM(CASE WHEN approval_status = 'pending' OR approval_status IS NULL THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN approval_status = 'revision' THEN 1 ELSE 0 END) as revision,
    SUM(CASE WHEN approval_status = 'rejected' THEN 1 ELSE 0 END) as rejected
    FROM question_page")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Monitoring - Admin</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-sidebar.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .metric-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s;
        }
        .metric-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        }
        .metric-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-color);
            margin: 0.5rem 0;
        }
        .metric-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .health-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 0.5rem;
            animation: pulse 2s infinite;
        }
        .health-good { background: var(--success-color); }
        .health-warning { background: var(--warning-color); }
        .health-danger { background: #dc3545; }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .activity-item {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .activity-item:last-child {
            border-bottom: none;
        }
        .chart-container {
            position: relative;
            height: 300px;
            margin: 1rem 0;
        }
        .progress-ring {
            transform: rotate(-90deg);
        }
        .alert-banner {
            padding: 1rem 1.5rem;
            border-radius: var(--radius-md);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .alert-warning {
            background: rgba(255, 193, 7, 0.1);
            border-left: 4px solid var(--warning-color);
        }
        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body class="admin-layout">
    <?php include 'sidebar-component.php'; ?>

    <div class="admin-main-content">
        <?php 
        $pageTitle = 'System Monitoring';
        include 'header-component.php'; 
        ?>

        <div class="admin-content">
            <div class="page-header">
                <h1>📊 System Monitoring Dashboard</h1>
                <p>Real-time system health and performance metrics</p>
            </div>

            <!-- System Alerts -->
            <?php if(!empty($alerts)): ?>
            <div style="margin-bottom: 2rem;">
                <?php foreach($alerts as $alert): ?>
                <div class="alert-banner alert-<?php echo $alert['type']; ?>">
                    <span style="font-size: 1.5rem;"><?php echo $alert['type'] == 'warning' ? '⚠️' : '🚨'; ?></span>
                    <strong><?php echo $alert['message']; ?></strong>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- System Health Overview -->
            <div style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color: white; padding: 2rem; border-radius: var(--radius-lg); margin-bottom: 2rem;">
                <h2 style="margin: 0 0 1.5rem 0;">🏥 System Health Status</h2>
                <div class="grid grid-3">
                    <div>
                        <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                            <span class="health-indicator health-good"></span>
                            <strong>Database</strong>
                        </div>
                        <div style="opacity: 0.9; font-size: 0.9rem;">Connected & Operational</div>
                        <div style="opacity: 0.8; font-size: 0.85rem; margin-top: 0.25rem;">Size: <?php echo $stats['db_size']; ?> MB</div>
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                            <span class="health-indicator health-good"></span>
                            <strong>Server</strong>
                        </div>
                        <div style="opacity: 0.9; font-size: 0.9rem;">Running Smoothly</div>
                        <div style="opacity: 0.8; font-size: 0.85rem; margin-top: 0.25rem;">PHP <?php echo $stats['php_version']; ?></div>
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                            <span class="health-indicator <?php echo $stats['pending_approvals'] > 10 ? 'health-warning' : 'health-good'; ?>"></span>
                            <strong>Approvals</strong>
                        </div>
                        <div style="opacity: 0.9; font-size: 0.9rem;"><?php echo $stats['pending_approvals']; ?> Pending</div>
                        <div style="opacity: 0.8; font-size: 0.85rem; margin-top: 0.25rem;">
                            <?php echo $stats['pending_approvals'] > 10 ? 'Needs Attention' : 'Under Control'; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Metrics -->
            <div class="grid grid-4" style="margin-bottom: 2rem;">
                <div class="metric-card">
                    <div class="metric-label">👥 Active Users</div>
                    <div class="metric-value"><?php echo $stats['total_active_users']; ?></div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">
                        <?php echo $stats['active_students']; ?> Students • 
                        <?php echo $stats['active_instructors']; ?> Instructors
                    </div>
                </div>

                <div class="metric-card" style="border-left-color: var(--success-color);">
                    <div class="metric-label">📝 Total Questions</div>
                    <div class="metric-value" style="color: var(--success-color);"><?php echo $stats['total_questions']; ?></div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">
                        <?php echo $stats['questions_today']; ?> added today
                    </div>
                </div>

                <div class="metric-card" style="border-left-color: var(--warning-color);">
                    <div class="metric-label">📋 Total Exams</div>
                    <div class="metric-value" style="color: var(--warning-color);"><?php echo $stats['total_exams']; ?></div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">
                        <?php echo $stats['exams_today']; ?> taken today
                    </div>
                </div>

                <div class="metric-card" style="border-left-color: #9c27b0;">
                    <div class="metric-label">📊 Avg Score</div>
                    <div class="metric-value" style="color: #9c27b0;"><?php echo $stats['avg_exam_score']; ?>%</div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">
                        Pass Rate: <?php echo $stats['pass_rate']; ?>%
                    </div>
                </div>
            </div>

            <div class="grid grid-2">
                <!-- Question Status Distribution -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">📊 Question Status Distribution</h3>
                    </div>
                    <div style="padding: 2rem;">
                        <div style="margin-bottom: 1rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>✓ Approved</span>
                                <strong><?php echo $questionStats['approved']; ?></strong>
                            </div>
                            <div style="background: var(--bg-light); height: 8px; border-radius: 4px; overflow: hidden;">
                                <div style="background: var(--success-color); height: 100%; width: <?php echo ($questionStats['approved'] / $stats['total_questions'] * 100); ?>%;"></div>
                            </div>
                        </div>

                        <div style="margin-bottom: 1rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>⏳ Pending</span>
                                <strong><?php echo $questionStats['pending']; ?></strong>
                            </div>
                            <div style="background: var(--bg-light); height: 8px; border-radius: 4px; overflow: hidden;">
                                <div style="background: var(--warning-color); height: 100%; width: <?php echo ($questionStats['pending'] / $stats['total_questions'] * 100); ?>%;"></div>
                            </div>
                        </div>

                        <div style="margin-bottom: 1rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>✏️ Revision</span>
                                <strong><?php echo $questionStats['revision']; ?></strong>
                            </div>
                            <div style="background: var(--bg-light); height: 8px; border-radius: 4px; overflow: hidden;">
                                <div style="background: #ff9800; height: 100%; width: <?php echo ($questionStats['revision'] / $stats['total_questions'] * 100); ?>%;"></div>
                            </div>
                        </div>

                        <div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>✗ Rejected</span>
                                <strong><?php echo $questionStats['rejected']; ?></strong>
                            </div>
                            <div style="background: var(--bg-light); height: 8px; border-radius: 4px; overflow: hidden;">
                                <div style="background: #dc3545; height: 100%; width: <?php echo ($questionStats['rejected'] / $stats['total_questions'] * 100); ?>%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Performing Students -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">🏆 Top Performing Students</h3>
                    </div>
                    <div style="padding: 2rem;">
                        <?php if($topStudents && $topStudents->num_rows > 0): ?>
                        <?php $rank = 1; while($student = $topStudents->fetch_assoc()): ?>
                        <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--bg-light); border-radius: var(--radius-md); margin-bottom: 0.75rem;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: <?php echo $rank == 1 ? '#ffd700' : ($rank == 2 ? '#c0c0c0' : '#cd7f32'); ?>; display: flex; align-items: center; justify-content: center; font-weight: 800; color: white;">
                                <?php echo $rank; ?>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600;"><?php echo $student['Name']; ?></div>
                                <div style="font-size: 0.85rem; color: var(--text-secondary);">
                                    <?php echo $student['exam_count']; ?> exams taken
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 1.5rem; font-weight: 800; color: var(--success-color);">
                                    <?php echo round($student['avg_score'], 1); ?>%
                                </div>
                            </div>
                        </div>
                        <?php $rank++; endwhile; ?>
                        <?php else: ?>
                        <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                            No exam results yet
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Activity & Server Info -->
            <div class="grid grid-2 mt-4">
                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">🕐 Recent Activity</h3>
                    </div>
                    <div style="max-height: 400px; overflow-y: auto;">
                        <?php if($recentActivity && $recentActivity->num_rows > 0): ?>
                        <?php while($activity = $recentActivity->fetch_assoc()): ?>
                        <div class="activity-item">
                            <div>
                                <div style="font-weight: 600;"><?php echo $activity['activity']; ?></div>
                                <div style="font-size: 0.85rem; color: var(--text-secondary);">
                                    <?php echo $activity['user']; ?>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 700; color: var(--primary-color);">
                                    <?php echo $activity['details']; ?>%
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary);">
                                    Just now
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                            No recent activity
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Server Information -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">🖥️ Server Information</h3>
                    </div>
                    <div style="padding: 2rem;">
                        <div style="margin-bottom: 1.5rem;">
                            <strong style="color: var(--primary-color);">Database</strong>
                            <div style="margin-top: 0.5rem; padding: 0.75rem; background: var(--bg-light); border-radius: var(--radius-md);">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span>Size:</span>
                                    <strong><?php echo $stats['db_size']; ?> MB</strong>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span>Tables:</span>
                                    <strong><?php echo $stats['table_count']; ?></strong>
                                </div>
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <strong style="color: var(--primary-color);">PHP Configuration</strong>
                            <div style="margin-top: 0.5rem; padding: 0.75rem; background: var(--bg-light); border-radius: var(--radius-md);">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span>Version:</span>
                                    <strong><?php echo $stats['php_version']; ?></strong>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span>Max Upload:</span>
                                    <strong><?php echo $stats['max_upload']; ?></strong>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span>Memory Limit:</span>
                                    <strong><?php echo $stats['memory_limit']; ?></strong>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span>Max Execution:</span>
                                    <strong><?php echo $stats['max_execution']; ?>s</strong>
                                </div>
                            </div>
                        </div>

                        <div>
                            <strong style="color: var(--primary-color);">Server</strong>
                            <div style="margin-top: 0.5rem; padding: 0.75rem; background: var(--bg-light); border-radius: var(--radius-md);">
                                <div style="font-size: 0.9rem; word-break: break-all;">
                                    <?php echo $stats['server_software']; ?>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 1.5rem;">
                            <button onclick="location.reload()" class="btn btn-primary btn-block">
                                🔄 Refresh Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
    <script>
        // Auto-refresh every 30 seconds
        setTimeout(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
<?php $con->close(); ?>
