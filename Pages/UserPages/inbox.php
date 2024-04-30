<?php
session_start();
// include("header.php");

include '../../php/dbconnect.php';



$user_id = $_SESSION['SN'];

// Retrieve notifications for the logged-in user
$sql_notifications = "SELECT * FROM notification WHERE user_id = '$user_id' ORDER BY timestamp DESC";
$result_notifications = mysqli_query($con, $sql_notifications);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>

<div class="container mt-5">
    <h2>Notifications</h2>
    <div class="list-group mt-3">
        <?php
        if (mysqli_num_rows($result_notifications) > 0) {
            while ($row = mysqli_fetch_assoc($result_notifications)) {
                $notification_id = $row['notification_id'];
                $product_name = $row['product_name'];
                $message = $row['message'];
                $timestamp = $row['timestamp'];
                $is_read = $row['is_read'];

                // Determine if the notification is read or unread
                $badge_class = $is_read ? 'badge-light' : 'badge-danger';

                echo '<a href="#" class="list-group-item list-group-item-action">';
                echo "<div class='d-flex w-100 justify-content-between'>";
                echo "<h5 class='mb-1'>$product_name</h5>";
                echo "<small class='text-muted'>$timestamp</small>";
                echo "</div>";
                echo "<p class='mb-1'>$message</p>";
                echo "<span class='badge badge-pill $badge_class'>".($is_read ? 'Read' : 'Unread')."</span>";
                echo '</a>';
            }
        } else {
            echo '<div class="alert alert-info" role="alert">No notifications</div>';
        }
        ?>
    </div>
</div>

</body>
</html>
