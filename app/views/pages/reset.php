<div class="password-reset">
    <?php require_once '../app/views/shared/navbar.php'; ?>
    <main class="main-content">
        <div class="container">
            <h2>Password Reset Form</h2>
            <form>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit">Reset Password</button>
            </form>
            <div class="signup-link">
                <p>Remember your password? <a href="/login">Log in</a></p>
            </div>
        </div>
    </main>
    <?php require_once '../app/views/shared/footer.php'; ?>
</div>