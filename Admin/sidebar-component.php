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
        <!-- Main Section -->
        <div class="sidebar-section-label">Main</div>
        <a href="index-modern.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'index-modern.php') ? 'active' : ''; ?>">
            <span class="sidebar-nav-icon">📊</span>
            <span>Dashboard</span>
        </a>
        
        <!-- Academic Management -->
        <div class="sidebar-section-label">Academic Management</div>
        <a href="Faculty.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'Faculty.php') ? 'active' : ''; ?>">
            <span class="sidebar-nav-icon">🏛️</span>
            <span>College</span>
        </a>
        <a href="Department.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'Department.php') ? 'active' : ''; ?>">
            <span class="sidebar-nav-icon">🏢</span>
            <span>Department</span>
        </a>
        <a href="Course.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'Course.php') ? 'active' : ''; ?>">
            <span class="sidebar-nav-icon">📚</span>
            <span>Course</span>
        </a>
        
        <!-- User Management -->
        <div class="sidebar-section-label">User Management</div>
        <a href="ECommittee.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'ECommittee.php') ? 'active' : ''; ?>">
            <span class="sidebar-nav-icon">�‍</span>
            <span>Exam Committee</span>
        </a>
        <a href="Instructor.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'Instructor.php') ? 'active' : ''; ?>">
            <span class="sidebar-nav-icon">👨‍🏫</span>
            <span>Instructor</span>
        </a>
        <a href="Student-modern.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'Student-modern.php') ? 'active' : ''; ?>">
            <span class="sidebar-nav-icon">👨‍🎓</span>
            <span>Student</span>
        </a>
        
        <!-- System -->
        <div class="sidebar-section-label">System</div>
        <a href="SystemSettings.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'SystemSettings.php') ? 'active' : ''; ?>">
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