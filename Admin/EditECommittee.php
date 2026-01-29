<?php
if (isset($_SESSION))
{
  session_start();
  
}
?>
<?php require_once('../Connections/OES.php'); ?>
<?php
$con->select_db($database_OES);
$query_Recordsetd = "SELECT * From department";
$Recordsetd = $con->query($query_Recordsetd);
$row_Recordsetd = $Recordsetd->fetch_assoc();
$totalRows_Recordsetd = $Recordsetd->num_rows;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="style1.css" rel="stylesheet" type="text/css" />
<title>AMU OES Exam Committee Management</title>
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
              <tr>
                <td height="33" bgcolor="#b8f5b1"><span class="style10 style11">Edit User Information</span></td>
              </tr>
              <tr>
                <td>
	<?php
	$Id=$_GET['Id'];
	// Establish Connection with MYSQL
	$con = new mysqli("localhost","root");
	// Select Database
	$con->select_db("oes");
	// Specify the query to Insert Record
    $sql = "select * from exam_committee where EC_ID='".$Id."'";
	// execute query
	$result = $con->query ($sql);
// Loop through each records 
while($row = $result->fetch_array())
{
$Id=$row['EC_ID'];
$Name=$row['EC_Name'];
$Email=$row['email'];
$UserName=$row['username'];
$Password=$row['password'];
$Department=$row['dept_name'];
$Status=$row['Status'];

}
?><br/>
                  <form method="post" action="UpdateECommittee.php?ID=<?php echo $Id;?>">

                      <table width="100%" border="0">
                        <tr>
                          <td height="32"><span class="style8">Exam committee Id</span></td>
             <td><span  id="sprytextfield2">
                                <label><?php echo $Id;?>
                            </label></span></td>
                      </tr>
                          <!--<tr>
                          <td height="32"><span class="style8">Exam committee Id</span></td>
                          <td><span id="sprytextfield1">
                            <label>
                            <input name="excID" type="text" id="excID" value="<?php echo $Id;?>" />
                            </label>
                            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                        </tr>
                        <tr>
                          <td height="32"><span class="style8">Exam committee Name</span></td>
                          <td><span id="sprytextfield2">
                            <label>
                            <input name="excName" type="text" id="excName"  />
                            </label>
                            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                        </tr>
                        <tr>
                          <td height="36"><span class="style8">Username:</span></td>
                          <td><span id="sprytextfield3">
                            <label>
                            <input name="excUserName" type="text" id="excUserName"  />
                            </label>
                            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                        </tr>
                        <tr>
                          <td height="36"><span class="style8">Password:</span></td>
                          <td><span id="sprytextfield4">
                            <label>
                            <input name="txtPass" type="password" id="txtPass"  />
                            </label>
                            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                        </tr>-->

                            <tr><td height="35">Department:</td>
                        <td><select name="cmbDep" id="cmbDept">
                          
<?php
do {  
?>
                  <option value="<?php echo $row_Recordsetd['dept_name']?>"><?php echo $row_Recordsetd['dept_name']?></option>
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
                            <tr><td>&nbsp;</td></tr>
                          <tr><td><strong>Status:  </strong></td>
                              <td><label>
                              <select name="cmbStatus" id="cmbStatus">
                                  <option>Active</option>
                                  <option>Inactive</option>
                                    </select>
                                  </label></td></tr>
                        <tr>
                          <td></td>
                          <td><input type="submit" name="submit" value="Update Record" /></td>
                        </tr>
</table>
                    </form>
                    <?php
// Close the connection
$con->close();
?>
                    <form method="post" action="UpdateECommittee.php">
                      <table width="100%" border="0">
                      </table>
                    </form></td>
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
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
//-->
</script>
</body>
</html>