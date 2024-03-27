<?php
session_start();
include '../php/dbconnect.php'; // Include your database connection script

// Check if the user is authenticated
if (!isset($_SESSION['SN'])) {
    echo 'Error: User not authenticated.';
    exit;
}

// Ensure that lesson number is provided and is a valid integer
if (isset($_GET['lesson_number']) && is_numeric($_GET['lesson_number'])) {
    // Retrieve lesson number and sanitize it
    $lesson_number = mysqli_real_escape_string($con, $_GET['lesson_number']);

    // Retrieve user ID from session
    $user_id = $_SESSION['SN'];

    // Retrieve course ID from the URL parameters
    if (isset($_GET['course_id']) && is_numeric($_GET['course_id'])) {
        $course_id = mysqli_real_escape_string($con, $_GET['course_id']);

        // Update the user's progress in the database
        $sql = "UPDATE enrollment SET userprogress = '$lesson_number' WHERE user_id = '$user_id' AND course_id = '$course_id'";
        if (mysqli_query($con, $sql)) {
            
            header("Location: lesson.php?lesson_number=$lesson_number&course_id=$course_id");
        } else {
            echo 'Error: ' . mysqli_error($con);
        }
    } else {
        echo 'Error: Course ID not provided or invalid.';
    }
} else {
    // If lesson number is not provided or is invalid, return an error message
    echo 'Error: Invalid lesson number.';
}

mysqli_close($con); // Close the database connection
?>
