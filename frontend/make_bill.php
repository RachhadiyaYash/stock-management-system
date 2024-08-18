<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include '../server/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customerName = $_POST['customer_name'];
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $billDate = date('Y-m-d H:i:s');

    // Fetch product details
    $stmt = $conn->prepare("SELECT * FROM total_stock WHERE product_id = ?");
    $stmt->bind_param("s", $productId);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();

    if ($product) {
        $brandName = $product['brand_id'];
        $productType = $product['product_type_id'];
        $sellingPrice = $product['selling_price'];
        $modelName = $product['model_name'];
        $totalAmount = $sellingPrice * $quantity;

        // Insert into the customer table
        $stmt = $conn->prepare("INSERT INTO customer (customer_name, bill_date, brand_id, product_type_id, selling_price, quantity, product_id, total_bill_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiiidsd", $customerName, $billDate, $brandName, $productType, $sellingPrice, $quantity, $productId, $totalAmount);
        $stmt->execute();

        // Update the stock quantity in total_stock table
        $updateStmt = $conn->prepare("UPDATE total_stock SET quantity = quantity - ? WHERE product_id = ?");
        $updateStmt->bind_param("is", $quantity, $productId);
        $updateStmt->execute();

        header("Location: view_stock.php?success=1"); // Redirect to view_stock.php with success message
        exit();
    } else {
        echo "Product not found";
    }
}

// Fetch brand, product type, and model details for dropdowns
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Bill</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
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

    <!-- Main Content -->
    <div class="container mt-5">
        <h1>Make Bill</h1>
        <form method="POST" action="bill.php">
            <div class="form-group">
                <label for="customer_name">Customer Name</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
            </div>
            <div class="form-group">
                <label for="product_id">Product ID</label>
                <input type="text" class="form-control" id="product_id" name="product_id" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <div class="form-group">
                <label for="brand_name">Brand Name</label>
                <input type="text" class="form-control" id="brand_name" readonly>
            </div>
            <div class="form-group">
                <label for="product_type">Product Type</label>
                <input type="text" class="form-control" id="product_type" readonly>
            </div>
            <div class="form-group">
                <label for="model_name">Model Name</label>
                <input type="text" class="form-control" id="model_name" readonly>
            </div>
            <div class="form-group">
                <label for="selling_price">Selling Price</label>
                <input type="number" class="form-control" id="selling_price" readonly>
            </div>
            <div class="form-group">
                <label for="total_amount">Total Amount</label>
                <input type="number" class="form-control" id="total_amount" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Make Bill</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#product_id').on('change', function() {
                var productId = $(this).val();
                if (productId) {
                    $.ajax({
                        type: 'POST',
                        url: 'fetch_product_details.php',
                        data: {product_id: productId},
                        dataType: 'json',
                        success: function(response) {
                            if (response.error) {
                                alert(response.error);
                            } else {
                                $('#brand_name').val(response.brand_name);
                                $('#product_type').val(response.product_type);
                                $('#model_name').val(response.model_name);
                                $('#selling_price').val(response.selling_price);
                                calculateTotal();
                            }
                        }
                    });
                }
            });

            $('#quantity').on('input', function() {
                calculateTotal();
            });

            function calculateTotal() {
                var sellingPrice = parseFloat($('#selling_price').val()) || 0;
                var quantity = parseInt($('#quantity').val()) || 0;
                var totalAmount = sellingPrice * quantity;
                $('#total_amount').val(totalAmount);
            }
        });
    </script>
</body>
</html>
