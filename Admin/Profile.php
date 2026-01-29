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
<title>AMU OES Student</title>
<style type="text/css">
 #dep{
        float:left;
  width:550px;
  margin-left:10px;
  padding: 0px 10px 30px 0px;
  display:inline;
    }</style>

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
      <h1>Welcome <?php echo $_SESSION['username'];?></h1>
			<?php
$Id=$_SESSION['ID'];
// Establish Connection with Database
$con = new mysqli("localhost","root");
  // Select Database
  $con->select_db("oes");
// Specify the query to execute
$sql = "select * from admin where Admin_ID='".$Id."'";
// Execute query
$result = $con->query($sql);
// Loop through each records 
$row = $result->fetch_array();

?>   
                      <table width="100%" border="0">
                        <tr>
                          <td height="32"><span class="style8"><strong>Admin Id</strong></span></td>
                          <td><?php echo $row['Admin_ID'];?></td>

                        </tr>
                        <tr>
                          <td height="36"><span class="style8"><strong>Admin Name:</strong></span></td>
                          <td><?php echo $row['Admin_Name'];?></td>
                        </tr>
                        
                          <tr>
                          <td height="33"><strong>Email:</strong></td>
                          <td><?php echo $row['email'];?></td>
                        </tr>
                        <tr>
                          <td height="31"><strong>User Name:</strong></td>
                          <td><?php echo $row['username'];?></td>
                        </tr>
                      <tr>
                          <td height="31"><strong>Password:</strong></td>
                          <td><?php echo $row['password'];?></td>
                        </tr>
                        
                        <tr>
                          <td height="28"></td>
                          <td><a href="EditProfile.php?Id=<?php echo $Id;?>"><strong>Edit Profile</strong></a></td>
                        </tr>
                      </table>
                  
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
	
	<div id="footer">Copyright &copy; 2022 SU Online Examination System.</br> All rights reserved.&nbsp;</div>	
</div>
</body>
</html>