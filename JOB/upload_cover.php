<?php
// Start the session and include the config file
session_start();
include 'includes/config.php'; // Path to your config.php file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Define the upload directory
$upload_dir = 'uploads/'; // Adjust the upload directory path as needed
$upload_file = $upload_dir . basename($_FILES['cover_photo']['name']);
$upload_ok = 1;
$image_file_type = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));

// Check if the file is a valid image
if (isset($_POST['submit'])) {
    $check = getimagesize($_FILES['cover_photo']['tmp_name']);
    if ($check !== false) {
        $upload_ok = 1;
    } else {
        echo "File is not an image.";
        $upload_ok = 0;
    }

    // Check file size (5MB limit)
    if ($_FILES['cover_photo']['size'] > 5000000) {
        echo "Sorry, your file is too large.";
        $upload_ok = 0;
    }

    // Only allow certain file formats (JPEG, PNG, JPG, GIF)
    if ($image_file_type != "jpg" && $image_file_type != "jpeg" && $image_file_type != "png" && $image_file_type != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $upload_ok = 0;
    }

    // Check if the file is ready to be uploaded
    if ($upload_ok == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES['cover_photo']['tmp_name'], $upload_file)) {
            // Store the path to the new cover photo in the session or database
            $cover_photo = basename($_FILES['cover_photo']['name']);
            $cover_photo_path = $upload_dir . $cover_photo;

            // Update the cover photo in the database (optional)
            $user_id = $_SESSION['user_id'];
            $query = "UPDATE users SET cover_photo = '$cover_photo_path' WHERE id = '$user_id'";
            $result = mysqli_query($conn, $query);

            if ($result) {
                echo "The file ". htmlspecialchars(basename($_FILES['cover_photo']['name'])). " has been uploaded.";
                header("Location: profile.php"); // Redirect to profile or desired page
            } else {
                echo "Error updating the cover photo in the database.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
