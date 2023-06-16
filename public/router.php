<?php

require_once '../vendor/autoload.php';

use App\Utils\Router;

$router = Router::getRouter();

// home routes
$router->add('GET', '/api/home', 'HomeController::get');

// contact routes
$router->add('POST', '/api/contact', 'ContactController::create');

// dashboard routes
$router->add('GET', '/api/dashboard', 'DashboardController::get');
$router->add('GET', '/api/admin','AdminController::getInfo');


// user routes
$router->add('POST', '/api/user', 'UserController::create');
$router->add('GET', '/api/user/{uuid}', 'UserController::get');
$router->add('DELETE', '/api/user/{uuid}', 'UserController::delete');
$router->add('PUT', '/api/user/{uuid}', 'UserController::update');
$router->add('GET','/api/user','UserController::gets');

// project routes
$router->add('GET', '/api/project', 'ProjectController::gets');
$router->add('POST','/api/project','ProjectController::create');
$router->add('DELETE', '/api/project/{uuid}', 'ProjectController::delete');
$router->add('GET','/api/project/{uuid}','ProjectController::get');

// about us route
$router->add('GET', '/api/about', 'AboutController::get');

// login route
$router->add('GET','/api/login','LoginController::get');
$router->add('POST','/api/login', 'LoginController::login');

// auth routes
$router->add('GET', '/api/auth', 'AuthController::get');
$router->add('GET','/api/auth/admin','AuthController::getAdmin');
$router->add('POST','/api/auth/verifyAccess','AuthController::verifyAccess');

// password routes
$router->add('POST', '/api/password/reset', 'PasswordController::forgotPassword');
$router->add('PUT', '/api/password/reset', 'PasswordController::resetPassword');

$router->add('POST', '/api/test', 'TestController::test');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
