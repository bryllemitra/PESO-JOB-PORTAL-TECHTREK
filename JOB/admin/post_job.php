<?php

include '../includes/config.php';
include '../includes/header.php';

// Fetch job categories from the database
$category_stmt = $conn->prepare("SELECT id, name FROM categories");
$category_stmt->execute();
$categories = $category_stmt->get_result();

// Handle job posting
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $responsibilities = $_POST['responsibilities'];
    $requirements = $_POST['requirements'];
    $preferred_qualifications = $_POST['preferred_qualifications'];
    $category_id = $_POST['category'];
    $location = $_POST['location'];

    // Fetch category name based on selected category_id
    $cat_stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
    $cat_stmt->bind_param("i", $category_id);
    $cat_stmt->execute();
    $cat_result = $cat_stmt->get_result();
    $category_row = $cat_result->fetch_assoc();
    $category_name = $category_row['name'];

    // Handle Thumbnail Upload
    $thumbnail_path = null;
    if (!empty($_FILES['thumbnail']['name'])) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["thumbnail"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
                $thumbnail_path = "uploads/" . basename($_FILES["thumbnail"]["name"]);
            } else {
                echo "<script>alert('Error uploading thumbnail.');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type for thumbnail. Only JPG, PNG, and GIF are allowed.');</script>";
        }
    }

    // Handle Photo Upload
    $photo_path = null;
    if (!empty($_FILES['photo']['name'])) {
        $photo_target_file = $target_dir . basename($_FILES["photo"]["name"]);
        $photoFileType = strtolower(pathinfo($photo_target_file, PATHINFO_EXTENSION));

        if (in_array($photoFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $photo_target_file)) {
                $photo_path = "uploads/" . basename($_FILES["photo"]["name"]);
            } else {
                echo "<script>alert('Error uploading photo.');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type for photo. Only JPG, PNG, and GIF are allowed.');</script>";
        }
    }

    // Insert job post with location, thumbnail, photo, and other details
    $insert_stmt = $conn->prepare("INSERT INTO jobs (title, description, responsibilities, requirements, preferred_qualifications, category, category_id, location, thumbnail, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert_stmt->bind_param("ssssssssss", $title, $description, $responsibilities, $requirements, $preferred_qualifications, $category_name, $category_id, $location, $thumbnail_path, $photo_path);

    if ($insert_stmt->execute()) {
        echo "
        <!-- Include Bootstrap CSS -->
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' rel='stylesheet'>

        <!-- Modal Structure -->
        <div class='modal fade show' id='successModal' tabindex='-1' aria-labelledby='successModalLabel' aria-hidden='false' style='display: block; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;'>
            <div class='modal-dialog modal-dialog-centered'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='successModalLabel'>Success!</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick=\"window.location.href='../pages/browse.php'\"></button>
                    </div>
                    <div class='modal-body'>
                        Job posted successfully!
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' onclick=\"window.location.href='../pages/browse.php'\">OK</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add a backdrop for the modal -->
        <div class='modal-backdrop fade show' style='position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9998;'></div>

        <!-- Include Bootstrap JS and Popper.js -->
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js'></script>
        ";
        exit();
    } else {
        echo "<script>alert('Error posting job. Please try again.');</script>";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a New Job</title>
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
        <h1 class="text-2xl font-bold text-center mb-6 text-[#1976d2]">Post a New Job</h1>

        <form action="post_job.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <!-- Job Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Job Title</label>
                <input type="text" name="title" id="title" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2]" required>
            </div>

            <!-- Job Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Job Description</label>
                <textarea name="description" id="description" rows="1" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2] auto-expand" required></textarea>
            </div>

            <!-- Job Responsibilities -->
            <div>
                <label for="responsibilities" class="block text-sm font-medium text-gray-700">Duties and Responsibilities</label>
                <textarea name="responsibilities" id="responsibilities" rows="1" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2] auto-expand" required></textarea>
            </div>

            <!-- Job Requirements -->
            <div>
                <label for="requirements" class="block text-sm font-medium text-gray-700">Job Requirements</label>
                <textarea name="requirements" id="requirements" rows="1" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2] auto-expand" required></textarea>
            </div>

            <!-- Preferred Qualifications -->
            <div>
                <label for="preferred_qualifications" class="block text-sm font-medium text-gray-700">Preferred Qualifications</label>
                <textarea name="preferred_qualifications" id="preferred_qualifications" rows="1" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2] auto-expand"></textarea>
            </div>

            <!-- Job Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Job Category</label>
                <select name="category" id="category" class="mt-1 block w-full px-3 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:border-[#1976d2] focus:ring-1 focus:ring-[#1976d2]" required>
                    <option value="">Select a category</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
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

<!-- Attach Photo -->
<div>
    <label for="photo" class="form-label">Attach Photo</label>
    <input type="file" name="photo" id="photo" class="form-control" required>
</div>


            <!-- Submit Button -->
            <div class="flex justify-between">
                <button type="submit" class="w-full py-2 px-4 bg-blue-900 hover:bg-blue-800 text-white font-semibold rounded-md shadow-md transition duration-300 ease-in-out">
                <i class="fas fa-upload me-2"></i>Post Job
                </button>
                <button onclick="goBack()" type="button" class="ml-4 w-full py-2 px-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-md shadow-md transition duration-300 ease-in-out">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </button>
            </div>
        </form>
    </div>

    <script>
        function goBack() {
            window.history.back(); // Go back to the previous page in the browser history
        }

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