<?php
session_start();
if(isset($_SESSION['username'])){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Debre Markos University Health Campus</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="modern-header">
        <div class="header-top">
            <div class="container">
                <div class="university-info">
                    <img src="../images/logo1.png" alt="Debre Markos University Health Campus" class="university-logo" onerror="this.style.display='none'">
                    <div class="university-name">
                        <h1>Debre Markos University Health Campus</h1>
                        <p>Online Examination System - Admin Panel</p>
                    </div>
                </div>
                <div class="header-actions">
                    <div class="user-info">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                        </div>
                        <div>
                            <div style="font-weight: 600;"><?php echo $_SESSION['username']; ?></div>
                            <div style="font-size: 0.75rem; opacity: 0.8;">Administrator</div>
                        </div>
                    </div>
                    <a href="Logout.php" class="btn btn-danger btn-sm">Logout</a>
                </div>
            </div>
        </div>
        <nav class="main-nav">
            <div class="container">
                <ul class="nav-menu">
                    <li><a href="index-modern.php" class="active">Dashboard</a></li>
                    <li><a href="Faculty.php">College</a></li>
                    <li><a href="Department.php">Department</a></li>
                    <li><a href="Course.php">Course</a></li>
                    <li><a href="ECommittee.php">Exam Committee</a></li>
                    <li><a href="Instructor.php">Instructor</a></li>
                    <li><a href="Student.php">Student</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Welcome Section -->
            <div class="welcome-banner">
                <div class="welcome-content">
                    <h1>👋 Welcome back, <?php echo $_SESSION['username']; ?>!</h1>
                    <p>Here's what's happening with your examination system today.</p>
                </div>
                <div class="welcome-image">
                    <img src="images/Admin.png" alt="Admin" onerror="this.style.display='none'">
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card stat-primary">
                    <div class="stat-icon">👨‍🎓</div>
                    <div class="stat-details">
                        <div class="stat-value">
                            <?php
                            $con = new mysqli("localhost","root","","oes");
                            $result = $con->query("SELECT COUNT(*) as count FROM student");
                            $row = $result->fetch_assoc();
                            echo $row['count'];
                            $con->close();
                            ?>
                        </div>
                        <div class="stat-label">Total Students</div>
                    </div>
                </div>

                <div class="stat-card stat-success">
                    <div class="stat-icon">👨‍🏫</div>
                    <div class="stat-details">
                        <div class="stat-value">
                            <?php
                            $con = new mysqli("localhost","root","","oes");
                            $result = $con->query("SELECT COUNT(*) as count FROM instructor");
                            $row = $result->fetch_assoc();
                            echo $row['count'];
                            $con->close();
                            ?>
                        </div>
                        <div class="stat-label">Total Instructors</div>
                    </div>
                </div>

                <div class="stat-card stat-warning">
                    <div class="stat-icon">📚</div>
                    <div class="stat-details">
                        <div class="stat-value">
                            <?php
                            $con = new mysqli("localhost","root","","oes");
                            $result = $con->query("SELECT COUNT(*) as count FROM course");
                            $row = $result->fetch_assoc();
                            echo $row['count'];
                            $con->close();
                            ?>
                        </div>
                        <div class="stat-label">Total Courses</div>
                    </div>
                </div>

                <div class="stat-card stat-info">
                    <div class="stat-icon">🏛️</div>
                    <div class="stat-details">
                        <div class="stat-value">
                            <?php
                            $con = new mysqli("localhost","root","","oes");
                            $result = $con->query("SELECT COUNT(*) as count FROM department");
                            $row = $result->fetch_assoc();
                            echo $row['count'];
                            $con->close();
                            ?>
                        </div>
                        <div class="stat-label">Total Departments</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="content-wrapper mt-4">
                <h2>⚡ Quick Actions</h2>
                <div class="quick-actions-grid">
                    <a href="InsertStudent.php" class="action-card">
                        <div class="action-icon">➕</div>
                        <div class="action-title">Add Student</div>
                        <div class="action-desc">Register new student</div>
                    </a>
                    <a href="InsertInstructor.php" class="action-card">
                        <div class="action-icon">➕</div>
                        <div class="action-title">Add Instructor</div>
                        <div class="action-desc">Register new instructor</div>
                    </a>
                    <a href="InsertCourse.php" class="action-card">
                        <div class="action-icon">➕</div>
                        <div class="action-title">Add Course</div>
                        <div class="action-desc">Create new course</div>
                    </a>
                    <a href="InsertDepartment.php" class="action-card">
                        <div class="action-icon">➕</div>
                        <div class="action-title">Add Department</div>
                        <div class="action-desc">Create new department</div>
                    </a>
                    <a href="Student.php" class="action-card">
                        <div class="action-icon">📋</div>
                        <div class="action-title">Manage Students</div>
                        <div class="action-desc">View and edit students</div>
                    </a>
                    <a href="Instructor.php" class="action-card">
                        <div class="action-icon">📋</div>
                        <div class="action-title">Manage Instructors</div>
                        <div class="action-desc">View and edit instructors</div>
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-2 mt-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">📊 System Overview</h3>
                    </div>
                    <div class="overview-list">
                        <div class="overview-item">
                            <span>Active Students</span>
                            <strong>
                                <?php
                                $con = new mysqli("localhost","root","","oes");
                                $result = $con->query("SELECT COUNT(*) as count FROM student WHERE Status='Active'");
                                $row = $result->fetch_assoc();
                                echo $row['count'];
                                $con->close();
                                ?>
                            </strong>
                        </div>
                        <div class="overview-item">
                            <span>Active Instructors</span>
                            <strong>
                                <?php
                                $con = new mysqli("localhost","root","","oes");
                                $result = $con->query("SELECT COUNT(*) as count FROM instructor WHERE Status='Active'");
                                $row = $result->fetch_assoc();
                                echo $row['count'];
                                $con->close();
                                ?>
                            </strong>
                        </div>
                        <div class="overview-item">
                            <span>Total Faculties</span>
                            <strong>
                                <?php
                                $con = new mysqli("localhost","root","","oes");
                                $result = $con->query("SELECT COUNT(*) as count FROM faculty");
                                $row = $result->fetch_assoc();
                                echo $row['count'];
                                $con->close();
                                ?>
                            </strong>
                        </div>
                        <div class="overview-item">
                            <span>Exam Committee Members</span>
                            <strong>
                                <?php
                                $con = new mysqli("localhost","root","","oes");
                                $result = $con->query("SELECT COUNT(*) as count FROM exam_committee");
                                $row = $result->fetch_assoc();
                                echo $row['count'];
                                $con->close();
                                ?>
                            </strong>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">🔔 Recent Updates</h3>
                    </div>
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon" style="background: var(--primary-color);">👤</div>
                            <div class="activity-content">
                                <div class="activity-title">System Access</div>
                                <div class="activity-time">You logged in just now</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon" style="background: var(--secondary-color);">✓</div>
                            <div class="activity-content">
                                <div class="activity-title">System Status</div>
                                <div class="activity-time">All systems operational</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="modern-footer">
        <div class="container">
            <div class="footer-content">
                <p>&copy; 2026 Debre Markos University Health Campus Online Examination System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
<?php 
} else {
    header("Location:../index-modern.php");
    exit();
}
?>
