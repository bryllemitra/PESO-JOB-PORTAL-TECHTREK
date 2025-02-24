<?php
include '../includes/header.php';
include '../includes/config.php'; // Include DB connection


// Determine which user profile to fetch based on the logged-in user
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    // Admin can access any user's profile by passing 'id' in the URL
    $user_id = isset($_GET['id']) ? (int)$_GET['id'] : $_SESSION['user_id'];
} else {
    // Regular users can only view their own profile
    $user_id = $_SESSION['user_id']; // Assuming the user ID is stored in session after login
}

// Validate $user_id to prevent invalid queries
if (!$user_id || !is_numeric($user_id)) {
    die("Invalid user ID.");
}

// Fetch user data from the database based on the user ID
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id); // Bind $user_id as an integer
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();


// Handle Cover Photo Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['cover_photo'])) {
    if ($_FILES["cover_photo"]["size"] == 0) {
        echo "<div class='alert alert-danger'>Please select a file to upload.</div>";
    } else {
        $target_dir = "../uploads/"; // Ensure this directory exists and is writable
        $file_name = uniqid() . '_' . basename($_FILES["cover_photo"]["name"]); // Generate unique file name
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the uploaded file is a valid image
        if (getimagesize($_FILES["cover_photo"]["tmp_name"])) {
            // Allow only certain image formats
            if ($imageFileType == "jpg" || $imageFileType == "jpeg" || $imageFileType == "png" || $imageFileType == "gif") {
                if (move_uploaded_file($_FILES["cover_photo"]["tmp_name"], $target_file)) {
                    // Update the database with the new cover photo path
                    $update_query = "UPDATE users SET cover_photo = ? WHERE id = ?";
                    $stmt = $conn->prepare($update_query);
                    $stmt->bind_param("si", $target_file, $user_id);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Cover photo updated successfully.</div>";
                        echo "<script>window.location.href = 'profile.php?id=$user_id';</script>";
                        exit();
                    } else {
                        echo "<div class='alert alert-danger'>Error updating cover photo in the database.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Only JPG, JPEG, PNG, and GIF files are allowed.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>File is not a valid image.</div>";
        }
    }
}

// Handle Cover Photo Removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_cover_photo'])) {
    // Check if a cover photo exists
    $query = "SELECT cover_photo FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!empty($user['cover_photo'])) {
        // Clear the cover_photo column in the database
        $update_query = "UPDATE users SET cover_photo = NULL WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            // Optionally delete the file from the server
            if (file_exists($user['cover_photo'])) {
                unlink($user['cover_photo']);
            }

            echo "<div class='alert alert-success'>Cover photo removed successfully.</div>";
            echo "<script>window.location.href = 'profile.php?id=$user_id';</script>";
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error removing cover photo from the database.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>No cover photo found to remove.</div>";
    }
}


// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_pic'])) {
    if ($_FILES["profile_pic"]["size"] == 0) {
        echo "<div class='alert alert-danger'>Please select a file to upload.</div>";
    } else {
        $target_dir = "../uploads/"; // Ensure this directory exists and is writable
        $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the uploaded file is a valid image
        if (getimagesize($_FILES["profile_pic"]["tmp_name"])) {
            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                $update_query = "UPDATE users SET uploaded_file = ? WHERE id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("si", $target_file, $user_id);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Profile picture updated successfully.</div>";
                    echo "<script>window.location.href = 'profile.php?id=$user_id';</script>";
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Error updating profile picture.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>File is not a valid image.</div>";
        }
    }
}



// Handle profile picture removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_profile_pic'])) {
    // Check if a profile picture exists
    if (!empty($user['uploaded_file'])) {
        // Clear the uploaded_file column in the database (do not delete the file from the server)
        $update_query = "UPDATE users SET uploaded_file = NULL WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Profile picture removed successfully.</div>";
            echo "<script>window.location.href = 'profile.php?id=$user_id';</script>";
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error removing profile picture from the database.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>No profile picture found to remove.</div>";
    }
}



// Handle caption (bio) update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['caption'])) {
    $caption = trim($_POST['caption']);
    $update_caption_query = "UPDATE users SET caption = ? WHERE id = ?";
    $stmt = $conn->prepare($update_caption_query);
    $stmt->bind_param("si", $caption, $user_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Caption updated successfully.</div>";
        // Using JavaScript to reload the page instead of header()
        echo "<script>window.location.href = 'profile.php?id=$user_id';</script>";
        exit(); // Make sure to stop further execution
    } else {
        echo "<div class='alert alert-danger'>Failed to update caption.</div>";
    }
}


// Handle work experience, skills, LinkedIn, and portfolio updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // Sanitize inputs
    $work_experience = trim($_POST['work_experience']);
    $skills = trim($_POST['skills']);
    $linkedin_profile = trim($_POST['linkedin_profile']);
    $portfolio_url = trim($_POST['portfolio_url']);

    // Update query
    $update_query = "UPDATE users 
                     SET work_experience = ?, 
                         skills = ?, 
                         linkedin_profile = ?, 
                         portfolio_url = ? 
                     WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssi", $work_experience, $skills, $linkedin_profile, $portfolio_url, $user_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Profile updated successfully.</div>";
        header("Location: profile.php?id=$user_id");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Failed to update profile.</div>";
    }
}

// Handle resume upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['resume'])) {
    if ($_FILES["resume"]["size"] == 0) {
        echo "<div class='alert alert-danger'>Please select a file to upload.</div>";
    } else {
        $target_dir = "../uploads/resumes/"; // Ensure this directory exists and is writable
        $target_file = $target_dir . basename($_FILES["resume"]["name"]);
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Allowed file types
        $allowed_types = ['pdf', 'doc', 'docx'];
        // Check if the file type is allowed
        if (!in_array($fileType, $allowed_types)) {
            echo "<div class='alert alert-danger'>Only PDF, DOC, and DOCX files are allowed.</div>";
        } elseif ($_FILES["resume"]["size"] > 5 * 1024 * 1024) { // Limit file size to 5MB
            echo "<div class='alert alert-danger'>File size must not exceed 5MB.</div>";
        } else {
            // If a previous resume exists, delete it
            if (!empty($user['resume_file']) && file_exists($user['resume_file'])) {
                unlink($user['resume_file']);
            }
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
                // Save the file path in the database
                $update_query = "UPDATE users SET resume_file = ? WHERE id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("si", $target_file, $user_id);
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Resume uploaded successfully.</div>";
                    echo "<script>window.location.href = 'profile.php?id=$user_id';</script>";
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Error updating resume file in the database.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
            }
        }
    }
}

// Handle resume removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_resume'])) {
    // Check if a resume exists
    if (!empty($user['resume_file'])) {
        // Clear the resume_file column in the database (do not delete the actual file)
        $update_query = "UPDATE users SET resume_file = NULL WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Resume removed from profile successfully.</div>";
            echo "<script>window.location.href = 'profile.php?id=$user_id';</script>";
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error removing resume from the database.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>No resume found to remove.</div>";
    }
}



// Fetch applied jobs for the user with status
$query_jobs = "
    SELECT jobs.title, jobs.category, jobs.location, jobs.id AS job_id, applications.status 
    FROM applications 
    JOIN jobs ON applications.job_id = jobs.id 
    WHERE applications.user_id = ?
";
$stmt = $conn->prepare($query_jobs);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result_jobs = $stmt->get_result();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Job Portal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/JOB/assets/profile.css">
</head>
<body>
<?php $isOwnProfile = ($user_id == $_SESSION['user_id']); // Check if it's the user's own profile ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 fade-in">
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">Profile</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="applications-tab" data-bs-toggle="tab" data-bs-target="#applications" type="button" role="tab" aria-controls="applications" aria-selected="false">Applications</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false">Documents</button>
    </li>
</ul>

                <!-- Tab Content -->
                <div class="tab-content" id="profileTabsContent">
                    <!-- Profile Tab -->
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <!-- Profile Card -->
                        <div class="profile-card mt-4 p-4 mb-4">
            <!-- Cover Photo -->
            <div class="cover-photo-container position-relative" style="height: 340px; overflow: hidden;">
                <img src="<?php echo $user['cover_photo'] ? $user['cover_photo'] : 'default-cover.jpg'; ?>" alt="Cover Photo" class="cover-photo w-100 h-100 object-fit-cover" style="object-position: center;">
                <?php if ($isOwnProfile): ?>
                    <!-- Edit Cover Photo Button -->
                    <button type="button" class="btn btn-light position-absolute top-0 end-0 m-3" data-bs-toggle="modal" data-bs-target="#coverPhotoModal">
                        <i class="fas fa-camera"></i> Edit Cover
                    </button>
                <?php endif; ?>
            </div>

            <!-- Profile Picture -->
            <div class="text-center position-relative" style="margin-top: -100px;">
                <div class="profile-picture mb-3" data-bs-toggle="modal" data-bs-target="#profilePictureModal">
                    <img src="<?php echo $user['uploaded_file'] ? $user['uploaded_file'] : 'default-avatar.png'; ?>" alt="Profile Picture" class="rounded-circle shadow-sm" style="width: 200px; height: 200px; object-fit: cover; border: 4px solid #fff;">
                </div>
                                <!-- User Name -->
                                <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
                                <!-- Caption -->
                                <p class="text-muted caption-text" id="bio-display">
                                    <?php echo !empty($user['caption']) ? htmlspecialchars($user['caption']) : 'No caption set'; ?>
                                </p>
                                <!-- Edit Bio Button -->
                                <?php if ($isOwnProfile): ?>
                                    <button id="edit-bio-button" class="btn btn-light rounded-pill mb-3">Edit Bio</button>
                                <?php endif; ?>
                                <!-- Caption Update Form (Hidden Initially) -->
                                <form action="profile.php?id=<?php echo $user_id; ?>" method="POST" id="bio-form" style="display:none;" class="mb-3">
                                    <textarea name="caption" class="form-control rounded-pill my-3" placeholder="Enter your caption or saying..."><?php echo htmlspecialchars($user['caption']); ?></textarea>
                                    <button type="submit" class="btn btn-primary rounded-pill me-2">Save</button>
                                    <button type="button" id="cancel-bio-button" class="btn btn-secondary rounded-pill">Cancel</button>
                                </form>
                            </div>
                        </div>

                    <!-- Personal Information -->
                    <div class="profile-card p-4 mb-4 fade-in">
                        <h3 class="section-title">Personal Information</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><i style="color:gray;" class="fas fa-envelope"></i> Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                                <p><strong><i style="color:gray;" class="fas fa-venus-mars"></i> Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
                                <p><strong><i style="color:gray;" class="fas fa-birthday-cake"></i> Birth Date:</strong> <?php echo htmlspecialchars($user['birth_date']); ?></p>
                                <p><strong><i style="color:gray;" class="fas fa-hourglass-half"></i> Age:</strong> <?php echo htmlspecialchars($user['age']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i style="color:gray;" class="fas fa-ring"></i> Civil Status:</strong> <?php echo htmlspecialchars($user['civil_status']); ?></p>
                                <p><strong><i style="color:gray;" class="fas fa-phone"></i>  Phone Number:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>
                                <p><strong><i style="color:gray;" class="fas fa-map-marker-alt"> </i>  Address:</strong> <?php echo htmlspecialchars($user['street_address'] . ', ' . $user['barangay'] . ', ' . $user['city']); ?></p>
                                <p><strong><i style="color:gray;" class="fas fa-map-pin"></i> Zip Code:</strong> <?php echo htmlspecialchars($user['zip_code']); ?></p>
                            </div>
                        </div>
                    </div>

                                            <!-- Educational Background -->
                                            <div class="profile-card p-4 mb-4 fade-in">
                            <h3 class="section-title">Educational Background</h3>
                            <p><strong><i style="color:gray;" class="fas fa-graduation-cap"></i> Education Level:</strong> <?php echo htmlspecialchars($user['education_level']); ?></p>
                            <p><strong><i style="color:gray;" class="fas fa-school"></i> School:</strong> <?php echo htmlspecialchars($user['school_name']); ?></p>
                            <p><strong><i style="color:gray;" class="fas fa-calendar-check"></i> Completion Year:</strong> <?php echo htmlspecialchars($user['completion_year']); ?></p>
                            <p><strong><i style="color:gray;" class="fas fa-calendar-alt"></i> Inclusive Years:</strong> <?php echo htmlspecialchars($user['inclusive_years']); ?></p>
                        </div>

                        <!-- Work Experience -->
                        <div class="profile-card p-4 mb-4 fade-in">
                            <h3 class="section-title">Work Experience</h3>
                            <p class="no-data"><?php echo !empty($user['work_experience']) ? htmlspecialchars($user['work_experience']) : 'No work experience added yet.'; ?></p>
                        </div>

                        <!-- Skills -->
                        <div class="profile-card p-4 mb-4 fade-in">
                            <h3 class="section-title">Skills</h3>
                            <p class="no-data"><?php echo !empty($user['skills']) ? htmlspecialchars($user['skills']) : 'No skills added yet.'; ?></p>
                        </div>

                        <!-- LinkedIn Profile -->
                        <div class="profile-card p-4 mb-4 fade-in">
                            <h3 class="section-title">LinkedIn Profile</h3>
                            <p class="no-data">
                                <?php if (!empty($user['linkedin_profile'])): ?>
                                    <a href="<?php echo htmlspecialchars($user['linkedin_profile']); ?>" target="_blank" class="btn btn-primary btn-sm rounded-pill"><i class="fab fa-linkedin"></i> View LinkedIn</a>
                                <?php else: ?>
                                    No LinkedIn profile added.
                                <?php endif; ?>
                            </p>
                        </div>

                        <!-- Portfolio -->
                        <div class="profile-card p-4 mb-4 fade-in">
                            <h3 class="section-title">Portfolio</h3>
                            <p class="no-data">
                                <?php if (!empty($user['portfolio_url'])): ?>
                                    <a href="<?php echo htmlspecialchars($user['portfolio_url']); ?>" target="_blank" class="btn btn-success btn-sm rounded-pill"><i class="fas fa-globe"></i> View Portfolio</a>
                                <?php else: ?>
                                    No portfolio added.
                                <?php endif; ?>
                            </p>
                        </div>
                        </div>
                        </div>




<!-- Modal for Fullscreen Resume Preview -->
<div id="resume-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Resume Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="resume-modal-body">
                <!-- Resume content will be loaded here -->
            </div>
        </div>
    </div>
</div>
                

<!-- Cover Photo Modal -->
<div class="modal fade" id="coverPhotoModal" tabindex="-1" aria-labelledby="coverPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="coverPhotoModalLabel">Edit Cover Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="update_cover_photo.php" method="POST" enctype="multipart/form-data">
                    <!-- File Input -->
                    <div class="mb-3">
                        <label for="cover-photo-upload" class="form-label fw-semibold">Upload New Cover Photo</label>
                        <input type="file" class="form-control" id="cover-photo-upload" name="cover_photo" accept="image/*" required>
                    </div>
                    <!-- Remove Cover Photo Option -->
                    <?php if ($user['cover_photo']): ?>
                        <div class="mb-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <button type="button" class="btn btn-light" onclick="removeCoverPhoto()">Remove Cover Photo</button>
                            
                        </div>
                    <?php else: ?>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal for Removing Cover Photo (Bootstrap 5 Example) -->
<div class="modal fade" id="removeCoverPhotoModal" tabindex="-1" aria-labelledby="removeCoverPhotoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="removeCoverPhotoModalLabel">Confirm Removal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to remove your cover photo? This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="confirmRemoveCoverPhotoBtn">Remove</button>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        
      </div>
    </div>
  </div>
</div>




<!-- Applications Tab -->
<div class="tab-pane fade" id="applications" role="tabpanel" aria-labelledby="applications-tab">
    <div class="container mt-4">
        <h4 class="text-center">My Applications</h4><Br>
        <?php
        // Check if the current user is an admin or viewing their own profile
        $isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
        $isOwnProfile = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id;
        // Only allow admins or the profile owner to view applications
        if ($isAdmin || $isOwnProfile): 
            // Fetch applied jobs for the user with status
            $query_jobs = "
                SELECT 
                    jobs.title, 
                    jobs.category, 
                    jobs.location, 
                    jobs.id AS job_id, 
                    applications.status, 
                    applications.applied_at, 
                    applications.resume_file, 
                    applications.status_updated_at 
                FROM applications 
                JOIN jobs ON applications.job_id = jobs.id 
                WHERE applications.user_id = ?
            ";
            $stmt = $conn->prepare($query_jobs);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result_jobs = $stmt->get_result();
            // Display content based on conditions
            if ($result_jobs->num_rows > 0): ?>
                <div class="job-list">
                    <?php while ($job = $result_jobs->fetch_assoc()): ?>
                        <div class="job-card card mb-3 shadow-sm rounded">
                            <div class="card-body">
                                <!-- Job Header -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong class="text-primary"><?php echo htmlspecialchars($job['title']); ?></strong>
                                    <!-- Display status badge -->
                                    <span class="badge 
                                        <?php if ($job['status'] === 'pending'): ?>bg-warning text-dark
                                        <?php elseif ($job['status'] === 'accepted'): ?>bg-success
                                        <?php elseif ($job['status'] === 'rejected'): ?>bg-danger
                                        <?php endif; ?>">
                                        <?php echo ucfirst($job['status']); ?>
                                    </span>
                                </div>

                                <!-- Job Details -->
                                <div class="job-details mt-3">
                                    <p class="mb-1"><i class="fas fa-briefcase me-2"></i><?php echo htmlspecialchars($job['category']); ?></p>
                                    <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($job['location']); ?></p>
                                    <p class="mb-1"><i class="fas fa-clock me-2"></i>Applied on: <?php echo date('M d, Y', strtotime($job['applied_at'])); ?></p>
                                    <p class="mb-1"><i class="fas fa-file-alt me-2"></i>Resume: 
                                        <?php if (!empty($job['resume_file'])): ?>
                                            <a href="javascript:void(0);" onclick="viewResume('<?php echo htmlspecialchars($job['resume_file']); ?>')" class="text-info text-decoration-none me-2">
                                                <i class="fas fa-eye me-1"></i> View Resume
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">No resume uploaded</span>
                                        <?php endif; ?>
                                    </p>
                                    <p class="mb-1"><i class="fas fa-calendar-check me-2"></i>Status Updated: <?php echo date('M d, Y', strtotime($job['status_updated_at'])); ?></p>
                                </div>

                                <!-- View Details Button -->
                                <div class="job-actions mt-3">
                                    <a href="job.php?id=<?php echo $job['job_id']; ?>" class="btn btn-primary btn-sm rounded-pill">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-muted"><?php echo $isAdmin ? "This user has not applied for any jobs yet." : "You have not applied for any jobs yet."; ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>



<!-- Documents Tab -->
<div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
        <div class="profile-cardop-4 mb-4">
        <h4 class="text-center mb-4 mt-4">My Documents</h4>
<!-- Resume Section -->
<div class="profile-card p-4 mb-4 fade-in">
    <h3 class="section-title resume-section">Resume</h3>
    <p class="no-data">
        <?php if (!empty($user['resume_file'])): ?>
            <!-- Download Resume Button -->
            <a href="<?php echo htmlspecialchars($user['resume_file']); ?>" 
                class="btn btn-download-resume me-2" 
                download>
                    <i class="fas fa-download"></i> Download Resume
            </a>

            <!-- View Resume Button -->
            <button onclick="viewResume('<?php echo htmlspecialchars($user['resume_file']); ?>')" class="btn btn-view-resume me-2">
                <i class="fas fa-eye"></i> View Resume
            </button>

            <!-- Remove Resume Button -->
            <?php if ($isOwnProfile): ?>
                <button id="remove-resume-button" class="btn btn-remove-resume" data-user-id="<?php echo $user_id; ?>">
                    <i class="fas fa-trash"></i> Remove Resume
                </button>
            <?php endif; ?>
        <?php else: ?>
            No resume uploaded yet.
        <?php endif; ?>
    </p>
    
    <?php if ($isOwnProfile): ?>
        <form action="profile.php?id=<?php echo $user_id; ?>" method="POST" enctype="multipart/form-data" class="mt-3">
            <label for="resume" class="form-label fw-bold">Upload/Replace Resume</label>
            <input type="file" name="resume" id="resume" class="form-control rounded-pill my-3" accept=".pdf,.doc,.docx">

            <!-- Upload/Replace Resume Button -->
            <button type="submit" class="btn btn-upload-resume me-2">
                <i class="fas fa-upload"></i> Upload/Replace Resume
            </button>

            <!-- Create Resume Button -->
            <a href="resume.php" class="btn btn-create-resume">
                <i class="fas fa-file-alt"></i> Don't have a resume yet? Create here
            </a>
        </form>
    <?php endif; ?>
</div>
        </div>
    </div>
</div>

<!-- Edit Profile Button -->
<?php if ($isOwnProfile): ?>
    <div id="edit-profile-button" style="margin-bottom: 30px;" class="text-center fade-in">
        <a href="edit_profile.php" class="btn btn-custom rounded-pill px-4">
            <i class="fas fa-edit"></i> Edit Profile
        </a>
    </div>
<?php endif; ?>

                

<!-- Profile Picture Modal -->
<div class="modal fade" id="profilePictureModal" tabindex="-1" aria-labelledby="profilePictureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profilePictureModalLabel">Update Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Display the upload and remove picture forms only if the logged-in user is the profile owner -->
                <?php if ($user_id == $_SESSION['user_id']): ?>
                    <!-- Upload Picture Form -->
                    <form action="profile.php?id=<?php echo $user_id; ?>" method="POST" enctype="multipart/form-data" class="mb-3">
                        <div class="mb-3 text-center">
                            <img src="<?php echo $user['uploaded_file'] ? $user['uploaded_file'] : 'default-avatar.png'; ?>" alt="Profile Picture" class="rounded-circle shadow-sm" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #ddd;">
                        </div>
                        <div class="mb-3">
                            <label for="profile_pic" class="form-label fw-bold">Upload New Profile Picture</label>
                            <input type="file" name="profile_pic" id="profile_pic" class="form-control rounded-pill">
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill w-100"><i class="fas fa-upload"></i> Upload Picture</button>
                    </form>

                    <!-- Remove Picture Form -->
                    <?php if ($user['uploaded_file']): ?>
                        <form action="profile.php?id=<?php echo $user_id; ?>" method="POST" class="mt-3">
                            <input type="hidden" name="remove_profile_pic" value="1">
                            <button type="submit" class="btn btn-light rounded-pill w-100"><i class="fas fa-trash"></i> Remove Picture</button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- If the user is not the profile owner, display a message or leave the modal empty -->
                    <p class="text-center text-muted">You cannot modify this user's profile picture.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Remove Resume Modal -->
<div class="modal fade" id="removeResumeModal" tabindex="-1" aria-labelledby="removeResumeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeResumeModalLabel">Confirm Remove Resume</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove your resume? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmRemoveResume">Remove</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                
            </div>
        </div>
    </div>
</div>





<script src="https://unpkg.com/mammoth/mammoth.browser.min.js"></script>
<script>

function removeCoverPhoto() {
    // Show the confirmation modal
    const confirmationModal = new bootstrap.Modal(document.getElementById('removeCoverPhotoModal'));
    confirmationModal.show();

    // Handle the "Remove" button click inside the confirmation modal
    document.getElementById('confirmRemoveCoverPhotoBtn').addEventListener('click', function() {
        // Send AJAX request to remove the cover photo
        fetch('../pages/remove_cover_photo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ user_id: <?php echo $user_id; ?> })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close both the confirmation modal and the cover photo modal
                confirmationModal.hide();

                // Optionally, you could close the "Cover Photo Modal" if it's open
                const coverPhotoModal = bootstrap.Modal.getInstance(document.getElementById('coverPhotoModal'));
                if (coverPhotoModal) {
                    coverPhotoModal.hide();
                }

                // Refresh the page to reflect changes
                location.reload();
            } else {
                alert(data.message); // Show error message
            }
        })
        .catch(error => console.error('Error:', error));
    });
}






        // Add a data attribute to the body to track the active tab
        document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab') || 'default'; // Default to 'default' if no tab is specified
        document.body.setAttribute('data-tab', activeTab);
    });
    // Function to Open Resume in Modal
    function viewResume(fileUrl) {
        const modalBody = document.getElementById('resume-modal-body');
        modalBody.innerHTML = ''; // Clear previous content

        const fileExtension = fileUrl.split('.').pop().toLowerCase();

        if (fileExtension === 'pdf') {
            // Embed PDF in an iframe
            modalBody.innerHTML = `<iframe src="${fileUrl}" width="100%" height="100%" style="border:none;"></iframe>`;
        } else if (fileExtension === 'docx') {
            // Use Mammoth.js to render DOCX as HTML
            fetch(fileUrl)
                .then(response => response.arrayBuffer())
                .then(arrayBuffer => mammoth.convertToHtml({ arrayBuffer }))
                .then(result => {
                    modalBody.innerHTML = result.value;
                })
                .catch(error => {
                    modalBody.innerHTML = `<div class="alert alert-danger">Error loading DOCX file: ${error.message}</div>`;
                });
        } else {
            // Unsupported format
            modalBody.innerHTML = `<div class="alert alert-warning">Unsupported file format. Please download the file to view it.</div>`;
        }

        // Display extracted images
        fetch('get_extracted_images.php?user_id=<?= $user_id ?>')
            .then(response => response.json())
            .then(images => {
                if (images.length > 0) {
                    modalBody.innerHTML += '<h5>Extracted Images</h5><div class="row">';
                    images.forEach(image => {
                        modalBody.innerHTML += `<img src="${image}" class="col-md-6 img-thumbnail">`;
                    });
                    modalBody.innerHTML += '</div>';
                }
            });

        // Show the modal
        const resumeModal = new bootstrap.Modal(document.getElementById('resume-modal'), {});
        resumeModal.show();
    }

    // Toggle the bio text field and edit button visibility
    document.getElementById('edit-bio-button').addEventListener('click', function() {
        document.getElementById('bio-display').style.display = 'none';
        document.getElementById('bio-form').style.display = 'block';
        document.getElementById('edit-bio-button').style.display = 'none';
    });

    // Cancel the bio edit and revert to original state
    document.getElementById('cancel-bio-button').addEventListener('click', function() {
        document.getElementById('bio-display').style.display = 'block';
        document.getElementById('bio-form').style.display = 'none';
        document.getElementById('edit-bio-button').style.display = 'inline-block';
    });

    document.getElementById('remove-resume-button')?.addEventListener('click', function () {
    const userId = this.getAttribute('data-user-id');
    
    // Show the modal
    const removeResumeModal = new bootstrap.Modal(document.getElementById('removeResumeModal'));
    removeResumeModal.show();

    // Handle the confirmation button click inside the modal
    document.getElementById('confirmRemoveResume')?.addEventListener('click', function () {
        // Submit the form to remove the resume
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `profile.php?id=${userId}`;
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'remove_resume';
        input.value = '1';
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();

        // Close the modal after submitting the form
        removeResumeModal.hide();

        // Refresh the page after form submission
        setTimeout(function () {
            location.reload();
        }, 500); // Small delay to allow form submission to complete
    });




});
</script>


</body>
</html>




