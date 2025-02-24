    <?php
    // Start output buffering to prevent headers already sent errors
    ob_start();

    include '../includes/config.php'; // Include your database connection file
    include '../includes/header.php';

    // Fetch user role from session if available
    $user_role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
    $is_admin = ($user_role === 'admin'); // Check if the user is an admin

    // Fetch data from the database
    $query = "SELECT * FROM about WHERE id = 1"; // Assuming there's only one row for 'about'
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) === 0) {
        // Provide default values if no data is found
        $about = [
            'id' => 1,
            'cover_photo' => 'default_cover.jpg',
            'carousel_images' => '[]',
            'mission' => 'Default mission text.',
            'vision' => 'Default vision text.',
            
        ];
    } else {
        $about = mysqli_fetch_assoc($result);
    }

    // Handle form submissions for updating mission, vision, and uploading images
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($is_admin) { // Only allow admins to perform these actions
            if (isset($_POST['update_mission'])) {
                $mission = mysqli_real_escape_string($conn, $_POST['mission']);
                $updateQuery = "UPDATE about SET mission='$mission' WHERE id=1";
                mysqli_query($conn, $updateQuery);

                // Redirect using JavaScript instead of header()
                echo '<script>window.location.href = "about.php";</script>';
                exit();
            }


            if (isset($_POST['update_vision'])) {
                $vision = mysqli_real_escape_string($conn, $_POST['vision']);
                $updateQuery = "UPDATE about SET vision='$vision' WHERE id=1";
                mysqli_query($conn, $updateQuery);

                // Redirect using JavaScript instead of header()
                echo '<script>window.location.href = "about.php";</script>';
                exit();
            }


                // Update Hero Text
                if (isset($_POST['update_hero_text'])) {
                    $hero_text = mysqli_real_escape_string($conn, $_POST['hero_text']);
                    $updateQuery = "UPDATE about SET hero_text='$hero_text' WHERE id=1";
                    mysqli_query($conn, $updateQuery);
        
                    // Redirect using JavaScript instead of header()
                    echo '<script>window.location.href = "about.php";</script>';
                    exit();
                }

            if (isset($_FILES['cover_photo'])) {
                $target_dir = "../uploads/";
                $target_file = $target_dir . basename($_FILES["cover_photo"]["name"]);
                move_uploaded_file($_FILES["cover_photo"]["tmp_name"], $target_file);
                $updateQuery = "UPDATE about SET cover_photo='" . basename($_FILES["cover_photo"]["name"]) . "' WHERE id=1";
                mysqli_query($conn, $updateQuery);

                // Redirect using JavaScript instead of header()
                echo '<script>window.location.href = "about.php";</script>';
                exit();
            }

            if (isset($_POST['delete_cover_photo'])) {
                $updateQuery = "UPDATE about SET cover_photo='default_cover.jpg' WHERE id=1";
                mysqli_query($conn, $updateQuery);

                // Redirect using JavaScript instead of header()
                echo '<script>window.location.href = "about.php";</script>';
                exit();
            }
        }

    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>About Us - Zamboanga City PESO Job Portal</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <!-- Custom CSS -->
        <link rel="stylesheet" href="/JOB/assets/about.css">
    </head>
    <body>
    <!-- Hero Section -->
    <div class="hero-section" style="background-image: url('../uploads/<?php echo htmlspecialchars($about['cover_photo']); ?>'); background-size: cover; background-position: center;">
        <div class="hero-content">
            <h1>About Us</h1>
            <?php if ($is_admin): ?>
                <!-- Clickable Paragraph for Admins -->
                <p id="editableParagraph" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#editHeroTextModal">
                    <?php echo htmlspecialchars($about['hero_text'] ?? 'Empowering the community through employment opportunities.'); ?>
                </p>
            <?php else: ?>
                <!-- Static Paragraph for Non-Admins -->
                <p>
                    <?php echo htmlspecialchars($about['hero_text'] ?? 'Empowering the community through employment opportunities.'); ?>
                </p>
            <?php endif; ?>

            <?php if ($is_admin): ?>
                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#uploadCoverPhotoModal">
                    <i class="fas fa-camera"></i> Edit Cover
                </button>
            <?php endif; ?>
        </div>
    </div>



        <!-- Mission and Vision Section -->
        <div class="container mission-vision mt-5">
            <div class="row">
                <div class="col-md-6 fade-in">
                    <div class="mission-vision-card">
                        <i class="fas fa-bullseye"></i>
                        <h3>Our Mission</h3>
                        <p><?php echo htmlspecialchars($about['mission']); ?></p>
                        <?php if ($is_admin): ?>
                            <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#editMissionModal">
                                Edit Mission
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6 fade-in">
                    <div class="mission-vision-card">
                        <i class="fas fa-eye"></i>
                        <h3>Our Vision</h3>
                        <p><?php echo htmlspecialchars($about['vision']); ?></p>
                        <?php if ($is_admin): ?>
                            <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#editVisionModal">
                                Edit Vision
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>



        <!-- Modal for Editing Hero Text -->
        <div class="modal fade" id="editHeroTextModal" tabindex="-1" aria-labelledby="editHeroTextModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editHeroTextModalLabel">Edit Hero Text</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <textarea class="form-control" name="hero_text" rows="3" required><?php echo htmlspecialchars($about['hero_text'] ?? 'Empowering the community through employment opportunities.'); ?></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_hero_text" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


<!-- Upload Cover Photo Modal -->
<div class="modal fade" id="uploadCoverPhotoModal" tabindex="-1" aria-labelledby="uploadCoverPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <!-- Center modal -->
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" id="uploadCoverPhotoForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadCoverPhotoModalLabel">Upload Cover Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="file" class="form-control" name="cover_photo">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                    <button type="button" id="deleteCoverPhotoButton" class="btn btn-light">Delete Cover Photo</button>
                </div>
            </form>
        </div>
    </div>
</div>


    <!-- Delete Cover Photo Modal -->
<div class="modal fade" id="deleteCoverPhotoModal" tabindex="-1" aria-labelledby="deleteCoverPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCoverPhotoModalLabel">Delete Cover Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your cover photo? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmDeleteCoverPhoto">Delete</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                
            </div>
        </div>
    </div>
</div>


        <!-- Edit Mission Modal -->
        <div class="modal fade" id="editMissionModal" tabindex="-1" aria-labelledby="editMissionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editMissionModalLabel">Edit Mission</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <textarea class="form-control" name="mission" rows="5" required><?php echo htmlspecialchars($about['mission']); ?></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="update_mission" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Vision Modal -->
        <div class="modal fade" id="editVisionModal" tabindex="-1" aria-labelledby="editVisionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editVisionModalLabel">Edit Vision</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <textarea class="form-control" name="vision" rows="5" required><?php echo htmlspecialchars($about['vision']); ?></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="update_vision" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        

        <!-- Bootstrap JS -->

        <script>
    // Handle "Delete Cover Photo" button click
    document.getElementById('deleteCoverPhotoButton').addEventListener('click', function () {
        // Show the modal when the delete button is clicked
        const modal = new bootstrap.Modal(document.getElementById('deleteCoverPhotoModal'));
        modal.show();
    });

    // Handle the confirmation to delete cover photo
    document.getElementById('confirmDeleteCoverPhoto').addEventListener('click', function () {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                window.location.reload(); // Reload the page after deletion
            }
        };
        xhr.send('delete_cover_photo=1');

        // Close the modal after deletion
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteCoverPhotoModal'));
        modal.hide();
    });

    // Check if the user is an admin (this should match your PHP logic)
    const isAdmin = <?php echo $is_admin ? 'true' : 'false'; ?>;
</script>

    </body>
    </html>

    <?php include '../includes/footer.php'; ?>

    <?php
    // End output buffering
    ob_end_flush();