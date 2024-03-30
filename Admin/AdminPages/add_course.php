<?php
    include '../../php/dbconnect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $course_name = $_POST['course-name'];
        $course_duration = $_POST['course-duration'];
        $course_difficulty = $_POST['course-difficulty'];
        $course_description = $_POST['course-description'];
        $course_price_type = $_POST['course-price']; // Free or Paid
        $course_price = ($course_price_type == 'paid') ? $_POST['course-price-input'] : '0'; // If course is paid, get the price
        $course_live_classes = $_POST['live-classes'];

        // Handling the image upload
        $target_dir = "../../Media/Courses_Thumnail/";
        $target_file = $target_dir . basename($_FILES["course-image"]["name"]);
        move_uploaded_file($_FILES["course-image"]["tmp_name"], $target_file);

        // Inserting into the database
        $sql = "INSERT INTO courses (Course_Name, Course_Duration, Course_Difficulty, Course_Description, Price, Cost,LiveClass, Course_Image) VALUES ('$course_name', '$course_duration', '$course_difficulty', '$course_description','$course_price_type', '$course_price', '$course_live_classes', '$target_file')";
        
        if (mysqli_query($con, $sql)) {
            echo "<script>";
            echo "document.addEventListener('DOMContentLoaded', function() {";
            echo "    $('#lessonUpdatedModal').modal('show');"; // Show the course updated modal
            echo "});";
            echo "</script>";

        
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($con);
        }

        mysqli_close($con);
    }
?>




<link rel="stylesheet" href="../Admin-Css/admin-Courses.css" />
    <link rel="stylesheet" href="../../Css/Admin-Css/admin.css">


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

<div id="lessonUpdatedModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Course Added Successfully</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Message or content to display when lesson is updated successfully -->
                <p>Your Course has been added successfully!</p>
            </div>
            <div class="modal-footer">
                <a href="admin_Courses.php" class="btn btn-default">okay</a>
            </div>
        </div>
    </div>
</div>
