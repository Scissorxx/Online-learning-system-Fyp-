<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../../CSS/User-Css/Courses-detail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-jx6WXSa9CFddo/NQs9Mv78v0CjB+l/5XmlymwHt8bndqvNvhIz1KpRIkAWcJcTbfLs1lxXTIt4deNlOeUHX1Ow==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <title>Courses</title>
</head>
<body>
    <?php
    include("header.php");
    include '../../php/dbconnect.php';

    // Check if user is logged in
    $loggedIn = isset($_SESSION['SN']);

    // Fetch course details based on the course ID from the URL
    $course_id = $_GET['course_id'];
    $sql = "SELECT * FROM courses WHERE Course_ID = $course_id";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo '<section class="page-header">
                    <div class="container">
                        <div class="page-header_wrap">
                            <ul class="coustom-breadcrumb">
                                <li><a href="#">Home</a></li>/
                                <li>' . $row["Course_Name"] . '</li>
                            </ul>
                        </div>
                    </div>
                </section>
                <section class="Main-section">
                    <div class="containers">
                        <div class="course-details">
                            <div class="image-section">
                                <img src="' . $row["Course_Image"] . '" alt="Course Image">
                                <p>' . $row["Course_Name"] . '</p>
                            </div>
                            <div class="course-info">
                                <img src="' . $row["Course_Image"] . '" alt="Course Image" style="width: 200px; max-width: 400px; height: 200px;">
                                <p>' . $row["Course_Name"] . '</p>
                                <h1>' . $row["Price"] . '</h1>';
                                
                                if ($loggedIn) {
                                    $user_id = $_SESSION['SN'];
                                    $sql_enrollment_check = "SELECT * FROM enrollment WHERE course_id = $course_id AND user_id = $user_id";
                                    $result_enrollment_check = mysqli_query($con, $sql_enrollment_check);
                                    if (mysqli_num_rows($result_enrollment_check) > 0) {
                                        // User is enrolled, display "Start Learning" button
                                        echo '<a href="lesson.php?course_id=' . $course_id . '" class="start-learning-btn">Start Learning</a>';
                                        
                                    } else {
                                        // User is not enrolled, display "Enroll Now" button
                                        echo '<form action="enroll.php" method="post">
                                            <input type="hidden" name="course_id" value="' . $course_id . '">
                                            <input type="hidden" name="course_name" value="' . htmlspecialchars($row["Course_Name"]) . '">
                                            <input type="hidden" name="user_id" value="' . $_SESSION['SN'] . '">
                                            <input type="hidden" name="user_name" value="' . $_SESSION['email'] . '">
                                            <button type="submit" class="enroll-btn">Enroll Now</button>
                                        </form>';
                                    }
                                } else {
                                    
                            echo"    <a href='#deleteCourseModal' class='enroll-btn' data-toggle='modal'>Enroll Now</a>";

                                }
                                
                                echo '<div class="course-container">
                                    <h2>Course Information</h2>
                                    <div class="course-information">
                                        <table>
                                            <tr>
                                                <th><i class="fas fa-clock"></i> Duration</th>
                                                <td>' . $row["Course_Duration"] . '</td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-bolt"></i> Difficulty</th>
                                                <td>' . $row["Course_Difficulty"] . '</td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-book"></i> Lessons</th>
                                                <td>';

                                                // Fetching lesson count
                                                $sql_lesson_count = "SELECT COUNT(*) AS lesson_count FROM lesson WHERE Course_ID = $course_id";
                                                $result_lesson_count = $con->query($sql_lesson_count);
                                            
                                                if ($result_lesson_count->num_rows > 0) {
                                                    $row_lesson_count = $result_lesson_count->fetch_assoc();
                                                    echo $row_lesson_count["lesson_count"];
                                                } else {
                                                    echo "0";
                                                }

                                                echo '</td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-user"></i> Instructor</th>
                                                <td>' . $row["Teacher"] . '</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="course-description">
                    <div class="content">
                        <div class="about-the-course">
                            <h1>About the Course</h1>
                            <p>' . $row["Course_Description"] . '</p>
                        </div>
                    </div>
                </section>';
        }
    } else {
        echo "0 results";
    }
    $con->close();
    ?>

    <section class="lesson">
        <div class="lessonbox">
            <div class="lesson-content">
                <h2>Course Lessons</h2>
                <ul class="lesson-list">
                    <?php
                    // Fetch lessons associated with the course
                    include '../../php/dbconnect.php';
                    $sql_lessons = "SELECT * FROM lesson WHERE Course_ID = $course_id";
                    $result_lessons = $con->query($sql_lessons);

                    if ($result_lessons->num_rows > 0) {
                        // Output each lesson dynamically
                        $lesson_number = 1;
                        while ($row_lesson = $result_lessons->fetch_assoc()) {
                            echo "<li>Lesson $lesson_number: " . $row_lesson["lesson_Name"] . "</li>";
                            $lesson_number++;
                        }
                    } else {
                        echo "<li>No lessons found for this course.</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </section>

    <div id="deleteCourseModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="delete-course-form" method="POST" action="delete_course.php">
                <div class="modal-header">                        
                    <h4 class="modal-title">Alert</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">     
                <div class="alert alert-warning alert-dismissible fade show">
    <strong>Warning!</strong> You need to log in first to Continue
</div>           
</div>
                <div class="modal-footer">
                    <button type="button" class="btns btn-default" data-dismiss="modal">Okay</button>

                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
