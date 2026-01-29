
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
$Id=$_GET['ID'];

$Name=$_POST['txtID'];

// Establish Connection with MYSQL
$con = new mysqli("localhost","root");
// Select Database
$con->select_db("oes");
// Specify the query to Update Record
$sql = "UPDATE department set dept_name='".$Name."'  where deptno='".$Id."'";

// Execute query
$con->query($sql);
// Close The Connection
$con->close();
echo '<script type="text/javascript">alert("Deprtment Updated Succesfully");window.location=\'Department.php\';</script>';
?>
</body>
</html>
