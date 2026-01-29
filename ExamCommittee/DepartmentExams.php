<?php
if (!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Name'])){
    header("Location:../auth/institute-login.php");
    exit();
}

$con = new mysqli("localhost","root","","oes");
$pageTitle = "Department Exams";

// Get all departments
$departments = $con->query("SELECT * FROM department");
$selected_dept = $_GET['dept'] ?? $_SESSION['Dept'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Exams - Exam Committee</title>
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
                <h1>🏛️ Department Exams</h1>
                <p>Filter and view exams by department</p>
            </div>

            <!-- Department Selector -->
            <div style="background: white; padding: 1.5rem; border-radius: var(--radius-lg); margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                <form method="GET">
                    <div class="form-group">
                        <label>Select Department</label>
                        <select name="dept" class="form-control" onchange="this.form.submit()">
                            <?php while($dept = $departments->fetch_assoc()): ?>
                            <option value="<?php echo $dept['dept_name']; ?>" <?php echo ($selected_dept == $dept['dept_name']) ? 'selected' : ''; ?>>
                                <?php echo $dept['dept_name']; ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </form>
            </div>

            <div style="text-align: center; padding: 4rem; background: white; border-radius: var(--radius-lg);">
                <h3>Department: <?php echo htmlspecialchars($selected_dept); ?></h3>
                <p style="color: var(--text-secondary);">Department exam data will appear here.</p>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
</body>
</html>
<?php $con->close(); ?>
