<?php
// *** Logout the current user.
$logoutGoTo = "../index.php";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['ID'] = NULL;
$_SESSION['Name'] = NULL;
$_SESSION['timeout'] = NULL;
unset($_SESSION['ID']);
unset($_SESSION['Name']);
unset($_SESSION['timeout']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
