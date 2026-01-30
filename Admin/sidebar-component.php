<!-- Enhanced Modern Admin Sidebar -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand-wrapper">
            <img src="../images/logo1.png" alt="Logo" class="brand-logo" onerror="this.style.display='none'">
            <div class="brand-text">
                <h2 class="brand-title">OES Admin</h2>
                <span class="brand-subtitle">Debre Markos University</span>
            </div>
        </div>
        <button class="sidebar-toggle-btn" onclick="toggleSidebarMinimize()" title="Toggle Sidebar" id="sidebarToggleBtn">
            <span id="toggleIcon">◀</span>
        </button>
    </div>

    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <a href="index-modern.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'index-modern.php') ? 'active' : ''; ?>" data-tooltip="Dashboard">
            <span class="sidebar-nav-icon">📊</span>
            <span>Dashboard</span>
        </a>

        <!-- Academic Section -->
        <div class="sidebar-section-label">Academic</div>
        <a href="AcademicCalendar.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'AcademicCalendar.php') ? 'active' : ''; ?>" data-tooltip="Academic Calendar">
            <span class="sidebar-nav-icon">📅</span>
            <span>Academic Calendar</span>
        </a>
        <a href="Faculty.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'Faculty.php') ? 'active' : ''; ?>" data-tooltip="Colleges">
            <span class="sidebar-nav-icon">🏛️</span>
            <span>Colleges</span>
        </a>
        <a href="Department.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'Department.php') ? 'active' : ''; ?>" data-tooltip="Departments">
            <span class="sidebar-nav-icon">🏢</span>
            <span>Departments</span>
        </a>
        <a href="Course.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'Course.php') ? 'active' : ''; ?>" data-tooltip="Courses">
            <span class="sidebar-nav-icon">📚</span>
            <span>Courses</span>
        </a>

        <!-- Users Section -->
        <div class="sidebar-section-label">Users</div>
        <a href="Student-modern.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'Student-modern.php') ? 'active' : ''; ?>" data-tooltip="Students">
            <span class="sidebar-nav-icon">👨‍🎓</span>
            <span>Students</span>
        </a>
        <a href="Instructor.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'Instructor.php') ? 'active' : ''; ?>" data-tooltip="Instructors">
            <span class="sidebar-nav-icon">👨‍🏫</span>
            <span>Instructors</span>
        </a>
        <a href="ECommittee.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'ECommittee.php') ? 'active' : ''; ?>" data-tooltip="Exam Committee">
            <span class="sidebar-nav-icon">👥</span>
            <span>Exam Committee</span>
        </a>
        <a href="ResetPassword.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'ResetPassword.php') ? 'active' : ''; ?>" data-tooltip="Reset Password">
            <span class="sidebar-nav-icon">🔐</span>
            <span>Reset Password</span>
        </a>
        <a href="BulkImport.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'BulkImport.php') ? 'active' : ''; ?>" data-tooltip="Bulk Import">
            <span class="sidebar-nav-icon">📥</span>
            <span>Bulk Import</span>
        </a>

        <!-- System Section -->
        <div class="sidebar-section-label">System</div>
        <a href="SystemMonitoring.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'SystemMonitoring.php') ? 'active' : ''; ?>" data-tooltip="System Monitoring">
            <span class="sidebar-nav-icon">📊</span>
            <span>System Monitoring</span>
        </a>
        <a href="Reports.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'Reports.php') ? 'active' : ''; ?>" data-tooltip="Reports">
            <span class="sidebar-nav-icon">📈</span>
            <span>Reports</span>
        </a>
        <a href="DataExport.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'DataExport.php') ? 'active' : ''; ?>" data-tooltip="Export Data">
            <span class="sidebar-nav-icon">📥</span>
            <span>Export Data</span>
        </a>
        <a href="DatabaseBackup.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'DatabaseBackup.php') ? 'active' : ''; ?>" data-tooltip="Backup">
            <span class="sidebar-nav-icon">💾</span>
            <span>Backup</span>
        </a>
        <a href="SecurityLogs.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'SecurityLogs.php') ? 'active' : ''; ?>" data-tooltip="Security">
            <span class="sidebar-nav-icon">🔒</span>
            <span>Security</span>
        </a>
        <a href="GradingSettings.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'GradingSettings.php') ? 'active' : ''; ?>" data-tooltip="Grading">
            <span class="sidebar-nav-icon">📝</span>
            <span>Grading</span>
        </a>
        <a href="SystemSettings.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'SystemSettings.php') ? 'active' : ''; ?>" data-tooltip="Settings">
            <span class="sidebar-nav-icon">⚙️</span>
            <span>Settings</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name"><?php echo $_SESSION['username']; ?></div>
                <div class="sidebar-user-role">Administrator</div>
            </div>
        </div>
        <a href="Logout.php" class="btn btn-danger btn-block">
            <span class="sidebar-nav-icon">🚪</span>
            <span>Logout</span>
        </a>
    </div>
</aside>
