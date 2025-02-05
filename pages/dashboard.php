<?php
session_start();
include '../config/database.php';

// If session is not set but cookies exist, auto-login the user
if (!isset($_SESSION['user']) && isset($_COOKIE['user_email']) && isset($_COOKIE['user_password'])) {
    $email = $_COOKIE['user_email'];
    $password = md5($_COOKIE['user_password']);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['user'] = $result->fetch_assoc();
    } else {
        header("Location: login.php");
        exit();
    }
}

// Redirect to login if session is still not set
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Fetch all users except admin
$sql = "SELECT * FROM users WHERE id != 'A001'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?= $_SESSION['user']['first_name']; ?>!</h1>

        <a href="profile.php" class="button">Profile</a>

        <input type="text" id="searchInput" placeholder="Search users...">
        
        <a href="user_create.php" class="button">Add New User</a>

            <table>
                <tr>
                    <th>No</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['first_name'] . " " . $row['last_name']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td>
                        <a href="user_edit.php?id=<?= $row['id']; ?>">Edit</a>
                        <a href="user_delete.php?id=<?= $row['id']; ?>" class="delete-link">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>

        <a href="../process/logout_process.php" class="logout">Logout</a>
    </div>

    <script src="../assets/js/script.js"></script>
</body>
</html>
