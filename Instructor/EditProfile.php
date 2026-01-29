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
<title>SUOES Student Profile</title>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
	<div id="header">
	</div>
    <?php
//		include "left_head.php";
		?>
	<div id="content">	<?php
		include "Header.php";
		?>
		<div id="left">
				            <div class="login">
	<h2>&nbsp;</h2>

			<h1 style="color:red">Hi! <?php  echo $_SESSION['Name'];?> Welcome to Profle</h1>
			<?php
$Id=$_GET['InstId'];
// Establish Connection with Database
$con = new mysqli("localhost","root");
// Select Database
$con->select_db("oes");
// Specify the query to execute
$sql = "select * from instructor where instructor.Inst_ID='".$Id."'";
// Execute query
$result = $con->query($sql);
// Loop through each records 
while($row = $result->fetch_array())
{
$Id=$row['Inst_ID'];
$Department=$row['dept_name'];
$Name=$row['Inst_Name'];

$Email=$row['email'];

$UserName =$row['username'];
$Password=$row['password'];
$Status=$row['Status'];
}
?>	<br/> 
          <form method="post" action="UpdateProfile.php?Id=<?php echo $Id;?>">
                     <span class="style8"><strong>Instructor Id:</strong></span>
                          <?php echo $Id;?><br/> <br/> 
                       <span class="style8"><strong> Instructor Name  :</strong></span>
                         <?php echo $Name;?><br/> <br/> 
                       <span class="style8"><strong>   Email  :</strong></span>
                        <?php echo $Email;?><br/>
                       <br/> <span class="style8"><strong>   Status  :</strong></span>
                          <?php echo $Status;?><br/> <br/>                        
                       <strong>Change username:</strong>
                          <span id="sprytextfield4">
                            <label>
                            <input type="text" name="txtUser" id="txtUser"  />
                            </label>
                          <span class="textfieldRequiredMsg">A value is required.</span></span><br/> <br/> 
                        <strong>Change Password:</strong>
                          <span id="sprytextfield5">
                            <label>
                            <input type="password" name="txtPass" id="txtPass"  />
                            </label>
                          <span class="textfieldRequiredMsg">A value is required.</span></span><br/> <br/> 
                      <label>
                            <input type="submit" name="button" id="button" value="Update Profile" />
                          </label>
                      
          </form>
                  <?php
// Close the connection
$con->close();
?>
	<p>&nbsp;</p>
	
	  </div>
		</div>
		<div id="footerline"></div>
	</div>
	
	<div id="footer">Copyright &copy; 2022 SU Online Examination System. All rights reserved.&nbsp;</div>	
</div>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
//-->
</script>
</body>
</html>