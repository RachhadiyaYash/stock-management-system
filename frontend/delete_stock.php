<?php
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include '../server/db_connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Prepare and execute the delete statement
    $sql = "DELETE FROM total_stock WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: view_stock.php");
    } else {
        echo "Error deleting record.";
    }
}
?>
