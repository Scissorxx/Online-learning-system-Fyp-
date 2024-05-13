<?php

// Include your database connection file and continue with your existing code
include '../../php/dbconnect.php';

// Check if the user ID is provided in the URL
if(isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $user_id = mysqli_real_escape_string($con, $_GET['id']);

    // Construct the SQL query to select the image path before deleting the user
    $sql_select_image = "SELECT Image FROM userdetail WHERE SN = '$user_id'";
    $result = mysqli_query($con, $sql_select_image);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $image_path = $row['Image'];

        // Construct the SQL query to delete notifications associated with the user
        $sql_delete_notifications = "DELETE FROM notification WHERE user_id = '$user_id'";

        // Execute the delete query for notifications
        if(mysqli_query($con, $sql_delete_notifications)) {
            // Construct the SQL query to delete enrollments associated with the user
            $sql_delete_enrollments = "DELETE FROM enrollment WHERE user_id = '$user_id'";

            // Execute the delete query for enrollments
            if(mysqli_query($con, $sql_delete_enrollments)) {
                // Construct the SQL query to delete user progress associated with the user
                $sql_delete_user_progress = "DELETE FROM user_progress WHERE user_id = '$user_id'";

                // Execute the delete query for user progress
                if(mysqli_query($con, $sql_delete_user_progress)) {
                    // Construct the SQL query to delete orders associated with the user
                    $sql_delete_orders = "DELETE FROM orders WHERE user_id = '$user_id'";

                    // Execute the delete query for orders
                    if(mysqli_query($con, $sql_delete_orders)) {
                        // Construct the SQL query to delete the user
                        $sql_delete_user = "DELETE FROM userdetail WHERE SN = '$user_id'";

                        // Execute the delete query for the user
                        if(mysqli_query($con, $sql_delete_user)) {
                            // User deleted successfully, now delete associated image file if exists
                            if ($image_path && file_exists($image_path)) {
                                unlink($image_path);
                            }

                            header('Location: Admin_Student.php');
                            exit();
                        } else {
                            // Error occurred while deleting the user
                            echo "Error: " . mysqli_error($con);
                        }
                    } else {
                        // Error occurred while deleting orders
                        echo "Error: " . mysqli_error($con);
                    }
                } else {
                    // Error occurred while deleting user progress
                    echo "Error: " . mysqli_error($con);
                }
            } else {
                // Error occurred while deleting enrollments
                echo "Error: " . mysqli_error($con);
            }
        } else {
            // Error occurred while deleting notifications
            echo "Error: " . mysqli_error($con);
        }
    } else {
        // Error occurred while fetching image path
        echo "Error: Unable to fetch image path.";
    }
} else {
    // No user ID provided, redirect or show error message
    echo "No user ID provided.";
}

// Close the database connection
mysqli_close($con);
?>
