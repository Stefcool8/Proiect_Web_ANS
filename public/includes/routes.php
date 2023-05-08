<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controllers\ContactController;
use App\Utils\Router;

$router = new Router();
$contactController = new ContactController();

$router->add('GET', '/', function () {
    require_once __DIR__ . '/../../app/views/pages/home.php';
});

$router->add('GET', '/home', function () {
    require_once __DIR__ . '/../../app/views/pages/home.php';
});

$router->add('GET', '/contact', function () {
    require_once __DIR__ . '/../../app/views/pages/contact.php';
});

$router->add('POST', '/contact', function () use ($contactController) {
    $contactController->postContact();
});

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);