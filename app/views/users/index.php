<?php
session_start();
require_once __DIR__ . '/../../controllers/user.controller.php';
$userController = new UserController($pdo);
$users = $userController->index();
?>



<?php require __DIR__ . '/../../components/head.php'; ?>

<section class="section">

    <?php require __DIR__ . '/../../components/private_nav.php'; ?>

    <main class="dashboard_body centered">
        <div class="reports_header">
            <h2>Users</h2>    
            <a href="users/create">Create User</a>
        </div>
        <!-- GENERATE: Users table -->

        <table class="users_table">

    <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Reports</th>                    
                </tr>
            </thead>
 <tbody>
                        <?php foreach ($users as $user): ?>  <tr>
           <tr>
                        <td>
                           <p> <?= htmlspecialchars($user['name']) ?></p>
                            <p><?= htmlspecialchars($user['email']) ?></p>
                        </td>
                        <td>
                            <?= htmlspecialchars($user['role']) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($user['total_reports']) ?>
                        </td>
                    </tr>

        <?php endforeach; ?>
    </tbody>
</table>


    </main>

</section>

<?php require __DIR__ . '/../../components/bottom.php'; ?>