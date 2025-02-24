<?php
include '../includes/config.php';
include '../includes/header.php';

// Redirect non-admin users
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>window.location.href = '../pages/index.php';</script>";
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Start output buffering
    ob_start();

    // First, check if the user exists
    $check_query = "SELECT id FROM users WHERE id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Delete the user
        $delete_query = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param('i', $user_id);

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
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick=\"window.location.href='user_list.php'\"></button>
                        </div>
                        <div class='modal-body'>
                            User deleted successfully!
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-primary' onclick=\"window.location.href='user_list.php'\">OK</button>
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
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick=\"window.location.href='user_list.php'\"></button>
                        </div>
                        <div class='modal-body'>
                            There was a problem deleting the user.
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-danger' onclick=\"window.location.href='user_list.php'\">OK</button>
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
    } else {
        // Clear any existing output and display only the modal
        ob_clean(); // Clear the output buffer

        echo "
        <!-- Include Bootstrap CSS -->
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>

        <!-- Modal Structure -->
        <div class='modal fade show' id='warningModal' tabindex='-1' aria-labelledby='warningModalLabel' aria-hidden='false' style='display: block; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;'>
            <div class='modal-dialog modal-dialog-centered'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='warningModalLabel'>Warning!</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick=\"window.location.href='user_list.php'\"></button>
                    </div>
                    <div class='modal-body'>
                        User not found.
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-warning' onclick=\"window.location.href='user_list.php'\">OK</button>
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
} else {
    // Clear any existing output and display only the modal
    ob_clean(); // Clear the output buffer

    echo "
    <!-- Include Bootstrap CSS -->
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>

    <!-- Modal Structure -->
    <div class='modal fade show' id='warningModal' tabindex='-1' aria-labelledby='warningModalLabel' aria-hidden='false' style='display: block; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;'>
        <div class='modal-dialog modal-dialog-centered'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='warningModalLabel'>Warning!</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' onclick=\"window.location.href='user_list.php'\"></button>
                </div>
                <div class='modal-body'>
                    No user ID provided.
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-warning' onclick=\"window.location.href='user_list.php'\">OK</button>
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .alert {
            font-size: 18px;
            padding: 15px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Delete User</h2>
    </div>
</body>
</html>
