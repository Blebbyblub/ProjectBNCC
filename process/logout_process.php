<?php
session_start();
session_destroy();

//Hapus kue
setcookie("user_email", "", time() - 3600, "/");
setcookie("user_password", "", time() - 3600, "/");

header("Location: ../pages/login.php");
?>