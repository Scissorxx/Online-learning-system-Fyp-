<?php
include '../php/dbconnect.php';

// Initialize variables
$course_id = $_GET['course_id'] ?? '';
$course_name = $_GET['course_name'] ?? '';
$lesson_name = '';
$lesson_description = '';
$lesson_video = '';
$upload_error = '';

// Handle form submission
if(isset($_POST['submit'])) {
    // Retrieve lesson details
    $lesson_name = mysqli_real_escape_string($con, $_POST['lesson_name']);
    $lesson_description = mysqli_real_escape_string($con, $_POST['lesson_description']);

    // Handle video upload
    $upload_dir = '../lesson_videos/';
    $upload_file = $upload_dir . basename($_FILES['lesson_video']['name']);
    if(move_uploaded_file($_FILES['lesson_video']['tmp_name'], $upload_file)) {
        $lesson_video = $upload_file;
    } else {
        $upload_error = "Error uploading file.";
    }

    // Insert lesson details into the database
    $insert_lesson_sql = "INSERT INTO lesson (Course_ID, Course_Name, Lesson_Name, Lesson_Description, Lesson_Video) VALUES ('$course_id', '$course_name', '$lesson_name', '$lesson_description', '$lesson_video')";
    mysqli_query($con, $insert_lesson_sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../Admin-Css/admin.css">
    <link rel="stylesheet" href="../Admin-Css/add_lesson.css">
    <title>Add Lesson</title>
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
        <a href="admin_Courses.php" >
          <i class="bx bx-box"></i>
          <span class="links_name">Courses</span>
        </a>
      </li>
      <li>
        <a href="admin_lesson.php" class="active">
          <i class="bx bx-list-ul"></i>
          <span class="links_name">Lessons</span>
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
          <i class="bx bx-heart"></i>
          <span class="links_name">Favorites</span>
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

  <section class="home-section">
<nav>
    <div class="sidebar-button">
        <i class="bx bx-menu sidebarBtn"></i>
        <span class="dashboard">Lesson</span>
    </div>
</nav>


    <h1>Add Lesson</h1>
    <form method="post" enctype="multipart/form-data">
        <h1>Add Lesson</h1>
        <label for="course_id">Course ID:</label>
        <input type="text" id="course_id" name="course_id" value="<?php echo htmlspecialchars($course_id); ?>" readonly><br>
        <label for="course_name">Course Name:</label>
        <input type="text" id="course_name" name="course_name" value="<?php echo htmlspecialchars($course_name); ?>" readonly><br>
        <label for="lesson_name">Lesson Name:</label>
        <input type="text" id="lesson_name" name="lesson_name"><br>
        <label for="lesson_description">Lesson Description:</label><br>
        <textarea id="lesson_description" name="lesson_description"></textarea><br>
        <label for="lesson_video">Lesson Video:</label>
        <input type="file" id="lesson_video" name="lesson_video"><br>
        <span style="color: red;"><?php echo $upload_error; ?></span><br>
        <button type="submit" name="submit">Add Lesson</button>
    </form>
</body>
</html>
