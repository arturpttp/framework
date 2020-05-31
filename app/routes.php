<?php
$route[] = ['/', 'HomeController@index'];
$route[] = ['/user/register', 'UserController@login'];
$route[] = ['/user/{id}', 'UserController@profile'];
$route[] = ['/user/login', 'UserController@register'];


return $route;