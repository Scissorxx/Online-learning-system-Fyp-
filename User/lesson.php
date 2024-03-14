<?php
session_start();
include '../php/dbconnect.php'; // Include your database connection script

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
        echo "<h2>{$course_details['Course_Name']} Lessons</h2>";

        // Fetch lessons for the selected course from the database
        $sql_lessons = "SELECT * FROM lesson WHERE Course_ID = $course_id";
        $result_lessons = mysqli_query($con, $sql_lessons);

        // Check if lessons are available for the course
        if (mysqli_num_rows($result_lessons) > 0) {
            // Display lessons
            echo "<div id='lesson-list'>";
            while ($lesson = mysqli_fetch_assoc($result_lessons)) {
                echo "<div class='lesson' data-video='{$lesson['lesson_video']}'>";
                echo "<h3>{$lesson['lesson_Name']}</h3>";
                echo "</div>";
            }
            echo "</div>";
            echo "<div id='video-player' style='width: 100%; max-width: 800px; height: auto;'></div>"; // Placeholder for video player
        } else {
            echo "<p>No lessons available for this course.</p>";
        }
    } else {
        // Handle case where no course details were found
        echo "<p>Course details not found.</p>";
    }
} else {
    // Handle case where course ID is not provided in the URL
    echo "<p>Course ID not provided.</p>";
}

mysqli_close($con); // Close the database connection
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const lessons = document.querySelectorAll('.lesson');
    const videoPlayerContainer = document.getElementById('video-player');

    lessons.forEach(lesson => {
        lesson.addEventListener('click', function() {
            const videoSrc = this.dataset.video;
            const videoPlayer = document.createElement('video');
            videoPlayer.setAttribute('controls', 'controls');
            videoPlayer.setAttribute('style', 'width: 100%; height: auto;');
            const source = document.createElement('source');
            source.setAttribute('src', videoSrc);
            source.setAttribute('type', 'video/mp4');
            videoPlayer.appendChild(source);
            videoPlayerContainer.innerHTML = '';
            videoPlayerContainer.appendChild(videoPlayer);
        });
    });
});
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <STYle>
        #lesson-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    grid-gap: 20px;
}

.lesson {
    cursor: pointer;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.lesson:hover {
    background-color: #f0f0f0;
}

#video-player {
    width: 100%;
    max-width: 800px;
    margin-top: 20px;
}

    </STYle>
</head>
<body>
    
</body>
</html>