<?php
// ?token={token}
$query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
parse_str($query, $params);
$token = $params['token'];

if (!isset($token)) {
    header('Location: /');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <!-- css libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- favicon -->
    <link rel="icon" href="/public/assets/img/favicon.png">

    <link rel="stylesheet" href="/public/css/index.css">
    <link rel="stylesheet" href="/public/css/shared/navbar.css">
    <link rel="stylesheet" href="/public/css/shared/footer.css">
    <link rel="stylesheet" href="/public/css/reset-password.css">
</head>

<body>
<?php require_once __DIR__ . '/shared/navbar.php'; ?>

<div class="reset">
    <main class="main-content">
        <div class="container">
            <h2>Reset Password</h2>
            <form action="/api/password/reset">
                <div class="error-message hidden"></div>
                <div class="success-message hidden"></div>
                <div class="input-group">
                    <label for="password">New Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="input-group">
                    <label for="confirm-password">Confirm Password:</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                </div>
                <input type="hidden" id="token" name="token" value="<?= $token ?>">
                <button type="submit">Reset Password</button>
            </form>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/shared/footer.php'; ?>
<script src="/public/js/reset-password.js"></script>
</body>

</html>