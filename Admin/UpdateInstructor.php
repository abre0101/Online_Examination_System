<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$Id = $_GET['ID'];
$Status = $_POST['cmbStatus'];
$Department = $_POST['cmbDept'];

$con = new mysqli("localhost","root","","oes");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$stmt = $con->prepare("UPDATE instructor SET dept_name=(SELECT dept_name FROM department WHERE deptno=?), Status=? WHERE Inst_ID=?");
$stmt->bind_param("sss", $Department, $Status, $Id);
$stmt->execute();
$stmt->close();
$con->close();

header("Location: Instructor.php?msg=updated");
exit();
?>
