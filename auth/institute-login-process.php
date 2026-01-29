<?php
session_start();

$UserName = $_POST['txtUserName'];
$Password = $_POST['txtPassword'];

// Try Administrator first
$con = new mysqli("localhost", "root", "", "oes");
$stmt = $con->prepare("SELECT * FROM admin WHERE username=? AND password=?");
$stmt->bind_param("ss", $UserName, $Password);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();
$num_row = $result->num_rows;

if ($num_row > 0) {
    $_SESSION['ID'] = $row['Admin_ID'];
    $_SESSION['username'] = $row['username'];
    $stmt->close();
    $con->close();
    header("location:../Admin/index-modern.php");
    exit();
}
$stmt->close();

// Try Instructor
$stmt = $con->prepare("SELECT * FROM instructor WHERE username=? AND password=? AND Status='Active'");
$stmt->bind_param("ss", $UserName, $Password);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();
$records = $result->num_rows;

if ($records > 0) {
    $_SESSION['ID'] = $row['Inst_ID'];
    $_SESSION['Name'] = $row['Inst_Name'];
    $_SESSION['Dept'] = $row['dept_name'];
    $_SESSION['Course'] = $row['course_name'];
    $_SESSION['Email'] = $row['email'];
    $stmt->close();
    $con->close();
    header("location:../Instructor/index.php");
    exit();
}
$stmt->close();

// Try Exam Committee
$stmt = $con->prepare("SELECT * FROM exam_committee WHERE username=? AND password=? AND Status='Active'");
$stmt->bind_param("ss", $UserName, $Password);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_array();
$records = $result->num_rows;

if ($records > 0) {
    $_SESSION['ID'] = $row['EC_ID'];
    $_SESSION['Name'] = $row['EC_Name'];
    $_SESSION['Dept'] = $row['dept_name'];
    $stmt->close();
    $con->close();
    header("location:../ExamCommittee/index.php");
    exit();
}
$stmt->close();
$con->close();

// If no match found
echo '<script type="text/javascript">alert("Wrong Username or Password, or Account is Inactive");window.location=\'institute-login.php\';</script>';
?>
