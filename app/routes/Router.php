<?php

class Router
{
    private $routes = [];

    public function add($path, $callback)
    {
        $this->routes[$path] = $callback;
    }

    public function dispatch($uri)
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = trim($uri, '/');

        foreach ($this->routes as $path => $callback) {
            if ($uri === trim($path, '/')) {
                if (is_callable($callback)) {
                    return call_user_func($callback);
                } elseif (is_array($callback)) {
                    [$controller, $method] = $callback;
                    $controllerInstance = new $controller();
                    return call_user_func([$controllerInstance, $method]);
                }
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
?>