<?php
include '../includes/config.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vision = mysqli_real_escape_string($conn, $_POST['vision']);
    $updateQuery = "UPDATE about SET vision='$vision' WHERE id=1";
    mysqli_query($conn, $updateQuery);

    // Redirect back to about.php
    echo '<script>window.location.href = "about.php";</script>';
    exit();
}

// Fetch current vision from the database
$query = "SELECT vision FROM about WHERE id = 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$current_vision = $row['vision'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vision</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Vision</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="vision" class="form-label">Vision</label>
                <textarea class="form-control" id="vision" name="vision" rows="5" required><?php echo htmlspecialchars($current_vision); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</body>
</html>