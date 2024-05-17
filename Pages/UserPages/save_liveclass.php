<?php
session_start();
include '../../php/dbconnect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $classDate = $_POST["classDate"];
    $classTime = $_POST["classTime"];
    $classTopic = $_POST["classTopic"];
    $meetingLink = $_POST["meeting"];

    // Example values for course_id, teacher_id, and title
    $course_id = $_POST["Courseid"]; // Change this according to your requirements
    $teacher_id = $_SESSION['SN'];
    $title = "Live Class"; // Change this according to your requirements

    // Insert data into liveclass table using prepared statement
    $sql = "INSERT INTO liveclass (course_id, link, teacher_id, date, time, title) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("isssss", $course_id, $meetingLink, $teacher_id, $classDate, $classTime, $classTopic);
    
    if ($stmt->execute()) {
        echo "New record created successfully<br>";
        
        // Retrieve student IDs from the POST data
        $studentIds = json_decode($_POST["studentIds"]);

        // Insert notifications for each student
        foreach ($studentIds as $studentId) {
            // Get user name and product details
            $user_sql = "SELECT fullname, email FROM userdetail WHERE SN = ?";
            $user_stmt = $con->prepare($user_sql);
            $user_stmt->bind_param("i", $studentId);
            $user_stmt->execute();
            $user_result = $user_stmt->get_result();
            if ($user_result->num_rows > 0) {
                $user_row = $user_result->fetch_assoc();
                $user_name = $user_row["fullname"];
                $email = $user_row["email"];
            }
            
            // Get the current timestamp
            $timestamp = date("Y-m-d H:i:s");

            // Insert notification for the student
            $notification_sql = "INSERT INTO notification (user_id, user_name, product_id, product_name, message, timestamp, is_read) VALUES (?, ?, ?, ?, ?, ?, 0)";
            $notification_stmt = $con->prepare($notification_sql);
            $notification_stmt->bind_param("isssss", $studentId, $user_name, $course_id, $title, $message, $timestamp);
            $message = "You have a new live class scheduled for $classDate at $classTime.";
            $notification_stmt->execute();
            
            if ($notification_stmt->affected_rows > 0) {
                echo "Notification created for user $studentId successfully<br>";
            } else {
                echo "Error creating notification for user $studentId: " . $con->error . "<br>";
            }
            
            $notification_stmt->close();
            
            // Send email notification
            try {
                $mail = require __DIR__ . "/../../mailer.php";
                $mail->setFrom('noreply@example.com', 'Your Name');
                $mail->addAddress($email); // User's email
                $mail->isHTML(true);
                $mail->Subject = 'Live Class Notification';
                $mail->Body = '
                    <html>
                    <head>
                        <style>
                            /* CSS styles */
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f4f4f4;
                            }
                            .container {
                                max-width: 600px;
                                margin: auto;
                                padding: 20px;
                                background-color: #fff;
                                border-radius: 8px;
                                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                            }
                            .header {
                                background-color: #007bff;
                                color: #fff;
                                padding: 20px;
                                border-top-left-radius: 8px;
                                border-top-right-radius: 8px;
                                text-align: center;
                            }
                            h2 {
                                margin-top: 0;
                            }
                            .content {
                                padding: 20px;
                                color: #555;
                            }
                            .footer {
                                background-color: #f4f4f4;
                                padding: 10px 20px;
                                border-bottom-left-radius: 8px;
                                border-bottom-right-radius: 8px;
                            }
                            p {
                                margin: 10px 0;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h2>Live Class Notification</h2>
                            </div>
                            <div class="content">
                                <p>Dear '.$user_name.',</p>
                                <p>You have a new live class scheduled for <strong>'.$classDate.'</strong> at <strong>'.$classTime.'</strong>.</p>
                                <p><strong>Topic:</strong> '.$classTopic.'</p>
                                <p>Join the class using the following link:</p>
                                <p><a href="'.$meetingLink.'">'.$meetingLink.'</a></p>
                            </div>
                            <div class="footer">
                                <p>Best regards,<br>Hamro Online Learning</p>
                            </div>
                        </div>
                    </body>
                    </html>';
                $mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
    
    $stmt->close();
}

// Close connection
$con->close();
?>
