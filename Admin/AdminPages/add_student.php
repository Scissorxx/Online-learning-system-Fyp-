<?php
// Include your database connection
include '../../php/dbconnect.php';

// Include PHPMailer

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert data into the database
    $query = "INSERT INTO userdetail (fullname, username, number, email, password, user_type) VALUES ('$fullname', '$username', '$number', '$email', '$hashed_password', 'student')";

    // Execute the query
    if (mysqli_query($con, $query)) {
        // Data inserted successfully


        try {
            //Server settings
          $mail = require __DIR__ . "/../../mailer.php";
            

            //Recipients
            $mail->setFrom('noreply@example.com', 'Your Name');
            $mail->addAddress($email); // User's email
            $mail->isHTML(true);
            $mail->Subject = 'Account Activation';
            $mail->Body = '
                <p>Dear ' . $fullname . ',</p>
                <p>Your account has been created successfully. Here are your login details:</p>
                <ul>
                    <li><strong>Email:</strong> ' . $email . '</li>
                    <li><strong>Password:</strong> ' . $password . '</li>
                </ul>
            ';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        header("Location: Admin_Student.php"); // Redirect to the page where students are managed
        exit();
    } else {
        // Error occurred
        echo "Error: " . $query . "<br>" . mysqli_error($con);
    }
}

// Close the database connection
mysqli_close($con);
?>
