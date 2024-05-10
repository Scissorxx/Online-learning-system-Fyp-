<?php
// Include your database connection file
include '../../php/dbconnect.php';

// Check if the lesson ID is provided in the URL
if(isset($_GET['lesson_id'])) {
    // Sanitize the input to prevent SQL injection
    $lesson_id = mysqli_real_escape_string($con, $_GET['lesson_id']);

    // Construct the SQL query to select the video path before deleting the lesson
    $sql_select_video = "SELECT lesson_video FROM lesson WHERE lesson_ID = '$lesson_id'";
    $result = mysqli_query($con, $sql_select_video);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $video_path = $row['lesson_video'];

        // Construct the SQL query to delete the lesson
        $sql = "DELETE FROM lesson WHERE lesson_ID = '$lesson_id'";

        // Execute the delete query
        if(mysqli_query($con, $sql)) {
            // Lesson deleted successfully, now delete associated video file if exists
            if ($video_path && file_exists($video_path)) {
                unlink($video_path);
            }

            // Redirect to teacherCoursedetails.php after successful deletion
            header("Location: teacherCoursedetails.php?course_id=".$_GET['course_id']);
            exit(); // Ensure that no other code is executed after sending the response
        } else {
            // Error occurred while deleting the lesson
            // Send an error response with the error message
            echo "Error: " . mysqli_error($con);
            exit(); // Ensure that no other code is executed after sending the response
        }
    } else {
        // No lesson found with the provided ID
        echo "Error: Lesson not found.";
        exit(); // Ensure that no other code is executed after sending the response
    }
} else {
    // No lesson ID provided, send a bad request response
    echo "No lesson ID provided.";
    exit(); // Ensure that no other code is executed after sending the response
}

// Close the database connection
mysqli_close($con);
?>
