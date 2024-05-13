<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../Admin-Css/admin_dashboard.css" />
    <link rel="stylesheet" href="../../Css/Admin-Css/admin.css">

    <title>Admin Dashboard</title>
    <style>
        .pending {
            color: red;
        }

        .completed {
            color: green;
        }

        /* Custom styling for the recent enrollment table */
        .table-data .todo table {
            border-collapse: collapse;
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .table-data .todo table th,
        .table-data .todo table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .table-data .todo table th {
            background-color: #f4f4f4;
            color: #333;
            font-weight: 500;
        }

        .table-data .todo table tbody tr:last-child td {
            border-bottom: none;
        }

        .table-data .todo table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table-data .todo table tbody tr:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
<?php
    include("SideBar.php");
    include '../../php/dbconnect.php';

    // Retrieve total course count
    $sql = "SELECT COUNT(*) AS total_courses FROM courses";
    $result = $con->query($sql);
    $total_courses = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total_courses = $row["total_courses"];
    }

    // Retrieve total student count
    $sql = "SELECT COUNT(*) AS total_students FROM userdetail WHERE user_type = 'Student'";
    $result = $con->query($sql);
    $total_students = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total_students = $row["total_students"];
    }

    // Retrieve total teacher count
    $sql = "SELECT COUNT(*) AS total_teachers FROM userdetail WHERE user_type = 'Teacher'";
    $result = $con->query($sql);
    $total_teachers = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total_teachers = $row["total_teachers"];
    }

    // Retrieve total books count
    $sql = "SELECT COUNT(*) AS total_books FROM books";
    $result = $con->query($sql);
    $total_books = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total_books = $row["total_books"];
    }

    // Fetch recent orders
    $sql_orders = "SELECT o.user_name, o.date_order, o.status, u.Image FROM orders o
                   INNER JOIN userdetail u ON o.user_id = u.SN
                   ORDER BY o.date_order DESC LIMIT 5";
    $result_orders = $con->query($sql_orders);

    // Fetch recent enrollments with student name, course name, and user progress
    $sql_enrollment = "SELECT e.user_name, c.course_name, e.userprogress, e.enrollment_date FROM enrollment e 
                       INNER JOIN courses c ON e.course_id = c.course_id 
                       ORDER BY e.enrollment_date DESC LIMIT 5";
    $result_enrollment = $con->query($sql_enrollment);
?>

<section id="content">
    <nav>
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link">Categories</a>
        
        <input type="checkbox" id="switch-mode" hidden>
        
        <a href="#" class="profile">
            <img src="../../Media/Default/default.jpg" alt="Profile Picture">
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
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Home</a>
                    </li>
                </ul>
            </div>
        </div>

        <ul class="box-info">
            <li>
                <i class='bx bxs-book'></i>
                <span class="text">
                    <h3><?php echo $total_courses; ?></h3>
                    <p>Total Courses</p>
                </span>
            </li>
            <li>
                <i class='bx bxs-group'></i>
                <span class="text">
                    <h3><?php echo $total_students; ?></h3>
                    <p>Total Students</p>
                </span>
            </li>
            <li>
                <i class='bx bxs-group'></i>
                <span class="text">
                    <h3><?php echo $total_teachers; ?></h3>
                    <p>Total Teachers</p>
                </span>
            </li>
            <li>
                <i class='bx bxs-book-open'></i>
                <span class="text">
                    <h3><?php echo $total_books; ?></h3>
                    <p>Total Books</p>
                </span>
            </li>
        </ul>

        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Recent Orders</h3>
                    <i class='bx bx-search'></i>
                    <i class='bx bx-filter'></i>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Date Order</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($result_orders->num_rows > 0) {
                                while($row_orders = $result_orders->fetch_assoc()) {
                                    $status_class = ($row_orders["status"] == "Pending") ? "pending" : "completed";
                                    $user_image = $row_orders["Image"] ? $row_orders["Image"] : "../../Media/Default/default.jpg";
                        ?>
                        <tr>
                            <td>
                                <img src="<?php echo $user_image; ?>" alt="User Image">
                                <p><?php echo $row_orders["user_name"]; ?></p>
                            </td>
                            <td><?php echo $row_orders["date_order"]; ?></td>
                            <td><span class="status <?php echo $status_class; ?>"><?php echo $row_orders["status"]; ?></span></td>
                        </tr>
                        <?php
                                }
                            } else {
                                echo "<tr><td colspan='3'>No recent orders</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="todo">
                <div class="head">
                    <h3>Recent Enrollments</h3>
                    <i class='bx bx-plus'></i>
                    <i class='bx bx-filter'></i>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Course</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($result_enrollment->num_rows > 0) {
                                while($row_enrollment = $result_enrollment->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $row_enrollment["user_name"]; ?></td>
                            <td><?php echo $row_enrollment["course_name"]; ?></td>
                            <td><?php echo $row_enrollment["userprogress"]; ?></td>
                        </tr>
                        <?php
                                }
                            } else {
                                echo "<tr><td colspan='3'>No recent enrollments</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</section>

<script src="../Script/Admin.js"></script>
</body>
</html>

<?php
// Close connection
$con->close();
?>
