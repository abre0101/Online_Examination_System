<?php
if (!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Name'])){
    header("Location:../auth/institute-login.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");
$pageTitle = "Approved Exams";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Exams - Exam Committee</title>
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
                <h1>✅ Approved Exams</h1>
                <p>View your approval history</p>
            </div>

            <div style="text-align: center; padding: 4rem; background: white; border-radius: var(--radius-lg);">
                <h3 style="color: var(--text-secondary);">No approved exams yet</h3>
                <p>Approved exams will appear here.</p>
                <a href="CheckQuestions.php" class="btn btn-primary" style="margin-top: 1rem;">Review Questions</a>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
</body>
</html>
<?php $con->close(); ?>
