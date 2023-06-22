<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <!-- css libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- favicon -->
    <link rel="icon" href="/public/assets/img/favicon.png">

    <link rel="stylesheet" href="/public/css/index.css">
    <link rel="stylesheet" href="/public/css/shared/navbar.css">
    <link rel="stylesheet" href="/public/css/shared/footer.css">
    <link rel="stylesheet" href="/public/css/admin.css">
</head>
<body>
<?php require_once __DIR__ . '/shared/navbar.php'; ?>
<main class="central-area">
    <div class="page-name">
        <p> </p>
    </div>
    
    <div>
        <a href="/user" class="view-profile-btn">View Profile</a>
        <a href="/upload" class="create-project-btn">Create new project</a>
        <a href="/viewUsers" class="view-users-btn">View Users</a>
    </div>
    <div class="project-area">
    </div>
</main>
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<script src="/public/js/admin.js"></script>
</body>
</html>