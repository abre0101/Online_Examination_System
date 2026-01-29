<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$Id = $_GET['Id'];
$Year = $_POST['cmbYear'];
$Status = $_POST['cmbStatus'];
$Sem = $_POST['cmbSem'];
$Department = $_POST['cmbDep'];

$con = new mysqli("localhost","root","","oes");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$stmt = $con->prepare("UPDATE student SET dept_name=?, Status=?, year=?, semister=? WHERE Id=?");
$stmt->bind_param("sssis", $Department, $Status, $Year, $Sem, $Id);
$stmt->execute();
$stmt->close();
$con->close();

header("Location: Student-modern.php?msg=updated");
exit();
?>
