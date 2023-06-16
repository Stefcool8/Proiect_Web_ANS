<?php
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
// Utilizează valoarea $currentPage cum ai nevoie
echo "Current Page: " . $currentPage;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- css libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- favicon -->
    <link rel="icon" href="/public/assets/img/favicon.png">

    <link rel="stylesheet" href="/public/css/index.css">
    <link rel="stylesheet" href="/public/css/shared/navbar.css">
    <link rel="stylesheet" href="/public/css/shared/footer.css">
    <link rel="stylesheet" href="/public/css/dashboard.css">
</head>

<body>
<?php require_once __DIR__ . '/shared/navbar.php'; ?>
<main class="central-area">
    <div class="page-name">
        <p>Dashboard, Hello</p>
    </div>
    <div class="profile-project-button-area">
        <a href="/user" class="view-profile-btn">View Profile</a>
        <a href="/project-initialization" class="create-project-btn">Create new project</a>
    </div>

    <div class="project-area">

    </div>
    <div class="button-area-next-previous">
        <a href="/dashboard?page=<?php echo urlencode($currentPage - 1); ?>" class="button button-previous">Previous
            Page</a>
        <a href="/dashboard?page=<?php echo urlencode($currentPage + 1); ?>" class="button button-next">Next Page</a>
    </div>

</main>

<?php require_once __DIR__ . '/shared/footer.php'; ?>

<script src="/public/js/dashboard.js"></script>

</body>

</html>
