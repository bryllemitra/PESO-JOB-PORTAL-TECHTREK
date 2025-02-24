<?php
session_start();
include '../includes/config.php';

// Ensure the user is logged in and not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] === 'admin') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$job_id = $_POST['job_id'] ?? null;

// Validate job ID
if (!$job_id || !is_numeric($job_id)) {
    echo "<div class='alert alert-danger text-center'>Invalid Job ID.</div>";
    exit();
}

// Check if the user has already applied for this job
$checkQuery = "SELECT * FROM applications WHERE user_id = ? AND job_id = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("ii", $user_id, $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<div class='alert alert-warning text-center'>You have already applied for this job.</div>";
    exit();
}

// Fetch user's resume file path (to validate "Attach from Profile" option)
$resume_query = "SELECT resume_file FROM users WHERE id = ?";
$stmt = $conn->prepare($resume_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resume_result = $stmt->get_result();
$user_data = $resume_result->fetch_assoc();
$has_resume = !empty($user_data['resume_file']);
$resume_file = $user_data['resume_file'];

// Handle resume attachment
$resume_attached = false;
if ($_POST['resume_option'] === 'existing') {
    // Use the existing resume file from the user's profile
    if (!$has_resume) {
        echo "<div class='alert alert-danger text-center'>No resume found in your profile. Please upload a new resume.</div>";
        exit();
    }
    $resume_attached = true;
    $resume_file_to_attach = $resume_file; // Use the existing resume file
} elseif ($_POST['resume_option'] === 'new' && isset($_FILES['resume']) && $_FILES['resume']['size'] > 0) {
    // Upload a new resume
    $target_dir = "../uploads/resumes/"; // Ensure this directory exists and is writable
    $target_file = $target_dir . basename($_FILES["resume"]["name"]);
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ['pdf', 'doc', 'docx'];

    // Validate file type
    if (!in_array($fileType, $allowed_types)) {
        echo "<div class='alert alert-danger text-center'>Only PDF, DOC, and DOCX files are allowed.</div>";
        exit();
    }

    // Validate file size (limit to 5MB)
    if ($_FILES["resume"]["size"] > 5 * 1024 * 1024) {
        echo "<div class='alert alert-danger text-center'>File size must not exceed 5MB.</div>";
        exit();
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
        $resume_attached = true;
        $resume_file_to_attach = $target_file; // Use the newly uploaded resume file
    } else {
        echo "<div class='alert alert-danger text-center'>Sorry, there was an error uploading your file.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger text-center'>You must select a valid resume option.</div>";
    exit();
}

// Insert the application into the database
if ($resume_attached) {
    $insertQuery = "INSERT INTO applications (user_id, job_id, resume_file, applied_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iis", $user_id, $job_id, $resume_file_to_attach);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success text-center'>Application submitted successfully!</div>";
        header("Location: job.php?id=$job_id&message=Application successful");
        exit();
    } else {
        echo "<div class='alert alert-danger text-center'>Error submitting application. Please try again later.</div>";
    }
}
?>