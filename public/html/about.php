<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>

    <!-- css libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- favicon -->
    <link rel="icon" href="/public/assets/img/favicon.png">

    <link rel="stylesheet" href="/public/css/index.css">
    <link rel="stylesheet" href="/public/css/shared/navbar.css">
    <link rel="stylesheet" href="/public/css/shared/footer.css">
    <link rel="stylesheet" href="/public/css/about.css">
</head>
<body>
<?php require_once __DIR__ . '/shared/navbar.php'; ?>
<div class="about" style="color: white;">
    <div class="main-content">
        <div class="show">
            <div class="my-element">
                <p> </p>
            </div>
        </div>
        <div class="central-area">
            <p>Information about us</p>
            <p>The creation of this application was a collective work of the following students from group A1:
                Martinescu Nicolae, Nastasiu Stefan si Cobaschi Emanuel-Aser. <span id="dots"></span><span id="more">The team was created as a result of a very good friendship and collaboration during the studying years.We managed to build up this software product with the aim of creating an interactive, easy-to-use application that allows an attractive visualization of some data selections based on certain criteria, in the automotive field.
            So, the user can create certain new viewing instances, from the desired categories, based on selected criteria.Also,he has the possibility to export these visualizations in certain familiar formats.
            </span></p>
            <button id="myBtn">Read more</button>
        </div>
        <div class="three-zones">
            <div class="left-zone">
                <p>Nicolae's contribution</p>
                <p>Nicolae managed to do the following tasks:</p><span id="dots1"></span>
                <span id="more1">
                        Create and managing a Jira project for displaying the tasks on a Kanban board <br>
                        Make research on UI/UX design tools, especially Figma <br>
                        Help in creating Pain diagrams with the main pages of our Application <br>
                        Create HTML, CSS, JavaScript code for NavBar and Footer <br>
                        Create HTML, CSS, JavaScript code for Home page  <br>
                        Create HTML, CSS code for Contact page  <br>
                        Create HTML, CSS code for Login page  <br>
                        Create HTML, CSS code for Register page  <br>
                        Help in creating class diagrams for the database  <br>
                        Help in creating SQL scripts to build the database and insert data into it  <br>
                        Participate in weekly meetings to manage the project and distribute the tasks
                    </span>
                <button id="myBtn1">Read more</button>
            </div>
            <div class="middle-zone">
                <p>Stefan's contribution</p>
                <p>Stefan managed to do the following tasks:</p><span id="dots2"></span>
                <span id="more2">
                        Participate in creating the ER diagram for our database  <br>
                        Help in creating class diagrams for the database  <br>
                        Write PHP scripts to handle requests from the client-side  <br>
                        Write SQL queries to retrieve data from the database  <br>
                        Help in creating HTML, CSS, JavaScript code for multiple pages of our application  <br>
                        Implement the backend logic for the Login and Register functionality  <br>
                        Participate in weekly meetings to manage the project and distribute the tasks
                    </span>
                <button id="myBtn2">Read more</button>
            </div>
            <div class="right-zone">
                <p>Emanuel's contribution</p>
                <p>Emanuel managed to do the following tasks:</p><span id="dots3"></span>
                <span id="more3">
                        Design the ER diagram for our database  <br>
                        Write SQL scripts to create the database and insert data into it  <br>
                        Write PHP scripts to handle requests from the client-side  <br>
                        Create HTML, CSS, JavaScript code for the Contact page  <br>
                        Implement the backend logic for the Contact functionality  <br>
                        Participate in weekly meetings to manage the project and distribute the tasks
                    </span>
                <button id="myBtn3">Read more</button>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<script src="/public/js/about.js"></script>
</body>
</html>
