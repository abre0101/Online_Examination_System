<?php
session_start();
if(!isset($_SESSION['Name'])){
    header("Location:../auth/institute-login.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");
$pageTitle = "Analytics & Insights";

// Get question difficulty analysis
$questionDifficulty = $con->query("SELECT 
    qp.Question_ID,
    qp.Question,
    qp.course_name,
    COUNT(DISTINCT sa.student_id) as attempt_count,
    SUM(CASE WHEN sa.is_correct = 1 THEN 1 ELSE 0 END) as correct_count,
    ROUND((SUM(CASE WHEN sa.is_correct = 1 THEN 1 ELSE 0 END) * 100.0 / NULLIF(COUNT(DISTINCT sa.student_id), 0)), 2) as success_rate
    FROM question_page qp
    LEFT JOIN student_answers sa ON qp.Question_ID = sa.question_id
    GROUP BY qp.Question_ID
    HAVING attempt_count > 0
    ORDER BY success_rate ASC
    LIMIT 20");

// Get performance trends over time
$performanceTrends = $con->query("SELECT 
    DATE(r.Result_ID) as exam_date,
    AVG(r.Result) as avg_score,
    COUNT(*) as exam_count,
    SUM(CASE WHEN r.Result >= 50 THEN 1 ELSE 0 END) as pass_count
    FROM result r
    WHERE r.Result > 0
    GROUP BY DATE(r.Result_ID)
    ORDER BY exam_date DESC
    LIMIT 30");

// Get course performance comparison
$coursePerformance = $con->query("SELECT 
    qp.course_name,
    COUNT(DISTINCT qp.Question_ID) as question_count,
    COUNT(DISTINCT sa.student_id) as student_count,
    ROUND(AVG(CASE WHEN sa.is_correct = 1 THEN 100 ELSE 0 END), 2) as avg_accuracy
    FROM question_page qp
    LEFT JOIN student_answers sa ON qp.Question_ID = sa.question_id
    WHERE qp.course_name IS NOT NULL
    GROUP BY qp.course_name
    ORDER BY avg_accuracy DESC");

// Get topic performance (if topics exist)
$topicPerformance = $con->query("SELECT 
    qt.topic_name,
    qt.course_name,
    COUNT(DISTINCT qp.Question_ID) as question_count,
    ROUND(AVG(CASE WHEN sa.is_correct = 1 THEN 100 ELSE 0 END), 2) as avg_accuracy
    FROM question_topics qt
    LEFT JOIN question_page qp ON qt.topic_id = qp.topic_id
    LEFT JOIN student_answers sa ON qp.Question_ID = sa.question_id
    GROUP BY qt.topic_id
    HAVING question_count > 0
    ORDER BY avg_accuracy ASC
    LIMIT 10");

// Get overall statistics
$stats = [];
$stats['total_questions'] = $con->query("SELECT COUNT(*) as count FROM question_page")->fetch_assoc()['count'];
$stats['total_attempts'] = $con->query("SELECT COUNT(*) as count FROM student_answers")->fetch_assoc()['count'];
$stats['avg_difficulty'] = $con->query("SELECT ROUND(AVG(CASE WHEN sa.is_correct = 1 THEN 100 ELSE 0 END), 2) as avg FROM student_answers sa")->fetch_assoc()['avg'] ?? 0;
$stats['hardest_questions'] = $con->query("SELECT COUNT(*) as count FROM (
    SELECT qp.Question_ID, 
    ROUND((SUM(CASE WHEN sa.is_correct = 1 THEN 1 ELSE 0 END) * 100.0 / NULLIF(COUNT(sa.student_id), 0)), 2) as success_rate
    FROM question_page qp
    LEFT JOIN student_answers sa ON qp.Question_ID = sa.question_id
    GROUP BY qp.Question_ID
    HAVING success_rate < 50 AND COUNT(sa.student_id) > 0
) as hard_questions")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Instructor</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-sidebar.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .difficulty-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .difficulty-easy { background: rgba(40, 167, 69, 0.1); color: var(--success-color); }
        .difficulty-medium { background: rgba(255, 193, 7, 0.1); color: var(--warning-color); }
        .difficulty-hard { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
    </style>
</head>
<body class="admin-layout">
    <?php include 'sidebar-component.php'; ?>

    <div class="admin-main-content">
        <?php include 'header-component.php'; ?>

        <div class="admin-content">
            <div class="page-header">
                <h1>📊 Analytics & Insights</h1>
                <p>Question difficulty analysis and student performance trends</p>
            </div>

            <!-- Key Metrics -->
            <div class="grid grid-4" style="margin-bottom: 2rem;">
                <div style="background: white; padding: 1.5rem; border-radius: var(--radius-lg); text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="font-size: 2.5rem; font-weight: 800; color: var(--primary-color);">
                        <?php echo $stats['total_questions']; ?>
                    </div>
                    <div style="font-size: 0.9rem; color: var(--text-secondary);">Total Questions</div>
                </div>
                <div style="background: white; padding: 1.5rem; border-radius: var(--radius-lg); text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="font-size: 2.5rem; font-weight: 800; color: var(--success-color);">
                        <?php echo $stats['total_attempts']; ?>
                    </div>
                    <div style="font-size: 0.9rem; color: var(--text-secondary);">Total Attempts</div>
                </div>
                <div style="background: white; padding: 1.5rem; border-radius: var(--radius-lg); text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="font-size: 2.5rem; font-weight: 800; color: var(--warning-color);">
                        <?php echo $stats['avg_difficulty']; ?>%
                    </div>
                    <div style="font-size: 0.9rem; color: var(--text-secondary);">Avg Success Rate</div>
                </div>
                <div style="background: white; padding: 1.5rem; border-radius: var(--radius-lg); text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="font-size: 2.5rem; font-weight: 800; color: #dc3545;">
                        <?php echo $stats['hardest_questions']; ?>
                    </div>
                    <div style="font-size: 0.9rem; color: var(--text-secondary);">Hard Questions (<50%)</div>
                </div>
            </div>

            <div class="grid grid-2">
                <!-- Question Difficulty Analysis -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">🎯 Most Difficult Questions</h3>
                    </div>
                    <div style="padding: 2rem; max-height: 600px; overflow-y: auto;">
                        <?php if($questionDifficulty && $questionDifficulty->num_rows > 0): ?>
                        <?php while($q = $questionDifficulty->fetch_assoc()): ?>
                        <div style="background: var(--bg-light); padding: 1.5rem; border-radius: var(--radius-md); margin-bottom: 1rem; border-left: 4px solid <?php 
                            echo $q['success_rate'] < 40 ? '#dc3545' : ($q['success_rate'] < 70 ? 'var(--warning-color)' : 'var(--success-color)'); 
                        ?>;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.75rem;">
                                <div style="flex: 1;">
                                    <strong style="color: var(--primary-color);">Question #<?php echo $q['Question_ID']; ?></strong>
                                    <div style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.25rem;">
                                        <?php echo $q['course_name']; ?>
                                    </div>
                                </div>
                                <span class="difficulty-badge difficulty-<?php 
                                    echo $q['success_rate'] < 40 ? 'hard' : ($q['success_rate'] < 70 ? 'medium' : 'easy'); 
                                ?>">
                                    <?php echo $q['success_rate']; ?>% Success
                                </span>
                            </div>
                            <p style="margin: 0 0 0.75rem 0; color: var(--text-secondary); font-size: 0.9rem;">
                                <?php echo substr($q['Question'], 0, 100); ?><?php echo strlen($q['Question']) > 100 ? '...' : ''; ?>
                            </p>
                            <div style="display: flex; gap: 2rem; font-size: 0.85rem; color: var(--text-secondary);">
                                <div>
                                    <strong><?php echo $q['attempt_count']; ?></strong> attempts
                                </div>
                                <div>
                                    <strong><?php echo $q['correct_count']; ?></strong> correct
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">📊</div>
                            <p>No question attempt data available yet.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Performance Trends Chart -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">📈 Performance Trends</h3>
                    </div>
                    <div style="padding: 2rem;">
                        <canvas id="performanceChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Course Performance Comparison -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">📚 Course Performance Comparison</h3>
                </div>
                <div style="padding: 2rem;">
                    <?php if($coursePerformance && $coursePerformance->num_rows > 0): ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem;">
                        <?php while($course = $coursePerformance->fetch_assoc()): ?>
                        <div style="background: var(--bg-light); padding: 1.5rem; border-radius: var(--radius-md); text-align: center;">
                            <h4 style="margin: 0 0 1rem 0; color: var(--primary-color);">
                                <?php echo $course['course_name']; ?>
                            </h4>
                            <div style="font-size: 2.5rem; font-weight: 800; color: <?php 
                                echo $course['avg_accuracy'] < 50 ? '#dc3545' : ($course['avg_accuracy'] < 75 ? 'var(--warning-color)' : 'var(--success-color)'); 
                            ?>; margin-bottom: 0.5rem;">
                                <?php echo $course['avg_accuracy']; ?>%
                            </div>
                            <div style="font-size: 0.85rem; color: var(--text-secondary);">
                                <?php echo $course['question_count']; ?> questions • 
                                <?php echo $course['student_count']; ?> students
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <div style="text-align: center; padding: 3rem; color: var(--text-secondary);">
                        No course performance data available
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Topic Performance (if available) -->
            <?php if($topicPerformance && $topicPerformance->num_rows > 0): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">📖 Weakest Topics (Need Attention)</h3>
                </div>
                <div style="padding: 2rem;">
                    <?php while($topic = $topicPerformance->fetch_assoc()): ?>
                    <div style="background: var(--bg-light); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="color: var(--primary-color);"><?php echo $topic['topic_name']; ?></strong>
                            <div style="font-size: 0.85rem; color: var(--text-secondary);">
                                <?php echo $topic['course_name']; ?> • <?php echo $topic['question_count']; ?> questions
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 1.5rem; font-weight: 800; color: <?php 
                                echo $topic['avg_accuracy'] < 50 ? '#dc3545' : ($topic['avg_accuracy'] < 70 ? 'var(--warning-color)' : 'var(--success-color)'); 
                            ?>;">
                                <?php echo $topic['avg_accuracy']; ?>%
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary);">Success Rate</div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Insights & Recommendations -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">💡 Insights & Recommendations</h3>
                </div>
                <div style="padding: 2rem;">
                    <div class="grid grid-2">
                        <div style="background: rgba(0, 123, 255, 0.1); padding: 1.5rem; border-radius: var(--radius-md); border-left: 4px solid var(--primary-color);">
                            <h4 style="margin: 0 0 0.5rem 0; color: var(--primary-color);">📊 Question Quality</h4>
                            <p style="margin: 0; color: var(--text-secondary);">
                                <?php 
                                if($stats['hardest_questions'] > 10) {
                                    echo "You have {$stats['hardest_questions']} questions with <50% success rate. Consider reviewing these for clarity or difficulty.";
                                } else {
                                    echo "Your questions have good difficulty balance. Keep monitoring student performance.";
                                }
                                ?>
                            </p>
                        </div>
                        <div style="background: rgba(40, 167, 69, 0.1); padding: 1.5rem; border-radius: var(--radius-md); border-left: 4px solid var(--success-color);">
                            <h4 style="margin: 0 0 0.5rem 0; color: var(--success-color);">✅ Best Practices</h4>
                            <ul style="margin: 0.5rem 0 0 1.5rem; color: var(--text-secondary);">
                                <li>Review questions with <40% success rate</li>
                                <li>Balance easy, medium, and hard questions</li>
                                <li>Use topics to organize questions</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
    <script>
        // Performance Trends Chart
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const performanceData = {
            labels: [
                <?php 
                $trends = [];
                if($performanceTrends) {
                    $performanceTrends->data_seek(0);
                    while($trend = $performanceTrends->fetch_assoc()) {
                        $trends[] = $trend;
                        echo "'" . date('M d', strtotime($trend['exam_date'])) . "',";
                    }
                }
                ?>
            ],
            datasets: [{
                label: 'Average Score',
                data: [
                    <?php 
                    foreach($trends as $trend) {
                        echo $trend['avg_score'] . ",";
                    }
                    ?>
                ],
                borderColor: 'rgb(0, 123, 255)',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }]
        };

        new Chart(ctx, {
            type: 'line',
            data: performanceData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
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
                }
            }
        });
    </script>
</body>
</html>
<?php $con->close(); ?>
