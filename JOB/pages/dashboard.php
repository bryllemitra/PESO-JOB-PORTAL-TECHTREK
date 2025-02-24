<?php
include '../includes/config.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT jobs.title, jobs.description 
          FROM applications 
          JOIN jobs ON applications.job_id = jobs.id 
          WHERE applications.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <h2 class="text-center mb-4">Your Job Applications</h2>

                    <?php if ($result->num_rows > 0): ?>
                        <ul class="list-group">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <li class="list-group-item">
                                    <h5 class="text-primary"><?= htmlspecialchars($row['title']) ?></h5>
                                    <p><?= htmlspecialchars($row['description']) ?></p>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-center text-muted">You haven't applied for any jobs yet.</p>
                    <?php endif; ?>

                    <div class="text-center mt-3">
                        <a href="profile.php" class="btn btn-outline-secondary">🏠 Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
