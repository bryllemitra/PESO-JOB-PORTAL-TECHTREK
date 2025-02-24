<?php
include '../includes/config.php';
include '../includes/header.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Access Denied");
}
$user_id = $_SESSION['user_id'];

// Fetch user details
$query = $conn->prepare("
    SELECT *, TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) AS age 
    FROM users 
    WHERE id = ?
");
$query->bind_param("i", $user_id);
$query->execute();
$userResult = $query->get_result();
$user = $userResult->fetch_assoc();
if (!$user) {
    die("User not found.");
}

// Handle profile updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone_number = trim($_POST['phone_number']);
    $address = trim($_POST['address']);
    $gender = trim($_POST['gender']);
    $birth_date = trim($_POST['birth_date']);
    $barangay = trim($_POST['barangay']);
    $city = trim($_POST['city']);
    $zip_code = trim($_POST['zip_code']);
    $education_level = trim($_POST['education_level']);
    $completion_year = trim($_POST['completion_year']);
    $school_name = trim($_POST['school_name']);
    $inclusive_years = trim($_POST['inclusive_years']);
    
    // Optional fields
    $caption = trim($_POST['caption']);
    $work_experience = trim($_POST['work_experience']);
    $skills = trim($_POST['skills']);
    $linkedin_profile = trim($_POST['linkedin_profile']);
    $portfolio_url = trim($_POST['portfolio_url']);

    // Validate zip code
    if (!preg_match('/^\d{4,10}$/', $zip_code)) {
        echo "<script>alert('Invalid zip code. Please enter a valid numeric zip code.'); window.location.href='edit_profile.php';</script>";
        exit;
    }

    // Validate phone number
    if (!preg_match('/^\d{11}$/', $phone_number)) {
        echo "<script>alert('Invalid phone number. Please enter exactly 11 digits.'); window.location.href='edit_profile.php';</script>";
        exit;
    }

    // Update user details
    $update_stmt = $conn->prepare("
        UPDATE users 
        SET first_name = ?, last_name = ?, phone_number = ?, street_address = ?, 
            barangay = ?, city = ?, zip_code = ?, education_level = ?, completion_year = ?, school_name = ?, inclusive_years = ?, 
            gender = ?, birth_date = ?, caption = ?, work_experience = ?, skills = ?, 
            linkedin_profile = ?, portfolio_url = ?, age = TIMESTAMPDIFF(YEAR, ?, CURDATE())
        WHERE id = ?
    ");

    if (!$update_stmt) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    // 20 placeholders match with 20 values (including age update)
    $update_stmt->bind_param(
        "sssssssssssssssssssi",  // 20 placeholders
        $first_name, $last_name, $phone_number, $address,
        $barangay, $city, $zip_code, $education_level, $completion_year,
        $school_name, $inclusive_years, $gender, $birth_date,
        $caption, $work_experience, $skills,
        $linkedin_profile, $portfolio_url, $birth_date, $user_id
    );

    if ($update_stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile: " . htmlspecialchars($update_stmt->error) . "');</script>";
    }
}

$barangay_query = "SELECT name FROM barangay ORDER BY name ASC";
$barangay_result = $conn->query($barangay_query);
?>




<link rel="stylesheet" href="/JOB/assets/edit_profile.css">
<div class="container mt-5">
    <h1 class="text-center text-primary fw-bold mb-4">Edit Profile</h1><br>



    <!-- Form -->
    <form id="edit-profile-form" action="edit_profile.php" method="POST" class="shadow p-5 rounded bg-white">
        <!-- Personal Information Section -->
        <section class="mb-5">
            <h4 class="section-title fw-bold text-primary border-bottom pb-2 mb-4">Personal Information</h4>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                </div>
                <div class="col-md-4">
                    <label for="first_name" class="form-label fw-semibold">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="last_name" class="form-label fw-semibold">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="birth_date" class="form-label fw-semibold">Birth Date</label>
                    <input type="date" name="birth_date" id="birth_date" class="form-control" value="<?php echo $user['birth_date']; ?>" onchange="calculateAge()">
                </div>
                <div class="col-md-4">
                    <label for="age" class="form-label fw-semibold">Age</label>
                    <input type="number" id="age" class="form-control" value="<?php echo $user['age']; ?>" onchange="calculateBirthDate()">
                </div>
                <div class="col-md-4">
                    <label for="gender" class="form-label fw-semibold">Gender</label>
                    <select name="gender" id="gender" class="form-select">
                        <option value="Male" <?php echo ($user['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($user['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Non-Binary" <?php echo ($user['gender'] == 'Non-Binary') ? 'selected' : ''; ?>>Non-Binary</option>
                        <option value="LGBTQ+" <?php echo ($user['gender'] == 'LGBTQ+') ? 'selected' : ''; ?>>LGBTQ+</option>
                        <option value="Other" <?php echo ($user['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label for="caption" class="form-label fw-semibold">Bio</label>
                    <textarea name="caption" id="caption" class="form-control auto-expand" rows="1"><?php echo htmlspecialchars($user['caption']); ?></textarea>
                </div>
            </div>
        </section>

        <!-- Contact Details Section -->
        <section class="mb-5">
            <h4 class="section-title fw-bold text-primary border-bottom pb-2 mb-4">Contact Details</h4>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="phone_number" class="form-label fw-semibold">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?php echo htmlspecialchars($user['phone_number']); ?>" oninput="validatePhoneNumber(this)" maxlength="11" required>
                    <small class="text-muted">Enter exactly 11 digits (e.g., 09123456789).</small>
                </div>
                <div class="col-md-4">
                    <label for="address" class="form-label fw-semibold">Street Address</label>
                    <input type="text" name="address" id="address" class="form-control" value="<?php echo htmlspecialchars($user['street_address']); ?>">
                </div>
                <div class="col-md-4">
    <label for="barangay" class="form-label fw-semibold">Barangay</label>
    <select name="barangay" id="barangay" class="form-select">
        <option value="">Select Barangay</option>
        <?php
        if ($barangay_result->num_rows > 0) {
            while ($row = $barangay_result->fetch_assoc()) {
                $barangay_name = htmlspecialchars($row['name']);
                $selected = (isset($user['barangay']) && $user['barangay'] == $barangay_name) ? 'selected' : '';
                echo "<option value=\"$barangay_name\" $selected>$barangay_name</option>";
            }
        }
        ?>
    </select>
</div>

                <div class="col-md-4">
                    <label for="city" class="form-label fw-semibold">City</label>
                    <input type="text" name="city" id="city" class="form-control" value="<?php echo htmlspecialchars($user['city']); ?>">
                </div>
                <div class="col-md-4">
                    <label for="zip_code" class="form-label fw-semibold">Zip Code</label>
                    <input type="text" name="zip_code" id="zip_code" class="form-control" value="<?php echo htmlspecialchars($user['zip_code']); ?>" maxlength="10" required>
                </div>
            </div>
        </section>

        <!-- Educational Background Section -->
        <section class="mb-5">
            <h4 class="section-title fw-bold text-primary border-bottom pb-2 mb-4">Educational Background</h4>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="education_level" class="form-label fw-semibold">Education Level</label>
                    <input type="text" name="education_level" id="education_level" class="form-control" value="<?php echo htmlspecialchars($user['education_level']); ?>">
                </div>
                <div class="col-md-4">
                    <label for="completion_year" class="form-label fw-semibold">Completion Year</label>
                    <input type="text" name="completion_year" id="completion_year" class="form-control" value="<?php echo htmlspecialchars($user['completion_year']); ?>">
                </div>
                <div class="col-md-4">
                    <label for="school_name" class="form-label fw-semibold">School Name</label>
                    <input type="text" name="school_name" id="school_name" class="form-control" value="<?php echo htmlspecialchars($user['school_name']); ?>">
                </div>
                <div class="col-md-4">
                    <label for="inclusive_years" class="form-label fw-semibold">Inclusive Years</label>
                    <input type="text" name="inclusive_years" id="inclusive_years" class="form-control" value="<?php echo htmlspecialchars($user['inclusive_years']); ?>">
                </div>
            </div>
        </section>

        <!-- Professional Information Section -->
        <section class="mb-5">
            <h4 class="section-title fw-bold text-primary border-bottom pb-2 mb-4">Professional Information</h4>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="work_experience" class="form-label fw-semibold">Work Experience</label>
                    <textarea name="work_experience" id="work_experience" class="form-control auto-expand" rows="1"><?php echo htmlspecialchars($user['work_experience']); ?></textarea>
                </div>
                <div class="col-md-6">
                    <label for="skills" class="form-label fw-semibold">Skills</label>
                    <textarea name="skills" id="skills" class="form-control auto-expand" rows="1"><?php echo htmlspecialchars($user['skills']); ?></textarea>
                </div>
                <div class="col-md-6">
                    <label for="linkedin_profile" class="form-label fw-semibold">LinkedIn Profile</label>
                    <input type="url" name="linkedin_profile" id="linkedin_profile" class="form-control" value="<?php echo htmlspecialchars($user['linkedin_profile']); ?>">
                </div>
                <div class="col-md-6">
                    <label for="portfolio_url" class="form-label fw-semibold">Portfolio URL</label>
                    <input type="url" name="portfolio_url" id="portfolio_url" class="form-control" value="<?php echo htmlspecialchars($user['portfolio_url']); ?>">
                </div>
            </div>
        </section>
    </form>

                <!-- Back and Update Profile Buttons -->
                <div class="d-flex justify-content-center gap-4 align-items-center mt-4 mb-4">
                    <!-- Back Button -->
                    <button type="button" class="btn btn-outline-secondary d-flex align-items-center" onclick="history.back()">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </button>
                    <!-- Update Profile Button -->
                    <button type="submit" form="edit-profile-form" class="btn btn-primary d-flex align-items-center px-4">
                        <i class="fas fa-save me-2"></i> Update Profile
                    </button>
                </div>

</div>

<script>
function calculateAge() {
    const birthDate = new Date(document.getElementById('birth_date').value);
    const currentDate = new Date();
    const age = currentDate.getFullYear() - birthDate.getFullYear();
    document.getElementById('age').value = age;
}

function calculateBirthDate() {
    const age = document.getElementById('age').value;
    const currentDate = new Date();
    currentDate.setFullYear(currentDate.getFullYear() - age);
    document.getElementById('birth_date').value = currentDate.toISOString().split('T')[0];
}

function validatePhoneNumber(input) {
    // Remove any non-numeric characters
    input.value = input.value.replace(/\D/g, '');

    // Limit the input to 11 digits
    if (input.value.length > 11) {
        input.value = input.value.slice(0, 11); // Truncate to 11 digits
    }
}

document.querySelectorAll('.auto-expand').forEach(textarea => {
    textarea.addEventListener('input', function () {
        this.style.height = 'auto'; // Reset height
        this.style.height = this.scrollHeight + 'px'; // Set height to scrollHeight
    });

    // Trigger input event on page load to set initial height
    textarea.dispatchEvent(new Event('input'));
});
</script>

<?php

?>
