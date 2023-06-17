<?php
    // /{uuid}
    // $query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
    // parse_str($query, $params);
    // $token = $params['token'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Project</title>

    <!-- css libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- favicon -->
    <link rel="icon" href="/public/assets/img/favicon.png">

    <link rel="stylesheet" href="/public/css/index.css">
    <link rel="stylesheet" href="/public/css/shared/navbar.css">
    <link rel="stylesheet" href="/public/css/shared/footer.css">
    <link rel="stylesheet" href="/public/css/project-initialization.css">
</head>

<body>
<?php require_once __DIR__ . '/shared/navbar.php'; ?>

<div class="project-initialization">
    <main class="main-content">
        <div class="container">
            <h2>Create New Project</h2>
            <form id="project-initialization-form">
                <div class="error-message hidden"></div>
                <div class="success-message hidden"></div>
                <div class="input-group">
                    <label for="project-name">Project Name:</label>
                    <input type="text" id="project-name" name="project-name" required>
                </div>
                <div class="input-group">
                    <label for="chart-type">Chart Type:</label>
                    <select id="chart-type" name="chart-type" required>
                        <option value="">Select Chart Type</option>
                        <option value="barChart">Bar Chart</option>
                        <option value="lineChart">Line Chart</option>
                        <option value="pieChart">Pie Chart</option>
                    </select>
                </div>
                <!--<div class="input-group">
                    <label for="years">Years:</label>
                    <div class="year-checkbox-container" id="year-checkboxes"></div>
                </div>-->
                <button type="submit">Create Project</button>
            </form>
        </div>
    </main>
</div>

<?php require_once  __DIR__ . '/shared/footer.php'; ?>
<script src="/public/js/project-initialization.js"></script>
</body>

</html>
