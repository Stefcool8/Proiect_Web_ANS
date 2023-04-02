<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/navbar.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/css/home.css">
    <script src="./assets/js/navbar.js" defer></script>
    <script src="./assets/js/footer.js" defer></script>
    <script src="./assets/js/home.js" defer></script>
    <title>Home Page</title>
</head>
<body>

    <div class="parent">
        <div class="header">
            <?php require_once '../app/views/shared/navbar.php'; ?>
        </div>
        
        <div class="main-content">
            <div class="intro">
                <div class="text-area">
                    <p>Insert your text here</p>
                </div>
                <div class="button-wrapper">
                    <button class="button">Button 1</button>
                    <button class="button">Button 2</button>
                </div>
                <div class="video">
                    <div class="video-wrapper">
                        <iframe src="https://www.youtube.com/embed/tgbNymZ7vqY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
            <div class="three-zones">
                <div class="left-zone">
                    <h3>Feature one</h3>
                    <p>Insert your text here</p>
                </div>
                <div class="middle-zone">
                    <h3>Feature two</h3>
                    <p>Insert your text here</p>
                </div>
                <div class="right-zone">
                    <h3>Feature three</h3>
                    <p>Insert your text here</p>
                </div>
            </div>
            <div class="slideshow">
                <img src="../assets/img/img1.jpg" class="slide">
                <img src="../assets/img/photo.png" class="slide">
                <img src="../assets/img/img1.jpg" class="slide">
                <img src="../assets/img/photo.png" class="slide">
                <img src="../assets/img/img1.jpg" class="slide">
                <img src="../assets/img/photo.png" class="slide">
            </div> 
        </div>
        
        <div class="footer">
            <?php require_once '../app/views/shared/footer.php'; ?>
        </div>
    </div>

</body>
</html>
