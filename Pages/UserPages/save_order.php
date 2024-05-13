<?php
session_start();
include '../../php/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the POST request
    $token = $_POST['token'];
    $amount = $_POST['amount']/100;
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
    $user_id = $_SESSION['SN'];

    // Save the order details into the database or perform other necessary actions
    // For example:
    // Insert into the orders table
    $timestamp = date('Y-m-d H:i:s'); // Current timestamp

    $sql = "INSERT INTO orders (total_amount, product_id, email, product_name, khalti_id, user_name, phone_number, quantity, shipping_address, city, zip_code, email_address,status,user_id,date_order) VALUES ('$amount', '$product_id', '$email', '$product_name', '$khalti_id', '$user_name', '$phone_number', '$quantity', '$shipping_address', '$city', '$zip_code', '$email_address','$status','$user_id','$timestamp')";

    if (mysqli_query($con, $sql)) {
        // Order saved successfully
        echo "Order saved successfully.";

        // Add notification with timestamp
        $notification_message = "Your order for $product_name has been placed.";

        $timestamp = date('Y-m-d H:i:s'); // Current timestamp
        $insert_notification_sql = "INSERT INTO notification (user_id, user_name, product_id, product_name, message, timestamp,is_read) VALUES ('$user_id', '$user_name', '$product_id', '$product_name', '$notification_message', '$timestamp',0)";
        try {
            //Server settings
            $mail = require __DIR__ . "/../../mailer.php";
            
            //Recipients
            $mail->setFrom('noreply@example.com', 'Your Name');
            $mail->addAddress($email); // User's email
            $mail->isHTML(true);
            $mail->Subject = 'Order Confirmation';
            $mail->Body = '
            <html>
            <head>
                <style>
                    body {
                        font-family: "Segoe UI", Roboto, Arial, sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        max-width: 600px;
                        margin: auto;
                        padding: 20px;
                        background-color: #fff;
                        border-radius: 8px;
                        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                    }
                    .header {
                        background-color: #007bff;
                        color: #fff;
                        padding: 20px;
                        border-top-left-radius: 8px;
                        border-top-right-radius: 8px;
                        text-align: center;
                    }
                    h2 {
                        margin-top: 0;
                    }
                    .content {
                        padding: 20px;
                        color: #555;
                    }
                    .footer {
                        background-color: #f4f4f4;
                        padding: 10px 20px;
                        border-bottom-left-radius: 8px;
                        border-bottom-right-radius: 8px;
                    }
                    p {
                        margin: 10px 0;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h2>Order Confirmation</h2>
                    </div>
                    <div class="content">
                        <p>Dear '.$user_name.',</p>
                        <p>Thank you for your order!</p>
                        <p>You have successfully placed an order for '.$quantity.' '.$product_name.'(s).</p>
                        <p>Product Price: NPR '.$amount.'</p>
                        <p>Your order will be shipped soon to the following address:</p>
                        <p>'.$shipping_address.', '.$city.', ZIP: '.$zip_code.'</p>
                        <p>We will notify you once your order is shipped.</p>
                       
                    </div>
                    <div class="footer">
                        <p>Best regards,<br>Hamro Online Learning</p>
                    </div>
                </div>
            </body>
            </html>';
        
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        if (mysqli_query($con, $insert_notification_sql)) {
            // Notification added successfully
            echo "Notification added successfully.";
        } else {
            // Error occurred while adding notification
            echo "Error adding notification: " . mysqli_error($con);
        }

    } else {
        // Error occurred while saving the order
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }

} else {
    // If the request method is not POST, return an error
    echo "Error: Method not allowed.";
}
?>
