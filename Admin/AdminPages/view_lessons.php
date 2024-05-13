<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../Admin-Css/view-lessons.css" />

    <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.tiny.cloud/1/ynu5oa33yc3yq0jzt1s9xfpmfic0m7knokyzlvwehwtkkm9c/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdn.tiny.cloud/1/ynu5oa33yc3yq0jzt1s9xfpmfic0m7knokyzlvwehwtkkm9c/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script>
  tinymce.init({
    selector: 'textarea',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount linkchecker',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
  });
</script>

    <title>View Lessons</title>
</head>
<body>

<?php
include("SideBar.php");


include '../../php/dbconnect.php';

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
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="#" href="#">Courses</a>
						</li>
                        <li><i class='bx bx-chevron-right' ></i></li>
                        <li>
							<a class="active" href="#">Lessons</a>
						</li>
					</ul>

				</div>

                
				
			</div>
 <div class="lessons">
                <div class="filters">
                <h2>All Lessons</h2>
                <!-- <a href="add_lesson.php?course_id= -->
                <?php
                //  echo $_GET['course_id']; 
                 ?>
                 <!-- " class="add-btn"> -->
                <div class="col-sm-6">
                    <a href="#addEmployeeModal" class     ="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Lesson</span></a>
                    </div>
        
    </a>
</div>


      

        <div class="row">
    <div class="col-lg-9 mx-auto">
      <!-- Accordion -->
      <div id="accordionExample" class="accordion shadow">

<?php
include '../../php/dbconnect.php';
$course_id = $_GET['course_id'];
$sql = "SELECT * FROM lesson WHERE Course_ID = $course_id";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    $counter = 1;
    while($row = mysqli_fetch_assoc($result)) {
        $collapseId = "collapse" . $counter;
        $headingId = "heading" . $counter;
?>

<div class="card">
    <div id="<?php echo $headingId; ?>" class="card-header bg-white shadow-sm border-0">
        <h6 class="mb-0 font-weight-bold">
            <a href="#" data-toggle="collapse" data-target="#<?php echo $collapseId; ?>" aria-expanded="true" aria-controls="<?php echo $collapseId; ?>" class="d-block position-relative text-dark text-uppercase collapsible-link py-2"><?php echo $row['lesson_Name']; ?></a>
        </h6>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-sm btn-primary mr-2 edit-lesson-btn" data-lesson-id="<?php echo $row['lesson_ID']; ?>">Edit</button>
            <button type="button" class="btn btn-sm btn-danger delete-lesson-btn" data-toggle="modal" data-target="#deleteCourseModal" data-lesson-id="<?php echo $row['lesson_ID']; ?>" data-lesson-name="<?php echo $row['lesson_Name']; ?>">Delete</button>

        </div>
    </div>
    <div id="<?php echo $collapseId; ?>" aria-labelledby="<?php echo $headingId; ?>" data-parent="#accordionExample" class="collapse <?php echo ($counter == 1) ? 'show' : ''; ?>">
        <div class="card-body p-5">
            <h2>Description</h2>
            <p class="font-weight-light m-0"><?php echo $row['lesson_description']; ?></p>
            <hr>
            <h3>Lesson Video</h3>
            <video width='320' height='240' controls>
                <source src='<?php echo $row['lesson_video']; ?>' type='video/mp4'>
                Your browser does not support the video tag.
            </video>
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
    </div>
</div>

<div id="deleteCourseModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="delete-course-form" method="POST" action="delete_course.php">
                <div class="modal-header">                        
                    <h4 class="modal-title">Delete Lesson</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">                    
                    <p>Are you sure you want to delete the lesson <span id="lesson-name"></span> (ID: <span id="lesson-id"></span>)?</p>
                    <p class="text-warning"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a id="delete-lesson-link" href="#" class="btn btn-danger">Delete</a>


                </div>
            </form>
        </div>
    </div>
</div>




        <div id="addEmployeeModal" class="modal fade">
        <div class="modal-dialog" style="max-width: 1000px;">
        <div class="modal-content">
        <div class="modal-header">                        
                    <h4 class="modal-title">Add New Lessons</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <form method="post" enctype="multipart/form-data" action="add_lesson.php">
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
                <button type="submit" name="submit">Add Lesson</button>
            </form>
        </div>
    </div>
</div>

<script>
     // Function to populate lesson details in the modal
     function populateModal(lessonId, lessonName) {
        document.getElementById('lesson-id').textContent = lessonId;
        document.getElementById('lesson-name').textContent = lessonName;
        document.getElementById('delete-lesson-link').setAttribute('href', 'delete_lesson.php?id=' + lessonId);
    }

    // Function to handle delete button click
    document.addEventListener('DOMContentLoaded', function () {
        var deleteButtons = document.querySelectorAll('.delete-lesson-btn');
        deleteButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var lessonId = this.getAttribute('data-lesson-id');
                var lessonName = this.getAttribute('data-lesson-name');
                populateModal(lessonId, lessonName);
            });
        });
    });

 
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        var editLessonButtons = document.querySelectorAll('.edit-lesson-btn');
        editLessonButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var lessonId = this.getAttribute('data-lesson-id');
                window.location.href = 'edit_lesson.php?lesson_id=' + lessonId;
            });
        });
    });
</script>


    <script src="../Script/Admin.js"></script>
           
</body>
</html>
