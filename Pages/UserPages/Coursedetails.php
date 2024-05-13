<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../../CSS/User-Css/Courses_details.css">
    
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

<style>
    
    .lesson {
            margin-top: 50px;
        }
        .lesson h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 4rem;
            color: #333;
        }
        .accordion .card-header {
            background-color: #f8f9fa;
            border: none;
            border-radius: 0;
            padding: 0;
            font-size:20px;
            
        }
        .accordion .card-header button {
            font-weight: bold;
            font-size: 1.1rem;
            color: #333;
            padding: 10px 20px;
            width: 100%;
            text-align: left;
            border: none;
            background-color: transparent;
            font-size:20px;

        }
        .accordion .card-header button:hover {
            background-color: #f0f0f0;
        }
        .accordion .card-body {
            padding: 20px;
            background-color: #fff;
            color: #555;
            font-size:20px;

        }
        .accordion .card {
            border: none;
            border-radius: 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            font-size:10px;

        }

</style>
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
            // Assign course details to variables
            $productIdentity = $row["Course_ID"];
            $productName = $row["Course_Name"];
            $productPrice =$row["Cost"];
            $productUrl = "http://yourwebsite.com/course_details.php?course_id=" . $row["Course_ID"];

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
                                <p>' . $row["Course_Name"] . '</p>';
                                
                                if ($loggedIn) {
                                    $user_id = $_SESSION['SN'];
                                    $sql_enrollment_check = "SELECT * FROM enrollment WHERE course_id = $course_id AND user_id = $user_id";
                                    $result_enrollment_check = mysqli_query($con, $sql_enrollment_check);
                                    if (mysqli_num_rows($result_enrollment_check) > 0) {
                                        // User is enrolled
                                        $enrollment_row = mysqli_fetch_assoc($result_enrollment_check);
                                        if ($enrollment_row['userprogress'] == 'completed') {
                                            // User has completed the course, display message and button for certificate
                                            echo '<h4>You have completed the course!</h4>';
                                            // echo '<a href="claim_certificate.php?enrollment_id=' . $enrollment_row['enrollment_id'] . '" class="claim-certificate-btn">Claim Certificate</a><br>';
                                            echo '<a href="lesson.php?course_id=' . $course_id . '" class="start-learning-btn">Contiue Learning</a>';

                                        } else {
                                            // User is enrolled but course is not completed, display "Start Learning" button
                                            echo '<h4>You have been enrolled!</h4>';
                                            echo '<a href="lesson.php?course_id=' . $course_id . '" class="start-learning-btn">Start Learning</a>';
                                        }
                                    } else {
                                        // User is not enrolled
                                        if ($row["Price"] == "paid") {
                                            // Course is paid, display "Buy Now" button
                                            echo '<h1>' . $row["Price"] . '</h1>';
                                            echo '<a href="base.php?course_id=' . $productIdentity . '&Course_name=' . $productName . '&Course_price='.$productPrice.'" class="start-learning-btn">Buy Now</a>';
                                        } else {
                                            // Course is free, display "Enroll Now" button
                                            echo '<form action="enroll.php" method="post">
                                                <input type="hidden" name="course_id" value="' . $course_id . '">
                                                <input type="hidden" name="course_name" value="' . htmlspecialchars($row["Course_Name"]) . '">
                                                <input type="hidden" name="user_id" value="' . $_SESSION['SN'] . '">
                                                <input type="hidden" name="user_name" value="' . $_SESSION['email'] . '">
                                                <button type="submit" class="enroll-btn">Enroll Now</button>
                                            </form>';
                                        }
                                    }
                                } else {
                                    // User is not logged in
                                    echo '<button type="submit" class="enroll-btn" id="addToCartButton"> Enroll Now</button>';
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
    <h1>Course Lessons</h1>
    <div id="accordionExample" class="accordion shadow">  
        <?php
        include '../../php/dbconnect.php';
        // $course_id = $_GET['course_id'];
        $sql = "SELECT * FROM lesson WHERE Course_ID = $course_id";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
            $counter = 1;
            while($row = mysqli_fetch_assoc($result)) {
                $collapseId = "collapse" . $counter;
                $headingId = "heading" . $counter;
        ?>

        <div class="card">
            <div id="<?php echo $headingId; ?>" class="card-header bg-light shadow-sm border-0">
                <h6 class="mb-0 font-weight-bold">
                    <button class="btn btn-link text-dark text-uppercase" type="button" data-toggle="collapse" data-target="#<?php echo $collapseId; ?>" aria-expanded="true" aria-controls="<?php echo $collapseId; ?>"><?php echo $counter . '. ' . $row['lesson_Name']; ?></button>
                </h6>
            </div>
            <div id="<?php echo $collapseId; ?>" class="collapse <?php echo ($counter == 1) ? 'show' : ''; ?>" aria-labelledby="<?php echo $headingId; ?>">
                <div class="card-body p-4">
                    <p class="font-weight-normal"><?php echo $row['lesson_description']; ?></p>
                </div>
            </div>
        </div>

        <?php
                $counter++;
            }
        } else {
            echo "<div class='card'><div class='card-body'><p>No lessons found for this course.</p></div></div>";
        }

        mysqli_close($con);
        ?>
    </div>
</section>



    <?php
include("footer.php");
?>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
  document.getElementById('addToCartButton').addEventListener('click', function() {
        // Display SweetAlert when the button is clicked
        swal("Warning!", "Please Log in  First!", "info");
    });    </script>
   
</body>
</html>
