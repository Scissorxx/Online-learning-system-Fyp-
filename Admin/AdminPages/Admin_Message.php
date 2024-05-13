<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-fnf3mWEUEwJQo3SaN0BfRXdJvEXUT1W4xGlHk41GI1hVkF/26B3eLZkByS7puCJTdS4PZ2ARcl2VzvJDKc2cYw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-fnf3mWEUEwJQo3SaN0BfRXdJvEXUT1W4xGlHk41GI1hVkF/26B3eLZkByS7puCJTdS4PZ2ARcl2VzvJDKc2cYw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Admin Dashboard</title>
    <style>
        /* Add your custom styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

    

        .message-container {
            width: 80%;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .message {
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            position: relative;
        }

        .message:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .message h3 {
            margin: 0;
            color: #333;
        }

        .message p {
            margin: 0;
            color: #666;
        }

        .delete-btn {
            position: absolute;
            top: 5px;
            right: 10px;
            color: red;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php
    include("SideBar.php");

    include '../../php/dbconnect.php';

    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        $sql_delete = "DELETE FROM message WHERE message_id = $delete_id";
        if ($con->query($sql_delete) === TRUE) {
            header("Location: admin_messages.php");
            exit();
        } else {
            echo "Error deleting message: " . $con->error;
        }
    }

    $sql = "SELECT * FROM message";
    $result = $con->query($sql);
?>

<section id="content">
    <nav>
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link">Categories</a>
        
        <input type="checkbox" id="switch-mode" hidden>
        
        <a href="#" class="profile">
            <img src="../../Media/Default/default.jpg">
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
                        <a class="active" href="#">Messages</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="message-container">
            <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='message'>";
                        echo "<h3>" . $row["name"] . "</h3>";
                        echo "<p>Email: " . $row["email"] . "</p>";
                        echo "<p>Phone: " . $row["phone"] . "</p>";
                        echo "<p>Message: " . $row["message"] . "</p>";
                        echo "<a href='delete_message.php?delete_id=" . $row["message_id"] . "' class='delete-btn'><i class='fas fa-trash-alt'></i>Delete</a>";
                        echo "</div>";
                    }
                } else {
                    echo "No messages found.";
                }
                $con->close();
            ?>
        </div>

    </main>
</section>
<script src="../Script/Admin.js"></script>
</body>
</html>
