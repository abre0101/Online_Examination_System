<?php
session_start();
if(isset($_SESSION['username'])){
    header("Location: index-modern.php");
    exit();
} else {
    header("Location: ../index-modern.php");
    exit();
}
?>
