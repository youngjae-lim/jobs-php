<?php

namespace Framework;

use App\Controllers\ErrorController;
use Exception;
use Framework\middleware\Authorize;

class Router
{
    protected $routes = [];

    /**
     * Register a route.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  string  $action
     * @param  array  $middleware
     * @return void
     */
    public function registerRoute($method, $uri, $action, $middleware)
    {
        if (strpos($action, '@') === false) {
            throw new Exception('Invalid route action.');
        }

        [$controller, $controllerMethod] = explode('@', $action);

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod,
            'middleware' => $middleware,
        ];
    }

    /**
     * Add a GET route.
     *
     * @param  string  $uri
     * @param  string  $action
     * @param  array  $middleware
     * @return void
     */
    public function get($uri, $action, $middleware = [])
    {
        $this->registerRoute('GET', $uri, $action, $middleware);
    }

    /**
     * Add a POST route.
     *
     * @param  string  $uri
     * @param  string  $action
     * @param  array  $middleware
     * @return void
     */
    public function post($uri, $action, $middleware = [])
    {
        $this->registerRoute('POST', $uri, $action, $middleware);
    }

    /**
     * Add a PUT route.
     *
     * @param  string  $uri
     * @param  string  $action
     * @param  array  $middleware
     * @return void
     */
    public function put($uri, $action, $middleware = [])
    {
        $this->registerRoute('PUT', $uri, $action, $middleware);
    }

    /**
     * Add a DELETE route.
     *
     * @param  string  $uri
     * @param  string  $action
     * @param  array  $middleware
     * @return void
     */
    public function delete($uri, $action, $middleware = [])
    {
        $this->registerRoute('DELETE', $uri, $action, $middleware);
    }

    /**
     * Route the request to the appropriate controller method.
     *
     * @param  string  $uri
     * @return void
     */
    public function route($uri)
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Check for _method input
        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            // Override the request method
            $requestMethod = $_POST['_method'];
        }

        foreach ($this->routes as $route) {
            // Split the route URI into parts
            $uriSegments = explode('/', trim($uri, '/'));
            $routeSegments = explode('/', trim($route['uri'], '/'));

            // Check if the number of segments matches and if the request method matches the route method
            if (count($uriSegments) === count($routeSegments) && $requestMethod === $route['method']) {
                $params = [];

                $match = true;

                // Loop through the route uriSegments
                for ($i = 0; $i < count($uriSegments); $i++) {
                    // If the segments don't match and the current route segment is not a parameter, stop matching
                    // In other words, if either the segments match or the current route segment is a parameter, continue matching
                    if ($uriSegments[$i] !== $routeSegments[$i] && ! preg_match('/\{(.+?)\}/', $routeSegments[$i])) {
                        $match = false;
                        break;
                    }

                    // If the current route segment is a parameter, add it to the params array
                    if (preg_match('/\{(.+?)\}/', $routeSegments[$i], $matches)) {
                        $params[$matches[1]] = $uriSegments[$i];
                    }
                }

                if ($match) {
                    // Handle middleware
                    foreach ($route['middleware'] as $middleware) {
                        (new Authorize())->handle($middleware);
                    }

                    $controller = 'App\\Controllers\\'.$route['controller'];
                    $controllerMethod = $route['controllerMethod'];
                    // Instantiate the controller and call the method.
                    $controllerInstance = new $controller();
                    $controllerInstance->$controllerMethod($params);

                    return;
                }
            }
        }

        ErrorController::notFound();
    }
}
