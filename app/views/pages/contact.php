<div class="contact">
    <?php require_once '../app/views/shared/navbar.php'; ?>

    <main class="main-content">
        <div class="container">
            <h2>Contact Us</h2>
            <form method="post" action="/">
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
    </main>
    <?php require_once '../app/views/shared/footer.php'; ?>
</div>
