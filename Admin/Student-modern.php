<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index-modern.php");
    exit();
}

require_once('../Connections/OES.php');
$con->select_db($database_OES);
$query_Recordsetd = "SELECT * From department";
$Recordsetd = $con->query($query_Recordsetd);
$row_Recordsetd = $Recordsetd->fetch_assoc();
$totalRows_Recordsetd = $Recordsetd->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management - Debre Markos University Health Campus</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .tabs {
            display: flex;
            gap: 0.5rem;
            border-bottom: 2px solid var(--border-color);
            margin-bottom: 2rem;
        }
        .tab {
            padding: 1rem 2rem;
            background: transparent;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            font-weight: 600;
            color: var(--text-secondary);
            transition: all 0.3s ease;
        }
        .tab:hover {
            color: var(--primary-color);
        }
        .tab.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
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
                    <li><a href="index-modern.php">Dashboard</a></li>
                    <li><a href="Faculty.php">College</a></li>
                    <li><a href="Department.php">Department</a></li>
                    <li><a href="Course.php">Course</a></li>
                    <li><a href="ECommittee.php">Exam Committee</a></li>
                    <li><a href="Instructor.php">Instructor</a></li>
                    <li><a href="Student-modern.php" class="active">Student</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-wrapper">
                <h1>👨‍🎓 Student Management</h1>
                <p class="text-secondary">Create new students and manage existing student records</p>

                <!-- Tabs -->
                <div class="tabs">
                    <button class="tab active" onclick="switchTab('create')">➕ Create Student</button>
                    <button class="tab" onclick="switchTab('manage')">📋 Manage Students</button>
                </div>

                <!-- Create Student Tab -->
                <div id="create-tab" class="tab-content active">
                    <div class="form-wrapper">
                        <h2>Create New Student</h2>
                        <form method="post" action="InsertStudent.php">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="txtRoll">Student ID *</label>
                                    <input type="text" class="form-control" name="txtRoll" id="txtRoll" required>
                                </div>
                                <div class="form-group">
                                    <label for="txtName">Student Name *</label>
                                    <input type="text" class="form-control" name="txtName" id="txtName" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Gender *</label>
                                    <div style="display: flex; gap: 2rem; padding-top: 0.5rem;">
                                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                            <input type="radio" name="gender" value="male" required>
                                            <span>Male</span>
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                            <input type="radio" name="gender" value="female" required>
                                            <span>Female</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cmbDept">Department *</label>
                                    <select name="cmbDept" id="cmbDept" class="form-control" required>
                                        <option value="">-- Select Department --</option>
                                        <?php
                                        do {  
                                        ?>
                                        <option value="<?php echo $row_Recordsetd['dept_name']?>"><?php echo $row_Recordsetd['dept_name']?></option>
                                        <?php
                                        } while ($row_Recordsetd = $Recordsetd->fetch_assoc());
                                        $rows = $Recordsetd->num_rows;
                                        if($rows > 0) {
                                            $Recordsetd->data_seek(0);
                                            $row_Recordsetd = $Recordsetd->fetch_assoc();
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="cmbYear">Year *</label>
                                    <select name="cmbYear" id="cmbYear" class="form-control" required>
                                        <option value="">-- Select Year --</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="cmbSem">Semester *</label>
                                    <select name="cmbSem" id="cmbSem" class="form-control" required>
                                        <option value="">-- Select Semester --</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="txtUserName">Username *</label>
                                    <input type="text" class="form-control" name="txtUserName" id="txtUserName" required>
                                </div>
                                <div class="form-group">
                                    <label for="txtPassword">Password *</label>
                                    <input type="password" class="form-control" name="txtPassword" id="txtPassword" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="cmbStatus">Status *</label>
                                    <select name="cmbStatus" id="cmbStatus" class="form-control" required>
                                        <option value="Active">Active</option>
                                        <option value="InActive">Inactive</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <!-- Empty for alignment -->
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="reset" class="btn btn-secondary">Reset</button>
                                <button type="submit" class="btn btn-primary">Create Student</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Manage Students Tab -->
                <div id="manage-tab" class="tab-content">
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>Department</th>
                                    <th>Username</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                error_reporting(0);
                                $con2 = new mysqli("localhost","root","","oes");
                                $sql = "select * from student ORDER BY Id DESC";
                                $result = $con2->query($sql);
                                
                                if($result->num_rows > 0) {
                                    while($row = $result->fetch_array()) {
                                        $Id = $row['Id'];
                                        $Name = $row['Name'];
                                        $Dept = $row['dept_name'];
                                        $UserName = $row['username'];
                                        $Status = $row['Status'];
                                        $Sex = $row['Sex'];
                                ?>
                                <tr>
                                    <td><strong><?php echo $Id; ?></strong></td>
                                    <td><?php echo $Name; ?></td>
                                    <td><?php echo $Sex; ?></td>
                                    <td><?php echo $Dept; ?></td>
                                    <td><?php echo $UserName; ?></td>
                                    <td>
                                        <?php if($Status == 'Active'): ?>
                                        <span class="status-badge status-active">Active</span>
                                        <?php else: ?>
                                        <span class="status-badge status-inactive">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="EditStudent.php?Stud_ID=<?php echo $Id; ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="DeleteStudent.php?Stud_ID=<?php echo $Id; ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-secondary);">No students found</td></tr>';
                                }
                                $con2->close();
                                ?>
                            </tbody>
                        </table>
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

    <script>
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName + '-tab').classList.add('active');
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
