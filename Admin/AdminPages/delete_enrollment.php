<?php
    // Include your database connection file
    include '../../php/dbconnect.php';

    if(isset($_POST['enrollment_id'])) {
        $enrollment_id = $_POST['enrollment_id'];
        
        // Delete progress of the student
        $delete_progress_query = "DELETE FROM User_progress WHERE enrollment_id = $enrollment_id";
        if(!mysqli_query($con, $delete_progress_query)) {
            echo "error";
            exit;
        }

        // Delete enrollment
        $delete_enrollment_query = "DELETE FROM enrollment WHERE enrollment_id = $enrollment_id";
        if(mysqli_query($con, $delete_enrollment_query)) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "error";
    }
?>
