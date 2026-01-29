<?php
session_start();
if(isset($_SESSION['username'])){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Management - Admin Dashboard</title>
    <link href="../assets/css/modern-v2.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="../assets/css/admin-sidebar.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .tabs-container {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .tabs-header {
            display: flex;
            background: #f8f9fa;
            border-bottom: 3px solid #e0e0e0;
        }
        
        .tab-btn {
            flex: 1;
            padding: 1.25rem 2rem;
            background: transparent;
            border: none;
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            margin-bottom: -3px;
        }
        
        .tab-btn:hover {
            background: rgba(0, 51, 102, 0.05);
            color: var(--primary-color);
        }
        
        .tab-btn.active {
            background: white;
            color: var(--primary-color);
            border-bottom-color: var(--secondary-color);
            font-weight: 700;
        }
        
        .tab-content {
            display: none;
            padding: 2rem;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1rem;
        }
        
        .form-group input[type="text"] {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: var(--radius-md);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-group input[type="text"]:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 51, 102, 0.1);
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        .data-table thead {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }
        
        .data-table th {
            padding: 1rem;
            text-align: left;
            color: white;
            font-weight: 700;
            font-size: 1rem;
        }
        
        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
            font-size: 0.95rem;
        }
        
        .data-table tbody tr {
            transition: all 0.3s ease;
        }
        
        .data-table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .action-link {
            padding: 0.5rem 1rem;
            border-radius: var(--radius-md);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            margin-right: 0.5rem;
        }
        
        .action-link.edit {
            background: #28a745;
            color: white;
        }
        
        .action-link.edit:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }
        
        .action-link.delete {
            background: #dc3545;
            color: white;
        }
        
        .action-link.delete:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }
    </style>
</head>
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
            <a href="index-modern.php" class="sidebar-nav-item">
                <span class="sidebar-nav-icon">📊</span>
                <span>Dashboard</span>
            </a>
            <a href="Faculty.php" class="sidebar-nav-item active">
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
                    <span class="breadcrumb-item active">College</span>
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
            <!-- Page Title -->
            <div class="page-header">
                <h1>🏛️ College Management</h1>
                <p>Create and manage colleges in the system</p>
            </div>

            <!-- Tabs Container -->
            <div class="tabs-container">
                <div class="tabs-header">
                    <button class="tab-btn active" onclick="switchTab(0)">➕ Create New College</button>
                    <button class="tab-btn" onclick="switchTab(1)">📋 Display Colleges</button>
                </div>

                <!-- Tab 1: Create New College -->
                <div class="tab-content active">
                    <form method="post" action="InsertFaculty.php">
                        <div class="form-group">
                            <label for="txtID">College ID:</label>
                            <input type="text" name="txtID" id="txtID" required placeholder="Enter College ID">
                        </div>
                        
                        <div class="form-group">
                            <label for="txtFaculty">College Name:</label>
                            <input type="text" name="txtFaculty" id="txtFaculty" required placeholder="Enter College Name">
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                ✓ Submit
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tab 2: Display Colleges -->
                <div class="tab-content">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>College Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $con = new mysqli("localhost","root","","oes");
                            $sql = "select * from faculty";
                            $result = $con->query($sql);

                            while($row = $result->fetch_array())
                            {
                                $Id=$row['faculty_id'];
                                $FacultyName=$row['faculty_name'];
                            ?>
                            <tr>
                                <td><strong><?php echo $Id;?></strong></td>
                                <td><?php echo $FacultyName;?></td>
                                <td>
                                    <a href="EditFaculty.php?FacId=<?php echo $Id;?>" class="action-link edit">✏️ Edit</a>
                                    <a href="DeleteFaculty.php?FacId=<?php echo $Id;?>" class="action-link delete" onclick="return confirm('Are you sure you want to delete this college?')">🗑️ Delete</a>
                                </td>
                            </tr>
                            <?php
                            }
                            $con->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin-sidebar.js?v=<?php echo time(); ?>"></script>
</body>
</html>
<?php 
} else {
    header("Location:../index-modern.php");
    exit();
}
?>
