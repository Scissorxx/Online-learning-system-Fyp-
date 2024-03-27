<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details</title>
    <link rel="stylesheet" href="Course_detailss.css">
</head>
<body>
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
        <div class="container">
            <div class="course-image">
                <img src="<?php echo htmlspecialchars($course_details['Course_Image']); ?>" alt="Course Image">
            </div>
            <div class="course-info">
                <h1 class="course-title"><?php echo htmlspecialchars($course_details['Course_Name']); ?></h1>
                <p class="instructor">Instructor: <?php echo isset($course_details['Instructor']) ? $course_details['Instructor'] : 'N/A'; ?></p>
                <div class="details">
                    <p><strong>Duration:</strong> <?php echo isset($course_details['Course_Duration']) ? $course_details['Course_Duration'] : 'N/A'; ?></p>
                    <p><strong>Total Students:</strong> <?php echo isset($course_details['Total_Students']) ? $course_details['Total_Students'] : 'N/A'; ?></p>
                    <p><strong>Lessons Available:</strong> <?php echo isset($course_details['Lessons_Available']) ? $course_details['Lessons_Available'] : 'N/A'; ?></p>
                    <p class="difficulty"><strong>Difficulty:</strong> <?php echo isset($course_details['Course_Difficulty']) ? $course_details['Course_Difficulty'] : 'N/A'; ?></p><br>
                    <?php
                    // Check if the current user is enrolled in the course
                    $user_id = $_SESSION['SN'];
                    $sql_enrollment_check = "SELECT * FROM enrollment WHERE course_id = $course_id AND user_id = $user_id";
                    $result_enrollment_check = mysqli_query($con, $sql_enrollment_check);
                    if (mysqli_num_rows($result_enrollment_check) > 0) {
                        // User is enrolled, display "Start Learning" button
                        ?>
                        <a href="lesson.php?course_id=<?php echo $course_id; ?>" class="start-learning-btn">Start Learning</a>
                        <?php
                    } else {
                        // User is not enrolled, display "Enroll Now" button
                        ?>
                        <form action="enroll.php" method="post">
                            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                            <input type="hidden" name="course_name" value="<?php echo htmlspecialchars($course_details['Course_Name']); ?>">
                            <input type="hidden" name="user_id" value="<?php echo $_SESSION['SN']; ?>">
                            <input type="hidden" name="user_name" value="<?php echo $_SESSION['email']; ?>">
                            <button type="submit" class="enroll-btn">Enroll Now</button>
                        </form>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="description">
                <h2>Description</h2>
                <p><?php echo isset($course_details['Course_Description']) ? $course_details['Course_Description'] : 'No description available'; ?></p>
            </div>
        </div>
        <div class="container">
            <div class="lessons">
                <h2>Lessons</h2>
                <?php
                // Fetch lessons for the course from the database
                $sql_lessons = "SELECT * FROM lesson WHERE Course_ID = $course_id";
                $result_lessons = mysqli_query($con, $sql_lessons);
                if(mysqli_num_rows($result_lessons) > 0) {
                    // Loop through each lesson and display them
                    while($lesson = mysqli_fetch_assoc($result_lessons)) {
                        ?>
                        <div class="lesson-box">
                            <p><?php echo $lesson['lesson_Name']; ?></p>
                        </div>
                        <?php
                    }
                } else {
                    // Handle case where no lessons were found for the course
                    echo "<p>No lessons available for this course.</p>";
                }
                ?>
            </div>
        </div>
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
</body>
</html>
