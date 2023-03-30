<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/navbar.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/css/contact.css">
    <script src="./assets/js/navbar.js" defer></script>
    <script src="./assets/js/footer.js" defer></script>
    <title>Contact Us</title>
</head>
<body>

    <div class="parent">
        <div class="div1"> 
            <?php require_once '../app/views/shared/navbar.php'; ?>
        </div>

        <div class="main-content">
            <div class="container">
                <h2>Contact Us</h2>
                <form>
                    <div class="input-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                                        <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="input-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    <div class="input-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit">Send Message</button>
                </form>
            </div>
        </div>

        <div class="div3"> 
            <?php require_once '../app/views/shared/footer.php'; ?>
        </div>
    </div>

</body>
</html>

