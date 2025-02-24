<?php
session_start();

// Include config.php for DB connection
require_once '../includes/config.php';

// Validate session
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo 'User not logged in.';
    exit;
}

$user_id = $_SESSION['user_id'];

// Validate input
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    http_response_code(400); // Bad Request
    echo 'Invalid notification ID.';
    exit;
}

$notification_id = (int)$_POST['id']; // Sanitize input

try {
    // Mark the notification as read
    $query = "UPDATE applications SET is_read = 1 WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$notification_id, $user_id]);

    // Check if any rows were affected
    if ($stmt->rowCount() > 0) {
        echo "Notification marked as read";
    } else {
        http_response_code(404); // Not Found
        echo 'Notification not found or already marked as read.';
    }
} catch (PDOException $e) {
    // Log the error and return a generic error message
    error_log('Database error in mark_as_read.php: ' . $e->getMessage());
    http_response_code(500); // Internal Server Error
    echo 'Error marking notification as read.';
}
?>