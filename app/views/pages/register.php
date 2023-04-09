
<div class="register">
    <?php require_once '../app/views/shared/navbar.php'; ?>
    <main class="main-content">
        <div class="container">
            <h2>Sign Up Form</h2>
            <form action="/login" method="POST">
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
                    <label for="password_confirm">Confirm Password</label>
                    <input type="password" id="password_confirm" name="password_confirm" required>
                </div>
                <button type="submit">Sign Up</button>
            </form>
            <div class="signup-link">
                <p>Already have an account? <a href="/login">Log in</a></p>
            </div>
        </div>
    </main>
    <?php require_once '../app/views/shared/footer.php'; ?>
</div>