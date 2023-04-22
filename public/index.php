<?php
    declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/index.css">
    <link rel="stylesheet" href="/assets/css/navbar.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <link rel="stylesheet" href="/assets/css/login.css">
    <link rel="stylesheet" href="/assets/css/register.css">
    <link rel="stylesheet" href="/assets/css/home.css">
    <link rel="stylesheet" href="/assets/css/contact.css">
    <link rel="stylesheet" href="/assets/css/about.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link rel="stylesheet" href="/assets/css/reset.css">
    <link rel="stylesheet" href="/assets/css/project.css">
    <link rel="stylesheet" href="/assets/css/profile.css">
    <link rel="stylesheet" href="/assets/css/upload.css">

    <link rel="icon" href="/assets/img/globe.png">

    <script src="./assets/js/about.js" defer></script>
    <script src="./assets/js/profile.js" defer></script>

    <script src="./assets/js/upload.js" defer></script>
    <script src="./assets/js/paste.js" defer></script>
    <script src="./assets/js/drag-drop.js" defer></script>
    <script src="./assets/js/url.js" defer></script>
    <script src="./assets/js/try.js" defer></script>

    <script src="./assets/js/home.js" defer></script>
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.1/papaparse.min.js"></script>
    <title>Web application</title>

</head>
<body>

    <?php
        require_once '../vendor/autoload.php';
        require_once '../app/controllers/UserController.php';
        require_once '../app/controllers/LoginController.php';
        require_once '../app/models/User.php';

        use App\Models\User;

        $user = new User(1, 'john_doe', "ana");




        // require_once '../utils/Database.php';
        // use Utils\Database;
        // $db = new Database();

        // Insert a new row
        // $db->insert('user', [
        //     'username' => 'John Doe',
        //     'email' => 'john@example.com',
        // ]);

        // Update a row
        // $db->update('user', [
        //     'username' => 'Nic',
        //     'email' => 'jane@example.com',
        // ], [
        //     'id' => 6
        // ]);

        // Delete a row
        // $db->delete('user', [
        //     'id' => 6
        // ]);



        $klein = new \Klein\Klein();

        $klein->respond('GET', '/', function () {
            require_once '../app/views/pages/home.php';
        });

        $klein->respond('GET', '/home', function () {
            require_once '../app/views/pages/home.php';
        });

        $klein->respond('GET', '/contact', function () {
            require_once '../app/views/pages/contact.php';
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
            require_once '../app/views/pages/register.php';
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

    ?>

</body>
</html>