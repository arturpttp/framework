<?php

use Core\Essetials\Request;
use Core\Essetials\Session;

#AutoLoad
require_once __DIR__ . '/../../vendor/autoload.php';
#Config
require_once  __DIR__ . '/config.php';
#GlobalFunctions
require_once  __DIR__ . '/functions.php';
#Session
Session::start();
#Request
Request::start();
#Repository System
\Core\Essetials\Repository::start();
#Application
$app = new \Core\Application();
$app->setup();
#Router
$routes = require_once __DIR__ . "/../../app/routes.php";
$router = new \Core\Router($routes, $app);
$router->run();
