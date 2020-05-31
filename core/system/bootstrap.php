<?php
    #AutoLoad
    require_once __DIR__ . '/../../vendor/autoload.php';
    #Config
    require_once  __DIR__ . '/config.php';
    #GlobalFunctions
    require_once  __DIR__ . '/functions.php';
    #Session
    if (!session_id()) session_start();
    #Router
    $routes = require_once __DIR__ . "/../../app/routes.php";
    $router = new \Core\Router($routes);