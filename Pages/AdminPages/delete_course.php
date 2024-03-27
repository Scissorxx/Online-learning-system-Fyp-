<?php
// Include your database connection file
include '../php/dbconnect.php';

// Check if the course ID is provided in the URL
if(isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $course_id = mysqli_real_escape_string($con, $_GET['id']);

    // Fetch the course details to get the image file path
    $sql_select_image = "SELECT Course_Image FROM courses WHERE Course_ID = '$course_id'";
    $result = mysqli_query($con, $sql_select_image);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $image_path = $row['Course_Image'];

        // If image path exists, delete the associated image file
        if (!empty($image_path) && file_exists($image_path)) {
            unlink($image_path);
        }
    }

    // Construct the SQL query to delete the course
    $sql = "DELETE FROM courses WHERE Course_ID = '$course_id'";

    // Execute the delete query
    if(mysqli_query($con, $sql)) {
        // Course deleted successfully, redirect back to the courses page
        header('Location: admin_Courses.php');
        exit(); // Ensure that no other code is executed after redirection
    } else {
        // Error occurred while deleting the course
        echo "Error: " . mysqli_error($con);
    }
} else {
    // No course ID provided, redirect or show error message
    echo "No course ID provided.";
}

// Close the database connection
mysqli_close($con);
?>
