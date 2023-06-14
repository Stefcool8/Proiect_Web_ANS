<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

    <!-- css libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- favicon -->
    <link rel="icon" href="/public/assets/img/favicon.png">

    <link rel="stylesheet" href="/public/css/index.css">
    <link rel="stylesheet" href="/public/css/shared/navbar.css">
    <link rel="stylesheet" href="/public/css/shared/footer.css">
    <link rel="stylesheet" href="/public/css/dashboard.css">
</head>

<body>
    <?php require_once __DIR__ . '/shared/navbar.php'; ?>
        <main class = "central-area">
        <div class="page-name">
            <p>Dashboard, Hello</p>
        </div>
        <div class="profile-project-button-area">
            <a href="/user" class="view-profile-btn">View Profile</a>
            <a href="/upload" class="create-project-btn">Create new project</a>
        </div>
                
        <div class="project-area">
                    
            <div class = "project project-1">
                <p class = "project-name">Project Name 1</p>
                <div class ="button-area">
                    <a href="/project/1" class="button">View</a>
                    <a href="/project/1/edit" class="button">Modify</a>
                    <a href="/project/1/delete" class="button">Delete</a>
                </div>
            </div>

            <div class = "project project-2">
                <p class = "project-name">Project Name 2</p>
                <div class ="button-area">
                    <a href="/project/2" class="button">View</a>
                    <a href="/project/2/edit" class="button">Modify</a>
                    <a href="/project/2/delete" class="button">Delete</a>
                </div>
            </div>
                
            <div class = "project project-3">
                <p class = "project-name">Project Name 3</p>
                <div class ="button-area">
                    <a href="/project/3" class="button">View</a>
                    <a href="/project/3/edit" class="button">Modify</a>
                    <a href="/project/3/delete" class="button">Delete</a>
                </div>
            </div>

            <div class = "project project-4">
                <p class = "project-name">Project Name 4</p>
                <div class ="button-area">
                    <a href="/project/4" class="button">View</a>
                    <a href="/project/4/edit" class="button">Modify</a>
                    <a href="/project/4/delete" class="button">Delete</a>
                </div>
            </div>
                    
        </div>
            

    </main>

    <?php require_once __DIR__ . '/shared/footer.php'; ?>

    <script src="/public/js/dashboard.js"></script>
    <script>
        let user = JSON.parse(localStorage.getItem("user"));
        //console.log(user);
        //console.log(user.uuid);
        const userLink = document.querySelector(".view-profile-btn");
        userLink.href = "/user/" + user.uuid;
    </script>
</body>

</html>
