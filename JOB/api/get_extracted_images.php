<?php
include '../includes/config.php';

// Validate and sanitize user_id
$user_id = isset($_GET['user_id']) && is_numeric($_GET['user_id']) ? (int)$_GET['user_id'] : null;
if (!$user_id) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}

// Define the resume images directory
$resume_images_dir = realpath("../uploads/resume_images/");
if (!$resume_images_dir || !is_dir($resume_images_dir)) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}

// Fetch images for the user
$pattern = "$resume_images_dir/user_$user_id_*";
$images = glob($pattern);

// Convert file paths to absolute URLs
$base_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
$image_urls = array_map(function ($image) use ($base_url) {
    return str_replace('../', '/', $base_url . '/' . $image);
}, $images);

// Return the JSON response
header('Content-Type: application/json');
echo json_encode($image_urls);
exit();