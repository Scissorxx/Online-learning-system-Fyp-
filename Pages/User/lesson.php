<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lesson List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            position: fixed;
            top: 0;
            left: 0;
            width: 400px;
            height: 100%;
            padding: 20px;
            background-color: #fff;
            border-right: 1px solid #ddd;
        }
        h1 {
            text-align: center;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        li:last-child {
            border-bottom: none;
        }
        a {
            text-decoration: none;
            color: #333;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #007bff;
        }
        .content {
            margin-left: 420px;
            padding: 20px;
        }
        #video-player {
            width: 90%;
        }

        .lesson {
            cursor: pointer;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .lesson:hover {
            background-color: #f0f0f0;
        }

        video {
            margin-top:100px;
            margin-left:70px;

            width: 100%;
        }
    
    </style>
</head>
<body>

<div class="container">
    <h1>Content</h1>
    <ul>
    <div id="lesson-list">
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

                $sql_check_all_completed = "SELECT COUNT(*) AS total_lessons FROM lesson";
                 $result = mysqli_query($con, $sql_check_all_completed);
                 $row = mysqli_fetch_assoc($result);
                 $total_lessons = $row['total_lessons'];

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
                            echo "<div class='lesson' data-video='{$lesson['lesson_video']}' data-completed='false' data-watched='false'>";
                            echo "<h3>{$lesson['lesson_Name']}</h3>";
                            echo "</div>";
                        }
                        echo "</div>";
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
        </div>
    </ul>
</div>

<div class="content">
    <?php
    //  echo"$total_lessons";
    ?>
<div id="video-player"></div>
    </div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const videoPlayerContainer = document.getElementById('video-player');
    const lessons = document.querySelectorAll('.lesson');
    let currentLessonIndex = 0;

    function playLesson(index) {
        const lesson = lessons[index];
        const videoSrc = lesson.dataset.video;

        const videoPlayer = document.createElement('video');
        videoPlayer.setAttribute('controls', 'controls');
        videoPlayer.onended = function() {
            videoPlayerContainer.innerHTML = '';
           
            // Display completion message
            // alert('Lesson ' + (index + 1) + ' completed!');
            
            // Send AJAX request to mark lesson as completed
            markLessonCompleted(index + 1); // Index is zero-based, so add 1
        };
        const source = document.createElement('source');
        source.setAttribute('src', videoSrc);
        source.setAttribute('type', 'video/mp4');
        videoPlayer.appendChild(source);
        videoPlayerContainer.innerHTML = '';
        videoPlayerContainer.appendChild(videoPlayer);

        videoPlayer.play(); // Start playing the video automatically
    }

    lessons.forEach((lesson, index) => {
        lesson.addEventListener('click', function() {
            playLesson(index);
        });
    });
  
        function markLessonCompleted(lessonNumber) {
        
            window.location.href = 'progess.php?lesson_number=' + lessonNumber + '&course_id=' + <?php echo $course_id; ?>;

}

});

</script>


</body>
</html>
