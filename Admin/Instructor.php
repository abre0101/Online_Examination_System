<?php
if (isset($_SESSION))
{
  session_start();
  
}
?>
<?php require_once('../Connections/OES.php'); ?>
<?php
$con = mysqli_connect("localhost","root","","oes");
$query_Recordsetd = "SELECT * From department";
$Recordsetd = mysqli_query($con,$query_Recordsetd) or die(mysqli_error());
$row_Recordsetd = mysqli_fetch_assoc($Recordsetd);
$totalRows_Recordsetd = mysqli_num_rows($Recordsetd);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="style1.css" rel="stylesheet" type="text/css" />
<title>SUOES Instructor  Management</title>
<script src="../SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {font-size: small}
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
.style9 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style4 {
	font-size: small;
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
            <div id="logout" align="left"><h2>Would you want to <a href="Logout.php">Logout</a></h2></div>
		<div id="left">
			<h1>Welcome </h1>
			
	        <div id="TabbedPanels1" class="TabbedPanels">
	          <ul class="TabbedPanelsTabGroup">
	            <li class="TabbedPanelsTab style1" tabindex="0">Create New Instructor</li>
	            <li class="TabbedPanelsTab style1" tabindex="0">Display Instructor</li>
              </ul>
	          <div class="TabbedPanelsContentGroup">
	            <div class="TabbedPanelsContent">
	              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><form id="form1" name="form1" method="post" action="InsertInstructor.php">
                        <table width="100%" height="94" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td>Instructor Id:</td>
                            <td><span id="sprytextfield1">
                              <label>
                              <input type="text" name="instID" id="instID" />
                              </label>
                            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                          </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                            <td>Instructor Name:</td>
                            <td><span id="sprytextfield2">
                              <label>
                              <input type="text" name="instName" id="instName" />
                              </label>
                            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                          </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                            <td>Email :</td>
                            <td><span id="sprytextfield3">
                              <label>
                                  <input type="email" name="instEmail" id="instEmail" />
                              </label>
                            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                          </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                            <td>Username :</td>
                            <td><span id="sprytextfield4">
                              <label>
                              <input type="text" name="instUName" id="instUName" />
                              </label>
                            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                          </tr>
                            <tr><td>&nbsp;</td></tr>
                          <tr>
                            <td>Password:</td>
                            <td><span id="sprytextfield5">
                              <label>
                                  <input type="password" name="instPassword" id="instPassword" />
                              </label>
                            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                          </tr>
                            <tr><td>&nbsp;</td></tr>
                            
                             <tr>
                        <td height="35">Department:</td>
                        <td><select name="cmbDept" id="cmbDept">
                          
<?php
do {  
?>
                                <option value="<?php echo $row_Recordsetd['deptno']?>"><?php echo $row_Recordsetd['dept_name']?></option>
                                <?php
} while ($row_Recordsetd = mysqli_fetch_assoc($Recordsetd));
  $rows = mysqli_num_rows($Recordsetd);
  if($rows > 0) {
      mysqli_data_seek($Recordsetd, 0);
	  $row_Recordsetd = mysqli_fetch_assoc($Recordsetd);
  }
?>
                          
                        </select></td>
                      </tr>
                            <tr><td>&nbsp;</td></tr>
                              <tr>
                              <td>Status:</td>
                              <td><label>
                              <select name="cmbStatus" id="cmbStatus">
                                  <option>Active</option>
                                  <option>Inactive</option>
                                    </select>
                              </label></td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
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
	              <table width="100%" border="1" bordercolor="#85A157" >
                    <tr>
                      <th height="32" bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Id</strong></div></th>
                      <th height="32" bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Name</strong></div></th>
					  <th height="32" bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Email</strong></div></th>
                      <th height="32" bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Department</strong></div></th>
                      <th height="32" bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>UserName</strong></div></th>
                      <th bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Password</strong></div></th>
                      <th height="32" bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Status</strong></div></th>
                      <th bgcolor="#85A157" class="style3"><div align="left" class="style9 style5"><strong>Edit</strong></div></th>
                      <th bgcolor="#85A157" class="style3"><div align="left" class="style4">Delete</div></th>
                    </tr>
                    <?php
// Establish Connection with Database
$con = mysqli_connect("localhost","root","","oes");// Select Database
//mysqli_select_db("oes", $con);
// Specify the query to execute
$sql = "select * from instructor";
// Execute query
$result = mysqli_query($con,$sql);
// Loop through each records 
while(@$row = mysqli_fetch_array($result))
{
$Id=$row['Inst_ID'];
$Name=$row['Inst_Name'];
$Email=$row['email'];
$UserName=$row['username'];
$Password=$row['password'];
$Department=$row['dept_name'];
$Status=$row['Status'];

?>
                    <tr>
                      <td bgcolor="#B8DEE9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Id;?></strong></div></td>
                      <td bgcolor="#B8DEE9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Name;?></strong></div></td>
					  <td bgcolor="#B8DEE9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Email;?></strong></div></td>
                      <td bgcolor="#B8DEE9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Department;?></strong></div></td>
                      <td bgcolor="#B8DEE9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $UserName;?></strong></div></td>
                       <td bgcolor="#B8DEE9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Password;?></strong></div></td>                     
                      <td bgcolor="#B8DEE9" class="style3"><div align="left" class="style9 style6"><strong><?php echo $Status;?></strong></div></td>
                      <td bgcolor="#B8DEE9" class="style3"><div align="left" class="style9 style6"><strong><a href="EditInstructor.php?Id=<?php echo $Id;?>">Edit</a></strong></div></td>
                      <td bgcolor="#B8DEE9" class="style3"><div align="left" class="style9 style6"><strong><a href="DeleteInstructor.php?Id=<?php echo $Id;?>">Delete</a></strong></div></td>
                    </tr>
                    <?php
}
// Retrieve Number of records returned
$records = mysqli_num_rows($result);
?>
                    <tr>
                      <td colspan="4" class="style3"><div align="left" class="style12"> </div></td>
                    </tr>
                    <?php
// Close the connection
mysqli_close($con);
?>
                  </table>
	            </div>
              </div>
          </div>
          <p>&nbsp;</p>
	
	<h1>&nbsp;</h1>
	  </div>
		
		<div id="footerline"></div>
	</div>
	
	  <div id="footer">Copyright &copy; 2014/2022 SU Online Examination System. <br/> All rights reserved.&nbsp;</div>	
</div>
<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
//-->
</script>
</body>
</html>