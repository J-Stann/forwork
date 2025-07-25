<?php
session_start();

require_once __DIR__ . '/../config/connection.php';

$email = $password = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (empty($email)) {
        $error = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } elseif (empty($password)) {
        $error = 'Password is required';
    } else {
        $sql = "SELECT id, first_name, last_name, email, password, user_type FROM users WHERE email = ?";
        $stmt = $con->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['user_type'] = $user['user_type'];
                    $_SESSION['logged_in'] = true;
                    header('Location: Main.php');
                    exit();
                } else {
                    $error = 'Invalid email or password';
                }
            } else {
                $error = 'Invalid email or password';
            }
            $stmt->close();
        } else {
            $error = 'Database error. Please try again later.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forwork - login</title>

    <!-- Tailwind CSS for styling -->
    <link rel="stylesheet" href="../src/output.css" />

    <!-- Custom External CSS for additional styles -->
    <link rel="stylesheet" href="../css/Login.css" />

    <!-- Font Awesome Icons for icons in the UI -->
    <link rel="stylesheet" href="../assets/fontawesome-free-6.7.2-web/css/all.min.css" />

    <link rel="icon" href="../components/image/Logo.png" type="image/png" sizes="32x32" />

</head>

<body>
    <!-- Navbar: Logo only -->
    <nav class="bg-white fixed w-full">
        <div class="nav-main max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="../index.php">
                <h1 class="forwork-logo">Forwork</h1>
            </a>
        </div>
    </nav>

    <!-- Form Container -->
    <div class="form-container">
        <div class="form-box">
            <h2 class="form-title">Welcome Back! Please Log In</h2>

            <p class="form-subtext">
                Don't have an account? <a href="signup.php">Sign up</a>
            </p>

            <?php if (!empty($error)): ?>
            <div class="form-message text-red-500 mb-3" id="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form class="form" method="POST" action="login.php">
                <!-- Email Field -->
                <div class="form-group relative">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="input-field pl-10"
                        value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter Your Email" required />
                    <i class="fas fa-envelope absolute left-3 text-gray-500"></i>
                </div>

                <!-- Password Field -->
                <div class="form-group relative">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="input-field pl-10 pr-10"
                        placeholder="Enter Your Password" required />
                    <i class="fas fa-lock absolute left-3 text-gray-500"></i>
                </div>

                <!-- Remember + Forgot -->
                <div class="form-group flex">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" /> Remember Me
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">Login</button>
            </form>
        </div>
    </div>

    <!-- JavaScript File for additional behavior -->
    <script src="../js/Login.js"></script>
</body>

</html>