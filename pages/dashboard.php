<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['user']['first_name']; ?>!</h1>
    <p>This is your dashboard.</p>
    <a href="/process/logout_process.php">Logout</a>
</body>
</html>