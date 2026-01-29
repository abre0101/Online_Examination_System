<?php
if (!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Name'])){
    header("Location:../auth/institute-login.php");
    exit();
}

$pageTitle = "Help & Support";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help - Instructor</title>
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
                <h1>❓ Help & Support</h1>
                <p>Get assistance with the system</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">📞 Contact Support</h3>
                </div>
                <div style="padding: 2rem;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                        <div>
                            <strong>📧 Email:</strong>
                            <p>support@dmu.edu</p>
                        </div>
                        <div>
                            <strong>📱 Phone:</strong>
                            <p>+251 911 234 567</p>
                        </div>
                        <div>
                            <strong>⏰ Hours:</strong>
                            <p>Mon-Fri: 8AM - 5PM</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">📚 Quick Guides</h3>
                </div>
                <div style="padding: 2rem;">
                    <div class="quick-actions-grid">
                        <div class="action-card">
                            <div class="action-icon">📄</div>
                            <div class="action-title">User Manual</div>
                            <div class="action-desc">Download PDF guide</div>
                        </div>
                        <div class="action-card">
                            <div class="action-icon">🎥</div>
                            <div class="action-title">Video Tutorials</div>
                            <div class="action-desc">Watch how-to videos</div>
                        </div>
                        <div class="action-card">
                            <div class="action-icon">❓</div>
                            <div class="action-title">FAQ</div>
                            <div class="action-desc">Common questions</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
</body>
</html>
