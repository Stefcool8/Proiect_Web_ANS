<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utils\Router;

$router = Router::getRouter();

$router->add('GET', '/', 'HomeController::get');
$router->add('GET', '/home', 'HomeController::get');
$router->add('GET', '/contact', 'ContactController::get');
$router->add('POST', '/contact', 'ContactController::post');
$router->add('GET', '/about', 'AboutController::get');
$router->add('GET', '/auth/login', 'AuthController::getLogin');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
