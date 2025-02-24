<?php
include '../includes/config.php';
include '../includes/header.php';

// Fetch ads from the database
$query = "SELECT * FROM ads ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$ads = [];
while ($row = mysqli_fetch_assoc($result)) {
    $ads[] = $row;
}



$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome to Zamboanga City PESO Job Portal</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/JOB/assets/index.css">
    
    <style>
        
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <div class="header-content">
            <button class="cta-button" onclick="window.location.href='about.php'">Discover More..</button>
        </div>
    </div>

        <!-- Ads Section -->
        <div class="container ads-section">
        <h2></h2>
        <?php if ($user_role === 'admin'): ?>
            <a href="../admin/add_ad.php" class="post-ad-button">Add Advertisement</a>
        <?php endif; ?>
        <div class="ad-slider" id="adSlider">
            <?php foreach ($ads as $ad): ?>
                <div class="ad-card-wrapper" data-ad-id="<?= htmlspecialchars($ad['id']) ?>">
                    <a href="<?= htmlspecialchars($ad['link_url']) ?>" target="_blank" class="ad-card-link">
                        <div class="ad-card">
                            <img 
                                src="/JOB/uploads/<?= htmlspecialchars($ad['image_file']) ?>" 
                                alt="<?= htmlspecialchars($ad['title']) ?>" 
                                onerror="this.onerror=null; this.src='/JOB/uploads/default_image.jpg';"
                            >
                            <div class="ad-card-content">
                                <h4><?= htmlspecialchars($ad['title']) ?></h4>
                                <p><?= htmlspecialchars($ad['description']) ?></p>
                            </div>
                        </div>
                    </a>
                    <?php if ($user_role === 'admin'): ?>
                        <button class="delete-ad-button" onclick="deleteAd(<?= htmlspecialchars($ad['id']) ?>)">
                            <i class="fas fa-times"></i>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="slider-controls" id="sliderControls">
            <?php foreach ($ads as $index => $ad): ?>
                <button data-index="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>"></button>
            <?php endforeach; ?>
        </div>
    </div>

<!-- Announcement Section -->
<div class="container announcement-section py-5">
    <h2 class="pb-4 border-bottom">Latest Announcements</h2>
    <div class="row g-5">
        <?php
        // Fetch the latest 2 announcements from the database
        $query = "SELECT id, title, content, created_at FROM announcements ORDER BY created_at DESC LIMIT 2";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $id = htmlspecialchars($row['id']);
                $title = htmlspecialchars($row['title']);
                $content = htmlspecialchars($row['content']); // Full content
                $short_content = htmlspecialchars(substr($row['content'], 0, 200)) . '...'; // Short excerpt
                $created_at = htmlspecialchars(date('F j, Y', strtotime($row['created_at'])));
        ?>
                <!-- Announcement Card -->
                <div class="col-md-6">
                    <article class="blog-post card-hover-effect">
                        <h3 class="blog-post-title"><?= $title ?></h3>
                        <p class="blog-post-meta"><?= $created_at ?></p>
                        <p class="short-content"><?= $short_content ?></p>
                        <p class="full-content" style="display: none;"><?= $content ?></p>
                        <button class="btn btn-outline-primary continue-reading">Continue reading</button>
                    </article>
                </div>
        <?php
            }
        } else {
            echo '<div class="col-12"><p>No announcements available at the moment.</p></div>';
        }
        ?>
    </div>
    <div class="text-center mt-5">
        <a href="/JOB/admin/announcement.php" class="btn btn-primary">View All Announcements</a>
    </div>
</div>

<!-- Recent Jobs Section -->
<div class="container recent-jobs-section py-5">
    <h2 class="pb-4 border-bottom">Recently Posted Jobs</h2>
    <div id="jobCarousel" class="carousel slide" data-bs-ride="carousel">
        <!-- Slides -->
        <div class="carousel-inner">
            <?php
            // Fetch the latest 5 jobs from the database
            $query = "SELECT id, title, description, responsibilities, requirements, preferred_qualifications, created_at, category, location, thumbnail FROM jobs ORDER BY created_at DESC LIMIT 5";
            $result = mysqli_query($conn, $query);
            if ($result && mysqli_num_rows($result) > 0) {
                $first = true; // Flag to set the first item as active
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = htmlspecialchars($row['id']);
                    $title = htmlspecialchars($row['title']);
                    $short_description = htmlspecialchars(substr($row['description'], 0, 100)) . '...'; // Short excerpt
                    $responsibilities = htmlspecialchars(substr($row['responsibilities'], 0, 100)) . '...'; // Short excerpt
                    $requirements = htmlspecialchars(substr($row['requirements'], 0, 100)) . '...'; // Short excerpt
                    $preferred_qualifications = htmlspecialchars(substr($row['preferred_qualifications'], 0, 100)) . '...'; // Short excerpt
                    $created_at = htmlspecialchars(date('F j, Y', strtotime($row['created_at'])));
                    $category = htmlspecialchars($row['category']);
                    $location = htmlspecialchars($row['location']);
                    $thumbnail = htmlspecialchars($row['thumbnail']);

                    // Generate thumbnail URL with fallback
                    $thumbnail_url = !empty($thumbnail) && file_exists("../$thumbnail")
                        ? "../$thumbnail"
                        : "../uploads/default_image.jpg";
            ?>
                    <!-- Job Slide -->
                    <div class="carousel-item <?= $first ? 'active' : '' ?>">
                        <div class="job-card card-hover-effect">
                            <div class="row g-0">
                                <!-- Content -->
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h4 class="card-title"><?= $title ?></h4>
                                        <p class="card-category"><i class="fas fa-briefcase me-2"></i><?= $category ?></p>
                                        <p class="card-location"><i class="fas fa-map-marker-alt me-2"></i><?= $location ?></p>
                                        <p class="card-description"><?= $short_description ?></p>
                                        <!-- Add Responsibilities, Requirements, and Preferred Qualifications -->
                                        <div class="extra-details mt-2">
                                            <p><strong>Responsibilities:</strong> <?= $responsibilities ?></p>
                                            <p><strong>Requirements:</strong> <?= $requirements ?></p>
                                            <p><strong>Preferred Qualifications:</strong> <?= $preferred_qualifications ?></p>
                                        </div>
                                        <a href="job.php?id=<?= $id ?>" class="btn btn-outline-primary w-30">View Details</a>
                                    </div>
                                </div>
                                <!-- Thumbnail -->
                                <div class="col-md-4">
                                    <img src="<?= $thumbnail_url ?>" alt="<?= $title ?>" class="card-thumbnail">
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                    $first = false; // After the first item, set flag to false
                }
            } else {
                echo '<div class="col-12 text-center"><p>No recent jobs available at the moment.</p></div>';
            }
            ?>
        </div>
        <!-- Carousel Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#jobCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#jobCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Indicators Centered Below the Carousel -->
    <div class="carousel-indicators-container text-center mt-4">
        <div class="carousel-indicators">
            <?php
            // Fetch the latest 5 jobs from the database
            $query = "SELECT id FROM jobs ORDER BY created_at DESC LIMIT 5";
            $result = mysqli_query($conn, $query);
            $index = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<button type="button" data-bs-target="#jobCarousel" data-bs-slide-to="' . $index . '" class="' . ($index === 0 ? 'active' : '') . '" aria-label="Slide ' . ($index + 1) . '"></button>';
                $index++;
            }
            ?>
        </div>
    </div>

    <!-- Browse All Jobs Button -->
    <div class="text-center mt-4">
        <a href="browse.php" class="btn btn-primary">Browse All Jobs</a>
    </div>
</div>


    

    <!-- Features Section -->
    <div style="margin-bottom:100px;"  class="container features">
        <div class="feature-card">
            <i class="fas fa-briefcase"></i>
            <h3>Job Listings</h3>
            <p>Find the perfect job that matches your skills and aspirations.</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-handshake"></i>
            <h3>Employer Support</h3>
            <p>We assist employers in finding the right candidates for their needs.</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-users"></i>
            <h3>Community Engagement</h3>
            <p>Empowering the community through employment opportunities.</p>
        </div>
    </div>

    <!-- Delete Ad Confirmation Modal -->
<div class="modal fade" id="deleteAdModal" tabindex="-1" aria-labelledby="deleteAdModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteAdModalLabel">Confirm Remove Advertisement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this advertisement? This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
      <button style="background-color:#007bff" type="button" class="btn btn-dark" id="confirmDeleteAdBtn">Delete</button>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        
      </div>
    </div>
  </div>
</div>




    <!-- JavaScript for Auto-Sliding Ads -->
    <script>

document.addEventListener("DOMContentLoaded", function () {
    // Select all "View Details" buttons
    const buttons = document.querySelectorAll(".view-details");

    buttons.forEach((button) => {
        button.addEventListener("click", function () {
            const cardBody = button.closest(".card-body"); // Find the parent card body
            const shortContent = cardBody.querySelector(".short-content");
            const fullContent = cardBody.querySelector(".full-content");

            if (shortContent.style.display !== "none") {
                // Hide short content and show full content
                shortContent.style.display = "none";
                fullContent.style.display = "block";
                button.textContent = "Collapse"; // Change button text
            } else {
                // Show short content and hide full content
                shortContent.style.display = "block";
                fullContent.style.display = "none";
                button.textContent = "View Details"; // Change button text back
            }
        });
    });
});    


        document.addEventListener("DOMContentLoaded", function () {
    // Select all "Continue reading" buttons
    const buttons = document.querySelectorAll(".continue-reading");

    buttons.forEach((button) => {
        button.addEventListener("click", function () {
            const article = button.closest(".blog-post"); // Find the parent article
            const shortContent = article.querySelector(".short-content");
            const fullContent = article.querySelector(".full-content");

            if (shortContent.style.display !== "none") {
                // Hide short content and show full content
                shortContent.style.display = "none";
                fullContent.style.display = "block";
                button.textContent = "Collapse"; // Change button text
            } else {
                // Show short content and hide full content
                shortContent.style.display = "block";
                fullContent.style.display = "none";
                button.textContent = "Continue reading"; // Change button text back
            }
        });
    });
});

        const slider = document.getElementById('adSlider');
        const controls = document.getElementById('sliderControls').querySelectorAll('button');
        let currentIndex = 0;

        function updateSlider(index) {
            slider.style.transform = `translateX(-${index * 100}%)`;
            controls.forEach((control, i) => {
                control.classList.toggle('active', i === index);
            });
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % controls.length;
            updateSlider(currentIndex);
        }

        // Auto-slide every 5 seconds
        setInterval(nextSlide, 4000);

        // Manual control
        controls.forEach((control, index) => {
            control.addEventListener('click', () => {
                currentIndex = index;
                updateSlider(currentIndex);
            });
        });

// Function to delete an ad using AJAX with a confirmation modal and auto-refresh after deletion
function deleteAd(adId) {
    // Show the confirmation modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteAdModal'));
    deleteModal.show();

    // Handle the "Delete" button click inside the confirmation modal
    document.getElementById('confirmDeleteAdBtn').addEventListener('click', function() {
        // Send AJAX request to delete the ad
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../includes/delete_ad_ajax.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Remove the ad card from the DOM
                    const adWrapper = document.querySelector(`[data-ad-id="${adId}"]`);
                    if (adWrapper) {
                        adWrapper.remove();
                    }
                    // Close the modal after successful deletion
                    deleteModal.hide();
                    
                    // Refresh the page to reflect the changes
                    location.reload();
                } else {
                    alert("Failed to delete the advertisement.");
                }
            }
        };
        xhr.send(`ad_id=${adId}`);
    });
}



        document.addEventListener("DOMContentLoaded", function () {
        var carousel = new bootstrap.Carousel(document.getElementById('jobCarousel'), {
            interval: 3000, // Auto-slide every 3 seconds
            wrap: true      // Loop back to the first slide after the last
        });
    });
    </script>

    
</body>
</html>

<?php include '../includes/footer.php'; ?>