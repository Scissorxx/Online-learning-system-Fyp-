<?php
    include '../../php/dbconnect.php';

    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        $sql_delete = "DELETE FROM message WHERE message_id = $delete_id";
        if ($con->query($sql_delete) === TRUE) {
            header("Location: Admin_Message.php");
            exit();
        } else {
            echo "Error deleting message: " . $con->error;
        }
    } else {
        echo "Invalid delete request";
    }
?>
