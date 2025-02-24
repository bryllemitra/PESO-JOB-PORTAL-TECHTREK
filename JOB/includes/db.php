<?php
include '../includes/config.php';

function getAboutContent() {
    global $conn;
    $query = "SELECT * FROM about ORDER BY created_at DESC";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}
?>