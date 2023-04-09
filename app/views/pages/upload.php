<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/navbar.css">
    <link rel="stylesheet" href="./assets/css/footer.css">
    <link rel="stylesheet" href="./assets/css/upload.css">
    <script src="./assets/js/navbar.js" defer></script>
    <script src="./assets/js/footer.js" defer></script>
    <title>Upload Files</title>
</head>
<body>

    <div class="upload">
        <div class="header">
            <?php require_once '../app/views/shared/navbar.php'; ?>
        </div>
        
        <div class="main-content">

            <div class="upload-options">
                <button class="button">Paste</button>
                <button class="button">Upload a File</button>
                <button class="button">From URL</button>
                <button class="button">Try our samples</button>
            </div>

            <div class="specialized-form">
                <iframe src="profile.php" title="Specialized Form"></iframe>
            </div>

        </div>
        
        <div class="footer">
            <?php require_once '../app/views/shared/footer.php'; ?>
        </div>
    </div>

</body>
</html>
