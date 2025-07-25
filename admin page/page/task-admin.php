<?php
require_once __DIR__ . '/../../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_task'])) {
    $task_id = (int)$_POST['task_id'];
    $stmt = $con->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param('i', $task_id);
    $stmt->execute();
    
    header("Location: task-admin.php");
    exit;
}

$tasks = [];
try {
    $query = "SELECT t.*, u.first_name, u.last_name 
              FROM tasks t
              LEFT JOIN users u ON t.user_id = u.id
              ORDER BY t.created_at DESC";
    $result = $con->query($query);
    $tasks = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forwork - Admin Tasks</title>
    <link rel="stylesheet" href="../../src/output.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/Project.css">
    <link rel="icon" href="../../components/image/Logo.png" type="image/png" sizes="32x32" />
    <link rel="stylesheet" href="../../assets/fontawesome-free-6.7.2-web/css/all.min.css">
</head>

<body>
    <nav class="creative-sidebar">
        <header class="sidebar-logo">Forwork</header>
        <div class="sidebar-scroll">
            <ul class="sidebar-menu">
                <li class="sidebar-item"><a href="Users.php"><i class="fas fa-users"></i>Users</a></li>
                <li class="sidebar-item"><a href="Repport.php"><i class="fas fa-file-alt"></i>Reports</a></li>
                <li class="sidebar-item active"><a href="task-admin.php"><i class="fas fa-key"></i>Task</a></li>
            </ul>
        </div>
        <div class="sidebar-logout">
            <a href="../../public/HomePage.php" class="sidebar-item logout-link">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>

    <main class="admin-project-panel">
        <div class="project-header">
            <h1>Manage Tasks</h1>
            <div class="project-stats">
                <div class="stat-card">
                    <i class="fas fa-tasks"></i>
                    <div>
                        <span>Total Tasks</span>
                        <strong><?= count($tasks) ?></strong>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <span>Completed</span>
                        <strong><?= count(array_filter($tasks, fn($t) => $t['status'] === 'completed')) ?></strong>
                    </div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-clock"></i>
                    <div>
                        <span>Pending</span>
                        <strong><?= count(array_filter($tasks, fn($t) => $t['status'] === 'pending')) ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="project-controls">
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input type="text" id="taskSearch" placeholder="Search tasks..." class="search-bar">
            </div>
            <button class="refresh-btn" onclick="location.reload()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>

        <div class="projects-table-container">
            <table class="projects-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Client</th>
                        <th>Category</th>
                        <th>Budget</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                    <tr class="task-row" data-task-id="<?= $task['id'] ?>">
                        <td><?= $task['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($task['title']) ?></strong>
                            <small><?= htmlspecialchars(substr($task['description'], 0, 30)) ?>...</small>
                        </td>
                        <td>
                            <?php if ($task['first_name']): ?>
                            <div class="client-info">
                                <span><?= htmlspecialchars($task['first_name'] . ' ' . $task['last_name']) ?></span>
                            </div>
                            <?php else: ?>
                            <span>Unknown</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($task['category']) ?></td>
                        <td>$<?= number_format($task['budget'], 2) ?></td>
                        <td><?= htmlspecialchars($task['city']) ?></td>
                        <td>
                            <span class="status-badge <?= $task['status'] ?>">
                                <?= ucfirst($task['status']) ?>
                            </span>
                        </td>
                        <td><?= date('M d, Y', strtotime($task['created_at'])) ?></td>
                        <td>
                            <div class="action-btns">
                                <form method="POST" class="delete-form"
                                    onsubmit="return confirm('Are you sure you want to delete this task?')">
                                    <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                    <button type="submit" name="delete_task" class="btn-icon text-danger"
                                        title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div id="taskModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Task Details</h3>
                    <button class="close-modal">&times;</button>
                </div>
                <div class="modal-body" id="taskDetailsContent">
                </div>
            </div>
        </div>
    </main>

    <script src="../ js/task-admin"></script>
</body>

</html>