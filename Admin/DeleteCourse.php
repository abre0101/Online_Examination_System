<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$CourseId = $_GET['CourseId'];
$con = new mysqli("localhost","root","","oes");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$stmt = $con->prepare("delete from course where course_id=?");
$stmt->bind_param("s", $CourseId);
$stmt->execute();
$stmt->close();
$con->close();

header("Location: Course.php?msg=deleted");
exit();
?>
