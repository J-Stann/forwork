<?php
session_start();
require_once __DIR__ . '/../config/connection.php';
$userId = $_GET['user_id'] ?? 0;
if (!$userId) {
    header("Location: signup.php");
    exit;
}   
$stmt = $con->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    header("Location: signup.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful - Forwork</title>
    <link rel="stylesheet" href="../src/output.css">
    <link rel="stylesheet" href="../css/signup-success.css">
    <link rel="icon" href="../components/image/Logo.png" type="image/png">

</head>

<body>
    <div id="loading-screen" class="success-container">
        <div class="success-card">
            <div class="flex justify-center">
                <h1 class="text-3xl font-bold text-indigo-600">Forwork</h1>
            </div>
            <div class="loading-spinner"></div>
            <p class="mt-4 text-gray-600">Preparing your account...</p>
        </div>
    </div>

    <div id="success-message" class="success-container" style="display: none;">
        <div class="success-card">
            <svg class="success-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <h2 class="mt-3 text-xl font-semibold text-gray-900">Registration Successful!</h2>
            <p class="mt-2 text-sm text-gray-600">
                Thank you, <?= htmlspecialchars($user['first_name']) ?>! Your account has been created successfully.
            </p>
            <div class="mt-6">
                <p class="text-sm text-gray-600">
                    We've sent a confirmation email to <?= htmlspecialchars($user['email']) ?>.
                </p>
            </div>
            <div class="mt-6">
                <a href="Login.php" class="success-button">
                    Continue to Login
                </a>
            </div>
        </div>
    </div>

    <script>
    setTimeout(() => {
        document.getElementById('loading-screen').style.display = 'none';
        document.getElementById('success-message').style.display = 'flex';
    }, 8000);
    </script>
</body>

</html>