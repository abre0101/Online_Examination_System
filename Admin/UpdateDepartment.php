<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$Id = $_GET['ID'];
$Name = $_POST['txtID'];
$Faculty = isset($_POST['cmbFaculty']) ? $_POST['cmbFaculty'] : '';

$con = new mysqli("localhost","root","","oes");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if($Faculty) {
    $stmt = $con->prepare("UPDATE department SET dept_name=?, faculty_name=? WHERE deptno=?");
    $stmt->bind_param("sss", $Name, $Faculty, $Id);
} else {
    $stmt = $con->prepare("UPDATE department SET dept_name=? WHERE deptno=?");
    $stmt->bind_param("ss", $Name, $Id);
}

$stmt->execute();
$stmt->close();
$con->close();

header("Location: Department.php?msg=updated");
exit();
?>
