<?php
require '../config/database.php';
session_start();

// Determine the action from the URL or form
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        createUser($conn);
        break;
    case 'edit':
        editUser($conn);
        break;
    case 'delete':
        deleteUser($conn);
        break;
    default:
        echo "Invalid action!";
}

// Function to create user
function createUser($conn) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstName = trim($_POST['first_name']);
        $lastName = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $bio = trim($_POST['bio']);
        $photo = $_FILES['photo'];

        $errors = [];

        // Handle photo upload
        $photoName = null;
        if (!empty($photo['name'])) {
            $uploadDir = "../assets/images";
            $photoName = uniqid() . "-" . basename($photo["name"]);
            $targetFile = $uploadDir . $photoName;

            // Ensure file is moved to the correct directory
            if (!move_uploaded_file($photo["tmp_name"], $targetFile)) {
                $errors[] = "Error uploading photo.";
            }
        }

        // Generate user ID and password
        $userId = uniqid("U");
        $rawPassword = bin2hex(random_bytes(4));
        $hashedPassword = md5($rawPassword);

        // Insert user into the database
        $stmt = $conn->prepare("INSERT INTO users (Id, first_name, last_name, email, password, bio, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $userId, $firstName, $lastName, $email, $hashedPassword, $bio, $photoName);

        if ($stmt->execute()) {
            header("Location: ../pages/dashboard.php?msg=User created successfully with password $rawPassword");
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

// Function to edit user
function editUser($conn) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userId = $_POST['user_id'];
        $firstName = trim($_POST['first_name']);
        $lastName = trim($_POST['last_name']);
        $email = trim($_POST['email']);
        $bio = trim($_POST['bio']);

        $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, bio = ? WHERE Id = ?");
        $stmt->bind_param("sssss", $firstName, $lastName, $email, $bio, $userId);

        if ($stmt->execute()) {
            header("Location: ../pages/dashboard.php?msg=User updated successfully");
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

// Function to delete user
function deleteUser($conn) {
    $userId = $_GET['user_id'] ?? '';

    if (!empty($userId)) {
        $stmt = $conn->prepare("DELETE FROM users WHERE Id = ?");
        $stmt->bind_param("s", $userId);

        if ($stmt->execute()) {
            header("Location: ../pages/dashboard.php?msg=User deleted successfully");
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>