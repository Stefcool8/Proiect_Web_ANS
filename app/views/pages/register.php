<?php
?>
<?php require_once __DIR__ . '/../shared/navbar.php'; ?>
<div class="register-wrapper">
    <main class="register-main-content">
        <div class="register-container">
            <div class="register-title">Registration</div>
            <div class="register-content">
                <form method="POST" action="/signup">
                    <div class="register-user-details">
                        <div class="register-input-box">
                            <label for="name" class="register-details">Full Name</label>
                            <input type="text" name="name" id="name" placeholder="Enter your name" required>
                        </div>
                        <div class="register-input-box">
                            <label for="username" class="register-details">Username</label>
                            <input type="text" name="username" id="username" placeholder="Enter your username" required>
                        </div>
                        <div class="register-input-box">
                            <label for="email" class="register-details">Email</label>
                            <input type="email" name="email" id="email" placeholder="Enter your email" required>
                        </div>
                        <div class="register-input-box">
                            <label for="phone" class="register-details">Phone Number</label>
                            <input type="tel" name="phone" id="phone" placeholder="Enter your number" required>
                        </div>
                        <div class="register-input-box">
                            <label for="password" class="register-details">Password</label>
                            <input type="password" name="password" id="password" placeholder="Enter your password" required>
                        </div>
                        <div class="register-input-box">
                            <label for="confirm-password" class="register-details">Confirm Password</label>
                            <input type="password" name="confirmPassword" id="confirm-password" placeholder="Confirm your password" required>
                        </div>
                    </div>
                    <div class="register-gender-details">
                        <input type="radio" name="gender" id="dot-1">
                        <input type="radio" name="gender" id="dot-2">
                        <input type="radio" name="gender" id="dot-3">
                        <span class="register-gender-title">Gender</span>
                        <div class="register-category">
                            <label for="dot-1">
                                <span class="register-dot one"></span>
                                <span class="register-gender">Male</span>
                            </label>
                            <label for="dot-2">
                                <span class="register-dot two"></span>
                                <span class="register-gender">Female</span>
                            </label>
                            <label for="dot-3">
                                <span class="register-dot three"></span>
                                <span class="register-gender">Prefer not to say</span>
                            </label>
                        </div>
                    </div>
                    <div class="register-button">
                        <input type="submit" name="submit" value="Register">
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/../shared/footer.php'; ?>
