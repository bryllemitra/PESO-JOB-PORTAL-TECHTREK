<?php
session_start();

// Function to validate phone number
function validatePhoneNumber($phone) {
    return preg_match('/^\d{11}$/', $phone); // Phone number must be exactly 11 digits
}

// Function to validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Retrieve query parameters
$data = [
    'name' => trim($_GET['name'] ?? ''),
    'email' => trim($_GET['email'] ?? ''),
    'phone' => trim($_GET['phone'] ?? ''),
    'address' => trim($_GET['address'] ?? ''),
    'age' => trim($_GET['age'] ?? ''),
    'dob' => trim($_GET['dob'] ?? ''),
    'pob' => trim($_GET['pob'] ?? ''),
    'gender' => trim($_GET['gender'] ?? ''),
    'marital_status' => trim($_GET['marital_status'] ?? ''),
    'weight' => trim($_GET['weight'] ?? ''),
    'height' => trim($_GET['height'] ?? ''),
    'nationality' => trim($_GET['nationality'] ?? ''),
    'religion' => trim($_GET['religion'] ?? ''),
    'languages' => trim($_GET['languages'] ?? ''),
    'profile' => trim($_GET['profile'] ?? ''),
    'training' => trim($_GET['training'] ?? ''),
    'achievements' => trim($_GET['achievements'] ?? ''),
    'education' => trim($_GET['education'] ?? ''),
    'skills' => trim($_GET['skills'] ?? ''),
];

// Validation flags
$errors = [];

// Validate required fields
if (empty($data['name'])) $errors[] = "Full Name is required.";
if (empty($data['email']) || !validateEmail($data['email'])) $errors[] = "Invalid email format.";
if (empty($data['phone']) || !validatePhoneNumber($data['phone'])) $errors[] = "Phone number must be exactly 11 digits.";
if (empty($data['address'])) $errors[] = "Address is required.";
if (empty($data['age'])) $errors[] = "Age is required.";
if (empty($data['dob'])) $errors[] = "Date of Birth is required.";
if (empty($data['pob'])) $errors[] = "Place of Birth is required.";
if (empty($data['gender'])) $errors[] = "Gender is required.";
if (empty($data['marital_status'])) $errors[] = "Marital Status is required.";
if (empty($data['weight'])) $errors[] = "Weight is required.";
if (empty($data['height'])) $errors[] = "Height is required.";
if (empty($data['nationality'])) $errors[] = "Nationality is required.";
if (empty($data['religion'])) $errors[] = "Religion is required.";
if (empty($data['languages'])) $errors[] = "Languages Spoken is required.";
if (empty($data['profile'])) $errors[] = "Profile is required.";
if (empty($data['training'])) $errors[] = "Work Experience and Training is required.";
if (empty($data['achievements'])) $errors[] = "Achievements is required.";
if (empty($data['education'])) $errors[] = "Education is required.";
if (empty($data['skills'])) $errors[] = "Skills are required.";

// Redirect back to index.php if there are errors
if (!empty($errors)) {
    $_SESSION['errors'] = $errors; // Store errors in session
    $_SESSION['resume_data'] = $data; // Retain user input
    header("Location: index.php");
    exit;
}

// If all validations pass, store sanitized data in the session
$_SESSION['resume_data'] = $data;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Preview</title>
    <link rel="stylesheet" href="/JOB/assets/preview_resume.css">
</head>
<body>
    <div class="resume-container">
        <!-- Header Section -->
        <div class="header">
            <h1><?php echo htmlspecialchars($data['name']); ?></h1>
            <p><?php echo nl2br(htmlspecialchars($data['profile'])); ?></p>
        </div>

        <!-- Contact Information -->
        <div class="contact-info">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" alt="Email Icon">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8l8 5 8-5v10z"/>
                </svg>
                <?php echo htmlspecialchars($data['email']); ?>
            </span>
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" alt="Phone Icon">
                    <path d="M6.62 10.79c1.44 2.83 3.76 5.17 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                </svg>
                <?php echo htmlspecialchars($data['phone']); ?>
            </span>
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" alt="Address Icon">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z"/>
                </svg>
                <?php echo htmlspecialchars($data['address']); ?>
            </span>
        </div>

        <!-- Profile Section -->
        <div class="section">
            <div class="section-title">Profile</div>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($data['age']); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($data['dob']); ?></p>
            <p><strong>Place of Birth:</strong> <?php echo htmlspecialchars($data['pob']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($data['gender']); ?></p>
            <p><strong>Marital Status:</strong> <?php echo htmlspecialchars($data['marital_status']); ?></p>
            <p><strong>Weight:</strong> <?php echo htmlspecialchars($data['weight']); ?> kg</p>
            <p><strong>Height:</strong> <?php echo htmlspecialchars($data['height']); ?> cm</p>
            <p><strong>Nationality:</strong> <?php echo htmlspecialchars($data['nationality']); ?></p>
            <p><strong>Religion:</strong> <?php echo htmlspecialchars($data['religion']); ?></p>
            <p><strong>Languages:</strong> <?php echo htmlspecialchars($data['languages']); ?></p>
        </div>


        <!-- Work Experience and Training -->
        <div class="section">
            <div class="section-title">Work Experience and Training</div>
            <p><?php echo nl2br(htmlspecialchars($data['training'])); ?></p>
        </div>

        <!-- Achievements -->
        <div class="section">
            <div class="section-title">Achievements</div>
            <p><?php echo nl2br(htmlspecialchars($data['achievements'])); ?></p>
        </div>

        <!-- Education -->
        <div class="section">
            <div class="section-title">Education</div>
            <p><?php echo nl2br(htmlspecialchars($data['education'])); ?></p>
        </div>

        <!-- Skills -->
        <div class="section">
            <div class="section-title">Skills</div>
            <div class="skills-list">
                <?php
                $skills = explode(',', $data['skills']);
                foreach ($skills as $skill) {
                    echo '<div class="skill">' . trim(htmlspecialchars($skill)) . '</div>';
                }
                ?>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button class="back-button" onclick="window.location.href='resume.php'">Back to Form</button>
            <button class="download-button" onclick="window.location.href='generate.php'">Download Resume</button>
        </div>
    </div>
</body>
</html>