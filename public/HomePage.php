<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>forwork</title>

    <!-- Link to Tailwind CSS -->
    <link rel="stylesheet" href="../src/output.css">

    <!-- Link to External CSS -->
    <link rel="stylesheet" href="../css/Home.css">

    <!-- Link to fontawesome  -->
    <link rel="stylesheet" href="../assets/fontawesome-free-6.7.2-web/css/all.min.css">

    <!-- This is for swiper mode -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Icon Web -->
    <link rel="icon" href="../components/image/Logo.png" type="image/png" sizes="32x32" />

<body>

    <!-- It's My navBar The Logo Link(drp) Buttom -->
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



    <section class="card-search-section">
        <div class="left-content">
            <h1>Find the Perfect Job</h1>
            <h2>
                Browse through hundreds of local opportunities and connect with people looking for help with tasks like
                home repairs, electrical work, and more.
            </h2>
            <div class="search-container">
                <div class="search-box">
                    <input type="text" id="searchInput" class="search-input" placeholder="Search for jobs...">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="location-box">
                    <i class="fas fa-map-marker-alt location-icon"></i>
                    <input type="text" id="locationInput" placeholder="Location">
                </div>
                <div class="search-btn-container">
                    <button onclick="showResults()" class="search-btn">
                        <i class="fas fa-search search-btn-icon"></i>
                        Search
                    </button>
                </div>
            </div>
            <div id="resultsCard"
                style="display: none; margin-top: 20px; background: white; border-radius: 8px; padding: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div id="resultsText"></div>
                    <button onclick="hideResults()"
                        style="background: none; border: none; color: #718096; cursor: pointer;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <?php
                $servername = "127.0.0.1";
                $username = "root";
                $password = "";
                $dbname = "forwork";
                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "SELECT city, COUNT(*) as task_count FROM tasks GROUP BY city";
                $result = $conn->query($sql);
                $taskData = array();
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $taskData[$row["city"]] = $row["task_count"];
                    }
                }
                $conn->close();
                $taskDataJson = json_encode($taskData);
                ?>
            <script>
            const taskData = <?php echo $taskDataJson; ?>;

            function showResults() {
                const location = document.getElementById('locationInput').value.trim();
                const resultsCard = document.getElementById('resultsCard');
                const resultsText = document.getElementById('resultsText');

                if (!location) {
                    alert('Please enter a location');
                    return;
                }
                const count = taskData[location] || 0;
                resultsText.innerHTML = `
                <span style="font-weight: bold;">${count}</span> 
                tasks found in 
                <span style="font-weight: bold;">${location}</span>
            `;
                resultsCard.style.display = 'block';
            }

            function hideResults() {
                document.getElementById('resultsCard').style.display = 'none';
            }
            </script>
        </div>
    </section>

    <!-- This section for Trusted company -->
    <div class="company-container">
        <div class="company-header">
            <h2>Companies Who Trust Our Website</h2>
            <p>These are the trusted companies that believe in our platform and use our services.</p>
        </div>
        <div class="company-trusted-logos-swiper swiper-container">
            <div class="company-swiper-wrapper swiper-wrapper">
                <div class="company-trusted-logo-slide swiper-slide">
                    <img src="../components/image/img-company/AWS.jpg" alt="AWS" />
                </div>
                <div class="company-trusted-logo-slide swiper-slide">
                    <img src="../components/image/img-company/Google.jpg" alt="Google" />
                </div>
                <div class="company-trusted-logo-slide swiper-slide">
                    <img src="../components/image/img-company/IBM.jpg" alt="IBM" />
                </div>
                <div class="company-trusted-logo-slide swiper-slide">
                    <img src="../components/image/img-company/Meta.png" alt="Meta" />
                </div>
                <div class="company-trusted-logo-slide swiper-slide">
                    <img src="../components/image/img-company/Microsoft.jpg" alt="Microsoft" />
                </div>
                <div class="company-trusted-logo-slide swiper-slide">
                    <img src="../components/image/img-company/SaleForce.jpg" alt="SaleForce" />
                </div>
            </div>
        </div>
    </div>

    <section class="local-services">
        <div class="services-header">
            <h2>Find Local Service Professionals</h2>
            <p>Need help with home services or looking for work in your neighborhood? We connect skilled locals with
                people who need their services.</p>
        </div>

        <div class="services-grid">
            <!-- Home Services -->
            <div class="service-category">
                <div class="category-header">
                    <i class="fas fa-home"></i>
                    <h3>Home Services</h3>
                </div>
                <ul class="service-list">
                    <li><i class="fas fa-paint-roller"></i> Painting</li>
                    <li><i class="fas fa-hammer"></i> Carpentry</li>
                    <li><i class="fas fa-bolt"></i> Electrical</li>
                    <li><i class="fas fa-toolbox"></i> Handyman</li>
                </ul>
            </div>

            <!-- Cleaning & Maintenance -->
            <div class="service-category">
                <div class="category-header">
                    <i class="fas fa-broom"></i>
                    <h3>Cleaning & Maintenance</h3>
                </div>
                <ul class="service-list">
                    <li><i class="fas fa-soap"></i> House Cleaning</li>
                    <li><i class="fas fa-leaf"></i> Gardening</li>
                    <li><i class="fas fa-snowplow"></i> Snow Removal</li>
                    <li><i class="fas fa-recycle"></i> Junk Removal</li>
                </ul>
            </div>
            <div class="service-category">
                <div class="category-header">
                    <i class="fas fa-hands-helping"></i>
                    <h3>Personal Services</h3>
                </div>
                <ul class="service-list">
                    <li><i class="fas fa-car"></i> Moving Help</li>
                    <li><i class="fas fa-dog"></i> Pet Care</li>
                    <li><i class="fas fa-baby-carriage"></i> Child Care</li>
                    <li><i class="fas fa-user-graduate"></i> Tutoring</li>
                </ul>
            </div>
        </div>

        <div class="service-cta">
            <a href="Top-Talent.php" class="cta-find-help">I Need Help With a Task</a>
            <a href="Login.php" class="cta-find-work">I Want to Offer My Services</a>
        </div>
    </section>


    <!-- Job Categories Section -->
    <div class="job-category-container">
        <div class="job-category-frame">
            <div class="job-category-card-wrapper">
                <div class="job-category-card">
                    <div class="job-category-title">Jobs by Category</div>
                    <p class="job-category-description">
                        Here, you can find the latest jobs related to your field and specialization.
                    </p>
                </div>
            </div>
            <?php
                require_once '../config/connection.php';
                $all_categories = [];
                try {
                    $sql = "SELECT * FROM job_categories ORDER BY category_name ASC";
                    $result = mysqli_query($con, $sql);
                    
                    if ($result) {
                        $all_categories = $result->fetch_all(MYSQLI_ASSOC);
                    }
                } catch (Exception $e) {
                    echo "<!--  Error: " . $e->getMessage() . " -->";
                }
                ?>
            <div class="categories-pagination-container">
                <div class="categories-grid-pages">
                    <?php if(!empty($all_categories)): ?>
                    <?php
                        $itemsPerPage = 6;
                        $totalPages = ceil(count($all_categories) / $itemsPerPage);
                        
                        for ($page = 0; $page < $totalPages; $page++):
                            $start = $page * $itemsPerPage;
                            $pageCategories = array_slice($all_categories, $start, $itemsPerPage);
                        ?>
                    <div class="categories-page" data-page="<?= $page ?>">
                        <div class="categories-grid">
                            <?php foreach($pageCategories as $category): ?>
                            <div class="job-category-icon-card">
                                <i class="<?= htmlspecialchars($category['icon_class']) ?>"></i>
                                <span><?= htmlspecialchars($category['category_name']) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endfor; ?>
                    <?php else: ?>
                    <div class="empty-state-message">
                        <p>No job categories available at the moment.</p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php if(!empty($all_categories) && count($all_categories) > $itemsPerPage): ?>
                <div class="pagination-controls">
                    <button class="pagination-btn prev-btn" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="page-indicator">
                        <span class="current-page">1</span> / <span class="total-pages"><?= $totalPages ?></span>
                    </div>
                    <button class="pagination-btn next-btn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <?php endif; ?>
                <div id="pagination-debug" style="display:none;"></div>
            </div>
        </div>
    </div>




    <!-- Section cards ( About us but in home page ) -->
    <div class="main">
        <div class="card-section">
            <!-- Left Content Section -->
            <div class="content">
                <h2>Why You Should Use ForWork and What It Can Help</h2>
                <div class="features">
                    <div class="feature">
                        <i class="fa-solid fa-check-circle"></i>
                        <div>
                            <h3>Quality</h3>
                            <p>Only legit jobs. No ads, scams, or junk to sift through. Our team spends 200+ hours/day
                                verifying every job.</p>
                        </div>
                    </div>
                    <div class="feature">
                        <i class="fa-solid fa-layer-group"></i>
                        <div>
                            <h3>Unlimited Projects</h3>
                            <p>Full access to all features including unlimited jobs, articles, and webinars to help you
                                with your remote job search.</p>
                        </div>
                    </div>
                    <div class="feature">
                        <i class="fa-solid fa-clock"></i>
                        <div>
                            <h3>Save Time</h3>
                            <p>Go straight from job listings to applications. No more hopping from one job board to the
                                next.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right Image Section -->
            <div class="img-sec">
                <img src="../components/image/card-why-forwork.png" alt="ForWork Illustration">
            </div>
        </div>
    </div>




    <!-- Trusted People Section -->
    <div class="trusted-people">
        <h2>Verified & Trusted Professionals</h2>
        <p>These individuals have proven their expertise through exceptional service and customer satisfaction.</p>

        <?php
            require_once '../config/connection.php';
            try {
                $query = "SELECT * FROM trusted_people ORDER BY Trusted_rating DESC, Trusted_id DESC LIMIT 6";
                $result = $con->query($query);
            } catch (Exception $e) {
                echo "<!-- Database Error: " . $e->getMessage() . " -->";
            }
            ?>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php if(isset($result) && $result->num_rows > 0): ?>
                <?php while($professional = $result->fetch_assoc()): ?>
                <div class="swiper-slide">
                    <img src="<?= htmlspecialchars($professional['Trusted_image_url']) ?>"
                        alt="<?= htmlspecialchars($professional['Trusted_name']) ?>" class="professional-img">
                    <div class="professional-info">
                        <h3 class="name"><?= htmlspecialchars($professional['Trusted_name']) ?></h3>
                        <p class="category"><?= htmlspecialchars($professional['Trusted_category']) ?></p>
                        <div class="rating"><?= str_repeat('★', $professional['Trusted_rating']) ?></div>
                        <?php if(!empty($professional['Trusted_Description'])): ?>
                        <p class="description">"<?= htmlspecialchars($professional['Trusted_Description']) ?>"</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <div class="swiper-slide no-data">
                    <p>No trusted professionals found</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>



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
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- This for JS Home -->
    <script src="../js/Home.js"></script>

    </style>
</body>

</html>