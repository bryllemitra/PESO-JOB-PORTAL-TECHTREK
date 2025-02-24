<?php
session_start();
include '../includes/config.php'; // Include your database connection file

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "0"; // Return 0 if not an admin
    exit;
}

// Fetch unread message count
$query = "SELECT COUNT(*) AS unread_count FROM contacts WHERE is_read = 0";
$result = $conn->query($query);
$data = $result->fetch_assoc();
echo $data['unread_count'];
?>