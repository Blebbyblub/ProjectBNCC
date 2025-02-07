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
    <a href="../process/logout_process.php" class="logout">Logout</a>
    <div class="dashboard-container">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['user']['first_name']); ?>!</h1>

        <a href="profile.php" class="button">Profile</a>

        <input type="text" id="searchInput" placeholder="Search users...">
        
        <a href="user_create.php" class="button">Add New User</a>

        <table id="userTable">
            <tr>
                <th>No</th>
                <th>Photo</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Bio</th>
                <th>Actions</th>
            </tr>
            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td>
                    <?php if (!empty($row['photo'])): ?>
                        <img src="../assets/images/<?= htmlspecialchars($row['photo']); ?>" alt="User Photo" width="100">
                    <?php else: ?>
                        No Photo
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td><?= nl2br(htmlspecialchars($row['bio'])); ?></td>
                <td>
                    <a href="user_edit.php?id=<?= $row['id']; ?>">Edit</a>
                    <a href="user_delete.php?id=<?= $row['id']; ?>" class="delete-link">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                const searchValue = this.value.toLowerCase().trim();
                const tableRows = document.querySelectorAll('#userTable tr:not(:first-child)');

                tableRows.forEach(row => {
                    const fullNameCell = row.cells[2]; // Select the Full Name column
                    if (fullNameCell) {
                        const fullName = fullNameCell.textContent.toLowerCase();
                        row.style.display = fullName.includes(searchValue) ? '' : 'none';
                    }
                });
            }
        });
    </script>
</body>
</html>
