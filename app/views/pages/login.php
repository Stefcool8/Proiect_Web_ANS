<?php
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }

  if (isset($_SESSION['username'])) {
      header('Location: /dashboard');
  }
?>

<div class="login">
    <?php require_once __DIR__ . '/../shared/navbar.php'; ?>
    <main class="main-content">
        <div class="container">
            <h2>Login Form</h2>
            <form action="/auth/login" method="post">
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
                <p>Don't have an account? <a href="/auth/register">Sign up</a></p>
                <p>Forgot your password? <a href="/reset">Reset password</a></p>
            </div>
        </div>
    </main>
    <?php require_once  __DIR__ . '/../shared/footer.php'; ?>
</div>
<script>

const loginForm = document.querySelector('form');

loginForm.addEventListener('submit', async (event) => {
  event.preventDefault();

  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;

const data = {
  username: username,
  password: password
};

try {
  const response = await fetch('/auth/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'Accept': 'application/json'
    },
    body: new URLSearchParams(data)
  });

  const result = await response.json();
  console.log(result)
  if (response.ok) {
    // The login was successful
    localStorage.setItem('jwt', result.token);
    // Redirect to the dashboard
    window.location.href = "/dashboard";
  } else {
    // Handle the error
    console.error(result.message);
  }

} catch (error) {
  // Handle any errors
  console.error(error);
}
});
</script>