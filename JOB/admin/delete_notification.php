<?php
session_start();
include '../includes/config.php';

if ($_SESSION['role'] === 'admin') {
    $id = $_POST['id'];

    // Update the dismissed column instead of deleting the record
    $query = "UPDATE applications SET dismissed = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Notification dismissed.";
        } else {
            echo "Notification not found.";
        }

        $stmt->close();
    } else {
        echo "Error preparing statement.";
    }
} else {
    echo "Unauthorized access.";
}
?>