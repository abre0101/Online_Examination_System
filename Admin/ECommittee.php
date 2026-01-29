<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
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
    <title>Exam Committee Management - Admin Dashboard</title>
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
                <h1>👥 Exam Committee Management</h1>
                <p>Create and manage exam committee members in the system</p>
            </div>

            <div class="tabs-container">
                <div class="tabs-header">
                    <button class="tab-btn active" onclick="switchTab(0)">➕ Create New Member</button>
                    <button class="tab-btn" onclick="switchTab(1)">📋 Display Members</button>
                </div>

                <!-- Tab 1: Create New Member -->
                <div class="tab-content active">
                    <form method="post" action="InsertECommittee.php">
                        <div class="form-group">
                            <label for="excID">Exam Committee ID:</label>
                            <input type="text" name="excID" id="excID" required placeholder="Enter Committee ID">
                        </div>
                        
                        <div class="form-group">
                            <label for="excName">Exam Committee Name:</label>
                            <input type="text" name="excName" id="excName" required placeholder="Enter Committee Name">
                        </div>
                        
                        <div class="form-group">
                            <label for="excEmail">Email:</label>
                            <input type="email" name="excEmail" id="excEmail" required placeholder="Enter Email Address">
                        </div>
                        
                        <div class="form-group">
                            <label for="excUName">Username:</label>
                            <input type="text" name="excUName" id="excUName" required placeholder="Enter Username">
                        </div>
                        
                        <div class="form-group">
                            <label for="excPassword">Password:</label>
                            <input type="password" name="excPassword" id="excPassword" required placeholder="Enter Password">
                        </div>
                        
                        <div class="form-group">
                            <label for="cmbDep">Department:</label>
                            <select name="cmbDep" id="cmbDep" required>
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

                <!-- Tab 2: Display Members -->
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
                            $con2 = new mysqli("localhost","root","","oes");
                            $sql = "select * from exam_committee";
                            $result = $con2->query($sql);

                            while($row = $result->fetch_array())
                            {
                                $Id=$row['EC_ID'];
                                $Name=$row['EC_Name'];
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
                                    <a href="EditECommittee.php?Id=<?php echo $Id;?>" class="action-link edit">✏️ Edit</a>
                                    <a href="DeleteECommittee.php?Id=<?php echo $Id;?>" class="action-link delete" onclick="return confirm('Are you sure you want to delete this member?')">🗑️ Delete</a>
                                </td>
                            </tr>
                            <?php
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
