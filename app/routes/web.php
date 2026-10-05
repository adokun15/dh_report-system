<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/dh_reports';

$route = str_replace($base, '', $uri);

if ($route === '/users') {
    require_once   __DIR__ . "/../controllers/user.controller.php";
    index();
}