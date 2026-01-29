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
<script src="../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
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
	font-size: 12px
}
-->
</style>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style12 {font-size: small; font-weight: bold; }
.style3 {font-family: Verdana, Arial, Helvetica, sans-serif;
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
.style9 {font-family: Verdana, Arial, Helvetica, sans-serif}
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
			<h1>Hi! <?php echo $_SESSION['Name'];?>  Welcome to student Page</h1>						
	        <div id="TabbedPanels1" class="TabbedPanels">
	          <ul class="TabbedPanelsTabGroup">
	            <!--<li class="TabbedPanelsTab style1" tabindex="0">Create Student</li>-->
	            <li class="TabbedPanelsTab style1" tabindex="0">Instructor Display Student</li>
              </ul>
	          <div class="TabbedPanelsContentGroup">
	           
	            <div class="TabbedPanelsContent">
	              <table width="100%" border="1" bordercolor="#85A157" >
                    <tr>
                      <th height="32" bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong> Id</strong></div></th>
                      <th bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Name</strong></div></th>
                      <th bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Department</strong></div></th>
                      <th height="32" bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Semester</strong></div></th>
                      <th bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>email</strong></div></th>
                      <th bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Status</strong></div></th>
                      <th bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Edit</strong></div></th>
                      </tr>
                    <?php
error_reporting(0);
// Establish Connection with Database
$con = new mysqli("localhost","root");
// Select Database
$con->select_db("oes");
// Specify the query to execute
$sql = "select * from student";
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
?>
                    <tr>
                      <td bgcolor="#B8DEE9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Id;?></strong></div></td>
                      <td bgcolor="#B8DEE9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Name;?></strong></div></td>
                      <td bgcolor="#B8DEE9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Dept;?></strong></div></td>
                      <td bgcolor="#B8DEE9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Semester;?></strong></div></td>
                      <td bgcolor="#B8DEE9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Email;?></strong></div></td>
                      <td bgcolor="#B8DEE9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Status;?></strong></div></td>
                      <td bgcolor="#B8DEE9" class="style3"><div align="left" class="style9 style6"><strong><a href="EditStudent.php?StuId=<?php echo $Id;?>">Edit</a></strong></div></td>
                      </tr>
                    <?php
}
// Retrieve Number of records returned
$records = $result->num_rows;
?>
                    <tr>
                      <td colspan="8" class="style3"><div align="left" class="style12"> </div></td>
                    </tr>
                    <?php
// Close the connection
$con->close();
?>
                  </table>
				  						            </div>

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
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
//-->
</script>
</body>
</html>