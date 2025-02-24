<?php
include '../includes/header.php';
include '../includes/config.php'; // Include your database connection file

// Check if the user is logged in
$is_logged_in = isset($_SESSION['username']);
$user_role = $is_logged_in ? $_SESSION['role'] : null;

// Handle form submission (only for logged-in non-admin users)
if ($is_logged_in && $user_role !== 'admin' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

// Start output buffering to prevent headers already sent issues
ob_start();

// Insert into database
$query = "INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $name, $email, $subject, $message);

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
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick=\"window.location.href='contact.php'\"></button>
                </div>
                <div class='modal-body'>
                    Your message has been sent successfully!
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-primary' onclick=\"window.location.href='contact.php'\">OK</button>
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
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick=\"window.location.href='contact.php'\"></button>
                </div>
                <div class='modal-body'>
                    There was an error sending your message. Please try again.
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-danger' onclick=\"window.location.href='contact.php'\">OK</button>
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

// Fetch user details if logged in
if ($is_logged_in && $user_role !== 'admin') {
    $user_id = $_SESSION['user_id']; // Assuming you store the user ID in the session
    $user_query = "SELECT first_name, last_name, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user_data = $user_result->fetch_assoc();
    $full_name = $user_data['first_name'] . ' ' . $user_data['last_name'];
    $email = $user_data['email'];
}

// Fetch recent messages for admin
$recent_messages = [];
if ($is_logged_in && $user_role === 'admin') {
    $query = "SELECT id, name, email, subject, message, created_at, is_read 
              FROM contacts 
              ORDER BY created_at DESC 
              LIMIT 5"; // Fetch only the latest 5 messages
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $recent_messages = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact Us - Zamboanga City PESO Job Portal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/JOB/assets/contact.css">
</head>
<body>
<!-- Contact Section -->
<div class="container contact-section">
    <h2 class="text-center mb-5">Contact Us</h2>
    <div class="row">
        <?php if (!$is_logged_in): ?>
            <!-- Message for Non-Logged-In Users -->
            <div class="col-md-6 fade-in">
                <div class="alert alert-warning text-center" role="alert">
                    <p>You must be logged in to send us a message.</p>
                    <a href="../pages/login.php" class="btn btn-primary">Login Now</a>
                </div>
            </div>
            <?php elseif ($user_role === 'admin'): ?>
                <!-- Admin-Specific Content -->
                <div class="col-md-6 fade-in">
                    <div class="messenger-container">
                        <h3>Recent Messages</h3><br>
                        <div class="messages-list">
                            <?php if (!empty($recent_messages)): ?>
                                <?php foreach ($recent_messages as $msg): ?>
                                    <div class="message-item <?= $msg['is_read'] ? '' : 'unread' ?>">
                                        <strong><?= htmlspecialchars($msg['name']) ?></strong>
                                        <p><small><?= html_entity_decode(htmlspecialchars($msg['subject'])) ?></small></p>
                                        <p><?= nl2br(html_entity_decode(htmlspecialchars(substr($msg['message'], 0, 50)))) ?>...</p>
                                        <small class="text-muted"><?= date('M d, Y h:i A', strtotime($msg['created_at'])) ?></small>
                                    </div><br>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No recent messages.</p>
                            <?php endif; ?>
                        </div>
                        <a href="../admin/view_message.php" class="btn btn-primary w-100 mt-3">View All Messages</a>
                    </div>
                </div>
            <?php else: ?>

            <!-- Contact Form for Logged-In Users -->
            <div class="col-md-6 fade-in">
                <div class="contact-form">
                    <h3>Send Us Feedback</h3>
                    <form method="POST" action="">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="<?= isset($full_name) ? htmlspecialchars($full_name) : '' ?>" readonly required>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" readonly required>
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" required>
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
        <!-- Contact Information -->
        <div class="col-md-6 fade-in">
            <div class="contact-info">
                <div class="contact-info-card">
                    <i class="fas fa-envelope"></i>
                    <h4>Email</h4>
                    <p>info@zamboangapeso.gov.ph</p>
                </div>
                <div class="contact-info-card">
                    <i class="fas fa-phone"></i>
                    <h4>Phone</h4>
                    <p>+63 912 345 6789</p>
                </div>
                <div class="contact-info-card">
                    <i class="fas fa-map-marker-alt"></i>
                    <h4>Address</h4>
                    <p>Zamboanga City PESO Office<br>Comelec Road, Zamboanga City</p>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Map Section -->
    <div style="margin-bottom:100px;"   class="container map-section fade-in">
        <h2 class="text-center mb-5">Our Location</h2>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.8850313120734!2d122.07317137604724!3d6.904349593095002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32506a071307c255%3A0xcbf0b93ac2f6e095!2sPublic%20Employment%20Service%20Office%20(PESO)%20-%20Zamboanga%20City!5e0!3m2!1sen!2sph!4v1739370444607!5m2!1sen!2sph" 
                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>



    <!-- Bootstrap JS (Optional) -->
 
</body>
</html>

<?php include '../includes/footer.php'; ?>