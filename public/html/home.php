<?php 
    require_once __DIR__ . '/shared/general.php';
    $data = fetch_data('home', [
        'title' => 'Default title'
    ]);

?>
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

    <link rel="stylesheet" href="/public/css/index.css">
    <link rel="stylesheet" href="/public/css/home.css">
    <link rel="stylesheet" href="/public/css/shared/navbar.css">
    <link rel="stylesheet" href="/public/css/shared/footer.css">

    <!-- javascript libraries -->
    <script src="https://www.youtube.com/iframe_api"></script>
</head>

<body>
    <?php require_once __DIR__ . '/shared/navbar.php'; ?>
    <main class="home">
        <div class="intro-section">
            <div class="intro-text">
                <h2 class="intro-title"><?= htmlspecialchars($data["title"]) ?></h2> <!-- Open source tool for data visualization -->
            </div>
            <div class="cta-buttons">
                <a href="/dashboard" class="cta-button"><span>Use it now</span></a>
                <a href="https://github.com/Stefcool8/Proiect_Web_ANS" target="_blank"
                    class="cta-button"><span>Github</span></a>
            </div>
            <div class="demo-video">
                <div class="video-container"></div>
                <div id="player"></div>
            </div>
        </div>
        <div class="features">
            <article class="feature-item">
                <h3>Data visualization</h3>
                <p>Our data visualization tool provides an intuitive and interactive platform for exploring complex
                    datasets. With a wide range of chart types, customizable options, you can easily uncover hidden
                    patterns, trends, and relationships in your data.</p>
            </article>
            <article class="feature-item">
                <h3>Data manipulation</h3>
                <p>Effortlessly manipulate your data with our built-in tools for filtering, sorting, and aggregation. By
                    streamlining the data preparation process, you can focus on what truly matters: gaining valuable
                    insights and making data-driven decisions.</p>
            </article>
            <article class="feature-item">
                <h3>Data sharing</h3>
                <p>Collaboration is key to effective data analysis. Our platform makes it simple to share your
                    visualizations with team members, stakeholders, or the public. Export your work in various formats,
                    or
                    generate shareable links for seamless access to your insights.</p>
            </article>
        </div>
        <section class="image-slider">
            <h2 class="hidden">Image slider</h2>

        </section>
    </main>
    <?php require_once __DIR__ . '/shared/footer.php'; ?>

    <script src="/public/js/home.js"></script>
</body>

</html>