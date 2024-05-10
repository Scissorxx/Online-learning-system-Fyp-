<?php
    // Include your database connection file
    include '../../php/dbconnect.php';
    

    // Query to fetch payments with user details from the database
    $query = "SELECT p.*, u.Image, u.fullname as name
              FROM payments p
              JOIN userdetail u ON p.user_id = u.SN";
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../Admin-Css/admin_payment.css">
    <style> 
       .avatar {
            width: 50px;
            height: auto;
            border-radius: 50%;
        }

        /* Different CSS for Payment Successful cells */
        .payment-successful {
            color: green; /* Change text color */
            font-weight: bold; /* Bold text */
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
                    <h1>Payment</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right' ></i></li>
                        <li>
                            <a class="active" href="#">Payment</a>
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
                                    <h2>Payment <b></b></h2>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User Profile</th>
                                    <th>Name</th>
                                    <th>Product Name</th>
                                    <th>Product ID</th>
                                    <th>Amount</th>
                                    <th>Token</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <th>Print Receipt</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $counter = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>".$counter++."</td>";
                                        echo "<td><img src='".$row['Image']."' class='avatar' alt='Avatar'></td>";
                                        echo "<td>".$row['name']."</td>";
                                        echo "<td>".$row['product_name']."</td>";
                                        echo "<td>".$row['product_id']."</td>";
                                        echo "<td>".$row['Amount']."</td>";
                                        echo "<td>".$row['token']."</td>";
                                        echo "<td class='payment-successful'>Payment Successful</td>"; // Adding custom class
                                        echo "<td><a href='delete_payment.php?payment_id=".$row['id']."'><i class='material-icons'>&#xE872;</i></a></td>"; 
                                        echo "<td><a href='payment_report.php?payment_id=".$row['id']."'>Print Receipt</a></td>"; 
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
    <script src="../Script/Admin.js"></script>
</body>
</html>
