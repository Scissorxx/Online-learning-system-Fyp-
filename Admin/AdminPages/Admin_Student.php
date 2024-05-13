<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../Admin-Css/admin-Courses.css" />
    <link rel="stylesheet" href="../../Css/Admin-Css/admin.css">
<style>
    /* Modal Header */
.modal-header {
    background-color: #007bff;
    color: #fff;
    border-bottom: 1px solid #dee2e6;
    padding: 15px;
}

/* Modal Body */
.modal-body {
    padding: 20px;
}

/* Modal Footer */
.modal-footer {
    border-top: 1px solid #dee2e6;
    padding: 15px;
}

/* Form Styles */
.form-group {
    margin-bottom: 20px;
}

label {
    font-weight: 600;
}

input[type="text"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Button Styles */
.btn-default {
    color: #212529;
    background-color: #f8f9fa;
    border-color: #f8f9fa;
}

.btn-default:hover {
    color: #212529;
    background-color: #e2e6ea;
    border-color: #dae0e5;
}

.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    color: #fff;
    background-color: #0056b3;
    border-color: #0056b3;
}

</style>

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
					<h1>Students</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Students</a>
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
                        <h2>Manage <b>Students</b></h2>
                    </div>
                    <div class="col-sm-6">
                    <a href="#addStudentModal" class     ="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Student</span></a>
                    </div>
                </div>
            </div>
			<table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Full Name</th>
                                <th>Username</th>
                                <th>Contact Number</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // Include your database connection
                                include '../../php/dbconnect.php';

                                // Fetch students from the database
                                $query = "SELECT * FROM userdetail WHERE user_type='Student'";
                                $result = mysqli_query($con, $query);

                                // Check if there are rows returned
                                if (mysqli_num_rows($result) > 0) {
                                    // Loop through each row
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $row['SN'] . "</td>";
                                        echo "<td>" . $row['fullname'] . "</td>";
                                        echo "<td>" . $row['username'] . "</td>";
                                        echo "<td>" . $row['number'] . "</td>";
                                        echo "<td>" . $row['email'] . "</td>";
                                        echo "<td>
                                                <a href='edit_student.php?id=" . $row['SN'] . "' class='edit'><i class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i></a>
                                                <a href='#' class='delete' data-student-name='" . $row['fullname'] . "' data-student-id='" . $row['SN'] . "'><i class='material-icons' data-toggle='tooltip' title='Delete'>&#xE872;</i></a>                                                 <a href='view_student.php?id=" . $row['SN'] . "' class='view'><i class='material-icons' data-toggle='tooltip' title='View'>&#xE417;</i></a>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>No students found</td></tr>";
                                }

                                // Close the database connection
                                mysqli_close($con);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="clearfix">
            <!-- Dynamic Pagination -->
            <?php
                // Include your database connection
                include '../../php/dbconnect.php';

                // Get total number of records
                $query = "SELECT COUNT(*) AS total_records FROM userdetail WHERE user_type='Teacher'";
                $result = mysqli_query($con, $query);
                $row = mysqli_fetch_assoc($result);
                $total_records = $row['total_records'];
				$records_per_page = 5;
				$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
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
    </main>
</section>

<div id="addStudentModal" class="modal fade">
<div class="modal-dialog" style="max-width: 1000px;">
        <div class="modal-content">
            <form id="add-student-form" method="POST" action="add_student.php">
                <div class="modal-header">                        
                    <h4 class="modal-title">Add New Student</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="fullname">Full Name:</label>
                        <input type="text" id="fullname" name="fullname" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="number">Contact Number:</label>
                        <input type="text" id="number" name="number" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirm Password:</label>
                        <input type="password" id="confirm-password" name="confirm-password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
$(document).on('click', '.delete', function(e) {
    e.preventDefault();
    var studentName = $(this).data('student-name');
    var studentId = $(this).data('student-id');

    Swal.fire({
        title: 'Are you sure?',
        text: "You are about to delete '" + studentName + "'.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // If user confirms deletion, send a request to delete the student
            var url = 'delete_student.php?id=' + encodeURIComponent(studentId);
            $.ajax({
                type: 'POST',
                url: url,
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Student deleted successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // Reload the page
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to delete student. Please try again later.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
});
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


