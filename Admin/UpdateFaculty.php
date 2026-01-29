<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update Faculty</title>
</head>

<body>
<?php
$Id = $_GET['FacId'];

$Name=$_POST['faculty_name'];

// Establish Connection with MYSQL
$con = new mysqli("localhost","root","","oes");

// Specify the query to Update Record
$sql = "UPDATE faculty set faculty_name='".$Name."'  where faculty_id='".$Id."'";
// Execute query
 $con->query($sql);
// Close The Connection
$con->close();
echo '<script type="text/javascript">alert("Faculty Updated Succesfully");window.location=\'Faculty.php\';</script>';
?>
</body>
</html>
