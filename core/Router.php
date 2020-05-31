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
        error("Controller not found");
    }

    public function getUrl()
    {
        return $this->uri;
    }

}