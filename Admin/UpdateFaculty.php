<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$Id = $_GET['FacId'];
$Name = $_POST['faculty_name'];

$con = new mysqli("localhost","root","","oes");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$stmt = $con->prepare("UPDATE faculty SET faculty_name=? WHERE faculty_id=?");
$stmt->bind_param("ss", $Name, $Id);
$stmt->execute();
$stmt->close();
$con->close();

header("Location: Faculty.php?msg=updated");
exit();
?>
