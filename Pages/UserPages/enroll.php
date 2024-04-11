<?php
session_start();
include '../../php/dbconnect.php';

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
        echo "<script>";
        echo "document.addEventListener('DOMContentLoaded', function() {";
        echo "    $('#lessonUpdatedModal').modal('show');"; // Show the course updated modal
        echo "});";
        echo "</script>";
        // header("Location: Coursedetails.php?course_id=$course_id");
    } else {
        echo "Error enrolling in the course: " . mysqli_error($con);
    }
} else {
    echo "Invalid request!";
}
?>
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<div id="lessonUpdatedModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Enrolled Successfully</h4>
            </div>
            <div class="modal-body">
                <!-- Message or content to display when course is enrolled successfully -->
                <div class="alert alert-success alert-dismissible fade show">
    <strong>Success!</strong> You have been enrolled in the course successfully!
</div>
            </div>
            <div class="modal-footer">
                <a href="Coursedetails.php?course_id=<?php echo $course_id; ?>" class="btn btn-default">Okay</a>
            </div>
        </div>
    </div>
</div>
