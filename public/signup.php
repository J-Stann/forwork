<?php
session_start();

require_once __DIR__ . '/../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && 
    (empty($_GET['step']) || $_GET['step'] == 1) && 
    isset($_SESSION['signup_data'])) {
    if (isset($_SESSION['signup_data']['profile_photo_path'])) {
        @unlink($_SESSION['signup_data']['profile_photo_path']);
    }
    if (isset($_SESSION['signup_data']['id_document_path'])) {
        @unlink($_SESSION['signup_data']['id_document_path']);
    }
    unset($_SESSION['signup_data']);
    unset($_SESSION['signup_step']);
    unset($_SESSION['verification_code']);
    unset($_SESSION['verification_expiry']);
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$formValues = [
    'firstName' => '',
    'lastName' => '',
    'email' => '',
    'password' => '',
    'confirmPassword' => '',
    'phone' => '',
    'countryCode' => '',
    'dob' => '',
    'gender' => '',
    'nationality' => '',
    'country' => '',
    'city' => '',
    'profile_photo_path' => '',
    'id_document_path' => '',
    'service_category' => '',
    'services' => '',
    'years_experience' => '',
    'hourly_rate' => '',
    'availability' => '',
    'tools_available' => '',
    'transportation' => '',
    'previous_work' => '',
    'languages' => '',
    'certifications' => '',
    'description' => '',
    'user_type' => 'provider'
];
$currentStep = 1;
if (isset($_GET['step'])) {
    $currentStep = max(1, min(3, (int)$_GET['step']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }
    if (isset($_POST['step1'])) {
        $formValues['user_type'] = $_POST['user_type'] ?? 'provider';
        $formValues['firstName'] = htmlspecialchars(trim($_POST['firstName'] ?? ''));
        $formValues['lastName'] = htmlspecialchars(trim($_POST['lastName'] ?? ''));
        $formValues['email'] = htmlspecialchars(trim($_POST['email'] ?? ''));
        $formValues['password'] = $_POST['password'] ?? '';
        $formValues['confirmPassword'] = $_POST['confirmPassword'] ?? '';
        $formValues['phone'] = htmlspecialchars(trim($_POST['phone'] ?? ''));
        $formValues['countryCode'] = htmlspecialchars(trim($_POST['countryCode'] ?? ''));
        $formValues['dob'] = htmlspecialchars(trim($_POST['dob'] ?? ''));
        $formValues['gender'] = htmlspecialchars(trim($_POST['gender'] ?? ''));
        $formValues['nationality'] = htmlspecialchars(trim($_POST['nationality'] ?? ''));
        $formValues['country'] = htmlspecialchars(trim($_POST['country'] ?? ''));
        $formValues['city'] = htmlspecialchars(trim($_POST['city'] ?? ''));

        $errors = [];
        if (empty($formValues['firstName'])) $errors['firstName'] = "First name is required";
        if (empty($formValues['lastName'])) $errors['lastName'] = "Last name is required";
        if (empty($formValues['email'])) $errors['email'] = "Email is required";
        if (!filter_var($formValues['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = "Invalid email format";
        
        $stmt = $con->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $formValues['email']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors['email'] = "Email already registered. Please use a different email or login.";
        }
        $stmt->close();
        
        if (empty($formValues['password'])) $errors['password'] = "Password is required";
        if (strlen($formValues['password']) < 8) $errors['password'] = "Password must be at least 8 characters";
        if ($formValues['password'] !== $formValues['confirmPassword']) $errors['confirmPassword'] = "Passwords do not match";
        if (empty($formValues['phone'])) $errors['phone'] = "Phone number is required";
        if (empty($formValues['countryCode'])) $errors['countryCode'] = "Country code is required";
        if (empty($formValues['dob'])) $errors['dob'] = "Date of birth is required";
        if (empty($formValues['gender'])) $errors['gender'] = "Gender is required";
        if (empty($formValues['nationality'])) $errors['nationality'] = "Nationality is required";
        if (empty($formValues['country'])) $errors['country'] = "Country is required";
        if (empty($formValues['city'])) $errors['city'] = "City is required";

        $uploadErrors = [];
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/temp/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileInfo = pathinfo($_FILES['profile_photo']['name']);
            $extension = strtolower($fileInfo['extension'] ?? '');
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (!in_array($extension, $allowedExtensions)) {
                $uploadErrors['profile_photo'] = "Only JPG, PNG, and GIF images are allowed";
            } elseif ($_FILES['profile_photo']['size'] > 5 * 1024 * 1024) { // Increased to 5MB
                $uploadErrors['profile_photo'] = "Profile photo must be less than 5MB";
            } else {
                $fileName = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetPath)) {
                    $formValues['profile_photo_path'] = $targetPath;
                } else {
                    $uploadErrors['profile_photo'] = "Error uploading profile photo";
                }
            }
        } else if ($_FILES['profile_photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadErrors['profile_photo'] = "Error with profile photo: " . $_FILES['profile_photo']['error'];
        } else {
            $uploadErrors['profile_photo'] = "Profile photo is required";
        }
        if (isset($_FILES['id_document']) && $_FILES['id_document']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/temp/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileInfo = pathinfo($_FILES['id_document']['name']);
            $extension = strtolower($fileInfo['extension'] ?? '');
            $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
            
            if (!in_array($extension, $allowedExtensions)) {
                $uploadErrors['id_document'] = "Only PDF, JPG, PNG files are allowed";
            } elseif ($_FILES['id_document']['size'] > 5 * 1024 * 1024) {
                $uploadErrors['id_document'] = "ID document must be less than 5MB";
            } else {
                $fileName = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['id_document']['tmp_name'], $targetPath)) {
                    $formValues['id_document_path'] = $targetPath;
                } else {
                    $uploadErrors['id_document'] = "Error uploading ID document";
                }
            }
        } else if ($_FILES['id_document']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadErrors['id_document'] = "Error with ID document: " . $_FILES['id_document']['error'];
        } else {
            $uploadErrors['id_document'] = "ID document is required";
        }

        if (empty($errors) && empty($uploadErrors)) {
            $verificationCode = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $formValues['password'] = password_hash($formValues['password'], PASSWORD_DEFAULT);
            $_SESSION['signup_data'] = $formValues;
            $_SESSION['signup_step'] = 2;
            $_SESSION['verification_code'] = $verificationCode;
            $_SESSION['verification_expiry'] = time() + 3600;
            header("Location: signup.php?step=2");
            exit;
        } else {
            $errors = array_merge($errors, $uploadErrors);
        }
    }

    // Step 2 Handling - Verification
    if (isset($_POST['step2'])) {
        $formValues = $_SESSION['signup_data'] ?? [];
        $errors = [];
        $userCode = trim($_POST['verificationCode'] ?? '');
        $correctCode = $_SESSION['verification_code'] ?? '';
        if (empty($userCode)) {
            $errors['verificationCode'] = "Verification code is required";
        } elseif ($userCode !== $correctCode) {
            $errors['verificationCode'] = "Invalid verification code";
        } elseif (time() > ($_SESSION['verification_expiry'] ?? 0)) {
            $errors['verificationCode'] = "Verification code has expired";
        }
        if (empty($errors)) {
            $_SESSION['signup_step'] = 3;
            header("Location: signup.php?step=3");
            exit;
        }
    }

    // Step 3 Handling - Professional Information
    if (isset($_POST['step3'])) {
        $formValues = $_SESSION['signup_data'] ?? [];
        $errors = [];
        if ($formValues['user_type'] === 'provider') {
            $formValues['service_category'] = htmlspecialchars(trim($_POST['service_category'] ?? ''));
            $formValues['services'] = htmlspecialchars(trim($_POST['services'] ?? ''));
            $formValues['years_experience'] = (int)($_POST['years_experience'] ?? 0);
            $formValues['hourly_rate'] = (float)($_POST['hourly_rate'] ?? 0);
            $formValues['availability'] = htmlspecialchars(trim($_POST['availability'] ?? ''));
            $formValues['tools_available'] = $_POST['tools_available'] ?? '';
            $formValues['transportation'] = $_POST['transportation'] ?? '';
            $formValues['previous_work'] = htmlspecialchars(trim($_POST['previous_work'] ?? ''));
            $formValues['languages'] = htmlspecialchars(trim($_POST['languages'] ?? ''));
            $formValues['certifications'] = htmlspecialchars(trim($_POST['certifications'] ?? ''));
            $formValues['description'] = htmlspecialchars(trim($_POST['description'] ?? ''));
            
            if (empty($formValues['service_category'])) $errors['service_category'] = "Service category is required";
            if (empty($formValues['services'])) $errors['services'] = "Services are required";
            if (empty($formValues['years_experience'])) $errors['years_experience'] = "Years of experience is required";
            if (empty($formValues['hourly_rate'])) $errors['hourly_rate'] = "Hourly rate is required";
            if (empty($formValues['availability'])) $errors['availability'] = "Availability is required";
            if (empty($formValues['tools_available'])) $errors['tools_available'] = "Please specify if you have tools";
            if (empty($formValues['transportation'])) $errors['transportation'] = "Please specify if you have transportation";
            if (empty($formValues['description'])) $errors['description'] = "Description is required";
            
            $resumePath = null;
            if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/temp/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $fileInfo = pathinfo($_FILES['resume']['name']);
                $extension = strtolower($fileInfo['extension'] ?? '');
                $allowedExtensions = ['pdf', 'doc', 'docx'];
                
                if (!in_array($extension, $allowedExtensions)) {
                    $errors['resume'] = "Only PDF, DOC, and DOCX files are allowed";
                } elseif ($_FILES['resume']['size'] > 5 * 1024 * 1024) {
                    $errors['resume'] = "File size must be less than 5MB";
                } else {
                    $fileName = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
                    $targetPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['resume']['tmp_name'], $targetPath)) {
                        $resumePath = $targetPath;
                    } else {
                        $errors['resume'] = "Error uploading file";
                    }
                }
            }
        } else {
            $formValues['needed_services'] = htmlspecialchars(trim($_POST['needed_services'] ?? ''));
        }
        
        $agreedToTerms = isset($_POST['terms']) ? 1 : 0;
        if (!$agreedToTerms) {
            $errors['terms'] = "You must agree to the terms";
        }

        if (empty($errors)) {
            $con->begin_transaction();
            try {
                $userId = null;
                $userDir = "../uploads/users/";
                if ($formValues['user_type'] === 'provider') {
                    $sql = "INSERT INTO users (
                        first_name, last_name, email, password, phone, country_code, 
                        dob, gender, nationality, country, city, 
                        profile_photo_path, id_document_path, user_type,
                        service_category, services, years_experience, hourly_rate, 
                        availability, tools_available, transportation, 
                        previous_work_examples, languages, certifications, description,
                        agreed_to_terms, is_verified
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
                    $stmt = $con->prepare($sql);
                    if (!$stmt) {
                        throw new Exception("Prepare failed: " . $con->error);
                    }
                    $stmt->bind_param(
                        'ssssssssssssssssiisssssssi',
                        $formValues['firstName'],
                        $formValues['lastName'],
                        $formValues['email'],
                        $formValues['password'],
                        $formValues['phone'],
                        $formValues['countryCode'],
                        $formValues['dob'],
                        $formValues['gender'],
                        $formValues['nationality'],
                        $formValues['country'],
                        $formValues['city'],
                        $formValues['profile_photo_path'],
                        $formValues['id_document_path'],
                        $formValues['user_type'],
                        $formValues['service_category'],
                        $formValues['services'],
                        $formValues['years_experience'],
                        $formValues['hourly_rate'],
                        $formValues['availability'],
                        $formValues['tools_available'],
                        $formValues['transportation'],
                        $formValues['previous_work'],
                        $formValues['languages'],
                        $formValues['certifications'],
                        $formValues['description'],
                        $agreedToTerms
                    );
                } else {
                    $sql = "INSERT INTO users (
                        first_name, last_name, email, password, phone, country_code, 
                        dob, gender, nationality, country, city, 
                        profile_photo_path, id_document_path, user_type,
                        agreed_to_terms, is_verified
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
                    $stmt = $con->prepare($sql);
                    if (!$stmt) {
                        throw new Exception("Prepare failed: " . $con->error);
                    }
                    $stmt->bind_param(
                        'ssssssssssssssi',
                        $formValues['firstName'],
                        $formValues['lastName'],
                        $formValues['email'],
                        $formValues['password'],
                        $formValues['phone'],
                        $formValues['countryCode'],
                        $formValues['dob'],
                        $formValues['gender'],
                        $formValues['nationality'],
                        $formValues['country'],
                        $formValues['city'],
                        $formValues['profile_photo_path'],
                        $formValues['id_document_path'],
                        $formValues['user_type'],
                        $agreedToTerms
                    );
                }
                if (!$stmt->execute()) {
                    throw new Exception("Execute failed: " . $stmt->error);
                }
                $userId = $con->insert_id;
                $stmt->close();
                $userDir = "../uploads/users/$userId/";
                if (!is_dir($userDir)) {
                    if (!mkdir($userDir, 0755, true)) {
                        throw new Exception("Failed to create user directory");
                    }
                }
                $filesToMove = [
                    'profile_photo' => $formValues['profile_photo_path'],
                    'id_document' => $formValues['id_document_path']
                ];
                if (isset($resumePath) && $resumePath) {
                    $filesToMove['resume'] = $resumePath;
                }
                $newPaths = [];
                foreach ($filesToMove as $type => $tempPath) {
                    if ($tempPath && file_exists($tempPath)) {
                        $fileInfo = pathinfo($tempPath);
                        $extension = $fileInfo['extension'] ?? '';
                        $newFilename = $type . '.' . $extension;
                        $newPath = $userDir . $newFilename;
                        
                        if (!rename($tempPath, $newPath)) {
                            throw new Exception("Failed to move $type file");
                        }
                        $newPaths[$type . '_path'] = $newPath;
                    }
                }
                if (!empty($newPaths)) {
                    $updateSql = "UPDATE users SET ";
                    $updateParts = [];
                    $updateParams = [];
                    $updateTypes = '';
                    
                    foreach ($newPaths as $field => $path) {
                        $updateParts[] = "$field = ?";
                        $updateParams[] = $path;
                        $updateTypes .= 's';
                    }
                    $updateSql .= implode(', ', $updateParts) . " WHERE id = ?";
                    $updateParams[] = $userId;
                    $updateTypes .= 'i';
                    $updateStmt = $con->prepare($updateSql);
                    if (!$updateStmt) {
                        throw new Exception("Prepare failed: " . $con->error);
                    }
                    $updateStmt->bind_param($updateTypes, ...$updateParams);
                    if (!$updateStmt->execute()) {
                        throw new Exception("Execute failed: " . $updateStmt->error);
                    }
                    $updateStmt->close();
                }
                $con->commit();
                unset($_SESSION['signup_data']);
                unset($_SESSION['signup_step']);
                unset($_SESSION['verification_code']);
                unset($_SESSION['verification_expiry']);
                unset($_SESSION['csrf_token']);
                header("Location: signup-success.php?user_id=".$userId);
                exit;
                
            } catch (Exception $e) {
                $con->rollback();
                if (isset($formValues['profile_photo_path']) && file_exists($formValues['profile_photo_path'])) {
                    @unlink($formValues['profile_photo_path']);
                }
                if (isset($formValues['id_document_path']) && file_exists($formValues['id_document_path'])) {
                    @unlink($formValues['id_document_path']);
                }
                if (isset($resumePath) && file_exists($resumePath)) {
                    @unlink($resumePath);
                }
                $errors['database'] = "Registration failed: " . $e->getMessage();
                error_log("Database error: " . $e->getMessage());
            }
        }
    }
}
if (isset($_SESSION['signup_data'])) {
    $formValues = array_merge($formValues, $_SESSION['signup_data']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forwork - Sign Up</title>
    <link rel="stylesheet" href="../src/output.css" />
    <link rel="stylesheet" href="../css/signup.css" />
    <link rel="stylesheet" href="../css/Home.css" />
    <link rel="stylesheet" href="../assets/fontawesome-free-6.7.2-web/css/all.min.css" />
    <!-- Icon Web -->
    <link rel="icon" href="../components/image/Logo.png" type="image/png">
</head>

<body>
    <nav class="signup-nav">
        <div class="signup-nav__container">
            <a href="HomePage.php">
                <h1 class="signup-nav__logo">Forwork</h1>
            </a>
        </div>
    </nav>
    <!-- Form Container -->
    <div class="signup">
        <div class="signup__container">
            <h2 class="signup__title">Sign up to find task</h2>
            <p class="signup__subtext">
                Already have an account? <a href="Login.php">Log in</a>
            </p>

            <!-- Step Indicators -->
            <div class="signup__steps">
                <?php for ($i = 1; $i <= 3; $i++): ?>
                <div class="signup__step <?= $currentStep >= $i ? 'signup__step--active' : '' ?>" data-step="<?= $i ?>">
                    <?= $i ?>
                </div>
                <?php if ($i < 3): ?>
                <div class="signup__step-line"></div>
                <?php endif; ?>
                <?php endfor; ?>
            </div>

            <!-- Error Messages -->
            <?php if (!empty($errors)): ?>
            <div class="signup__error">
                <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Step 1 Form - Personal Information -->
            <?php if ($currentStep == 1): ?>
            <form method="POST" action="signup.php?step=1" class="signup__form" id="step1-form"
                enctype="multipart/form-data">
                <input type="hidden" name="step1" value="1">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <!-- User Type Selection -->
                <div class="signup__form-group signup__form-group--full">
                    <label>You are signing up as a:</label>
                    <div class="signup__radio-options">
                        <label class="signup__radio-option">
                            <input type="radio" name="user_type" value="provider"
                                <?= ($formValues['user_type'] === 'provider') ? 'checked' : '' ?> required />
                            <span>Service Provider (I want to find work)</span>
                        </label>
                        <label class="signup__radio-option">
                            <input type="radio" name="user_type" value="client"
                                <?= ($formValues['user_type'] === 'client') ? 'checked' : '' ?> />
                            <span>Client (I need to hire someone)</span>
                        </label>
                    </div>
                </div>

                <!-- First and Last Name -->
                <div class="signup__form-row">
                    <div class="signup__form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName"
                            value="<?= htmlspecialchars($formValues['firstName']) ?>" required />
                        <?php if (isset($errors['firstName'])): ?>
                        <span class="signup__error-text"><?= htmlspecialchars($errors['firstName']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="signup__form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName"
                            value="<?= htmlspecialchars($formValues['lastName']) ?>" required />
                        <?php if (isset($errors['lastName'])): ?>
                        <span class="signup__error-text"><?= htmlspecialchars($errors['lastName']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Email Address -->
                <div class="signup__form-group signup__form-group--full">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($formValues['email']) ?>"
                        required />
                    <?php if (isset($errors['email'])): ?>
                    <span class="signup__error-text"><?= htmlspecialchars($errors['email']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Password Fields -->
                <div class="signup__form-row">
                    <div class="signup__form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="pass" required minlength="8" />
                        <?php if (isset($errors['password'])): ?>
                        <span class="signup__error-text"><?= htmlspecialchars($errors['password']) ?></span>
                        <?php endif; ?>
                        <small class="signup__hint">Must be at least 8 characters</small>
                    </div>
                    <div class="signup__form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" class="pass" id="confirmPassword" name="confirmPassword" required />
                        <?php if (isset($errors['confirmPassword'])): ?>
                        <span class="signup__error-text"><?= htmlspecialchars($errors['confirmPassword']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Phone Number & Date of Birth -->
                <div class="signup__form-row">
                    <div class="signup__form-group">
                        <label for="phone">Phone Number</label>
                        <div class="signup__phone-input">
                            <select id="country-code" name="countryCode" required style="width: 120px;">
                                <option value="">Select code</option>
                                <?php
                                $countryCodes = [
                                    '+1' => 'USA (+1)',
                                    '+44' => 'UK (+44)',
                                    '+961' => 'Lebanon (+961)',
                                    '+966' => 'Saudi Arabia (+966)',
                                    '+971' => 'UAE (+971)',
                                    '+962' => 'Jordan (+962)',
                                    '+20' => 'Egypt (+20)',
                                    '+90' => 'Turkey (+90)',
                                    '+33' => 'France (+33)',
                                    '+49' => 'Germany (+49)',
                                    '+81' => 'Japan (+81)',
                                    '+86' => 'China (+86)',
                                    '+91' => 'India (+91)',
                                    '+7' => 'Russia (+7)',
                                    '+55' => 'Brazil (+55)'
                                ];
                                
                                foreach ($countryCodes as $code => $label) {
                                    $selected = ($formValues['countryCode'] === $code) ? 'selected' : '';
                                    echo "<option value=\"$code\" $selected>$label</option>";
                                }
                                ?>
                            </select>
                            <input type="tel" id="phone" name="phone"
                                value="<?= htmlspecialchars($formValues['phone']) ?>" required
                                placeholder="Enter phone number" pattern="[0-9]{7,15}"
                                title="Please enter a valid phone number (7-15 digits)" />
                        </div>
                        <?php if (isset($errors['phone']) || isset($errors['countryCode'])): ?>
                        <span class="signup__error-text">
                            <?= htmlspecialchars($errors['phone'] ?? '') ?>
                            <?= isset($errors['phone']) && isset($errors['countryCode']) ? '<br>' : '' ?>
                            <?= htmlspecialchars($errors['countryCode'] ?? '') ?>
                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="signup__form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob" value="<?= htmlspecialchars($formValues['dob']) ?>"
                            required max="<?= date('Y-m-d', strtotime('-18 years')) ?>"
                            title="You must be at least 18 years old" />
                        <?php if (isset($errors['dob'])): ?>
                        <span class="signup__error-text"><?= htmlspecialchars($errors['dob']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Profile Photo Upload -->
                <div class="signup__form-group signup__form-group--full">
                    <label for="profile_photo">Profile Photo</label>
                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*" required />
                    <small class="signup__file-note">Accepted formats: JPG, PNG, GIF (max 5MB)</small>
                    <?php if (isset($errors['profile_photo'])): ?>
                    <span class="signup__error-text"><?= htmlspecialchars($errors['profile_photo']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- ID Document Upload -->
                <div class="signup__form-group signup__form-group--full">
                    <label for="id_document">ID Document (Passport/ID Card)</label>
                    <input type="file" id="id_document" name="id_document" accept=".pdf,.jpg,.jpeg,.png" required />
                    <small class="signup__file-note">Accepted formats: PDF, JPG, PNG (max 5MB)</small>
                    <?php if (isset($errors['id_document'])): ?>
                    <span class="signup__error-text"><?= htmlspecialchars($errors['id_document']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Gender Selection -->
                <div class="signup__form-group signup__form-group--full">
                    <label>Gender</label>
                    <div class="signup__gender-options">
                        <label class="signup__gender-option">
                            <input type="radio" name="gender" value="male"
                                <?= $formValues['gender'] === 'male' ? 'checked' : '' ?> required /> Male
                        </label>
                        <label class="signup__gender-option">
                            <input type="radio" name="gender" value="female"
                                <?= $formValues['gender'] === 'female' ? 'checked' : '' ?> required /> Female
                        </label>
                    </div>
                    <?php if (isset($errors['gender'])): ?>
                    <span class="signup__error-text"><?= htmlspecialchars($errors['gender']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Nationality & Location -->
                <div class="signup__form-row">
                    <div class="signup__form-group">
                        <label for="nationality">Nationality</label>
                        <input type="text" id="nationality" name="nationality"
                            value="<?= htmlspecialchars($formValues['nationality']) ?>" required
                            placeholder="Enter your nationality" />
                        <?php if (isset($errors['nationality'])): ?>
                        <span class="signup__error-text"><?= htmlspecialchars($errors['nationality']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="signup__form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country"
                            value="<?= htmlspecialchars($formValues['country']) ?>" required
                            placeholder="Enter your country" />
                        <?php if (isset($errors['country'])): ?>
                        <span class="signup__error-text"><?= htmlspecialchars($errors['country']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="signup__form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" value="<?= htmlspecialchars($formValues['city']) ?>"
                            required placeholder="Enter your city" />
                        <?php if (isset($errors['city'])): ?>
                        <span class="signup__error-text"><?= htmlspecialchars($errors['city']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="signup__btn">Next</button>
            </form>
            <?php endif; ?>

            <!-- Step 2 Form - Verification -->
            <?php if ($currentStep == 2): ?>
            <form method="POST" action="signup.php?step=2" class="signup__form" id="step2-form">
                <input type="hidden" name="step2" value="1">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="signup__form-group">
                    <label for="verificationCode">Verification Code</label>

                    <div class="signup__verification-display">
                        <p class="signup__verification-title">Your verification code is:</p>
                        <p class="signup__verification-code"><?= $_SESSION['verification_code'] ?></p>
                        <p class="signup__verification-note">(Enter this code below to continue)</p>
                    </div>

                    <input type="text" id="verificationCode" name="verificationCode" required maxlength="4"
                        pattern="\d{4}" title="Please enter the 4-digit code"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4)">

                    <?php if (isset($errors['verificationCode'])): ?>
                    <span class="signup__error-text"><?= htmlspecialchars($errors['verificationCode']) ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="signup__btn">Verify</button>

                <div class="signup__back-link">
                    <a href="signup.php?step=1">
                        &larr; Back to previous step
                    </a>
                </div>
            </form>
            <?php endif; ?>

            <!-- Step 3 Form - Professional Information -->
            <?php if ($currentStep == 3): ?>
            <?php if ($_SESSION['signup_data']['user_type'] === 'client'): ?>
            <!-- Show a simplified form for clients -->
            <form method="POST" action="signup.php?step=3" class="signup__form" id="step3-form">
                <input type="hidden" name="step3" value="1">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="signup__form-group">
                    <label for="needed_services">What type of services do you need?</label>
                    <input type="text" id="needed_services" name="needed_services"
                        value="<?= htmlspecialchars($formValues['needed_services'] ?? '') ?>"
                        placeholder="e.g., Plumbing, Electrical" required>
                    <?php if (isset($errors['needed_services'])): ?>
                    <span class="signup__error-text"><?= htmlspecialchars($errors['needed_services']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="signup__form-group">
                    <label class="signup__terms">
                        <input type="checkbox" name="terms" required />
                        <span>I agree to the <a href="#" class="signup__terms-link">Terms & Conditions</a></span>
                    </label>
                    <?php if (isset($errors['terms'])): ?>
                    <span class="signup__error-text"><?= htmlspecialchars($errors['terms']) ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="signup__btn">Complete Registration</button>
            </form>
            <?php else: ?>
            <!-- Show all the professional fields for providers -->
            <form method="POST" action="signup.php?step=3" class="signup__form" id="step3-form"
                enctype="multipart/form-data">
                <input type="hidden" name="step3" value="1">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <!-- Service Category -->
                <div class="signup__form-group">
                    <label for="service_category">What type of work do you do?</label>
                    <select id="service_category" name="service_category" required>
                        <option value="">Select your service</option>
                        <option value="home_repair"
                            <?= ($formValues['service_category'] ?? '') === 'home_repair' ? 'selected' : '' ?>>Home
                            Repairs</option>
                        <option value="plumbing"
                            <?= ($formValues['service_category'] ?? '') === 'plumbing' ? 'selected' : '' ?>>Plumbing
                        </option>
                        <option value="electrical"
                            <?= ($formValues['service_category'] ?? '') === 'electrical' ? 'selected' : '' ?>>Electrical
                        </option>
                        <option value="gardening"
                            <?= ($formValues['service_category'] ?? '') === 'gardening' ? 'selected' : '' ?>>
                            Gardening/Landscaping</option>
                        <option value="cleaning"
                            <?= ($formValues['service_category'] ?? '') === 'cleaning' ? 'selected' : '' ?>>Cleaning
                        </option>
                        <option value="moving"
                            <?= ($formValues['service_category'] ?? '') === 'moving' ? 'selected' : '' ?>>Moving Help
                        </option>
                        <option value="painting"
                            <?= ($formValues['service_category'] ?? '') === 'painting' ? 'selected' : '' ?>>Painting
                        </option>
                        <option value="other"
                            <?= ($formValues['service_category'] ?? '') === 'other' ? 'selected' : '' ?>>Other Service
                        </option>
                    </select>
                    <?php if (isset($errors['service_category'])): ?>
                    <span class="signup__error-text"><?= htmlspecialchars($errors['service_category']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Specific Services Offered -->
                <div class="signup__form-group">
                    <label for="services">What specific services do you offer? (comma separated)</label>
                    <input type="text" id="services" name="services"
                        value="<?= htmlspecialchars($formValues['services'] ?? '') ?>"
                        placeholder="e.g., Pipe fixing, toilet repair, faucet installation" required />
                    <?php if (isset($errors['services'])): ?>
                    <span class="signup__error-text"><?= htmlspecialchars($errors['services']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Experience -->
                <div class="signup__form-row">
                    <div class="signup__form-group">
                        <label for="years_experience">Years of Experience</label>
                        <select id="years_experience" name="years_experience" required>
                            <option value="">Select years</option>
                            <option value="1" <?= ($formValues['years_experience'] ?? '') === '1' ? 'selected' : '' ?>>
                                Less than 1 year</option>
                            <option value="2" <?= ($formValues['years_experience'] ?? '') === '2' ? 'selected' : '' ?>>
                                1-2 years</option>
                            <option value="5" <?= ($formValues['years_experience'] ?? '') === '5' ? 'selected' : '' ?>>
                                3-5 years</option>
                            <option value="10"
                                <?= ($formValues['years_experience'] ?? '') === '10' ? 'selected' : '' ?>>5-10 years
                            </option>
                            <option value="20"
                                <?= ($formValues['years_experience'] ?? '') === '20' ? 'selected' : '' ?>>10+ years
                            </option>
                        </select>
                        <?php if (isset($errors['years_experience'])): ?>
                        <span class="signup__error-text"><?= htmlspecialchars($errors['years_experience']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="signup__form-group">
                        <label for="hourly_rate">Hourly Rate (USD)</label>
                        <input type="number" id="hourly_rate" name="hourly_rate"
                            value="<?= htmlspecialchars($formValues['hourly_rate'] ?? '') ?>" min="5" max="100" step="5"
                            required />
                        <?php if (isset($errors['hourly_rate'])): ?>
                        <span class="signup__error-text"><?= htmlspecialchars($errors['hourly_rate']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Availability -->
                <div class="signup__form-group">
                    <label for="availability">When are you available?</label>
                    <select id="availability" name="availability" required>
                        <option value="">Select availability</option>
                        <option value="weekdays"
                            <?= ($formValues['availability'] ?? '') === 'weekdays' ? 'selected' : '' ?>>Weekdays
                        </option>
                        <option value="weekends"
                            <?= ($formValues['availability'] ?? '') === 'weekends' ? 'selected' : '' ?>>Weekends
                        </option>
                        <option value="evenings"
                            <?= ($formValues['availability'] ?? '') === 'evenings' ? 'selected' : '' ?>>Evenings
                        </option>
                        <option value="flexible"
                            <?= ($formValues['availability'] ?? '') === 'flexible' ? 'selected' : '' ?>>Flexible
                        </option>
                        <option value="full_time"
                            <?= ($formValues['availability'] ?? '') === 'full_time' ? 'selected' : '' ?>>Full-time
                        </option>
                    </select>
                    <?php if (isset($errors['availability'])): ?>
                    <span class="signup__error-text"><?= htmlspecialchars($errors['availability']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Tools and Transportation -->
                <div class="signup__form-row">
                    <div class="signup__form-group">
                        <label>Do you have your own tools/equipment?</label>
                        <div class="signup__radio-options">
                            <label class="signup__radio-option">
                                <input type="radio" name="tools_available" value="yes"
                                    <?= ($formValues['tools_available'] ?? '') === 'yes' ? 'checked' : '' ?> required />
                                Yes
                            </label>
                            <label class="signup__radio-option">
                                <input type="radio" name="tools_available" value="no"
                                    <?= ($formValues['tools_available'] ?? '') === 'no' ? 'checked' : '' ?> /> No
                            </label>
                        </div>
                        <?php if (isset($errors['tools_available'])): ?>
                        <span class="signup__error-text"><?= htmlspecialchars($errors['tools_available']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="signup__form-group">
                        <label>Do you have transportation?</label>
                        <div class="signup__radio-options">
                            <label class="signup__radio-option">
                                <input type="radio" name="transportation" value="yes"
                                    <?= ($formValues['transportation'] ?? '') === 'yes' ? 'checked' : '' ?> required />
                                Yes
                            </label>
                            <label class="signup__radio-option">
                                <input type="radio" name="transportation" value="no"
                                    <?= ($formValues['transportation'] ?? '') === 'no' ? 'checked' : '' ?> /> No
                            </label>
                        </div>
                        <?php if (isset($errors['transportation'])): ?>
                        <span class="signup__error-text"><?= htmlspecialchars($errors['transportation']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Previous Work Examples -->
                <div class="signup__form-group">
                    <label for="previous_work">Examples of previous work (photos or descriptions)</label>
                    <textarea id="previous_work" name="previous_work" rows="3"
                        placeholder="Describe your experience or paste links to photos"><?= htmlspecialchars($formValues['previous_work'] ?? '') ?></textarea>
                </div>

                <!-- Certifications -->
                <div class="signup__form-group">
                    <label for="certifications">Certifications or Licenses (if any)</label>
                    <textarea id="certifications" name="certifications" rows="2"
                        placeholder="e.g., Plumbing license, electrician certification"><?= htmlspecialchars($formValues['certifications'] ?? '') ?></textarea>
                </div>

                <!-- Languages -->
                <div class="signup__form-group">
                    <label for="languages">Languages you speak</label>
                    <input type="text" id="languages" name="languages"
                        value="<?= htmlspecialchars($formValues['languages'] ?? '') ?>"
                        placeholder="English, Arabic, French" />
                </div>

                <!-- About You -->
                <div class="signup__form-group">
                    <label for="description">Tell us about your work experience</label>
                    <textarea id="description" name="description" rows="4" required
                        placeholder="Describe your skills, experience, and what makes you a good worker"><?= htmlspecialchars($formValues['description'] ?? '') ?></textarea>
                    <?php if (isset($errors['description'])): ?>
                    <span class="signup__error-text"><?= htmlspecialchars($errors['description']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Resume Upload -->
                <div class="signup__form-group">
                    <label for="resume">Upload Resume (PDF, DOC, DOCX - max 5MB)</label>
                    <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" />
                    <?php if (isset($errors['resume'])): ?>
                    <span class="signup__error-text"><?= htmlspecialchars($errors['resume']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Terms Agreement -->
                <div class="signup__form-group">
                    <label class="signup__terms">
                        <input type="checkbox" name="terms" required />
                        <span>I agree to the <a href="#" class="signup__terms-link">Terms & Conditions</a></span>
                    </label>
                    <?php if (isset($errors['terms'])): ?>
                    <span class="signup__error-text"><?= htmlspecialchars($errors['terms']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="signup__btn">Complete Registration</button>
            </form>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <script src="../js/signup.js"></script>
    <script src="../js/Home.js"></script>
</body>

</html>