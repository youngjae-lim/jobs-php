<?php

namespace Framework;

use App\Controllers\ErrorController;
use Exception;

class Router
{
    protected $routes = [];

    /**
     * Register a route.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  string  $action
     * @return void
     */
    public function registerRoute($method, $uri, $action)
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
        ];
    }

    /**
     * Add a GET route.
     *
     * @param  string  $file
     * @return Router
     * @return void
     */
    public function get($uri, $action)
    {
        $this->registerRoute('GET', $uri, $action);
    }

    /**
     * Add a POST route.
     *
     * @param  string  $file
     * @return Router
     * @return void
     */
    public function post($uri, $action)
    {
        $this->registerRoute('POST', $uri, $action);
    }

    /**
     * Add a PUT route.
     *
     * @param  string  $file
     * @return Router
     * @return void
     */
    public function put($uri, $action)
    {
        $this->registerRoute('PUT', $uri, $action);
    }

    /**
     * Add a DELETE route.
     *
     * @param  string  $file
     * @return Router
     * @return void
     */
    public function delete($uri, $action)
    {
        $this->registerRoute('DELETE', $uri, $action);
    }

    /**
     * Route the request to the appropriate controller method.
     *
     * @param  string  $uri
     * @param  string  $method
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
