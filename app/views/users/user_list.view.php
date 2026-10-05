<?php 
$title = 'DH Reports - Users List';
require __DIR__ . '/../../components/head.php'; ?>
<h1>Users</h1>

<p>Here are the users.</p>

<h1>Users</h1>

<?php foreach ($users as $user): ?>
    
    <p>
        <?= htmlspecialchars($user['name']) ?>
        -
        <?= htmlspecialchars($user['email']) ?>
    </p>
    
    <?php endforeach; ?>
    
<?php require __DIR__ . '/../../components/bottom.php'; ?>