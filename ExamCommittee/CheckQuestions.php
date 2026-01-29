<?php
if (!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Name'])){
    header("Location:../auth/institute-login.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");
$pageTitle = "Check Questions";

// Get questions from question_page table
$questions = $con->query("SELECT qp.*, ec.exam_name 
    FROM question_page qp 
    LEFT JOIN exam_category ec ON qp.exam_id = ec.exam_id 
    ORDER BY qp.question_id DESC 
    LIMIT 20");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Questions - Exam Committee</title>
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
                <h1>🔍 Check Questions</h1>
                <p>Review and approve examination questions</p>
            </div>

            <!-- Questions List -->
            <div>
                <?php if($questions && $questions->num_rows > 0): ?>
                    <?php while($q = $questions->fetch_assoc()): ?>
                    <div style="background: white; border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); border-left: 4px solid var(--primary-color);">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <div>
                                <h3 style="margin: 0 0 0.5rem 0; color: var(--primary-color);">
                                    <?php echo $q['exam_name'] ?? 'Exam Question'; ?>
                                </h3>
                                <div style="font-size: 0.9rem; color: var(--text-secondary);">
                                    <strong>Course:</strong> <?php echo $q['course_name'] ?? 'N/A'; ?> | 
                                    <strong>Question ID:</strong> <?php echo $q['question_id']; ?>
                                </div>
                            </div>
                            <span style="padding: 0.35rem 0.75rem; border-radius: var(--radius-sm); font-size: 0.85rem; font-weight: 700; background: #ffc107; color: #000;">
                                Pending Review
                            </span>
                        </div>
                        
                        <div style="padding: 1rem; background: var(--bg-light); border-radius: var(--radius-md); margin: 1rem 0;">
                            <strong>Course Code:</strong> <?php echo $q['course_name']; ?> | 
                            <strong>Exam ID:</strong> <?php echo $q['exam_id']; ?> |
                            <strong>Semester:</strong> <?php echo $q['semester']; ?>
                        </div>
                        
                        <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                            <a href="ViewQuestion.php?id=<?php echo $q['question_id']; ?>" class="btn btn-primary btn-sm">
                                👁️ View Details
                            </a>
                            <button class="btn btn-success btn-sm" onclick="alert('Approval feature coming soon')">
                                ✓ Approve
                            </button>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 4rem; background: white; border-radius: var(--radius-lg);">
                        <h3 style="color: var(--text-secondary);">No questions found</h3>
                        <p>Questions will appear here when instructors submit them.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
</body>
</html>
<?php $con->close(); ?>
