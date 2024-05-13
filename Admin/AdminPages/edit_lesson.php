<?php
include '../../php/dbconnect.php';

// Initialize variables
$lesson_id = $_GET['lesson_id'] ?? '';
$lesson_name_from_database = '';
$lesson_description_from_database = '';
$lesson_video_from_database = '';

// Retrieve lesson details from the database
if (!empty($lesson_id)) {
    $get_lesson_sql = "SELECT Lesson_Name, Lesson_Description, Lesson_Video FROM lesson WHERE Lesson_ID = ?";
    $stmt = mysqli_prepare($con, $get_lesson_sql);
    mysqli_stmt_bind_param($stmt, "s", $lesson_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $lesson_name_from_database, $lesson_description_from_database, $lesson_video_from_database);
        mysqli_stmt_fetch($stmt);
    } else {
        // Handle case where lesson details are not found
        echo "Lesson details not found.";
        exit();
    }
    mysqli_stmt_close($stmt);
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve form data
    $lesson_name = mysqli_real_escape_string($con, $_POST['lesson_name']);
    $lesson_description = mysqli_real_escape_string($con, $_POST['lesson_description']);
    
    // Update lesson details in the database
    $update_lesson_sql = "UPDATE lesson SET Lesson_Name = ?, Lesson_Description = ? WHERE Lesson_ID = ?";
    $stmt = mysqli_prepare($con, $update_lesson_sql);
    mysqli_stmt_bind_param($stmt, "sss", $lesson_name, $lesson_description, $lesson_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "Lesson details updated successfully.";
    } else {
        echo "Error updating lesson details: " . mysqli_error($con);
    }
    mysqli_stmt_close($stmt);

    // Handle Lesson Video upload
    if (!empty($_FILES['lesson_video']['name'])) {
        $upload_dir = '../../Media/lesson_videos/';
        $video_tmp_name = $_FILES['lesson_video']['tmp_name'];
        $video_destination = $upload_dir . basename($_FILES['lesson_video']['name']);
        $video_extension = strtolower(pathinfo($video_destination, PATHINFO_EXTENSION));
        $allowed_extensions = array('mp4', 'avi', 'wmv', 'mov');

        if (in_array($video_extension, $allowed_extensions)) {
            if (move_uploaded_file($video_tmp_name, $video_destination)) {
                // Update lesson video path in the database
                $update_video_sql = "UPDATE lesson SET Lesson_Video = ? WHERE Lesson_ID = ?";
                $stmt = mysqli_prepare($con, $update_video_sql);
                mysqli_stmt_bind_param($stmt, "ss", $video_destination, $lesson_id);
                
                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>";
                    echo "document.addEventListener('DOMContentLoaded', function() {";
                    echo "    $('#lessonUpdatedModal').modal('show');"; // Show the course updated modal
                    echo "});";
                    echo "</script>";
                } else {
                    echo "Error updating lesson video: " . mysqli_error($con);
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "Error uploading lesson video.";
            }
        } else {
            echo "Invalid file format. Allowed formats: mp4, avi, wmv, mov.";
        }
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lesson</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
        }

        input[type="text"],
        select,
        textarea,
        input[type="file"] {
            width: calc(100% - 22px); /* adjust for border */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            display: block;
        }

        button:hover {
            background-color: #0056b3;
        }

        .video-container {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            margin-bottom: 20px;
        }

        .video-container video {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }
    </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script src="https://cdn.tiny.cloud/1/ynu5oa33yc3yq0jzt1s9xfpmfic0m7knokyzlvwehwtkkm9c/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount linkchecker',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Edit Lesson</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="lesson_name">Lesson Name:</label>
                <input type="text" id="lesson_name" name="lesson_name" value="<?php echo htmlspecialchars($lesson_name_from_database); ?>">
            </div>
            <div class="form-group">
                <label for="lesson_description">Lesson Description:</label>
                <textarea id="lesson_description" name="lesson_description"><?php echo htmlspecialchars($lesson_description_from_database); ?></textarea>
            </div>
            <div class="form-group">
                <label for="lesson_video">Lesson Video:</label>
                <div class="video-container">
                    <?php if (!empty($lesson_video_from_database)): ?>
                        <video controls>
                            <source src="<?php echo $lesson_video_from_database; ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    <?php endif; ?>
                </div>
                <input type="file" id="lesson_video" name="lesson_video">
            </div>
            <button type="submit" name="submit">Save</button>
        </form>
    </div>



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
                <a href="admin_Courses.php" class="btn btn-default">okay</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
