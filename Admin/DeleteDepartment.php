<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Delete Faculty</title>
</head>

<body>
<?php
//require_once '../Connetions/oes.php';
	$Id=$_GET['ID'];
	// Establish Connection with MYSQL
    $con = new mysqli("localhost","root");
    // Select Database
    $con->select_db("oes");
	// Specify the query to Delete Record
	$sql = "delete from department where department.deptno='".$Id."'";
	// execute query
	$con->query ($sql);
	// Close The Connection
	$con->close();
	echo '<script type="text/javascript">alert("Department Deleted Succesfully");window.location=\'Department.php\';</script>';

?>
</body>
</html>
