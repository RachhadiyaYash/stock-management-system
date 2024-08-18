<?php
session_start();

// Clear session variables and destroy the session
session_unset();
session_destroy();

// Redirect to the login page
header("Location: index.php");  // Adjust the URL if your login page has a different name or path
exit();
?>
