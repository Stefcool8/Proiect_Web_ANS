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
    <title>Edit User Profile</title>
</head>
<body>
<?php require_once __DIR__ . '/shared/navbar.php'; ?>
<div class="profile">
    <form method="post">
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
                </div>
            </div>
            <div class="user-details">
                <div id="error-message" class="error-message hidden"></div>
                <div id="success-message" class="success-message hidden"></div>
                <div class="input-group">
                    <label>
                        First name
                        <input type="text" name="firstName" class="data first-name" required>
                    </label>
                </div>
                <div class="input-group">
                    <label>
                        Last name
                        <input type="text" name="lastName" class="data last-name" required>
                    </label>
                </div>
                <div class="input-group">
                    <label>
                        Email
                        <input type="email" name="email" class="data email" required>
                    </label>
                </div>
                <div class="input-group">
                    <label>
                        Username
                        <input type="text" name="username" class="data username" required>
                    </label>
                </div>
                <div class="input-group">
                    <label>
                        Bio
                        <textarea name="bio" class="data bio"></textarea>
                    </label>
                </div>
                <button type="submit">Update Profile</button>
            </div>
        </div>
    </form>
</div>
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<script src="/public/js/user-edit.js"></script>
</body>
</html>
