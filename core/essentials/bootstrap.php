<?php

#Imports
use Core\Shadowz;
use Core\System\Repository;
use Core\System\Request;
use Core\System\Session;
use Core\System\System;

#AutoLoad
require_once __DIR__ . '/../../vendor/autoload.php';
#Config
require_once  __DIR__ . '/config.php';
#GlobalFunctions
require_once  __DIR__ . '/functions.php';
#Shadowz setup
$shadowz = new Shadowz();
#Session
Session::start();
#Request
Request::start();
#Repository System
Repository::start();
#Application
$app = System::app();
#Router
$router = System::router();
