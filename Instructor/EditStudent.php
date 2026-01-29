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
<title>SUOES-Instructor students mngt</title>
<style type="text/css">
 #dep{
        float:left;
  width:550px;
  margin-left:10px;
  padding: 0px 10px 30px 0px;
  display:inline;
    }
<!--
.style10 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: small; font-weight: bold; color: #FFFFFF; }
.style11 {color: #000000}
.style8 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: small; font-weight: bold; }
-->
</style>
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
			<h1>Welcome <?php //echo $_SESSION['Name'];?></h1>
			
	        <table width="100%" height="209" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td height="33" bgcolor="#E3E5DB"><span class="style10 style11">Edit Student Information</span></td>
              </tr>
              <tr>
                <td><?php
$Id=$_GET['Id'];
// Establish Connection with Database
$con = new mysqli("localhost","root");
// Select Database
$con->select_db("oes");
// Specify the query to execute
$sql = "select * from student where Id='".$Id."'";
// Execute query
$result = $con->query($sql);
// Loop through each records 
while($row = $result->fetch_array())
{
$Id=$row['Id'];
$Dept=$row['dept_name'];
$Name=$row['Name'];
$Semester=$row['semister'];
$Email=$row['email'];

$Status=$row['Status'];
}
?>
                    <form method="post" action="UpdateStudent.php?Id=<?php echo $Id;?>">
                      <table width="100%" border="0">
                        <tr>
                          <td height="32"><span class="style8">Student Id</span></td>
                          <td><?php echo $Id;?></td>
                        </tr>
                        <tr>
                          <td height="36"><span class="style8">Student Name:</span></td>
                          <td><?php echo $Name;?></td>
                        </tr>
                        <tr>
                          <td height="31"><strong>Department:</strong></td>
                          <td><?php echo $Dept;?></td>
                        </tr>
                        <tr>
                          <td height="31"><strong>Semester:</strong></td>
                          <td><?php echo $Semester;?></td>
                        </tr>
                        
                        <tr>
                          <td height="33"><strong>Email:</strong></td>
                          <td><?php echo $Email;?></td>
                        </tr>
                        <tr>
                          <td height="34"><strong>Status:</strong></td>
                          <td><label>
                            <select name="cmbStatus" id="cmbStatus">
                              <option>Active</option>
                              <option>InActive</option>
                            </select>
                          </label></td>
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
          <p>&nbsp;</p>
	
	<h1>&nbsp;</h1>
	  </div>
		
		<div id="footerline"></div>
	</div>
	
	<div id="footer">Copyright &copy; 2022 SU Online Examination System. </br> All rights reserved.&nbsp;</div>
</div>
</body>
</html>