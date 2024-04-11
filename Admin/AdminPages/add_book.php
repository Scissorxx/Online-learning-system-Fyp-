<?php
    include '../../php/dbconnect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = $_POST['title'];
        $authors = $_POST['authors'];
        $description = $_POST['description'];
        $publisher = $_POST['publisher'];
        $edition = $_POST['edition'];
        $price = $_POST['price'];
        $course = $_POST['course'];

        // Handling the image upload
       
            $target_dir = "../../Media/Books_Images/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        

        // Inserting into the database
        $sql = "INSERT INTO books (title, authors, description, publisher, edition, price,CourseName, image) 
                VALUES ('$title', '$authors', '$description', '$publisher', '$edition', '$price','$course', '$target_file')";
        
        if (mysqli_query($con, $sql)) {
            echo "<script>";
            echo "document.addEventListener('DOMContentLoaded', function() {";
            echo "    $('#lessonUpdatedModal').modal('show');"; // Show the book added modal
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
                <h4 class="modal-title">Book Added Successfully</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Message or content to display when lesson is updated successfully -->
                <p>Your Book has been added successfully!</p>
            </div>
            <div class="modal-footer">
                <a href="admin_Books.php" class="btn btn-default">okay</a>
            </div>
        </div>
    </div>
</div>
