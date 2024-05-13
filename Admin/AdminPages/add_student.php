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
    $query = "INSERT INTO userdetail (fullname, username, number, email, password, user_type) VALUES ('$fullname', '$username', '$number', '$email', '$hashed_password', 'Student')";

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
            <div style="background-color: #f0f0f0; padding: 20px; border: 1px solid #ccc; border-radius: 5px;">
            <p style="font-family: Arial, sans-serif; font-size: 16px; color: #333;">Dear ' . $fullname . ',</p>
            <p style="font-family: Arial, sans-serif; font-size: 16px; color: #333;">Your student account has been created successfully.</p>
            <p style="font-family: Arial, sans-serif; font-size: 16px; color: #333;">Please find below your login details:</p>
            
            <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 5px; padding: 10px; margin-top: 10px;">
                <p style="font-family: Arial, sans-serif; font-size: 16px; color: #333;"><strong>Email:</strong> ' . $email . '</p>
                <p style="font-family: Arial, sans-serif; font-size: 16px; color: #333;"><strong>Password:</strong> ' . $password . '</p>
            </div>
        
            <p style="font-family: Arial, sans-serif; font-size: 16px; color: #333; margin-top: 10px;">Please ensure to keep your login credentials confidential.</p>
            <p style="font-family: Arial, sans-serif; font-size: 16px; color: #333;">Best regards,<br>The Administration Team</p>
        </div>';

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
