<?php

session_start();
require_once '../config/connection.php';

if (isset($_GET['talent_id'])) {
    $talent_id = (int)$_GET['talent_id'];
    $stmt = $con->prepare("SELECT * FROM `top-talent` WHERE `Top-Talent-id` = ?");
    $stmt->bind_param('i', $talent_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $talent = $result->fetch_assoc();
    
    if ($talent) {
        $user = [
            'id' => $talent['Top-Talent-id'],
            'first_name' => explode(' ', $talent['Top-Talent-name'])[0] ?? '',
            'last_name' => explode(' ', $talent['Top-Talent-name'])[1] ?? '',
            'profile_photo_path' => str_replace('../', '../../', $talent['Top-Talent-image']),
            'service_category' => $talent['Top-Talent-category'],
            'years_experience' => $talent['Top-Talent-experience'],
            'city' => explode(',', $talent['Top-Talent-location'])[0] ?? '',
            'country' => trim(explode(',', $talent['Top-Talent-location'])[1] ?? $talent['Top-Talent-location']),
            'services' => $talent['Top-Talent-skills'],
            'is_verified' => 1,
            'rating' => 5.0,
            'description' => "Professional " . $talent['Top-Talent-title'],
            'email' => '',
            'phone' => $talent['phone'] ?? '', // Add this line to get phone from top-talent
            'country_code' => '+961', // Default country code
            'dob' => '',
            'gender' => '',
            'nationality' => '',
            'hourly_rate' => 0,
            'availability' => 'Available',
            'tools_available' => 'yes',
            'transportation' => 'yes',
            'languages' => '',
            'certifications' => '',
            'previous_work_examples' => '',
            'resume_path' => ''
        ];
    } else {
        header("Location: Top-Talent.php");
        exit();
    }
} else {
    $profile_id = isset($_GET['id']) ? (int)$_GET['id'] : $_SESSION['user_id'];
    $stmt = $con->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param('i', $profile_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (!$user) {
        header("Location: login.php");
        exit();
    }
}

// Set WhatsApp number only if phone is available
$whatsapp_number = '';
if (!empty($user['phone']) && !empty($user['country_code'])) {
    $whatsapp_number = urlencode($user['country_code'] . $user['phone']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>forwork</title>

    <!-- Link to Tailwind CSS -->
    <link rel="stylesheet" href="../src/output.css">

    <!-- Link to External CSS -->
    <link rel="stylesheet" href="../css/Profile.css">

    <!-- Link to fontawesome  -->
    <link rel="stylesheet" href="../assets/fontawesome-free-6.7.2-web/css/all.min.css">

    <!-- This is for swiper mode -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Icon of the top -->
    <link rel="icon" href="../components/image/Logo.png" type="image/png" sizes="32x32" />
</head>

<body>
    <div class="profile-header">
        <?php if (!empty($user['profile_photo_path']) && file_exists($user['profile_photo_path'])): ?>
        <img src="<?php echo htmlspecialchars($user['profile_photo_path']); ?>" alt="Profile Photo"
            class="profile-photo">
        <?php else: ?>
        <div class="profile-photo"
            style="background-color: #ddd; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-user" style="font-size: 50px; color: #777;"></i>
        </div>
        <?php endif; ?>

        <div>
            <h1 class="profile-name">
                <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                <?php if ($user['is_verified']): ?>
                <span class="verification-badge"><i class="fas fa-check-circle"></i> Verified</span>
                <?php endif; ?>
            </h1>
            <p class="profile-title"><?php echo htmlspecialchars($user['service_category']); ?>
                <?php echo isset($_GET['talent_id']) ? 'Professional' : 'Provider'; ?></p>
            <div class="rating">
                <i class="fas fa-star"></i>
                <?php echo number_format($user['rating'], 1); ?> (0 reviews)
            </div>
            <?php if (!empty($whatsapp_number)): ?>
            <a href="https://wa.me/<?php echo $whatsapp_number; ?>" class="whatsapp-btn" target="_blank">
                <i class="fab fa-whatsapp"></i> Contact via WhatsApp
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="profile-section">
        <h2 class="section-title">About Me</h2>
        <p><?php echo nl2br(htmlspecialchars($user['description'])); ?></p>
    </div>

    <div class="profile-section">
        <h2 class="section-title">Service Details</h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Service Category</div>
                <div><?php echo htmlspecialchars($user['service_category']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Years of Experience</div>
                <div><?php echo htmlspecialchars($user['years_experience']); ?> years</div>
            </div>
            <div class="info-item">
                <div class="info-label">Hourly Rate</div>
                <div>$<?php echo number_format($user['hourly_rate'], 2); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Availability</div>
                <div><?php echo htmlspecialchars(ucfirst($user['availability'])); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Tools Available</div>
                <div><?php echo htmlspecialchars(ucfirst($user['tools_available'])); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Transportation</div>
                <div><?php echo htmlspecialchars(ucfirst($user['transportation'])); ?></div>
            </div>
        </div>

        <h3 style="margin-top: 20px;">Services Offered</h3>
        <ul class="services-list">
            <?php 
            $services = explode(',', $user['services']);
            foreach ($services as $service): 
                if (trim($service)): ?>
            <li><?php echo htmlspecialchars(trim($service)); ?></li>
            <?php endif; 
            endforeach; ?>
        </ul>
    </div>

    <div class="profile-section">
        <h2 class="section-title">Personal Information</h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Email</div>
                <div><?php echo htmlspecialchars($user['email']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Phone</div>
                <div><?php echo htmlspecialchars($user['country_code'] . ' ' . $user['phone']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Date of Birth</div>
                <div><?php echo date('F j, Y', strtotime($user['dob'])); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Gender</div>
                <div><?php echo htmlspecialchars(ucfirst($user['gender'])); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Nationality</div>
                <div><?php echo htmlspecialchars($user['nationality']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Location</div>
                <div><?php echo htmlspecialchars($user['city'] . ', ' . $user['country']); ?></div>
            </div>
        </div>
    </div>

    <?php if (!empty($user['languages'])): ?>
    <div class="profile-section">
        <h2 class="section-title">Languages</h2>
        <p><?php echo htmlspecialchars($user['languages']); ?></p>
    </div>
    <?php endif; ?>

    <?php if (!empty($user['certifications'])): ?>
    <div class="profile-section">
        <h2 class="section-title">Certifications</h2>
        <p><?php echo nl2br(htmlspecialchars($user['certifications'])); ?></p>
    </div>
    <?php endif; ?>

    <?php if (!empty($user['previous_work_examples'])): ?>
    <div class="profile-section">
        <h2 class="section-title">Previous Work Examples</h2>
        <p><?php echo nl2br(htmlspecialchars($user['previous_work_examples'])); ?></p>
    </div>
    <?php endif; ?>

    <?php if ($user['resume_path']): ?>
    <div class="profile-section">
        <h2 class="section-title">Resume</h2>
        <a href="<?php echo htmlspecialchars($user['resume_path']); ?>" target="_blank"
            style="color: #4CAF50; text-decoration: none;">
            <i class="fas fa-file-pdf"></i> Download Resume
        </a>
    </div>
    <?php endif; ?>
</body>

</html>