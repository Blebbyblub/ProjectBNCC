<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include '../config/database.php';

// Ambil data Admin dari session
$user = $_SESSION['user'];

// Handle update profile jika ada request POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $bio = $_POST['bio'];

    // Update database
    $update_sql = "UPDATE users SET first_name='$first_name', last_name='$last_name', bio='$bio' WHERE id='A001'";
    if ($conn->query($update_sql)) {
        $_SESSION['user']['first_name'] = $first_name;
        $_SESSION['user']['last_name'] = $last_name;
        $_SESSION['user']['bio'] = $bio;
        $success_message = "Profile updated successfully!";
    } else {
        $error_message = "Error updating profile: " . $conn->error;
    }
}
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
    <div class="profile-container">
        <h1>Profile Page</h1>

        <?php if (isset($success_message)) echo "<p class='success'>$success_message</p>"; ?>
        <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>

        <form method="POST">
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?= $user['first_name']; ?>" required>

            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?= $user['last_name']; ?>" required>

            <label>Email:</label>
            <input type="email" value="<?= $user['email']; ?>" disabled>

            <label>Bio:</label>
            <textarea name="bio"><?= $user['bio']; ?></textarea>

            <button type="submit">Update Profile</button>
        </form>

        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
