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
    <link href="../assets/css/modern-v2.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="../assets/css/admin-sidebar.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="admin-layout">
<body class="admin-layout">
    <!-- Left Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <img src="../images/logo1.png" alt="Logo" class="sidebar-logo" onerror="this.style.display='none'">
            <h2 class="sidebar-title">Admin Panel</h2>
            <p class="sidebar-subtitle">Debre Markos University</p>
            <button class="sidebar-toggle-btn" onclick="toggleSidebarMinimize()" title="Toggle Sidebar">
                <span id="toggleIcon">◀</span>
            </button>
        </div>

        <nav class="sidebar-nav">
            <a href="index-modern.php" class="sidebar-nav-item active">
                <span class="sidebar-nav-icon">📊</span>
                <span>Dashboard</span>
            </a>
            <a href="Faculty.php" class="sidebar-nav-item">
                <span class="sidebar-nav-icon">🏛️</span>
                <span>College</span>
            </a>
            <a href="Department.php" class="sidebar-nav-item">
                <span class="sidebar-nav-icon">🏢</span>
                <span>Department</span>
            </a>
            <a href="Course.php" class="sidebar-nav-item">
                <span class="sidebar-nav-icon">📚</span>
                <span>Course</span>
            </a>
            <a href="ECommittee.php" class="sidebar-nav-item">
                <span class="sidebar-nav-icon">👥</span>
                <span>Exam Committee</span>
            </a>
            <a href="Instructor.php" class="sidebar-nav-item">
                <span class="sidebar-nav-icon">👨‍🏫</span>
                <span>Instructor</span>
            </a>
            <a href="Student-modern.php" class="sidebar-nav-item">
                <span class="sidebar-nav-icon">👨‍🎓</span>
                <span>Student</span>
            </a>
            <a href="SystemSettings.php" class="sidebar-nav-item">
                <span class="sidebar-nav-icon">⚙️</span>
                <span>System Settings</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name"><?php echo $_SESSION['username']; ?></div>
                    <div class="sidebar-user-role">Administrator</div>
                </div>
            </div>
            <a href="Logout.php" class="btn btn-danger btn-block">
                🚪 Logout
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="admin-main-content">
        <!-- Enhanced Header -->
        <header class="admin-header">
            <div class="header-left">
                <button class="mobile-menu-btn" onclick="toggleSidebar()">☰</button>
                <div class="header-breadcrumb">
                    <span class="breadcrumb-item">Admin</span>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-item active">Dashboard</span>
                </div>
            </div>
            
            <div class="header-center">
                <div class="header-search">
                    <span class="search-icon">🔍</span>
                    <input type="text" placeholder="Search students, courses, instructors..." class="search-input">
                </div>
            </div>
            
            <div class="header-right">
                <div class="header-datetime">
                    <div class="header-time" id="currentTime"></div>
                    <div class="header-date"><?php echo date('D, M d, Y'); ?></div>
                </div>
                
                <div class="header-notifications">
                    <button class="header-icon-btn" title="Notifications">
                        <span class="notification-icon">🔔</span>
                        <span class="notification-badge">3</span>
                    </button>
                </div>
                
                <div class="header-profile" onclick="toggleProfileDropdown(event)">
                    <div class="header-profile-avatar">
                        <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                    </div>
                    <div class="header-profile-info">
                        <div class="header-profile-name"><?php echo $_SESSION['username']; ?></div>
                        <div class="header-profile-role">Administrator</div>
                    </div>
                    <button class="header-dropdown-btn">▼</button>
                    
                    <!-- Dropdown Menu -->
                    <div class="profile-dropdown" id="profileDropdown">
                        <a href="Profile.php" class="dropdown-item">
                            <span class="dropdown-icon">👤</span>
                            <span>My Profile</span>
                        </a>
                        <a href="EditProfile.php" class="dropdown-item">
                            <span class="dropdown-icon">⚙️</span>
                            <span>Settings</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="Logout.php" class="dropdown-item logout">
                            <span class="dropdown-icon">🚪</span>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <div class="admin-content">
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

    <script src="../assets/js/admin-sidebar.js?v=<?php echo time(); ?>"></script>
</body>
</html>
<?php 
} else {
    header("Location:../index-modern.php");
    exit();
}
?>
