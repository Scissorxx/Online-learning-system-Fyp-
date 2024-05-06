<?php
session_start(); // Start the session

include '../../php/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['notification_id'])) {
    $notification_id = $_POST['notification_id'];

    $sql_update = "UPDATE notification SET is_read = 1 WHERE notification_id = $notification_id";
    mysqli_query($con, $sql_update);

    // Count unread notifications
    if (isset($_SESSION['SN'])) {
        $user_id = $_SESSION['SN'];
        $sql_count_unread = "SELECT COUNT(*) AS unread_count FROM notification WHERE user_id = '$user_id' AND is_read = 0";
        $result_count_unread = mysqli_query($con, $sql_count_unread);
        $row = mysqli_fetch_assoc($result_count_unread);
        echo $row['unread_count'];
    } else {
        echo "0"; // Session is not set, return 0
    }
}
?>
