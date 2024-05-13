<?php
include '../../php/dbconnect.php';

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
    


// Check if there's an existing image for the course
if ($_FILES["course-image"]["error"] == UPLOAD_ERR_OK) {
    $target_dir = "../../Media/Courses_Thumnail/";
    $target_file = $target_dir . basename($_FILES["course-image"]["name"]);

    // Check if there's an existing image for the course
    if (!empty($course['Course_Image']) && file_exists($course['Course_Image'])) {
        unlink($course['Course_Image']);
    }

    if (move_uploaded_file($_FILES["course-image"]["tmp_name"], $target_file)) {
        $image_updated = true;
    } else {
        echo "Error uploading the new image.";
        exit;
    }
} else {
    // No new image uploaded, no need to change the existing image
    $target_file = $course['Course_Image'];
}

// Update the course details in the database
$sql = "UPDATE courses SET Course_Name='$course_name', Course_Duration='$course_duration', Course_Difficulty='$course_difficulty', Course_Description='$course_description', Price='$course_price_type', Cost='$course_price', LiveClass='$course_live_classes', Course_Image='$target_file', Teacher='$teacher_id' WHERE Course_ID='$course_id'";

if (mysqli_query($con, $sql)) {
    // If the course is updated successfully, output JavaScript code to display the modal
    echo "<script>";
    echo "document.addEventListener('DOMContentLoaded', function() {";
    echo "    $('#lessonUpdatedModal').modal('show');"; // Show the course updated modal
    echo "});";
    echo "</script>";
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
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../Admin-Css/edit_Couses.css">

    <link rel="stylesheet" href="../Admin-Css/admin-Courses.css" />
    <link rel="stylesheet" href="../../Css/Admin-Css/admin.css">


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.tiny.cloud/1/ynu5oa33yc3yq0jzt1s9xfpmfic0m7knokyzlvwehwtkkm9c/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
  tinymce.init({
    selector: 'textarea',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount linkchecker',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
  });
</script>
</head>
<body>

<?php
     include("SideBar.php");
    ?>
    
      <section id="content">
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link">Categories</a>
			
			<input type="checkbox" id="switch-mode" hidden>
			
			<a href="#" class="profile">
				<img src="../../Media/Default/default.jpg">
			</a>
		</nav>

        <main>
			<div class="head-title">
				<div class="left">
					<h1>Course</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Courses</a>
						</li>
					</ul>
				</div>
				
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
    <label for="teacher">Teacher:</label>
    <?php if (!empty($course['Teacher'])): ?>
        <p>The currently appointed teacher is: <?php echo $course['Teacher']; ?></p>
        <p>If you want to appoint another teacher, select from the options below:</p>
    <?php endif; ?>
    <select id="teacher" name="teacher">
        <?php if (empty($course['Teacher'])): ?>
            <option value="">Select Teacher</option>
        <?php endif; ?>
        <?php foreach($teachers as $teacher): ?>
            <option value="<?php echo $teacher['fullname']; ?>"><?php echo $teacher['fullname']; ?></option>
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
    <div id="lessonUpdatedModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Course Updated Successfully</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Message or content to display when lesson is updated successfully -->
                <p>Your Course has been updated successfully!</p>
            </div>
            <div class="modal-footer">
                <a href="admin_Courses.php" class="btn btn-default">okay</a>
            </div>
        </div>
    </div>
</div>


    </main>
        </section>
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
    <script src="../Script/Admin.js"></script>

</body>
</html>
