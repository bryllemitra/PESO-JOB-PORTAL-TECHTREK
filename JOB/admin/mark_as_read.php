<?php
session_start();
include '../includes/config.php';

if ($_SESSION['role'] === 'admin') {
    $id = $_POST['id'];
    $query = "UPDATE applications SET is_read = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "Notification marked as read.";
}
?>