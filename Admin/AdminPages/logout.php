<?php
// Start session
session_start();

// Destroy session data
session_destroy();

// Redirect to login page
header("Location: ../../Pages/UserPages/loginpage.php");
exit;
?>
