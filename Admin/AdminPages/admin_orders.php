<?php
    // Include your database connection file
    include '../../php/dbconnect.php';

    // Query to fetch orders from the database
    $query = "SELECT * FROM Orders";
              
    $result = mysqli_query($con, $query);

    // Check if query execution was successful
    if (!$result) {
        die("Error: " . mysqli_error($con));
    }
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

        /* Make table responsive */
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }
        }

        /* Adjust table content */
        .table td, .table th {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px; /* Adjust this value according to your needs */
        }
    </style>
    <title>Admin Dashboard</title>
</head>
<body>
    <?php include("SideBar.php"); ?>
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
                    <h1>Orders</h1>
                    <ul class="breadcrumb">
                        <li><a href="#">Dashboard</a></li>
                        <li><i class='bx bx-chevron-right' ></i></li>
                        <li><a class="active" href="#">Orders</a></li>
                    </ul>
                </div>
            </div>
            <div class="container-xl">
                <div class="table-responsive">
                    <div class="table-wrapper">
                        <div class="table-title">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h2>Order Details</h2>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Phone Number</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Total Amount</th>
                                    <th>Shipping Address</th>
                                    <th>City</th>
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
                                        echo "<td>".$row['user_name']."</td>";
                                        echo "<td>".$row['phone_number']."</td>";
                                        echo "<td>".$row['product_name']."</td>";
                                        echo "<td>".$row['quantity']."</td>";
                                        echo "<td>".$row['total_amount']."</td>";
                                        echo "<td>".$row['shipping_address']."</td>";
                                        echo "<td>".$row['city']."</td>";
                                        echo "<td class='payment-successful'>".$row['status']."</td>"; // Assuming status indicates payment status
                                        echo "<td>";
                                        if ($row['status'] != 'Completed') {
                                            echo "<a href='mark_completed.php?order_id=".$row['id']."'><i class='material-icons' style='color: green;'>&#xe5ca;</i></a>";
                                        }
                                        echo "<a href='view_order.php?order_id=".$row['id']."'><i class='material-icons'>&#xe8f4;</i></a>";

                                        echo "<a href='delete_order.php?order_id=".$row['id']."'><i class='material-icons'>&#xE872;</i></a>";
                                        echo "</td>";
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
