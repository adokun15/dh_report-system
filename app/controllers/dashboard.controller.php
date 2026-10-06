<?php

function dashboard()
{
    require_once  __DIR__ . "/../models/dashboard.model.php";

    //geT SUMMARY!
    $dashboard = getDashboardSummary();

    require_once  __DIR__ . "/../views/dashboard.php";
}