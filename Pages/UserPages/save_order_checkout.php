<?php
session_start();
include '../../php/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the POST request
    $token = $_POST['token'];
    $amount = $_POST['amount'];
    $khalti_id = $_POST['khalti_id'];
    $user_name = $_POST['user_name'];
    $phone_number = $_POST['phone_number'];
    $shipping_address = $_POST['shipping_address'];
    $city = $_POST['city'];
    $zip_code = $_POST['zip_code'];
    $email_address = $_POST['email_address'];
    $status = "pending";

    // Check if products array is provided
    if (isset($_POST['products'])) {
        // Retrieve product details array and decode JSON string
        $products = json_decode($_POST['products'], true);

        // Insert each product into the database
        foreach ($products as $product) {
            $product_id = $product['id'];
            $product_name = $product['name'];
            $quantity = $product['quantity'];
            $product_amount = $product['amount']; // added product amount

            // Insert into the orders table using prepared statement
            $stmt = $con->prepare("INSERT INTO orders (total_amount, product_id, email, product_name, khalti_id, user_name, phone_number, quantity, shipping_address, city, zip_code, email_address, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssssss", $amount, $product_id, $email_address, $product_name, $khalti_id, $user_name, $phone_number, $quantity, $shipping_address, $city, $zip_code, $email_address, $status);

            if ($stmt->execute()) {
                // Order saved successfully
                echo "Order for $product_name saved successfully.<br>";
            } else {
                // Error occurred while saving the order
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    } else {
        // Products array is empty
        echo "Error: Products array is empty.";
    }

} else {
    // If the request method is not POST, return an error
    echo "Error: Method not allowed.";
}
?>
