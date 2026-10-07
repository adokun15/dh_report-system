<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = '/dh_reports';
$route = str_replace($base, '', $uri);

switch($route){
    case '/index.php':
        require_once  __DIR__ . "/../views/home.php";
        break;
        
        case '/login':
            require_once  __DIR__ . "/../views/login.php";
    break;
    
    case '/users':
        require_once   __DIR__ . "/../controllers/user.controller.php";
        index();
    break;
    
    case '/dashboard/reports':
        require_once   __DIR__ . "/../views/reports/report_list.php";
    break;
    
    case '/dashboard':
        require_once   __DIR__ . "/../views/dashboard.php";
        //dashboard();
    break;
    default:
        echo 'Page Not Found!';
}