<?php
session_start();
include '../includes/config.php'; // Include DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'You must be logged in to save jobs.']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $job_id = json_decode(file_get_contents('php://input'))->job_id;

    // Check if the job is already saved
    $stmt = $conn->prepare("SELECT id FROM saved_jobs WHERE user_id = ? AND job_id = ?");
    $stmt->bind_param("ii", $user_id, $job_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Unsave the job
        $delete_stmt = $conn->prepare("DELETE FROM saved_jobs WHERE user_id = ? AND job_id = ?");
        $delete_stmt->bind_param("ii", $user_id, $job_id);
        $delete_stmt->execute();
        echo json_encode(['status' => 'unsaved']);
    } else {
        // Save the job
        $insert_stmt = $conn->prepare("INSERT INTO saved_jobs (user_id, job_id) VALUES (?, ?)");
        $insert_stmt->bind_param("ii", $user_id, $job_id);
        $insert_stmt->execute();
        echo json_encode(['status' => 'saved']);
    }
}
?>