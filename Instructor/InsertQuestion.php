
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
        $ID=$_POST['quetionID'];       
        $cmbCourse=$_POST['cmbCourse'];
	$cmbExam=$_POST['cmbExam'];
        
	$cmbSem=$_POST['cmbSem'];
	//$cmbQtype=$_POST['cmbQtype'];
	$txtQuestion=$_POST['txtQue'];
	$txtA=$_POST['txtA'];
	$txtB=$_POST['txtB'];
	$txtAns=$_POST['txtAns'];
	// Establish Connection with Database
$con = new mysqli("localhost","root");
// Select Database
$con->select_db("oes");
// Specify the query to execute

$sql = "insert into truefalse_question	(question_id,course_name,exam_id,semester,question,Answer1,Answer2,Answer) 	
       values('".$ID."','".$cmbCourse."','".$cmbExam."','".$cmbSem."','".$txtQuestion."','".$txtA."','".$txtB."','".$txtAns."')";
// Execute query
$result = $con->query($sql);

// Close The Connection
//$con->close();
	
	echo '<script type="text/javascript">alert("Question Inserted Succesfully");window.location=\'Question.php\';</script>';

?>
</body>
</html>
