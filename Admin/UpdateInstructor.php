
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
$Id=$_GET['ID'];
$Status=$_POST['cmbStatus'];
$Department=$_POST['cmbDept'];
$Course=$_POST['cmbCourse'];


// Establish Connection with MYSQL
    $con = new mysqli("localhost","root");
	// Select Database
	$con->select_db("oes");
	// Specify the query to Insert Record
    //$sql = "Update instructor set dept_name='".$Department."', Status='".$Status."' where Inst_ID=".$Id."";
    $sql = "UPDATE instructor set course_name='".$Course."',dept_name='".$Department."',Status='".$Status."'  where Inst_ID='".$Id."'";

	// execute query
	$con->query ($sql);
	// Close The Connection
	$con->close ();

echo '<script type="text/javascript">alert("Exam Committee Updated Succesfully");window.location=\'Instructor.php\';</script>';
?>
</body>
</html>
