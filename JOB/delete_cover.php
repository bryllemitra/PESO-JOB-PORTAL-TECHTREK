<?php
// Start the session and include the config file
session_start();
include 'includes/config.php'; // Path to your config.php file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get current cover photo path from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT cover_photo FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $current_cover_photo = $row['cover_photo'];

    // Check if cover photo exists and delete it
    if ($current_cover_photo && file_exists($current_cover_photo)) {
        unlink($current_cover_photo); // Delete the file

        // Update database to remove the cover photo
        $query = "UPDATE users SET cover_photo = NULL WHERE id = '$user_id'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "Cover photo deleted successfully.";
            header("Location: profile.php"); // Redirect after deletion
        } else {
            echo "Error removing the cover photo from the database.";
        }
    } else {
        echo "No cover photo to delete.";
    }
} else {
    echo "Error fetching user cover photo.";
}
?>
