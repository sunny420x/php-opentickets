<?php
session_start();
unset($_SESSION['user_session']);
setcookie("alert", "successfully_logout");
header("Location: login.php");
die();
?>