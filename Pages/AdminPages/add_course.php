<?php
    include '../../php/dbconnect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $course_name = $_POST['course-name'];
        $course_duration = $_POST['course-duration'];
        $course_difficulty = $_POST['course-difficulty'];
        $course_description = $_POST['course-description'];
        $course_price_type = $_POST['course-price']; // Free or Paid
        $course_price = ($course_price_type == 'paid') ? $_POST['course-price-input'] : '0'; // If course is paid, get the price
        $course_live_classes = $_POST['live-classes'];

        // Handling the image upload
        $target_dir = "../../Media/Courses_Thumnail/";
        $target_file = $target_dir . basename($_FILES["course-image"]["name"]);
        move_uploaded_file($_FILES["course-image"]["tmp_name"], $target_file);

        // Inserting into the database
        $sql = "INSERT INTO courses (Course_Name, Course_Duration, Course_Difficulty, Course_Description, Price, Cost,LiveClass, Course_Image) VALUES ('$course_name', '$course_duration', '$course_difficulty', '$course_description','$course_price_type', '$course_price', '$course_live_classes', '$target_file')";
        
        if (mysqli_query($con, $sql)) {
          echo "<script>alert('New Course added successfully!!');</script>";
          echo "<script>window.location = 'admin_Courses.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($con);
        }

        mysqli_close($con);
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Course</title>
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../Css/Admin-Css/admin.css">
    <link rel="stylesheet" href="../../Css/Admin-Css/add_course.css">

    
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
      <div class="container">
        <h1>Add New Course</h1>
        <form id="course-form" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="course-name">Course Name:</label>
                <input type="text" id="course-name" name="course-name" required>
            </div>
            <div class="form-group">
                <label for="course-duration">Course Duration:</label>
                <input type="text" id="course-duration" name="course-duration" required>
            </div>
            <div class="form-group">
                <label for="course-difficulty">Course Difficulty:</label>
                <select id="course-difficulty" name="course-difficulty" required>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
            </div>
            <div class="form-group">
                <label for="course-description">Course Description:</label>
                <textarea id="course-description" name="course-description" required></textarea>
            </div>
            <div class="form-group">
                <label for="course-image">Course Image:</label>
                <input type="file" id="course-image" name="course-image" accept="image/*" >
            </div>
            <div class="form-group">
                <label for="course-price">Price:</label>
                <select id="course-price" name="course-price" required>
                    <option value="free">Free</option>
                    <option value="paid">Paid</option>
                </select>
            </div>
            <div class="form-group" id="price-input" style="display: none;">
                <label for="course-price-input">Enter Price:</label>
                <input type="number" id="course-price-input" name="course-price-input">
            </div>
            <div class="form-group">
                <label for="live-classes">Live Classes:</label>
                <select id="live-classes" name="live-classes" required>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>

    <script>
        document.getElementById('course-price').addEventListener('change', function() {
    var priceInput = document.getElementById('price-input');
    if (this.value === 'paid') {
        priceInput.style.display = 'block';
        document.getElementById('course-price-input').required = true;
    } else {
        priceInput.style.display = 'none';
        document.getElementById('course-price-input').required = false;
    }
});

    </script>
</body>
</html>