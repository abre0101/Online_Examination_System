<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Delete Question</title>
</head>

<body>
<?php

	$Id=$_GET['QueId'];
	// Establish Connection with MYSQL
	$con = new mysqli("localhost","root");
	// Select Database
	$con->select_db("oes");
	// Specify the query to Insert Record
	$sql = "delete from question_page where question_id='".$Id."'";
	// execute query
	$con->query ($sql);
	// Close The Connection
	$con->close ();
	echo '<script type="text/javascript">alert("Question Deleted Succesfully");window.location=\'question.php\';</script>';

?>
</body>
</html>
