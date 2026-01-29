<?php
if (!isset($_SESSION)) 
{
  session_start();
  
}
?>
<?php require_once('../Connections/OES.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$con->select_db($database_OES);
$query_Recordsetd = "SELECT * FROM department";
$Recordsetd = $con->query($query_Recordsetd);
$row_Recordsetd = $Recordsetd->fetch_assoc();
$totalRows_Recordsetd = $Recordsetd->num_rows;

$con->select_db($database_OES);
$query_Recordset1 = "SELECT * FROM exam_category";
$Recordset1 = $con->query($query_Recordset1);
$row_Recordset1 = $Recordset1->fetch_assoc();
$totalRows_Recordset1 = $Recordset1->num_rows;

$con->select_db($database_OES);
$query_Recordset2 = "SELECT * FROM course";
$Recordset2= $con->query($query_Recordset2);
$row_Recordset2 = $Recordset2->fetch_assoc();
$totalRows_Recordset2 = $Recordset2->num_rows;

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="style1.css" rel="stylesheet" type="text/css" />
<title>SUOES-Instructor Question Management</title>
<script src="../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<style type="text/css">
 #dep{
        float:left;
  width:550px;
  margin-left:10px;
  padding: 0px 10px 30px 0px;
  display:inline;
    }

<!--
.style1 {
	font-size: small;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.style2 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style3 {font-size: small}
-->
</style>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
  <div id="header">
  </div>
  <div id="content">
    <?php
    include "Header.php";
    ?>
    <div id="left">
                  <div class="login">
                        <div id="dep">

			<h3>Hi <?php // echo $_SESSION['Name'];?> Welcome to Manage  Question</h3>
			
	        <div id="TabbedPanels1" class="TabbedPanels">
	          <ul class="TabbedPanelsTabGroup">
	            <li class="TabbedPanelsTab style1" tabindex="0">Add Choose</li>
	            <li class="TabbedPanelsTab style2 style3" tabindex="0">Display Questions</li>
              </ul>
	          <div class="TabbedPanelsContentGroup">
	            <div class="TabbedPanelsContent">
	              <table width="600px"  align="content" border="0" cellspacing="0" cellpadding="0">
                   
                                         <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><form id="form1" name="form1" method="post" action="InsertQue.php">
                        <table width="550px" height="281" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                              <td height="30" width="200px"><strong>Question No:</strong></td>
                            <td><span id="sprytextfield6">
                              <label>
                              <input type="text" name="quetionID" id="quetionID" />
                              </label>
                                    <span class="textfieldRequiredMsg">A value is required.</span></span></td></tr>
                            <tr><td height="31"><strong>Select Course:</strong></td>
                            <td><label>
                              <select name="cmbCourse" id="cmbCourse">
                                <?php
do {  
?>
                                <option value="<?php echo $row_Recordset2['course_id']?>"> <?php echo $row_Recordset2['course_name']?> </option>
                                <?php
} while ($row_Recordset2 = $Recordset2->fetch_assoc());
  $rows = $Recordset2->num_rows;
  if($rows > 0) {
      $Recordset2->data_seek(0);
	  $row_Recordset2 = $Recordset2->fetch_assoc();
  }
?>
                              </select>
                            </label></td>
                          </tr> 
                   <tr>
                      <td>&nbsp;</td>
                    </tr>
                    
                          <tr>
                              <td height="30" width="100"><strong>Select Exam:</strong></td>
                            <td><label>
                              <select name="cmbExam" id="cmbExam">
                                <?php
do {  
?>
                                <option value="<?php echo $row_Recordset1['exam_id']?>"><?php echo $row_Recordset1['exam_name']?></option>
                                <?php
} while ($row_Recordset1 =$Recordset1->fetch_assoc());
  $rows = $Recordset1->num_rows;
  if($rows > 0) {
   $Recordset1->data_seek(0);
	  $row_Recordset1 = $Recordset1->fetch_assoc();
  }
?>
                              </select>
                            </label></td>
                          </tr>
                            <tr>
                      <td>&nbsp;</td>
                    </tr>
                          
                             <tr>
                      <td>&nbsp;</td>
                    </tr>
                            <tr>
                            <td height="32"><strong>Select Semester:</strong></td>
                            <td><label>
                              <select name="cmbSem" id="cmbSem">
                                <option>1</option>
                                <option>2</option>
                               
                            </select>
                            </label></td>
                          </tr>
                            
                          
                          <tr>
                            <td height="29"><strong>Question:</strong></td>
                            <td><span id="sprytextarea1">
                              <label>
                              <textarea name="txtQue" id="txtQue" cols="40" rows="2"></textarea>
                              </label>
                            <span class="textareaRequiredMsg">Please write your question.</span></span></td>
                          </tr>
                          <tr>
                            <td height="27"><strong>Option A:</strong></td>
                            <td><span id="sprytextfield1">
                              <label>
                              <input type="text" name="txtA" id="txtA" />
                              </label>
                            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                          </tr>
                          <tr>
                            <td height="27"><strong>Option B:</strong></td>
                            <td><span id="sprytextfield2">
                              <label>
                              <input type="text" name="txtB" id="txtB" />
                              </label>
                            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                          </tr>
                          <tr>
                            <td height="27"><strong>Option C:</strong></td>
                            <td><span id="sprytextfield3">
                              <label>
                              <input type="text" name="txtC" id="txtC" />
                              </label>
                            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                          </tr>
                          <tr>
                            <td height="27"><strong>Option D:</strong></td>
                            <td><span id="sprytextfield4">
                              <label>
                              <input type="text" name="txtD" id="txtD" />
                              </label>
                            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                          </tr>
                          <tr>
                            <td height="27"><strong>Answer:</strong></td>
                            <td><span id="sprytextfield5">
                              <label>
                              <input type="text" name="txtAns" id="txtAns" />
                              </label>
                            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                            <td><label>
                              <input type="submit" name="button" id="button" value="Submit" />
                            </label></td>
                          </tr>
                        </table>
                                            </form>
                      </td>
                    </tr>
                  </table>
	            </div>
	            <div class="TabbedPanelsContent">
                 <?php
// Establish Connection with Database
$con = new mysqli("localhost","root");
// Select Database
 $con->select_db("oes");
// Specify the query to execute
//$sql = "SELECT distinct question_page.question_id,question_page.exam_id, question_page.course_name,question_page.semister, question_page.question, question_page.Option1, question_page.Option2, question_page.Option3, question_page.Option4, question_page.Answer, course.course_name, exam_category.exam_name
//FROM question_page, exam_category, course
//WHERE question_page.exam_id=exam_category.exam_id AND question_page.course_name=course.course_name";
$sql = "SELECT * FROM question_page ";
// Execute query
$result =  $con->query($sql);
// Loop through each records 
while($row = $result->fetch_array())
{
$Id=$row['question_id'];
$Quiz=$row['exam_id'];
$Sem=$row['semester'];
$Subject=$row['course_name'];
$Question=$row['question'];
$OptionA=$row['Option1'];
$OptionB=$row['Option2'];
$OptionC=$row['Option3'];
$OptionD=$row['Option4'];
$Answer=$row['Answer'];

?>
	              <table width="100%" height="184" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><strong>Question No:<?php echo $Id;?></strong></td>
                      <td><strong>Exam_Name:<?php echo $Quiz;?></strong></td>
                    </tr>
                    <tr>
                      <td><strong>Semester:<?php echo $Sem;?></strong></td>
                      <td><strong>Course Name:<?php echo $Subject;?></strong></td>
                    </tr>
                    <tr>
                      <td colspan="2"><strong>Question:<?php echo $Question;?></strong></td>
                    </tr>
                    <tr>
                      <td>(A)<?php echo $OptionA;?></td>
                      <td>(B)<?php echo $OptionB;?></td>
                    </tr>
                    <tr>
                      <td>(C)<?php echo $OptionC;?></td>
                      <td>(D)<?php echo $OptionD;?></td>
                    </tr>
                    <tr>
                      <td colspan="2"><strong>Right Answer:<?php echo $Answer;?></strong></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td><a href="DeleteQue.php?QueId=<?php echo $Id;?>"><strong>Delete</strong></a></td>
                    </tr>
                    <tr>
                      <td colspan="2"><hr/></td>
                    </tr>
                  </table>
                           <?php
}
// Retrieve Number of records returned
$records = $result->num_rows;
// Close the connection
$con->close();
?>
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
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6");
//-->
</script>
</body>
</html>
<?php
$Recordset1->free_result();

$Recordset2->free_result();
//mysql_free_result($Recordsetd);
?>