<?php
include '../../php/dbconnect.php';

if (isset($_POST['classId'])) {
    $classId = $_POST['classId'];
    // Perform SQL query to delete the live class based on $classId
    $sql = "DELETE FROM liveclass WHERE Course_ID = $classId";
    if ($con->query($sql) === TRUE) {
        echo "Class ended successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}

$con->close();
?>
