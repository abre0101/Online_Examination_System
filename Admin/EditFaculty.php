<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="style1.css" rel="stylesheet" type="text/css" />
<title>Edit Faculty</title>
<style type="text/css">
<!--
.style10 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: small; font-weight: bold; color: #FFFFFF; }
.style8 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: small; font-weight: bold; }
-->
</style>

<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<style type="text/css">
 #dep{
        float:left;
  width:550px;
  margin-left:10px;
  padding: 0px 10px 30px 0px;
  display:inline;
    }</style>
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
              <tr>
                <td height="33" bgcolor="#07dddd" color="#000000" ><span class="style10 style11">Edit Faculty Information</span></td>
              </tr>
              <tr>
                <td>
<?php
$Id=$_GET['FacId'];
// Establish Connection with Database
$con = new mysqli("localhost","root","","oes");
// Specify the query to execute
$sql = "select * from faculty where faculty_id='".$Id."'";
// Execute query
$result = $con->query($sql);
// Loop through each records 
while($row = $result->fetch_array())
{
$Id=$row['faculty_id'];
$Name=$row['faculty_name'];

}
?> <br/>
                  <form method="post" action="UpdateFaculty.php?FacId=<?php echo $Id;?>">
                    <table width="100%" border="0">
                      <tr>
                        <td height="32"><span class="style8">Faculty Id</span></td>
                        <td><span  id="sprytextfield2">
                                <label><?php echo $Id;?>
                            </label></span></td>
                      </tr>
                      <tr>
                        <td height="36"><span class="style8">Faculty Name:</span></td>
                       <td><span id="sprytextfield1">
                                <label>
                                <input type="text" name="faculty_name" id="faculty_name" />
                                </label>
                                <span class="textfieldRequiredMsg">Please Enter Faculty ID</span></span></td>
                            </tr>
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
	
	<div id="footer">Copyright &copy; 2021 AMU Online Examination System.  All rights reserved.&nbsp;</div>		
</div>
<script type="text/javascript">
<!--

var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
//-->
</script>
</body>
</html>