<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}

// Check if cookies exist
$stored_email = isset($_COOKIE['user_email']) ? $_COOKIE['user_email'] : "";
$stored_password = isset($_COOKIE['user_password']) ? $_COOKIE['user_password'] : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="../process/login_process.php" method="POST">
            <label>Email:</label>
            <input type="email" name="email" value="<?= $stored_email; ?>" required>

            <label>Password:</label>
            <input type="password" name="password" value="<?= $stored_password; ?>" required>

            <label>
                <input type="checkbox" name="remember" <?= $stored_email ? 'checked' : ''; ?>> Remember Me
            </label>

            <button type="submit">Login</button>
        </form>

        <?php if (isset($_GET['error'])) echo "<p class='error'>".$_GET['error']."</p>"; ?>
    </div>
</body>
</html>
