<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lesson List</title>
    <!-- Include Video.js CSS -->
    <link href="https://vjs.zencdn.net/7.15.4/video-js.css" rel="stylesheet">
    <link rel="stylesheet" href="../../CSS/User-Css/lessons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Reset CSS */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        /* Main Container */
        .main {
            margin-top: 50px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            flex-wrap: wrap;
            padding: 20px;
        }

        /* Lesson Container */
        .container {
            flex: 0 1 calc(25% - 40px);
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        h1 {
            text-align: center;
            margin-top: 0;
            font-size: 28px;
            color: #007bff;
        }

        /* Lesson List */
        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        li:last-child {
            border-bottom: none;
        }

        li:hover {
            background-color: #f0f0f0;
        }

        a {
            text-decoration: none;
            color: #007bff;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
        }

        .completed .lesson-title {
            text-decoration: line-through;
            color: #888;
        }

        .completed .lesson-icon i {
            display: none;
        }

        .completed .lesson-icon .fa-check {
            display: inline-block;
            color: green;
        }

        .lesson-icon .fa-check {
            color: green;
            margin-left: 10px;
            display: none;
        }

        .video-container {
    flex: 1 1 calc(75% - 40px);
    margin: 20px;
    padding: 0; /* Changed padding to 0 */
    background-color: #fff;
    border-radius: 10px; /* Keep border-radius for the container */
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
}

.video-container h2 {

    margin: 20px 0 10px; /* Adjusted margin */
    font-size: 24px;
    color: #007bff;
}

video {
    width: auto;
    max-width: 100%;
    height: auto;
    object-fit: contain;
}

@media only screen and (max-width: 768px) {
    .video-container {
        flex-direction: column; /* Display video on top when in small screen */
    }
    
    video {
        width: 50%; /* Set width to 50% when in small screen */
    }
}

    </style>
</head>
<body>
    <section class="main">
        <div class="container">
            <h1>Course Lessons</h1>
            <ul>
                <?php
                include("header.php");
                include '../../php/dbconnect.php'; // Include your database connection script

                // Check if session and course_id are set
                if(isset($_SESSION['SN']) && isset($_GET['course_id'])) {
                    $user_id = $_SESSION['SN'];
                    $course_id = $_GET['course_id'];

                    // Fetch course details from the database based on the provided course ID
                    $sql_course_details = "SELECT * FROM courses WHERE Course_ID = ?";
                    $stmt = $con->prepare($sql_course_details);
                    $stmt->bind_param("i", $course_id);
                    $stmt->execute();
                    $result_course_details = $stmt->get_result();

                    // Check if course details were found
                    if ($result_course_details->num_rows > 0) {
                        $course_details = $result_course_details->fetch_assoc();

                        // Fetch enrollment ID
                        $sql_enrollment_id = "SELECT enrollment_id FROM enrollment WHERE user_id = ? AND course_id = ?";
                        $stmt = $con->prepare($sql_enrollment_id);
                        $stmt->bind_param("ii", $user_id, $course_id);
                        $stmt->execute();
                        $result_enrollment_id = $stmt->get_result();

                        if ($result_enrollment_id->num_rows > 0) {
                            $enrollment_row = $result_enrollment_id->fetch_assoc();
                            $enrollment_id = $enrollment_row['enrollment_id'];

                            // Fetch lessons for the selected course from the database
                            $sql_lessons = "SELECT * FROM lesson WHERE Course_ID = ?";
                            $stmt = $con->prepare($sql_lessons);
                            $stmt->bind_param("i", $course_id);
                            $stmt->execute();
                            $result_lessons = $stmt->get_result();

                            // Check if lessons are available for the course
                            if ($result_lessons->num_rows > 0) {
                                $total_lessons = $result_lessons->num_rows;
                                $completed_lessons = 0; // Initialize counter for completed lessons

                                // Display lessons
                                while ($lesson = $result_lessons->fetch_assoc()) {
                                    // Fetch completion status from the database
                                    $lesson_id = $lesson['lesson_ID'];
                                    $completed = 'false';
                                    $watched = 'false';

                                    // Check user's progress in this lesson
                                    $sql_user_progress = "SELECT * FROM user_progress WHERE user_id = ? AND lesson_id = ?";
                                    $stmt = $con->prepare($sql_user_progress);
                                    $stmt->bind_param("ii", $user_id, $lesson_id);
                                    $stmt->execute();
                                    $result_user_progress = $stmt->get_result();
                                    if ($result_user_progress->num_rows > 0) {
                                        $progress = $result_user_progress->fetch_assoc();
                                        $completed = $progress['status'] === 'completed' ? 'true' : 'false';
                                        $watched = 'true';

                                        // Increment counter if lesson is completed
                                        if ($completed === 'true') {
                                            $completed_lessons++;
                                        }
                                    }

                                    // Add a CSS class for completed lessons
                                    $class = $completed === 'true' ? 'completed' : '';

                                    echo "<li class='lesson $class' data-video='{$lesson['lesson_video']}' data-completed='{$completed}' data-watched='{$watched}' data-lesson-id='{$lesson_id}' data-course-id='{$course_id}' data-enrollment-id='{$enrollment_id}'>";
                                    echo "<span class='lesson-title'>{$lesson['lesson_Name']}</span>";
                                    echo "<span class='lesson-icon'><i class='fas fa-check'></i></span>";
                                    echo "</li>";
                                }

                                // Display total number of lessons completed
                                echo "<li id='completed-lessons' style='font-weight: bold; margin-top: 20px;'>Total lessons completed: $completed_lessons out of $total_lessons</li>";
                            } else {
                                echo "<p>No lessons available for this course.</p>";
                            }
                        } else {
                            // Handle case where enrollment ID is not found
                            echo "<p>Enrollment ID not found.</p>";
                        }
                    } else {
                        // Handle case where no course details were found
                        echo "<p>Course details not found.</p>";
                    }
                } else {
                    // Handle case where session or course ID is not provided
                    echo "<p>Session or course ID not provided.</p>";
                }

                $con->close(); // Close the database connection
                ?>
            </ul>
        </div>

        <div class="video-container">
            <h2 id="lesson-title">Lesson Title</h2>
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
            const lessonTitle = document.getElementById('lesson-title');

            const lessons = document.querySelectorAll('.lesson');

            let lessonId; // Define lessonId outside event listeners
            let courseId; // Define courseId outside event listeners
            let enrollmentId; // Define enrollmentId outside event listeners

            lessons.forEach((lesson) => {
                lesson.addEventListener('click', function() {
                    const videoSrc = this.dataset.video;
                    lessonId = this.dataset.lessonId; // Assign value to lessonId
                    courseId = this.dataset.courseId; // Assign value to courseId
                    enrollmentId = this.dataset.enrollmentId; // Assign value to enrollmentId

                    // Update lesson title
                    lessonTitle.innerText = this.querySelector('.lesson-title').innerText;

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
                            const completedLesson = document.querySelector(`.lesson[data-lesson-id="${lessonId}"][data-course-id="${courseId}"]`);
                            if (completedLesson) {
                                completedLesson.setAttribute('data-completed', 'true');
                                completedLesson.classList.add('completed'); // Add completed class

                                // Show tick mark
                                completedLesson.querySelector('.lesson-icon .fa-check').style.display = 'inline-block';

                                // Update completed lessons count
                                const totalCompleted = document.getElementById('completed-lessons');
                                const completedCount = parseInt(totalCompleted.innerText.match(/\d+/)[0]) + 1;
                                totalCompleted.innerText = `Total lessons completed: ${completedCount} out of ${lessons.length}`;
                            }
                        } else {
                            console.error('Failed to mark lesson as completed. Status: ' + xhr.status);
                        }
                    }
                };
                xhr.onerror = function() {
                    console.error('An error occurred during the AJAX request.');
                };
                xhr.send('lesson_id=' + lessonId + '&course_id=' + courseId + '&enrollment_id=' + enrollmentId);
            });
        });
    </script>
</body>
</html>
