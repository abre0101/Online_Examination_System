<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");

// Create grading_config table if it doesn't exist
$createTableSQL = "CREATE TABLE IF NOT EXISTS `grading_config` (
    `config_id` INT AUTO_INCREMENT PRIMARY KEY,
    `config_key` VARCHAR(50) UNIQUE,
    `config_value` TEXT,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
$con->query($createTableSQL);

$message = '';
$messageType = '';

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['save_grading'])) {
        $passMark = intval($_POST['pass_mark']);
        $excellentMark = intval($_POST['excellent_mark']);
        $goodMark = intval($_POST['good_mark']);
        $satisfactoryMark = intval($_POST['satisfactory_mark']);
        
        // Save pass mark
        $con->query("INSERT INTO grading_config (config_key, config_value) VALUES ('pass_mark', '$passMark') 
            ON DUPLICATE KEY UPDATE config_value='$passMark'");
        
        // Save grade boundaries
        $gradeBoundaries = json_encode(array(
            'A' => $excellentMark,
            'B' => $goodMark,
            'C' => $satisfactoryMark,
            'D' => $passMark,
            'F' => 0
        ));
        $con->query("INSERT INTO grading_config (config_key, config_value) VALUES ('grade_boundaries', '$gradeBoundaries') 
            ON DUPLICATE KEY UPDATE config_value='$gradeBoundaries'");
        
        $message = 'Grading settings saved successfully!';
        $messageType = 'success';
    }
}

// Get current settings
$passMarkResult = $con->query("SELECT config_value FROM grading_config WHERE config_key='pass_mark'");
$passMark = $passMarkResult && $passMarkResult->num_rows > 0 ? $passMarkResult->fetch_assoc()['config_value'] : 50;

$gradeBoundariesResult = $con->query("SELECT config_value FROM grading_config WHERE config_key='grade_boundaries'");
$gradeBoundaries = array('A' => 85, 'B' => 70, 'C' => 60, 'D' => 50, 'F' => 0);
if($gradeBoundariesResult && $gradeBoundariesResult->num_rows > 0) {
    $gradeBoundaries = json_decode($gradeBoundariesResult->fetch_assoc()['config_value'], true);
}

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grading Settings - Admin</title>
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
                <h1>📊 Grading & Pass Mark Configuration</h1>
                <p>Configure grading schemes and pass marks for examinations</p>
            </div>

            <?php if($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>" style="margin-bottom: 2rem; padding: 1.25rem; border-radius: var(--radius-lg); background: <?php echo $messageType == 'success' ? 'rgba(40, 167, 69, 0.1)' : 'rgba(220, 53, 69, 0.1)'; ?>; border-left: 4px solid <?php echo $messageType == 'success' ? 'var(--success-color)' : '#dc3545'; ?>;">
                <strong><?php echo $messageType == 'success' ? '✓' : '✗'; ?></strong> <?php echo $message; ?>
            </div>
            <?php endif; ?>

            <div class="grid grid-2">
                <!-- Grading Configuration Form -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">⚙️ Configure Grading System</h3>
                    </div>
                    <div style="padding: 2rem;">
                        <form method="POST">
                            <div class="form-group">
                                <label>Pass Mark (Minimum to Pass) *</label>
                                <input type="number" name="pass_mark" class="form-control" min="0" max="100" value="<?php echo $passMark; ?>" required>
                                <small style="color: var(--text-secondary);">Students must score at least this percentage to pass</small>
                            </div>

                            <h4 style="margin: 2rem 0 1rem 0; color: var(--primary-color);">Grade Boundaries</h4>
                            
                            <div class="form-group">
                                <label>Grade A (Excellent) - Minimum %</label>
                                <input type="number" name="excellent_mark" class="form-control" min="0" max="100" value="<?php echo $gradeBoundaries['A']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Grade B (Good) - Minimum %</label>
                                <input type="number" name="good_mark" class="form-control" min="0" max="100" value="<?php echo $gradeBoundaries['B']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label>Grade C (Satisfactory) - Minimum %</label>
                                <input type="number" name="satisfactory_mark" class="form-control" min="0" max="100" value="<?php echo $gradeBoundaries['C']; ?>" required>
                            </div>

                            <div style="background: var(--bg-light); padding: 1rem; border-radius: var(--radius-md); margin: 1.5rem 0;">
                                <strong>Note:</strong> Grade D will be from Pass Mark to Grade C, and Grade F will be below Pass Mark.
                            </div>

                            <div class="form-actions">
                                <button type="submit" name="save_grading" class="btn btn-primary">
                                    💾 Save Grading Settings
                                </button>
                                <a href="SystemSettings.php" class="btn btn-secondary">
                                    ← Back to Settings
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Current Grading Scheme Preview -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">📋 Current Grading Scheme</h3>
                    </div>
                    <div style="padding: 2rem;">
                        <div style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color: white; padding: 1.5rem; border-radius: var(--radius-lg); margin-bottom: 2rem; text-align: center;">
                            <div style="font-size: 3rem; font-weight: 800; margin-bottom: 0.5rem;">
                                <?php echo $passMark; ?>%
                            </div>
                            <div style="font-size: 1.1rem; opacity: 0.9;">
                                Minimum Pass Mark
                            </div>
                        </div>

                        <h4 style="margin-bottom: 1rem; color: var(--primary-color);">Grade Distribution</h4>
                        
                        <div style="margin-bottom: 1rem; padding: 1rem; background: rgba(40, 167, 69, 0.1); border-left: 4px solid var(--success-color); border-radius: var(--radius-md);">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="font-size: 1.5rem; color: var(--success-color);">A</strong>
                                    <span style="margin-left: 0.5rem; color: var(--text-secondary);">Excellent</span>
                                </div>
                                <strong style="color: var(--success-color);"><?php echo $gradeBoundaries['A']; ?>% - 100%</strong>
                            </div>
                        </div>

                        <div style="margin-bottom: 1rem; padding: 1rem; background: rgba(0, 123, 255, 0.1); border-left: 4px solid #007bff; border-radius: var(--radius-md);">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="font-size: 1.5rem; color: #007bff;">B</strong>
                                    <span style="margin-left: 0.5rem; color: var(--text-secondary);">Good</span>
                                </div>
                                <strong style="color: #007bff;"><?php echo $gradeBoundaries['B']; ?>% - <?php echo $gradeBoundaries['A'] - 1; ?>%</strong>
                            </div>
                        </div>

                        <div style="margin-bottom: 1rem; padding: 1rem; background: rgba(255, 193, 7, 0.1); border-left: 4px solid #ffc107; border-radius: var(--radius-md);">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="font-size: 1.5rem; color: #ffc107;">C</strong>
                                    <span style="margin-left: 0.5rem; color: var(--text-secondary);">Satisfactory</span>
                                </div>
                                <strong style="color: #ffc107;"><?php echo $gradeBoundaries['C']; ?>% - <?php echo $gradeBoundaries['B'] - 1; ?>%</strong>
                            </div>
                        </div>

                        <div style="margin-bottom: 1rem; padding: 1rem; background: rgba(255, 152, 0, 0.1); border-left: 4px solid #ff9800; border-radius: var(--radius-md);">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="font-size: 1.5rem; color: #ff9800;">D</strong>
                                    <span style="margin-left: 0.5rem; color: var(--text-secondary);">Pass</span>
                                </div>
                                <strong style="color: #ff9800;"><?php echo $passMark; ?>% - <?php echo $gradeBoundaries['C'] - 1; ?>%</strong>
                            </div>
                        </div>

                        <div style="padding: 1rem; background: rgba(220, 53, 69, 0.1); border-left: 4px solid #dc3545; border-radius: var(--radius-md);">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="font-size: 1.5rem; color: #dc3545;">F</strong>
                                    <span style="margin-left: 0.5rem; color: var(--text-secondary);">Fail</span>
                                </div>
                                <strong style="color: #dc3545;">0% - <?php echo $passMark - 1; ?>%</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grading Guidelines -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">📚 Grading Guidelines</h3>
                </div>
                <div style="padding: 2rem;">
                    <div class="grid grid-2">
                        <div>
                            <h4 style="color: var(--primary-color); margin-bottom: 1rem;">Best Practices</h4>
                            <ul style="color: var(--text-secondary); line-height: 1.8;">
                                <li>Set pass mark between 40-60% (50% is standard)</li>
                                <li>Ensure grade boundaries don't overlap</li>
                                <li>Grade A should be challenging (80-85% minimum)</li>
                                <li>Maintain consistency across all courses</li>
                                <li>Review and adjust based on performance data</li>
                            </ul>
                        </div>
                        <div>
                            <h4 style="color: var(--primary-color); margin-bottom: 1rem;">Common Grading Scales</h4>
                            <ul style="color: var(--text-secondary); line-height: 1.8;">
                                <li><strong>Standard:</strong> A(85+), B(70-84), C(60-69), D(50-59), F(0-49)</li>
                                <li><strong>Strict:</strong> A(90+), B(80-89), C(70-79), D(60-69), F(0-59)</li>
                                <li><strong>Lenient:</strong> A(80+), B(65-79), C(55-64), D(45-54), F(0-44)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
</body>
</html>
