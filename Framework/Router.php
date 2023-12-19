<?php

namespace Framework;

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

        list($controller, $controllerMethod) = explode('@', $action);

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod
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
     * Load error page based on status code.
     *
     * @param  int  $httpStatusCode
     * @return void
     */
    public function error($httpStatusCode = 404)
    {
        http_response_code($httpStatusCode);
        loadView("error/{$httpStatusCode}");
        exit;
    }

    /**
     * Route the request to the appropriate controller method.
     *
     * @param  string  $uri
     * @param  string  $method
     * @return void
     */
    public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === $method) {
                $controller = 'App\\Controllers\\' . $route['controller'];
                inspect($controller);
                $controllerMethod = $route['controllerMethod'];
                inspect($controllerMethod);

                // Instantiate the controller and call the method.
                $controllerInstance = new $controller();
                $controllerInstance->$controllerMethod();

                return;
            }
        }

        $this->error();
    }
}
