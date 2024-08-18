<?php
session_start();

// Include the database connection file
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM owner WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $inputUsername, $inputPassword);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $inputUsername;
        header("Location: ../frontend/home.php");
    } else {
        echo '<div style="text-align: center; margin-top: 50px;">';
        echo '<h3>Invalid username or password.</h3>';
        echo '<a href="../frontend/index.php" class="btn btn-primary">Return to Login</a>';
        echo '</div>';
    }

    $stmt->close();
}
$conn->close();
?>
