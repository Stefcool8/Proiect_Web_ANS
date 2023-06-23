<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <!-- css libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- favicon -->
    <link rel="icon" href="/public/assets/img/favicon.png">

    <link rel="stylesheet" href="/public/css/shared/navbar.css">
    <link rel="stylesheet" href="/public/css/shared/footer.css">
    <link rel="stylesheet" href="/public/css/home.css">
    <link rel="stylesheet" href="/public/css/index.css">

    <!-- javascript libraries -->
    <script src="https://www.youtube.com/iframe_api"></script>
</head>
<body>
<?php require_once __DIR__ . '/shared/navbar.php'; ?>
<main class="main-content">
    <section class="intro-section">
        <div class="intro-content">
            <h2 class="intro-heading">Default title</h2>
        </div>
        <div class="cta-button-group">
            <a href="/dashboard" class="cta-btn"><span>Use it now</span></a>
            <a href="https://github.com/Stefcool8/Proiect_Web_ANS" target="_blank"
               class="cta-btn"><span>Github</span></a>
        </div>
        <div class="demo-video-section">
            <div class="video-wrapper"></div>
            <div id="video-player"></div>
        </div>
    </section>
    <section class="feature-section">
        <h2>Our Features</h2>
        <article class="feature">
            <h3>Data visualization</h3>
            <p>Our data visualization tool provides an intuitive and interactive platform for exploring complex
                datasets. With a wide range of chart types, customizable options, you can easily uncover hidden
                patterns, trends, and relationships in your data.</p>
        </article>
        <article class="feature">
            <h3>Data manipulation</h3>
            <p>Effortlessly manipulate your data with our built-in tools for filtering, sorting, and aggregation. By
                streamlining the data preparation process, you can focus on what truly matters: gaining valuable
                insights and making data-driven decisions.</p>
        </article>
        <article class="feature">
            <h3>Data sharing</h3>
            <p>Collaboration is key to effective data analysis. Our platform makes it simple to share your
                visualizations with team members, stakeholders, or the public. Export your work in various formats
                with just a few clicks.</p>
        </article>
    </section>
    <section class="about-section">
        <h2>About Us</h2>
        <p>We are a dedicated team committed to transforming the way you visualize and interact with your data. We
            believe in the power of data and aim to create intuitive tools to make data analysis accessible and
            enjoyable for everyone.</p>
    </section>
    <section class="testimonial-section">
        <h2>Testimonials</h2>
        <div class="testimonial">
            <p>"This is the best data visualization tool I've ever used. It's user-friendly and makes data analysis a
                breeze."</p>
            <h3>- John Doe, Data Analyst</h3>
        </div>
        <div class="testimonial">
            <p>"I love how easy it is to manipulate and share my data. This tool has become an essential part of my
                workflow."</p>
            <h3>- Jane Smith, Researcher</h3>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/shared/footer.php'; ?>
<script src="/public/js/home.js"></script>
</body>
</html>
