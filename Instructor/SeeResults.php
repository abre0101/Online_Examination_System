<?php
if (!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Name'])){
    header("Location:../auth/institute-login.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");
$pageTitle = "See Results";

// Get results
$results = $con->query("SELECT r.*, s.Name, ec.exam_name 
    FROM result r 
    LEFT JOIN student s ON r.Stud_ID = s.Id 
    LEFT JOIN exam_category ec ON r.exam_id = ec.exam_id 
    ORDER BY r.Stud_ID DESC 
    LIMIT 50");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>See Results - Instructor</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-sidebar.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="admin-layout">
    <?php include 'sidebar-component.php'; ?>

    <div class="admin-main-content">
        <?php include 'header-component.php'; ?>

        <div class="admin-content">
            <div class="page-header">
                <h1>📊 Student Results</h1>
                <p>View and analyze student performance</p>
            </div>

            <!-- Stats -->
            <div class="stats-grid" style="margin-bottom: 2rem;">
                <div class="stat-card stat-primary">
                    <div class="stat-icon">📝</div>
                    <div class="stat-details">
                        <div class="stat-value">
                            <?php echo $results ? $results->num_rows : 0; ?>
                        </div>
                        <div class="stat-label">Total Results</div>
                    </div>
                </div>
                
                <div class="stat-card stat-success">
                    <div class="stat-icon">✅</div>
                    <div class="stat-details">
                        <div class="stat-value">85%</div>
                        <div class="stat-label">Average Score</div>
                    </div>
                </div>
                
                <div class="stat-card stat-warning">
                    <div class="stat-icon">📈</div>
                    <div class="stat-details">
                        <div class="stat-value">92%</div>
                        <div class="stat-label">Pass Rate</div>
                    </div>
                </div>
                
                <div class="stat-card stat-info">
                    <div class="stat-icon">🏆</div>
                    <div class="stat-details">
                        <div class="stat-value">A</div>
                        <div class="stat-label">Top Grade</div>
                    </div>
                </div>
            </div>

            <!-- Results Table -->
            <div class="data-table-wrapper">
                <div class="table-header">
                    <h3 class="table-title">Student Results</h3>
                    <div class="table-actions">
                        <button class="btn btn-success btn-sm" onclick="exportResults()">
                            📤 Export Excel
                        </button>
                        <button class="btn btn-primary btn-sm" onclick="window.print()">
                            🖨️ Print
                        </button>
                    </div>
                </div>
                
                <div style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Exam</th>
                                <th>Score</th>
                                <th>Grade</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($results && $results->num_rows > 0): ?>
                                <?php while($result = $results->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $result['Stud_ID']; ?></td>
                                    <td><?php echo $result['Name'] ?? 'N/A'; ?></td>
                                    <td><?php echo $result['exam_name'] ?? 'Exam'; ?></td>
                                    <td><strong><?php echo $result['score'] ?? '0'; ?>%</strong></td>
                                    <td>
                                        <span class="badge badge-success">
                                            <?php echo $result['grade'] ?? 'N/A'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y'); ?></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm">View Details</button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 2rem;">
                                        No results available
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
    <script>
        function exportResults() {
            alert('Export functionality will be implemented');
        }
    </script>
</body>
</html>
<?php $con->close(); ?>
