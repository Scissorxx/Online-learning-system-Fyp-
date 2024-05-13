<?php
// Include your database connection file
include '../../php/dbconnect.php';
// Include PHPMailer

if(isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Update the order status to 'Completed' in the database
    $query = "UPDATE Orders SET status = 'Completed' WHERE id = '$order_id'";
    $result = mysqli_query($con, $query);

    if($result) {
        try {
            
          

            // Get order details
            $order_details_query = "SELECT * FROM Orders WHERE id = '$order_id'";
            $order_details_result = mysqli_query($con, $order_details_query);
            $order_row = mysqli_fetch_assoc($order_details_result);

            $user_name = $order_row['user_name'];
            $email = $order_row['email'];
            $quantity = $order_row['quantity'];
            $product_name = $order_row['product_name'];
            $amount = $order_row['total_amount'];
            $shipping_address = $order_row['shipping_address'];
            $city = $order_row['city'];
            $zip_code = $order_row['zip_code'];
            $user_id = $order_row['user_id'];

            // Notification message
            $notification_message = "Your order for $product_name has been received.";

            // Current timestamp
            $timestamp = date('Y-m-d H:i:s');

            // Insert notification into the notification table
            $insert_notification_sql = "INSERT INTO notification (user_id, user_name, product_id, product_name, message, timestamp, is_read) VALUES ('$user_id', '$user_name', '$order_id', '$product_name', '$notification_message', '$timestamp', 0)";
            mysqli_query($con, $insert_notification_sql);

            // Email body
              // Initialize PHPMailer
              $mail = require_once __DIR__ . "/../../mailer.php";

            
              //Recipients
              $mail->setFrom('noreply@example.com', 'Your Name');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Order Delivered';

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
                        <h2>Order Delivered</h2>
                    </div>
                    <div class="content">
                        <p>Dear '.$user_name.',</p>
                        <p>Your order has been delivered successfully. Thank you!</p>
                        <p>You have received '.$quantity.' '.$product_name.'(s).</p>
                        <p>Total Amount: NPR '.$amount.'</p>
                        <p>Delivery Address: '.$shipping_address.', '.$city.', ZIP: '.$zip_code.'</p>
                        <p>We hope you are satisfied with your purchase. If you have any questions, feel free to contact us.</p>
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
        // Redirect back to the orders page after marking as completed
        header("Location: admin_orders.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }
} else {
    echo "Order ID not provided";
}
?>
