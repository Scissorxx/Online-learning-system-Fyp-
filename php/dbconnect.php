<?php 
$server = "localhost";
$username = "root";
$password = "";
$database = "my_system";

$con = mysqli_connect($server, $username, $password, $database);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Return the connection object
return $con;
?>
