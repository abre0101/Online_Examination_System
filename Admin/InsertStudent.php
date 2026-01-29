<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Create Student</title>
</head>

<body>
<?php

	$ID=$_POST['txtRoll'];
	$Name=$_POST['txtName'];
        $StudDept=$_POST['cmbDept'];
        $StudYear=$_POST['cmbYear'];
        $StudSem=$_POST['cmbSem'];
        $UserName=$_POST['txtUserName'];
        $Password=$_POST['txtPassword'];
        $Sex=$_POST['gender'];
        $Status=$_POST['cmbStatus'];

	// Establish Connection with MYSQL
	$con = new mysqli("localhost","root","","oes");
	// Specify the query to Insert Record
	$stmt = $con->prepare("Insert into student(Id,Name,dept_name,year,semister,Sex,username,password,Status) values(?,?,?,?,?,?,?,?,?)");
	$stmt->bind_param("ssssissss", $ID, $Name, $StudDept, $StudYear, $StudSem, $Sex, $UserName, $Password, $Status);
	// execute query
	$stmt->execute();
	$stmt->close();
	// Close The Connection
	$con->close();
	echo '<script type="text/javascript">alert("New Student Inserted Successfully");window.location=\'Student.php\';</script>';

?>
</body>
</html>