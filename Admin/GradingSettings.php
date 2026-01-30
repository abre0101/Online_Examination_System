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
        $passMark = 50; // Fixed at 50% for D grade
        
        // Define the new grading scheme
        $gradeBoundaries = json_encode(array(
            'A+' => array('min' => 90, 'max' => 100, 'gpa' => 4.0, 'status' => 'Excellent'),
            'A'  => array('min' => 85, 'max' => 89.99, 'gpa' => 3.75, 'status' => 'Excellent'),
            'A-' => array('min' => 80, 'max' => 84.99, 'gpa' => 3.5, 'status' => 'Excellent'),
            'B+' => array('min' => 75, 'max' => 79.99, 'gpa' => 3.0, 'status' => 'Good'),
            'B'  => array('min' => 70, 'max' => 74.99, 'gpa' => 2.75, 'status' => 'Good'),
            'B-' => array('min' => 65, 'max' => 69.99, 'gpa' => 2.5, 'status' => 'Good'),
            'C+' => array('min' => 60, 'max' => 64.99, 'gpa' => 2.0, 'status' => 'Satisfactory'),
            'C'  => array('min' => 55, 'max' => 59.99, 'gpa' => 1.75, 'status' => 'Satisfactory'),
            'C-' => array('min' => 50, 'max' => 54.99, 'gpa' => 1.5, 'status' => 'Satisfactory'),
            'D'  => array('min' => 45, 'max' => 49.99, 'gpa' => 1.0, 'status' => 'Pass'),
            'F'  => array('min' => 0, 'max' => 44.99, 'gpa' => 0.0, 'status' => 'Fail')
        ));
        
        // Save pass mark
        $con->query("INSERT INTO grading_config (config_key, config_value) VALUES ('pass_mark', '$passMark') 
            ON DUPLICATE KEY UPDATE config_value='$passMark'");
        
        // Save grade boundaries
        $con->query("INSERT INTO grading_config (config_key, config_value) VALUES ('grade_boundaries', '$gradeBoundaries') 
            ON DUPLICATE KEY UPDATE config_value='$gradeBoundaries'");
        
        $message = 'Grading system updated successfully with GPA configuration!';
        $messageType = 'success';
    }
}

// Get current settings
$passMarkResult = $con->query("SELECT config_value FROM grading_config WHERE config_key='pass_mark'");
$passMark = $passMarkResult && $passMarkResult->num_rows > 0 ? $passMarkResult->fetch_assoc()['config_value'] : 50;

$gradeBoundariesResult = $con->query("SELECT config_value FROM grading_config WHERE config_key='grade_boundaries'");

// Default GPA-based grading scheme
$gradeBoundaries = array(
    'A+' => array('min' => 90, 'max' => 100, 'gpa' => 4.0, 'status' => 'Excellent'),
    'A'  => array('min' => 85, 'max' => 89.99, 'gpa' => 3.75, 'status' => 'Excellent'),
    'A-' => array('min' => 80, 'max' => 84.99, 'gpa' => 3.5, 'status' => 'Excellent'),
    'B+' => array('min' => 75, 'max' => 79.99, 'gpa' => 3.0, 'status' => 'Good'),
    'B'  => array('min' => 70, 'max' => 74.99, 'gpa' => 2.75, 'status' => 'Good'),
    'B-' => array('min' => 65, 'max' => 69.99, 'gpa' => 2.5, 'status' => 'Good'),
    'C+' => array('min' => 60, 'max' => 64.99, 'gpa' => 2.0, 'status' => 'Satisfactory'),
    'C'  => array('min' => 55, 'max' => 59.99, 'gpa' => 1.75, 'status' => 'Satisfactory'),
    'C-' => array('min' => 50, 'max' => 54.99, 'gpa' => 1.5, 'status' => 'Satisfactory'),
    'D'  => array('min' => 45, 'max' => 49.99, 'gpa' => 1.0, 'status' => 'Pass'),
    'F'  => array('min' => 0, 'max' => 44.99, 'gpa' => 0.0, 'status' => 'Fail')
);

if($gradeBoundariesResult && $gradeBoundariesResult->num_rows > 0) {
    $gradeBoundaries = json_decode($gradeBoundariesResult->fetch_assoc()['config_value'], true);
}

$con->close();

// Helper function to get grade from score
function getGradeFromScore($score, $gradeBoundaries) {
    foreach($gradeBoundaries as $grade => $range) {
        if($score >= $range['min'] && $score <= $range['max']) {
            return array('grade' => $grade, 'gpa' => $range['gpa'], 'status' => $range['status']);
        }
    }
    return array('grade' => 'F', 'gpa' => 0.0, 'status' => 'Fail');
}
?>
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
                <!-- Grading Configuration Info -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">⚙️ Grading System Configuration</h3>
                    </div>
                    <div style="padding: 2rem;">
                        <form method="POST">
                            <div style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); color: white; padding: 1.5rem; border-radius: var(--radius-lg); margin-bottom: 2rem; text-align: center;">
                                <div style="font-size: 3rem; font-weight: 800; margin-bottom: 0.5rem;">
                                    GPA 4.0
                                </div>
                                <div style="font-size: 1.1rem; opacity: 0.9;">
                                    Standard Grading Scale
                                </div>
                            </div>

                            <div style="background: var(--bg-light); padding: 1.5rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
                                <h4 style="margin: 0 0 1rem 0; color: var(--primary-color);">📋 Grading Information</h4>
                                <ul style="margin: 0; padding-left: 1.5rem; color: var(--text-secondary); line-height: 1.8;">
                                    <li><strong>Pass Mark:</strong> 50% (Grade C-)</li>
                                    <li><strong>Excellent:</strong> 80% and above (A-, A, A+)</li>
                                    <li><strong>Good:</strong> 65% - 79.99% (B-, B, B+)</li>
                                    <li><strong>Satisfactory:</strong> 50% - 64.99% (C-, C, C+)</li>
                                    <li><strong>Pass:</strong> 45% - 49.99% (D)</li>
                                    <li><strong>Fail:</strong> Below 45% (F)</li>
                                </ul>
                            </div>

                            <div style="background: rgba(0, 123, 255, 0.1); padding: 1rem; border-radius: var(--radius-md); border-left: 4px solid var(--primary-color); margin-bottom: 1.5rem;">
                                <strong>ℹ️ Note:</strong>
                                <p style="margin: 0.5rem 0 0 0; color: var(--text-secondary);">
                                    This grading system follows the standard GPA 4.0 scale with 11 grade levels for precise evaluation.
                                </p>
                            </div>

                            <div class="form-actions">
                                <button type="submit" name="save_grading" class="btn btn-primary">
                                    💾 Apply Grading System
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
                        <h3 class="card-title">📋 Complete Grading Scale</h3>
                    </div>
                    <div style="padding: 2rem;">
                        <h4 style="margin-bottom: 1.5rem; color: var(--primary-color);">Grade Distribution</h4>
                        
                        <?php foreach($gradeBoundaries as $grade => $details): ?>
                        <div style="margin-bottom: 1rem; padding: 1rem; background: <?php 
                            echo $details['status'] == 'Excellent' ? 'rgba(40, 167, 69, 0.1)' : 
                                 ($details['status'] == 'Good' ? 'rgba(0, 123, 255, 0.1)' : 
                                 ($details['status'] == 'Satisfactory' ? 'rgba(255, 193, 7, 0.1)' : 
                                 ($details['status'] == 'Pass' ? 'rgba(255, 152, 0, 0.1)' : 'rgba(220, 53, 69, 0.1)'))); 
                        ?>; border-left: 4px solid <?php 
                            echo $details['status'] == 'Excellent' ? 'var(--success-color)' : 
                                 ($details['status'] == 'Good' ? '#007bff' : 
                                 ($details['status'] == 'Satisfactory' ? '#ffc107' : 
                                 ($details['status'] == 'Pass' ? '#ff9800' : '#dc3545'))); 
                        ?>; border-radius: var(--radius-md);">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="font-size: 1.5rem; color: <?php 
                                        echo $details['status'] == 'Excellent' ? 'var(--success-color)' : 
                                             ($details['status'] == 'Good' ? '#007bff' : 
                                             ($details['status'] == 'Satisfactory' ? '#ffc107' : 
                                             ($details['status'] == 'Pass' ? '#ff9800' : '#dc3545'))); 
                                    ?>;"><?php echo $grade; ?></strong>
                                    <span style="margin-left: 0.5rem; color: var(--text-secondary); font-size: 0.9rem;">
                                        <?php echo $details['status']; ?>
                                    </span>
                                </div>
                                <div style="text-align: right;">
                                    <strong style="color: var(--text-primary); font-size: 1.1rem;">
                                        <?php echo $details['min']; ?>% - <?php echo $details['max']; ?>%
                                    </strong>
                                    <div style="font-size: 0.85rem; color: var(--text-secondary);">
                                        GPA: <?php echo $details['gpa']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Grading Guidelines -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">📚 GPA Grading System Guide</h3>
                </div>
                <div style="padding: 2rem;">
                    <div class="grid grid-3">
                        <div>
                            <h4 style="color: var(--primary-color); margin-bottom: 1rem;">🎯 Grade Categories</h4>
                            <ul style="color: var(--text-secondary); line-height: 1.8;">
                                <li><strong>Excellent (A+, A, A-):</strong> 80-100%</li>
                                <li><strong>Good (B+, B, B-):</strong> 65-79.99%</li>
                                <li><strong>Satisfactory (C+, C, C-):</strong> 50-64.99%</li>
                                <li><strong>Pass (D):</strong> 45-49.99%</li>
                                <li><strong>Fail (F):</strong> 0-44.99%</li>
                            </ul>
                        </div>
                        <div>
                            <h4 style="color: var(--primary-color); margin-bottom: 1rem;">📊 GPA Scale</h4>
                            <ul style="color: var(--text-secondary); line-height: 1.8;">
                                <li><strong>4.0 Scale:</strong> Standard international scale</li>
                                <li><strong>A+ = 4.0:</strong> Highest achievement</li>
                                <li><strong>C- = 1.5:</strong> Minimum passing GPA</li>
                                <li><strong>D = 1.0:</strong> Pass without GPA credit</li>
                                <li><strong>F = 0.0:</strong> Fail, must retake</li>
                            </ul>
                        </div>
                        <div>
                            <h4 style="color: var(--primary-color); margin-bottom: 1rem;">✅ Key Points</h4>
                            <ul style="color: var(--text-secondary); line-height: 1.8;">
                                <li>11 grade levels for precise evaluation</li>
                                <li>Pass mark is 50% (Grade C-)</li>
                                <li>GPA calculated automatically</li>
                                <li>Consistent across all courses</li>
                                <li>Aligned with international standards</li>
                            </ul>
                        </div>
                    </div>

                    <div style="margin-top: 2rem; padding: 1.5rem; background: rgba(0, 123, 255, 0.1); border-radius: var(--radius-md); border-left: 4px solid var(--primary-color);">
                        <h4 style="margin: 0 0 1rem 0; color: var(--primary-color);">📖 Example Calculations</h4>
                        <div class="grid grid-2">
                            <div>
                                <strong>Student Score: 87%</strong>
                                <ul style="margin: 0.5rem 0 0 1.5rem; color: var(--text-secondary);">
                                    <li>Grade: A (85-89.99%)</li>
                                    <li>GPA: 3.75</li>
                                    <li>Status: Excellent</li>
                                </ul>
                            </div>
                            <div>
                                <strong>Student Score: 52%</strong>
                                <ul style="margin: 0.5rem 0 0 1.5rem; color: var(--text-secondary);">
                                    <li>Grade: C- (50-54.99%)</li>
                                    <li>GPA: 1.5</li>
                                    <li>Status: Satisfactory (Pass)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
</body>
</html>
