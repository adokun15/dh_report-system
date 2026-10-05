<?php

function index()
{
    require_once  __DIR__ . "/../models/users.model.php";

    $users = getUsers();

    require_once  __DIR__ . "/../views/users/user_list.view.php";
}