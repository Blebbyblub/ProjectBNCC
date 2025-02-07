<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}

include '../config/database.php';

// ID
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_GET['id'];

//Gambar user
$stmt = $conn->prepare("SELECT photo FROM users WHERE id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $image_path = "../assets/images/" . $user['photo']; // Adjust based on your storage structure

    //Hapus
    $delete_stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $delete_stmt->bind_param("s", $user_id);

    if ($delete_stmt->execute()) {
        //Hapus gambar
        if (!empty($user['photo']) && file_exists($image_path)) {
            unlink($image_path);
        }

        header("Location: dashboard.php?message=User deleted successfully");
        exit();
    } else {
        echo "Error deleting user: " . $conn->error;
    }
} else {
    echo "User not found!";
}
?>
