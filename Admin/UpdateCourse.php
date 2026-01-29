<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update Course</title>
</head>

<body>
<?php
$Id=$_GET['txtDeptID'];

$Name=$_POST['txtCourseName'];

// Establish Connection with MYSQL
$con = new mysqli("localhost","root");
// Select Database
$con->select_db("oes");
$sql = "UPDATE Course set Course_name='".$Name."'  where course_id='".$Id."'";
// Execute query
$con->query($sql);
// Close The Connection
$con->close();
echo '<script type="text/javascript">alert("Course Updated Succesfully");window.location=\'Course.php\';</script>';
?>
</body>
</html>
