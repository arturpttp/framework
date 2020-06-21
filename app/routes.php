<?php

use Core\Router;
use Core\User\User;

$route[] = ['/', 'HomeController@index', Router::$defaultInfos// = [
//    "auth" => false,
//    "level" => 0
//]
];
$route[] = ['/{id}/{name}/{email}/{password}', 'HomeController@test'];
$route[] = ['/user/register', 'UserController@register'];
$route[] = ['/user/register/submit', 'UserController@registerSubmit'];
$route[] = ['/user/profile/{id}', 'UserController@profile'];
$route[] = ['/user/login', 'UserController@login'];

Router::get("/user", "UserController@index");
Router::get("/user/test", "UserController@index", true, 2);

return $route;