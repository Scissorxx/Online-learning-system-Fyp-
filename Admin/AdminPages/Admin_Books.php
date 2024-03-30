<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Books</title>
    <!-- CSS Links -->
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../Admin-Css/admin-Courses.css" />
    <link rel="stylesheet" href="../../Css/Admin-Css/admin.css">
</head>
<body>
<?php
    include("SideBar.php");
?>
<section id="content">
    <nav>
        <i class='bx bx-menu'></i>
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
                    <li><a href="#">Dashboard</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a class="active" href="#">Books</a></li>
                </ul>
            </div>
        </div>
        <div class="container-xl">
            <div class="table-responsive">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <h2>Manage <b>Books</b></h2>
                            </div>
                            <div class="col-sm-6">
                                <a href="#addBookModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New Book</span></a>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Book ID</th>
                                <th>Title</th>
                                <th>Authors</th>
                                <th>Description</th>
                                <th>Publisher</th>
                                <th>Edition</th>
                                <th>Price</th>
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
                                $query = "SELECT * FROM books LIMIT $offset, $records_per_page";
                                $result = mysqli_query($con, $query);

                                // Check if there are rows returned
                                if (mysqli_num_rows($result) > 0) {
                                    // Loop through each row
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $row['BookID'] . "</td>";
                                        echo "<td>" . $row['Title'] . "</td>";
                                        echo "<td>" . $row['Authors'] . "</td>";
                                        echo "<td>" . $row['Description'] . "</td>";
                                        echo "<td>" . $row['Publisher'] . "</td>";
                                        echo "<td>" . $row['Edition'] . "</td>";
                                        echo "<td>" . $row['Price'] . "</td>";
                                        echo "<td>
                                            <a href='edit_book.php?id=" . $row['BookID'] . "' class='edit'><i class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i></a>
                                            <a href='#deleteBookModal' class='delete' data-toggle='modal' data-book-id='" . $row['BookID'] . "'><i class='material-icons' data-toggle='tooltip' title='Delete'>&#xE872;</i></a>
                                        </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>No books found</td></tr>";
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
                            $query = "SELECT COUNT(*) AS total_records FROM books";
                            $result = mysqli_query($con, $query);
                            $row = mysqli_fetch_assoc($result);
                            $total_records = $row['total_records'];

                            // Calculate total pages
                            $total_pages = ceil($total_records / $records_per_page);

                            // Generate pagination links
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

<!-- Add Book Modal -->
<div id="addBookModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="add_book.php">
                <div class="modal-header">                        
                    <h4 class="modal-title">Add New Book</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="bookID">BookID:</label>
                        <input type="text" class="form-control" id="bookID" name="bookID">
                    </div>
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" id="title" name="title">
                    </div>
                    <div class="form-group">
                        <label for="authors">Authors:</label>
                        <input type="text" class="form-control" id="authors" name="authors">
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="publisher">Publisher:</label>
                        <input type="text" class="form-control" id="publisher" name="publisher">
                    </div>
                    <div class="form-group">
                        <label for="edition">Edition:</label>
                        <input type="text" class="form-control" id="edition" name="edition">
                    </div>
                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="text" class="form-control" id="price" name="price">
                    </div>
                    <div class="form-group">
                        <label for="course">Course:</label>
                        <input type="text" class="form-control" id="course" name="course">
                    </div>
                    <div class="form-group">
                        <label for="image">Book Image:</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Book</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Delete Book Modal -->
<div id="deleteBookModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="delete_book.php">
                <div class="modal-header">                        
                    <h4 class="modal-title">Delete Book</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">                    
                    <p>Are you sure you want to delete this book?</p>
                    <!-- Hidden input field to store the book ID -->
                    <input type="hidden" name="book_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript Links -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.tiny.cloud/1/ynu5oa33yc3yq0jzt1s9xfpmfic0m7knokyzlvwehwtkkm9c/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="../Script/Admin.js"></script>
<script>
    $(document).ready(function() {
        // This function is triggered when the delete button is clicked
        $('.delete').on('click', function() {
            // Get the book ID from the clicked delete button
            var bookId = $(this).data('book-id');

            // Set the book ID in the hidden input field inside the delete modal
            $('#deleteBookModal input[name=book_id]').val(bookId);

            // Show the delete book modal
            $('#deleteBookModal').modal('show');
        });
    });
</script>
</body>
</html>
