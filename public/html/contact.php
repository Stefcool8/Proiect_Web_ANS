<?php
require_once __DIR__ . '/shared/general.php';
$data = fetch_data('contact', [
    'title' => 'Default title'
]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

    <!-- css libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- favicon -->
    <link rel="icon" href="/public/assets/img/favicon.png">

    <link rel="stylesheet" href="/public/css/index.css">
    <link rel="stylesheet" href="/public/css/shared/navbar.css">
    <link rel="stylesheet" href="/public/css/shared/footer.css">
    <link rel="stylesheet" href="/public/css/contact.css">
</head>
<body>
<?php require_once __DIR__ . '/shared/navbar.php'; ?>
<div class="contact-page">
    <main class="main-content">
        <div class="contact-form-container">
            <h2><?=$data['title'] ?></h2>
            <form id="contact-form" method="POST">
                <div class="error-message hidden"></div>
                <div class="success-message hidden"></div>
                <div class="alert" id="form-alert" style="display:none; transition: opacity 1s;"></div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" class="form-control" required></textarea>
                </div>

                <!-- Honeypot -->
                <div class="user-cannot-see">
                    <label for="nickname" aria-hidden="true"> Nickname </label>
                    <input type="text" name="nickname" id="nickname" tabindex="-1" autocomplete="off">
                </div>
                <button type="submit" id="submit" name="submit" class="submit-btn">Send Message</button>
            </form>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<script src="/public/js/contact.js"></script>
</body>
</html>