<?php
session_start();
require_once __DIR__ . '/../../controllers/user.controller.php';
$userController = new UserController($pdo);
$users = $userController->index();
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
        <div>
            <h2>Users</h2>    
            <a href="create">Create User</a>
        </div>
        <!-- GENERATE: Users table -->

        <table class="users_table">

            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Reports</th>
                    
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
                            <?= htmlspecialchars($user['total_reports']) ?>
                        </td>

                   <!--     <td>
                            <a href="./edit.php?id=<?/*= urlencode($user['user_id']) */ ?>">
                                Edit
                            </a>
                        </td>
                -->
                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </main>

</section>

<?php require __DIR__ . '/../../components/bottom.php'; ?>