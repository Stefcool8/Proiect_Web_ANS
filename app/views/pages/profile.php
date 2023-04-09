<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/navbar.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/css/profile.css">
    <script src="./assets/js/navbar.js" defer></script>
    <script src="./assets/js/footer.js" defer></script>
    <title>Home Page</title>
</head>
<body>

    <div class="parent">
        <div class="header">
            <?php require_once '../app/views/shared/navbar.php'; ?>
        </div>
        
        <div class="main-content">

            <div class="user-visual">
                <div class="user-banner">
                    <img src="./assets/img/banner.jpg" alt="User Banner">
                </div>
                <div class="user-picture-and-controls">
                    <div class="user-picture">
                        
                    </div>
                    <div class="user-name-and-email">
                        <h1> Cool Cat </h1>
                        <h5> cat@cat.com </h5>
                    </div>
                    <div class="controls">
                        <button class="button">Cancel</button>
                        <button class="button">Save</button>
                    </div>
                </div>
            </div>

            <div class="user-details">
                <form>
                    <div class="input-group">
                        <label for="firstname">First Name</label>
                        <input type="text" name="firstname" id="firstname" placeholder="First Name" required>
                    </div>
                    <div class="input-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" name="lastname" id="lastname" placeholder="Last Name" required>
                    </div>
                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" placeholder="Username" required>
                    </div>
                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Email" required>
                    </div>

                    <?php require_once '../app/views/shared/countries.php'; ?>

                    <div class="input-group">
                        <label for="bio">Bio</label>
                        <textarea name="bio" id="bio" cols="30" rows="10" placeholder="Let us know more about you"></textarea>
                    </div>
                </form>
            </div>

        </div>
        
        <div class="footer">
            <?php require_once '../app/views/shared/footer.php'; ?>
        </div>
    </div>

</body>
</html>
