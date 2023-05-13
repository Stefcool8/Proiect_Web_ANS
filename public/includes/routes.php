<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utils\Router;

$router = Router::getRouter();

$router->add('GET', '/api/', 'HomeController::get');
$router->add('GET', '/api/home', 'HomeController::get');
$router->add('GET', '/api/home/', 'HomeController::get');

$router->add('GET', '/api/contact', 'ContactController::get');
$router->add('GET', '/api/contact/', 'ContactController::get');
$router->add('POST', '/api/contact', 'ContactController::post');
$router->add('POST', '/api/contact/', 'ContactController::post');

$router->add('GET', '/api/about', 'AboutController::get');
$router->add('GET', '/api/about/', 'AboutController::get');

$router->add('GET', '/api/auth/login', 'AuthController::getLogin');
$router->add('GET', '/api/auth/login/', 'AuthController::getLogin');
$router->add('POST', '/api/auth/login', 'AuthController::postLogin');
$router->add('POST', '/api/auth/login/', 'AuthController::postLogin');

$router->add('GET', '/api/dashboard', 'DashboardController::get');
$router->add('GET', '/api/dashboard/', 'DashboardController::get');

$router->add('GET', '/api/auth/register', 'AuthController::getRegister');
$router->add('GET', '/api/auth/register/', 'AuthController::getRegister');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
