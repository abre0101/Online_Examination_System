<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update Student</title>
</head>

<body>
<?php
$Id = $_GET['Id'];
$Year=$_POST['cmbYear'];
$Status=$_POST['cmbStatus'];
$Sem=$_POST['cmbSem'];
$Department=$_POST['cmbDep'];

// Establish Connection with MYSQL
$con = new mysqli("localhost","root","","oes");
// Specify the query to Update Record
$stmt = $con->prepare("Update student set dept_name=?, Status=?, year=?, semister=? where Id=?");
$stmt->bind_param("sssis", $Department, $Status, $Year, $Sem, $Id);
// execute query
$stmt->execute();
$stmt->close();
// Close The Connection
$con->close();
echo '<script type="text/javascript">alert("Student Updated Successfully");window.location=\'Student.php\';</script>';
?>
</body>
</html>