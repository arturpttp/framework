<?php


namespace Core;


class Router
{

    private $routes;
    private $uri;

    public function __construct($routes)
    {
        $this->routes = $routes;
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->routeNormalize($this->routes);
    }

    private function routeNormalize($routes) {
        $nRoutes = null;
        foreach ($routes as $route) {
            $explode = explode("@", $route[1]);
            if (isset($route[2])) {
                $r = [$route[0], $explode[0], $explode[1], $route[2]];
            }else {
                $r = [$route[0], $explode[0], $explode[1]];
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
//                $auth = new Auth;
//                if(isset($route[3]) && $route[3] == 'auth' && !$auth->check()){
//                    $action = 'forbiden';
//                }
                break;
            }
        }

        if(isset($found)){
            $controllerName = $controller;
            $controller = Container::newController($controller);
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