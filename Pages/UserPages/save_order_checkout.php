<?php
session_start();
include '../../php/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the POST request
    $token = $_POST['token'];
    $amount = $_POST['amount']/100;
    $khalti_id = $_POST['khalti_id'];
    $user_name = $_POST['user_name'];
    $phone_number = $_POST['phone_number'];
    $shipping_address = $_POST['shipping_address'];
    $city = $_POST['city'];
    $zip_code = $_POST['zip_code'];
    $email_address = $_POST['email_address'];
    $status = "pending";
    $user_id = $_SESSION['SN'];

    // Check if products array is provided
    if (isset($_POST['products'])) {
        // Retrieve product details array and decode JSON string
        $products = json_decode($_POST['products'], true);

        // Insert each product into the database
        foreach ($products as $product) {
            $product_id = $product['id'];
            $product_name = $product['name'];
            $quantity = $product['quantity'];
            $product_amount = $product['amount']; // adjusted product amount
            $timestamp = date('Y-m-d H:i:s'); // Current timestamp

            // Insert into the orders table using prepared statement
            $stmt = $con->prepare("INSERT INTO orders (total_amount, product_id, email, product_name, khalti_id, user_name, phone_number, quantity, shipping_address, city, zip_code, user_id, status, date_order,email_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
            $stmt->bind_param("sssssssssssssss", $amount, $product_id, $email_address, $product_name, $khalti_id, $user_name, $phone_number, $quantity, $shipping_address, $city, $zip_code, $user_id, $status, $timestamp,$email_address);

            $notification_message = "Your order for $product_name has been placed.";

            try {
                //Server settings
                $mail = require __DIR__ . "/../../mailer.php";

                //Recipients
                $mail->setFrom('noreply@example.com', 'Your Name');
                $mail->addAddress($email_address); // User's email
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
                            <p>Product Price: NPR '.$product_amount.'</p>
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

            if ($stmt->execute()) {
                // Order saved successfully
                echo "Order for $product_name saved successfully.<br>";

                // Insert notification
                $stmt_insert_notification = $con->prepare("INSERT INTO notification (user_id, user_name, product_id, product_name, message, timestamp, is_read) VALUES (?, ?, ?, ?, ?, ?, 0)");
                $stmt_insert_notification->bind_param("ssssss", $user_id, $user_name, $product_id, $product_name, $notification_message, $timestamp);

                if ($stmt_insert_notification->execute()) {
                    echo "Notification inserted successfully.<br>";
                } else {
                    echo "Error inserting notification: " . $stmt_insert_notification->error;
                }

                $stmt_insert_notification->close();
            } else {
                // Error occurred while saving the order
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        // Clear the cart after saving the order
        $clear_cart_sql = "DELETE FROM cart WHERE user_id = ?";
        $stmt_clear_cart = $con->prepare($clear_cart_sql);
        $stmt_clear_cart->bind_param("s", $user_id);

        if ($stmt_clear_cart->execute()) {
            echo "Cart cleared successfully.<br>";
        } else {
            echo "Error clearing cart: " . $stmt_clear_cart->error;
        }

        $stmt_clear_cart->close();
    } else {
        // Products array is empty
        echo "Error: Products array is empty.";
    }
} else {
    // If the request method is not POST, return an error
    echo "Error: Method not allowed.";
}
?>
