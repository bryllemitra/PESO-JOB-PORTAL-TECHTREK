<?php
session_start();
include '../includes/config.php'; // Include DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['is_saved' => false]);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $job_id = json_decode(file_get_contents('php://input'))->job_id;

    // Check if the job is already saved
    $stmt = $conn->prepare("SELECT id FROM saved_jobs WHERE user_id = ? AND job_id = ?");
    $stmt->bind_param("ii", $user_id, $job_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Return whether the job is saved
    echo json_encode(['is_saved' => $result->num_rows > 0]);
}
?>