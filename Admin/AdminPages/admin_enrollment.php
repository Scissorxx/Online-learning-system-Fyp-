<?php
    // Include your database connection file
    include '../../php/dbconnect.php';

    $query = "SELECT e.*, u.SN, u.fullname, u.Image
              FROM enrollment e
              JOIN userdetail u ON e.user_id = u.SN
              JOIN courses c ON e.course_id = c.course_id";
    $result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="../Admin-Css/admin_payment.css">
    <style> 
       .avatar {
            width: 50px;
            height: auto;
            border-radius: 50%;
        }
    </style>
    <title>Admin Dashboard</title>
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
                    <h1>Enrollment</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right' ></i></li>
                        <li>
                            <a class="active" href="#">Enrollment</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="container-xl">
                <div class="table-responsive">
                    <div class="table-wrapper">
                        <div class="table-title">
                            <div class="row">
                                <div class="col-sm-5">
                                    <h2>Enrollment <b></b></h2>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Profile</th> <!-- Changed from Enrollment ID -->
                                    <th>Name</th>
                                    <th>Course ID</th>
                                    <th>Course Name</th>
                                    <th>Enrollment Date</th>
                                    <th>View Progress</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $counter = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>".$counter++."</td>";
                                        echo "<td><img src='../../Media/UserImages/".$row['Image']."' class='avatar'></td>"; // Displaying user's profile image
                                        echo "<td>".$row['fullname']."</td>";
                                        echo "<td>".$row['course_id']."</td>";
                                        echo "<td>".$row['course_name']."</td>";
                                        echo "<td>".$row['enrollment_date']."</td>";
                                        echo "<td><a class='btn btn-primary view-progress' href='view_progress.php?user_id=".$row['SN']."&course_id=".$row['course_id']."'>View Progress</a></td>";

                                        echo "<td>".$row['userprogress']."</td>";
                                        echo "<td><a href='#' class='delete-enrollment' data-id='".$row['enrollment_id']."'><i class='fa fa-trash'></i></a></td>"; // Delete icon with data-id attribute
                                        echo "</tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        </main>
    </section>
    <script>
        $(document).ready(function(){
            // Function to handle delete button click
            $(".delete-enrollment").click(function(){
                var enrollment_id = $(this).data('id');
                // Using SweetAlert for confirmation
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'delete_enrollment.php', // PHP script to handle deletion
                            type: 'post',
                            data: {enrollment_id: enrollment_id},
                            success: function(response) {
                                // Update table after successful deletion
                                if(response == "success") {
                                    Swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        'Failed to delete enrollment.',
                                        'error'
                                    );
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
