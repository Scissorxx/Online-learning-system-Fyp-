<?php
include '../../php/dbconnect.php';

// Retrieve course ID from the form
$course_id = $_POST['course_id'] ?? '';

// Retrieve course name from the database
$course_name = '';
if (!empty($course_id)) {
    $query = "SELECT Course_Name FROM courses WHERE Course_ID = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $course_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $course_name);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

// Handle form submission
if(isset($_POST['submit'])) {
    // Retrieve lesson details
    $lesson_name = $_POST['lesson_name'] ?? '';
    $lesson_description = $_POST['lesson_description'] ?? '';

    // Validate form fields
    if(empty($lesson_name) || empty($lesson_description)) {
        $upload_error = "Please fill in all the required fields.";
    } else {
        // Handle video upload
        if ($_FILES['lesson_video']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../../Media/lesson_videos/';
            $upload_file = $upload_dir . basename($_FILES['lesson_video']['name']);
            $video_extension = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));
            $allowed_extensions = array('mp4', 'avi', 'wmv', 'mov'); 
            
            if (in_array($video_extension, $allowed_extensions)) {
                if ($_FILES['lesson_video']['size'] <= 209715200) { 
                    if (move_uploaded_file($_FILES['lesson_video']['tmp_name'], $upload_file)) {
                        // Insert lesson details into the database
                        $insert_lesson_sql = "INSERT INTO lesson (Course_ID, Course_Name, Lesson_Name, Lesson_Description, Lesson_Video) VALUES (?, ?, ?, ?, ?)";
                        $stmt = mysqli_prepare($con, $insert_lesson_sql);
                        mysqli_stmt_bind_param($stmt, "sssss", $course_id, $course_name, $lesson_name, $lesson_description, $upload_file);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);

                        echo "<script>";
                        echo "document.addEventListener('DOMContentLoaded', function() {";
                        echo "    $('#lessonUpdatedModal').modal('show');"; // Show the course updated modal
                        echo "});";
                        echo "</script>";
                    } else {
                        $upload_error = "Error uploading file.";
                    }
                } else {
                    $upload_error = "File size exceeds the maximum allowed limit (200MB).";
                }
            } else {
                $upload_error = "Invalid file format. Please upload a video in MP4, AVI, WMV, or MOV format.";
            }
        } else {
            $upload_error = "Error uploading file.";
        }
    }
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
                <h4 class="modal-title">Lesson Updated Successfully</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Message or content to display when lesson is updated successfully -->
                <p>Your lesson has been updated successfully!</p>
            </div>
            <div class="modal-footer">
                <a href="teacherCoursedetails.php?course_id=<?php echo $course_id; ?>" class="btn btn-default">Okay</a>
            </div>
        </div>
    </div>
</div>
