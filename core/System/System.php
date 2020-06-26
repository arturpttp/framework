<?php


namespace Core\System;


use Core\Application;
use Core\Router;
use Core\Shadowz;

trait System {

    public static function redirect($url, $with = [], $time = 0, $message = false, $die = false) {
        if (count($with) > 0)
            foreach ($with as $key => $value)
                Session::set($key, $value);
        echo "<meta http-equiv=\"refresh\" content=\"{$time}; URL='{$url}\"/>";
        if ($message)
            echo "<span class='alert alert-danger'>{$message}</span>";
        if ($die)
            die;
    }

    public static function app(): Application
    {
        return Shadowz::$app;
    }

    public static function routes()
    {
        return require_once dirname(dirname(__DIR__)) . "/app/routes.php";
    }

    public static function router(): Router
    {
        return Shadowz::$router;
    }

}