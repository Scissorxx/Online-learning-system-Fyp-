

<?php

session_start();
include '../../php/dbconnect.php';

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['message'];
// $user_id =$_SESSION['SN'];

// Sanitize input (prevent SQL injection)
$name = mysqli_real_escape_string($con, $name);
$email = mysqli_real_escape_string($con, $email);
$phone = mysqli_real_escape_string($con, $phone);
$message = mysqli_real_escape_string($con, $message);

// Insert data into database
$sql = "INSERT INTO message (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message')";

if ($con->query($sql) === TRUE) {
    echo "Message sent successfully!";
    header("Location: Dashboard.php");

    
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

$con->close();
?>


