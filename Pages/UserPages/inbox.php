<?php
include("header.php");
include '../../php/dbconnect.php';

if (!isset($_SESSION['SN'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['SN'];

// Retrieve notifications for the logged-in user
$sql_notifications = "SELECT * FROM notification WHERE user_id = ? ORDER BY timestamp DESC";
$stmt = mysqli_prepare($con, $sql_notifications);
mysqli_stmt_bind_param($stmt, "s", $user_id);
mysqli_stmt_execute($stmt);
$result_notifications = mysqli_stmt_get_result($stmt);

// Count unread notifications
$sql_unread = "SELECT COUNT(*) AS unread_count FROM notification WHERE user_id = ? AND is_read = 0";
$stmt_unread = mysqli_prepare($con, $sql_unread);
mysqli_stmt_bind_param($stmt_unread, "s", $user_id);
mysqli_stmt_execute($stmt_unread);
$result_unread = mysqli_stmt_get_result($stmt_unread);
$row_unread = mysqli_fetch_assoc($result_unread);
$unread_count = $row_unread['unread_count'];

$user_query = "SELECT * FROM userdetail WHERE SN = ?";
$user_stmt = mysqli_prepare($con, $user_query);
mysqli_stmt_bind_param($user_stmt, "s", $user_id);
mysqli_stmt_execute($user_stmt);
$user_result = mysqli_stmt_get_result($user_stmt);
$user = mysqli_fetch_assoc($user_result);

$default_image = "../../Media/Default/default.jpg"; // Change this to the path of your static image

$profile = isset($user['Image']) ? htmlspecialchars($user['Image']) : $default_image; // Check if Image is set in userdetail table, otherwise use default image
$name = isset($user['fullname']) ? htmlspecialchars($user['fullname']) : ''; // Check if Image is set in userdetail table

// Update notification status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['notification_id'])) {
    $notification_id = $_POST['notification_id'];
    $sql_update = "UPDATE notification SET is_read = 1 WHERE notification_id = ? AND user_id = ?";
    $stmt_update = mysqli_prepare($con, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "is", $notification_id, $user_id);
    mysqli_stmt_execute($stmt_update);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="./images/favicon-32x32.png">
    <link rel="stylesheet" href="../../CSS/User-Css/inboxs.css" />
    <title>Frontend Mentor | Notifications page</title>
</head>
<body>
<div class="container">
    <header>
        <div class="notif_box">
            <h2 class="title">Notifications</h2>
            <span id="notifes"><?php echo $unread_count; ?></span>
        </div>
        <p id="mark_all">Mark all as read</p>
    </header>
    <main>
        <?php while ($notification = mysqli_fetch_assoc($result_notifications)): ?>
            <div class="notif_card <?php echo $notification['is_read'] ? 'read' : 'unread'; ?>" data-notification-id="<?php echo $notification['notification_id']; ?>">
                <img src="<?php echo $profile ?>" alt="avatar" />
                <div class="description">
                    <p class="user_activity">
                        <strong><?php echo $name ?></strong> <?php echo htmlspecialchars($notification['message']); ?>
                    </p>
                    <p class="time"><?php echo $notification['timestamp']; ?></p>
                </div>

            </div>
            <p>----------------------------------------------------------------------------------</p>

        <?php endwhile; ?>
    </main>
</div>

<script>
    const unreadMessages = document.querySelectorAll(".unread");
    const unread = document.getElementById("notifes");
    const markAll = document.getElementById("mark_all");

    unreadMessages.forEach((message) => {
        message.addEventListener("click", () => {
            message.classList.remove("unread");
            message.classList.add("read");
            const notificationId = message.getAttribute("data-notification-id");
            markNotificationAsRead(notificationId);
        })
    });

    markAll.addEventListener("click", () => {
        unreadMessages.forEach(message => {
            message.classList.remove("unread");
            message.classList.add("read");
            const notificationId = message.getAttribute("data-notification-id");
            markNotificationAsRead(notificationId);
        });
    });

    function markNotificationAsRead(notificationId) {
        fetch('notification.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'notification_id=' + notificationId
        })
        .then(response => response.text())
        .then(data => {
            unread.innerText = data;
        })
        .catch(error => console.error('Error:', error));
    }
</script>
</body>
</html>
