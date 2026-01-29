<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$Id = $_GET['ID'];
$Status = $_POST['cmbStatus'];
$Department = $_POST['cmbDep'];

$con = new mysqli("localhost","root","","oes");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$stmt = $con->prepare("UPDATE exam_committee SET dept_name=?, Status=? WHERE EC_ID=?");
$stmt->bind_param("sss", $Department, $Status, $Id);
$stmt->execute();
$stmt->close();
$con->close();

header("Location: ECommittee.php?msg=updated");
exit();
?>
