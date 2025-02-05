<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}
include '../config/database.php';

// Get user data
$user_id = $_GET['id'];
$user_sql = "SELECT * FROM users WHERE id='$user_id'";
$user_result = $conn->query($user_sql);
if ($user_result->num_rows == 0) {
    header("Location: dashboard.php");
    exit();
}
$user = $user_result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];

    // Ensure email is unique
    $check_email = "SELECT * FROM users WHERE email='$email' AND id!='$user_id'";
    $email_result = $conn->query($check_email);

    if ($email_result->num_rows > 0) {
        $error_message = "Email already exists!";
    } else {
        $update_sql = "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email', bio='$bio' WHERE id='$user_id'";
        if ($conn->query($update_sql)) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "Error updating user: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Edit User</h2>
        <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>
        <form action="../process/user_process.php?action=edit" method="POST">
            <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
            <input type="text" name="first_name" value="<?php echo $firstName; ?>" required>
            <input type="text" name="last_name" value="<?php echo $lastName; ?>" required>
            <input type="email" name="email" value="<?php echo $email; ?>" required>
            <textarea name="bio"><?php echo $bio; ?></textarea>
            <button type="submit">Update User</button>
        </form>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>