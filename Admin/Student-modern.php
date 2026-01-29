<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: ../index-modern.php");
    exit();
}

// Database connection
$con = new mysqli("localhost","root","","oes");
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

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
    <title>Student Management - Admin Dashboard</title>
    <link href="../assets/css/modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-modern-v2.css" rel="stylesheet">
    <link href="../assets/css/admin-sidebar.css" rel="stylesheet">
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
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
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
        .form-group input[type="text"],
        .form-group input[type="password"],
        .form-group select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: var(--radius-md);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 51, 102, 0.1);
        }
        .radio-group {
            display: flex;
            gap: 2rem;
            padding-top: 0.5rem;
        }
        .radio-group label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-weight: 500;
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
        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: var(--radius-md);
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="admin-layout">
    <?php include 'sidebar-component.php'; ?>

    <div class="admin-main-content">
        <?php include 'header-component.php'; ?>

        <div class="admin-content">
            <div class="page-header">
                <h1>👨‍🎓 Student Management</h1>
                <p>Create and manage students in the system</p>
            </div>

            <div class="tabs-container">
                <div class="tabs-header">
                    <button class="tab-btn active" onclick="switchTab(0)">➕ Create New Student</button>
                    <button class="tab-btn" onclick="switchTab(1)">📋 Display Students</button>
                </div>

                <!-- Tab 1: Create New Student -->
                <div class="tab-content active">
                    <form method="post" action="InsertStudent.php">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="txtRoll">Student ID:</label>
                                <input type="text" name="txtRoll" id="txtRoll" required placeholder="Enter Student ID">
                            </div>
                            
                            <div class="form-group">
                                <label for="txtName">Student Name:</label>
                                <input type="text" name="txtName" id="txtName" required placeholder="Enter Student Name">
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Gender:</label>
                                <div class="radio-group">
                                    <label>
                                        <input type="radio" name="gender" value="male" required>
                                        <span>Male</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="gender" value="female" required>
                                        <span>Female</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="cmbDept">Department:</label>
                                <select name="cmbDept" id="cmbDept" required>
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
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="cmbYear">Year:</label>
                                <select name="cmbYear" id="cmbYear" required>
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
                                <label for="cmbSem">Semester:</label>
                                <select name="cmbSem" id="cmbSem" required>
                                    <option value="">-- Select Semester --</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="txtUserName">Username:</label>
                                <input type="text" name="txtUserName" id="txtUserName" required placeholder="Enter Username">
                            </div>
                            
                            <div class="form-group">
                                <label for="txtPassword">Password:</label>
                                <input type="password" name="txtPassword" id="txtPassword" required placeholder="Enter Password">
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="cmbStatus">Status:</label>
                                <select name="cmbStatus" id="cmbStatus" required>
                                    <option value="Active">Active</option>
                                    <option value="InActive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                ✓ Submit
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tab 2: Display Students -->
                <div class="tab-content">
                    <table class="data-table">
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
                                    <a href="EditStudent.php?Stud_ID=<?php echo $Id; ?>" class="action-link edit">✏️ Edit</a>
                                    <a href="DeleteStudent.php?Stud_ID=<?php echo $Id; ?>" class="action-link delete" onclick="return confirm('Are you sure you want to delete this student?')">🗑️ Delete</a>
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

    <script src="../assets/js/admin-sidebar.js?v=<?php echo time(); ?>"></script>
</body>
</html>
<?php 
$con->close();
?>
