<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../../CSS/User-Css/Courses.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-jx6WXSa9CFddo/NQs9Mv78v0CjB+l/5XmlymwHt8bndqvNvhIz1KpRIkAWcJcTbfLs1lxXTIt4deNlOeUHX1Ow==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- <link rel="stylesheet" href="../../CSS/User-Css/Landing-page.css"> -->
    
    <title>Courses</title>
</head>
<body> 
    <?php
    include("header.php");

    include '../../php/dbconnect.php';
    $courses = [];
    $sql1 = "SELECT * FROM courses";
    $result1 = mysqli_query($con, $sql1);
    while ($row = mysqli_fetch_assoc($result1)) {
        $courses[] = $row;
    }
?>
<section class="page-header">
    <div class="container">
        <div class="page-header_wrap">
            <div class="page-heading"></div>
            <ul class="coustom-breadcrumb">
                <li><a href="#">Home</a></li>/
                <li>Courses</li>
            </ul>
        </div>
    </div>
</section>

<!-- Courses section -->
<section class="courses" id="courses">
    
    <div class="box-container">
        <?php foreach ($courses as $course): ?>
            <a href="Coursedetails.php?course_id=<?php echo $course['Course_ID']; ?>" class="box">
                <div class="image shine">
                    <img src="<?php echo $course['Course_Image']; ?>" alt="<?php echo $course['Course_Name']; ?>">
                    <!-- Static content for course difficulty -->
                    <h3><?php echo $course['Course_Difficulty']; ?></h3>
                </div>
                <div class="content">
                    <h4><?php echo $course['Price']; ?></h4>
                    <h3><?php echo $course['Course_Name']; ?></h3>
                    <!-- Static content for rating and reviews -->
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <span>(785)</span>
                    </div>
                    <!-- Static content for lessons -->
                    <div class="icons">
                        <span><i class="far fa-bookmark"></i> 
                      <?php  
                            $sql_lesson_count = "SELECT COUNT(*) AS lesson_count FROM lesson WHERE Course_ID = " . $course['Course_ID'];
                            $result_lesson_count = $con->query($sql_lesson_count);
                                                
                            if ($result_lesson_count->num_rows > 0) {
                                $row_lesson_count = $result_lesson_count->fetch_assoc();
                                echo $row_lesson_count["lesson_count"];
                            } else {
                                echo "0";
                            }
                        ?>
                        </span>
                        <!-- Static content for duration -->
                        <span><i class="far fa-clock"></i> 8h 25m 9s</span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
    
</section>

<?php
include("footer.php");
?>

   
</body>
</html>
