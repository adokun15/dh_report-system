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
    
    //Actions
    case '/dashboard/reports/create':
        require_once   __DIR__ . "/../views/reports/report_create.php";
    break;

    case '/dashboard/users/create':
        require_once   __DIR__ . "/../views/users/user_create.php";
    break;

    //SINGLE date display!
    case '/dashboard/reports/view':
        require_once   __DIR__ . "/../views/reports/report_view.php";
    break;

    //List Items / Indexes
    case '/dashboard/users':
        require_once   __DIR__ . "/../views/users/index.php";
    
    break;
    
    case '/dashboard/reports':
        require_once   __DIR__ . "/../views/reports/index.php";
    break;
    
    case '/dashboard':
        require_once   __DIR__ . "/../views/dashboard.php";
        //dashboard();
    break;
    default:
        echo 'Page Not Found!';
}