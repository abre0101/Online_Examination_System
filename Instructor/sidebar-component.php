<!-- Enhanced Modern Instructor Sidebar -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand-wrapper">
            <img src="../images/logo1.png" alt="Logo" class="brand-logo" onerror="this.style.display='none'">
            <div class="brand-text">
                <h2 class="brand-title">Instructor Panel</h2>
                <span class="brand-subtitle">Debre Markos University</span>
            </div>
        </div>
        <button class="sidebar-toggle-btn" onclick="toggleSidebarMinimize()" title="Toggle Sidebar" id="sidebarToggleBtn">
            <span id="toggleIcon">◀</span>
        </button>
    </div>

    <nav class="sidebar-nav">
        <!-- Main Section -->
        <div class="sidebar-section-label">Main</div>
        <a href="index-modern.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'index-modern.php') ? 'active' : ''; ?>" data-tooltip="Dashboard">
            <span class="sidebar-nav-icon">📊</span>
            <span>Dashboard</span>
        </a>
        <a href="MyCourses.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'MyCourses.php') ? 'active' : ''; ?>" data-tooltip="My Courses">
            <span class="sidebar-nav-icon">👨‍🏫</span>
            <span>My Courses</span>
        </a>
        
        <!-- Exam Management -->
        <div class="sidebar-section-label">Exam Management</div>
        <a href="ManageTopics.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'ManageTopics.php') ? 'active' : ''; ?>" data-tooltip="Topics">
            <span class="sidebar-nav-icon">📚</span>
            <span>Topics & Chapters</span>
        </a>
        <a href="ManageQuestions.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'ManageQuestions.php' || basename($_SERVER['PHP_SELF']) == 'AddQuestion.php' || basename($_SERVER['PHP_SELF']) == 'EditQuestion.php') ? 'active' : ''; ?>" data-tooltip="Questions">
            <span class="sidebar-nav-icon">📝</span>
            <span>Questions</span>
        </a>
        <a href="ManageExams.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'ManageExams.php' || basename($_SERVER['PHP_SELF']) == 'ViewExam.php' || basename($_SERVER['PHP_SELF']) == 'EditExam.php') ? 'active' : ''; ?>" data-tooltip="Exams">
            <span class="sidebar-nav-icon">📋</span>
            <span>Exams</span>
        </a>
        <a href="ManageSchedule.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'ManageSchedule.php') ? 'active' : ''; ?>" data-tooltip="Schedule">
            <span class="sidebar-nav-icon">📅</span>
            <span>Schedule</span>
        </a>
        
        <!-- Results & Reports -->
        <div class="sidebar-section-label">Results & Reports</div>
        <a href="SeeResults.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'SeeResults.php') ? 'active' : ''; ?>" data-tooltip="Student Results">
            <span class="sidebar-nav-icon">📈</span>
            <span>Student Results</span>
        </a>
        <a href="Reports.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'Reports.php') ? 'active' : ''; ?>" data-tooltip="Reports & Analytics">
            <span class="sidebar-nav-icon">📊</span>
            <span>Reports & Analytics</span>
        </a>
        <a href="Analytics.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'Analytics.php') ? 'active' : ''; ?>" data-tooltip="Advanced Analytics">
            <span class="sidebar-nav-icon">📉</span>
            <span>Advanced Analytics</span>
        </a>
        
        <!-- Settings & Support -->
        <div class="sidebar-section-label">Settings & Support</div>
        <a href="Notifications.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'Notifications.php') ? 'active' : ''; ?>" data-tooltip="Notifications">
            <span class="sidebar-nav-icon">🔔</span>
            <span>Notifications</span>
        </a>
        <a href="Settings.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'Settings.php') ? 'active' : ''; ?>" data-tooltip="Settings">
            <span class="sidebar-nav-icon">⚙️</span>
            <span>Settings</span>
        </a>
        <a href="Help.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'Help.php') ? 'active' : ''; ?>" data-tooltip="Help & Support">
            <span class="sidebar-nav-icon">❓</span>
            <span>Help & Support</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar"><?php echo strtoupper(substr($_SESSION['Name'], 0, 1)); ?></div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name"><?php echo $_SESSION['Name']; ?></div>
                <div class="sidebar-user-role">Instructor</div>
            </div>
        </div>
        <a href="Logout.php" class="btn btn-danger btn-block">
            <span class="sidebar-nav-icon">🚪</span>
            <span>Logout</span>
        </a>
    </div>
</aside>

<script>
function toggleSidebarMinimize() {
    const sidebar = document.getElementById('adminSidebar');
    const toggleIcon = document.getElementById('toggleIcon');
    
    sidebar.classList.toggle('minimized');
    
    // Change icon direction
    if (sidebar.classList.contains('minimized')) {
        toggleIcon.textContent = '▶';
        localStorage.setItem('sidebarMinimized', 'true');
    } else {
        toggleIcon.textContent = '◀';
        localStorage.setItem('sidebarMinimized', 'false');
    }
}

// Restore sidebar state from localStorage on page load
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('adminSidebar');
    const toggleIcon = document.getElementById('toggleIcon');
    const isMinimized = localStorage.getItem('sidebarMinimized') === 'true';
    
    if (isMinimized) {
        sidebar.classList.add('minimized');
        toggleIcon.textContent = '▶';
    }
});
</script>

