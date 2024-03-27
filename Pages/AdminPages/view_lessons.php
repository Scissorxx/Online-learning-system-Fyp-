<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../Admin-Css/view_lessons.css" />
    <link rel="stylesheet" href="../Admin-Css/admin.css">
    <link rel="stylesheet" href="style.css"> <!-- Custom CSS for page styling -->

    <title>View Lessons</title>
</head>
<body>
    <div class="sidebar">
        <div class="logo-details">
          <span class="logo_name">Online learning</span>
        </div>
        <ul class="nav-links">
          <li>
            <a href="admin_dashboard.html" >
              <i class="bx bx-grid-alt"></i>
              <span class="links_name">Dashboard</span>
            </a>
          </li>
          <li>
            <a href="admin_Courses.php" class="active">
              <i class="bx bx-box"></i>
              <span class="links_name">Courses</span>
            </a>
          </li>
        
          <li>
            <a href="#">
              <i class="bx bx-book-alt"></i>
              <span class="links_name">Books</span>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="bx bx-user"></i>
              <span class="links_name">Students</span>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="bx bx-user"></i>
              <span class="links_name">Teacher</span>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="bx bx-message"></i>
              <span class="links_name">Messages</span>
            </a>
          </li>
          <li>

            <a href="#">
              <i class="bx bx-cog"></i>
              <span class="links_name">Settings</span>
            </a>
          </li>
          <li class="log_out">
            <a href="#">
              <i class="bx bx-log-out"></i>
              <span class="links_name">Log out</span>
            </a>
          </li>
        </ul>
      </div>

     <!-- Inside the home-section -->
<section class="home-section">
    <nav>
        <div class="sidebar-button">
            <i class="bx bx-menu sidebarBtn"></i>
            <span class="dashboard">Course</span>
        </div>
    </nav>

    <div class="content">
            <div class="lessons">
                <div class="filters">
                <h2>All Lessons</h2>
                <a href="add_lesson.php?course_id=<?php echo $_GET['course_id']; ?>" class="add-btn">
        <i class="bx bx-plus"></i>Add New lesson
    </a>
</div>
<div class="lesson-list">
                    <ul>
                    <?php
                        include '../php/dbconnect.php';
                        $course_id = $_GET['course_id'];
                        $sql = "SELECT * FROM lesson WHERE Course_ID = $course_id";
                        $result = mysqli_query($con, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<li>";
                                echo "<h3>" . $row['lesson_Name'] . "</h3>";
                                echo "<p>" . $row['lesson_description'] . "</p>";
                                echo "<video width='320' height='240' controls>";
                                echo "<source src='" . $row['lesson_video'] . "' type='video/mp4'>";
                                echo "Your browser does not support the video tag.";
                                echo "</video>";
                               
                                echo "</li>";
                            }
                        } else {
                            echo "<li>No lessons found for this course.</li>";
                        }

                        mysqli_close($con);
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
