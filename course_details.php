<?php
session_start();
include 'php/dbconnect.php';

// Check if the user is logged in
if (!isset($_SESSION['valid'])) {
    header("Location: Loginpage.php");
    exit();
}



// Get the course ID from the URL
$courseId = isset($_GET['course_id']) ? $_GET['course_id'] : null;

if ($courseId === null) {
    // Redirect to an error page or back to the dashboard if no course ID is provided
    header("Location: dashboard.php");
        exit();
}

// Retrieve course details from the database
$sql = "SELECT id, title, image, level, instructor FROM courses WHERE id = $courseId";

$result = $con->query($sql);

if ($result->num_rows == 1) {
    $course = $result->fetch_assoc();
} else {
    // Redirect to an error page or back to the dashboard if the course ID is invalid
    header("Location: dashboard.php");
    exit();
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $course['title']; ?> Details</title>
    <link rel="stylesheet" href="course_details.css"> 
</head>
<body>
    <div class="container">
        <h2><?php echo $course['title']; ?> Details</h2>
        <img src="<?php echo $course['image']; ?>" alt="<?php echo $course['title']; ?>">
        <p>Level: <?php echo $course['level']; ?></p>
        <p>Instructor: <?php echo $course['instructor']; ?></p>
        <!-- <p>Description: 
        //   <?php 
        // echo $course['description']; 
        ?> //
            
        </p> -->

        <!-- Additional course details can be added here -->

        <form action="enroll_process.php" method="post">
            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
            <button type="submit">Enroll Now</button>
        </form>
    </div>
</body>
</html>
