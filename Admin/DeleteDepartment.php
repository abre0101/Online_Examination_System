<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$ID = $_GET['ID'];
$con = new mysqli("localhost","root","","oes");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$stmt = $con->prepare("delete from department where deptno=?");
$stmt->bind_param("s", $ID);
$stmt->execute();
$stmt->close();
$con->close();

header("Location: Department.php?msg=deleted");
exit();
?>
