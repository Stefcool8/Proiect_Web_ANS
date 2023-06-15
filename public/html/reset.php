<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset password</title>

    <!-- css libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- favicon -->
    <link rel="icon" href="/public/assets/img/favicon.png">

    <link rel="stylesheet" href="/public/css/index.css">
    <link rel="stylesheet" href="/public/css/shared/navbar.css">
    <link rel="stylesheet" href="/public/css/shared/footer.css">
    <link rel="stylesheet" href="/public/css/reset.css">
</head>
<body>
</script>
<?php require_once __DIR__ . '/shared/navbar.php'; ?>
<div class="reset">
    <main class="main-content">
        <div class="container">
            <h2>Reset password</h2>
            <form action="/auth/reset" method="post">
                <div class="error-message hidden"></div>
                <div class="success-message hidden"></div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit">Reset Password</button>
            </form>
            <div class="login-link">
                <p>Remembered your password? <a href="/login">Log in</a></p>
            </div>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<script src="/public/js/reset.js"></script>
</body>
</html>
