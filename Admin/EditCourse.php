<?php require_once('../Connections/OES.php'); ?>
<?php
//Department requirement
$con->select_db($database_OES);
$query_Recordsetd = "SELECT * From department";
$Recordsetd = $con->query($query_Recordsetd);
$row_Recordsetd = $Recordsetd->fetch_assoc();
$totalRows_Recordsetd =$Recordsetd->num_rows;
?>

<!--Instructor Requist-->
<?php
$con->select_db($database_OES);
$query_Recordseti = "SELECT * From instructor";
$Recordseti = $con->query($query_Recordseti);
$row_Recordseti = $Recordseti->fetch_assoc();
$totalRows_Recordseti =$Recordseti->num_rows;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="style1.css" rel="stylesheet" type="text/css" />
<title>QUIZ Management</title>
<style type="text/css">
 #dep{
        float:left;
  width:550px;
  margin-left:10px;
  padding: 0px 10px 30px 0px;
  display:inline;
    }</style>
<!--
.style10 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: small; font-weight: bold; color: #FFFFFF; }
.style8 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: small; font-weight: bold; }
-->
</style>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style11 {color: #000000}
-->
</style>
</head>

<body>
<div id="container">
<div id="header">
  </div>
  <div id="content">
    
    <?php
    include "left_head.php";
    ?>
    <div id="left">
      <div id="dep">
          <h1>&nbsp;</h1>
			<table width="100%" height="209" border="0" cellpadding="0" cellspacing="0">
      </br>
              <tr>
                <td height="33" bgcolor="#b8f5b1"><span class="style10 style11">Edit Subject Information</span></td>
              </tr>
              <tr>
                <td><?php
$Id=$_GET['CourseId'];
// Establish Connection with Database
	$con = new mysqli("localhost","root");
	// Select Database
	$con->select_db("oes");
// Specify the query to execute
$sql = "select * from course where course_id='".$Id."'";
// Execute query
$result =$con->query($sql);
// Loop through each records 
while($row = $result->fetch_array())
{
$Id=$row['course_id'];
$Name=$row['course_name'];
$Credit=$row['credit_hr'];
$Sem=$row['semister'];
$Dept=$row['dept_name'];
$Instrutor=$row['Inst_Name'];
}
?>
                    <form method="post" action="UpdateCourse.php">
                    <table width="100%" border="0">
                      <tr>
                        <td height="32"><span class="style8">Course Id</span></td>
                        <td><span id="sprytextfield1">
                          <label>
                          <input name="txtDeptID" type="text" id="txtDeptID" value="<?php echo $Id;?>" />
                          </label>
                          <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                      </tr>
                      <tr>
                        <td height="36"><span class="style8">Course Name:</span></td>
                        <td><span id="sprytextfield2">
                          <label>
                          <input name="txtCourseName" type="text" id="txtCourseName" value="<?php echo $Name;?>" />
                          </label>
                          <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                      </tr>
                           <tr>
                        <td height="36"><span class="style8">Credit Hour:</span></td>
                        <td><span id="sprytextfield4">
                          <label>
                          <input name="txtCredit" type="text" id="txtCredit" value="<?php echo $Credit;?>" />
                          </label>
                          <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                      </tr>
                      <tr>
                        <td height="36"><span class="style8">Semester:</span></td>
                        <td><span id="sprytextfield3">
                          <label></label>
                          <span class="textfieldRequiredMsg">A value is required.</span>
                          <label>
                          <select name="cmbSem" id="cmbSem">
                            <option>1</option>
                            <option>2</option>
                          
                          </select>
                          </label>
                        </span></td>
                      </tr>
                        
                         <tr>
                        <td height="35">Department:</td>
                        <td><select name="cmbDept" id="cmbDept">
                          
<?php
do {  
?>
                                <option value="<?php echo $row_Recordsetd['deptno']?>"><?php echo $row_Recordsetd['dept_name']?></option>
                                <?php
} while ($row_Recordsetd = $Recordsetd->fetch_assoc());
  $rows = $Recordsetd->num_rows;
  if($rows > 0) {
   $Recordsetd->data_seek(0);
	  $row_Recordsetd =$Recordsetd->fetch_assoc();
  }
?>
                          
                        </select></td>
                      </tr>
                              <!--instructor-->
                               <tr>
                        <td height="35">Instructor:</td>
                        <td><select name="cmbInst" id="cmbInst">
                          
<?php
do {  
?>
                                <option value="<?php echo $row_Recordseti['Inst_ID']?>"><?php echo $row_Recordseti['Inst_Name']?></option>
                                <?php
} while ($row_Recordseti = $Recordseti->fetch_assoc());
  $rows =$Recordseti->num_rows;
  if($rows > 0) {
      $Recordseti->data_seek(0);
	  $row_Recordseti =$Recordseti->fetch_assoc();
  }
?>
                          
                        </select></td>
                      </tr>
                        
                      <tr>
                        <td></td>
                        <td><input type="submit" name="submit" value="Update Record" /></td>
                      </tr>
</table>
                  </form>
                    <?php
// Close the connection
$con->close();
?></td>
              </tr>
		  </table>
			<h1>&nbsp;</h1>
			
	        <p>&nbsp;</p>
	
	<h1>&nbsp;</h1>
	  </div>
		
		<div id="footerline"></div>
	</div>
	
	  <div id="footer">Copyright &copy; 2021 AMU Online Examination System. All rights reserved.&nbsp;</div>
</div>
<script type="text/javascript">
<!--
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
//-->
</script>
</body>
</html>