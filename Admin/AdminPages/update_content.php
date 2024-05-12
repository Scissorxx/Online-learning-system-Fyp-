<?php
// Include database connection file
include '../../php/dbconnect.php';

// Retrieve data from form
$content_id = $_POST['content_id'];
$name = $_POST['name'];
$heading1 = $_POST['heading1'];
$heading2 = $_POST['heading2'];
$teacher = $_POST['teacher'];
$course = $_POST['course'];
$class = $_POST['class'];
$material = $_POST['material'];
$video = $_POST['video'];

// Update query
$sql = "UPDATE landingpage SET 
        Name='$name', 
        heading1='$heading1', 
        heading2='$heading2', 
        teacher='$teacher', 
        course='$course', 
        class='$class', 
        material='$material', 
        video='$video' 
        WHERE content_id=$content_id";

// Execute query
if (mysqli_query($con, $sql)) {
    header("Location: admin_content.php"); // Redirect to the page where students are managed
    exit();
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($con);
}

// Close connection
mysqli_close($con);
?>
