<?php 
session_start();
$_SESSION = []; 
session_destroy(); 
header("Location: /dh_reports/login"); 
exit; 
?>