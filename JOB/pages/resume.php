<?php
session_start();
// Retrieve session data if it exists
$data = $_SESSION['resume_data'] ?? [];
$errors = $_SESSION['errors'] ?? [];
// Clear errors from session after displaying them
unset($_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Generator</title>
    <link rel="stylesheet" href="/JOB/assets/resume.css">
</head>
<body>
    <div class="container">
        <h1>Create Your Resume</h1>
        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form id="resumeForm" action="preview.php" method="GET">
            <!-- Basic Information -->
            <div class="collapsible-section">
                <div class="section-header" onclick="toggleSection('basic-info')">Basic Information</div>
                <div class="section-content" id="basic-info">
                    <div class="form-grid">
                        <div>
                            <label for="name">Full Name:</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($data['name'] ?? ''); ?>" required>
                        </div>
                        <div>
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>" required>
                        </div>
                        <div>
                            <label for="phone">Phone Number:</label>
                            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($data['phone'] ?? ''); ?>" pattern="\d{11}" title="Phone number must be exactly 11 digits" required>
                        </div>
                        <div>
                            <label for="address">Address:</label>
                            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($data['address'] ?? ''); ?>" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Section -->
            <div class="collapsible-section">
                <div class="section-header" onclick="toggleSection('profile')">Profile</div>
                <div class="section-content" id="profile">
                    <div class="form-grid">
                        <div>
                            <label for="age">Age:</label>
                            <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($data['age'] ?? ''); ?>" required>
                        </div>
                        <div>
                            <label for="dob">Date of Birth:</label>
                            <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($data['dob'] ?? ''); ?>" required>
                        </div>
                        <div>
                            <label for="pob">Place of Birth:</label>
                            <input type="text" id="pob" name="pob" value="<?php echo htmlspecialchars($data['pob'] ?? ''); ?>" required>
                        </div>
                        <div>
                            <label for="gender">Gender:</label>
                            <select id="gender" name="gender" required>
                                <option value="Male" <?php echo isset($data['gender']) && $data['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo isset($data['gender']) && $data['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo isset($data['gender']) && $data['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="marital_status">Marital Status:</label>
                            <select id="marital_status" name="marital_status" required>
                                <option value="Single" <?php echo isset($data['marital_status']) && $data['marital_status'] === 'Single' ? 'selected' : ''; ?>>Single</option>
                                <option value="Married" <?php echo isset($data['marital_status']) && $data['marital_status'] === 'Married' ? 'selected' : ''; ?>>Married</option>
                                <option value="Divorced" <?php echo isset($data['marital_status']) && $data['marital_status'] === 'Divorced' ? 'selected' : ''; ?>>Divorced</option>
                            </select>
                        </div>
                        <div>
                            <label for="weight">Weight (kg):</label>
                            <input type="number" id="weight" name="weight" value="<?php echo htmlspecialchars($data['weight'] ?? ''); ?>" required>
                        </div>
                        <div>
                            <label for="height">Height (cm):</label>
                            <input type="number" id="height" name="height" value="<?php echo htmlspecialchars($data['height'] ?? ''); ?>" required>
                        </div>
                        <div>
                            <label for="nationality">Nationality:</label>
                            <input type="text" id="nationality" name="nationality" value="<?php echo htmlspecialchars($data['nationality'] ?? ''); ?>" required>
                        </div>
                        <div>
                            <label for="religion">Religion:</label>
                            <input type="text" id="religion" name="religion" value="<?php echo htmlspecialchars($data['religion'] ?? ''); ?>" required>
                        </div>
                    </div>
                    <label for="languages">Languages Spoken:</label>
                    <textarea id="languages" name="languages" rows="2" placeholder="Enter languages separated by commas..." required><?php echo htmlspecialchars($data['languages'] ?? ''); ?></textarea>
                </div>
            </div>

            <!-- Professional Summary -->
            <div class="collapsible-section">
                <div class="section-header" onclick="toggleSection('professional-summary')">Professional Summary</div>
                <div class="section-content" id="professional-summary">
                    <label for="profile">Profile:</label>
                    <textarea id="profile" name="profile" rows="4" placeholder="Provide a brief description of yourself..." required><?php echo htmlspecialchars($data['profile'] ?? ''); ?></textarea>
                </div>
            </div>

            <!-- Work Experience and Training -->
            <div class="collapsible-section">
                <div class="section-header" onclick="toggleSection('work-experience')">Work Experience and Training</div>
                <div class="section-content" id="work-experience">
                    <textarea id="training" name="training" rows="6" placeholder="Describe your work experience and training..." required><?php echo htmlspecialchars($data['training'] ?? ''); ?></textarea>
                </div>
            </div>

            <!-- Achievements -->
            <div class="collapsible-section">
                <div class="section-header" onclick="toggleSection('achievements')">Achievements</div>
                <div class="section-content" id="achievements">
                    <textarea id="achievements" name="achievements" rows="4" placeholder="List your achievements..." required><?php echo htmlspecialchars($data['achievements'] ?? ''); ?></textarea>
                </div>
            </div>

            <!-- Education -->
            <div class="collapsible-section">
                <div class="section-header" onclick="toggleSection('education')">Education</div>
                <div class="section-content" id="education">
                    <p class="placeholder">Institution Name | Address | Graduation Year</p>
                    <textarea id="education" name="education" rows="4" placeholder="Provide details about your education..." required><?php echo htmlspecialchars($data['education'] ?? ''); ?></textarea>
                </div>
            </div>

            <!-- Skills -->
            <div class="collapsible-section">
                <div class="section-header" onclick="toggleSection('skills')">Skills</div>
                <div class="section-content" id="skills">
                    <textarea id="skills" name="skills" rows="3" placeholder="Enter your skills separated by commas..." required><?php echo htmlspecialchars($data['skills'] ?? ''); ?></textarea>
                </div>
            </div>

            <!-- Centered Preview Button -->
            <button type="submit">Preview Resume</button>
        </form>
        <button style="background-color: gray;" onclick="window.location.href='profile.php';">Back</button>
           
    </div>

    <script>
        // Function to toggle collapsible sections
        function toggleSection(sectionId) {
            const sectionContent = document.getElementById(sectionId);
            sectionContent.classList.toggle('active');
        }
    </script>
</body>
</html>