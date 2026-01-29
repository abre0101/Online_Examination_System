<?php
if (!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Name'])){
    header("Location:../auth/institute-login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Committee Dashboard - Debre Markos University</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-sidebar.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="admin-layout">
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <img src="../images/logo1.png" alt="Logo" class="sidebar-logo" onerror="this.style.display='none'">
            <h2 class="sidebar-title">Exam Committee</h2>
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
            <a href="CheckQuestions.php" class="sidebar-nav-item">
                <span class="sidebar-nav-icon">🔍</span>
                <span>Check Questions</span>
            </a>
            <a href="PendingApprovals.php" class="sidebar-nav-item">
                <span class="sidebar-nav-icon">⏳</span>
                <span>Pending Approvals</span>
            </a>
            <a href="ApprovedExams.php" class="sidebar-nav-item">
                <span class="sidebar-nav-icon">✅</span>
                <span>Approved Exams</span>
            </a>
            <a href="DepartmentExams.php" class="sidebar-nav-item">
                <span class="sidebar-nav-icon">🏛️</span>
                <span>Department Exams</span>
            </a>
            <a href="ChangePassword.php" class="sidebar-nav-item">
                <span class="sidebar-nav-icon">🔒</span>
                <span>Change Password</span>
            </a>
            <a href="../Help-modern.php" class="sidebar-nav-item">
                <span class="sidebar-nav-icon">❓</span>
                <span>Help</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    <?php echo strtoupper(substr($_SESSION['Name'], 0, 1)); ?>
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name"><?php echo $_SESSION['Name']; ?></div>
                    <div class="sidebar-user-role">Exam Committee</div>
                </div>
            </div>
            <a href="Logout.php" class="btn btn-danger btn-block">
                🚪 Logout
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="admin-main-content">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-left">
                <button class="mobile-menu-btn" onclick="toggleSidebar()">☰</button>
                <div class="header-breadcrumb">
                    <span class="breadcrumb-item">Exam Committee</span>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-item active">Dashboard</span>
                </div>
            </div>
            
            <div class="header-center">
                <div class="header-search">
                    <span class="search-icon">🔍</span>
                    <input type="text" placeholder="Search questions, exams..." class="search-input">
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
                        <span class="notification-badge">2</span>
                    </button>
                </div>
                
                <div class="header-profile" onclick="toggleProfileDropdown(event)">
                    <div class="header-profile-avatar">
                        <?php echo strtoupper(substr($_SESSION['Name'], 0, 1)); ?>
                    </div>
                    <div class="header-profile-info">
                        <div class="header-profile-name"><?php echo $_SESSION['Name']; ?></div>
                        <div class="header-profile-role">Exam Committee</div>
                    </div>
                    <button class="header-dropdown-btn">▼</button>
                    
                    <div class="profile-dropdown">
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

        <!-- Content -->
        <div class="admin-content">
            <!-- Welcome Banner -->
            <div class="welcome-banner">
                <div class="welcome-content">
                    <h1>👋 Welcome, <?php echo $_SESSION['Name']; ?>!</h1>
                    <p>Exam Committee Dashboard - <?php echo $_SESSION['Dept']; ?> Department</p>
                    <p style="font-size: 0.95rem; margin-top: 0.5rem; opacity: 0.9;">
                        Last login: <?php echo date('D, M d, Y - h:i A'); ?>
                    </p>
                </div>
                <div class="welcome-image">
                    <img src="images/EC.jpg" alt="Exam Committee" onerror="this.style.display='none'">
                </div>
            </div>

            <!-- Notifications -->
            <?php
            $con = new mysqli("localhost","root","","oes");
            // Using question_page table (the actual table name in database)
            $pending_count = 0; // Will be implemented after migration
            ?>
            <?php if($pending_count > 0): ?>
            <div style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: white; padding: 1.5rem; border-radius: var(--radius-lg); margin-bottom: 2rem; box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);">
                <h3 style="margin: 0 0 0.5rem 0; font-size: 1.2rem;">⚠️ Urgent: <?php echo $pending_count; ?> Question(s) Awaiting Your Review</h3>
                <p style="margin: 0; opacity: 0.95;">Please review and approve pending questions to keep exams on schedule.</p>
                <a href="PendingApprovals.php" class="btn btn-light" style="margin-top: 1rem; background: white; color: #ff9800;">Review Now →</a>
            </div>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card stat-warning">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-details">
                        <div class="stat-value">
                            <?php
                            // Count from question_page table
                            $result = $con->query("SELECT COUNT(*) as count FROM question_page WHERE exam_id IN (SELECT exam_id FROM exam_category WHERE exam_name LIKE '%".$_SESSION['Dept']."%')");
                            $row = $result ? $result->fetch_assoc() : ['count' => 0];
                            echo $row['count'] ?? 0;
                            ?>
                        </div>
                        <div class="stat-label">Pending Approvals</div>
                    </div>
                </div>

                <div class="stat-card stat-success">
                    <div class="stat-icon">✅</div>
                    <div class="stat-details">
                        <div class="stat-value">
                            <?php
                            // Approved this month - placeholder
                            echo "0";
                            ?>
                        </div>
                        <div class="stat-label">Approved This Month</div>
                    </div>
                </div>

                <div class="stat-card stat-primary">
                    <div class="stat-icon">📝</div>
                    <div class="stat-details">
                        <div class="stat-value">
                            <?php
                            // Reviewed today - placeholder
                            echo "0";
                            ?>
                        </div>
                        <div class="stat-label">Reviewed Today</div>
                    </div>
                </div>

                <div class="stat-card stat-info">
                    <div class="stat-icon">📊</div>
                    <div class="stat-details">
                        <div class="stat-value">
                            <?php
                            // Total questions from question_page
                            $total_q = $con->query("SELECT COUNT(*) as count FROM question_page")->fetch_assoc()['count'];
                            echo $total_q ?? 0;
                            ?>
                        </div>
                        <div class="stat-label">Total Questions</div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity & Upcoming Deadlines -->
            <div class="grid grid-2 mt-4">
                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">📋 Recent Activity</h3>
                    </div>
                    <div class="activity-list">
                        <div style="padding: 2rem; text-align: center; color: var(--text-secondary);">
                            <p>Recent activity will appear here</p>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Deadlines -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">⏰ Upcoming Exam Deadlines</h3>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div style="padding: 2rem; text-align: center; color: var(--text-secondary);">
                            <p>Upcoming deadlines will appear here</p>
                        </div>
                    </div>
                </div>
            </div>

            <?php $con->close(); ?>

            <!-- Quick Actions -->
            <div class="content-wrapper mt-4">
                <h2>⚡ Quick Actions</h2>
                <div class="quick-actions-grid">
                    <a href="CheckQuestions.php" class="action-card">
                        <div class="action-icon">🔍</div>
                        <div class="action-title">Check Questions</div>
                        <div class="action-desc">Review and approve questions</div>
                    </a>
                    <a href="PendingApprovals.php" class="action-card">
                        <div class="action-icon">⏳</div>
                        <div class="action-title">Pending Approvals</div>
                        <div class="action-desc">Questions awaiting review</div>
                    </a>
                    <a href="ApprovedExams.php" class="action-card">
                        <div class="action-icon">✅</div>
                        <div class="action-title">Approved Exams</div>
                        <div class="action-desc">View approved examinations</div>
                    </a>
                    <a href="DepartmentExams.php" class="action-card">
                        <div class="action-icon">🏛️</div>
                        <div class="action-title">Department Exams</div>
                        <div class="action-desc">Filter by department</div>
                    </a>
                    <a href="ChangePassword.php" class="action-card">
                        <div class="action-icon">🔒</div>
                        <div class="action-title">Change Password</div>
                        <div class="action-desc">Update your password</div>
                    </a>
                    <a href="../Help-modern.php" class="action-card">
                        <div class="action-icon">❓</div>
                        <div class="action-title">Help</div>
                        <div class="action-desc">Get assistance</div>
                    </a>
                </div>
            </div>

            <!-- Role Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">📌 Your Role as Exam Committee</h3>
                </div>
                <div style="padding: 2rem;">
                    <p style="font-size: 1.1rem; line-height: 1.8; color: var(--text-primary);">
                        <strong>Welcome to the Exam Committee Dashboard!</strong><br><br>
                        As an Exam Committee member for the <strong style="color: var(--secondary-color);"><?php echo $_SESSION['Dept']; ?> Department</strong>, 
                        you are responsible for reviewing and approving examination questions prepared by instructors to ensure quality and academic standards.
                    </p>
                    <div style="margin-top: 1.5rem; padding: 1.5rem; background: var(--bg-light); border-radius: var(--radius-md); border-left: 4px solid var(--primary-color);">
                        <h4 style="color: var(--primary-color); margin-bottom: 1rem;">🎯 Your Main Responsibilities:</h4>
                        <ul style="list-style: none; padding: 0;">
                            <li style="padding: 0.5rem 0; font-weight: 600;">✓ Review questions for content accuracy and clarity</li>
                            <li style="padding: 0.5rem 0; font-weight: 600;">✓ Verify alignment with course objectives and syllabus</li>
                            <li style="padding: 0.5rem 0; font-weight: 600;">✓ Check for appropriate difficulty level</li>
                            <li style="padding: 0.5rem 0; font-weight: 600;">✓ Approve or request revisions on exam questions</li>
                            <li style="padding: 0.5rem 0; font-weight: 600;">✓ Ensure compliance with academic standards</li>
                        </ul>
                    </div>
                    <div style="margin-top: 1.5rem; padding: 1.5rem; background: rgba(220, 53, 69, 0.1); border-radius: var(--radius-md); border-left: 4px solid #dc3545;">
                        <h4 style="color: #dc3545; margin-bottom: 1rem;">🚫 Note: What You Cannot Do</h4>
                        <ul style="list-style: none; padding: 0; color: var(--text-secondary);">
                            <li style="padding: 0.3rem 0;">❌ Create or edit questions (only instructors can)</li>
                            <li style="padding: 0.3rem 0;">❌ View student exam results</li>
                            <li style="padding: 0.3rem 0;">❌ Manage user accounts</li>
                            <li style="padding: 0.3rem 0;">❌ Take exams</li>
                        </ul>
                    </div>
                    <div style="margin-top: 1.5rem; padding: 1.5rem; background: rgba(40, 167, 69, 0.1); border-radius: var(--radius-md); border-left: 4px solid var(--success-color);">
                        <h4 style="color: var(--success-color); margin-bottom: 0.5rem;">💡 Your Impact</h4>
                        <p style="margin: 0; color: var(--text-primary); font-weight: 500;">
                            You act as the <strong>quality control gatekeeper</strong>, ensuring exams are valid, fair, and academically sound before students take them. 
                            Your approval gives the final green light for exams to go live.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js"></script>
</body>
</html>
