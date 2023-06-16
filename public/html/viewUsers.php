<?php 
    require_once __DIR__ . '/shared/general.php';
    $result = fetch_data('user', [
        'data' => []
    ]);
    //var_dump($result);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>

    <!-- css libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- favicon -->
    <link rel="icon" href="/public/assets/img/favicon.png">

    <link rel="stylesheet" href="/public/css/index.css">
    <link rel="stylesheet" href="/public/css/shared/navbar.css">
    <link rel="stylesheet" href="/public/css/shared/footer.css">
    <link rel="stylesheet" href="/public/css/viewUsers.css">
</head>

<body>
    <?php require_once __DIR__ . '/shared/navbar.php'; ?>
        <main class = "central-area">
        <div class="page-name">
            <p>
                <?php
                        $countUsers = count($result['data']);
                        if($countUsers == 1){
                            echo "There is only " . count($result['data']). " user registered.";
                        }else{
                            echo "There are " . count($result['data']). " users registered.";
                        }

                ?>
            </p>
        </div>

        <div class="project-area">
        <div class="user-list">
            <?php foreach ($result['data'] as $user) : ?>

                <div class="project project-1" data-user="<?php echo htmlentities(json_encode($user)); ?>">
                    <p><?php echo "Username: ".$user['username']; ?> <?php echo "|     UUID:".$user['uuid'];?></p>
                    <div class ="button-area">
                    <a  class="button">View Profile</a>
                    <a  class="button">Modify Profile</a>
                    <a  class="button">Delete User</a>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>

        </div>

    </main>

    <?php require_once __DIR__ . '/shared/footer.php'; ?>
    <script src="/public/js/viewUsers.js"></script>

</body>

</html>