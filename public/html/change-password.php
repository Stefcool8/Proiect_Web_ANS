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
<div class="change-password">
    <form method="post">
        <h1>Change Password</h1>
        <div id="success-message" class="success-message hidden"></div>
        <div id="error-message" class="error-message hidden"></div>
        <div class="input-group">
            <label>
                Current Password
                <input type="password" id="currentPassword" required>
            </label>
        </div>
        <div class="input-group">
            <label>
                New Password
                <input type="password" id="newPassword" required>
            </label>
        </div>
        <div class="input-group">
            <label>
                Repeat New Password
                <input type="password" id="repeatNewPassword" required>
            </label>
        </div>
        <button type="submit">Change Password</button>
        <a href="#" class="back-to-profile">Back to Profile</a>
    </form>
</div>
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<script src="/public/js/change-password.js"></script>
</body>
</html>
