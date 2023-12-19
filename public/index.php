<?php

require '../helpers.php';
require __DIR__ . '/../vendor/autoload.php';

// Autoload classes
// spl_autoload_register(function ($class) {
//     $path = basePath("Framework/{$class}.php");
//     if (file_exists($path)) {
//         require $path;
//     }
// });

// Instantiate the routher
$router = new Router();

// Get routes
$routes = require basePath('routes.php');

// Get current URI and HTTP method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Route the request
$router->route($uri, $method);
