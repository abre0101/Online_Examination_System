<?php
if (!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Name'])){
    header("Location:../auth/institute-login.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");
$pageTitle = "My Courses";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - Instructor</title>
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
                <h1>👨‍🏫 My Courses</h1>
                <p>Manage your assigned courses</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Current Courses</h3>
                </div>
                <div style="padding: 2rem;">
                    <div style="background: white; border: 2px solid var(--primary-color); padding: 2rem; border-radius: var(--radius-lg); margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                        <h3 style="margin: 0 0 0.5rem 0; color: var(--primary-color);"><?php echo $_SESSION['Course']; ?></h3>
                        <p style="margin: 0; color: var(--text-secondary); font-weight: 500;">Department: <?php echo $_SESSION['Dept']; ?></p>
                        <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                            <button class="btn btn-primary">View Details</button>
                            <button class="btn btn-secondary">Student Roster</button>
                        </div>
                    </div>
                    
                    <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                        <p>Additional courses will appear here</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
</body>
</html>
<?php $con->close(); ?>
