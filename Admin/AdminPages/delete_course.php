<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection file
include '../../php/dbconnect.php';

// Check if the course ID is provided in the URL
if(isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $course_id = mysqli_real_escape_string($con, $_GET['id']);

    // Delete all enrollments for the course
    $sql_delete_enrollments = "DELETE FROM enrollment WHERE course_id = '$course_id'";
    if (!mysqli_query($con, $sql_delete_enrollments)) {
        echo "Error deleting enrollments: " . mysqli_error($con);
    }

    // Delete user progress for the course
    $sql_delete_user_progress = "DELETE FROM user_progress WHERE course_id = '$course_id'";
    if (!mysqli_query($con, $sql_delete_user_progress)) {
        echo "Error deleting user progress: " . mysqli_error($con);
    }

    // Construct the SQL query to select the image path before deleting the course
    $sql_select_image = "SELECT Course_Image FROM courses WHERE Course_ID = '$course_id'";
    $result = mysqli_query($con, $sql_select_image);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $image_path = $row['Course_Image'];

        // Construct the SQL query to select lessons and their videos associated with the course
        $sql_select_lessons = "SELECT lesson_ID, lesson_video FROM lesson WHERE Course_ID = '$course_id'";
        $lessons_result = mysqli_query($con, $sql_select_lessons);

        // Delete associated lessons and their videos
        if ($lessons_result) {
            while ($lesson_row = mysqli_fetch_assoc($lessons_result)) {
                $lesson_id = $lesson_row['lesson_ID'];
                $lesson_video = $lesson_row['lesson_video'];

                // Delete lesson
                $sql_delete_lesson = "DELETE FROM lesson WHERE lesson_ID = '$lesson_id'";
                if (!mysqli_query($con, $sql_delete_lesson)) {
                    echo "Error deleting lesson: " . mysqli_error($con);
                }

                // Delete lesson video if exists
                if ($lesson_video && file_exists($lesson_video)) {
                    if (!unlink($lesson_video)) {
                        echo "Error deleting lesson video: $lesson_video";
                    }
                }
            }
        }

        // Construct the SQL query to delete the course
        $sql_delete_course = "DELETE FROM courses WHERE Course_ID = '$course_id'";

        // Execute the delete query
        if(mysqli_query($con, $sql_delete_course)) {
            // Course deleted successfully, now delete associated image file if exists
            if ($image_path && file_exists($image_path)) {
                if (!unlink($image_path)) {
                    echo "Error deleting image: $image_path";
                }
            }

            header('Location: admin_Courses.php');
            exit();
        } else {
            // Error occurred while deleting the course
            echo "Error deleting course: " . mysqli_error($con);
        }
    } else {
        // Error occurred while fetching image path
        echo "Error fetching image path: " . mysqli_error($con);
    }
} else {
    // No course ID provided, redirect or show error message
    echo "No course ID provided.";
}

// Close the database connection
mysqli_close($con);
?>
