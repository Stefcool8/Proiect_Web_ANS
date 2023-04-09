<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/index.css">
    <link rel="stylesheet" href="./assets/css/navbar.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/css/login.css">
    <link rel="stylesheet" href="./assets/css/register.css">
    <link rel="stylesheet" href="./assets/css/home.css">
    <link rel="stylesheet" href="./assets/css/contact.css">
    <link rel="stylesheet" href="./assets/css/profile.css">

    <script src="./assets/js/navbar.js" defer></script>
    <script src="./assets/js/footer.js" defer></script>
    <title>Index Page</title>

</head>
<body>

    <?php

        // Determine which page to load based on the URL
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
        } elseif ($url === '/profile') {
            require_once '../app/views/pages/profile.php';
        } else {
            // If the URL doesn't match any of the above, show a 404 error page
            http_response_code(404);
            echo '404 Not Found';
        }

    ?>

</body>
</html>