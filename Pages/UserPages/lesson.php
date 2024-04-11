<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lesson List</title>
    <!-- Include Video.js CSS -->
    <link href="https://vjs.zencdn.net/7.15.4/video-js.css" rel="stylesheet">
    <link rel="stylesheet" href="../../CSS/User-Css/lesson.css">

    <style>
        /* Define styles for completed lessons */
        .lesson.completed {
            /* Add your styles here */
            /* For example, change background color */
            background-color: #cce5ff; /* Light blue */
        }
    </style>
</head>
<body>
    <section class="main">
        <div class="container">
            <h1>Course Lessons</h1>
            <ul id="lesson-list">
                <?php
                    include("header.php");
                    $user_id = $_SESSION['SN'];
                    include '../../php/dbconnect.php'; // Include your database connection script

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

                            // Fetch lessons for the selected course from the database
                            $sql_lessons = "SELECT * FROM lesson WHERE Course_ID = $course_id";
                            $result_lessons = mysqli_query($con, $sql_lessons);

                            // Check if lessons are available for the course
                            if (mysqli_num_rows($result_lessons) > 0) {
                                // Display lessons
                                while ($lesson = mysqli_fetch_assoc($result_lessons)) {
                                    // Fetch completion status from the database
                                    $lesson_id = $lesson['lesson_ID'];
                                    $completed = 'false';
                                    $watched = 'false';

                                    // Check user's progress in this lesson
                                    $sql_user_progress = "SELECT * FROM user_progress WHERE user_id = $user_id AND lesson_id = $lesson_id";
                                    $result_user_progress = mysqli_query($con, $sql_user_progress);
                                    if (mysqli_num_rows($result_user_progress) > 0) {
                                        $progress = mysqli_fetch_assoc($result_user_progress);
                                        $completed = $progress['status'] === 'completed' ? 'true' : 'false';
                                        $watched = 'true';
                                    }

                                    // Add a CSS class for completed lessons
                                    $class = $completed === 'true' ? 'completed' : '';

                                    echo "<li class='lesson $class' data-video='{$lesson['lesson_video']}' data-completed='{$completed}' data-watched='{$watched}' data-lesson-id='{$lesson_id}'>";
                                    echo "<h3>{$lesson['lesson_Name']}</h3>";
                                    echo "</li>";
                                }
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
            </ul>
        </div>

        <div class="video-container">
            <video id="my-video" class="video-js" controls preload="auto" data-setup="{}">
                <p class="vjs-no-js">
                    To view this video please enable JavaScript, and consider upgrading to a
                    <a href="https://videojs.com/html5-video-support/" target="_blank">browser that supports HTML5 video</a>
                </p>
            </video>
        </div>
    </section>

    <!-- Include Video.js JavaScript -->
    <script src="https://vjs.zencdn.net/7.15.4/video.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const videoPlayer = videojs('my-video');

    const lessons = document.querySelectorAll('.lesson');

    let lessonId; // Define lessonId outside event listeners

    lessons.forEach((lesson) => {
        lesson.addEventListener('click', function() {
            const videoSrc = this.dataset.video;
            lessonId = this.dataset.lessonId; // Assign value to lessonId

            videoPlayer.src([
                { type: 'video/mp4', src: videoSrc },
                // Add more source types if necessary
            ]);

            videoPlayer.play();
        });
    });

    // Add event listener for when the video ends
    videoPlayer.on('ended', function() {
        // Mark lesson as completed when video ends
        // AJAX call to mark lesson as completed in the database
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'mark_completed.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // Find the lesson element and mark it as completed
                    const completedLesson = document.querySelector(`.lesson[data-lesson-id="${lessonId}"]`);
                    if (completedLesson) {
                        completedLesson.setAttribute('data-completed', 'true');
                        completedLesson.classList.add('completed'); // Add completed class
                    }
                } else {
                    console.error('Failed to mark lesson as completed. Status: ' + xhr.status);
                }
            }
        };
        xhr.onerror = function() {
            console.error('An error occurred during the AJAX request.');
        };
        xhr.send('lesson_id=' + lessonId);
    });
});


</script>


</body>
</html>
