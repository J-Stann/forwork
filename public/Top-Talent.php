<?php
require_once '../config/connection.php';
function getFilter($name, $default = '') {
    return isset($_GET[$name]) ? htmlspecialchars($_GET[$name]) : $default;
}
$filters = [
    'category' => getFilter('category'),
    'location' => getFilter('location'),
    'experience' => (int)getFilter('experience', 0),
    'keyword' => getFilter('keyword'),
    'page' => max(1, (int)getFilter('page', 1))
];
define('TALENTS_PER_PAGE', 6);
if (!empty($_GET['suggest_query'])) {
    $searchTerm = '%' . $_GET['suggest_query'] . '%';
    $query = "SELECT DISTINCT `Top-Talent-category` FROM `Top-Talent` WHERE `Top-Talent-category` LIKE ? LIMIT 5";
    $stmt = $con->prepare($query);
    $stmt->bind_param('s', $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $suggestions = array_column($result->fetch_all(MYSQLI_ASSOC), 'Top-Talent-category');
    echo json_encode($suggestions);
    exit;
}

$where = [];
$params = [];
$types = '';
function addFilter(&$where, &$params, &$types, $field, $value, $operator = 'LIKE', $paramType = 's') {
    if (!empty($value)) {
        $where[] = "`$field` $operator ?";
        $params[] = ($operator === 'LIKE') ? "%$value%" : $value;
        $types .= $paramType;
    }
}
addFilter($where, $params, $types, 'Top-Talent-category', $filters['category']);
addFilter($where, $params, $types, 'Top-Talent-location', $filters['location']);
addFilter($where, $params, $types, 'Top-Talent-experience', $filters['experience'], '>=', 'i');

if (!empty($filters['keyword'])) {
    $where[] = "(`Top-Talent-skills` LIKE ? OR `Top-Talent-title` LIKE ? OR `Top-Talent-name` LIKE ?)";
    $keywordParam = "%{$filters['keyword']}%";
    array_push($params, $keywordParam, $keywordParam, $keywordParam);
    $types .= 'sss';
}
$countQuery = "SELECT COUNT(*) FROM `Top-Talent`" . 
    (!empty($where) ? " WHERE " . implode(" AND ", $where) : "");
$stmt = $con->prepare($countQuery);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$totalTalents = $stmt->get_result()->fetch_row()[0];
$totalPages = ceil($totalTalents / TALENTS_PER_PAGE);

$query = "SELECT * FROM `Top-Talent`" . 
    (!empty($where) ? " WHERE " . implode(" AND ", $where) : "") . 
    " LIMIT ? OFFSET ?";

array_push($params, TALENTS_PER_PAGE, ($filters['page'] - 1) * TALENTS_PER_PAGE);
$types .= 'ii';

$stmt = $con->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$talents = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Talent forwork</title>

    <!-- Link to Tailwind CSS -->
    <link rel="stylesheet" href="../src/output.css">

    <!-- Link to External CSS -->
    <link rel="stylesheet" href="../css/Top-Talent.css">
    <link rel="stylesheet" href="../css/Home.css">

    <!-- Link to fontawesome  -->
    <link rel="stylesheet" href="../assets/fontawesome-free-6.7.2-web/css/all.min.css">

    <!-- This is for swiper mode -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Icon of the top -->
    <link rel="icon" href="../components/image/Logo.png" type="image/png" sizes="32x32" />

<body>

    <!-- NavBar -->
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

    <!-- Benefits Section -->
    <section class="benefits-section bg-white">
        <div class="strict-container">
            <!-- Breadcrumb -->
            <div class="breadcrumb bg-gray-100 py-3 px-4 rounded-lg max-w-screen-xl mx-auto mb-8">
                <a href="HomePage.php" class="text-blue-600 hover:text-blue-800">Home</a>
                <span class="text-gray-500 mx-2">/</span>
                <span class="text-gray-700 font-medium">Top Talent</span>
            </div>
            <div class="section-intro">

                <h2>Why Choose Our Professionals</h2>
                <p>We connect you with trusted, skilled workers for all your needs</p>
            </div>


            <div class="benefit-item">
                <div class="benefit-image">
                    <img src="../components/image/verified.png" alt="Quality professionals">
                </div>
                <div class="benefit-content">
                    <h3>Verified & Reliable</h3>
                    <p>Every professional on our platform undergoes strict verification to ensure quality and
                        reliability for your projects.</p>
                    <ul class="benefits-list">
                        <li><i class="fas fa-check-circle"></i> Background checked professionals</li>
                        <li><i class="fas fa-check-circle"></i> Customer rating system</li>
                        <li><i class="fas fa-check-circle"></i> Work guarantee options</li>
                    </ul>
                </div>
            </div>

            <div class="benefit-item reversed">
                <div class="benefit-image">
                    <img src="../components/image/find.png" alt=" Easy hiring process">
                </div>
                <div class="benefit-content">
                    <h3>Hassle-Free Hiring</h3>
                    <p>Our platform makes finding and hiring the right professional simple and transparent.</p>
                    <ul class="benefits-list">
                        <li><i class="fas fa-check-circle"></i> Easy to find</li>
                        <li><i class="fas fa-check-circle"></i> Real-time </li>
                        <li><i class="fas fa-check-circle"></i> Secure </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <div class="strict-container">
        <form id="talentFilters" method="GET" action="" class="bg-white p-6 rounded-lg shadow-md">
            <div class="talent-filters">
                <div class="filter-group relative">
                    <input type="text" name="category" id="category-filter" placeholder="Search categories"
                        value="<?= htmlspecialchars($filters['category']) ?>" autocomplete="on">
                    <div id="category-suggestions"
                        class="suggestions-dropdown hidden absolute z-10 w-full bg-white border border-gray-300 rounded-b-lg shadow-lg">
                    </div>
                </div>
                <div class="filter-group">
                    <input type="text" name="location" id="location-filter" placeholder="Enter location (city or area)"
                        value="<?= htmlspecialchars($filters['location']) ?>">
                </div>
                <div class="filter-group">
                    <select name="experience" id="experience-filter">
                        <option value="">Any Experience Level</option>
                        <option value="1" <?= $filters['experience'] == '1' ? 'selected' : '' ?>>1+ years</option>
                        <option value="3" <?= $filters['experience'] == '3' ? 'selected' : '' ?>>3+ years</option>
                        <option value="5" <?= $filters['experience'] == '5' ? 'selected' : '' ?>>5+ years</option>
                    </select>
                </div>
                <div class="filter-group">
                    <input type="text" name="keyword" id="keyword-search" placeholder="Search skills or keywords"
                        value="<?= htmlspecialchars($filters['keyword']) ?>">
                </div>
                <div class="filter-group">
                    <button type="submit"
                        class="search-btn bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Search
                    </button>
                </div>
                <input type="hidden" name="page" id="page-input" value="<?= $filters['page'] ?>">
            </div>
        </form>
    </div>

    <!-- Talent Grid -->
    <div class="strict-container">
        <div id="talent-grid" class="talent-grid">
            <?php if (empty($talents)): ?>
            <div class="no-results strict-container text-center py-12 bg-white rounded-lg shadow">
                <i class="fas fa-search fa-3x text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold">No talents found</h3>
                <p class="text-gray-600">Try adjusting your search filters</p>
            </div>
            <?php else: ?>
            <?php foreach ($talents as $talent): ?>
            <div class="talent-card bg-white">
                <div class="talent-img">
                    <img alt="" src="<?= htmlspecialchars($talent['Top-Talent-image']) ?>" />
                </div>
                <div class="talent-info">
                    <h3 class="talent-name"><?= htmlspecialchars($talent['Top-Talent-name']) ?></h3>
                    <p class="talent-title"><?= htmlspecialchars($talent['Top-Talent-title']) ?></p>
                    <div class="talent-meta">
                        <span><i class="fas fa-briefcase"></i>
                            <?= htmlspecialchars($talent['Top-Talent-category']) ?></span>
                        <span><i class="fas fa-map-marker-alt"></i>
                            <?= htmlspecialchars($talent['Top-Talent-location']) ?></span>
                    </div>
                    <div class="talent-meta">
                        <span><i class="fas fa-star"></i> <?= $talent['Top-Talent-experience'] ?>+ years
                            experience</span>
                    </div>
                    <div class="talent-skills">
                        <?php 
                        $skills = explode(',', $talent['Top-Talent-skills']);
                        foreach ($skills as $skill): 
                            if (trim($skill)): ?>
                        <span class="skill-tag"><?= htmlspecialchars(trim($skill)) ?></span>
                        <?php endif;
                        endforeach; ?>
                    </div>
                    <a href="Profile.php?talent_id=<?= $talent['Top-Talent-id'] ?>" class="view-profile">View
                        Profile</a>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($totalPages > $filters['page']): ?>
        <div class="text-center mt-8">
            <button id="view-more-btn"
                class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition-colors">
                View More
            </button>
        </div>
        <?php endif; ?>
    </div>

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
    <script src="../js/Top-Talent.js"></script>
</body>

</html>