<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

//ID
$userId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$userId) {
    echo "User ID is required.";
    exit();
}

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "User not found.";
    exit();
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User Profile</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="profile-container">
        <h1>User Profile</h1>

        <label>First Name:</label>
        <input type="text" value="<?= htmlspecialchars($user['first_name']); ?>" readonly>

        <label>Last Name:</label>
        <input type="text" value="<?= htmlspecialchars($user['last_name']); ?>" readonly>

        <label>Email:</label>
        <input type="email" value="<?= htmlspecialchars($user['email']); ?>" readonly>

        <label>Bio:</label>
        <textarea readonly><?= htmlspecialchars($user['bio']); ?></textarea>

        <?php if (!empty($user['photo'])): ?>
            <label>Photo:</label>
            <img src="../assets/images/<?= htmlspecialchars($user['photo']); ?>" alt="User Photo" class="profile-photo">
        <?php endif; ?>

        <a href="dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>
