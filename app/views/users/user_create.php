<?php
/*
session_start();
require_once __DIR__ . '/../../middleware/auth.php';
requireAdmin();

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/user.model.php';
require_once __DIR__ . '/../../controllers/user.controller.php';

$userController = new UserController($pdo);

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $success = $userController->create($_POST);

    if ($success) {
        header('Location: ./index.php');
        exit;
    }

    $error = 'Failed to create user.';
}
*/
?>

<?php require __DIR__ . '/../../components/head.php'; ?>

<section class="section">

    <nav class="nav">
        <p>Date Harvest Reports</p>
    </nav>

    <main class="dashboard_body">

        <h2>Create User</h2>

        <?php if ($error): ?>
            <p class="error_text">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <form method="POST" class="report_form">

            <div>
                <label>Name</label>

                <input
                    type="text"
                    name="name"
                    placeholder="Enter user's name"
                    required
                >
            </div>

            <div>
                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter user's email"
                    required
                >
            </div>

            <div>
                <label>Role</label>

                <select name="role" required>
                    <option value="">Select role</option>
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div>
                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter password"
                    required
                >
            </div>

            <button type="submit">
                Create User
            </button>

            <a href="./index.php">
                Cancel
            </a>

        </form>

    </main>

</section>

<?php require __DIR__ . '/../../components/bottom.php'; ?>