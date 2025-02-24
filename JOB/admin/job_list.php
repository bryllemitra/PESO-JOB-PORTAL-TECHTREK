<?php
include '../includes/config.php';
include '../includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/index.php");
    exit();
}

// Pagination logic
$limit = 15;  // Number of jobs per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search and Sorting
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category_filter = isset($_GET['category_filter']) ? $_GET['category_filter'] : ''; // Added category filter
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'created_at_desc'; // Default sorting by creation date in descending order

// Define allowed sorting options to avoid SQL injection
$sort_mapping = [
    'created_at_asc' => ['column' => 'created_at', 'order' => 'ASC'],
    'created_at_desc' => ['column' => 'created_at', 'order' => 'DESC'],
    'title_asc' => ['column' => 'title', 'order' => 'ASC'],
    'title_desc' => ['column' => 'title', 'order' => 'DESC'],
    'total_applicants_asc' => ['column' => 'total_applicants', 'order' => 'ASC'],
    'total_applicants_desc' => ['column' => 'total_applicants', 'order' => 'DESC'],
];

// Validate the sort parameter
if (!array_key_exists($sort, $sort_mapping)) {
    $sort = 'created_at_desc'; // Default to created_at_desc if invalid sort is passed
}

// Extract column and order from the mapping
$sort_column = $sort_mapping[$sort]['column'];
$sort_order = $sort_mapping[$sort]['order'];

// Fetch categories for filtering
$categories_query = "SELECT * FROM categories";
$categories_result = $conn->query($categories_query);

// Fetch jobs with search, category filter, sort, and pagination
$query = "SELECT j.*, COUNT(a.id) AS total_applicants
          FROM jobs j
          LEFT JOIN applications a ON j.id = a.job_id
          WHERE j.title LIKE ?";
if ($category_filter) {
    $query .= " AND j.category_id = ?";
}
$query .= " GROUP BY j.id
            ORDER BY $sort_column $sort_order
            LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$search_param = "%" . $search . "%";
if ($category_filter) {
    $stmt->bind_param("siii", $search_param, $category_filter, $limit, $offset);
} else {
    $stmt->bind_param("sii", $search_param, $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();

// Count total jobs for pagination
$count_query = "SELECT COUNT(*) AS total_jobs FROM jobs WHERE title LIKE ?";
if ($category_filter) {
    $count_query .= " AND category_id = ?";
}
$stmt = $conn->prepare($count_query);
if ($category_filter) {
    $stmt->bind_param("si", $search_param, $category_filter);
} else {
    $stmt->bind_param("s", $search_param);
}
$stmt->execute();
$count_result = $stmt->get_result();
$count_data = $count_result->fetch_assoc();
$total_jobs = $count_data['total_jobs'];
$total_pages = ceil($total_jobs / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job List - Admin Dashboard</title>
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="/JOB/assets/job_list.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div>
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="job_list.php" class="active"><i class="fas fa-briefcase"></i> Job List</a></li>
            <li><a href="user_list.php"><i class="fas fa-users"></i> Users</a></li>
        </ul>
    </div>
    <div class="toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-angle-right"></i>
    </div>
</div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <h2 class="mt-4">Posted Jobs</h2>

                <!-- Search, Category Filter, and Sort Form -->
                <form class="d-flex mb-3" method="get" action="job_list.php">
                    <input type="text" name="search" class="form-control me-2 rounded-pill" placeholder="Search by job title" value="<?= htmlspecialchars($search) ?>">
                    
                    <!-- Category Filter -->
                    <select name="category_filter" class="form-select mx-2 rounded-pill">
                        <option value="">All Categories</option>
                        <?php while ($category = $categories_result->fetch_assoc()): ?>
                            <option value="<?= $category['id'] ?>" <?= $category['id'] == $category_filter ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    
                    <!-- Combined Sorting Dropdown -->
                    <select name="sort" class="form-select mx-2 rounded-pill">
                        <option value="created_at_desc" <?= $sort === 'created_at_desc' ? 'selected' : '' ?>>Sort by Date (Newest First)</option>
                        <option value="created_at_asc" <?= $sort === 'created_at_asc' ? 'selected' : '' ?>>Sort by Date (Oldest First)</option>
                        <option value="title_asc" <?= $sort === 'title_asc' ? 'selected' : '' ?>>Sort by Title (A-Z)</option>
                        <option value="title_desc" <?= $sort === 'title_desc' ? 'selected' : '' ?>>Sort by Title (Z-A)</option>
                        <option value="total_applicants_asc" <?= $sort === 'total_applicants_asc' ? 'selected' : '' ?>>Sort by Applicants (Lowest First)</option>
                        <option value="total_applicants_desc" <?= $sort === 'total_applicants_desc' ? 'selected' : '' ?>>Sort by Applicants (Highest First)</option>
                    </select>
                    
                    <button type="submit" class="btn btn-primary rounded-pill">Search</button>
                </form>

   <!-- Job List -->
   <div class="job-list">
        <!-- Table Header -->
        <div class="job-header">
            <div>Job Title</div>
            <div>Description</div>
            <div>Applicants</div>
            <div>Date</div>
            <div>Actions</div>
        </div>

        <!-- Job Items -->
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="job-item">
                <div class="title"><?= htmlspecialchars($row['title']) ?></div>
                <div class="description"><?= htmlspecialchars($row['description']) ?></div>
                <div class="applicants" onclick="location.href='view_applicants.php?job_id=<?= $row['id'] ?>'">
                    👤 <?= $row['total_applicants'] ?> Applicants
                </div>
                <div class="date"><?= htmlspecialchars(date('Y-m-d H:i', strtotime($row['created_at']))) ?></div>
                <div class="actions">
                    <button onclick="location.href='edit_job.php?id=<?= $row['id'] ?>'"><i class="fas fa-edit"></i></button>
                    <button data-bs-toggle="modal" data-bs-target="#deleteJobModal" class="btn-delete" data-job-id="<?= $row['id'] ?>"><i class="fas fa-trash"></i></button>
                </div>

            </div>
        <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <button onclick="location.href='?page=<?= $page - 1 ?>&search=<?= $search ?>'" <?= $page <= 1 ? 'disabled' : '' ?>> <i class="fas fa-chevron-left"></i> </button>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <button onclick="location.href='?page=<?= $i ?>&search=<?= $search ?>'" <?= $i === $page ? 'class="active"' : '' ?>><?= $i ?></button>
        <?php endfor; ?>
        <button onclick="location.href='?page=<?= $page + 1 ?>&search=<?= $search ?>'" <?= $page >= $total_pages ? 'disabled' : '' ?>> <i class="fas fa-chevron-right"></i> </button>
    </div>
</div>

<!-- Delete Job Modal -->
<div class="modal fade" id="deleteJobModal" tabindex="-1" aria-labelledby="deleteJobModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteJobModalLabel">Delete Job</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this job? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmDeleteJob">Delete</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        sidebar.classList.toggle('hidden');
        mainContent.classList.toggle('hidden');
    }

    // Get modal element and confirm delete button
const deleteJobModal = new bootstrap.Modal(document.getElementById('deleteJobModal'));
const confirmDeleteJob = document.getElementById('confirmDeleteJob');

// Handle Delete Job button click
document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function () {
        const jobId = this.getAttribute('data-job-id');
        // Store the job ID for later use in the modal
        confirmDeleteJob.setAttribute('data-job-id', jobId);
    });
});

// Confirm deletion in the modal
confirmDeleteJob.addEventListener('click', function () {
    const jobId = this.getAttribute('data-job-id');
    location.href = `delete_job.php?id=${jobId}`;  // Perform the deletion by navigating to the delete URL
});

</script>

</body>
</html>