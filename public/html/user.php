<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/public/css/shared/navbar.css">
    <link rel="stylesheet" href="/public/css/shared/footer.css">
    <link rel="stylesheet" href="/public/css/user.css">
    <link rel="stylesheet" href="/public/css/index.css">
    <link rel="icon" href="/public/assets/img/favicon.png">
    <title>User Profile</title>
</head>
<body>
<?php require_once __DIR__ . '/shared/navbar.php'; ?>
<div class="profile">
    <div class="main-content">
        <div class="user-visual">
            <div class="user-picture-and-controls">
                <div class="user-picture">
                    <img src="/public/assets/img/user.jpg" alt="User Picture">
                </div>
                <div class="user-name-and-email">
                    <h1 class="header-username">Username</h1>
                    <h5 class="header-email">Email</h5>
                </div>
                <div class="controls">
                    <button class="modify">Modify Account</button>
                    <button class="delete">Delete Account</button>
                    <button class="change-pass">Change Password</button>
                </div>
            </div>
        </div>
        <div class="user-details">
            <div class="input-group">
                <label>First name</label>
                <p class="data first-name"></p>
            </div>
            <div class="input-group">
                <label>Last name</label>
                <p class="data last-name"></p>
            </div>
            <div class="input-group">
                <label>Email</label>
                <p class="data email"></p>
            </div>
            <div class="input-group">
                <label>Username</label>
                <p class="data username"></p>
            </div>
            <div class="input-group">
                <label>
                    Bio
                    <textarea class="data bio" disabled></textarea>
                </label>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<script src="/public/js/user.js"></script>
</body>
</html>
