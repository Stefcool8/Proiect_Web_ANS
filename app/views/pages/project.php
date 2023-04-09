<?php require_once '../app/views/shared/navbar.php'; ?>
<main class="project-info">
    <h2>Project Name: <span id="project-name">Project 1</span></h2>
    <p>Date Created: <span id="date-created">2023-04-09</span></p>
    <p>Last Modified: <span id="last-modified">2023-04-20</span></p>
    <p>Description: <span id="project-description">Project description.</span></p>
    <p>Author: <span id="project-author">N. Martinescu</span></p>
    <p>Contributors: <span id="project-contributors">A. Cobaschi, S. Nastasiu</span></p>
    <p>Chart Type: <span id="chart-type">Bar Chart</span></p>
    <div class="buttons">
        <button class="btn" id="download-csv">Download CSV</button>
        <button class="btn" id="download-png">Download PNG</button>
        <button class="btn" id="download-jpeg">Download JPEG</button>
        <button class="btn" id="download-svg">Download SVG</button>
        <button class="btn">Share Link</button>
        <button class="btn">Add Collaborators</button>
    </div>
</main>
<div id="chart-container">
    <div id="chart"></div>
</div>
<script src="/assets/js/project.js"></script>
<?php require_once '../app/views/shared/footer.php'; ?>
