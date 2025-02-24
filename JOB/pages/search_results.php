<?php
include '../includes/config.php';
include '../includes/header.php';

$search_query = "";
$jobs = [];

if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
    $search_query = trim($_GET['query']);
    $query = "%" . $search_query . "%";

    $stmt = $conn->prepare("SELECT * FROM jobs WHERE title LIKE ? OR description LIKE ?");
    $stmt->bind_param("ss", $query, $query);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="text-center mb-4">🔍 Search Results</h2>



            <?php if (!empty($jobs)): ?>
                <div class="list-group">
                    <?php foreach ($jobs as $job): ?>
                        <a href="job.php?id=<?= $job['id'] ?>" class="list-group-item list-group-item-action">

                            <h5 class="text-primary"><?= htmlspecialchars($job['title']) ?></h5>
                            <p class="text-muted"><?= htmlspecialchars(substr($job['description'], 0, 100)) ?>...</p>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-muted">❌ No jobs found for "<strong><?= htmlspecialchars($search_query) ?></strong>".</p>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-outline-secondary">🏠 Back to Jobs</a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
