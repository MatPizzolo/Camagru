<?
class Router
{
    private $routes = [];
    private $globalMiddleware = [];

    public function add($path, $callback, $methods = ['GET'], $middleware = [])
    {
        $this->routes[$path] = [
            'callback' => $callback,
            'methods' => $methods,
            'middleware' => $middleware
        ];
    }

    public function use($middleware)
    {
        $this->globalMiddleware[] = $middleware;
    }

    public function dispatch($uri, $method)
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = trim($uri, '/');

        foreach ($this->routes as $path => $route) {
            $pattern = preg_replace('/\{[^\/]+\}/', '([^/]+)', trim($path, '/'));
            $pattern = "#^{$pattern}$#";

            if (preg_match($pattern, $uri, $matches)) {
                if (!in_array($method, $route['methods'])) {
                    http_response_code(405); // Method Not Allowed
                    echo json_encode(['error' => 'Method not allowed']);
                    return;
                }

                array_shift($matches); // Remove the full match

                // Run global middleware
                foreach ($this->globalMiddleware as $middleware) {
                    $middlewareInstance = new $middleware();
                    if (!$middlewareInstance->handle()) {
                        return; // Stop execution if middleware fails
                    }
                }

                // Run route-specific middleware
                foreach ($route['middleware'] as $middleware) {
                    $middlewareInstance = new $middleware();
                    if (!$middlewareInstance->handle()) {
                        return; // Stop execution if middleware fails
                    }
                }

                $callback = $route['callback'];
                if (is_callable($callback)) {
                    return call_user_func_array($callback, $matches);
                } elseif (is_array($callback)) {
                    [$controller, $method] = $callback;
                    $controllerInstance = new $controller();

                    $request = $_REQUEST; // Optional: merge $_POST, $_GET here
                    return call_user_func_array([$controllerInstance, $method], [$request]);
                }
            }
        }

        http_response_code(404); // Not Found
        echo json_encode(['error' => 'Not Found']);
    }
}
?>