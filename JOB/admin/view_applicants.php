<?php
include '../includes/config.php';
include '../includes/header.php';

// Redirect if not admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>window.location.href = '../pages/index.php';</script>";
    exit();
}

// Handle Accept/Reject Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    $user_id = $_POST['user_id'] ?? null;
    $job_id = $_POST['job_id'] ?? null;

    if ($action && $user_id && $job_id) {
        // Determine the status based on the action
        $status = ($action === 'accept') ? 'accepted' : 'rejected';

        // Update the application status in the database
        $updateQuery = "UPDATE applications SET status = ? WHERE user_id = ? AND job_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sii", $status, $user_id, $job_id);
        $stmt->execute();
        $stmt->close();

        // Use JavaScript to redirect instead of header()
        echo "<script>window.location.href = 'view_applicants.php?job_id=$job_id&status=success';</script>";
        exit(); // Always exit after a redirect
    }
}

// Get job_id and validate
$job_id = $_GET['job_id'] ?? null;
if (!$job_id) {
    echo "<div class='alert alert-danger text-center'>Invalid job ID.</div>";
    exit();
}

// Fetch job title
$jobQuery = "SELECT title FROM jobs WHERE id = ?";
$stmt = $conn->prepare($jobQuery);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$jobResult = $stmt->get_result();
$job = $jobResult->fetch_assoc();
if (!$job) {
    echo "<div class='alert alert-danger text-center'>Job not found.</div>";
    exit();
}

// Fetch applicants with full name, username, resume file, and status
$appQuery = "SELECT u.id AS user_id, u.username, u.first_name, u.middle_name, u.last_name, a.applied_at, a.resume_file, a.status 
             FROM applications a 
             JOIN users u ON a.user_id = u.id 
             WHERE a.job_id = ?";
$stmt = $conn->prepare($appQuery);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$appResult = $stmt->get_result();
?>


<link rel="stylesheet" href="/JOB/assets/view_applicants.css">
<div class="container mt-5">
    <h2 class="text-center mb-4 text-futuristic">📄 Applicants for <span class="text-primary"><?= htmlspecialchars($job['title']) ?></span></h2>
    <div class="d-flex justify-content-between my-3">
        <button onclick="goBack()" class="btn btn-back"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-left-dashed"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12h6m3 0h1.5m3 0h.5" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg> Back</button>
    </div>
    <?php if ($appResult->num_rows > 0): ?>
        <table class="table-futuristic">
            <thead>
                <tr>
                    <th>Applicant</th>
                    <th>Applied At</th>
                    <th>Resume</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $appResult->fetch_assoc()): 
                // Construct full name
                $fullName = trim($row['first_name'] . ' ' . ($row['middle_name'] ? $row['middle_name'] . ' ' : '') . $row['last_name']);
                $profileLink = "../pages/profile.php?id=" . urlencode($row['user_id']); // Use 'id' instead of 'user_id'
                $resumeFile = $row['resume_file'];
                $status = $row['status'];
            ?>
            <tr>
                <td>
                    <div>
                        <a href="<?= htmlspecialchars($profileLink) ?>" class="fw-bold text-decoration-none text-futuristic-link">
                            <?= htmlspecialchars($fullName) ?>
                        </a>
                        <br>
                        <small>
                            <a href="<?= htmlspecialchars($profileLink) ?>" class="text-decoration-none text-muted">
                                @<?= htmlspecialchars($row['username']) ?>
                            </a>
                        </small>
                    </div>
                </td>
                <td><?= date("F j, Y, g:i a", strtotime($row['applied_at'])) ?></td>
                <td>
                    <?php if (!empty($resumeFile)): ?>
                        <?php
                        // Check the file extension
                        $fileExtension = pathinfo($resumeFile, PATHINFO_EXTENSION);
                        $fileUrl = htmlspecialchars($resumeFile);
                        ?>
                        <div id="resume-actions-<?= $row['user_id'] ?>" class="d-flex">
                            <button class="btn btn-light-futuristic me-2" onclick="viewResume('<?= $fileUrl ?>', '<?= $fileExtension ?>')">View</button>
                            <a href="<?= $fileUrl ?>" class="btn btn-light-download" download>
                                <i class="fas fa-download me-2"></i> Download
                            </a>
                        </div>
                    <?php else: ?>
                        <span class="text-danger">No resume attached</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($status === 'pending'): ?>
                        <span class="badge bg-warning text-dark">Pending</span>
                    <?php elseif ($status === 'accepted'): ?>
                        <span class="badge bg-success">Accepted</span>
                    <?php elseif ($status === 'rejected'): ?>
                        <span class="badge bg-danger">Rejected</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($status === 'pending'): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                            <input type="hidden" name="job_id" value="<?= $job_id ?>">
                            <button type="submit" name="action" value="accept" class="btn btn-light-check me-2">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="submit" name="action" value="reject" class="btn btn-light-cross">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                    <?php else: ?>
                        <span class="text-muted">Action taken</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning text-center">❌ No applicants for this job yet.</div>
    <?php endif; ?>
</div>

<!-- Modal for Fullscreen Resume Preview -->
<div id="resume-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-light text-dark">
            <div class="modal-header">
                <h5 class="modal-title">Resume Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="resume-modal-body">
                <!-- Resume content will be loaded here -->
            </div>
        </div>
    </div>
</div>




<!-- Include Mammoth.js -->
<script src="https://unpkg.com/mammoth/mammoth.browser.min.js"></script>
<script>
    // Fix Back Button Issue
    function goBack() {
    // Check if there's a valid referrer
    if (document.referrer && !document.referrer.includes("view_applicants.php")) {
        window.location.href = document.referrer; // Navigate to the referrer page
    } else {
        window.location.href = "../pages/index.php"; // Fallback URL
    }
}

    // Function to Open Resume in Modal
    function viewResume(fileUrl, fileExtension) {
        const modalBody = document.getElementById('resume-modal-body');
        modalBody.innerHTML = ''; // Clear previous content

        if (fileExtension.toLowerCase() === 'pdf') {
            // Embed PDF in an iframe
            modalBody.innerHTML = `<iframe src="${fileUrl}" width="100%" height="100%" style="border:none;"></iframe>`;
        } else if (fileExtension.toLowerCase() === 'docx') {
            // Use Mammoth.js to render DOCX as HTML
            fetch(fileUrl)
                .then(response => response.arrayBuffer())
                .then(arrayBuffer => mammoth.convertToHtml({ arrayBuffer }))
                .then(result => {
                    modalBody.innerHTML = result.value;
                })
                .catch(error => {
                    modalBody.innerHTML = `<div class="alert alert-danger">Error loading DOCX file: ${error.message}</div>`;
                });
        } else {
            // Unsupported format
            modalBody.innerHTML = `<div class="alert alert-warning">Unsupported file format. Please download the file to view it.</div>`;
        }

        // Show the modal
        const resumeModal = new bootstrap.Modal(document.getElementById('resume-modal'), {});
        resumeModal.show();
    }
</script>

