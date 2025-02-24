<?php
include '../includes/config.php'; // Include DB connection

// Start the session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Handle Cover Photo Removal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

            // Return success response
            echo json_encode(['success' => true, 'message' => 'Cover photo removed successfully.']);
            exit();
        } else {
            // Return error response
            echo json_encode(['success' => false, 'message' => 'Error removing cover photo from the database.']);
            exit();
        }
    } else {
        // Return error response if no cover photo exists
        echo json_encode(['success' => false, 'message' => 'No cover photo found to remove.']);
        exit();
    }
} else {
    // Return error response for invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}
?>