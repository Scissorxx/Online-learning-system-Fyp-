<?php
    // Include your database connection file
    include '../../php/dbconnect.php';

    // Check if a user ID and course ID are passed
    if (isset($_GET['user_id']) && isset($_GET['course_id'])) {
        $user_id = $_GET['user_id'];
        $course_id = $_GET['course_id'];

        // Fetch all lessons for the given course
        $lesson_query = "SELECT * FROM lesson WHERE course_id = $course_id";
        $lesson_result = mysqli_query($con, $lesson_query);
        if (!$lesson_result) {
            die("Lesson Query failed: " . mysqli_error($con));
        }

        // Fetch user progress for the given course
        $progress_query = "SELECT lesson_id, status FROM user_progress WHERE user_id = $user_id AND course_id = $course_id";
        $progress_result = mysqli_query($con, $progress_query);
        if (!$progress_result) {
            die("Progress Query failed: " . mysqli_error($con));
        }
        $progress_map = []; // Map to store lesson status
        while ($progress_row = mysqli_fetch_assoc($progress_result)) {
            $progress_map[$progress_row['lesson_id']] = $progress_row['status'];
        }
    } else {
        // Redirect to the previous page if user or course ID is not provided
        header("Location: admin_enrollment.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Progress</title>
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../Admin-Css/admin_payment.css">
    <!-- Head content -->
</head>
<body>
    <?php include("SideBar.php"); ?>
    <section id="content">
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">Categories</a>
            <input type="checkbox" id="switch-mode" hidden>
            <a href="#" class="profile">
                <img src="../../Media/Default/default.jpg" alt="profile picture">
            </a>
        </nav>
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>View Progress</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">View Progress</a>
                        </li>
                    </ul>
                </div>
            </div>
      
            <div class="container-xl">
                <div class="table-responsive">
                    <div class="table-wrapper">
                        <div class="table-title">
                            <div class="row">
                                <div class="col-sm-2">
                                    <h2>Lesson ID</h2>
                                </div>
                                <div class="col-sm-2">
                                    <h2>Course ID</h2>
                                </div>
                                <div class="col-sm-5">
                                    <h2>Lesson Name</h2>
                                </div>
                                <div class="col-sm-3">
                                    <h2>Status</h2>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Lesson ID</th>
                                    <th>Course ID</th>
                                    <th>Lesson Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $counter = 1;
                                    while ($row = mysqli_fetch_assoc($lesson_result)) {
                                        $lesson_id = $row['lesson_ID'];
                                        $status = isset($progress_map[$lesson_id]) ? ($progress_map[$lesson_id] == "completed" ? "Completed" : "Incomplete") : "Incomplete";
                                        echo "<tr>";
                                        echo "<td>".$counter++."</td>";
                                        echo "<td>".$lesson_id."</td>";
                                        echo "<td>".$row['Course_ID']."</td>"; // Fixed: Corrected case
                                        echo "<td>".$row['lesson_Name']."</td>";
                                        echo "<td>".$status."</td>";
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
</body>
</html>
