<?php
session_start();
include '../includes/config.php';

if ($_SESSION['role'] === 'admin') {
    $query = "SELECT a.id, u.first_name, u.last_name, j.title, a.applied_at, j.id AS job_id, a.is_read 
              FROM applications a
              JOIN users u ON a.user_id = u.id
              JOIN jobs j ON a.job_id = j.id
              WHERE a.dismissed = 0"; // Only fetch non-dismissed notifications
    $result = mysqli_query($conn, $query);
    $notifications = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Ensure applied_at is valid and not NULL
        $appliedAt = strtotime($row['applied_at']);
        if ($appliedAt === false) {
            $appliedAtFormatted = "undefined"; // Fallback for invalid timestamps
        } else {
            $appliedAtFormatted = date("M d, Y h:i A", $appliedAt); // Format timestamp if valid
        }
        $notifications[] = [
            'id' => $row['id'],
            'message' => "{$row['first_name']} {$row['last_name']} applied for '{$row['title']}'",
            'applied_at' => $appliedAtFormatted,
            'url' => "../admin/view_applicants.php?job_id={$row['job_id']}",
            'is_read' => (bool)$row['is_read']
        ];
    }
    echo json_encode($notifications);
}
?>