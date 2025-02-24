<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Resume</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .resume-container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .header {
            text-align: center; /* Center align all elements in the header */
            margin-bottom: 20px;
        }
        .photo-placeholder {
            width: 150px; /* Larger photo size for 2x2 picture */
            height: 150px;
            background: #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            border-radius: 50%;
            margin: 0 auto 10px auto; /* Center the photo and add space below it */
        }
        .header-info h1 {
            margin: 0;
            font-size: 24px;
            margin-bottom: 10px; /* Space between name and contact info */
        }
        .contact-info {
            display: flex;
            justify-content: center; /* Center the contact info horizontally */
            gap: 20px; /* Space between email, phone, and address */
            font-size: 14px;
        }
        .section {
            margin-top: 20px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .profile-item {
            margin: 5px 0;
        }
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .skill {
            background:rgb(255, 255, 255);
            color: black;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="resume-container">
        <!-- Header Section -->
        <div class="header">
            <!-- Photo Placeholder -->
            <div class="photo-placeholder"></div>
            
            <!-- Name -->
            <h1><?php echo htmlspecialchars($data['name'] ?? ''); ?></h1>
            <p><?php echo nl2br(htmlspecialchars($data['profile'] ?? '')); ?></p>
            
            <!-- Contact Info (Email, Phone, Address) -->
            <div class="contact-info">
                <span><?php echo htmlspecialchars($data['email'] ?? ''); ?> | </span>
                <span><?php echo htmlspecialchars($data['phone'] ?? ''); ?> | </span>
                <span><?php echo htmlspecialchars($data['address'] ?? ''); ?> | </span>
            </div>
        </div>

        <!-- Profile Section -->
        <br><br><div class="section">
            <div class="section-title">Profile</div>
            <div class="profile-item"><strong>Age:</strong> <?php echo htmlspecialchars($data['age'] ?? ''); ?></div>
            <div class="profile-item"><strong>Date of Birth:</strong> <?php echo htmlspecialchars($data['dob'] ?? ''); ?></div>
            <div class="profile-item"><strong>Place of Birth:</strong> <?php echo htmlspecialchars($data['pob'] ?? ''); ?></div>
            <div class="profile-item"><strong>Gender:</strong> <?php echo htmlspecialchars($data['gender'] ?? ''); ?></div>
            <div class="profile-item"><strong>Marital Status:</strong> <?php echo htmlspecialchars($data['marital_status'] ?? ''); ?></div>
            <div class="profile-item"><strong>Weight:</strong> <?php echo htmlspecialchars($data['weight'] ?? ''); ?> kg</div>
            <div class="profile-item"><strong>Height:</strong> <?php echo htmlspecialchars($data['height'] ?? ''); ?> cm</div>
            <div class="profile-item"><strong>Nationality:</strong> <?php echo htmlspecialchars($data['nationality'] ?? ''); ?></div>
            <div class="profile-item"><strong>Religion:</strong> <?php echo htmlspecialchars($data['religion'] ?? ''); ?></div>
            <div class="profile-item"><strong>Languages:</strong> <?php echo htmlspecialchars($data['languages'] ?? ''); ?></div>
        </div>


        <!-- Work Experience and Training Section -->
        <br><br><div class="section">
            <div class="section-title">Work Experience and Training</div>
            <p><?php echo nl2br(htmlspecialchars($data['training'] ?? '')); ?></p>
        </div>

        <!-- Achievements Section -->
        <br><br><div class="section">
            <div class="section-title">Achievements</div>
            <p><?php echo nl2br(htmlspecialchars($data['achievements'] ?? '')); ?></p>
        </div>

        <!-- Education Section -->
        <br><br><div class="section">
            <div class="section-title">Education</div>
            <p><?php echo nl2br(htmlspecialchars($data['education'] ?? '')); ?></p>
        </div>

        <!-- Skills Section -->
        <br><br><div class="section">
            <div class="section-title">Skills</div>
            <div class="skills-list">
                <?php
                $skills = explode(',', $data['skills'] ?? '');
                foreach ($skills as $skill) {
                    echo '<div class="skill">' . trim(htmlspecialchars($skill)) . '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>