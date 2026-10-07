<?php

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

function requireAuth()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: /dh_reports/app/views/login.php');
        exit;
    }
    
}

function requireAdmin()
{
    requireAuth();

    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        exit('Forbidden');
    }
}