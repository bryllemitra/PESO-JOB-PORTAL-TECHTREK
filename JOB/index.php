<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: pages/index.php");
        exit();
    } else {
        header("Location: pages/index.php");
        exit();
    }
} else {
    // If not logged in, go to job listings
    header("Location: pages/index.php");
    exit();
}
?>