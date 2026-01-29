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
        <!-- Main Section -->
        <div class="sidebar-section-label">Main</div>
        <a href="index-modern.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'index-modern.php') ? 'active' : ''; ?>">
            <span class="sidebar-nav-icon">📊</span>
            <span>Dashboard</span>
        </a>
        
        <!-- Review & Approval -->
        <div class="sidebar-section-label">Review & Approval</div>
        <a href="CheckQuestions.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'CheckQuestions.php' || basename($_SERVER['PHP_SELF']) == 'ViewQuestion.php') ? 'active' : ''; ?>">
            <span class="sidebar-nav-icon">🔍</span>
            <span>Check Questions</span>
        </a>
        <a href="PendingApprovals.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'PendingApprovals.php') ? 'active' : ''; ?>">
            <span class="sidebar-nav-icon">⏳</span>
            <span>Pending Approvals</span>
            <?php
            // Get pending count
            $con = new mysqli("localhost","root","","oes");
            $pendingCount = $con->query("SELECT COUNT(*) as count FROM question_page WHERE approval_status = 'pending'")->fetch_assoc()['count'];
            $con->close();
            if($pendingCount > 0):
            ?>
            <span class="sidebar-badge"><?php echo $pendingCount; ?></span>
            <?php endif; ?>
        </a>
        
        <!-- Exam Management -->
        <div class="sidebar-section-label">Exam Management</div>
        <a href="ApprovedExams.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'ApprovedExams.php') ? 'active' : ''; ?>">
            <span class="sidebar-nav-icon">✅</span>
            <span>Approved Exams</span>
        </a>
        <a href="DepartmentExams.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'DepartmentExams.php') ? 'active' : ''; ?>">
            <span class="sidebar-nav-icon">🏛️</span>
            <span>Department Exams</span>
        </a>
        
        <!-- Settings & Support -->
        <div class="sidebar-section-label">Settings & Support</div>
        <a href="ChangePassword.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'ChangePassword.php') ? 'active' : ''; ?>">
            <span class="sidebar-nav-icon">🔒</span>
            <span>Change Password</span>
        </a>
        <a href="Help.php" class="sidebar-nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'Help.php') ? 'active' : ''; ?>">
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
