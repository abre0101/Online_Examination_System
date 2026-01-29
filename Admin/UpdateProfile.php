<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$Id = $_GET['Id'];
$UserName = $_POST['txtUser'];
$Password = $_POST['txtPass'];

$con = new mysqli("localhost","root","","oes");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$stmt = $con->prepare("UPDATE admin SET username=?, password=? WHERE Admin_ID=?");
$stmt->bind_param("sss", $UserName, $Password, $Id);
$stmt->execute();
$stmt->close();
$con->close();

// Update session username if changed
$_SESSION['username'] = $UserName;

header("Location: Profile.php?msg=updated");
exit();
?>
