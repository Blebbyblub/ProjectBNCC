<?php
session_start();
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // MD5 hashing sesuai ketentuan proyek

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['user'] = $result->fetch_assoc();

        // Jika Remember Me dicentang, simpan cookie selama 7 hari
        if (isset($_POST['remember'])) {
            setcookie("user_email", $email, time() + (7 * 24 * 60 * 60), "/");
            setcookie("user_password", $password, time() + (7 * 24 * 60 * 60), "/");
        }

        header("Location: ../pages/dashboard.php");
    } else {
        header("Location: ../pages/login.php?error=Email atau password salah!");
    }
}
?>
