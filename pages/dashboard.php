<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect jika belum login
    exit();
}
include '../config/database.php';

// Ambil daftar user, kecuali admin
$sql = "SELECT * FROM users WHERE id != 'A001'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $_SESSION['user']['first_name']; ?>!</h1>
        
        <input type="text" id="searchInput" placeholder="Search users...">
        
        <table border="1" id="userTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['first_name'] . " " . $row['last_name']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td>
                        <a href="user_edit.php?id=<?= $row['id']; ?>">Edit</a> |
                        <a href="user_delete.php?id=<?= $row['id']; ?>" class="delete-link">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="user_create.php">Add New User</a>
        <a href="../process/logout_process.php">Logout</a>
    </div>

    <script src="../assets/js/script.js"></script>
</body>
</html>
