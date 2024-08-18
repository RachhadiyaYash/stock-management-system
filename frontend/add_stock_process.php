<?php
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include '../server/db_connection.php';

// Get form data
$brand = $_POST['brand'];
$type = $_POST['type'];
$price = $_POST['price'];
$sellingprice = $_POST['sellingprice'];
$quantity = $_POST['quantity'];
$product_id = $_POST['product_id'];
$model_name = $_POST['model_name'];
$specifications = $_POST['specifications'];

// Prepare and execute SQL query to insert data
$stmt = $conn->prepare("INSERT INTO total_stock (brand_id, product_type_id, price, selling_price, quantity, product_id, model_name, specifications) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iidddsss", $brand, $type, $price, $sellingprice, $quantity, $product_id, $model_name, $specifications);

if ($stmt->execute()) {
    header("Location: add_stock.php?status=success");
} else {
    header("Location: add_stock.php?status=error");
}

$stmt->close();
$conn->close();
?>
