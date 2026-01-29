<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Delete Student</title>
</head>
<body>
<?php

$Id=$_GET['Stud_ID'];
	// Establish Connection with MYSQL
	$con = new mysqli("localhost","root","","oes");
	// Specify the query to Delete Record
	$stmt = $con->prepare("delete from student where Id=?");
	$stmt->bind_param("s", $Id);
	// execute query
	$stmt->execute();
	$stmt->close();
	// Close The Connection
	$con->close();
	
	echo '<script type="text/javascript">alert("Student Deleted Successfully");window.location=\'Student.php\';</script>';

?>
</body>
</html>