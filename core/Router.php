<?php

class Router {
    private $routes = [];

    public function add($method, $path, $handler) {
        $this->routes[$method][$path] = $handler;
    }

    public function get($path, $handler) {
        $this->add('GET', $path, $handler);
    }

    public function post($path, $handler) {
        $this->add('POST', $path, $handler);
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/');

        if (isset($this->routes[$method][$uri])) {
            $handler = $this->routes[$method][$uri];
            if (is_callable($handler)) {
                call_user_func($handler);
            } elseif (is_string($handler)) {
                $this->callController($handler);
            }
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
    }

    private function callController($handler) {
        list($controller, $method) = explode('@', $handler);
        $controllerClass = $controller . 'Controller';
        require_once "controllers/$controllerClass.php";
        $instance = new $controllerClass();
        $instance->$method();
    }
}