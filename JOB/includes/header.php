<?php
session_start(); // Start the session at the very top
include '../includes/config.php'; // Include DB connection
// Get the current page URL for highlighting active links
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Job Portal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/heroicons@1.0.6/dist/outline.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/JOB/assets/header.css">
    <style>
        
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg custom-header shadow-sm fixed-top custom-header-container">
        <div class="container-fluid px-4">
            <!-- Left Side: Brand + Navigation Links -->
            <div class="d-flex align-items-center">
                <!-- Brand -->
                <a class="navbar-brand d-flex align-items-center" href="../pages/index.php">
                    <img src="/JOB/uploads/PESO.png" alt="PESO Logo" style="width: 50px; height: auto; margin-right: 10px;">
                    <span class="brand-text fw-bold text-uppercase">PESO</span>
                </a>
                <!-- Toggler Button for Main Navigation -->
                <button class="navbar-toggler border-0 me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars text-white"></i>
                </button>
                <!-- Collapsible Navigation Links -->
                <div class="collapse navbar-collapse justify-content-start" id="navbarNav">
                    <ul class="navbar-nav align-items-center">
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page === 'index.php') ? 'custom-active' : ''; ?>" href="../pages/index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page === 'browse.php') ? 'custom-active' : ''; ?>" href="../pages/browse.php">Browse</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page === 'announcement.php') ? 'custom-active' : ''; ?>" href="../admin/announcement.php">Announcement</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page === 'about.php') ? 'custom-active' : ''; ?>" href="../pages/about.php">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($current_page === 'contact.php') ? 'custom-active' : ''; ?>" href="../pages/contact.php">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Right Side: Notification, Message, User Icon -->
            <div class="d-flex align-items-center">
                <!-- Toggler Button for Right-Side Navigation -->
                <button class="navbar-toggler border-0 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#rightNav" aria-controls="rightNav" aria-expanded="false" aria-label="Toggle right navigation">
                    <i class="fas fa-user-circle text-white" style="font-size: 1.5em;"></i>
                </button>
                <div class="collapse navbar-collapse justify-content-end d-lg-flex" id="rightNav">
                    <ul class="navbar-nav align-items-center">
                        <?php if (isset($_SESSION['username'])): ?>
                            <!-- Message Icon (only for admins) -->
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <li class="nav-item position-relative">
                                    <a href="../admin/view_message.php" class="nav-link d-flex align-items-center" id="message-link">
                                        <i class="fas fa-envelope message-icon"></i>
                                        <span id="unread-count" class="message-count badge bg-danger rounded-circle"></span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <!-- Notification Bell (only for Admin) -->
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <li class="nav-item dropdown position-relative">
                                    <a class="nav-link d-flex align-items-center" href="#" id="notification-link" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-bell notification-icon"></i>
                                        <span id="notification-count" class="notification-count badge bg-danger rounded-circle" style="display: none;"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 notification-scroll animated-dropdown" id="notification-dropdown">
                                        <li><div class="dropdown-header text-center fw-bold">Notifications</div></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li id="notification-list">
                                            <div class="dropdown-item text-center py-3">Loading...</div>
                                        </li>
                                        <!-- Add Dismiss All Button -->
                                        <li class="dropdown-item text-center">
                                            <button id="dismiss-all-notifications" class="btn header-btn btn-sm btn-outline-danger rounded-pill w-100">
                                                Dismiss All
                                            </button>
                                        </li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <!-- User Dropdown Menu -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-2" style="font-size: 1.5em;"></i>
                                    <?= htmlspecialchars($_SESSION['username']) ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="../pages/profile.php"><i class="fas fa-user me-2"></i> Profile</a></li>
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                        <li><a class="dropdown-item" href="../admin/admin.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                                    <?php endif; ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <!-- Login Button (for users who are not logged in) -->
                            <li class="nav-item">
                                <a class="nav-link btn btn-primary btn-sm rounded-pill text-white px-3" href="../pages/login.php">Login</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    
    <script>

        
<?php if ($_SESSION['role'] === 'admin'): ?>
    // Function to fetch unread message count via AJAX (Admin only)
    function fetchUnreadCount() {
        let url = '../admin/get_unread_count.php'; // Admin-specific URL
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                const count = parseInt(response.trim());
                const unreadCountElement = $('#unread-count');
                if (count > 0) {
                    unreadCountElement.text(count).show(); // Show the badge with the count
                } else {
                    unreadCountElement.hide(); // Hide the badge if no unread messages
                }
            },
            error: function() {
                console.error('Error fetching unread message count.');
            }
        });
    }

    // Fetch unread count every 5 seconds
    setInterval(fetchUnreadCount, 5000);
    fetchUnreadCount(); // Initial fetch on page load

    // Pagination Variables
    let currentPage = 1;
    const notificationsPerPage = 5;

    // Function to fetch notifications via AJAX (Admin only)
    function fetchNotifications() {
        let url = '../admin/get_notifications.php'; // Admin-specific URL
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                try {
                    const notifications = JSON.parse(response);
                    const notificationList = $('#notification-list');
                    const notificationCount = $('#notification-count');

                    if (notifications.length > 0) {
                        // Calculate unread notifications
                        const unreadCount = notifications.filter(n => n.is_read === false || n.is_read === '0').length;
                        if (unreadCount > 0) {
                            notificationCount.text(unreadCount).show(); // Show the badge with the count
                        } else {
                            notificationCount.hide(); // Hide the badge if no unread notifications
                        }

                        // Clear previous notifications
                        notificationList.empty();

                        // Append new notifications
                        notifications.forEach(notification => {
                            const isReadClass = notification.is_read ? 'text-black' : 'text-primary fw-bold';
                            const appliedAt = notification.applied_at === "undefined" ? "Time not available" : notification.applied_at;

                            notificationList.append(`
<li class="dropdown-item d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center ${isReadClass} p-3 mb-2 rounded-4 animate__animated animate__fadeIn">
    <div class="flex-grow-1 message-container">
        <strong class="notification-message">${notification.message}</strong><br>
        <small class="text-muted notification-timestamp">${appliedAt}</small>
    </div>
    <div class="d-flex gap-2 mt-2 mt-md-0 flex-column flex-md-row">
        <a href="${notification.url}" class="notification-icon-btn view-notification hover-scale" data-id="${notification.id}">
            <i class="fas fa-eye"></i>
        </a>
        <button class="notification-icon-btn delete-notification hover-scale" data-id="${notification.id}">
            <svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
        </button>
    </div>
</li>

                            `);
                        });

                        // Add Pagination Controls
                        if (notifications.length > notificationsPerPage) {
                            notificationList.append(`
                                <li class="d-flex justify-content-center mt-3">
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill me-2" id="prev-page" ${currentPage === 1 ? 'disabled' : ''}>
                                        <i class="fas fa-chevron-left me-1"></i> Previous
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill" id="next-page" ${end >= notifications.length ? 'disabled' : ''}>
                                        Next <i class="fas fa-chevron-right ms-1"></i>
                                    </button>
                                </li>
                            `);
                        }
                    } else {
                        notificationCount.hide(); // Hide the badge if no notifications
                        notificationList.html('<li class="dropdown-item text-muted">No notifications</li>');
                    }
                } catch (error) {
                    console.error('Error parsing notifications:', error);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error fetching notifications:', status, error);
                console.error('Response:', xhr.responseText);
            }
        });
    }

    // Call fetchNotifications on page load
    fetchNotifications();

    // Pagination Event Listeners
    $(document).on('click', '#prev-page', function() {
        if (currentPage > 1) {
            currentPage--;
            fetchNotifications();
        }
    });

    $(document).on('click', '#next-page', function() {
        currentPage++;
        fetchNotifications();
    });

    // Mark notification as read when viewed
    $(document).on('click', '.view-notification', function(e) {
        e.preventDefault();
        const notificationId = $(this).data('id');
        const url = $(this).attr('href');

        // Mark as read via AJAX
        $.ajax({
            url: '../admin/mark_as_read.php',
            method: 'POST',
            data: { id: notificationId },
            success: function(response) {
                window.location.href = url; // Redirect after marking as read
            },
            error: function() {
                console.error('Error marking notification as read.');
            }
        });
    });

// Delete notification
$(document).on('click', '.delete-notification', function() {
    const notificationId = $(this).data('id');

    // Mark notification as dismissed via AJAX
    $.ajax({
        url: '../admin/delete_notification.php',
        method: 'POST',
        data: { id: notificationId },
        success: function(response) {
            fetchNotifications(); // Refetch notifications after dismissal
        },
        error: function() {
            console.error('Error dismissing notification.');
        }
    });
});

// Dismiss all notifications
$(document).on('click', '#dismiss-all-notifications', function() {
    $.ajax({
        url: '../admin/dismiss_all_notifications.php',
        method: 'POST',
        success: function(response) {
            fetchNotifications(); // Refetch notifications after dismissal
        },
        error: function() {
            console.error('Error dismissing all notifications.');
        }
    });
});
<?php endif; ?>
    </script>
</body>
</html>

