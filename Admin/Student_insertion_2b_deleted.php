<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Insert Student</title>
</head>

<body>
    <div class="InsertStudent">
	              <form id="form1" name="form1" method="post" action="InsertStudent.php">
	                <table width="100%" height="259" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td>ID:</td>
                  <td><span id="sprytextfield1">
                          <label>
                          <input type="text" name="txtRoll" id="txtRoll" />
                          </label>
                        <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                      </tr>
                      <tr>
                        <td>Name:</td>
                        <td><span id="sprytextfield2">
                          <label>
                          <input type="text" name="txtName" id="txtName" />
                          </label>
                        <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                      </tr>
                            <tr>
                        <td height="35">Department:</td>
                        <td><select name="cmbDept" id="cmbDept">
                          
<?php
do {  
?>
                                <option value="<?php echo $row_Recordsetd['deptno']?>"><?php echo $row_Recordsetd['dept_name']?></option>
                                <?php
} while ($row_Recordsetd = mysql_fetch_assoc($Recordsetd));
  $rows = mysql_num_rows($Recordsetd);
  if($rows > 0) {
      mysql_data_seek($Recordsetd, 0);
	  $row_Recordsetd = mysql_fetch_assoc($Recordsetd);
  }
?>
                          
                        </select></td>
                      </tr>
                            <tr>
                                <td height="33">Year:</td>
                                <td><span id="sprytextfield3">
                               <label>
                                   <select name="cmbYear" id="cmbYear">
                                       <option>1</option>
                                       <option>2</option>
                                       <option>3</option>
                                       <option>4</option>
                                       <option>5</option>
                                   </select>
                               </label></span></td>
                            </tr>
                                <tr><td height="33">Sex:</td>
                                <td><span id="sprytextfield3">
                               <label>
                                   <select name="cmbSex" id="cmbSex">
                                       <option>Male</option>
                                       <option>Female</option>
                                       
                                   </select>
                               </label></span></td>
                            </tr>
                      <tr>
                        <td height="35">Semester:</td>
                        <td><select name="cmbSem" id="cmbSem">
                          <option>1</option>
                          <option>2</option>
                          
                        </select></td>
                      </tr>
                      <tr>
                        <td height="33">Email:</td>
                        <td><span id="sprytextfield3">
                          <label>
                          <input type="text" name="txtEmail" id="txtEmail" />
                          </label>
                        <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                      </tr>
                     
                            
                      <tr>
                        <td>User Name:</td>
                        <td><span id="sprytextfield5">
                          <label>
                          <input type="text" name="txtUserName" id="txtUserName" />
                          </label>
                        <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                      </tr>
                      <tr>
                        <td>Password:</td>
                        <td><span id="sprytextfield6">
                          <label>
                          <input type="password" name="txtPassword" id="txtPassword" />
                          </label>
                        <span class="textfieldRequiredMsg">A value is required.</span></span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><label>
                          <input type="submit" name="button" id="button" value="Submit" />
                        </label></td>
                      </tr>
                    </table>
                  </form>
                </div>
<?php

	$txtRoll=$_POST['txtRoll'];
        $txtName=$_POST['txtName'];
        $cmbDept=$_POST['cmbDept'];
        $cmbYear=$_POST['cmbYear'];
	$cmbSem=$_POST['cmbSem'];
        $cmbSex=$_POST['cmbSex'];
	$txtEmail=$_POST['txtEmail'];
	$txtUserName=$_POST['txtUserName'];
	$txtPassword=$_POST['txtPassword'];
	$Status="Active";
	// Establish Connection with MYSQL
	$con = mysql_connect ("localhost","root");
	// Select Database
	mysql_select_db("oes", $con);
	// Specify the query to Insert Record
	$sql = "insert into student(Stud_ID,Stud_Name,Department,year,semister,Stud_Sex,email,Mobile,username,password,Status) 	"
                . "values('".$txtRoll."','".$txtName."','".$cmbDept."','".$cmbYear."','".$cmbSem."','".$cmbSex."','".$txtEmail."','".$txtUserName."','".$txtPassword."','Active')";
	// execute query
	mysql_query ($sql,$con);
	// Close The Connection
	mysql_close ($con);
	echo '<script type="text/javascript">alert("New Student Inserted Succesfully");window.location=\'Student.php\';</script>';

?>
</body>
</html>
