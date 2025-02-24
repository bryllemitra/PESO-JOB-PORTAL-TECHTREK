<?php
include '../includes/config.php'; // Include your database connection file
include '../includes/header.php';
// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo '<script>window.location.href = "../pages/login.php";</script>';
    exit;
}
// Start output buffering
ob_start();

// Handle Delete Request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query = "DELETE FROM contacts WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        // Clear any existing output and display only the modal
        ob_clean(); // Clear the output buffer

        echo "
        <!-- Include Bootstrap CSS -->
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>

        <!-- Modal Structure -->
        <div class='modal fade show' id='successModal' tabindex='-1' aria-labelledby='successModalLabel' aria-hidden='false' style='display: block; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;'>
            <div class='modal-dialog modal-dialog-centered'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='successModalLabel'>Success!</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick=\"window.location.href='view_message.php'\"></button>
                    </div>
                    <div class='modal-body'>
                        Message deleted successfully!
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' onclick=\"window.location.href='view_message.php'\">OK</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add a backdrop for the modal -->
        <div class='modal-backdrop fade show' style='position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9998;'></div>

        <!-- Include Bootstrap JS and Popper.js -->
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
        ";

        exit(); // Stop further execution of the script
    } else {
        // Clear any existing output and display only the modal
        ob_clean(); // Clear the output buffer

        echo "
        <!-- Include Bootstrap CSS -->
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>

        <!-- Modal Structure -->
        <div class='modal fade show' id='errorModal' tabindex='-1' aria-labelledby='errorModalLabel' aria-hidden='false' style='display: block; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;'>
            <div class='modal-dialog modal-dialog-centered'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='errorModalLabel'>Error!</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick=\"window.location.href='view_message.php'\"></button>
                    </div>
                    <div class='modal-body'>
                        Error deleting message. Please try again.
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-danger' onclick=\"window.location.href='view_message.php'\">OK</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add a backdrop for the modal -->
        <div class='modal-backdrop fade show' style='position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9998;'></div>

        <!-- Include Bootstrap JS and Popper.js -->
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>
        ";

        exit(); // Stop further execution of the script
    }
}
// Mark all messages as read
$update_query = "UPDATE contacts SET is_read = 1 WHERE is_read = 0";
$conn->query($update_query);
// Fetch messages with sorting and search handling
$order_by = 'created_at DESC';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'created_at DESC'; // Get sort option
if ($search) {
    $query = "SELECT c.*, u.id AS user_id FROM contacts c LEFT JOIN users u ON c.email = u.email WHERE c.name LIKE ? OR c.email LIKE ? OR c.subject LIKE ? ORDER BY $sort";
    $stmt = $conn->prepare($query);
    $search_term = "%$search%";
    $stmt->bind_param("sss", $search_term, $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $query = "SELECT c.*, u.id AS user_id FROM contacts c LEFT JOIN users u ON c.email = u.email ORDER BY $sort";
    $result = mysqli_query($conn, $query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Messages - Admin Panel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/JOB/assets/view_message.css">
    <!-- Custom CSS -->
    <style>
        /* Add custom styles for positioning the dropdown */
        .message-card {
            position: relative;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .message-card .dropdown {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000; /* Ensure the dropdown appears above other elements */
        }

        .message-header h4 {
            margin: 0;
            font-size: 1.2rem;
        }

        .message-body {
            margin: 5px 0;
        }

        .dropdown-item.disabled {
            pointer-events: none;
            color: #ccc;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-5">Feedbacks</h1>
        <!-- Search and Sorting Controls (Centered) -->
        <div class="search-sort-container d-flex justify-content-center mb-4">
            <div class="search-bar me-4">
                <form method="GET">
                    <input type="text" name="search" placeholder="Search messages..." class="form-control" value="<?= htmlspecialchars($search) ?>" />
                </form>
            </div>
            <div class="sorting">
                <form method="GET" class="d-flex">
                    <select name="sort" class="form-select me-2" onchange="this.form.submit()">
                        <option value="created_at DESC" <?= $sort == 'created_at DESC' ? 'selected' : '' ?>>Date (Newest First)</option>
                        <option value="created_at ASC" <?= $sort == 'created_at ASC' ? 'selected' : '' ?>>Date (Oldest First)</option>
                        <option value="name ASC" <?= $sort == 'name ASC' ? 'selected' : '' ?>>Name (A to Z)</option>
                        <option value="name DESC" <?= $sort == 'name DESC' ? 'selected' : '' ?>>Name (Z to A)</option>
                    </select>
                </form>
            </div>
        </div>
        <!-- Display Messages -->
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="message-card">
                    <!-- Dropdown Menu Positioned at Top-Right -->
                    <div class="dropdown">
                        <button class="btn btn-link text-dark p-0" type="button" id="dropdownMenuButton<?= $row['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-vertical"></i> <!-- Triple Dots Icon -->
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?= $row['id'] ?>">
                        <li>
                            <a class="dropdown-item" href="#" onclick="showDeleteMessageModal(<?= $row['id'] ?>)">
                                <i class="fas fa-trash-alt me-2"></i>Delete
                            </a>
                        </li>

                            <li>
                                <?php if ($row['user_id']): ?>
                                    <a class="dropdown-item" href="../pages/profile.php?id=<?= $row['user_id'] ?>">
                                        <i class="fas fa-user me-2"></i>View Profile
                                    </a>
                                <?php else: ?>
                                    <span class="dropdown-item disabled">
                                        <i class="fas fa-user me-2"></i>Sender Not Registered
                                    </span>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                    <div class="message-header">
                        <h4><?= htmlspecialchars($row['name']) ?></h4><br><br>
                        <small><i class="fas fa-clock me-2"></i><?= date("F j, Y, g:i A", strtotime($row['created_at'])) ?></small>
                    </div>
                    <p class="message-body"><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
                    <p class="message-body"><strong>Subject:</strong> <?= html_entity_decode(htmlspecialchars($row['subject'])) ?></p><br>

                    <p class="message-body"><strong>Message:</strong> <?= nl2br(html_entity_decode(htmlspecialchars($row['message']))) ?></p>

                    <div class="message-footer">
                        <small>Status: <?= $row['is_read'] ? 'Read' : 'Unread' ?></small>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center text-muted">No messages received yet.</p>
        <?php endif; ?>
    </div>

    <!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteMessageModal" tabindex="-1" aria-labelledby="deleteMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMessageModalLabel">Delete Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this message? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmDeleteButton">Delete</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteMessageId = null;

    // Show modal and set the message ID for deletion
    function showDeleteMessageModal(messageId) {
        deleteMessageId = messageId;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteMessageModal'));
        deleteModal.show();
    }

    // Handle deletion confirmation
    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        if (deleteMessageId) {
            // Redirect to delete URL with the message ID
            window.location.href = 'view_message.php?delete_id=' + deleteMessageId;
        }
    });
</script>


</body>
</html>