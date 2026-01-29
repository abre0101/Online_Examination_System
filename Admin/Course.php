<?php
if (isset($_SESSION))
{
  session_start();
  
}
?>
<?php require_once('../Connections/OES.php'); ?>
<?php
//Department requirement
$con->select_db($database_OES);
//$query_Recordsetd = "SELECT * From course,department where course.dept_name=department.dept_name";
$query_Recordsetd = "SELECT * From department";
$Recordsetd = $con->query($query_Recordsetd);
$row_Recordsetd = $Recordsetd->fetch_assoc();
$totalRows_Recordsetd = $Recordsetd->num_rows;
?>

<!--Instructor Requist-->
<?php
$con->select_db($database_OES);
//$query_Recordseti = "SELECT * From instructor, department where instructor.dept_name=department.dept_name";
$query_Recordseti = "SELECT * From instructor";

$Recordseti = $con->query($query_Recordseti);
$row_Recordseti = $Recordseti->fetch_assoc();
$totalRows_Recordseti = $Recordseti->num_rows;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="style1.css" rel="stylesheet" type="text/css" />
<title>Course Management</title>
<style type="text/css">
  #dep{
        float:left;
  width:550px;
  margin-left:10px;
  padding: 0px 10px 30px 0px;
  display:inline;
    }</style>
<!--
.style1 {font-size: small}
.style12 {font-size: small; font-weight: bold; }
.style3 {font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: small;
	font-weight: bold;
	color: #000000;
}
.style4 {	font-size: small;
	font-weight: bold;
	color: #FFFFFF;
}
.style5 {color: #FFFFFF}
.style6 {color: #000000}
.style9 {font-family: Verdana, Arial, Helvetica, sans-serif}
-->
</style>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style15 {font-size: 10pt}
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
                    <h1>Welcome To <strong>Course</strong> Management</h1>
			
	        <div id="TabbedPanels1" class="TabbedPanels">
              <ul class="TabbedPanelsTabGroup">
                <li class="TabbedPanelsTab style1" tabindex="0"><span class="style1">Create New Course</span></li>
                <li class="TabbedPanelsTab style15" tabindex="0">Display Course</li>
              </ul>
	          <div class="TabbedPanelsContentGroup">
                <div class="TabbedPanelsContent">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><form id="form1" name="form1" method="post" action="InsertCourse.php">
                          <table width="100%" height="94" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                
                              <td>Course ID:</td>
                              <td><span id="sprytextfield1">
                                <label>
                                <input type="text" name="txtDeptID" id="txtDeptID" />
                                </label>
                                <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                            </tr>
                              <tr>
                      <td>&nbsp;</td>
                    </tr>
                              <tr>
                                  
                              <td>Course Name:</td>
                              <td><span id="sprytextfield2">
                                <label>
                                <input type="text" name="txtDeptName" id="txtDeptName" />
                                </label>
                                <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                            </tr>
                              <tr>
                      <td>&nbsp;</td>
                    </tr>
                              <tr>
                                 
                              <td>Credit Hour:</td>
                              <td><span id="sprytextfield3">
                                <label>
                                <input type="text" name="txtDeptCredit" id="txtDeptCredit" />
                                </label>
                                <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td></tr>
                              <tr>
                              <td>Semester:</td>
                              <td><label>
                              <select name="cmbSem" id="cmbSem">
                                  <option>1</option>
                                  <option>2</option>
                                    </select>
                              </label></td>
                            </tr>
                              
                              <tr><td>&nbsp;</td></tr>
                              <!--Department-->
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
	  $row_Recordsetd = $Recordsetd->fetch_assoc();
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
  $rows = $Recordseti->num_rows;
  if($rows > 0) {
     $Recordseti->data_seek(0);
	  $row_Recordseti = $Recordseti->fetch_assoc();
  }
?>
                          
                        </select></td>
                      </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td><label>
                                <input type="submit" name="button" id="button" value="Submit" />
                              </label></td>
                            </tr>
</table>
                      </form></td>
                    </tr>
</table>
                </div>
                <div class="TabbedPanelsContent">
                  <table width="100%" border="1" bordercolor="#85A157" >
                    <tr>
                      <th height="32" bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Course Id</strong></div></th>
                      <th bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Course Name</strong></div></th>
                       <th bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Credit Hour</strong></div></th>
                       <th bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Semester</strong></div></th>
                       <th bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Department</strong></div></th>
                       <th bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Instructor</strong></div></th>
                      <th bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Edit</strong></div></th>
                      <th bgcolor="#85A157" class="style3"><div align="left" class="style4">Delete</div></th>
                    </tr>
                    <?php
// Establish Connection with Database
$con = new mysqli("localhost","root");
// Select Database
$con->select_db("oes");
// Specify the query to execute
$sql = "select * from course";
// Execute query
$result = $con->query($sql);
// Loop through each records 
while($row = $result->fetch_array())
{
$Id=$row['course_id'];
$Name=$row['course_name'];
$Semester=$row['semister'];
$Credit=$row['credit_hr'];
$Dept=$row['dept_name'];
$Instrutor=$row['Inst_Name'];


?>
                    <tr>
                      <td bgcolor="#BBE0E9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Id;?></strong></div></td>
                      <td bgcolor="#BBE0E9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Name;?></strong></div></td>
                      <td bgcolor="#BBE0E9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Credit;?></strong></div></td>
                      <td bgcolor="#BBE0E9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Semester;?></strong></div></td>
                      <td bgcolor="#BBE0E9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Dept;?></strong></div></td>
                      <td bgcolor="#BBE0E9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Instrutor;?></strong></div></td>
                      <td bgcolor="#BBE0E9" class="style3"><div align="left" class="style9 style6"><strong><a href="EditCourse.php?CourseId=<?php echo $Id;?>">Edit</a></strong></div></td>
                      <td bgcolor="#BBE0E9" class="style3"><div align="left" class="style9 style6"><strong><a href="DeleteCourse.php?CourseId=<?php echo $Id;?>">Delete</a></strong></div></td>
                    </tr>
                    <?php
}
// Retrieve Number of records returned
$records = $result->num_rows;
?>
                    <tr>
                      <td colspan="4" class="style3"><div align="left" class="style12"> </div></td>
                    </tr>
                    <?php
// Close the connection
$con->close();
?>
                  </table>
                </div>
                <div class="TabbedPanelsContent">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td><form name="form1" method="post" action="InsertCourse.php">
                        <table width="100%" height="94" border="0" cellpadding="0" cellspacing="0">
                          </table>
                      </form></td>
                    </tr>
                  </table>
                </div>
	          </div>
          </div>
          <p>&nbsp;</p>
	
	<h1>&nbsp;</h1>
	  </div>
		
		<div id="footerline"></div>
	</div>
	
	<div id="footer">Copyright &copy; 2022 SU Online Examination System.  All rights reserved.&nbsp;</div>	
</div>
<script type="text/javascript">
<!--

var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
//-->
</script>
</body>
</html>