<?php


namespace Core;


use Core\Database\Database;
use Core\Essetials\Controller;
use Core\Essetials\Model;
use Illuminate\Support\Facades\DB;

class Container
{

    public static function newController($controller): Controller
    {
        $objController = "App\\Controllers\\" . $controller;
        if (!class_exists($objController)) {
            error("Controller {$controller} not found");
            die();
        }
        return new $objController;
    }

    public static function getModel($model): Model
    {
        $objModel = "\\App\\Models\\" . $model;
        if (!class_exists($objModel)) {
            error("Controller {$model} not found");
            die();
        }
        return new $objModel();
    }

    public static function pageNotFound()
    {
        if(file_exists(__DIR__ . "/../app/Views/404.php")){
            return require_once __DIR__ . "/../app/Views/404.php";
        }else{
            return error("Page nont found");
        }
    }

}