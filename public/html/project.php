<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project</title>

    <!-- css libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- favicon -->
    <link rel="icon" href="/public/assets/img/favicon.png">

    <link rel="stylesheet" href="/public/css/index.css">
    <link rel="stylesheet" href="/public/css/shared/navbar.css">
    <link rel="stylesheet" href="/public/css/shared/footer.css">
    <link rel="stylesheet" href="/public/css/project.css">
</head>

<body>
<?php require_once __DIR__ . '/shared/navbar.php'; ?>
<div class="project">
    <main class="main-content">
        <div class="container" id="project-container">
            <h2>Project</h2>
            <div class="input-group">
                <label for="projectName">Project Name</label>
                <input type="text" id="projectName" name="projectName" readonly>
            </div>
            <div class="input-group">
                <label for="chartType">Chart Type</label>
                <input type="text" id="chartType" name="chartType" readonly>
            </div>
            <div class="input-group">
                <label for="years">Years</label>
                <input type="text" id="years" name="years" readonly>
            </div>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<script src="/public/js/project.js"></script>
</body>

</html>
