<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location:../index-modern.php");
    exit();
}

$Id = $_POST['txtDeptID'];
$Name = $_POST['txtCourseName'];
$Credit = $_POST['txtCredit'];
$Sem = $_POST['cmbSem'];
$Dept = $_POST['cmbDept'];
$Inst = $_POST['cmbInst'];

$con = new mysqli("localhost","root","","oes");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$stmt = $con->prepare("UPDATE course SET course_name=?, credit_hr=?, semister=?, dept_name=(SELECT dept_name FROM department WHERE deptno=?), Inst_Name=(SELECT Inst_Name FROM instructor WHERE Inst_ID=?) WHERE course_id=?");
$stmt->bind_param("ssisss", $Name, $Credit, $Sem, $Dept, $Inst, $Id);
$stmt->execute();
$stmt->close();
$con->close();

header("Location: Course.php?msg=updated");
exit();
?>
