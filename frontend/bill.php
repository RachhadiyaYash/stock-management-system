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
        $brandId = $product['brand_id'];
        $productTypeId = $product['product_type_id'];
        $sellingPrice = $product['selling_price'];
        $totalAmount = $sellingPrice * $quantity;

        // Insert into the customer table
       
        // Update the stock quantity in total_stock table
        $updateStmt = $conn->prepare("UPDATE total_stock SET quantity = quantity - ? WHERE product_id = ?");
        if (!$updateStmt) {
            die("Prepare failed: " . $conn->error);
        }
        $updateStmt->bind_param("is", $quantity, $productId);
        if (!$updateStmt->execute()) {
            die("Execute failed: " . $updateStmt->error);
        }

        header("Location: view_stock.php?success=1"); // Redirect to view_stock.php with success message
        exit();
    } else {
        echo "Product not found";
    }
}
?>
