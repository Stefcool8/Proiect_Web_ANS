<?php

require_once '../vendor/autoload.php';

use App\Utils\Router;

$router = Router::getRouter();

$router->add('GET', '/api/home', 'HomeController::get');
$router->add('GET', '/api/about', 'AboutController::get');

$router->add('GET','/api/login','LoginController::get');
$router->add('POST','/api/login', 'LoginController::login');

$router->add('GET', '/api/contact', 'ContactController::get');

// User routes
$router->add('POST', '/api/user', 'UserController::create');
$router->add('GET', '/api/user/{uuid}', 'UserController::get');
$router->add('DELETE', '/api/user/{uuid}', 'UserController::delete');

$router->add('GET', '/api/dashboard', 'DashboardController::get');

$router->add('GET', '/api/auth', 'AuthController::get');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
