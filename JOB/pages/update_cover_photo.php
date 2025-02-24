<?php
include '../includes/config.php'; // Include DB connection

// Start the session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Debugging: Log the request method
error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);

// Handle Cover Photo Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['cover_photo'])) {
    error_log("Handling cover photo upload...");

    if ($_FILES["cover_photo"]["size"] == 0) {
        echo "<div class='alert alert-danger'>Please select a file to upload.</div>";
    } else {
        $target_dir = "../uploads/"; // Ensure this directory exists and is writable
        $file_name = uniqid() . '_' . basename($_FILES["cover_photo"]["name"]); // Generate unique file name
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the uploaded file is a valid image
        if (getimagesize($_FILES["cover_photo"]["tmp_name"])) {
            // Allow only certain image formats
            if ($imageFileType == "jpg" || $imageFileType == "jpeg" || $imageFileType == "png" || $imageFileType == "gif") {
                if (move_uploaded_file($_FILES["cover_photo"]["tmp_name"], $target_file)) {
                    // Update the database with the new cover photo path
                    $update_query = "UPDATE users SET cover_photo = ? WHERE id = ?";
                    $stmt = $conn->prepare($update_query);
                    $stmt->bind_param("si", $target_file, $user_id);

                    if ($stmt->execute()) {
                        error_log("Cover photo updated successfully.");
                        header("Location: profile.php?id=$user_id"); // Redirect to profile page
                        exit();
                    } else {
                        echo "<div class='alert alert-danger'>Error updating cover photo in the database.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Only JPG, JPEG, PNG, and GIF files are allowed.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>File is not a valid image.</div>";
        }
    }
}

// Handle Cover Photo Removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_cover_photo'])) {
    error_log("Handling cover photo removal...");

    // Check if a cover photo exists
    $query = "SELECT cover_photo FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!empty($user['cover_photo'])) {
        // Clear the cover_photo column in the database
        $update_query = "UPDATE users SET cover_photo = NULL WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            // Optionally delete the file from the server
            if (file_exists($user['cover_photo'])) {
                unlink($user['cover_photo']);
            }

            error_log("Cover photo removed successfully.");
            header("Location: profile.php?id=$user_id"); // Redirect to profile page
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error removing cover photo from the database.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>No cover photo found to remove.</div>";
    }
}
?>