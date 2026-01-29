<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Insert Faculty</title>
</head>

<body>
<?php

	$ID=$_POST['txtID'];
	$Name=$_POST['txtFaculty'];
	

	
	// Establish Connection with MYSQL
	 $con = new mysqli("localhost","root","","oes");
	//$con = mysql_connect ("localhost","root");
	// Select Database
	//mysql_select_db("oes", $con);
	// Specify the query to Insert Record
	$sql = "insert into faculty 	(faculty_id,faculty_name) 	values('".$ID."','".$Name."' )";
	// execute query
	 $con->query($sql);

	//mysql_query ($sql,$con);
	// Close The Connection
	$con->close();
	//mysql_close ($con);
	echo '<script type="text/javascript">alert("New Faculty Inserted Succesfully");window.location=\'Faculty.php\';</script>';

?>
</body>
</html>
