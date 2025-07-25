<?php
require_once __DIR__ . '/../../config/connection.php';
$contactQuery = "SELECT * FROM contact_submissions ORDER BY created_at DESC";
$contactResult = $con->query($contactQuery);
$contactSubmissions = $contactResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forwork - Admin Reports</title>
    <link rel="stylesheet" href="../../src/output.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/Repport.css">
    <link rel="icon" href="../../components/image/Logo.png" type="image/png" sizes="32x32" />
    <link rel="stylesheet" href="../../assets/fontawesome-free-6.7.2-web/css/all.min.css">
</head>

<body>
    <nav class="creative-sidebar">
        <header class="sidebar-logo">Forwork</header>
        <div class="sidebar-scroll">
            <ul class="sidebar-menu">
                <li class="sidebar-item"><a href="Users.php"><i class="fas fa-users"></i>Users</a></li>
                <li class="sidebar-item active"><a href="Repport.php"><i class="fas fa-file-alt"></i>Reports</a></li>
                <li class="sidebar-item"><a href="task-admin.php"><i class="fas fa-key"></i>Task</a></li>
            </ul>
        </div>
        <div class="sidebar-logout">
            <a href="../../public/HomePage.php" class="sidebar-item logout-link">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>

    <main class="admin-reports-panel">
        <div class="reports-header">
            <h1>Contact Submissions</h1>
        </div>
        <div class="table-card">
            <div class="table-header">
                <h3>Contact Messages</h3>
                <div class="table-actions">
                    <span>Total: <?= count($contactSubmissions) ?></span>
                    <button class="refresh-btn" onclick="location.reload()"><i class="fas fa-sync-alt"></i>
                        Refresh</button>
                </div>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contactSubmissions as $submission): ?>
                        <tr>
                            <td><?= $submission['id'] ?></td>
                            <td><?= htmlspecialchars($submission['name']) ?></td>
                            <td><?= htmlspecialchars($submission['email']) ?></td>
                            <td><?= htmlspecialchars($submission['subject']) ?></td>
                            <td><?= htmlspecialchars($submission['message']) ?></td>
                            <td><?= date('M d, Y', strtotime($submission['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="../js/Repport.js"></script>
</body>

</html>