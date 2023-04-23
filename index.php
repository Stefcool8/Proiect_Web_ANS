<?php require_once __DIR__ . '/config/config.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web application</title>

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- favicon -->
    <link rel="icon" href="<?= BASE_URL; ?>/assets/img/globe.png">

    <!-- styles and scripts -->
    <?php require_once __DIR__ . '/public/includes/styles.php'; ?>
    <?php require_once __DIR__ . '/public/includes/scripts.php'; ?>

    <!-- external libraries -->
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.1/papaparse.min.js"></script>
    <script src="https://www.youtube.com/iframe_api"></script>
</head>
<body>
    <!-- Routes -->
    <?php require_once __DIR__ . '/public/includes/routes.php'; ?>
</body>
</html>