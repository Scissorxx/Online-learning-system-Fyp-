<?php
// Include your database connection file
include '../../php/dbconnect.php';

// Check if order_id is set and not empty
if(isset($_GET['order_id']) && !empty($_GET['order_id'])) {
    // Sanitize the input to prevent SQL injection
    $order_id = mysqli_real_escape_string($con, $_GET['order_id']);

    // Delete order from the database
    $query = "DELETE FROM Orders WHERE id = '$order_id'";

    if(mysqli_query($con, $query)) {
        // Order deleted successfully
        header("Location: admin_orders.php"); // Redirect back to the admin dashboard
        exit();
    } else {
        // Error deleting order
        echo "Error deleting order: " . mysqli_error($con);
    }
} else {
    // Redirect to admin dashboard if order_id is not set or empty
    header("Location: admin_dashboard.php");
    exit();
}
?>
