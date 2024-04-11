<?php
session_start();

// Include database connection
include '../../php/dbconnect.php';

// Check if the request is POST and if 'lesson_id' is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['lesson_id'])) {
    // Sanitize lesson_id to prevent SQL injection
    $lesson_id = mysqli_real_escape_string($con, $_POST['lesson_id']);
    
    // Retrieve user ID from session
    if(isset($_SESSION['SN'])) { // Assuming 'SN' is the correct session variable name
        $user_id = $_SESSION['SN'];
        
        // Check if the lesson exists
        $sql_check_lesson = "SELECT * FROM lesson WHERE lesson_ID = $lesson_id"; // Corrected column name
        $result_check_lesson = mysqli_query($con, $sql_check_lesson);
        
        if ($result_check_lesson) {
            if (mysqli_num_rows($result_check_lesson) > 0) {
                // Check if the user has already completed the lesson
                $sql_check_completion = "SELECT * FROM user_progress WHERE user_id = $user_id AND lesson_id = $lesson_id";
                $result_check_completion = mysqli_query($con, $sql_check_completion);
                
                if ($result_check_completion) {
                    if (mysqli_num_rows($result_check_completion) == 0) {
                        // Insert completion status into the user_progress table
                        $sql_insert_completion = "INSERT INTO user_progress (user_id, lesson_id, status) VALUES ($user_id, $lesson_id, 'completed')";
                        if (mysqli_query($con, $sql_insert_completion)) {
                            echo 'Lesson marked as completed.';
                        } else {
                            echo 'Error inserting completion status: ' . mysqli_error($con);
                        }
                    } else {
                        echo 'Lesson already marked as completed.';
                    }
                } else {
                    echo 'Error checking completion status: ' . mysqli_error($con);
                }
            } else {
                echo 'Lesson not found.';
            }
        } else {
            echo 'Error checking lesson: ' . mysqli_error($con);
        }
    } else {
        echo 'User ID not found in session.';
    }
} else {
    echo 'Invalid request.';
}

// Close database connection
mysqli_close($con);
?>
