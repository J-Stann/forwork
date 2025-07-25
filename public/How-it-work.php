    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>forwork</title>

        <!-- Link to Tailwind CSS -->
        <link rel="stylesheet" href="../src/output.css">

        <!-- Link to External CSS -->
        <link rel="stylesheet" href="../css/How-it-work.css">
        <link rel="stylesheet" href="../css/Home.css">

        <!-- Link to fontawesome  -->
        <link rel="stylesheet" href="../assets/fontawesome-free-6.7.2-web/css/all.min.css">

        <!-- This is for swiper mode -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

        <!-- Icon of the top -->
        <link rel="icon" href="../components/image/Logo.png" type="image/png" sizes="32x32" />

    <body>
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
                            <button
                                class="dropdown-btn py-2 px-3 md:hover:text-blue-700 md:p-0 flex items-center gap-2">
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
                            <button
                                class="dropdown-btn py-2 px-3 md:hover:text-blue-700 md:p-0 flex items-center gap-2">
                                Find Work <i class="fa-solid fa-angle-down"></i>
                            </button>
                            <div class="dropdown-menu">
                                <ul>
                                    <li><a href="Login.php">Browse a Job</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="relative">
                            <button
                                class="dropdown-btn py-2 px-3 md:hover:text-blue-700 md:p-0 flex items-center gap-2">
                                <a href="Why-Forwork.php">Why Forwork ?</a>
                            </button>
                        </li>
                        <li class="relative">
                            <button
                                class="dropdown-btn py-2 px-3 md:hover:text-blue-700 md:p-0 flex items-center gap-2">
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




        <!-- Hero Section -->
        <section class="how-it-works-hero">
            <div class="strict-container">
                <div class="hero-content">
                    <div class="breadcrumb">
                        <a href="HomePage.php">Home</a> / <span>How It Works</span>
                    </div>
                    <h1 class="hero-title">How Forwork Works</h1>
                    <p class="hero-subtitle">Connecting skilled professionals with those who need their services, simply
                        and
                        securely.</p>
                    <div class="hero-buttons">
                        <a href="signup.php" class="btn-primary">Find a Task</a>
                        <a href="Contact-us.php" class="btn-secondary">Contact us</a>
                    </div>
                </div>
                <div class="hero-animation">
                    <div class="animation-circle"></div>
                    <div class="animation-circle"></div>
                    <div class="animation-circle"></div>
                </div>
            </div>
        </section>

        <!-- Tabs Section -->
        <section class="tabs-section">
            <div class="strict-container">
                <div class="tabs-header">
                    <button class="tab-btn active" data-tab="for-clients">For Clients</button>
                    <button class="tab-btn" data-tab="for-professionals">For Professionals</button>
                </div>

                <div class="tab-content active" id="for-clients-tab">
                    <div class="steps-container">
                        <div class="step-card">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h3>Post Your Project</h3>
                                <p>Describe what you need done and set your budget. It's free to post and you'll receive
                                    bids from professionals within minutes.</p>
                                <div class="step-icon">
                                    <i class="fas fa-edit"></i>
                                </div>
                            </div>
                        </div>

                        <div class="step-card">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h3>Review Proposals</h3>
                                <p>Compare bids, portfolios, and reviews. Chat with professionals to ask questions and
                                    clarify details before hiring.</p>
                                <div class="step-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                        </div>

                        <div class="step-card">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h3>Hire the Best Fit</h3>
                                <p>Select your professional and agree on terms. Our platform facilitates secure payments
                                    and
                                    project management.</p>
                                <div class="step-icon">
                                    <i class="fas fa-handshake"></i>
                                </div>
                            </div>
                        </div>

                        <div class="step-card">
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <h3>Collaborate & Pay Securely</h3>
                                <p>Work together through our platform. Release payment only when you're completely
                                    satisfied
                                    with the work.</p>
                                <div class="step-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="for-professionals-tab">
                    <div class="steps-container">
                        <div class="step-card">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h3>Create Your Profile</h3>
                                <p>Showcase your skills, experience, and portfolio. The more complete your profile, the
                                    more
                                    jobs you'll attract.</p>
                                <div class="step-icon">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                        </div>

                        <div class="step-card">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h3>Find Perfect Projects</h3>
                                <p>Browse jobs that match your skills and submit competitive proposals to clients who
                                    need
                                    your expertise.</p>
                                <div class="step-icon">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                            </div>
                        </div>

                        <div class="step-card">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h3>Get Hired</h3>
                                <p>Win projects by demonstrating your value. Communicate clearly and set expectations
                                    with
                                    your clients.</p>
                                <div class="step-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>

                        <div class="step-card">
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <h3>Get Paid Securely</h3>
                                <p>Complete the work to your client's satisfaction and receive payment through our
                                    protected
                                    system.</p>
                                <div class="step-icon">
                                    <i class="fas fa-wallet"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Video Demo Section -->
        <section class="video-demo-section">
            <div class="strict-container">
                <div class="section-header">
                    <h2>See Forwork in Action</h2>
                    <p>Watch our quick demo to see how easy it is to get started</p>
                </div>
                <div class="video-container">
                    <div class="video-wrapper">
                        <div class="play-button">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="video-modal">
                        <div class="modal-content">
                            <span class="close-modal">&times;</span>
                            <iframe class="video-iframe" src="../components/video/demo.mp4" frameborder="0"
                                allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Grid Section -->
        <section class="features-section">
            <div class="strict-container">
                <div class="section-header">
                    <h2>Why Professionals Choose Forwork</h2>
                    <p>Tools and features designed to help you succeed</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Safe Payments</h3>
                        <p>Get paid on time with our secure payment system that holds funds in escrow until work is
                            completed.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3>Grow Your Business</h3>
                        <p>Build your reputation with verified reviews and attract higher-paying clients over time.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h3>Clear Communication</h3>
                        <p>Built-in messaging and file sharing makes collaboration easy with clients anywhere.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3>Work Anywhere</h3>
                        <p>Access projects and communicate with clients from our mobile app or desktop.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="strict-container">
                <div class="cta-content">
                    <h2>Ready to Get Started?</h2>
                    <p>Join thousands of professionals and clients finding success on Forwork</p>
                    <div class="cta-buttons">
                        <a href="Top-Talent.php" class="btn-primary">Hire a Professional</a>
                        <a href="Login.php" class="btn-secondary">Apply as Professional</a>
                    </div>
                </div>
                <div class="cta-decoration">
                    <div class="decoration-circle"></div>
                    <div class="decoration-circle"></div>
                    <div class="decoration-circle"></div>
                </div>
            </div>
        </section>


        <!-- FAQ Section -->
        <section class="faq-section">
            <div class="strict-container">
                <div class="section-header">
                    <h2>Frequently Asked Questions</h2>
                    <p>Find answers to common questions about using Forwork</p>
                </div>
                <div class="faq-container">
                    <div class="faq-item">
                        <button class="faq-question">
                            How much does it cost to post a project?
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Posting a project on Forwork is completely free. You only pay when you hire a
                                professional
                                and agree on a price for the work.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            How do payments work?
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Forwork uses a secure payment system where funds are held in escrow until you approve the
                                work. This protects both clients and professionals.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            What types of services can I find on Forwork?
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>You can find professionals for hundreds of services including home repairs, design work,
                                programming, marketing, and much more.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            How do I know professionals are qualified?
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Professionals are verified by our team and rated by previous clients. You can review
                                their
                                portfolios, certifications, and client feedback before hiring.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question">
                            Can I hire professionals for long-term projects?
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Absolutely! Many professionals on Forwork are available for both short-term tasks and
                                ongoing
                                work arrangements.</p>
                        </div>
                    </div>
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

        <!-- Swiper JS -->
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

        <!-- This for JS Home -->
        <script src="../js/How-it-work.js"></script>
    </body>

    </html>