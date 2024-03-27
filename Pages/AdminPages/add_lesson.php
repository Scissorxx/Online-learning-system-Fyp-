<?php
include '../php/dbconnect.php';

// Initialize variables
$course_id = $_GET['course_id'] ?? '';
$lesson_name = '';
$lesson_description = '';
$upload_error = '';

// Retrieve course name from the database
$course_name = '';
if (!empty($course_id)) {
    $query = "SELECT Course_Name FROM courses WHERE Course_ID = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $course_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $course_name);
        mysqli_stmt_fetch($stmt);
    }
    mysqli_stmt_close($stmt);
}

// Handle form submission
if(isset($_POST['submit'])) {
    // Retrieve lesson details
    $lesson_name = $_POST['lesson_name'];
    $lesson_description = $_POST['lesson_description'];

    // Handle video upload
    if ($_FILES['lesson_video']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../lesson_videos/';
        $upload_file = $upload_dir . basename($_FILES['lesson_video']['name']);
        $video_extension = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));
        $allowed_extensions = array('mp4', 'avi', 'wmv', 'mov'); // Define allowed video file extensions
        
        if (in_array($video_extension, $allowed_extensions)) {
            if ($_FILES['lesson_video']['size'] <= 209715200) { // 200MB in bytes
                if (move_uploaded_file($_FILES['lesson_video']['tmp_name'], $upload_file)) {
                    // Insert lesson details into the database
                    $insert_lesson_sql = "INSERT INTO lesson (Course_ID, Course_Name, Lesson_Name, Lesson_Description, Lesson_Video) VALUES (?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($con, $insert_lesson_sql);
                    mysqli_stmt_bind_param($stmt, "sssss", $course_id, $course_name, $lesson_name, $lesson_description, $upload_file);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                } else {
                    $upload_error = "Error uploading file.";
                }
            } else {
                $upload_error = "File size exceeds the maximum allowed limit (200MB).";
            }
        } else {
            $upload_error = "Invalid file format. Please upload a video in MP4, AVI, WMV, or MOV format.";
        }
    } else {
        $upload_error = "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Lesson</title>
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Admin-Css/admin.css">
    <link rel="stylesheet" href="../Admin-Css/add_lessonss.css">
    <script src="https://cdn.tiny.cloud/1/ynu5oa33yc3yq0jzt1s9xfpmfic0m7knokyzlvwehwtkkm9c/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
          { value: 'First.Name', title: 'First Name' },
          { value: 'Email', title: 'Email' },
        ],
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
      });
    </script>
  

</head>
<body>
    <div class="sidebar">
        <div class="logo-details">
          <span class="logo_name">Online learning</span>
        </div>
        <ul class="nav-links">
          <li>
            <a href="admin_dashboard.html">
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

    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class="bx bx-menu sidebarBtn"></i>
                <span class="dashboard">Lesson</span>
            </div>
        </nav>

        <div class="content">
            <h1>Add Lesson</h1>
            <form method="post" enctype="multipart/form-data">
                <label for="course_id">Course ID:</label>
                <input type="text" id="course_id" name="course_id" value="<?= htmlspecialchars($course_id) ?>" readonly><br>
                <label for="course_name">Course Name:</label>
                <input type="text" id="course_name" name="course_name" value="<?= htmlspecialchars($course_name) ?>" readonly><br>
                <label for="lesson_name">Lesson Name:</label>
                <input type="text" id="lesson_name" name="lesson_name" required><br>
                <label for="lesson_description">Lesson Description:</label><br>
                <textarea name="lesson_description">
                    
                </textarea>
                <label for="lesson_video">Lesson Video:</label>
                <input type="file" id="lesson_video" name="lesson_video" required><br>
                <span style="color: red;"><?= $upload_error ?></span><br>
                <button type="submit" name="submit">Add Lesson</button>
            </form>
        </div>
    </section>

    
</body>
</html>
