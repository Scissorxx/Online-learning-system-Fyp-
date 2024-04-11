<?php

// Include your database connection file and continue with your existing code
include '../../php/dbconnect.php';

// Check if the course ID is provided in the URL
if(isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $course_id = mysqli_real_escape_string($con, $_GET['id']);

    // Construct the SQL query to select the image path before deleting the course
    $sql_select_image = "SELECT Image FROM userdetail WHERE SN = '$course_id'";
    $result = mysqli_query($con, $sql_select_image);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $image_path = $row['Image'];

        // Construct the SQL query to delete the course
        $sql = "DELETE FROM userdetail WHERE SN = '$course_id'";

        // Execute the delete query
        if(mysqli_query($con, $sql)) {
            // Course deleted successfully, now delete associated image file if exists
            if ($image_path && file_exists($image_path)) {
                unlink($image_path);
            }

            header('Location: Admin_Student.php');
            exit();
        } else {
            // Error occurred while deleting the course
            echo "Error: " . mysqli_error($con);
        }
    } else {
        // Error occurred while fetching image path
        echo "Error: Unable to fetch image path.";
    }
} else {
    // No course ID provided, redirect or show error message
    echo "No course ID provided.";
}

// Close the database connection
mysqli_close($con);
?>
