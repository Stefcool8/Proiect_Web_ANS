<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/navbar.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/css/about.css">
    <script src="./assets/js/navbar.js" defer></script>
    <script src="./assets/js/footer.js" defer></script>
    <script src="./assets/js/about.js" defer></script>
    <title>About us</title>
</head>
<body>


        <div class="div1"> 
            <?php require_once '../app/views/shared/navbar.php'; ?>
        </div>

        <div class="main-content">
            <div class="show">
                <!-- <img src="../assets/img/about.jpg" class="image"> -->
                <div class= "my-element">
                    <p> About us </p>      
                </div>
            </div>
  
            <div class="central-area">
                <p>Information about us</p>
                <p>The creation of this application was a collective work of the following students from group A1: Martinescu Nicolae, Nastasiu Stefan si Cobaschi Emanuel-Aser. <span id="dots">...</span><span id="more">The team was created as a result of a very good friendship and collaboration during the studying years.We managed to build up this software product with the aim of creating an interactive, easy-to-use application that allows an attractive visualization of some data selections based on certain criteria, in the automotive field. 
                    So, the user can create certain new viewing instances, from the desired categories, based on selected criteria.Also,he has the possibility to export these visualizations in certain familiar formats.
                </span></p>
                <button  onclick="myFunction()" id="myBtn">Read more</button>

            </div>
  
            <div class="three-zones">
                <div class="left-zone">
                    <p>Nicu's contribution</p>
                    <p ><span id ="dots1">...</span> <span id="more1"> egestas vitae scelerisque enim ligula venenatis dolor. Maecenas nisl est, ultrices nec congue eget, auctor vitae massa. Fusce luctus vestibulum augue ut aliquet. Nunc sagittis dictum nisi, sed ullamcorper ipsum dignissim ac. In at libero sed nunc venenatis imperdiet sed ornare turpis. Donec vitae dui eget tellus gravida venenatis. Integer fringilla congue eros non fermentum. Sed dapibus pulvinar nibh tempor porta.</span></p>
                    <button onclick ="myFunction1()" id ="myBtn1"> Read more</button>
                </div>
                <div class="middle-zone">
                    <p> Stefan's contribution</p>
                    <p >Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus imperdiet, nulla et dictum interdum, nisi lorem<span id ="dots2">...</span> <span id="more2"> egestas vitae scelerisque enim ligula venenatis dolor. Maecenas nisl est, ultrices nec congue eget, auctor vitae massa. Fusce luctus vestibulum augue ut aliquet. Nunc sagittis dictum nisi, sed ullamcorper ipsum dignissim ac. In at libero sed nunc venenatis imperdiet sed ornare turpis. Donec vitae dui eget tellus gravida venenatis. Integer fringilla congue eros non fermentum. Sed dapibus pulvinar nibh tempor porta.</span></p>
                    <button onclick ="myFunction2()" id ="myBtn2"> Read more</button>
                </div>
                <div class="right-zone">
                    <p> Aser's contribution</p>
                    <p >Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus imperdiet, nulla et dictum interdum, nisi lorem<span id ="dots3">...</span> <span id="more3"> egestas vitae scelerisque enim ligula venenatis dolor. Maecenas nisl est, ultrices nec congue eget, auctor vitae massa. Fusce luctus vestibulum augue ut aliquet. Nunc sagittis dictum nisi, sed ullamcorper ipsum dignissim ac. In at libero sed nunc venenatis imperdiet sed ornare turpis. Donec vitae dui eget tellus gravida venenatis. Integer fringilla congue eros non fermentum. Sed dapibus pulvinar nibh tempor porta.</span></p>
                    <button onclick ="myFunction3()" id ="myBtn3"> Read more</button>
                </div>
            </div>
        </div>

        <div class="div3"> 
            <?php require_once '../app/views/shared/footer.php'; ?>
        </div>
   

</body>
</html>

