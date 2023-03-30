<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/navbar.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/css/login.css">
    <script src="./assets/js/navbar.js" defer></script>
    <script src="./assets/js/footer.js" defer></script>
    <title>Home Page</title>
</head>
<body>

    <div class="parent">
        <div class="div1"> 
            <?php require_once '../app/views/shared/navbar.php'; ?>
        </div>

        <div class="main-content">
            <div class="container">
                <h2>Login Form</h2>
                <form>
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
                </div>
            </div>
        </div>

        <div class="div3"> 
            <?php require_once '../app/views/shared/footer.php'; ?>
        </div>
    </div>
 


</body>
</html>