<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$Id = $_GET['Stud_ID'];
$con = new mysqli("localhost","root","","oes");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$stmt = $con->prepare("delete from student where Id=?");
$stmt->bind_param("s", $Id);
$stmt->execute();
$stmt->close();
$con->close();

header("Location: Student-modern.php?msg=deleted");
exit();
?>
