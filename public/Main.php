<?php
session_start();
require_once __DIR__ . '/../config/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login.php");
    exit();
}

$current_view = $_GET['view'] ?? 'find-tasks';
$task_detail_view = isset($_GET['task_id']);
$status_filter = $_GET['status'] ?? 'all';
$search_query = $_GET['search'] ?? '';
$user_id = $_SESSION['user_id'];
$user_stmt = $con->prepare("SELECT id, user_type, first_name, last_name FROM users WHERE id = ?");
$user_stmt->bind_param('i', $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
$user_stmt->close();
$user_type = $user['user_type'] ?? 'client';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_task'])) {
        $task_id = (int)$_POST['task_id'];
        $title = $_POST['task-title'];
        $description = $_POST['task-description'];
        $category = $_POST['task-category'];
        $budget = !empty($_POST['task-budget']) ? (float)$_POST['task-budget'] : null;
        $city = $_POST['task-city'];
        $location = $_POST['task-location'];
        $phone = $_POST['task-phone'];
        $start_date = $_POST['task-start-date'];
        
        $image_path = null;
        if (isset($_FILES['task-image']) && $_FILES['task-image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            $file_name = uniqid() . '_' . basename($_FILES['task-image']['name']);
            $target_file = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['task-image']['tmp_name'], $target_file)) {
                $image_path = $file_name;
            }
        }
        
        $sql = "UPDATE tasks SET 
                title = ?, 
                description = ?,
                category = ?,
                budget = ?,
                city = ?,
                location = ?,
                phone = ?,
                start_date = ?";
        
        if ($image_path) {
            $sql .= ", image_path = ?";
        }
        
        $sql .= " WHERE id = ? AND user_id = ?";
        
        $stmt = $con->prepare($sql);
        
        if ($image_path) {
            $stmt->bind_param('sssdsssssii', 
                $title, $description, $category, $budget,
                $city, $location, $phone, $start_date,
                $image_path, $task_id, $user_id
            );
        } else {
            $stmt->bind_param('sssdssssii', 
                $title, $description, $category, $budget,
                $city, $location, $phone, $start_date,
                $task_id, $user_id
            );
        }
        
        $stmt->execute();
        $stmt->close();
        
        header("Location: Main.php?task_id=$task_id");
        exit();
    }

    if (isset($_POST['accept_provider'])) {
        $task_id = (int)$_POST['task_id'];
        $provider_id = (int)$_POST['provider_id'];
        $stmt = $con->prepare("UPDATE tasks SET status = 'completed', assigned_to = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param('iii', $provider_id, $task_id, $user_id);
        $stmt->execute();
        $stmt->close();
        header("Location: Main.php?task_id=$task_id");
        exit();
    }
    
    if (isset($_POST['delete_task'])) {
        $task_id = (int)$_POST['task_id'];
        $stmt = $con->prepare("DELETE FROM task_applications WHERE task_id = ?");
        $stmt->bind_param('i', $task_id);
        $stmt->execute();
        $stmt->close();
        $stmt = $con->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->bind_param('ii', $task_id, $user_id);
        $stmt->execute();
        $stmt->close();
        header("Location: Main.php?view=my-tasks&status=" . urlencode($status_filter));
        exit();
    }
    
    if (isset($_POST['submit_task'])) {
        $title = trim($_POST['task-title'] ?? '');
        $description = trim($_POST['task-description'] ?? '');
        $category = trim($_POST['task-category'] ?? '');
        $budget = !empty($_POST['task-budget']) ? (float)$_POST['task-budget'] : null;
        $city = trim($_POST['task-city'] ?? '');
        $location = trim($_POST['task-location'] ?? '');
        $phone = trim($_POST['task-phone'] ?? '');
        $start_date = trim($_POST['task-start-date'] ?? '');
        if (empty($title) || empty($description) || empty($category) || empty($city) || 
            empty($location) || empty($phone) || empty($start_date)) {
            $_SESSION['error'] = "Please fill all required fields";
            header("Location: Main.php?view=post-task");
            exit();
        }

        $image_path = null;
        if (isset($_FILES['task-image']) && $_FILES['task-image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $file_ext = pathinfo($_FILES['task-image']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '.' . $file_ext;
            $target_file = $upload_dir . $file_name;
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array(strtolower($file_ext), $allowed_types)) {
                $_SESSION['error'] = "Only JPG, PNG, and GIF images are allowed";
                header("Location: Main.php?view=post-task");
                exit();
            }
            if (move_uploaded_file($_FILES['task-image']['tmp_name'], $target_file)) {
                $image_path = $file_name;
            }
        }
        try {
            $stmt = $con->prepare("INSERT INTO tasks (
                user_id, title, description, category, budget,
                city, location, phone, start_date, status, image_path, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'open', ?, NOW())");
            
            $stmt->bind_param('isssdsssss', 
                $user_id, $title, $description, $category, $budget,
                $city, $location, $phone, $start_date, $image_path
            );
            if ($stmt->execute()) {
                $_SESSION['success'] = "Task posted successfully!";
                header("Location: Main.php?view=my-tasks");
                exit();
            } else {
                throw new Exception("Database error: " . $stmt->error);
            }
        } catch (Exception $e) {
            if ($image_path && file_exists($upload_dir . $image_path)) {
                unlink($upload_dir . $image_path);
            }
            $_SESSION['error'] = "Failed to post task: " . $e->getMessage();
            header("Location: Main.php?view=post-task");
            exit();
        }
    }
    
    if (isset($_POST['apply_task'])) {
        $task_id = (int)$_POST['task_id'];
        
        $stmt = $con->prepare("SELECT id FROM task_applications WHERE task_id = ? AND user_id = ?");
        $stmt->bind_param('ii', $task_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $stmt = $con->prepare("INSERT INTO task_applications (task_id, user_id, applied_at) VALUES (?, ?, NOW())");
            $stmt->bind_param('ii', $task_id, $user_id);
            $stmt->execute();
            $stmt->close();
        }
        
        header("Location: Main.php?task_id=$task_id&applied=1");
        exit();
    }
}

$tasks = [];
if ($current_view === 'my-tasks') {
    $query = "SELECT * FROM tasks WHERE user_id = ?";
    
    if ($status_filter !== 'all') {
        $query .= " AND status = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('is', $user_id, $status_filter);
    } else {
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $user_id);
    }
    
    $stmt->execute();
    $tasks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} elseif ($current_view === 'find-tasks') {
    $query = "SELECT t.*, u.first_name, u.last_name
             FROM tasks t 
             JOIN users u ON t.user_id = u.id
             WHERE t.status = 'open'";
    
    if (!empty($search_query)) {
        $query .= " AND (t.title LIKE ? OR t.description LIKE ?)";
        $search_param = "%$search_query%";
        $stmt = $con->prepare($query);
        $stmt->bind_param('ss', $search_param, $search_param);
    } else {
        $stmt = $con->prepare($query);
    }
    
    $stmt->execute();
    $tasks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$viewing_task = null;
$applicants = [];
if ($task_detail_view) {
    $task_id = (int)$_GET['task_id'];
    $stmt = $con->prepare("SELECT t.*, u.first_name, u.last_name FROM tasks t JOIN users u ON t.user_id = u.id WHERE t.id = ?");
    $stmt->bind_param('i', $task_id);
    $stmt->execute();
    $viewing_task = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $stmt = $con->prepare("SELECT u.* FROM task_applications ta 
                          JOIN users u ON ta.user_id = u.id 
                          WHERE ta.task_id = ?");
    $stmt->bind_param('i', $task_id);
    $stmt->execute();
    $applicants = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    $has_applied = false;
    if ($user_type === 'provider') {
        $stmt = $con->prepare("SELECT id FROM task_applications WHERE task_id = ? AND user_id = ?");
        $stmt->bind_param('ii', $task_id, $user_id);
        $stmt->execute();
        $has_applied = $stmt->get_result()->num_rows > 0;
        $stmt->close();
    }
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
    <link rel="stylesheet" href="../css/Main.css">

    <!-- Link to fontawesome  -->
    <link rel="stylesheet" href="../assets/fontawesome-free-6.7.2-web/css/all.min.css">

    <!-- This is for swiper mode -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Icon of the top -->
    <link rel="icon" href="../components/image/Logo.png" type="image/png" sizes="32x32" />
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="Main.php" class="logo">Forwork</a>
            <div class="search-container">
                <input type="text" id="global-search" placeholder="Search for tasks...">
                <i class="fas fa-search"></i>
            </div>
            <div class="profile">
                <a href="Profile.php"><i class="fas fa-user"></i></a>
            </div>
            <div class="profile">
                <a href="HomePage.php"><i class="fas fa-sign-out"></i></a>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <!-- Left Action Panel -->
        <div class="action-panel">
            <a href="Main.php" class="action-item <?= $current_view === 'find-tasks' ? 'active' : '' ?>">
                <i class="fas fa-search"></i>
                <span>Find Tasks</span>
            </a>
            <?php if ($user_type === 'client' || $user_type === 'provider'): ?>
            <a href="?view=post-task" class="action-item <?= $current_view === 'post-task' ? 'active' : '' ?>">
                <i class="fas fa-plus-circle"></i>
                <span>Post a Task</span>
            </a>
            <?php endif; ?>
            <a href="?view=my-tasks" class="action-item <?= $current_view === 'my-tasks' ? 'active' : '' ?>">
                <i class="fas fa-clipboard-list"></i>
                <span>My Tasks</span>
            </a>
        </div>

        <!-- Dynamic Content Area -->
        <div id="dynamic-content">
            <?php if ($task_detail_view && $viewing_task): ?>
            <!-- Task Detail View -->
            <div class="task-detail-view">
                <button class="back-btn" onclick="window.location.href='Main.php'">
                    <i class="fas fa-arrow-left"></i> Back to Tasks
                </button>

                <div class="task-detail-header">
                    <h2><?= htmlspecialchars($viewing_task['title']) ?></h2>
                    <span class="price">$<?= htmlspecialchars($viewing_task['budget'] ?? 'Negotiable') ?></span>
                    <span class="task-status <?= $viewing_task['status'] ?>">
                        <?= ucfirst($viewing_task['status']) ?>
                    </span>
                </div>

                <?php if (!empty($viewing_task['image_path'])): ?>
                <div class="task-image">
                    <img src="../uploads/<?= htmlspecialchars($viewing_task['image_path']) ?>" alt="Task Image">
                </div>
                <?php endif; ?>

                <div class="task-detail-meta">
                    <span><i class="fas fa-user"></i> Posted by:
                        <?= htmlspecialchars($viewing_task['first_name'].' '.$viewing_task['last_name']) ?></span>
                    <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($viewing_task['city']) ?></span>
                    <span><i class="fas fa-calendar-alt"></i> Posted:
                        <?= date('M j, Y', strtotime($viewing_task['created_at'])) ?></span>
                </div>

                <div class="task-detail-description">
                    <h3>Description</h3>
                    <p><?= htmlspecialchars($viewing_task['description']) ?></p>
                </div>

                <div class="task-detail-location">
                    <h3>Location</h3>
                    <p><?= !empty($viewing_task['location']) ? htmlspecialchars($viewing_task['location']) : 'Location not specified' ?>
                    </p>
                </div>

                <div class="task-detail-actions">
                    <?php if ($user_type === 'provider' && $viewing_task['status'] === 'open' && $viewing_task['user_id'] != $user_id): ?>
                    <?php if ($has_applied): ?>
                    <div class="applied-alert">You've applied to this task</div>
                    <?php else: ?>
                    <form method="POST">
                        <input type="hidden" name="apply_task" value="1">
                        <input type="hidden" name="task_id" value="<?= $viewing_task['id'] ?>">
                        <button type="submit" class="apply-btn"
                            onclick="return confirm('Are you sure you want to apply to this task?')">
                            <i class="fas fa-paper-plane"></i> Apply for this Task
                        </button>
                    </form>
                    <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($viewing_task['user_id'] == $user_id): ?>
                    <a href="?view=post-task&edit=<?= $viewing_task['id'] ?>" class="edit-btn">
                        <i class="fas fa-edit"></i> Edit Task
                    </a>

                    <!-- Complete Task Button -->
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="complete_task" value="1">
                        <input type="hidden" name="task_id" value="<?= $viewing_task['id'] ?>">
                        <button type="submit" class="complete-btn"
                            onclick="return confirm('Mark this task as completed?')">
                            <i class="fas fa-check-circle"></i> Mark Completed
                        </button>
                    </form>
                    <?php endif; ?>
                </div>

                <?php if ($viewing_task['user_id'] == $user_id && !empty($applicants)): ?>
                <div class="applicants-section">
                    <h3>Applicants (<?= count($applicants) ?>)</h3>
                    <div class="applicants-list">
                        <?php foreach ($applicants as $applicant): ?>
                        <div class="applicant-card">
                            <div class="applicant-info">
                                <?php if (!empty($applicant['profile_photo_path'])): ?>
                                <img src="<?= htmlspecialchars($applicant['profile_photo_path']) ?>"
                                    class="applicant-avatar">
                                <?php else: ?>
                                <div class="applicant-avatar default">
                                    <i class="fas fa-user"></i>
                                </div>
                                <?php endif; ?>

                                <div class="applicant-details">
                                    <a href="Profile.php?id=<?= $applicant['id'] ?>&task_id=<?= $viewing_task['id'] ?>"
                                        class="applicant-name">
                                        <?= htmlspecialchars($applicant['first_name'] . ' ' . $applicant['last_name']) ?>
                                    </a>
                                    <div class="applicant-meta">
                                        <span><?= htmlspecialchars($applicant['service_category']) ?></span>
                                        <span><?= htmlspecialchars($applicant['years_experience']) ?> years
                                            experience</span>
                                    </div>
                                </div>
                            </div>

                            <div class="applicant-actions">
                                <form method="POST">
                                    <input type="hidden" name="task_id" value="<?= $viewing_task['id'] ?>">
                                    <input type="hidden" name="provider_id" value="<?= $applicant['id'] ?>">
                                    <button type="submit" name="accept_provider" class="accept-btn"
                                        onclick="return confirm('Accept <?= htmlspecialchars($applicant['first_name']) ?> for this task?')">
                                        <i class="fas fa-check"></i> Accept
                                    </button>
                                </form>

                                <a href="Profile.php?id=<?= $applicant['id'] ?>&task_id=<?= $viewing_task['id'] ?>"
                                    class="view-profile-btn">
                                    <i class="fas fa-eye"></i> View Profile
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <?php elseif ($current_view === 'find-tasks'): ?>
            <div class="task-list">
                <h2>Available Tasks</h2>
                <div class="task-list-container" id="tasks-container">
                    <?php if (empty($tasks)): ?>
                    <p class="no-tasks">No tasks available at the moment.</p>
                    <?php else: ?>
                    <?php foreach ($tasks as $task): ?>
                    <div class="task-card">
                        <?php if (!empty($task['image_path'])): ?>
                        <div class="task-image">
                            <img src="../uploads/<?= htmlspecialchars($task['image_path']) ?>" alt="Task Image">
                        </div>
                        <?php endif; ?>
                        <div class="task-header">
                            <h3><?= htmlspecialchars($task['title']) ?></h3>
                            <span class="price">$<?= htmlspecialchars($task['budget'] ?? 'Negotiable') ?></span>
                        </div>
                        <p><?= htmlspecialchars(substr($task['description'], 0, 100)) ?><?= strlen($task['description']) > 100 ? '...' : '' ?>
                        </p>
                        <div class="task-meta">
                            <span class="task-meta-item"><i class="fas fa-user"></i>
                                <?= htmlspecialchars($task['first_name'].' '.$task['last_name']) ?></span>
                            <span class="task-meta-item"><i class="fas fa-map-marker-alt"></i>
                                <?= htmlspecialchars($task['city']) ?></span>
                        </div>
                        <div class="task-footer">
                            <button class="view-task-btn" onclick="window.location.href='?task_id=<?= $task['id'] ?>'">
                                View Task
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <?php elseif ($current_view === 'post-task'): ?>
            <!-- Post Task View -->
            <div class="task-form">
                <?php
    $editing = isset($_GET['edit']);
    $task_data = [
        'title' => '',
        'description' => '',
        'category' => '',
        'budget' => '',
        'city' => '',
        'location' => '',
        'phone' => '',
        'start_date' => '',
        'image_path' => ''
    ];
    
    if ($editing) {
        $edit_id = (int)$_GET['edit'];
        $stmt = $con->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->bind_param('ii', $edit_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $task_data = $result->fetch_assoc();
        $stmt->close();
    }
    ?>

                <h2><?= $editing ? 'Edit Task' : 'Post a New Task' ?></h2>

                <form method="POST" id="task-creation-form" enctype="multipart/form-data">
                    <?php if ($editing): ?>
                    <input type="hidden" name="task_id" value="<?= $edit_id ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="task-title">Task Title*</label>
                        <input type="text" id="task-title" name="task-title" required
                            value="<?= htmlspecialchars($task_data['title']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="task-description">Description*</label>
                        <textarea id="task-description" name="task-description" required><?= 
                htmlspecialchars($task_data['description']) 
            ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="task-category">Task Type*</label>
                        <input type="text" id="task-category" name="task-category" required
                            value="<?= htmlspecialchars($task_data['category']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="task-budget">Budget ($)</label>
                        <input type="number" id="task-budget" name="task-budget" min="1"
                            value="<?= htmlspecialchars($task_data['budget']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="task-city">City*</label>
                        <input type="text" id="task-city" name="task-city" required
                            value="<?= htmlspecialchars($task_data['city']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="task-location">Exact Location*</label>
                        <input type="text" id="task-location" name="task-location" required
                            value="<?= htmlspecialchars($task_data['location']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="task-phone">Phone Number*</label>
                        <input type="tel" id="task-phone" name="task-phone" required
                            value="<?= htmlspecialchars($task_data['phone']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="task-start-date">Start Date*</label>
                        <input type="date" id="task-start-date" name="task-start-date" required
                            value="<?= htmlspecialchars($task_data['start_date']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="task-image">Task Image (Optional)</label>
                        <input type="file" id="task-image" name="task-image" accept="image/*">
                        <?php if ($editing && !empty($task_data['image_path'])): ?>
                        <div class="current-image">
                            <p>Current Image:</p>
                            <img src="../uploads/<?= htmlspecialchars($task_data['image_path']) ?>"
                                style="max-width: 200px; margin-top: 10px;">
                        </div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="submit-btn" name="<?= $editing ? 'update_task' : 'submit_task' ?>">
                        <?= $editing ? 'Update Task' : 'Post Task' ?>
                    </button>
                </form>
            </div>
            <?php elseif ($current_view === 'my-tasks'): ?>
            <!-- My Tasks View -->
            <div class="my-tasks-view">
                <h2>My Tasks</h2>
                <div class="status-filter">
                    <a href="?view=my-tasks&status=all" class="<?= $status_filter === 'all' ? 'active' : '' ?>">All</a>
                    <a href="?view=my-tasks&status=open"
                        class="<?= $status_filter === 'open' ? 'active' : '' ?>">Open</a>
                    <a href="?view=my-tasks&status=completed"
                        class="<?= $status_filter === 'completed' ? 'active' : '' ?>">Completed</a>
                </div>

                <div class="my-tasks-container">
                    <?php if (empty($tasks)): ?>
                    <p class="no-tasks">No tasks found</p>
                    <?php else: ?>
                    <?php foreach ($tasks as $task): 
                        // Get applicant count for each task
                        $stmt = $con->prepare("SELECT COUNT(*) as count FROM task_applications WHERE task_id = ?");
                        $stmt->bind_param('i', $task['id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $applicant_count = $result->fetch_assoc()['count'];
                        $stmt->close();
                    ?>
                    <div class="task-card">
                        <?php if (!empty($task['image_path'])): ?>
                        <div class="task-image">
                            <img src="../uploads/<?= htmlspecialchars($task['image_path']) ?>" alt="Task Image">
                        </div>
                        <?php endif; ?>
                        <div class="task-header">
                            <h3><?= htmlspecialchars($task['title']) ?></h3>
                            <span class="price">$<?= htmlspecialchars($task['budget'] ?? 'Negotiable') ?></span>
                        </div>
                        <div class="task-status <?= $task['status'] ?>">
                            <span><?= ucfirst($task['status']) ?></span>
                        </div>
                        <div class="task-meta">
                            <span><i class="fas fa-users"></i> <?= $applicant_count ?> applicant(s)</span>
                        </div>
                        <div class="task-actions">
                            <a href="?task_id=<?= $task['id'] ?>" class="view-btn">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="?view=post-task&edit_task=<?= $task['id'] ?>" class="edit-btn">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <?php if ($task['status'] === 'open'): ?>
                            <form method="POST">
                                <input type="hidden" name="delete_task" value="1">
                                <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                <button type="submit" class="delete-btn" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
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

    <script src="../js/Main.js"></script>
</body>

</html>