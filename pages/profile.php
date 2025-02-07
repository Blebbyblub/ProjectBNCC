<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include '../config/database.php';

// Get the logged-in user's data from the session
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <a href="../process/logout_process.php" class="logout">Logout</a>
    <div class="profile-container">
        <h1>Profile Page</h1>

        <form>
            <label>First Name:</label>
            <input type="text" value="<?= htmlspecialchars($user['first_name']); ?>" readonly>

            <label>Last Name:</label>
            <input type="text" value="<?= htmlspecialchars($user['last_name']); ?>" readonly>

            <label>Email:</label>
            <input type="email" value="<?= htmlspecialchars($user['email']); ?>" readonly>

            <label>Bio:</label>
            <textarea readonly><?= htmlspecialchars($user['bio']); ?></textarea>
        </form>

        <a href="dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>
