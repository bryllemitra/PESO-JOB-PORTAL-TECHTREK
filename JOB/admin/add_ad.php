<?php
include '../includes/config.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $link_url = $_POST['link_url'];

    // Handle file upload
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image_file'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB limit

        // Validate file type and size
        if (!in_array($file['type'], $allowed_types)) {
            echo "<script>alert('Invalid file type. Only JPG, PNG, and GIF files are allowed.');</script>";
            exit;
        }
        if ($file['size'] > $max_size) {
            echo "<script>alert('File size exceeds the maximum limit of 2MB.');</script>";
            exit;
        }

        // Generate a unique filename
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid('ad_', true) . '.' . $file_extension;

        // Move the uploaded file to the uploads folder
        $upload_path = __DIR__ . '/../uploads/' . $new_filename;
        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            echo "<script>alert('Failed to upload the file.');</script>";
            exit;
        }
    } else {
        echo "<script>alert('No file uploaded or an error occurred during upload.');</script>";
        exit;
    }

// Start output buffering to prevent headers already sent issues
ob_start();

// Insert into the database
$query = "INSERT INTO ads (title, description, image_file, link_url, created_at) VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param('ssss', $title, $description, $new_filename, $link_url);

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
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick=\"window.location.href='../pages/index.php'\"></button>
                </div>
                <div class='modal-body'>
                    Advertisement added successfully!
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-primary' onclick=\"window.location.href='../pages/index.php'\">OK</button>
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
    echo "<script>alert('Error adding ad: " . htmlspecialchars($stmt->error) . "');</script>";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Advertisement - Admin Panel</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Custom Scrollbar for Textareas */
        textarea {
            resize: none; /* Disable manual resizing */
            overflow: hidden; /* Hide scrollbar until needed */
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col items-center justify-center">

    <div class="max-w-xl w-full p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold text-center mb-6 text-[#1976d2]"><i class="fas fa-plus-circle me-2"></i>Add New Advertisement</h1>

        <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2]" placeholder="Enter ad title" required>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" rows="1" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2] auto-expand" placeholder="Enter ad description" required></textarea>
            </div>

            <!-- Upload Image -->
            <div>
                <label for="image_file" class="block text-sm font-medium text-gray-700">Upload Image</label>
                <input type="file" id="image_file" name="image_file" accept="image/jpeg, image/png, image/gif" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2]" required>
            </div>

            <!-- Clickable URL -->
            <div>
                <label for="link_url" class="block text-sm font-medium text-gray-700">Clickable URL</label>
                <input type="url" id="link_url" name="link_url" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2]" placeholder="Enter the URL to redirect to" required>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-between">
                <button type="submit" class="w-full py-2 px-4 bg-blue-900 hover:bg-blue-800 text-white font-semibold rounded-md shadow-md transition duration-300 ease-in-out">
                    <i class="fas fa-upload me-2"></i>Post Advertisement
                </button>
                <a href="../pages/index.php" class="ml-4 w-full py-2 px-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-md shadow-md transition duration-300 ease-in-out flex items-center justify-center">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </form>
    </div>

    <script>
        // Auto-expand textarea functionality
        document.querySelectorAll('.auto-expand').forEach(textarea => {
            textarea.addEventListener('input', function () {
                this.style.height = 'auto'; // Reset height to recalculate
                this.style.height = this.scrollHeight + 'px'; // Set height to fit content
            });
        });
    </script>


</body>
</html>

