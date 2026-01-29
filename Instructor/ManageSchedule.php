<?php
if (!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Name'])){
    header("Location:../auth/institute-login.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");
$pageTitle = "Manage Schedule";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Schedule - Instructor</title>
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
                <h1>📅 Manage Schedule</h1>
                <p>Schedule and manage exam dates</p>
            </div>

            <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                <button class="btn btn-primary" onclick="showScheduleForm()">
                    ➕ Schedule New Exam
                </button>
                <button class="btn btn-success">
                    📅 Calendar View
                </button>
            </div>

            <!-- Schedule Form (Hidden by default) -->
            <div id="scheduleForm" style="display: none; background: white; padding: 2rem; border-radius: var(--radius-lg); margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                <h3 style="margin-bottom: 1.5rem;">Schedule New Exam</h3>
                <form>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Exam</label>
                            <select class="form-control" required>
                                <option>Select Exam</option>
                                <option>Midterm Exam</option>
                                <option>Final Exam</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Time</label>
                            <input type="time" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save Schedule</button>
                        <button type="button" class="btn btn-secondary" onclick="hideScheduleForm()">Cancel</button>
                    </div>
                </form>
            </div>

            <!-- Upcoming Exams -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">📅 Upcoming Exams</h3>
                </div>
                <div style="padding: 2rem;">
                    <div style="text-align: center; color: var(--text-secondary);">
                        <p>No scheduled exams</p>
                        <button class="btn btn-primary" style="margin-top: 1rem;" onclick="showScheduleForm()">
                            Schedule Your First Exam
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
    <script>
        function showScheduleForm() {
            document.getElementById('scheduleForm').style.display = 'block';
        }
        function hideScheduleForm() {
            document.getElementById('scheduleForm').style.display = 'none';
        }
    </script>
</body>
</html>
<?php $con->close(); ?>
