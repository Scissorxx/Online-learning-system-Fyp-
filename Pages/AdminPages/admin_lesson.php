<?php
// Include your database connection file
include '../php/dbconnect.php';

// Initialize variables
$course_id = "";
$course_name = "";
$course_has_lessons = false;
$lessons_count = 0;
$lessons_data = []; // Initialize as an empty array

// Check if form is submitted
if(isset($_POST['search'])) {
    // Sanitize input
    $course_id = mysqli_real_escape_string($con, $_POST['course_id']);

    // Query to check if course exists
    $check_course_sql = "SELECT * FROM courses WHERE Course_ID = '$course_id'";
    $result = mysqli_query($con, $check_course_sql);

    if(mysqli_num_rows($result) > 0) {
        $course = mysqli_fetch_assoc($result);
        $course_name = $course['Course_Name'];

        // Query to check if course has lessons
        $check_lessons_sql = "SELECT * FROM lesson WHERE Course_ID = '$course_id'";
        $lessons_result = mysqli_query($con, $check_lessons_sql);
        $lessons_count = mysqli_num_rows($lessons_result);

        if($lessons_count > 0) {
            $course_has_lessons = true;

            // Fetch lessons data
            while($lesson_row = mysqli_fetch_assoc($lessons_result)) {
                $lessons_data[] = $lesson_row;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../Admin-Css/admin_lessons.css" />
    <link rel="stylesheet" href="../Admin-Css/admin.css">

    <title>Search Course</title>
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
  <div class="content">
  <h1>Search Course</h1>
<form method="post">
    <label for="course_id">Enter Course ID:</label>
    <input type="text" id="course_id" name="course_id" value="<?php echo htmlspecialchars($course_id); ?>">
    <button type="submit" name="search">Search</button>
</form>

<?php if(isset($_POST['search'])): ?>
        <?php if(mysqli_num_rows($result) > 0): ?>
            <h2><?php echo htmlspecialchars($course_name); ?></h2>
            <?php if($course_has_lessons): ?>
                <p>Lessons are available for this course.</p>
                <table>
    <thead>
        <tr>
            <th>Lesson ID</th>
            <th>Lesson Name</th>
            <th>Video</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lessons_data as $lesson): ?>
            <tr>
                <td><?php echo isset($lesson['lesson_ID']) ? htmlspecialchars($lesson['lesson_ID']) : ''; ?></td>
                <td><?php echo isset($lesson['lesson_Name']) ? htmlspecialchars($lesson['lesson_Name']) : ''; ?></td>
                
                <td>
                    <?php if (isset($lesson['lesson_video']) && !empty($lesson['lesson_video'])): ?>
                      <video controls width="200" height="150">
    <source src="<?php echo htmlspecialchars($lesson['lesson_video']); ?>" type="video/mp4">
    Your browser does not support the video tag.
</video>

                        
                    <?php else: ?>
                        No video available
                    <?php endif; ?>
                </td>
                <td><button onclick="editLesson(<?php echo htmlspecialchars($lesson['lesson_ID']); ?>)"  class='edit-btn'>Edit Lesson</button>
                   <button onclick="deleteLesson(<?php echo htmlspecialchars($lesson['lesson_ID']); ?>)"  class='delete-btn'>Delete Lesson</button></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
            <?php else: ?>
                <p>No lessons available for this course.</p>
            <?php endif; ?>
            <button type="button" name="add_lesson" onclick="redirectToAnotherPage()">Add Lesson</button>

        <?php else: ?>
        <p>No such course exists.</p>

    <?php endif; ?>
<?php endif; ?>


  </div>
</section>


<script>
function redirectToAnotherPage() {
// Append course_id and course_name as parameters to the URL
var courseId = "<?php echo htmlspecialchars($course_id); ?>";
var courseName = "<?php echo htmlspecialchars($course_name); ?>";
window.location.href = "add_lesson.php?course_id=" + courseId + "&course_name=" + courseName;
}

function editLesson(lessonId) {
    // Redirect to the edit lesson page with the lesson ID
    window.location.href = 'edit_lesson.php?lesson_id=' + lessonId;
}

function deleteLesson(lessonId) {
    // Redirect to the delete lesson page with the lesson ID
    window.location.href = 'delete_lesson.php?lesson_id=' + lessonId;
}

</script>
</body>
</html>

