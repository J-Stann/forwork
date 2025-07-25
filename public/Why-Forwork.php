<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>forwork</title>

    <!-- Link to Tailwind CSS -->
    <link rel="stylesheet" href="../src/output.css">

    <!-- Link to External CSS -->
    <link rel="stylesheet" href="../css/Why-Forwork.css">

    <!-- Link to fontawesome  -->
    <link rel="stylesheet" href="../assets/fontawesome-free-6.7.2-web/css/all.min.css">

    <!-- This is for swiper mode -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Icon of the top -->
    <link rel="icon" href="../components/image/Logo.png" type="image/png" sizes="32x32" />
</head>

<body>

    <!-- It's My navBar The Logo, Link(drp), Button -->
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

    <!-- Section main  -->
    <section class="py-16 bg-white">
        <!-- Breadcrumb -->
        <div class="breadcrumb bg-gray-100 py-3 px-4 rounded-lg max-w-screen-xl mx-auto mb-8">
            <a href="HomePage.php" style="color: #2563eb;">Home</a>
            <span class="text-gray-500 mx-2">/</span>
            <span class="">Why Forwork</span>
        </div>

        <div class="main-container">
            <!-- Text on the left -->
            <div class="why-choose-text">
                <h2 class="section-title">Why Choose Forwork?</h2>
                <p class="section-paragraph">
                    Forwork is your go-to platform, whether you're looking to hire experts for your project or find your
                    next opportunity. We make the process easy, fast, and secure, connecting top talent and job seekers
                    across a range of industries.
                    For employers, we provide access to skilled professionals across multiple fields. Post your job,
                    review candidates, and make hires with ease. For job seekers, we offer a variety of opportunities,
                    from full-time roles to freelance gigs, so you can find the perfect fit for your career.
                    With Forwork, you get a seamless experience, powerful tools for managing your projects or
                    applications, and the confidence that comes with working in a secure environment.
                </p>
                <div class="cta-container">
                    <a href="signup.php" class="cta-button">Sign Up</a>
                </div>
            </div>

            <!-- Image on the right -->
            <div class="image-wrapper">
                <img src="../components/image/img-Why-Forwork/Why-Forwork ( Eng ).png" alt="Why Forwork"
                    class="image-style">
            </div>
        </div>
    </section>


    <!-- Connection & Growth Section -->
    <section class="growth-section">
        <div class="container">
            <div class="top-content">
                <div class="left-side">
                    <h2>Built to connect talent with meaningful opportunities and foster growth through valuable
                        collaborations.</h2>
                </div>
                <div class="right-side">
                    <p>
                        We create real connections that help people grow faster. Forwork is here to support individuals
                        and businesses looking to hire, collaborate, and achieve more. Whether you're a freelancer, job
                        seeker, or employer, Forwork is your starting point. From gigs to full-time roles, we help you
                        showcase your skills, build a strong profile, and connect with the right people. Join thousands
                        of successful users on our trusted platform.
                    </p>
                </div>
            </div>
            <div class="divider"></div>
            <div class="stats">
                <div class="stat-box">
                    <h3 class="count" data-target="1">0</h3>
                    <p>Solo developer since 2025</p>
                </div>

                <div class="stat-box">
                    <h3 class="count" data-target="100000">0</h3>
                    <p>Users Registering & sharing</p>
                </div>

                <div class="stat-box">
                    <h3 class="count" data-target="1500">0</h3>
                    <p>Porple who verified</p>
                </div>
            </div>
        </div>
    </section>


    <section class="our-story-section">
        <div class="our-story-container">
            <!-- Image -->
            <div class="our-story-image-wrapper">
                <img src="../components/image/img-Why-Forwork/Why-Forwork ( Watch ).png" alt="Our journey"
                    class="our-story-image">
            </div>

            <!-- Text Content -->
            <div class="our-story-text-content animate-content">
                <h2 class="our-story-heading">From an Idea to Forwork</h2>
                <p class="our-story-text">
                    What began in a small dorm room is now a growing platform that helps individuals showcase their
                    skills and find meaningful work.
                </p>
                <p class="our-story-text">
                    <i class="fas fa-shield-alt" style="color: #2563eb; margin-right: 8px;"></i>
                    Security & trust are our top priorities—we ensure your data is protected and your experience safe.
                </p>
                <p class="our-story-text">
                    <i class="fas fa-lightbulb" style="color: #f59e0b; margin-right: 8px;"></i>
                    Innovation drives us—we're constantly improving to bring you smarter ways to connect and work.
                </p>
                <p class="our-story-text">
                    <i class="fas fa-users" style="color: #10b981; margin-right: 8px;"></i>
                    Community matters—we’re building a space where everyone has a chance to shine.
                </p>
                <button class="our-story-btn">Join Our Journey</button>
            </div>
        </div>
    </section>


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

    <script src="../js/Why-Forwork.js"></script>
</body>

</html>