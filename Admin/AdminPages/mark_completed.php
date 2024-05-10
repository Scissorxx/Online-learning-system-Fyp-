<?php
// Include your database connection file
include '../../php/dbconnect.php';

if(isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Update the order status to 'Completed' in the database
    $query = "UPDATE Orders SET status = 'Completed' WHERE id = '$order_id'";
    $result = mysqli_query($con, $query);

    if($result) {
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
