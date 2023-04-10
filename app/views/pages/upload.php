
<div class="upload">
    <div class="header">
        <?php require_once '../app/views/shared/navbar.php'; ?>
    </div>
        
    <div class="main-content">

        <div class="upload-options">
            <button id="paste" class="special">Paste</button>
            <button id="drag-drop">Drop a File</button>
            <button id="url">From URL</button>
            <button id="try">Try our samples</button>
        </div>

        <div class="specialized-form">
            <textarea id="paste-form" placeholder="Paste your code here: "></textarea>
            <div id="drag-drop-form">
                <p> Upload your file here: </p>
            </div>
            <div id="url-form">
                <input type="text" name="url-link" id="url-link">
                <button id="url-submit">Load data</button>
                <p> Enter a web address (URL) pointing to the data you want to load. </p>
            </div>
            <div id="try-form">
                <p> Try our samples here: </p>
            </div>
        </div>

    </div>
        
    <div class="footer">
        <?php require_once '../app/views/shared/footer.php'; ?>
    </div>
</div>

</body>
</html>
