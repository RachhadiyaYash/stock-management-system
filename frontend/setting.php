<?php
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include '../server/db_connection.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add Brand
    if (isset($_POST['add_brand'])) {
        $brandName = $_POST['brand_name'];
        $stmt = $conn->prepare("INSERT INTO brandname (name) VALUES (?)");
        $stmt->bind_param("s", $brandName);
        $stmt->execute();
    }
    // Delete Brand
    if (isset($_POST['delete_brand'])) {
        $brandId = $_POST['brand_id'];
        $stmt = $conn->prepare("DELETE FROM brandname WHERE id = ?");
        $stmt->bind_param("i", $brandId);
        $stmt->execute();
    }
    // Add Type
    if (isset($_POST['add_type'])) {
        $typeName = $_POST['type_name'];
        $stmt = $conn->prepare("INSERT INTO product_type (type_name) VALUES (?)");
        $stmt->bind_param("s", $typeName);
        $stmt->execute();
    }
    // Delete Type
    if (isset($_POST['delete_type'])) {
        $typeId = $_POST['type_id'];
        $stmt = $conn->prepare("DELETE FROM product_type WHERE id = ?");
        $stmt->bind_param("i", $typeId);
        $stmt->execute();
    }
    // Add Model Name
    if (isset($_POST['add_model'])) {
        $modelName = $_POST['model_name'];
        $stmt = $conn->prepare("INSERT INTO models (model_number) VALUES (?)");
        $stmt->bind_param("s", $modelName);
        $stmt->execute();
    }
    // Delete Model Name
    if (isset($_POST['delete_model'])) {
        $modelId = $_POST['model_id'];
        $stmt = $conn->prepare("DELETE FROM models WHERE id = ?");
        $stmt->bind_param("i", $modelId);
        $stmt->execute();
    }
}

// Fetch current options from the database
$brands = $conn->query("SELECT * FROM brandname");
$types = $conn->query("SELECT * FROM product_type");
$models = $conn->query("SELECT * FROM models");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1, h3 {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .list-group-item form {
            margin: 0;
        }
        .btn-section {
            margin-bottom: 20px;
        }
        .section-content {
            display: none;
        }
        .disclaimer {
            border: 1px solid #dc3545;
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
        <img src="images/logoipsum-325.svg" >
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

    <!-- Settings Form -->
    <div class="container">
        <h1>Settings</h1>

        <!-- Disclaimer Message -->
        <div class="disclaimer">
            <strong>Disclaimer:</strong> Please note that deleting a brand, product type, or model name will also remove all associated stock records from the database.
        </div>

        <!-- Button Row -->
        <div class="btn-section ">
            <a href="#"  class="px-3" onclick="showSection('brands')">Manage Brands</a>
            <a href="#" class="px-3" onclick="showSection('types')">Manage Product Types</a>
            <a href="#" class="px-3" onclick="showSection('models')">Manage Models</a>
        </div>

        <!-- Manage Brands Section -->
        <div id="brands" class="section-content">
            <h3>Manage Brands</h3>
            <form method="POST" action="setting.php">
                <div class="form-group">
                    <label for="brand_name">Add New Brand:</label>
                    <input type="text" class="form-control" id="brand_name" name="brand_name" required>
                </div>
                <button type="submit" class="btn btn-primary" name="add_brand">Add Brand</button>
            </form>
            <ul class="list-group mt-3">
                <?php while ($brand = $brands->fetch_assoc()) { ?>
                    <li class="list-group-item">
                        <?php echo htmlspecialchars($brand['name']); ?>
                        <form method="POST" action="setting.php" class="m-0">
                            <input type="hidden" name="brand_id" value="<?php echo $brand['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm" name="delete_brand">Delete</button>
                        </form>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <!-- Manage Product Types Section -->
        <div id="types" class="section-content">
            <h3>Manage Product Types</h3>
            <form method="POST" action="setting.php">
                <div class="form-group">
                    <label for="type_name">Add New Product Type:</label>
                    <input type="text" class="form-control" id="type_name" name="type_name" required>
                </div>
                <button type="submit" class="btn btn-primary" name="add_type">Add Type</button>
            </form>
            <ul class="list-group mt-3">
                <?php while ($type = $types->fetch_assoc()) { ?>
                    <li class="list-group-item">
                        <?php echo htmlspecialchars($type['type_name']); ?>
                        <form method="POST" action="setting.php" class="m-0">
                            <input type="hidden" name="type_id" value="<?php echo $type['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm" name="delete_type">Delete</button>
                        </form>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <!-- Manage Models Section -->
        <div id="models" class="section-content">
            <h3>Manage Models</h3>
            <form method="POST" action="setting.php">
                <div class="form-group">
                    <label for="model_name">Add New Model Name:</label>
                    <input type="text" class="form-control" id="model_name" name="model_name" required>
                </div>
                <button type="submit" class="btn btn-primary" name="add_model">Add Model</button>
            </form>
            <ul class="list-group mt-3">
                <?php while ($model = $models->fetch_assoc()) { ?>
                    <li class="list-group-item">
                        <?php echo htmlspecialchars($model['model_number']); ?>
                        <form method="POST" action="setting.php" class="m-0">
                            <input type="hidden" name="model_id" value="<?php echo $model['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm" name="delete_model">Delete</button>
                        </form>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.section-content').forEach(function(section) {
                section.style.display = 'none';
            });
            document.getElementById(sectionId).style.display = 'block';
        }

        // Default to showing the brands section
        document.addEventListener('DOMContentLoaded', function() {
            showSection('brands');
        });
    </script>
</body>
</html> 