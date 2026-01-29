<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="style1.css" rel="stylesheet" type="text/css" />
<title>SUOES Admin College management</title>
<style type="text/css">

    #fac{
        float:left;
  width:550px;
  margin-left:10px;
  padding: 0px 10px 30px 0px;
  display:inline;
    }

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
      <div id="fac">
                    <h1>Welcome To <i>College Management</i> Page</h1>
			
	        <div id="TabbedPanels1" class="TabbedPanels">
              <ul class="TabbedPanelsTabGroup">
                <li class="TabbedPanelsTab style1" tabindex="0"><span class="style1">Create New College</span></li>
                <li class="TabbedPanelsTab style15" tabindex="0">Display College</li>
              </ul>
	          <div class="TabbedPanelsContentGroup">
                <div class="TabbedPanelsContent">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><form id="form1" name="form1" method="post" action="InsertFaculty.php">
                          <table width="100%" height="186" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td height="35">College ID:</td>
                              <td><span id="sprytextfield1">
                                <label>
                                <input type="text" name="txtID" id="txtID" />
                                </label>
                                <span class="textfieldRequiredMsg">Please Enter College ID</span></span></td>
                            </tr>
                            <tr>
                              <td height="30">College Name:</td>
                              <td><label><span id="sprytextfield2">
                                <input type="text" name="txtFaculty" id="txtFaculty" />
                              <span class="textfieldRequiredMsg">Please Enter College Name!</span></span></label></td>
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
                  <table width="100%" border="1" border_color="#85A157" >
                    <tr>
                      <th height="32" bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Id</strong></div></th>
                      <th bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>College Name</strong></div></th>
                       
                      <th bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Edit</strong></div></th>
                      <th bgcolor="#85A157" class="style3"><div align="left" class="style4">Delete</div></th>
                    </tr>
                    <?php
// Establish Connection with Database
 $con = new mysqli("localhost","root","","oes");
// Select Database
//$con->mysqli_select_db("oes");
// Specify the query to execute
$sql = "select * from faculty";
// Execute query
$result = $con->query($sql);

// Loop through each records 
while($row = $result->fetch_array())
{
$Id=$row['faculty_id'];
$FacultyName=$row['faculty_name'];

?>
                    <tr>
                      <td bgcolor="#BADFE8" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Id;?></strong></div></td>
                      <td bgcolor="#BADFE8" class="style3"><div align="left" class="style9 style6"><strong><?php echo $FacultyName;?></strong></div></td>
                      
                      <td bgcolor="#BADFE8" class="style3"><div align="left" class="style9 style6"><strong><a href="EditFaculty.php?FacId=<?php echo $Id;?>">Edit</a></strong></div></td>
                      <td bgcolor="#BADFE8" class="style3"><div align="left" class="style9 style6"><strong><a href="DeleteFaculty.php?FacId=<?php echo $Id;?>">Delete</a></strong></div></td>
                    </tr>
                    <?php
}
// Retrieve Number of records returned
$records = $result->num_rows;
?>
                    <tr>
                      <td colspan="7" class="style3"><div align="left" class="style12"> </div></td>
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
                        <td><form name="form1" method="post" action="InsertFaculty.php" >
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
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");

</script>
</body>
</html>