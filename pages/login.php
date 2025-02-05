<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="../process/login_process.php" method="POST">
            <label>Email:</label>
            <input type="email" name="email" placeholder="Enter your email" required>

            <label>Password:</label>
            <input type="password" name="password" placeholder="Enter your password" required>

            <label>
                <input type="checkbox" name="remember"> Remember Me
            </label>

            <button type="submit">Login</button>
        </form>
        
        <?php
        if (isset($_GET['error'])) {
            echo "<p style='color:red;'>".$_GET['error']."</p>";
        }
        ?>
    </div>
</body>
</html>
