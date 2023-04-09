
<div class="login">
    <?php require_once '../app/views/shared/navbar.php'; ?>
    <main class="main-content">
        <div class="container">
            <h2>Login Form</h2>
            <form action="/dashboard" method="post">
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
                <p>Don't have an account? <a href="/signup">Sign up</a></p>
                <p>Forgot your password? <a href="/reset">Reset password</a></p>
            </div>
        </div>
    </main>
    <?php require_once '../app/views/shared/footer.php'; ?>
</div>

