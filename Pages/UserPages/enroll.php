<?php
session_start();
include '../../php/dbconnect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $user_id = $_POST['user_id'];
    $user_name = $_POST['user_name'];
    $email =$_SESSION['email'];

    // Insert data into the enrollment table
    $sql_enroll = "INSERT INTO enrollment (course_id, course_name, user_id, user_name,userprogress) VALUES ('$course_id', '$course_name', '$user_id', '$user_name','pending')";
    
    if (mysqli_query($con, $sql_enroll)) {
        // Insert notification data into the notification table
        $notification_message = "You have been enrolled in the course $course_name";
        $timestamp = date('Y-m-d H:i:s'); // Current timestamp

        $sql_notification = "INSERT INTO notification (user_id, user_name, product_id, product_name, message,timestamp, is_read) VALUES ('$user_id', '$user_name', '$course_id', '$course_name', '$notification_message', '$timestamp',0)";
        try {
            //Server settings
          $mail = require __DIR__ . "/../../mailer.php";
            

            //Recipients
            $mail->setFrom('noreply@example.com', 'Your Name');
            $mail->addAddress($email); // User's email
            $mail->isHTML(true);
            $mail->Subject = 'Course Enrollment';
            $mail->Body = '
            <html>
            <head>
                <style>
                    body {
                        font-family: "Segoe UI", Roboto, Arial, sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
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
                        <h2>Course Enrollment Confirmation</h2>
                    </div>
                    <div class="content">
                        <p>Dear '.$user_name.',</p>
                        <p>Congratulations! You have successfully enrolled in the course '.$course_name.'. We are excited to have you onboard.</p>
                        <p>You can now access the course materials and start learning immediately.</p>
                        <p>Thank you for choosing our platform. Should you have any questions or need assistance, feel free to contact us.</p>
                    </div>
                    <div class="footer">
                        <p>Best regards,<br>Hamro OnlineLearning</p>
                    </div>
                </div>
            </body>
            </html>';
        
        
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
        if (mysqli_query($con, $sql_notification)) {
            // Display SweetAlert
            echo "<script>";
            echo "document.addEventListener('DOMContentLoaded', function() {";
            echo "    swal('Success!', 'You have been enrolled in the course $course_name', 'success').then((value) => {";
            echo "        if (value) {";
            echo "            window.location.href = 'Coursedetails.php?course_id=$course_id';";
            echo "        }";
            echo "    });";
            echo "});";
            echo "</script>";
        } else {
            echo "Error adding notification: " . mysqli_error($con);
        }
    } else {
        echo "Error enrolling in the course: " . mysqli_error($con);
    }
} else {
    echo "Invalid request!";
}
?>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
