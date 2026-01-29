<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$FacId = $_GET['FacId'];
$con = new mysqli("localhost","root","","oes");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$stmt = $con->prepare("delete from faculty where faculty_id=?");
$stmt->bind_param("s", $FacId);
$stmt->execute();
$stmt->close();
$con->close();

header("Location: Faculty.php?msg=deleted");
exit();
?>
