<?php

require_once '../vendor/autoload.php';

use App\Utils\Router;

$router = Router::getRouter();

$router->add('GET', '/api/home', 'HomeController::get');
$router->add('GET', '/api/about', 'AboutController::get');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
