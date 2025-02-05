<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}
include '../config/database.php';

// Get user ID
$user_id = $_GET['id'];

// Delete user
$delete_sql = "DELETE FROM users WHERE id='$user_id'";
if ($conn->query($delete_sql)) {
    header("Location: dashboard.php");
    exit();
} else {
    echo "Error deleting user: " . $conn->error;
}
?>
