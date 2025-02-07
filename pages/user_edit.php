<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include '../config/database.php';

//ID
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_GET['id'];

//Ambil data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows == 0) {
    header("Location: dashboard.php");
    exit();
}

$user = $user_result->fetch_assoc();

//Submisi edit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $bio = trim($_POST['bio']);
    $email = trim($_POST['email']);

    //Validasi
    if (strlen($first_name) > 255) {
        $error_message = "First name must be 255 characters or less.";
    } elseif (strlen($last_name) > 255) {
        $error_message = "Last name must be 255 characters or less.";
    } else {
        //Cek email
        $check_email_stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check_email_stmt->bind_param("ss", $email, $user_id);
        $check_email_stmt->execute();
        $email_result = $check_email_stmt->get_result();

        if ($email_result->num_rows > 0) {
            $error_message = "Email already exists!";
        } else {
            //Upload gambar
            $photo_name = $user['photo'];
            if (!empty($_FILES['photo']['name'])) {
                $upload_dir = "../assets/images/";
                $new_photo_name = uniqid() . "-" . basename($_FILES["photo"]["name"]);
                $target_file = $upload_dir . $new_photo_name;

                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                    if (!empty($user['photo']) && file_exists($upload_dir . $user['photo'])) {
                        unlink($upload_dir . $user['photo']);
                    }
                    $photo_name = $new_photo_name;
                } else {
                    $error_message = "Error uploading photo.";
                }
            }

            //Update user profile
            $update_stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?, bio=?, photo=? WHERE id=?");
            $update_stmt->bind_param("ssssss", $first_name, $last_name, $email, $bio, $photo_name, $user_id);

            if ($update_stmt->execute()) {
                $success_message = "User profile updated successfully!";
            } else {
                $error_message = "Error updating profile: " . $conn->error;
            }
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
    <div class="edit-container">
        <h1>Edit User</h1>

        <?php if (isset($success_message)) echo "<p class='success'>$success_message</p>"; ?>
        <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']); ?>" required pattern=".{1,255}" title="First name must be 255 characters or less.">

            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']); ?>" required pattern=".{1,255}" title="Last name must be 255 characters or less.">

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

        <a href="dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>
