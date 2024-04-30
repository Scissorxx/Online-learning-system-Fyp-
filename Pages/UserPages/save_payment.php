<?php
include '../../php/dbconnect.php';

// Check if user is logged in
session_start();
include '../../php/dbconnect.php';

$loggedIn = isset($_SESSION['valid']);

// Fetch user details from the database
$userdetail = [];
if ($loggedIn) {
    $email = $_SESSION['valid'];
    $sql = "SELECT * FROM userdetail WHERE email = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $userdetail = mysqli_fetch_assoc($result);
    $userID = $userdetail['SN'];
    $userName = $userdetail['username'];
} 


// Get the payment details from the POST request
$token = $_POST['token'];
$amounts = $_POST['amount'];
$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$all =$_POST['playload'];
$email =$_POST['email'];
$amount =$amounts/100;


// You may also want to get the user ID if you have a logged-in user system

// Prepare SQL statement to insert the payment details into the database
$sql = "INSERT INTO payments (user_id, token, amount, product_id, product_name,Allfeild,email) VALUES (?, ?, ?, ?, ?,?,?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("issssss", $userID, $token, $amount, $product_id, $product_name,$all,$email);


// Execute the statement
if ($stmt->execute()) {
    
} else {
    // Error inserting payment details
    echo "Error: " . $sql . "<br>" . $con->error;
}

// Close the database connection
$con->close();
?>
