<?php
require_once '../../vendor/autoload.php';

use Dompdf\Dompdf;

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if payment_id is set in the URL
if(isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];
    
    // Replace this with your database connection and query
    include '../../php/dbconnect.php';

    // Prepare SQL query
    $sql = "SELECT p.*, u.email, u.fullname FROM payments p 
            INNER JOIN userdetail u ON p.user_id = u.SN 
            WHERE p.id = $payment_id";

    // Execute query
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Create a new instance of Dompdf
        $dompdf = new Dompdf();

        // Set document information
        $dompdf->set_option('defaultFont', 'Arial');
        $dompdf->setPaper('A4', 'portrait');

        // Load HTML content
        $html = '
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
                .container {
                    width: 80%;
                    margin: 0 auto;
                    padding: 20px;
                }
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .receipt-info {
                    margin-bottom: 20px;
                }
                .receipt-info p {
                    margin: 5px 0;
                }
                .footer {
                    text-align: center;
                    margin-top: 50px;
                }
            </style>
            <div class="container">
                <div class="header">
                    <h1>Payment Receipt</h1>
                </div>
                <div class="receipt-info">
                    <p><strong>Payment ID:</strong> '.$row['id'].'</p>
                    <p><strong>Token:</strong> '.$row['token'].'</p>
                    <p><strong>Payment Date:</strong> '.$row['payment_date'].'</p>
                    <p><strong>User ID:</strong> '.$row['user_id'].'</p>
                    <p><strong>Username:</strong> '.$row['fullname'].'</p>
                    <p><strong>Email:</strong> '.$row['email'].'</p>
                    <p><strong>Amount:</strong> '.$row['Amount'].'</p>
                    <p><strong>Product ID:</strong> '.$row['product_id'].'</p>
                    <p><strong>Product Name:</strong> '.$row['product_name'].'</p>
                  
                </div>
                <div class="footer">
                    <p>Thank you for your payment!</p>
                </div>
            </div>
        ';

        // Render the HTML as PDF
        $dompdf->loadHtml($html);
        $dompdf->render();
        
        // Output the generated PDF (inline or attachment)
        $output = $dompdf->output();
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="receipt.pdf"');
        header('Content-Length: ' . strlen($output));
        echo $output;
        exit;
    } else {
        echo "No records found!";
    }

    $con->close();
} else {
    // If payment_id is not set, redirect to some page
    header("Location: index.php");
    exit;
}
?>
