<?php 

    $currentUrl = $_SERVER['REQUEST_URI'];

    // Parse the URL to extract the userID
    $parts = explode('/', $currentUrl);
    $userID = end($parts);

    require_once __DIR__ . '/shared/general.php';
    $urlAPI = 'user/'.$userID;
    $result = fetch_data($urlAPI, [
        'data' => []
    ]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/public/css/shared/navbar.css">
    <link rel="stylesheet" href="/public/css/index.css">
    <link rel="stylesheet" href="/public/css/user.css">
    <link rel="stylesheet" href="/public/css/shared/footer.css">
    <link rel="icon" href="/public/assets/img/favicon.png">
    <script src="./public/js/navbar.js" defer></script>
    <script src="./public/js/footer.js" defer></script>
    <title>User Profile</title>
</head>
<body>
    <div class="profile">
        <div class="header">
            <?php require_once __DIR__ . '/shared/navbar.php'; ?>
        </div>

        <div class="main-content">
            <div class="user-visual">
                <div class="user-banner">
                    <img src="/public/assets/img/banner.jpg" alt="User Banner">
                </div>

                <div class="user-picture-and-controls">
                    <div class="user-picture">
                        <!-- User picture content goes here -->
                    </div>

                    <div class="user-name-and-email">
                        <h1> <?php echo $result['data']['username'] ?></h1>
                        <h5> <?php echo $result['data']['email'] ?> </h5>
                    </div>

                    <div class="controls">
                        <button class="modify">Modify Account</button>
                        <button class="delete">Delete Account</button>
                        <button class="change-pass">Change Password</button>
                    </div>
                </div>
            </div>

            <div class="user-details">
                <div class="input-group">
                    <label>Full Name</label>
                    <p> <?php echo $result['data']['name'] ?> </p>
                </div>

                <div class="input-group">
                    <label>Email</label>
                    <p> <?php echo $result['data']['email'] ?> </p>
                </div>

                <div class="input-group">
                    <label>Username</label>
                    <p> <?php echo $result['data']['username'] ?> </p>
                </div>
            </div>
        </div>

        <?php require_once __DIR__ . '/shared/footer.php'; ?>
    </div>

    <script>
        var uuid = localStorage.getItem('uuid');
        console.log(uuid);
    </script>
</body>
</html>
