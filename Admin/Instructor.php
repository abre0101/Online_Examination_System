<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$con = mysqli_connect("localhost","root","","oes");
$query_Recordsetd = "SELECT * From department";
$Recordsetd = mysqli_query($con,$query_Recordsetd) or die(mysqli_error($con));
$row_Recordsetd = mysqli_fetch_assoc($Recordsetd);
$totalRows_Recordsetd = mysqli_num_rows($Recordsetd);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Management - Admin Dashboard</title>
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
        .form-group input[type="email"],
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
    <?php include 'sidebar-component.php'; ?>

    <div class="admin-main-content">
        <?php include 'header-component.php'; ?>

        <div class="admin-content">
            <div class="page-header">
                <h1>👨‍🏫 Instructor Management</h1>
                <p>Create and manage instructors in the system</p>
            </div>

            <div class="tabs-container">
                <div class="tabs-header">
                    <button class="tab-btn active" onclick="switchTab(0)">➕ Create New Instructor</button>
                    <button class="tab-btn" onclick="switchTab(1)">📋 Display Instructors</button>
                </div>

                <!-- Tab 1: Create New Instructor -->
                <div class="tab-content active">
                    <form method="post" action="InsertInstructor.php">
                        <div class="form-group">
                            <label for="instID">Instructor ID:</label>
                            <input type="text" name="instID" id="instID" required placeholder="Enter Instructor ID">
                        </div>
                        
                        <div class="form-group">
                            <label for="instName">Instructor Name:</label>
                            <input type="text" name="instName" id="instName" required placeholder="Enter Instructor Name">
                        </div>
                        
                        <div class="form-group">
                            <label for="instEmail">Email:</label>
                            <input type="email" name="instEmail" id="instEmail" required placeholder="Enter Email Address">
                        </div>
                        
                        <div class="form-group">
                            <label for="instUName">Username:</label>
                            <input type="text" name="instUName" id="instUName" required placeholder="Enter Username">
                        </div>
                        
                        <div class="form-group">
                            <label for="instPassword">Password:</label>
                            <input type="password" name="instPassword" id="instPassword" required placeholder="Enter Password">
                        </div>
                        
                        <div class="form-group">
                            <label for="cmbDept">Department:</label>
                            <select name="cmbDept" id="cmbDept" required>
                                <option value="">-- Select Department --</option>
                                <?php
                                do {  
                                ?>
                                <option value="<?php echo $row_Recordsetd['deptno']?>"><?php echo $row_Recordsetd['dept_name']?></option>
                                <?php
                                } while ($row_Recordsetd = mysqli_fetch_assoc($Recordsetd));
                                $rows = mysqli_num_rows($Recordsetd);
                                if($rows > 0) {
                                    mysqli_data_seek($Recordsetd, 0);
                                    $row_Recordsetd = mysqli_fetch_assoc($Recordsetd);
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="cmbStatus">Status:</label>
                            <select name="cmbStatus" id="cmbStatus" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                ✓ Submit
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tab 2: Display Instructors -->
                <div class="tab-content">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Username</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $con2 = mysqli_connect("localhost","root","","oes");
                            $sql = "select * from instructor";
                            $result = mysqli_query($con2,$sql);

                            while(@$row = mysqli_fetch_array($result))
                            {
                                $Id=$row['Inst_ID'];
                                $Name=$row['Inst_Name'];
                                $Email=$row['email'];
                                $UserName=$row['username'];
                                $Department=$row['dept_name'];
                                $Status=$row['Status'];
                            ?>
                            <tr>
                                <td><strong><?php echo $Id;?></strong></td>
                                <td><?php echo $Name;?></td>
                                <td><?php echo $Email;?></td>
                                <td><?php echo $Department;?></td>
                                <td><?php echo $UserName;?></td>
                                <td><?php echo $Status;?></td>
                                <td>
                                    <a href="EditInstructor.php?Id=<?php echo $Id;?>" class="action-link edit">✏️ Edit</a>
                                    <a href="DeleteInstructor.php?Id=<?php echo $Id;?>" class="action-link delete" onclick="return confirm('Are you sure you want to delete this instructor?')">🗑️ Delete</a>
                                </td>
                            </tr>
                            <?php
                            }
                            mysqli_close($con2);
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
mysqli_close($con);
?>
