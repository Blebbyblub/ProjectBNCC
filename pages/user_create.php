<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}
include '../config/database.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = uniqid();
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    $random_password = "User123"; // Default password
    $password_hash = md5($random_password);

    $photoName = null;

    // Handle photo upload
    if (!empty($_FILES['photo']['name'])) {
        $uploadDir = "../assets/images";
        $photoName = uniqid() . "-" . basename($_FILES["photo"]["name"]);
        $targetFile = $uploadDir . $photoName;

        if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
            $error_message = "Error uploading photo.";
        }
    }

    // Check if email already exists
    $check_email = "SELECT * FROM users WHERE email='$email'";
    $email_result = $conn->query($check_email);

    if ($email_result->num_rows > 0) {
        $error_message = "Email already exists!";
    } else {
        // Include the photo column in the insert query
        $sql = "INSERT INTO users (id, first_name, last_name, email, password, bio, photo)
                VALUES ('$id', '$first_name', '$last_name', '$email', '$password_hash', '$bio', '$photoName')";
        if ($conn->query($sql)) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create User</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Create User</h2>
        <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>
        <form action="../process/user_process.php?action=create" method="POST" enctype="multipart/form-data">
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <textarea name="bio" placeholder="Bio"></textarea>
            <input type="file" name="photo">
            <button type="submit">Create User</button>
        </form>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
