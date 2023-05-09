<?php require_once __DIR__ . '/../shared/navbar.php'; ?>
<div class="contact-page">
    <main class="main-content">
        <div class="contact-form-container">
            <h2>Contact Us</h2>
            <form method="POST" id="contact-form">
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
    
                <button type="submit" id="submit" name="submit" class="submit-btn">Send Message</button>
            </form>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/../shared/footer.php'; ?>

<script>
    document.getElementById('contact-form').addEventListener('submit', async (event) => {
        event.preventDefault();

        const formAlert = document.getElementById('form-alert');
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const subject = document.getElementById('subject').value;
        const message = document.getElementById('message').value;

        try {
            const response = await fetch('/contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept': 'application/json',
                },
                body: new URLSearchParams({ name, email, subject, message })
            });
            const jsonResponse = await response.json();
            formAlert.style.display = 'block';
            formAlert.style.opacity = '1';

            if (response.status === 200) {
                formAlert.className = 'alert alert-success';
            } else {
                formAlert.className = 'alert alert-danger';
            }

            formAlert.textContent = jsonResponse.message;

            // Fade out after 3 seconds
            setTimeout(() => {
                formAlert.style.opacity = '0';
                // Hide the element after it's faded out
                setTimeout(() => {
                    formAlert.style.display = 'none';
                }, 1000); // Matches the transition duration
            }, 3000);
        } catch (error) {
            formAlert.style.display = 'block';
            formAlert.style.opacity = '1';
            formAlert.className = 'alert alert-danger';
            formAlert.textContent = 'An error occurred. Please try again later.';
        }
    });
</script>
