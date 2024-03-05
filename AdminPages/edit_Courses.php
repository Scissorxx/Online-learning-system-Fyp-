<?php
include '../php/dbconnect.php';

// Fetch course details based on the provided course ID
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $course_id = $_GET['id'];
    $sql = "SELECT * FROM courses WHERE Course_ID = '$course_id'";
    $result = mysqli_query($con, $sql);

    if(mysqli_num_rows($result) > 0) {
        $course = mysqli_fetch_assoc($result);
    } else {
        echo "Course not found!";
        exit;
    }
} else {
    echo "Invalid course ID!";
    exit;
}

// Fetch available teachers
$sql = "SELECT * FROM userdetail WHERE user_type = 'Teacher'";
$teacher_result = mysqli_query($con, $sql);
$teachers = mysqli_fetch_all($teacher_result, MYSQLI_ASSOC);


// Update course details in the database after form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = $_POST['course-name'];
    $course_duration = $_POST['course-duration'];
    $course_difficulty = $_POST['course-difficulty'];
    $course_description = $_POST['course-description'];
    $course_price_type = $_POST['course-price'];
    $course_live_classes = $_POST['live-classes'];
    $course_price = ($course_price_type == 'paid') ? $_POST['course-price-input'] : '0'; // If course is paid, get the price
    $teacher_id = $_POST['teacher'];

    // Handling the image upload
    $target_dir = "../Course_image/";
    $target_file = $target_dir . basename($_FILES["course-image"]["name"]);
    move_uploaded_file($_FILES["course-image"]["tmp_name"], $target_file);

    // Update the course details in the database
    $sql = "UPDATE courses SET Course_Name='$course_name', Course_Duration='$course_duration', Course_Difficulty='$course_difficulty', Course_Description='$course_description', Price='$course_price_type', Cost='$course_price', LiveClass='$course_live_classes', Course_Image='$target_file', Teacher='$teacher_id' WHERE Course_ID='$course_id'";

    if (mysqli_query($con, $sql)) {
        echo "Course details updated successfully";
    } else {
        echo "Error updating course details: " . mysqli_error($con);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <link rel="stylesheet" href="../Admin-Css/admin_Courses.css">
    <link rel="stylesheet" href="../Admin-Css/edit_Couses.css">
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
            <a href="#" class="active">
              <i class="bx bx-box"></i>
              <span class="links_name">Courses</span>
            </a>
          </li>
          <li>
            <a href="#">
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


      
    <div class="container">
        <h1>Edit Course</h1>
        <form id="course-form" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="course-name">Course Name:</label>
                <input type="text" id="course-name" name="course-name" value="<?php echo $course['Course_Name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="course-duration">Course Duration:</label>
                <input type="text" id="course-duration" name="course-duration" value="<?php echo $course['Course_Duration']; ?>" required>
            </div>
            <div class="form-group">
                <label for="course-difficulty">Course Difficulty:</label>
                <select id="course-difficulty" name="course-difficulty" required>
                    <option value="beginner" <?php if($course['Course_Difficulty'] == 'beginner') echo 'selected'; ?>>Beginner</option>
                    <option value="intermediate" <?php if($course['Course_Difficulty'] == 'intermediate') echo 'selected'; ?>>Intermediate</option>
                    <option value="advanced" <?php if($course['Course_Difficulty'] == 'advanced') echo 'selected'; ?>>Advanced</option>
                </select>
            </div>
            <div class="form-group">
                <label for="course-description">Course Description:</label>
                <textarea id="course-description" name="course-description" required><?php echo $course['Course_Description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="teacher">Select Teacher:</label>
                <select id="teacher" name="teacher" required>
                    <option value="">Select Teacher</option>
                    <?php foreach($teachers as $teacher): ?>
                        <option value="<?php echo $teacher['fullname']; ?>" <?php if($teacher['fullname'] == $course['Teacher']) echo 'selected'; ?>><?php echo $teacher['fullname']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
    <label for="course-image">Course Image:</label>
    <input type="file" id="course-image" name="course-image" accept="image/*">
    <?php if (!empty($course['Course_Image'])): ?>
        <img src="<?php echo $course['Course_Image']; ?>" alt="Course Image" style="max-width: 200px; margin-top: 10px;">
    <?php endif; ?>
</div>
            <div class="form-group">
                <label for="course-price">Price:</label>
                <select id="course-price" name="course-price" required>
                    <option value="free" <?php if($course['Price'] == 'free') echo 'selected'; ?>>Free</option>
                    <option value="paid" <?php if($course['Price'] == 'paid') echo 'selected'; ?>>Paid</option>
                </select>
            </div>
            <div class="form-group" id="price-input" style="<?php if($course['Price'] == 'free') echo 'display: none;'; ?>">
                <label for="course-price-input">Enter Price:</label>
                <input type="number" id="course-price-input" name="course-price-input" value="<?php echo $course['Cost']; ?>">
            </div>
            <div class="form-group">
                <label for="live-classes">Live Classes:</label>
                <select id="live-classes" name="live-classes" required>
                    <option value="yes" <?php if($course['LiveClass'] == 'yes') echo 'selected'; ?>>Yes</option>
                    <option value="no" <?php if($course['LiveClass'] == 'no') echo 'selected'; ?>>No</option>
                </select>
            </div>
            <button type="submit">Update Course</button>
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
