<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/controllers/RegisterController.php';

$klein = new \Klein\Klein();

$klein->respond('GET', '/', function () {
    require_once __DIR__ . '/../../app/views/pages/home.php';
});

$klein->respond('GET', '/home', function () {
    require_once __DIR__ . '/../../app/views/pages/home.php';
});

$klein->respond('GET', '/contact', function () {
    require_once __DIR__ . '/../../app/views/pages/contact.php';
});

$klein->respond('POST', '/contact', function () {
    require_once __DIR__ . '/../../app/views/pages/contact.php';
});

        $klein->respond('GET', '/about', function () {
            require_once '../app/views/pages/about.php';
        });

        $klein->respond('GET', '/login', function () {
            require_once '../app/views/pages/login.php';
        });

        $klein->respond('POST', '/login', function () {
            $loginController = new LoginController();
            return $loginController->login();
        });

$klein->respond('GET', '/signup', function () {
    require_once __DIR__ . '/../../app/views/pages/register.php';
});

$klein->respond('POST', '/signup', function () {
    $registerController = new \App\Controllers\RegisterController();
    $errors = $registerController->register();
    foreach ($errors as $error) {
        echo $error . '<br>';
    }
    require_once __DIR__ . '/../../app/views/pages/register.php';
});

        $klein->respond('GET', '/dashboard', function () {
            require_once '../app/views/pages/dashboard.php';
        });

        $klein->respond('GET', '/reset', function () {
            require_once '../app/views/pages/reset.php';
        });

        $klein->respond('GET', '/project/[:id]', function ($request) {
            require_once '../app/views/pages/project.php';
        });

        $klein->respond('GET', '/profile', function () {
            require_once '../app/views/pages/profile.php';
        });

        $klein->respond('GET', '/upload', function () {
            require_once '../app/views/pages/upload.php';
        });

        $klein->respond('GET', '/logout', function () {
            require_once '../app/views/pages/logout.php';
        });

        $klein->respond('GET', '/user/[:id]', function ($request) {
            $userController = new UserController();
            return $userController->getUser($request->id);
        });

        $klein->dispatch();