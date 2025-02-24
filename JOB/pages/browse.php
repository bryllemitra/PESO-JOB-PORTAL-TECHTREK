<?php
include '../includes/config.php';
include '../includes/header.php';

// Fetch user role and user_id from session if available
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Handle search input
$search_filter = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = $conn->real_escape_string($_GET['search']);
    $search_filter = " AND title LIKE '%$search_term%'";
}

// Handle category selection
$category_filter = "";
if (isset($_GET['category']) && is_numeric($_GET['category'])) {
    $category_id = $_GET['category'];
    $category_filter = " AND category_id = $category_id";
}

// Handle location selection
$location_filter = "";
if (isset($_GET['location']) && !empty($_GET['location'])) {
    $location = $conn->real_escape_string($_GET['location']);
    $location_filter = " AND location = '$location'";
}

// Build the query for all jobs
$query_all_jobs = "SELECT * FROM jobs WHERE 1" . $category_filter . $location_filter . $search_filter . " ORDER BY created_at DESC";

// Build the query for saved jobs (Only for logged-in users)
if ($user_id) {
    $query_saved_jobs = "
        SELECT j.* 
        FROM saved_jobs sj
        JOIN jobs j ON sj.job_id = j.id
        WHERE sj.user_id = $user_id
        ORDER BY sj.saved_at DESC
    ";
} else {
    // If user is not logged in, show empty result or an error message (optional)
    $query_saved_jobs = "SELECT * FROM jobs WHERE 1 = 0"; // This query returns no jobs if not logged in
}

// Determine which tab is active
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'all'; // Default to 'all'

if ($active_tab === 'saved' && $user_id) {
    $result = $conn->query($query_saved_jobs);
} else {
    $result = $conn->query($query_all_jobs);
}

// Fetch categories for the dropdown
$category_query = "SELECT * FROM categories";
$category_result = $conn->query($category_query);

// Fetch barangay names for the location dropdown (Use a separate variable)
$barangay_query = "SELECT name FROM barangay ORDER BY name ASC";
$barangay_result = $conn->query($barangay_query);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/JOB/assets/browse.css">
    <style>



    </style>
</head>
<body>

<!-- Hero Section -->
<section class="py-5 text-center container">
    <div class="row py-lg-4">
        <div class="col-lg-10 col-md-10 mx-auto">
            <!-- Hero Text -->
            <h1 class="fw-bold">Find Your Dream Job</h1>
            <p class="lead text-muted mb-4">Browse available job listings and apply for positions that match your skills.</p>
            <!-- Search and Filter Form -->
            <form action="" method="get" class="row g-3 align-items-center mt-3">
                <!-- Search Bar -->
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-lg rounded-pill" placeholder="Search jobs by title..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                </div>
                <!-- Category Dropdown -->
                <div class="col-md-3">
                    <select name="category" class="form-select form-select-lg rounded-pill">
                        <option value="">Select Category</option>
                        <?php while ($category = $category_result->fetch_assoc()): ?>
                            <option value="<?= $category['id'] ?>" <?= isset($_GET['category']) && $_GET['category'] == $category['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <!-- Location Dropdown -->
                <div class="col-md-3">
    <select name="location" class="form-select form-select-lg rounded-pill">
        <option value="">Select Location</option>
        <?php
        // Use the previously declared $barangay_result from browse.php
        if ($barangay_result->num_rows > 0) {
            while ($row = $barangay_result->fetch_assoc()) {
                $barangay_name = htmlspecialchars($row['name']); 
                $selected = (isset($_GET['location']) && $_GET['location'] == $barangay_name) ? 'selected' : '';
                echo "<option value=\"$barangay_name\" $selected>$barangay_name</option>";
            }
        }
        ?>
    </select>
</div>


                <!-- Submit Button -->
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-customs btn-lg w-100 rounded-pill">Filter</button>
                </div>
            </form>
            <!-- Admin Button: Post a New Job -->
            <?php if ($user_role === 'admin'): ?>
                <div class="text-center mt-4">
                    <a href="../admin/post_job.php" class="btn btn-outline-custom btn-lg rounded-pill">➕ Post a New Job</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Tab Navigation -->
<ul class="nav nav-tabs justify-content-center mb-4">
    <li class="nav-item">
        <a class="nav-link <?= $active_tab === 'all' ? 'active' : '' ?>" href="?tab=all">All Jobs</a>
    </li>
    <?php if ($user_id): ?>
    <li class="nav-item">
        <a class="nav-link <?= $active_tab === 'saved' ? 'active' : '' ?>" href="?tab=saved">Saved Jobs</a>
    </li>
<?php endif; ?>
</ul>

<!-- Job Listings -->
<div class="album py-5 bg-light">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm job-card">
                            <!-- Thumbnail -->
                            <div class="position-relative">
                                <?php if (!empty($row['thumbnail'])): ?>
                                    <img src="../<?= htmlspecialchars($row['thumbnail']) ?>" class="card-img-top job-thumbnail" alt="Job Thumbnail">
                                <?php else: ?>
                                    <div class="card-img-top placeholder-thumbnail">No Image</div>
                                <?php endif; ?>
                                <!-- Save Flag -->
                                <?php if ($user_id): ?>
            <div class="save-flag" data-job-id="<?= $row['id'] ?>">
                <i class="fas fa-flag"></i>
            </div>
        <?php endif; ?>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title job-title"><?= htmlspecialchars($row['title']) ?></h5>
                                
                                <small class="text-muted"><?= time_elapsed_string($row['created_at']) ?></small><br>
                                <!-- Action Buttons -->
                                <div class="mt-auto">
                                    <a href="job.php?id=<?= $row['id'] ?>" class="btn btn-light btn-view-job">View Job</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center text-muted fs-5">No jobs found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- JavaScript for Saving Jobs -->
<script>
document.querySelectorAll('.save-flag').forEach(flag => {
    const jobId = flag.dataset.jobId;

    // Check if the job is already saved
    fetch('../includes/check_saved.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ job_id: jobId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.is_saved) {
            flag.classList.add('saved');
        }
    });

    // Add click event to toggle save status
    flag.addEventListener('click', function () {
        fetch('../includes/toggle_save.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ job_id: jobId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'saved') {
                flag.classList.add('saved');
            } else if (data.status === 'unsaved') {
                flag.classList.remove('saved');

                // If on the "Saved Jobs" tab, remove the job card from the DOM
                const activeTab = new URLSearchParams(window.location.search).get('tab');
                if (activeTab === 'saved') {
                    const jobCard = flag.closest('.col'); // Find the parent job card
                    if (jobCard) {
                        jobCard.remove(); // Remove the job card from the DOM
                    }
                }
            }
        });
    });
});
</script>

<!-- Bootstrap JS (Optional) -->

</body>
</html>



<?php
// Helper Function: Time Elapsed String
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $string = [
        'y' => 'year',
        'm' => 'month',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second'
    ];

    foreach ($string as $key => &$value) {
        if ($diff->$key) {
            $value = $diff->$key . ' ' . $value . ($diff->$key > 1 ? 's' : '');
        } else {
            unset($string[$key]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>
<?php include '../includes/footer.php'; ?>