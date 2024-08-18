<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Stock Management System</title>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        #container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
          
        }

        footer {
            text-align: center;
            padding: 1rem;
            background-color: #f8f9fa;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div id="container" >
        <div class="w-100 border p-5" style="max-width: 400px;">
            <h2 class="mb-4">Login</h2>
            <form action="../server/login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
                <br>
                <p >
    Interested in exploring this system?Just visit <a href="https://rachhadiyayash.github.io/Portfolio/" target="_blank">my portfolio</a> to get the username and password.
</p>

            </form>
        </div>
    </div>
    <footer>
    Made with 💕 by <a href="https://rachhadiyayash.github.io/Portfolio/" target="_blank">Yash Rachhadiya</a>
</footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
