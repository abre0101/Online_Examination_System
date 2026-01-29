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
<style type="text/css">
</style>
<title>SU Online Examination System</title>
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
                   <!-- <div id="logout" align="left"><h2>Would you want to <a href="Logout.php">Logout</a></h2></div>-->
			<h1>Hi! <?php echo $_SESSION['Name'];?>  Welcome To Instructor Page</h1>
			
	
	<h1>&nbsp;</h1>
	  </div>
		
		<div id="footerline"></div>
	</div>
	
	<div id="footer">Copyright &copy; 2022 SU Online Examination System. All rights reserved.&nbsp;</div>	
</div>
</body>
</html>