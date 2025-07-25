<?php
require_once __DIR__ . '/../../config/connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = (int)$_POST['user_id'];
    $action = $_POST['action'];
    
    if ($action === 'delete') {
        $stmt = $con->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
    } elseif ($action === 'promote_to_talent') {
    $stmt = $con->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    
    if ($user) {
        $insertStmt = $con->prepare("INSERT INTO `top-talent` (
            `Top-Talent-name`, 
            `Top-Talent-title`, 
            `Top-Talent-category`, 
            `Top-Talent-experience`, 
            `Top-Talent-location`, 
            `Top-Talent-skills`, 
            `Top-Talent-image`,
            `phone`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $name = $user['first_name'] . ' ' . $user['last_name'];
        $title = $user['service_category'] . ' Professional';
        $category = $user['service_category'];
        $experience = $user['years_experience'];
        $location = $user['city'] . ', ' . $user['country'];
        $skills = $user['services'];
        $image = $user['profile_photo_path'] ?? 'default-profile.jpg';
        $phone = $user['phone']; 
        
        $insertStmt->bind_param(
            'sssissss', 
            $name, 
            $title, 
            $category, 
            $experience, 
            $location, 
            $skills, 
            $image,
            $phone 
        );
        $insertStmt->execute();
    }
}elseif ($action === 'mark_trusted') {
        $stmt = $con->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if ($user) {
            $insertStmt = $con->prepare("INSERT INTO `trusted_people` (
                `Trusted_name`, 
                `Trusted_category`, 
                `Trusted_image_url`,
                `Trusted_Description`
            ) VALUES (?, ?, ?, ?)");
            
            $name = $user['first_name'] . ' ' . $user['last_name'];
            $category = $user['service_category'];
            $image = $user['profile_photo_path'] ?? 'default-profile.jpg';
            $description = "Verified professional with " . $user['years_experience'] . " years experience";
            
            $insertStmt->bind_param(
                'ssss', 
                $name, 
                $category, 
                $image,
                $description
            );
            $insertStmt->execute();
            $updateStmt = $con->prepare("UPDATE users SET is_verified = 1 WHERE id = ?");
            $updateStmt->bind_param('i', $userId);
            $updateStmt->execute();
        }
    }
    
    header("Location: Users.php");
    exit;
}
$query = "SELECT * FROM users ORDER BY created_at DESC";
$result = $con->query($query);
$users = $result->fetch_all(MYSQLI_ASSOC);
$providers = array_filter($users, function($user) {
    return $user['user_type'] === 'provider';
});
$clients = array_filter($users, function($user) {
    return $user['user_type'] === 'client';
});
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forwork - Admin Users</title>
    <link rel="stylesheet" href="../../src/output.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/Users.css">
    <link rel="stylesheet" href="../../assets/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="icon" href="../../components/image/Logo.png" type="image/png" sizes="32x32" />
</head>

<body>
    <nav class="creative-sidebar">
        <header class="sidebar-logo">Forwork</header>
        <div class="sidebar-scroll">
            <ul class="sidebar-menu">
                <li class="sidebar-item active"><a href="Users.php"><i class="fas fa-users"></i>Users</a></li>
                <li class="sidebar-item"><a href="Repport.php"><i class="fas fa-file-alt"></i>Reports</a></li>
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

    <main class="admin-user-panel">
        <div class="role-toggle">
            <button class="toggle-btn active" data-role="provider">👷 Providers</button>
            <button class="toggle-btn" data-role="client">📢 Clients</button>
        </div>

        <div class="panel-controls">
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input type="text" id="userSearch" placeholder="Search users..." class="search-bar">
            </div>
        </div>
        <section class="user-table active" id="provider-table">
            <div class="table-header">
                <h2>Service Providers</h2>
                <button class="refresh-btn" id="refreshProviders"><i class="fas fa-sync-alt"></i> Refresh</button>
            </div>
            <div class="overflow-auto">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($providers as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['city'] . ', ' . $user['country']) ?></td>
                            <td>
                                <?php if ($user['is_verified']): ?>
                                <span class="verified-badge"><i class="fas fa-check-circle"></i> Verified</span>
                                <?php else: ?>
                                <span class="unverified-badge">Unverified</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <input type="hidden" name="action" value="mark_trusted">
                                    <button type="submit" class="btn-icon text-primary" title="Mark as Trusted">
                                        <i class="fas fa-user-shield"></i>
                                    </button>
                                </form>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <input type="hidden" name="action" value="promote_to_talent">
                                    <button type="submit" class="btn-icon text-success" title="Promote to Top Talent">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </form>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="btn-icon text-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <section class="user-table" id="client-table">
            <div class="table-header">
                <h2>Clients</h2>
                <button class="refresh-btn" id="refreshClients"><i class="fas fa-sync-alt"></i> Refresh</button>
            </div>
            <div class="overflow-auto">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['city'] . ', ' . $user['country']) ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="btn-icon text-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <script src="../js/Users.js"></script>
</body>

</html>