<?php
session_start();
include '../php/dbconnect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $user_id = $_POST['user_id'];
    $user_name = $_POST['user_name'];

    // Insert data into the enrollment table
    $sql_enroll = "INSERT INTO enrollment (course_id, course_name, user_id, user_name) VALUES ('$course_id', '$course_name', '$user_id', '$user_name')";
    
    if (mysqli_query($con, $sql_enroll)) {
        echo "Enrollment successful!";
        header("Location: Course_details.php?course_id=$course_id");
    } else {
        echo "Error enrolling in the course: " . mysqli_error($con);
    }
} else {
    echo "Invalid request!";
}
?>
