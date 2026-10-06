<?php
/*
session_start();

require_once __DIR__ . '/../../middleware/auth.php';
requireAuth();
if ($_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Forbidden');
}



<?php

session_start();

require_once __DIR__ . '/../../middleware/auth.php';
requireAdmin();

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/user.model.php';
require_once __DIR__ . '/../../controllers/user.controller.php';

$userController = new UserController($pdo);

$users = $userController->index();

?>



*/
?>

<?php require __DIR__ . '/../../components/head.php'; ?>

<section class="section">

    <nav class="nav dashboard_nav">

        <p>Date Harvest Reports</p>

        <ul>
            <li>
                <?= htmlspecialchars($_SESSION['name']) ?>
            </li>

            <li>
                <a href="../dashboard.php">Dashboard</a>
            </li>
        </ul>

    </nav>

    <main class="dashboard_body">

        <h2>Users</h2>

        <a href="./create.php">Create User</a>

        <!-- GENERATE: Users table -->

        <table class="users_table">

            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($users as $user): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($user['name']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($user['email']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($user['role']) ?>
                        </td>

                        <td>
                            <a href="./edit.php?id=<?= urlencode($user['user_id']) ?>">
                                Edit
                            </a>
                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </main>

</section>

<?php require __DIR__ . '/../../components/bottom.php'; ?>