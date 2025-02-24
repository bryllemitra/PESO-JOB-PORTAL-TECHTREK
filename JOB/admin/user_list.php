<?php
include '../includes/config.php';
include '../includes/header.php';

// Redirect non-admin users
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>window.location.href = '../pages/index.php';</script>";
    exit();
}

// Handle search and sort
$search = isset($_GET['search']) ? trim($_GET['search']) : ''; // Trim whitespace from search term
$sort_by = isset($_GET['sort_by']) && in_array($_GET['sort_by'], ['username', 'created_at']) ? $_GET['sort_by'] : 'created_at';
$order = ($sort_by == 'username') ? 'ASC' : 'DESC'; // Default sorting: alphabetical for username, or by created_at

// Pagination setup
$limit = 10; // Number of users per page
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1; // Current page (defaults to 1)
$offset = ($page - 1) * $limit; // Offset for the SQL query

// Prepare search parameters
$search_param = '%' . $search . '%'; // Add wildcards for partial matching

// Query users with search and sort (searching username, first_name, and last_name)
$user_query = "
    SELECT id, username, email, role, created_at, first_name, last_name 
    FROM users 
    WHERE username LIKE ? OR first_name LIKE ? OR last_name LIKE ? 
    ORDER BY $sort_by $order
    LIMIT ?, ?
";
$stmt = $conn->prepare($user_query);
$stmt->bind_param('sssii', $search_param, $search_param, $search_param, $offset, $limit); // Bind parameters
$stmt->execute();
$user_result = $stmt->get_result();

// Query to get the total number of users for pagination
$total_query = "
    SELECT COUNT(*) AS total 
    FROM users 
    WHERE username LIKE ? OR first_name LIKE ? OR last_name LIKE ?
";
$total_stmt = $conn->prepare($total_query);
$total_stmt->bind_param('sss', $search_param, $search_param, $search_param); // Bind parameters
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_users = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_users / $limit); // Calculate total pages
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List - Admin Dashboard</title>
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="/JOB/assets/user_list.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div>
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="job_list.php"><i class="fas fa-briefcase"></i> Job List</a></li>
            <li><a href="user_list.php" class="active"><i class="fas fa-users"></i> Users</a></li>
        </ul>
    </div>
    <div class="toggle-btn" onclick="toggleSidebar()">
        <i class="fas fa-angle-right"></i>
    </div>
</div>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="header">
        <h1>Registered Users</h1>

    </div>

    <!-- Filters and Sorting -->
    <div class="filters">
    <form action="user_list.php" method="GET" class="mb-4 row g-3 align-items-center">
    <!-- Search Box -->
<!-- Search Box -->
<div class="col-md-6">
    <input type="text" name="search" class="form-control rounded-pill shadow-sm expanded-input" 
           placeholder="Search by name" 
           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
</div>
<!-- Sorting Dropdown -->
<div class="col-md-3">
    <select name="sort_by" class="form-select rounded-pill shadow-sm expanded-select">
        <option value="username" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'username') ? 'selected' : '' ?>>Alphabetical</option>
        <option value="created_at" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'created_at') ? 'selected' : '' ?>>Latest Registered</option>
    </select>
</div>
    <!-- Submit Button -->
    <div class="col-md-auto">
        <button type="submit" class="btn btn-primary rounded-pill shadow-sm">Filter</button>
    </div>
</form>
    </div>

<!-- User List -->
<div class="user-list">
    <!-- Table Header -->
    <div class="user-header">
        <div>ID</div>
        <div>Username</div>
        <div>First Name</div>
        <div>Last Name</div>
        <div>Email</div>
        <div>Role</div>
        <div>Registered At</div>
        <div>Actions</div>
    </div>
    <!-- User Items -->
    <?php while ($user = $user_result->fetch_assoc()): ?>
        <div class="user-item">
            <div><?= htmlspecialchars($user['id']) ?></div>
            <div class="username">
                <a href="../pages/profile.php?id=<?= $user['id'] ?>" class="text-decoration-none text-primary fw-semibold">
                    <?= htmlspecialchars($user['username']) ?>
                </a>
            </div>
            <div><?= htmlspecialchars($user['first_name']) ?></div>
            <div><?= htmlspecialchars($user['last_name']) ?></div>
            <div><?= htmlspecialchars($user['email']) ?></div>
            <div class="role"><?= htmlspecialchars($user['role']) ?></div>
            <div><?= htmlspecialchars($user['created_at']) ?></div>
            <div class="actions">
                <!-- Change Role Button -->
                <?php if ($user['id'] == $_SESSION['user_id']): ?>
                    <button disabled title="You cannot change your own role" class="btn">
                        <i class="fas fa-user-edit"></i>
                    </button>
                <?php else: ?>
                    <button onclick="location.href='change_role.php?id=<?= $user['id'] ?>'" class="btn">
                        <i class="fas fa-user-edit"></i>
                    </button>
                <?php endif; ?>

                <!-- Delete Button -->
                <?php if ($user['id'] == $_SESSION['user_id']): ?>
                    <button disabled title="You cannot delete your own account" class="btn">
                        <i class="fas fa-trash"></i>
                    </button>
                <?php else: ?>
                    <button data-bs-toggle="modal" data-bs-target="#deleteUserModal" class="btn-delete btn" data-user-id="<?= $user['id'] ?>">
                        <i class="fas fa-trash"></i>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>

    <!-- Pagination -->
    <div class="pagination">
        <button onclick="location.href='?page=<?= max($page - 1, 1) ?>&search=<?= htmlspecialchars($search) ?>&sort_by=<?= htmlspecialchars($sort_by) ?>'" <?= $page <= 1 ? 'disabled' : '' ?>>
            <i class="fas fa-chevron-left"></i>
        </button>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <button onclick="location.href='?page=<?= $i ?>&search=<?= htmlspecialchars($search) ?>&sort_by=<?= htmlspecialchars($sort_by) ?>'" <?= $i === $page ? 'class="active"' : '' ?>>
                <?= $i ?>
            </button>
        <?php endfor; ?>
        <button onclick="location.href='?page=<?= min($page + 1, $total_pages) ?>&search=<?= htmlspecialchars($search) ?>&sort_by=<?= htmlspecialchars($sort_by) ?>'" <?= $page >= $total_pages ? 'disabled' : '' ?>>
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>


<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmDeleteUser">Delete</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    // Toggle visibility for mobile
    if (window.innerWidth <= 768) {
        sidebar.classList.toggle('visible'); // Toggle visibility for dropdown
    } else {
        // For larger screens, slide the sidebar in/out
        sidebar.classList.toggle('hidden');
        mainContent.classList.toggle('hidden');
    }
}

// Get modal element and confirm delete button
const deleteUserModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
const confirmDeleteUser = document.getElementById('confirmDeleteUser');

// Handle Delete User button click
document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function () {
        const userId = this.getAttribute('data-user-id');
        // Store the user ID for later use in the modal
        confirmDeleteUser.setAttribute('data-user-id', userId);
    });
});

// Confirm deletion in the modal
confirmDeleteUser.addEventListener('click', function () {
    const userId = this.getAttribute('data-user-id');
    location.href = `delete_user.php?id=${userId}`;  // Perform the deletion by navigating to the delete URL
});

</script>

</body>
</html>
