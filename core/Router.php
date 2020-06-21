<?php


namespace Core;


use App\Models\UserModel;
use Core\Essetials\Session;
use Core\Essetials\System;
use Core\User\User;

class Router
{

    private $routes;
    private $uri;
    private $app;
    public static $staticRoutes = [];
    public static $defaultInfos = [
        "auth" => false, //true if user need to be logged
        "level" => 0 //level to access the route User::$levels
    ];

    public function __construct($routes, $app)
    {
        $this->routes = $routes;
        foreach (self::$staticRoutes as $route)
            $this->routes[] = $route;
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->routeNormalize($this->routes);
        $this->app = $app;
    }

    public static function get($route, $data, $auth = false, $level = 0) {
        $infos = [
            "auth" => $auth,
            "level" => $level
        ];
        $r = [$route, $data, $infos];
        return self::$staticRoutes[] = $r;
    }

    private function routeNormalize($routes) {
        $nRoutes = null;
        foreach ($routes as $route) {
            $explode = explode("@", $route[1]);
            if (isset($route[2])) {
                $r = [$route[0], $explode[0], $explode[1], $route[2]];
            }else {
                $r = [$route[0], $explode[0], $explode[1], self::$defaultInfos];
            }
            $nRoutes[] = $r;
        }
        if ($nRoutes == null)
            error('routeNormalize->[$nRoutes] is null');
        $this->routes = $nRoutes;
        return $this->routes;
    }

    private function getRequest()
    {
        $obj = new \stdClass;

        foreach ($_GET as $key => $value){
            @$obj->get->$key = $value;
        }

        foreach ($_POST as $key => $value){
            @$obj->post->$key = $value;
        }

        return $obj;
    }

    public function getUrl()
    {
        return $this->uri;
    }

    public function run()
    {
        $url = $this->getUrl();
        $urlArray = explode('/', $url);

        foreach ($this->routes as $route) {
            $routeArray = explode('/', $route[0]);
            $param = [];
            for($i = 0; $i < count($routeArray); $i++){
                if((strpos($routeArray[$i], "{") !==false) && (count($urlArray) == count($routeArray))){
                    $routeArray[$i] = $urlArray[$i];
                    $param[] = $urlArray[$i];
                }
                $route[0] = implode($routeArray, '/');
            }

            if($url == $route[0]){
                $found = true;
                $controller = $route[1];
                $action = $route[2];
                $infos = $route[3];
                $userModel = new UserModel();
                $user = $userModel->find(['id' => Session::get('loggedUser', null)])->first();
                if ($infos["auth"] == true && !User::isLogged())
                    $action = 'unLogged';
                if (User::isLogged() && $user["level"] < $infos["level"])
                    $action = 'noPermission';
                break;
            }
        }

        if(isset($found)){
            $controllerName = $controller;
            $controller = Container::newController($controller);
            $controller->app = $this->app;
            if (!method_exists($controller, $action)) {
                error("method {$action} not found in {$controllerName}");
                die();
            }
            switch (count($param)){
                case 1:
                    $controller->$action($param[0], $this->getRequest());
                    break;
                case 2:
                    $controller->$action($param[0], $param[1], $this->getRequest());
                    break;
                case 3:
                    $controller->$action($param[0], $param[1], $param[2], $this->getRequest());
                    break;
                case 4:
                    $controller->$action($param[0], $param[1], $param[2], $param[3], $this->getRequest());
                    break;
                default:
                    $controller->$action($this->getRequest());
                    break;
            }
        }else{
            Container::pageNotFound();
        }
    }

}