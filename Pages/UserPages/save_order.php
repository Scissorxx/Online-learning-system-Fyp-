<?php
session_start();
include '../../php/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the POST request
    $token = $_POST['token'];
    $amount = $_POST['amount'];
    $product_id = $_POST['product_id'];
    $payload = $_POST['payload'];
    $email = $_POST['email'];
    $product_name = $_POST['product_name'];
    $khalti_id = $_POST['khalti_id'];
    $user_name = $_POST['user_name'];
    $phone_number = $_POST['phone_number'];
    $quantity = $_POST['quantity']; // Retrieve the quantity parameter
    $shipping_address = $_POST['shipping_address'];
    $city = $_POST['city'];
    $zip_code = $_POST['zip_code'];
    $email_address = $_POST['email_address'];
    $status ="pending";

    // Save the order details into the database or perform other necessary actions
    // For example:
    // Insert into the orders table
    $sql = "INSERT INTO orders (total_amount, product_id, email, product_name, khalti_id, user_name, phone_number, quantity, shipping_address, city, zip_code, email_address,status) VALUES ('$amount', '$product_id', '$email', '$product_name', '$khalti_id', '$user_name', '$phone_number', '$quantity', '$shipping_address', '$city', '$zip_code', '$email_address','$status')";
   
    if (mysqli_query($con, $sql)) {
        // Order saved successfully
        echo "Order saved successfully.";
    } else {
        // Error occurred while saving the order
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }

} else {
    // If the request method is not POST, return an error
    echo "Error: Method not allowed.";
}
?>
