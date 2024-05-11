<?php
include '../../php/dbconnect.php';

// Fetch student details based on the provided student ID
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $student_id = $_GET['id'];
    $sql = "SELECT * FROM userdetail WHERE SN = '$student_id'";
    $result = mysqli_query($con, $sql);

    if(mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);
    } else {
        echo "Student not found!";
        exit;
    }
} else {
    echo "Invalid student ID!";
    exit;
}

// Update student details in the database after form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $number = $_POST['number'];
    $email = $_POST['email'];

    // Update the password if changed
    // if (!empty($_POST['password'])) {
    //     $password = base64_encode($_POST['password']);
    //     $sql .= ", password='$password'";
    // }

    // Update the student details in the database
    $sql = "UPDATE userdetail SET fullname='$fullname', username='$username', number='$number', email='$email' WHERE SN='$student_id'";

    if (mysqli_query($con, $sql)) {
        // If the student is updated successfully, output JavaScript code to display the modal
        echo "<script>";
        echo "document.addEventListener('DOMContentLoaded', function() {";
        echo "    $('#studentUpdatedModal').modal('show');"; // Show the student updated modal
        echo "});";
        echo "</script>";
    } else {
        echo "Error updating student details: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <!-- Include necessary CSS and JavaScript libraries -->
<style>
    /* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
}

.container {
    max-width: 600px;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
}

h1 {
    font-size: 28px;
    color: #333;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    font-weight: bold;
    color: #555;
}

input[type="text"],
input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    transition: border-color 0.3s ease-in-out;
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus {
    outline: none;
    border-color: #007bff;
}

button[type="submit"] {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    background-color: #007bff;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

/* Modal Styles */
.modal-content {
    border-radius: 10px;
}

.modal-header {
    background-color: #007bff;
    color: #fff;
    border-radius: 10px 10px 0 0;
}

.modal-title {
    font-size: 24px;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    border-radius: 0 0 10px 10px;
}

/* Responsive Styles */
@media screen and (max-width: 768px) {
    .container {
        margin: 20px;
        padding: 10px;
    }
}

@media screen and (max-width: 576px) {
    h1 {
        font-size: 24px;
    }

    button[type="submit"] {
        font-size: 14px;
    }
}

</style>
    <!-- Your CSS styles -->
    <link rel="stylesheet" href="../Admin-Css/admin-Courses.css" />
    <link rel="stylesheet" href="../../Css/Admin-Css/admin.css">
    <link rel="stylesheet" href="../Admin-Css/edit_Students.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

   
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
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

    <div class="container">
        <h1>Edit Student</h1>
        <form id="student-form" method="POST">
            <!-- Form fields for editing student details -->
            <!-- Example: -->
            <div class="form-group">
                <label for="fullname">Full Name:</label>
                <input type="text" id="fullname" name="fullname" value="<?php echo $student['fullname']; ?>" required>
            </div>

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $student['username']; ?>" required>
            </div>

            <div class="form-group">
                <label for="number">Contact Number:</label>
                <input type="text" id="number" name="number" value="<?php echo $student['number']; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $student['email']; ?>" required>
            </div>

            <!-- <div class="form-group">
                <label for="password">Password:</label>
                <div class="password-field">
                    <input type="password" id="password" name="password" value="<?php echo base64_decode($student['password']); ?>" required>
                    <span class="toggle-password" onclick="togglePasswordVisibility()"><i class="fa fa-eye" aria-hidden="true"></i></span>
                </div>
            </div> -->

            <button type="submit">Update Student</button>
        </form>
    </div>

    <!-- Modal for showing success message -->
    <div id="studentUpdatedModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Student Updated Successfully</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Success message -->
                    <p>Student details have been updated successfully!</p>
                </div>
                <div class="modal-footer">
                    <a href="Admin_Student.php" class="btn btn-default">OK</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Your JavaScript code for handling form submission and other interactions -->
    <script>
    // function togglePasswordVisibility() {
    //     var passwordField = document.getElementById("password");
    //     var toggleButton = document.querySelector(".toggle-password i");

    //     if (passwordField.type === "password") {
    //         passwordField.type = "text";
    //         toggleButton.classList.remove("fa-eye");
    //         toggleButton.classList.add("fa-eye-slash");
    //     } else {
    //         passwordField.type = "password";
    //         toggleButton.classList.remove("fa-eye-slash");
    //         toggleButton.classList.add("fa-eye");
    //     }
    // }
    </script>
</body>
</html>
