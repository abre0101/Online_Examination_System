<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SU OES</title>
</head>

<body>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$UserName=$_POST['txtUserName'] ?? '';
$Password=$_POST['txtPassword'] ?? '';
$UserType=$_POST['cmbType'] ?? '';

// Check if form was submitted
if(empty($UserName) || empty($Password) || empty($UserType)) {
    echo '<script type="text/javascript">alert("Please fill in all fields");window.location=\'student-login.php\';</script>';
    exit();
}

if($UserType==="Administrator")
{
 $con = new mysqli("localhost","root","","oes");
$stmt = $con->prepare("select * from admin where username=? and password=?");
$stmt->bind_param("ss", $UserName, $Password);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();
$num_row = $result->num_rows;

//echo $records;
if ($num_row==0)
{
echo '<script type="text/javascript">alert("Wrong UserName or Password");window.location=\'index.php\';</script>';
//die(header("location:index.php"));
}
else
{
$_SESSION['ID']=$row['Admin_ID'];
$_SESSION['username']=$row['username'];

header("location:Admin/index.php");
} 
$stmt->close();
$con->close();
}

else if ($UserType=="Instructor")
{
    $con = new mysqli("localhost","root","","oes");
$stmt = $con->prepare("select * from instructor where username=? and password=? and Status='Active'");
$stmt->bind_param("ss", $UserName, $Password);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();
$records = $result->num_rows;
if ($records==0)
{
echo '<script type="text/javascript">alert("Wrong UserName or Password Or You are Inactivated");window.location=\'index.php\';</script>';
}
else
{
$_SESSION['ID']=$row['Inst_ID'];
$_SESSION['Name']=$row['Inst_Name'];
$_SESSION['Dept']=$row['dept_name'];
$_SESSION['Course']=$row['course_name'];
$_SESSION['Email']=$row['email'];
header("location:../Instructor/index.php");
} 
$stmt->close();
$con->close();
}

else if ($UserType=="ExamCommittee")
{
$con = new mysqli("localhost","root","","oes");
$stmt = $con->prepare("select * from exam_committee where username=? and password=? and Status='Active'");
$stmt->bind_param("ss", $UserName, $Password);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();
$records = $result->num_rows;
if ($records==0)
{
echo '<script type="text/javascript">alert("Wrong UserName or Password");window.location=\'index.php\';</script>';
}
else
{
$_SESSION['ID']=$row['EC_ID'];
$_SESSION['Name']=$row['EC_Name'];
$_SESSION['Dept']=$row['dept_name'];

header("location:../ExamCommittee/index.php");
} 
$stmt->close();
$con->close();
}

else if ($UserType=="Student")
{
 $con = new mysqli("localhost","root","","oes");
 
 // Check connection
 if ($con->connect_error) {
     die("Connection failed: " . $con->connect_error);
 }
 
$stmt = $con->prepare("select * from student where username=? and password=? and Status='Active'");

if (!$stmt) {
    die("Prepare failed: " . $con->error);
}

$stmt->bind_param("ss", $UserName, $Password);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();
$records = $result->num_rows;

if ($records==0)
{
echo '<script type="text/javascript">alert("Wrong UserName or Password or Inactivated");window.location=\'student-login.php\';</script>';
}
else
{
$_SESSION['ID']=$row['Id'];
$_SESSION['Name']=$row['Name'];
$_SESSION['Dept']=$row['dept_name'];
$_SESSION['Sem']=$row['semister'];
header("location:../Student/index-modern.php");
exit();
} 
$stmt->close();
$con->close();

}

?>
</body>
</html>
