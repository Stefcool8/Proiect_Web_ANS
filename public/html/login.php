<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- css libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- favicon -->
    <link rel="icon" href="/public/assets/img/favicon.png">

    <link rel="stylesheet" href="/public/css/index.css">
    <link rel="stylesheet" href="/public/css/shared/navbar.css">
    <link rel="stylesheet" href="/public/css/shared/footer.css">
    <link rel="stylesheet" href="/public/css/login.css">
</head>
<body>
<script src="/public/js/helper/logged.js">
</script>
<?php require_once __DIR__ . '/shared/navbar.php'; ?>
<div class="login">
    <main class="main-content">
        <div class="container">
            <h2>Login</h2>
            <form method="post">
                <div class="error-message hidden"></div>
                <div class="success-message hidden"></div>
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Log In</button>
            </form>
            <div class="signup-link">
                <p>Don't have an account? <a href="/register">Sign up</a></p>
                <p>Forgot your password? <a href="/reset">Reset password</a></p>
            </div>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<script src="/public/js/login.js"></script>
</body>
</html>