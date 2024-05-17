<?php
include '../../php/dbconnect.php';

if (isset($_GET['classId'])) {
    $classId = $_GET['classId'];
    $sql = "SELECT link FROM liveclass WHERE Course_ID = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $classId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['link'];
    }
}

mysqli_stmt_close($stmt);
$con->close();
?>
