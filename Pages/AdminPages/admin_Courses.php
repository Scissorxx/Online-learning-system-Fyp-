<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../Css/Admin-Css/admin_Course.css" />
    <link rel="stylesheet" href="../../Css/Admin-Css/admin.css">

    <title>Admin Dashboard</title>
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
            <div class="courses">
                

            <div class="filters">
    <h2>View Courses</h2>
    <a href="add_course.php" class="add-btn">
        <i class="bx bx-plus"></i>Add New Course
    </a>
</div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Course ID</th>
                                <th>Course Name</th>
                                <th>Price</th>
                                <th>Image</th>
                                <th>Lessons</th>
                                <th>Actions</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include '../../php/dbconnect.php';


                            $sql = "SELECT Course_ID, Course_Name, Price, Status, Teacher, Course_Image FROM courses";
                            $result = mysqli_query($con, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['Course_ID'] . "</td>";
                                    echo "<td>" . $row['Course_Name'] . "</td>";
                                    echo "<td>" . $row['Price'] . "</td>";
                                    echo "<td>";
                                    if ($row['Course_Image'] !== null) {
                                      echo "<img src='" . htmlspecialchars($row['Course_Image']) . "' alt='Course Image' style='max-width: 100px; max-height: 100px;'>";
                                    } else {
                                        echo "No Image Available";
                                    }
                                    echo "</td>";

                                    echo "<td><a href='view_lessons.php?course_id=" . $row['Course_ID'] . "' class='view-btn'><i class='bx bx-show'></i> View</a></td>";
                                    

                                    echo "<td><a href='edit_Courses.php?id=" . $row['Course_ID'] . "' class='edit-btn'><i class='bx bx-edit'></i></a> <button onclick='confirmDelete(\"" . $row['Course_Name'] . "\", \"" . $row['Course_ID'] . "\")' class='delete-btn'><i class='bx bx-trash'></i></button></td>";
                                    
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No courses found</td></tr>";
                            }

                            mysqli_close($con);
                            ?>
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>

        <script>
        function confirmDelete(courseName, courseId) {
            if (confirm("Are you sure you want to delete the course '" + courseName + "'?")) {
                window.location.href = "delete_course.php?id=" + courseId;
            }
        }
    </script>
    </section>
    </body>
</html>


