<?php require_once __DIR__ . '/../shared/navbar.php'; ?>
        <main class="home">
            <div class="intro-section">
                <div class="intro-text">
                    <h2 class="intro-title">Open source tool for data visualization</h2>
                </div>
                <div class="cta-buttons">
                    <a href="<?= BASE_URL; ?>/login" class="cta-button"><span>Use it now</span></a>
                    <a href="https://github.com/Stefcool8/Proiect_Web_ANS" target="_blank" class="cta-button"><span>Github</span></a>
                </div>
                <div class="demo-video">
                    <div class="video-container">
                        <div id="player"></div>
                    </div>
                </div>
            </div>
            <div class="features">
                <article class="feature-item">
                    <h3>Data visualization</h3>
                    <p>Our data visualization tool provides an intuitive and interactive platform for exploring complex datasets. With a wide range of chart types, customizable options, you can easily uncover hidden patterns, trends, and relationships in your data.</p>
                </article>
                <article class="feature-item">
                    <h3>Data manipulation</h3>
                    <p>Effortlessly manipulate your data with our built-in tools for filtering, sorting, and aggregation. By streamlining the data preparation process, you can focus on what truly matters: gaining valuable insights and making data-driven decisions.</p>
                </article>
                <article class="feature-item">
                    <h3>Data sharing</h3>
                    <p>Collaboration is key to effective data analysis. Our platform makes it simple to share your visualizations with team members, stakeholders, or the public. Export your work in various formats, or generate shareable links for seamless access to your insights.</p>
                </article>
            </div>
            <section class="image-slider">
                <h2 class="hidden">Image slider</h2>
                <figure>
                    <img src="<?= BASE_URL; ?>/public/assets/img/img1.jpg" class="slide-image" alt="first-image">
                </figure>
                <figure>
                    <img src="<?= BASE_URL; ?>/public/assets/img/photo.png" class="slide-image" alt="second-image">
                </figure>
                <figure>
                    <img src="<?= BASE_URL; ?>/public/assets/img/img1.jpg" class="slide-image" alt="third-image">
                </figure>
                <figure>
                    <img src="<?= BASE_URL; ?>/public/assets/img/photo.png" class="slide-image" alt="fourth-image">
                </figure>
                <figure>
                    <img src="<?= BASE_URL; ?>/public/assets/img/img1.jpg" class="slide-image" alt="fifth-image">
                </figure>
                <figure>
                    <img src="<?= BASE_URL; ?>/public/assets/img/photo.png" class="slide-image" alt="sixth-image">
                </figure>            
            </section>
        </main>
<?php require_once __DIR__ . '/../shared/footer.php'; ?>

        <script src="<?= BASE_URL; ?>/public/assets/js/slider.js"></script>
        <script src="<?= BASE_URL; ?>/public/assets/js/youtube.js"></script>