<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/user.model.php';
require_once __DIR__ . '/../controllers/auth.controller.php';

$authController = new AuthController($pdo);
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $authController->login(trim($_POST['email']), $_POST['password']);
    if ($result['success']) {
        header('Location: ./dashboard');
        exit;
    }
    $error = $result['message'];
}
?>

<?php require __DIR__ . '/../components/head.php'; ?>
<section class='section login'>
<!-- Navigate -->
<nav class='nav'>
    <p>Date Harvest Reports</p>         
</nav>


<!-- Login Body -->
<form method="post" class='login_form centered'>
 <h2>Login to your Account</h2>    
<?php if ($error): ?>    
    <p class="error_text"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<div>
        <label>Email</label>
        <input placeholder="Enter your email Address" name="email" type='email'/>
    </div>
    <div>
        <label>Password</label>
        <input type='password' placeholder="Enter your password" name="password"/>
    </div>

    <button>Sign In</button>
</div>

</section>   
<?php require __DIR__ . '/../components/bottom.php'; ?>