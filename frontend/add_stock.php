<?php
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include '../server/db_connection.php';

// Fetch brand names from the database
$brands = [];
$brandResult = $conn->query("SELECT id, name FROM brandname");
if ($brandResult->num_rows > 0) {
    while ($row = $brandResult->fetch_assoc()) {
        $brands[] = $row;
    }
}

// Fetch product types from the database
$productTypes = [];
$typeResult = $conn->query("SELECT id, type_name FROM product_type");
if ($typeResult->num_rows > 0) {
    while ($row = $typeResult->fetch_assoc()) {
        $productTypes[] = $row;
    }
}

// Fetch model names from the database
$models = [];
$modelResult = $conn->query("SELECT id, model_number AS model_name FROM models");
if ($modelResult->num_rows > 0) {
    while ($row = $modelResult->fetch_assoc()) {
        $models[] = $row;
    }
}

// Determine alert type based on status query parameter
$alertType = '';
$alertMessage = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'success') {
        $alertType = 'success';
        $alertMessage = 'Stock added successfully!';
    } elseif ($_GET['status'] == 'error') {
        $alertType = 'danger';
        $alertMessage = 'Failed to add stock. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Stock</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .invalid-feedback {
            display: block;
        }
        .form-row {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="path_to_logo.png" alt="Logo"> <!-- Replace with your logo path -->
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
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
    
    <!-- Add Stock Form -->
    <div class="container mt-4">
        <h1>Add Stock</h1>
        
        <?php if ($alertType): ?>
            <div class="alert alert-<?php echo $alertType; ?> alert-dismissible fade show" role="alert">
                <?php echo $alertMessage; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <form id="addStockForm" action="add_stock_process.php" method="POST">
            <div class="form-row">
                <div class="col-md-6 form-group">
                    <label for="brand">Brand:</label>
                    <select class="form-control" id="brand" name="brand" required>
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?php echo htmlspecialchars($brand['id']); ?>"><?php echo htmlspecialchars($brand['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="type">Type:</label>
                    <select class="form-control" id="type" name="type" required>
                        <?php foreach ($productTypes as $type): ?>
                            <option value="<?php echo htmlspecialchars($type['id']); ?>"><?php echo htmlspecialchars($type['type_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
             <div class="col-md-12 form-group">
            <label for="model_name">Model Name:</label>
             <select class="form-control" id="model_name" name="model_name" required>
                <?php foreach ($models as $model): ?>
                <option value="<?php echo htmlspecialchars($model['id']); ?>"><?php echo htmlspecialchars($model['model_name']); ?></option>
            <?php endforeach; ?>
            </select>
             </div>
            </div>

            <div class="form-row">
                <div class="col-md-6 form-group">
                    <label for="price">Price:</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="sellingprice">Selling Price:</label>
                    <input type="number" class="form-control" id="sellingprice" name="sellingprice" required>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="product_id">Product ID:</label>
                    <input type="text" class="form-control" id="product_id" name="product_id" required>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 form-group">
                    <label for="specifications">Specifications:</label>
                    <textarea class="form-control" id="specifications" name="specifications" rows="3"></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Add Stock</button>
        </form>
    </div>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Initialize Select2 -->
    <script>
        $(document).ready(function() {
            $('#brand').select2({
                placeholder: 'Select a Brand',
                allowClear: true
            });
            
            $('#type').select2({
                placeholder: 'Select a Type',
                allowClear: true
            });
            
            $('#model_name').select2({
                placeholder: 'Select a Model Name',
                allowClear: true
            });
        });
    </script>
</body>
</html>
