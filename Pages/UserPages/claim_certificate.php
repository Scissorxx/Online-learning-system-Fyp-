<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Claim Certificate</title>
</head>
<body>
    <?php
    include("header.php"); // Include your header

    // Database connection
    include '../../php/dbconnect.php';

    // Check if the enrollment ID is provided
    if(isset($_GET['enrollment_id'])) {
        $enrollment_id = $_GET['enrollment_id'];

        // Fetch enrollment details
        $enrollment_query = mysqli_query($con, "SELECT * FROM enrollment WHERE enrollment_id = $enrollment_id");
        $enrollment_data = mysqli_fetch_assoc($enrollment_query);

        // Fetch course details
        $course_id = $enrollment_data['course_id'];
        $course_query = mysqli_query($con, "SELECT * FROM courses WHERE Course_ID = $course_id");
        $course_data = mysqli_fetch_assoc($course_query);

        // Check if the user has completed the course
        if($enrollment_data['userprogress'] == 'completed') {
            // Generate certificate
            $user_id = $enrollment_data['user_id'];
            $username = $enrollment_data['user_name'];
            $course_name = $course_data['Course_Name'];

            // Certificate details
            $certificate_content = "This is to certify that $username has successfully completed the course $course_name.";

            // Insert certificate into database
            $insert_certificate_query = mysqli_query($con, "INSERT INTO certificates (user_id, course_id, certificate_content) VALUES ($user_id, $course_id, '$certificate_content')");
            
            if($insert_certificate_query) {
                echo "<h1>Certificate Claimed!</h1>";
                echo "<p>Your certificate has been successfully generated. You can download it below:</p>";
                echo "<a href='download_certificate.php?user_id=$user_id&course_id=$course_id'>Download Certificate</a>";
            } else {
                echo "<h1>Error Generating Certificate</h1>";
                echo "<p>There was an error generating your certificate. Please try again later.</p>";
            }
        } else {
            echo "<h1>Course Not Completed</h1>";
            echo "<p>You have not completed the course yet. Please complete the course to claim the certificate.</p>";
        }
    } else {
        echo "<h1>Error</h1>";
        echo "<p>Enrollment ID not provided.</p>";
    }

    mysqli_close($con); // Close database connection
    ?>
</body>
</html>
