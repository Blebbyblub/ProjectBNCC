<?php
session_start();
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['user'] = $result->fetch_assoc();

        //Remember me
        if (isset($_POST['remember'])) {
            setcookie("user_email", $email, time() + (7 * 24 * 60 * 60), "/"); // 7 days
            setcookie("user_password", $_POST['password'], time() + (7 * 24 * 60 * 60), "/");
        } else {
            setcookie("user_email", "", time() - 3600, "/");
            setcookie("user_password", "", time() - 3600, "/");
        }

        header("Location: ../pages/dashboard.php");
    } else {
        header("Location: ../pages/login.php?error=Invalid email or password");
    }
}
?>
