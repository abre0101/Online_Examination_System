<?php
if (!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Name'])){
    header("Location:../auth/institute-login.php");
    exit();
}

$pageTitle = "Settings";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Instructor</title>
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
                <h1>⚙️ Settings</h1>
                <p>Manage your account settings</p>
            </div>

            <div class="settings-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">👤 Profile Settings</h3>
                    </div>
                    <div style="padding: 1.5rem;">
                        <p>Update your personal information</p>
                        <a href="EditProfile.php" class="btn btn-primary btn-block" style="margin-top: 1rem;">Edit Profile</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">🔒 Change Password</h3>
                    </div>
                    <div style="padding: 1.5rem;">
                        <p>Update your account password</p>
                        <a href="ChangePassword.php" class="btn btn-primary btn-block" style="margin-top: 1rem;">Change Password</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">📧 Notifications</h3>
                    </div>
                    <div style="padding: 1.5rem;">
                        <label style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <input type="checkbox" checked>
                            <span>Email notifications</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 1rem;">
                            <input type="checkbox" checked>
                            <span>Exam approval alerts</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
</body>
</html>
