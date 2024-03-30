<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../Admin-Css/admin-Courses.css" />
    <link rel="stylesheet" href="../../Css/Admin-Css/admin.css">


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
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

    <title>Admin Courses</title>
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
					<h1>Dashboard</h1>
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

      <div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h2>Manage <b>Courses</b></h2>
                    </div>
                    <div class="col-sm-6">
                    <a href="#addEmployeeModal" class     ="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Course</span></a>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Course ID</th>
                        <th>Course Name</th>
                        <th>Course Cost</th>
                        <th>Lessons</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Include your database connection
                        include '../../php/dbconnect.php';

                        // Variables for pagination
                        $records_per_page = 5;
                        $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $offset = ($current_page - 1) * $records_per_page;

                        // Fetch data from the database with pagination
                        $query = "SELECT * FROM courses LIMIT $offset, $records_per_page";
                        $result = mysqli_query($con, $query);


                     

                        // Check if there are rows returned
                        if (mysqli_num_rows($result) > 0) {
                            // Loop through each row
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['Course_ID'] . "</td>";
                                echo "<td>";
                                if ($row['Course_Image'] !== null) {
                                    echo "<img src='" . htmlspecialchars($row['Course_Image']) . "' alt='Course Image' style='max-width: 100px; max-height: 100px;'>";
                                }
                                echo $row['Course_Name'] . "</td>";
                                echo "<td>" . $row['Price'] . "</td>";
                                echo "<td><a href='view_lessons.php?course_id=" . $row['Course_ID'] . "' class='btn'>View Lessons</a></td>";
                                echo "<td>
                                <a href='edit_Courses.php?id=" . $row['Course_ID'] . "' class='edit' ><i class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i></a>
                                <a href='#deleteCourseModal' class='delete' data-toggle='modal' data-course-name='" . $row['Course_Name'] . "' data-course-id='" . $row['Course_ID'] . "'><i class='material-icons' data-toggle='tooltip' title='Delete'>&#xE872;</i></a>
                                <a href='View_Course.php?id=" . $row['Course_ID'] . "' class='view' ><i class='material-icons' data-toggle='tooltip' title='View'>&#xE417;</i></a>

                            </td>";
                            
                        
                        
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No courses found</td></tr>";
                        }

                        // Close the database connection
                        mysqli_close($con);
                    ?>
                </tbody>
            </table>
            <div class="clearfix">
                <!-- Dynamic Pagination -->
                <?php
                    // Include your database connection
                    include '../../php/dbconnect.php';

                    // Get total number of records
                    $query = "SELECT COUNT(*) AS total_records FROM courses";
                    $result = mysqli_query($con, $query);
                    $row = mysqli_fetch_assoc($result);
                    $total_records = $row['total_records'];

                    // Calculate total pages
                    $total_pages = ceil($total_records / $records_per_page);

                    // Generate pagination links
                    echo "<div class='hint-text'>Showing <b>" . (($current_page - 1) * $records_per_page + 1) . "</b> to <b>" . min($current_page * $records_per_page, $total_records) . "</b> of <b>" . $total_records . "</b> entries</div>";
                    echo "<ul class='pagination'>";
                    if ($current_page > 1) {
                        echo "<li class='page-item'><a href='?page=" . ($current_page - 1) . "' class='page-link'>Previous</a></li>";
                    }
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo "<li class='page-item " . ($i == $current_page ? 'active' : '') . "'><a href='?page=" . $i . "' class='page-link'>" . $i . "</a></li>";
                    }
                    if ($current_page < $total_pages) {
                        echo "<li class='page-item'><a href='?page=" . ($current_page + 1) . "' class='page-link'>Next</a></li>";
                    }
                    echo "</ul>";

                    // Close the database connection
                    mysqli_close($con);
                ?>
            </div>
        </div>
    </div>
</div>



	
		</main>
        </section>

        <div id="addEmployeeModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="course-form" method="POST" enctype="multipart/form-data" action="add_course.php">
                <div class="modal-header">                        
                    <h4 class="modal-title">Add New Course</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
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
                    <label for="course-description">Course Description:</label><br>
                <textarea name="course-description">
                    
                </textarea>
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
    </div>
</div>

<div id="deleteCourseModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="delete-course-form" method="POST" action="delete_course.php">
                <div class="modal-header">                        
                    <h4 class="modal-title">Delete Course</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">                    
    <p>Are you sure you want to delete the course <span id="course-name-to-delete"></span>?</p>
    <p class="text-warning"><small>This action cannot be undone.</small></p>
    <!-- Hidden input field to store the course ID -->
    <input  type="Hidden" id="data-course-id" name="course_id">
</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="deleteCourse()">Delete</button>

                </div>
            </form>
        </div>
    </div>
</div>





<script>
    function deleteCourse() {
        // Retrieve the course ID from the hidden input field in the modal
        var courseId = $('#data-course-id').val();
        
        // Check if the courseId is not empty
        if(courseId) {
            // Append the course ID to the URL
            var url = 'delete_course.php?id=' + encodeURIComponent(courseId);
            
            // Redirect to the deletion script with the course ID appended to the URL
            window.location.href = url;
        } else {
            // If courseId is empty, alert the user
            alert("Course ID is missing.");
        }
    }

    $(document).ready(function() {
        // This function is triggered when the delete button is clicked
        $('.delete').on('click', function() {
            // Get the course name and ID from the clicked delete button
            var courseName = $(this).data('course-name');
            var courseId = $(this).data('course-id');

            // Set the course name in the modal
            $('#course-name-to-delete').text(courseName);

            // Set the course ID in the hidden input field
            $('#data-course-id').val(courseId);

            // Show the delete course modal
            $('#deleteCourseModal').modal('show');
        });
    });
</script>


</script>







    

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


