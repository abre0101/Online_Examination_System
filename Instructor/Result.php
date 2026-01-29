<?php
if (!isset($_SESSION)) 
{
  session_start();
  
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="style1.css" rel="stylesheet" type="text/css" />
<title>TEST Management</title>
<style type="text/css">
<!--
.style12 {font-size: small; font-weight: bold; }
.style13 {font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: small;
	font-weight: bold;
	color: #000000;
}
.style4 {font-size: small;
	font-weight: bold;
	color: #FFFFFF;
}
.style5 {color: #FFFFFF}
.style6 {color: #000000}
-->
</style>
</head>

<body>
<div id="container">
	<?php
		include "Header.php";
		?>
	<div id="content">
		<div id="left">
			<h1>Welcome <?php echo $_SESSION['Name'];?></h1>
			
	        <table width="100%" border="1" bordercolor="#85A157" >
              <tr>
                            <th bgcolor="#85A157" class="style13"><div align="left" class="style9 style5"><strong>Exam Type</strong></div></th>
                <th bgcolor="#85A157" class="style13"><div align="left" class="style9 style5"><strong>Semester</strong></div></th>
                <th height="32" bgcolor="#85A157" class="style13"><div align="left" class="style9 style5"><strong>Course</strong></div></th>
                <th bgcolor="#85A157" class="style13"><div align="left" class="style9 style5"><strong>Total</strong></div></th>
                <th bgcolor="#85A157" class="style13"><div align="left" class="style9 style5"><strong>Correct</strong></div></th>
                <th bgcolor="#85A157" class="style13"><div align="left" class="style4">Wrong</div></th>
                <th bgcolor="#85A157" class="style13"><div align="left" class="style4">Score</div></th>
            
              </tr>
              <?php
// Establish Connection with Database
$con = mysqli_connect("localhost","root","","oes");
// Select Database
//mysqli_select_db("oes", $con);
// Specify the query to execute
$sql = "SELECT result.result_id, exam_category.exam_name, 

course.course_name, result.Id, 

 student.Name, 

student.semister, result.Total, result.Correct, 

result.Wrong, result.`Result`
FROM result, exam_category, course, student
WHERE result.exam_id=exam_category.exam_id AND 

result.course_id=course.course_id AND 

result.Id=student.Id AND result.Id='".$_SESSION['ID']."' order by exam_category.exam_name ";
// Execute query
$result = mysqli_query($con,$sql);
// Loop through each records 
while($row = mysqli_fetch_array($result))
{
$Exam=$row['exam_name'];
$Sem=$row['semister'];
$Subject=$row['course_name'];
$Total=$row['Total'];
$Correct=$row['Correct'];
$Wrong=$row['Wrong'];
$Score=$row['Result'];

?>
              <tr>
               
                <td class="style13"><div align="left" class="style9 style6"><strong><?php echo $Exam;?></strong></div></td>
                <td class="style13"><div align="left" class="style9 style6"><strong><?php echo $Sem;?></strong></div></td>
                <td class="style13"><div align="left" class="style9 style6"><strong><?php echo $Subject;?></strong></div></td>
                <td class="style13"><div align="left" class="style9 style6"><strong><?php echo $Total;?></strong></div></td>
                <td class="style13"><div align="left" class="style9 style6"><strong><?php echo $Correct;?></strong></div></td>
                <td class="style13"><div align="left" class="style9 style6"><strong><?php echo $Wrong;?></strong></div></td>
                <td class="style13"><div align="left" class="style9 style6"><strong><?php echo $Score;?></strong></div></td>
              </tr>
              <?php
}
// Retrieve Number of records returned
$records = mysqli_num_rows($result);
?>
              <tr>
                <td colspan="7" class="style13"><div align="left" class="style12"> </div></td>
              </tr>
              <?php
// Close the connection
mysqli_close($con);
?>
            </table>
          <p>&nbsp;</p>
	
	<h1>&nbsp;</h1>
	  </div>
		
		<div id="footerline"></div>
	</div>
	
	<div id="footer">Copyright &copy; 2014/2022 SU Online Examination System.<br/> All rights reserved.&nbsp;</div>	
</div>
</body>
</html>