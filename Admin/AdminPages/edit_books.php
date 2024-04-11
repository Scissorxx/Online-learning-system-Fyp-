<?php
include '../../php/dbconnect.php';

// Fetch book details based on the provided book ID
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $book_id = $_GET['id'];
    $sql = "SELECT * FROM books WHERE BookID = '$book_id'";
    $result = mysqli_query($con, $sql);

    if(mysqli_num_rows($result) > 0) {
        $book = mysqli_fetch_assoc($result);
    } else {
        echo "Book not found!";
        exit;
    }
} else {
    echo "Invalid book ID!";
    exit;
}

// Update book details in the database after form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $authors = $_POST['authors'];
    $description = $_POST['description'];
    $publisher = $_POST['publisher'];
    $edition = $_POST['edition'];
    $price = $_POST['price'];
    $course = $_POST['course'];

    // Handling the image upload
    if ($_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = "../../Media/Books_Images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);

        // Check if there's an existing image for the book
        if (!empty($book['image']) && file_exists($book['image'])) {
            unlink($book['image']);
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_updated = true;
        } else {
            echo "Error uploading the new image.";
            exit;
        }
    } else {
        // No new image uploaded, no need to change the existing image
        $target_file = $book['image'];
    }

    // Update the book details in the database
    $sql = "UPDATE books SET Title='$title', Authors='$authors', Description='$description', Publisher='$publisher', Edition='$edition', Price='$price', CourseName='$course',image='$target_file' WHERE BookID='$book_id'";

    if (mysqli_query($con, $sql)) {
        // If the book is updated successfully, output JavaScript code to display the modal
        echo "<script>";
        echo "document.addEventListener('DOMContentLoaded', function() {";
        echo "    $('#bookUpdatedModal').modal('show');"; // Show the book updated modal
        echo "});";
        echo "</script>";
    } else {
        echo "Error updating book details: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <!-- Include necessary CSS and JavaScript libraries -->

    <!-- Your CSS styles -->
    <link rel="stylesheet" href="../Admin-Css/admin-Courses.css" />
    <link rel="stylesheet" href="../../Css/Admin-Css/admin.css">
    <link rel="stylesheet" href="../Admin-Css/edit_Couses.css">


   
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
					<h1>Books</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Books</a>
						</li>
					</ul>
				</div>
				
			</div>

    <div class="container">
        <h1>Edit Book</h1>
        <form id="book-form" method="POST" enctype="multipart/form-data">
            <!-- Form fields for editing book details -->
            <!-- Example: -->
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo $book['Title']; ?>" required>
            </div>
           <!-- Other form fields for book details -->

<div class="form-group">
    <label for="authors">Authors:</label>
    <input type="text" id="authors" name="authors" value="<?php echo $book['Authors']; ?>" required>
</div>

<div class="form-group">
    <label for="description">Description:</label>
    <textarea id="description" name="description" required><?php echo $book['Description']; ?></textarea>
</div>

<div class="form-group">
    <label for="publisher">Publisher:</label>
    <input type="text" id="publisher" name="publisher" value="<?php echo $book['Publisher']; ?>" required>
</div>

<div class="form-group">
    <label for="edition">Edition:</label>
    <input type="text" id="edition" name="edition" value="<?php echo $book['Edition']; ?>" required>
</div>

<div class="form-group">
    <label for="price">Price:</label>
    <input type="text" id="price" name="price" value="<?php echo $book['Price']; ?>" required>
</div>

<div class="form-group">
    <label for="course">Course:</label>
    <select id="course" name="course" required>
        <option value="">Select Course</option>
        <?php
            // Fetch courses from the database
            $sql = "SELECT course_Name FROM Courses";
            $result = mysqli_query($con, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Check if the current course matches the book's course
                    $selected = ($row['course_Name'] == $book['CourseName']) ? 'selected' : '';
                    echo '<option value="' . $row['course_Name'] . '" ' . $selected . '>' . $row['course_Name'] . '</option>';
                }
            }
        ?>
    </select>
</div>


<div class="form-group">
    <label for="image">Book Image:</label>
    <input type="file" id="image" name="image" accept="image/*">
    <?php if (!empty($book['image'])): ?>
        <img src="<?php echo $book['image']; ?>" alt="Book Image" style="max-width: 200px; margin-top: 10px;">
    <?php endif; ?>
</div>


            <button type="submit">Update Book</button>
        </form>
    </div>

    <!-- Modal for showing success message -->
    <div id="bookUpdatedModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Book Updated Successfully</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Success message -->
                    <p>Your book has been updated successfully!</p>
                </div>
                <div class="modal-footer">
                    <a href="admin_Books.php" class="btn btn-default">OK</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Your JavaScript code for handling form submission and other interactions -->
    <!-- Example: -->
    <script>
    </script>
</body>
</html>
