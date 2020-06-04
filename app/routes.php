<?php
$route[] = ['/', 'HomeController@index'];
$route[] = ['/{id}/{name}/{email}/{password}', 'HomeController@test'];
$route[] = ['/user/register', 'UserController@login'];
$route[] = ['/user/{id}', 'UserController@profile'];
$route[] = ['/user/login', 'UserController@register'];


return $route;