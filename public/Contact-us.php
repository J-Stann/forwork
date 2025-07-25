<?php
require_once '../config/connection.php';
$name = $email = $subject = $message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
        $errors['email'] = 'Invalid email format';
    }
    $stmt = $con->prepare("INSERT INTO contact_submissions (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit();
        } else {
            $baseUrl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            $path = strtok($_SERVER['REQUEST_URI'], '?');
            header("Location: $baseUrl$path?success=1");
            exit();
        }
    }
} 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - forwork</title>

    <!-- Link to Tailwind CSS -->
    <link rel="stylesheet" href="../src/output.css">

    <!-- Link to External CSS -->
    <link rel="stylesheet" href="../css/Contact-us.css">
    <link rel="stylesheet" href="../css/Home.css">

    <!-- Link to fontawesome  -->
    <link rel="stylesheet" href="../assets/fontawesome-free-6.7.2-web/css/all.min.css">

    <!-- This is for swiper mode -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Link of Ajax -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Icon of the top -->
    <link rel="icon" href="../components/image/Logo.png" type="image/png" sizes="32x32" />

<body>

    <!-- Notification System -->
    <div class=" notification" id="notification">
        <i class="fas fa-check-circle"></i>
        <span id="notification-message"></span>
    </div>

    <nav class="bg-white fixed w-full">
        <div class="nav-main max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="HomePage.php">
                <h1 href="HomePage.php" class="forwork-logo">Forwork</h1>
            </a>
            <button class="hamburger-menu md:hidden" aria-label="Open navigation">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="items-center justify-between w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
                <ul
                    class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-white md:space-x-8 md:flex-row md:mt-0 md:border-0">
                    <li class="relative">
                        <button class="dropdown-btn py-2 px-3 md:hover:text-blue-700 md:p-0 flex items-center gap-2">
                            Start Hiring <i class="fa-solid fa-angle-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <ul>
                                <li><a href="Login.php">Post a Task</a></li>
                                <li><a href="Top-Talent.php">Hire an Expert</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="relative">
                        <button class="dropdown-btn py-2 px-3 md:hover:text-blue-700 md:p-0 flex items-center gap-2">
                            Find Work <i class="fa-solid fa-angle-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <ul>
                                <li><a href="Login.php">Browse a Job</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="relative">
                        <button class="dropdown-btn py-2 px-3 md:hover:text-blue-700 md:p-0 flex items-center gap-2">
                            <a href="Why-Forwork.php">Why Forwork ?</a>
                        </button>
                    </li>
                    <li class="relative">
                        <button class="dropdown-btn py-2 px-3 md:hover:text-blue-700 md:p-0 flex items-center gap-2">
                            Other <i class="fa-solid fa-angle-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <ul>
                                <li><a href="How-it-work.php">How It work?</a></li>
                                <li><a href="Contact-us.php"> Contact Us</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="logn-in-sign">
                <a href="Login.php">
                    <div class="log-in">
                        <div class="text-wrapper">Log In</div>
                    </div>
                </a>
                <a href="signup.php">
                    <div class="register">
                        <div class="div">Register</div>
                    </div>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="contact-container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="HomePage.php">Home</a> / <span>Contact Us</span>
        </div>
        <!-- Contact Header -->
        <section class="contact-header">
            <h1>Get in Touch</h1>
            <p>Have questions or need assistance? We're here to help.</p>
            <?php if (!empty($errors['database'])): ?>
            <div class="error-message">
                <?= htmlspecialchars($errors['database']) ?>
            </div>
            <?php endif; ?>
        </section>
        <!-- Contact Methods - Three Cards -->
        <div class="contact-methods">
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Email Us</h3>
                <p>For general inquiries and support</p>
                <a href="mailto:info123forwork@gmail.com" class="contact-link">support@forwork.com</a>
            </div>
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-hashtag"></i>
                </div>
                <h3>Social Media</h3>
                <p>Connect with us online</p>
                <a href="https://www.instagram.com/123forwork/" class="contact-link">@forwork</a>
            </div>
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <h3>Help Center</h3>
                <p>Find answers to common questions</p>
            </div>
        </div>



        <!-- form -->
        <section class="contact-form-section">
            <div class="form-container">
                <h2>Send Us a Message</h2>
                <form id="contactForm" method="POST" action="">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                        <?php if (!empty($errors['name'])): ?>
                        <span class="error"><?= htmlspecialchars($errors['name']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                        <?php if (!empty($errors['email'])): ?>
                        <span class="error"><?= htmlspecialchars($errors['email']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <select id="subject" name="subject" required>
                            <option value="">Select a subject</option>
                            <option value="Technical-Issue" <?= $subject === 'Technical-Issue' ? 'selected' : '' ?>>
                                Technical Issue</option>
                            <option value="employer" <?= $subject === 'employer' ? 'selected' : '' ?>>Employer Support
                            </option>
                            <option value="Bug" <?= $subject === 'Bug' ? 'selected' : '' ?>>Bug
                            </option>
                        </select>
                        <?php if (!empty($errors['subject'])): ?>
                        <span class="error"><?= htmlspecialchars($errors['subject']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="message">Your Message</label>
                        <textarea id="message" name="message" rows="5"
                            required><?= htmlspecialchars($message) ?></textarea>
                        <?php if (!empty($errors['message'])): ?>
                        <span class="error"><?= htmlspecialchars($errors['message']) ?></span>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="submit-btn">
                        <span id="submitText">Send Message</span>
                    </button>
                </form>
            </div>

            <div class="faq-container">
                <h2>Frequently Asked Questions</h2>
                <div class="faq-item">
                    <button class="faq-question">
                        How do I create an account as a job seeker?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Click on the "Register" button at the top right of the page and select "I'm looking for
                            work". Follow the step-by-step process to set up your profile.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        How can employers post jobs on Forwork?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>After registering as an employer, go to your dashboard and click "Post a Job". Fill in the
                            details about the position and requirements.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question">
                        What payment methods do you accept?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>
                            Currently, all services on Forwork are completely free to use! In the future, we may
                            introduce premium features with subscription options credit cards, PayPal, and
                            bank transfers.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-logo">
                    <a href="HomePage.php">
                        <span class="logo-name">Forwork</span>
                        <video src="../components/video/Animated Logo.mp4" autoplay loop muted></video>
                    </a>
                </div>
                <div class="footer-links">
                    <div>
                        <h2>Learn more</h2>
                        <ul>
                            <li><a href="signup.php">Start Hiring</a></li>
                            <li><a href="Login.php">Find work</a></li>
                            <li><a href="Why-Forwork.php">Why us</a></li>
                            <li><a href="Top-Talent.php">Top Talent</a></li>
                            <li><a href="How-it-work.php">How it Work </a></li>
                            <li><a href="Contact-us.php"> Contact us</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2>Follow us</h2>
                        <ul>
                            <li><a href="https://github.com/J-Stann">Github</a></li>
                            <li><a href="https://discord.gg/zt9ZugdB">Discord</a></li>
                            <li><a href="www.linkedin.com/in/forwork-forwork-75371a363">LinkedIn</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2>Legal</h2>
                        <ul>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms & Conditions</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr />
            <div class="footer-bottom">
                <span class="footer-text">© 2025 Forwork. All Rights Reserved.</span>
                <div class="footer-social-links">
                    <a href="https://www.facebook.com/profile.php?id=61575936150829" class="facebook"><i
                            class="fab fa-facebook-f"></i></a>
                    <a href="https://discord.gg/zt9ZugdB" class="discord"><i class="fab fa-discord"></i></a>
                    <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
                    <a href="https://github.com/J-Stann" class="github"><i class="fab fa-github"></i></a>
                    <a href="www.linkedin.com/in/forwork-forwork-75371a363" class="linkedin"><i
                            class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- This for JS Home -->
    <script src="../js/Contact-us.js"></script>
    <script src="../js/Home.js"></script>

</body>

</html>