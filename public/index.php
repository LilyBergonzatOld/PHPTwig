<?php

require_once '../vendor/autoload.php';
require_once '../application/Controller.php';
session_start();
$controller = new Controller();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case "login":
            $controller->login();
            break;
        case "logout":
            $controller->logout();
            break;
        case "register":
            $controller->register();
            break;
        case "profile":
            $controller->profile();
            break;
    }
} else {
    $controller->index();
}
