<?php
session_start();
session_destroy();

// Remove "Remember Me" cookies
setcookie("user_email", "", time() - 3600, "/");
setcookie("user_password", "", time() - 3600, "/");

header("Location: ../pages/login.php");
?>