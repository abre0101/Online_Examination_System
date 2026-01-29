

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
<title>Edit Student</title>
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

<style type="text/css">
<!--
.style11 {color: #000000}
-->
</style>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
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
			<table width="100%" height="209" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td height="33" bgcolor="#07dddd"><span class="style10 style11">Edit Student Information</span></td>
              </tr>
              <tr>
                <td><?php
$Id=$_GET['Id'];
// Establish Connection with Database
    $con = new mysqli("localhost","root","","oes");
	// Specify the query to Insert Record
    $stmt = $con->prepare("select * from student where Id=?");
	$stmt->bind_param("s", $Id);
	// execute query
	$stmt->execute();
	$result = $stmt->get_result();
   // Loop through each records 
while($row = $result->fetch_array())
{
$Id=$row['Id'];
$Name=$row['Name'];
$StudDept=$row['dept_name'];
$StudYear=$row['year'];
$Semester=$row['semister'];
$StudSex=$row['Sex'];
$Email=$row['email'];
$UserName =$row['username'];
$Password=$row['password'];
$Status=$row['Status'];
}
$stmt->close();
?>
                  <form method="post" action="UpdateStudent.php?Id=<?php echo $Id;?>">
                    <table width="100%" border="0">
                      <tr>
                        <td height="32"><span class="style8">Student Id</span></td>
                        <td><?php echo $Id;?></td>
                      </tr>
                                           
                      <tr>
                        <td height="31"><strong>Name:</strong></td>
                        <td><?php echo $Name;?></td>
                      </tr>
                   
 <tr><td height="35">Department:</td>
  <td><select name="cmbDep" id="cmbDept">
                          
<?php
do {  
?>
 <option value="<?php echo $row_Recordsetd['dept_name']?>"><?php echo $row_Recordsetd['dept_name']?></option>  <?php
} while ($row_Recordsetd = $Recordsetd->fetch_assoc());
  $rows = $Recordsetd->num_rows;
  if($rows > 0) {
      $Recordsetd->data_seek(0);
    $row_Recordsetd = $Recordsetd->fetch_assoc();
    

  }
?>
                          
                        </select></td>
                      </tr>
                        <tr>
                        <td height="31"><strong>Year:</strong></td>
                        <td><?php echo $StudYear;?></td>
                      </tr>
                      <tr>
                        <td height="31"><strong>Semester:</strong></td>
                        <td><?php echo $Semester;?></td>
                      </tr>
                      <tr>
                        <td height="31"><strong>Sex:</strong></td>
                        <td><?php echo $StudSex;?></td>
                      </tr>
                      
					  <tr>
                        <td height="34"><strong>Change Year :</strong></td>
                        <td><label>
                                   <select name="cmbYear" id="cmbYear">
                                       <option>1</option>
                                       <option>2</option>
                                       <option>3</option>
                                       <option>4</option>
                                       <option>5</option>
                                       <option>6</option>
                                       <option>7</option>

																			
                          </select>
                        </label></td>
                      </tr>
                        <tr>
                        <td height="34"><strong>Change Semester :</strong></td>
                        <td><label>
                          <select name="cmbSem" id="cmbSem">
                            <option>1</option>
                            <option>2</option>
                          </select>
                        </label></td>
                      </tr>
                      <tr>
                        <td height="34"><strong>Change Status:</strong></td>
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
			<h1>&nbsp;</h1>
			
	
	  </div>
		
		<div id="footerline"></div>
	</div>

	<div id="footer">Copyright &copy; 2022 SU Online examination system .  All rights reserved.</div>	
</div>


</body>
</html>