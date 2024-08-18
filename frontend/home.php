<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1;
        }
       
        .hero-section img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="images/logoipsum-325.svg" alt="Logo" style="max-height: 50px;
            max-width:150px">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="add_stock.php">Add Stock</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_stock.php">View Stock</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="make_bill.php">Make Bill</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="setting.php"><i class="fas fa-cog"></i> Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    
    <!-- Page Content -->
    <div class="container mt-4 content">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p>You have successfully logged in. Explore the features available to you through the navigation menu.</p>
        
        <!-- Dummy Content Section -->
        <div class="dummy-content mt-4">
            <h3>Latest Updates</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque ut imperdiet velit. Integer vehicula ex at felis facilisis, eget elementum velit vulputate. Nulla facilisi. Curabitur interdum urna ut risus cursus, eget viverra metus congue.</p>
            <p>Curabitur convallis, odio sit amet congue feugiat, erat odio blandit ante, in auctor lacus elit id nisl. Integer et metus id mi malesuada dapibus. Duis id nisl id metus fermentum consectetur at ut metus. Praesent sit amet orci at turpis facilisis feugiat.</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
