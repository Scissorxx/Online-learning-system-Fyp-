<?php
session_start(); // Start the session

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect the user to the login page or any other page as desired
header("Location: Loginpage.php");
exit; // Ensure script execution stops here
?>
