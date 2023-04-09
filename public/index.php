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
    <link rel="stylesheet" href="./assets/css/profile.css">
    <link rel="stylesheet" href="./assets/css/upload.css">

    <link rel="icon" href="/assets/img/globe.png">

    <script src="./assets/js/about.js"></script>
    <script src="./assets/js/upload.js"></script>
    <script src="./assets/js/home.js"></script>
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.1/papaparse.min.js"></script>
    <title>Web application</title>

</head>
<body>

    <?php

        // determine which page to load based on the URL
        $url = $_SERVER['REQUEST_URI'];
        if ($url === '/') {
            require_once '../app/views/pages/home.php';
        } elseif ($url === '/home') {
            require_once '../app/views/pages/home.php';
        } elseif ($url === '/contact') {
            require_once '../app/views/pages/contact.php';
        } elseif ($url === '/login') {
            require_once '../app/views/pages/login.php';
        } elseif ($url === '/signup') {
            require_once '../app/views/pages/register.php';
        } elseif ($url === '/about') {
            require_once '../app/views/pages/about.php';
        } elseif ($url === '/dashboard') {
            require_once '../app/views/pages/dashboard.php';
        } elseif ($url === '/reset') {
            require_once '../app/views/pages/reset.php';
        } elseif ($url === '/project/1') {
            require_once '../app/views/pages/project.php';
        } elseif ($url === '/project/2') {
            require_once '../app/views/pages/project.php';
        } elseif ($url === '/project/3') {
            require_once '../app/views/pages/project.php';
        } elseif ($url === '/project/4') {
            require_once '../app/views/pages/project.php';
        } elseif ($url === '/profile') {
            require_once '../app/views/pages/profile.php';
        } elseif ($url === '/upload') {
            require_once '../app/views/pages/upload.php';
        }
        else {
            // if the URL doesn't match any of the above, show a 404 error page
            http_response_code(404);
            echo '404 Not Found';
        }

    ?>

</body>
</html>