<?php
session_start();

// Include database connection
include '../../php/dbconnect.php';

// Check if the request is POST and if 'lesson_id' and 'course_id' are set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['lesson_id']) && isset($_POST['course_id']) && isset($_POST['enrollment_id'])) {
    // Sanitize lesson_id, course_id, and enrollment_id to prevent SQL injection
    $lesson_id = mysqli_real_escape_string($con, $_POST['lesson_id']);
    $course_id = mysqli_real_escape_string($con, $_POST['course_id']);
    $enrollment_id = mysqli_real_escape_string($con, $_POST['enrollment_id']);
    
    // Retrieve user ID from session
    if(isset($_SESSION['SN'])) { // Assuming 'SN' is the correct session variable name
        $user_id = $_SESSION['SN'];
        
        // Check if the lesson exists
        $sql_check_lesson = "SELECT * FROM lesson WHERE lesson_ID = $lesson_id AND Course_ID = $course_id"; // Added Course_ID check
        $result_check_lesson = mysqli_query($con, $sql_check_lesson);
        
        if ($result_check_lesson) {
            if (mysqli_num_rows($result_check_lesson) > 0) {
                // Check if the user has already completed the lesson
                $sql_check_completion = "SELECT * FROM user_progress WHERE user_id = $user_id AND lesson_id = $lesson_id AND course_id = $course_id AND enrollment_id = $enrollment_id"; // Added course_id check
                $result_check_completion = mysqli_query($con, $sql_check_completion);
                
                if ($result_check_completion) {
                    if (mysqli_num_rows($result_check_completion) == 0) {
                        // Insert completion status into the user_progress table
                        $sql_insert_completion = "INSERT INTO user_progress (user_id, lesson_id, course_id, enrollment_id, status) VALUES ($user_id, $lesson_id, $course_id, $enrollment_id, 'completed')"; // Added course_id
                        if (mysqli_query($con, $sql_insert_completion)) {
                            // Check if all lessons in the course are completed
                            $sql_check_all_completed = "SELECT COUNT(*) AS total_lessons FROM lesson WHERE Course_ID = $course_id";
                            $result_check_all_completed = mysqli_query($con, $sql_check_all_completed);

                            if ($result_check_all_completed) {
                                $row = mysqli_fetch_assoc($result_check_all_completed);
                                $total_lessons = $row['total_lessons'];

                                $sql_count_completed = "SELECT COUNT(*) AS completed_lessons FROM user_progress WHERE enrollment_id = $enrollment_id AND course_id = $course_id AND status = 'completed'";
                                $result_count_completed = mysqli_query($con, $sql_count_completed);

                                if ($result_count_completed) {
                                    $row_completed = mysqli_fetch_assoc($result_count_completed);
                                    $completed_lessons = $row_completed['completed_lessons'];

                                    if ($completed_lessons == $total_lessons) {
                                        // Update enrollment status to completed
                                        $sql_update_enrollment = "UPDATE enrollment SET userprogress = 'completed' WHERE enrollment_id = $enrollment_id";
                                        if (mysqli_query($con, $sql_update_enrollment)) {
                                            echo 'All lessons completed. Enrollment status updated.';
                                        } else {
                                            echo 'Error updating enrollment status: ' . mysqli_error($con);
                                        }
                                    } else {
                                        echo 'Lesson marked as completed.';
                                    }
                                } else {
                                    echo 'Error counting completed lessons: ' . mysqli_error($con);
                                }
                            } else {
                                echo 'Error checking all lessons: ' . mysqli_error($con);
                            }
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
