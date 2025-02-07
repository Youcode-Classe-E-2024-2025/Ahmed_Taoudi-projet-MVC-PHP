<?php

namespace app\core;
use Exception;

class Router
{
    private $routes = [];

    private $namespace;

    public function __construct(){
        $this->namespace = "app\\controllers\\";
    }

    public function add($method, $path, $handler)
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            $pattern = $this->convertToRegex($route['path']);

            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {

                array_shift($matches);

                $args = array_values($matches);

                $handlerName = explode('@', $route['handler']);
                if (count($handlerName) !== 2) {
                    throw new Exception("Invalid handler format. Expected 'Controller@method'.");
                }

                $className = $this->namespace . ucfirst($handlerName[0]) . 'Controller';
                $methodName = $handlerName[1];

                if (class_exists($className) && method_exists($className, $methodName)) {
                    $controller = new $className();
                    return call_user_func_array([$controller, $methodName], $args);
                } else {
                    throw new Exception("Controller or method not found: $className@$methodName");
                }
            }
        }

        http_response_code(404);
        echo '404 Not found';
    }


    private function convertToRegex($path)
    {
        $pattern = preg_replace('/\{([^}]+)\}/', '(?P<\1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
}