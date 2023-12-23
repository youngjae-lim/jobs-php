<?php

session_start();

require '../helpers.php';
require __DIR__.'/../vendor/autoload.php';

use Framework\Router;

// Instantiate the router
$router = new Router();

// Get routes
$routes = require basePath('routes.php');

// Get current URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Route the request
$router->route($uri);
