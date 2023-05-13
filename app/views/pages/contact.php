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
