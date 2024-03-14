<?php
session_start();
include '../php/dbconnect.php';

// Check if course_id is provided in the URL
if(isset($_GET['course_id'])) {
    // Retrieve course ID from URL parameter
    $course_id = $_GET['course_id'];
    
    // Fetch course details from the database based on the provided course ID
    $sql_course_details = "SELECT * FROM courses WHERE Course_ID = $course_id";
    $result_course_details = mysqli_query($con, $sql_course_details);

    // Check if course details were found
    if (mysqli_num_rows($result_course_details) > 0) {
        $course_details = mysqli_fetch_assoc($result_course_details);
        // Display course details
?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Course Details</title>
    <link rel="stylesheet" href="Course_details.css" />

          
        </head>
        <body>
            <div class="course-details">
                <img class="course-image" src="<?php echo htmlspecialchars($course_details['Course_Image']); ?>" alt="<?php echo isset($course_details['Course_Name']) ? htmlspecialchars($course_details['Course_Name']) : 'Course Image'; ?>">
                <h2><?php echo htmlspecialchars($course_details['Course_Name']); ?></h2>
                <p><strong>Duration:</strong> <?php echo isset($course_details['Course_Duration']) ? $course_details['Course_Duration'] : 'N/A'; ?></p>
                <p><strong>Difficulty:</strong> <?php echo isset($course_details['Course_Difficulty']) ? $course_details['Course_Difficulty'] : 'N/A'; ?></p>
                <p><strong>Cost:</strong> <?php echo $course_details['Cost'] == 0 ? 'Free' : $course_details['Cost']; ?></p>
                <p><strong>Description:</strong> <?php echo isset($course_details['Course_Description']) ? $course_details['Course_Description'] : 'No description available'; ?></p>
                <!-- You can display other details here -->
                <a href="lesson.php?course_id=<?php echo $course_details['Course_ID']; ?>" class="btn">View Lessons</a>

            </div>
            <!-- Add your JavaScript link or script here -->
        </body>
        </html>
<?php
    } else {
        // Handle case where no course details were found
        echo "<p>Course details not found.</p>";
    }
} else {
    // Handle case where course ID is not provided in the URL
    echo "<p>Course ID not provided.</p>";
}
?>
