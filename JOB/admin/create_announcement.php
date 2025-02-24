<?php
include '../includes/config.php'; // Include your database connection file
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

// Start output buffering to prevent headers already sent issues
ob_start();

// Insert into database
$query = "INSERT INTO announcements (title, content) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $title, $content);

if ($stmt->execute()) {
    // Clear any existing output and display only the modal
    ob_clean(); // Clear the output buffer

    echo "
    <!-- Include Bootstrap CSS -->
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>

    <!-- Modal Structure -->
    <div class='modal fade show' id='successModal' tabindex='-1' aria-labelledby='successModalLabel' aria-hidden='false' style='display: block; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;'>
        <div class='modal-dialog modal-dialog-centered'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='successModalLabel'>Success!</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick=\"window.location.href='announcement.php'\"></button>
                </div>
                <div class='modal-body'>
                    Announcement created successfully!
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-primary' onclick=\"window.location.href='announcement.php'\">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add a backdrop for the modal -->
    <div class='modal-backdrop fade show' style='position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9998;'></div>

    <!-- Include Bootstrap JS and Popper.js -->
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js'></script>
    ";

    exit(); // Stop further execution of the script
} else {
    echo "<script>alert('Error creating announcement. Please try again.');</script>";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Announcement - Zamboanga City PESO Job Portal</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Custom Scrollbar for Textareas */
        textarea {
            resize: none; /* Disable manual resizing */
            overflow: hidden; /* Hide scrollbar until needed */
            min-height: 6rem; /* Set a minimum height for better usability */
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col items-center justify-center">

    <div class="max-w-xl w-full p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-center mb-6 text-[#1976d2]">Create New Announcement</h1>

        <form method="POST" action="" class="space-y-4">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2]" required>
            </div>

            <!-- Content -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                <textarea id="content" name="content" rows="1" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2] auto-expand" required></textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-between">
                <button type="submit" class="w-full py-2 px-4 bg-blue-900 hover:bg-blue-800 text-white font-semibold rounded-md shadow-md transition duration-300 ease-in-out">
                <i class="fas fa-upload me-2"></i>Post Announcement
                </button>
                <a href="announcement.php" class="ml-4 w-full py-2 px-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-md shadow-md transition duration-300 ease-in-out flex items-center justify-center">
                <i class="fa-solid fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>

    <script>
        // Auto-expand textarea functionality
        document.querySelectorAll('.auto-expand').forEach(textarea => {
            // Set initial height based on content
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';

            // Add event listener for dynamic expansion
            textarea.addEventListener('input', function () {
                this.style.height = 'auto'; // Reset height to recalculate
                this.style.height = this.scrollHeight + 'px'; // Set height to fit content
            });
        });
    </script>


</body>
</html>