<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include '../config/database.php';

// Get user ID from URL
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_GET['id'];

// Fetch user data securely
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows == 0) {
    header("Location: dashboard.php");
    exit();
}

$user = $user_result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $bio = $_POST['bio'];
    $email = $_POST['email'];

    // Check if email is unique
    $check_email_stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $check_email_stmt->bind_param("ss", $email, $user_id);
    $check_email_stmt->execute();
    $email_result = $check_email_stmt->get_result();

    if ($email_result->num_rows > 0) {
        $error_message = "Email already exists!";
    } else {
        // Handle image upload
        $photo_name = $user['photo']; // Keep existing photo by default
        if (!empty($_FILES['photo']['name'])) {
            $upload_dir = "../assets/images/";
            $new_photo_name = uniqid() . "-" . basename($_FILES["photo"]["name"]);
            $target_file = $upload_dir . $new_photo_name;

            // Validate file upload and move the file
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                // Delete old image if exists
                if (!empty($user['photo']) && file_exists($upload_dir . $user['photo'])) {
                    unlink($upload_dir . $user['photo']);
                }
                $photo_name = $new_photo_name;
            } else {
                $error_message = "Error uploading photo.";
            }
        }

        // Update user profile
        $update_stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, bio=?, photo=? WHERE id=?");
        $update_stmt->bind_param("ssssss", $first_name, $last_name, $email, $bio, $photo_name, $user_id);

        if ($update_stmt->execute()) {
            $success_message = "User profile updated successfully!";
        } else {
            $error_message = "Error updating profile: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Edit User</h1>

        <?php if (isset($success_message)) echo "<p class='success'>$success_message</p>"; ?>
        <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']); ?>" required>

            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']); ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

            <label>Bio:</label>
            <textarea name="bio"><?= htmlspecialchars($user['bio']); ?></textarea>

            <label>Profile Photo:</label>
            <?php if (!empty($user['photo'])): ?>
                <img src="../assets/images/<?= htmlspecialchars($user['photo']); ?>" alt="User Photo" width="100">
            <?php endif; ?>
            <input type="file" name="photo">

            <button type="submit">Update User</button>
        </form>

        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
