<?php
session_start();
include '../includes/config.php';

// Ensure user is not already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: ../pages/index.php");
    exit();
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $username = htmlspecialchars(trim($_POST['username']));
    $password = trim($_POST['password']);
    
    // Initialize error message
    $error = '';

    // Validate inputs
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Secure query to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);

                // Store user details in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect user to their dashboard
                header("Location: ../pages/index.php");
                exit();
            } else {
                $error = "Invalid username or password!";
            }
        } else {
            $error = "Invalid username or password!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/JOB/assets/login.css">
    <title>Login</title>
    <style>
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo Section -->
        <div class="logo-section">
            <img src="/JOB/uploads/PESO.png" alt="Logo" class="logo rotating-logo">
        </div>
        <!-- Login Form Section -->
        <div class="form-section">
            <h2>Login</h2>

            <?php
            // Display error message if any
            if (!empty($error)) {
                echo "<p class='error'>$error</p>";
            }

            // Display success message from registration
            if (isset($_SESSION['success_message'])) {
                echo "<p class='success'>" . htmlspecialchars($_SESSION['success_message']) . "</p>";
                unset($_SESSION['success_message']); // Remove after displaying
            }
            ?>

            <form method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Login</button>
            </form>
            <p>New user? <a href="register.php">Sign up here</a></p>
            <p>Seeking for a job? <a href="index.php">Browse here</a></p>
        </div>
    </div>
</body>
</html>
