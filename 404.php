<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(185deg, #1c1e2d, #262a3e, #3c3e4f, #4e6e92);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            text-align: center;
            padding: 20px;
        }

        .error-code {
            font-size: 5rem;
            color: white;
        }

        .error-text {
            font-size: 1.5rem;
            color: white;
            margin-top: 10px;
        }

        .home-btn {
            margin-top: 30px;
            color: #fff;
            background-color: #333;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">404</div>
        <div class="error-text">Page Not Found</div>
        <a href="/" class="home-btn">Go to Home</a>
    </div>

    <script>
        document.querySelector(".home-btn").addEventListener("click", (event) => {
            event.preventDefault();
            window.location.replace("/");
        });
    </script>
</body>
</html>
