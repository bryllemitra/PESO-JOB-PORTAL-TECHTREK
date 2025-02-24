<?php
session_start();
include '../includes/config.php';

// Generate CSRF token if it doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$barangay_query = "SELECT name FROM barangay ORDER BY name ASC";
$barangay_result = $conn->query($barangay_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify CSRF token
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }

    // Sanitize inputs
    $email = trim(htmlspecialchars($_POST['email']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $username = trim($_POST['username']);
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $last_name = trim($_POST['last_name']);
    $ext_name = trim($_POST['ext_name']);
    $gender = trim($_POST['gender']);
    $birth_date = $_POST['birth_date'];
    $age = intval($_POST['age']);
    $phone_number = trim($_POST['phone_number']);
    $place_of_birth = trim($_POST['place_of_birth']);
    $civil_status = trim($_POST['civil_status']);
    $zip_code = trim($_POST['zip_code']);
    $street_address = trim($_POST['street_address']);
    $barangay = trim($_POST['barangay']);
    $city = trim($_POST['city']);

    // Validate inputs
    $errors = [];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = "Username can only contain letters, numbers, and underscores.";
    }
    if (!in_array($gender, ['Male', 'Female', 'Non-Binary', 'LGBTQ+', 'Other'])) {
        $errors[] = "Invalid gender selected.";
    }
    if (!preg_match('/^\d{11}$/', $phone_number)) {
        $errors[] = "Phone number must be exactly 11 digits.";
    }

    // Check if email or username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = "Email or username already exists.";
    }
    $stmt->close();

    // Insert data into the database
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (
            email, password, username, first_name, middle_name, last_name, ext_name, gender, birth_date, age, phone_number, place_of_birth, civil_status, zip_code, street_address, barangay, city
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssisssssss", $email, $hashed_password, $username, $first_name, $middle_name, $last_name, $ext_name, $gender, $birth_date, $age, $phone_number, $place_of_birth, $civil_status, $zip_code, $street_address, $barangay, $city);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Registration successful! You can now log in.";
            header("Location: login.php");
            exit();
        } else {
            error_log("Database error: " . $stmt->error);
            $errors[] = "An error occurred while registering. Please try again.";
        }
        $stmt->close();
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/JOB/assets/register.css">
</head>
<body>
    <div class="container">
        <img src="/JOB/uploads/PESO.png" alt="Logo" class="logo rotating-logo">
        <h2>Create an Account</h2><br><br>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
    <!-- Add CSRF Token for Security -->
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

    <div class="row g-3">
        <!-- Column 1: Basic Information -->
        <div class="col-lg-6">
            <h4 class="mb-3">Basic Information</h4>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required maxlength="50">
                <label for="username">Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required maxlength="100" oninput="this.value = this.value.toLowerCase();">
                <label for="email">Email</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required minlength="8">
                <label for="password">Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required minlength="8">
                <label for="confirm_password">Confirm Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" required maxlength="50">
                <label for="first_name">First Name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="Middle Name (Optional)" required maxlength="50">
                <label for="middle_name">Middle Name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" required maxlength="50">
                <label for="last_name">Last Name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="ext_name" name="ext_name" placeholder="Extension Name (e.g., Jr., Sr.)" maxlength="10">
                <label for="ext_name">Extension Name (e.g., Jr., Sr.)</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Non-Binary">Non-Binary</option>
                    <option value="LGBTQ+">LGBTQ+</option>
                    <option value="Other">Other</option>
                </select>
                <label for="gender">Gender</label>
            </div>
            <div class="form-floating mb-3">
                <input type="date" class="form-control" id="birth_date" name="birth_date" placeholder="Birth Date" required>
                <label for="birth_date">Birth Date</label>
            </div>
            <div class="form-floating mb-3">
                <input type="number" class="form-control" id="age" name="age" placeholder="Age" min="1" max="120">
                <label for="age">Age</label>
            </div>
        </div>

        <!-- Column 2: Contact Details -->
        <div class="col-lg-6">
            <h4 class="mb-3">Contact Details</h4>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Phone Number (11 digits)" required maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);">
                <label for="phone_number">Phone Number (11 digits)</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" placeholder="Place of Birth" required maxlength="100">
                <label for="place_of_birth">Place of Birth</label>
            </div>
            <div class="form-floating mb-3">
                <select class="form-select" id="civil_status" name="civil_status" required>
                    <option value="Single">Single</option>
                    <option value="Married">Married</option>
                    <option value="Divorced">Divorced</option>
                    <option value="Widowed">Widowed</option>
                </select>
                <label for="civil_status">Civil Status</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="ZIP Code" required maxlength="10">
                <label for="zip_code">ZIP Code</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="street_address" name="street_address" placeholder="Street Address" required maxlength="255">
                <label for="street_address">Street Address</label>
            </div>
            <div class="form-floating mb-3">
    <select class="form-control" id="barangay" name="barangay" required>
        <option value="">Select Barangay</option>
        <?php
        if ($barangay_result->num_rows > 0) {
            while ($row = $barangay_result->fetch_assoc()) {
                $barangay_name = htmlspecialchars($row['name']);
                echo "<option value=\"$barangay_name\">$barangay_name</option>";
            }
        }
        ?>
    </select>
    <label for="barangay">Barangay</label>
</div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="city" name="city" placeholder="City" required maxlength="100">
                <label for="city">City</label>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-lg mt-4">Register</button>
    <p class="login-link">Already have an account? <a href="login.php">Sign in here</a></p>
</form>

    </div>

    <!-- JavaScript for Age and Birth Date Synchronization -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const birthDateInput = document.getElementById('birth_date');
            const ageInput = document.getElementById('age');

            // Calculate age based on birth date
            birthDateInput.addEventListener('change', function () {
                const birthDate = new Date(this.value);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDifference = today.getMonth() - birthDate.getMonth();
                if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                ageInput.value = age > 0 ? age : '';
            });

            // Calculate birth year based on age
            ageInput.addEventListener('input', function () {
                const age = parseInt(this.value);
                if (age > 0) {
                    const today = new Date();
                    const birthYear = today.getFullYear() - age;
                    const birthMonth = today.getMonth() + 1; // Months are zero-based
                    const birthDay = today.getDate();
                    const formattedBirthDate = `${birthYear}-${String(birthMonth).padStart(2, '0')}-${String(birthDay).padStart(2, '0')}`;
                    birthDateInput.value = formattedBirthDate;
                } else {
                    birthDateInput.value = '';
                }
            });
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>