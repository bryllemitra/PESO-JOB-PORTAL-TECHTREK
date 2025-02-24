<?php
include '../includes/config.php';
include '../includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied");
}

// Validate Job ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid Job ID");
}

$id = $_GET['id'];

// Fetch job details
$stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$jobResult = $stmt->get_result();
$job = $jobResult->fetch_assoc();

if (!$job) {
    die("Job not found.");
}

// Fetch job categories
$category_stmt = $conn->prepare("SELECT id, name FROM categories");
$category_stmt->execute();
$categories = $category_stmt->get_result();

// Define allowed image file types for both thumbnail and photo
$allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

// Handle job update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $responsibilities = $_POST['responsibilities'];
    $requirements = $_POST['requirements'];
    $preferred_qualifications = $_POST['preferred_qualifications'];
    $category_id = $_POST['category']; // Category ID from dropdown
    $location = $_POST['location']; // Location from input

    // Fetch category name based on selected category_id
    $cat_stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
    $cat_stmt->bind_param("i", $category_id);
    $cat_stmt->execute();
    $cat_result = $cat_stmt->get_result();
    $category_row = $cat_result->fetch_assoc();
    $category_name = $category_row['name']; // Category name

    // Handle file upload (if new thumbnail is uploaded)
    $thumbnail_path = $job['thumbnail']; // Retain current thumbnail if no new one is uploaded
    if (!empty($_FILES['thumbnail']['name'])) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["thumbnail"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
                $thumbnail_path = "uploads/" . basename($_FILES["thumbnail"]["name"]);
            } else {
                echo "<script>alert('Error uploading thumbnail image.');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type for thumbnail. Only JPG, PNG, and GIF are allowed.');</script>";
        }
    }

    // Handle photo upload (if new photo is uploaded)
    $photo_path = $job['photo']; // Retain current photo if no new one is uploaded
    if (!empty($_FILES['photo']['name'])) {
        $photo_target_dir = "../uploads/";
        $photo_target_file = $photo_target_dir . basename($_FILES["photo"]["name"]);
        $photoFileType = strtolower(pathinfo($photo_target_file, PATHINFO_EXTENSION));
        if (in_array($photoFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $photo_target_file)) {
                $photo_path = "uploads/" . basename($_FILES["photo"]["name"]);
            } else {
                echo "<script>alert('Error uploading job photo.');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type for photo. Only JPG, PNG, and GIF are allowed.');</script>";
        }
    }

    // Start output buffering to prevent headers already sent issues
    ob_start();
    
    // Update job post
    $update_stmt = $conn->prepare("UPDATE jobs SET title = ?, description = ?, responsibilities = ?, requirements = ?, preferred_qualifications = ?, category = ?, category_id = ?, location = ?, thumbnail = ?, photo = ? WHERE id = ?");
    $update_stmt->bind_param("ssssssssssi", $title, $description, $responsibilities, $requirements, $preferred_qualifications, $category_name, $category_id, $location, $thumbnail_path, $photo_path, $id);
    
    if ($update_stmt->execute()) {
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
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick=\"window.location.href='job_list.php'\"></button>
                    </div>
                    <div class='modal-body'>
                        Job updated successfully!
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' onclick=\"window.location.href='job_list.php'\">OK</button>
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
        echo "<script>alert('Error updating job. Please try again.');</script>";
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job Post</title>
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
        <h1 class="text-2xl font-bold text-center mb-6 text-[#1976d2]">Edit Job Post</h1>

        <form action="edit_job.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <!-- Job Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Job Title</label>
                <input type="text" name="title" id="title" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2]" value="<?= htmlspecialchars($job['title']) ?>" required>
            </div>

            <!-- Job Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Job Description</label>
                <textarea name="description" id="description" rows="1" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2] auto-expand" required><?= htmlspecialchars($job['description']) ?></textarea>
            </div>

            <!-- Job Responsibilities -->
            <div>
                <label for="responsibilities" class="block text-sm font-medium text-gray-700">Responsibilities</label>
                <textarea name="responsibilities" id="responsibilities" rows="1" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2] auto-expand"><?= htmlspecialchars($job['responsibilities']) ?></textarea>
            </div>

            <!-- Job Requirements -->
            <div>
                <label for="requirements" class="block text-sm font-medium text-gray-700">Requirements</label>
                <textarea name="requirements" id="requirements" rows="1" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2] auto-expand"><?= htmlspecialchars($job['requirements']) ?></textarea>
            </div>

            <!-- Preferred Qualifications -->
            <div>
                <label for="preferred_qualifications" class="block text-sm font-medium text-gray-700">Preferred Qualifications</label>
                <textarea name="preferred_qualifications" id="preferred_qualifications" rows="1" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2] auto-expand"><?= htmlspecialchars($job['preferred_qualifications']) ?></textarea>
            </div>

            <!-- Job Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Job Category</label>
                <select name="category" id="category" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2]" required>
                    <option value="">Select a category</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $job['category_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Job Location -->
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Job Location</label>
                <select name="location" id="location" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2]" required>
        <option value="">Select a location</option>
        <?php
        // Fetch barangay names from the database
        $query = "SELECT name FROM barangay ORDER BY name ASC";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // Loop through each row in the result set
            while ($row = $result->fetch_assoc()) {
                $barangayName = htmlspecialchars($row['name']); // Prevent XSS attacks
                echo "<option value=\"$barangayName\">$barangayName</option>";
            }
        } else {
            echo "<option value=\"\">No locations available</option>";
        }
        ?>
    </select>
            </div>

<!-- Job Thumbnail -->
<div>
    <label for="thumbnail" class="form-label">Job Thumbnail</label>
    <input type="file" name="thumbnail" id="thumbnail" class="form-control" required>
</div>


<!-- Job Photo -->
<div class="mb-3">
    <label for="photo" class="form-label">Job Photo</label>
    <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
</div>



            <!-- Submit Button -->
            <div class="flex justify-between">
                <button type="submit" class="w-full py-2 px-4 bg-blue-900 hover:bg-blue-800 text-white font-semibold rounded-md shadow-md transition duration-300 ease-in-out">
                    Update Job
                </button>
                <a href="job_list.php" class="ml-4 w-full py-2 px-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-md shadow-md transition duration-300 ease-in-out flex items-center justify-center">
                    Cancel
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
