<?php
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad_id = intval($_POST['ad_id']);
    $query = "DELETE FROM ads WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $ad_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>