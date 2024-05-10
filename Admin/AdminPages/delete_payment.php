<?php
// Include your database connection file
include '../../php/dbconnect.php';

if(isset($_GET['payment_id']) && !empty($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];
    
    // Perform delete operation
    $delete_query = "DELETE FROM payments WHERE id = $payment_id";
    $delete_result = mysqli_query($con, $delete_query);

    if($delete_result) {
        // Redirect back to the payment page after successful deletion
        header("Location: admin_payment.php");
        exit;
    } else {
        echo "Error deleting payment: " . mysqli_error($con);
    }
} else {
    // Redirect back to the payment page if payment_id is not set
    header("Location: payment.php");
    exit;
}
?>
