<?php
session_start();
include 'php/dbconnect.php';

// Check if the user is logged in
if (!isset($_SESSION['valid'])) {
    header("Location: Loginpage.php");
    exit();
}

// Check if the course ID is provided through POST
if (isset($_POST['course_id'])) {
    // Retrieve user and course IDs
    $userId = $_SESSION['SN']; 
    $courseId = $_POST['course_id']; // Corrected to use 'course_id'


    

    $checkEnrollmentQuery = "SELECT * FROM user_enrollments WHERE user_id = ? AND course_id = ?";
    $stmt = mysqli_prepare($con, $checkEnrollmentQuery);
    mysqli_stmt_bind_param($stmt, "ii", $userId, $courseId);
    mysqli_stmt_execute($stmt);
    $checkEnrollmentResult = mysqli_stmt_get_result($stmt);
    

    if ($checkEnrollmentResult->num_rows == 0) {
        // If not enrolled, proceed with enrollment
        $enrollQuery = "INSERT INTO user_enrollments (user_id, course_id) VALUES ($userId, $courseId)";
        $enrollResult = mysqli_query($con, $enrollQuery);

        if ($enrollResult) {
            // Enrollment successful
            header("Location: dashboard.php"); // Redirect to the dashboard or a success page
            exit();
        } else {
            // Enrollment failed
            echo "Enrollment failed. Please try again.";
        }
    } else {
        // User is already enrolled
        echo "You are already enrolled in this course.";
    }
} else {
    // Course ID is not provided
    echo "Invalid course ID.";
}
?>
