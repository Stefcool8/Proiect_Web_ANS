<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>

    <!-- css libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- favicon -->
    <link rel="icon" href="/public/assets/img/favicon.png">

    <link rel="stylesheet" href="/public/css/index.css">
    <link rel="stylesheet" href="/public/css/shared/navbar.css">
    <link rel="stylesheet" href="/public/css/shared/footer.css">
    <link rel="stylesheet" href="/public/css/register.css">
</head>

<body>
<?php require_once __DIR__ . '/shared/navbar.php'; ?>
<div class="register">
    <main class="main-content">
        <div class="container">
            <h2>Register</h2>
            <form>
                <div id="error-message" class="error-message invisible"></div>
                <div class="input-group">
                    <label for="first-name">First name</label>
                    <input type="text" id="first-name" name="first-name" required>
                </div>
                <div class="input-group">
                    <label for="last-name">Last name</label>
                    <input type="text" id="last-name" name="last-name" required>
                </div>
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="input-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                <button type="submit">Register</button>
                <div class="login-link">
                    <p>Already have an account? <a href="/login">Log in</a></p>
                </div>  
            </form>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<script src="/public/js/register.js"></script>
</body>
</html>