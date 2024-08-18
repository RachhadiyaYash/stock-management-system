<?php
include '../server/db_connection.php';

$productId = $_POST['product_id'];

// Prepare and execute the query to fetch product details
$stmt = $conn->prepare("SELECT * FROM total_stock WHERE product_id = ?");
$stmt->bind_param("s", $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($product = $result->fetch_assoc()) {
    $brandId = $product['brand_id'];
    $productTypeId = $product['product_type_id'];

    // Fetch brand name
    $brandStmt = $conn->prepare("SELECT name FROM brandname WHERE id = ?");
    $brandStmt->bind_param("i", $brandId);
    $brandStmt->execute();
    $brandResult = $brandStmt->get_result();
    $brandName = $brandResult->fetch_assoc()['name'];

    // Fetch product type
    $typeStmt = $conn->prepare("SELECT type_name FROM product_type WHERE id = ?");
    $typeStmt->bind_param("i", $productTypeId);
    $typeStmt->execute();
    $typeResult = $typeStmt->get_result();
    $productType = $typeResult->fetch_assoc()['type_name'];

    // Fetch model name
    $modelStmt = $conn->prepare("SELECT model_number FROM models WHERE id = ?");
    $modelStmt->bind_param("i", $product['model_name']);
    $modelStmt->execute();
    $modelResult = $modelStmt->get_result();
    $modelName = $modelResult->fetch_assoc()['model_number'];

    // Send response
    $response = array(
        'brand_name' => $brandName,
        'product_type' => $productType,
        'model_name' => $modelName,
        'selling_price' => $product['selling_price']
    );

    echo json_encode($response);
} else {
    echo json_encode(array('error' => 'Product not found'));
}
?>
