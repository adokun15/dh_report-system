<?php
session_start();
require_once __DIR__ . '/../../controllers/user.controller.php';

$userController = new UserController($pdo);

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = $userController->create($_POST);
   if ($success) {
        header('Location: /dh_reports/dashboard/users');
        exit;
    }
    $error = 'Failed to create user.';
}
?>

<?php require __DIR__ . '/../../components/head.php'; ?>
<main>
    <?php require __DIR__ . '/../../components/private_nav.php'; ?>
<section class="create-user">
    <div class="create-user__header">
        <div>
            <h1>Create User</h1>
            <p>Add an admin or staff member to the reporting system.</p>
        </div>

        <a href="/dh_reports/dashboard/users" class="">
            Back to Users
        </a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="form-error">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form
        method="POST"
        class="create-user__form"
    >
        <div class="form-group">
            <label for="name">Username</label>
            <input
                type="text"
                id="name"
                name="name"
                placeholder="Enter full name"
                required
            >
        </div>

        <div class="form-group">
            <label for="email">Email address</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="name@example.com"
                required
            >
        </div>

        <div class="form-group">
            <label for="role">User role</label>
            <select id="role" name="role" required>
                <option value="staff" selected>
                    Staff
                </option>
                      </select>
        </div>

        <div class="default-password">
                <div>
                <p> <strong>Default password</strong>: password123</p>
                <small>This will be the user's initial login password.</small>
            </div>
        </div>

        <button class="create_user_btn">
            Create User
        </button>
    </form>
</section>
            </main>
<?php require __DIR__ . '/../../components/bottom.php'; ?>