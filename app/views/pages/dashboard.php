<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/navbar.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/css/dashboard.css">
    <script src="./assets/js/navbar.js" defer></script>
    <script src="./assets/js/footer.js" defer></script>
    <title>About us</title>
</head>
<body>


        <div class="div1"> 
            <?php require_once '../app/views/shared/navbar.php'; ?>
        </div>

        <div class = "central-area">
            <div class = "my-element">
                <p> List of project </p>
            </div>
            
            <div class = "project-area">
                
                <div class = "project-1">
                    <p>Project Name</p>
                    <div class ="button-area">
                        <button class="button"> View  </button>
                        <button class="button"> Modify  </button>
                        <button class="button"> Delete  </button>

                    </div>
                </div>
                <div class = "project-1">
                    <p>Project Name</p>
                    <div class ="button-area">
                        <button class="button"> View  </button>
                        <button class="button"> Modify  </button>
                        <button class="button"> Delete  </button>
                    </div>
                </div>
                <div class = "project-1">
                    <p>Project Name</p>
                    <div class ="button-area">
                        <button class="button"> View  </button>
                        <button class="button"> Modify  </button>
                        <button class="button"> Delete  </button>

                    </div>
                </div>
                <div class = "project-1">
                    <p>Project Name</p>
                    <div class ="button-area">
                        <button class="button"> View  </button>
                        <button class="button"> Modify  </button>
                        <button class="button"> Delete  </button>

                    </div>
                </div>
                
            </div>
           

        </div>

        <div class="div3"> 
            <?php require_once '../app/views/shared/footer.php'; ?>
        </div>
   

</body>
</html>

