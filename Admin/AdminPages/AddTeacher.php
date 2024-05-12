<?php
$errorMessage = $successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../../php/dbconnect.php';

    $fullname = mysqli_real_escape_string($con, $_POST["name"]); 
    $email = mysqli_real_escape_string($con, $_POST["email"]);
    $number = mysqli_real_escape_string($con, $_POST["number"]);

    function generateRandomPassword($length = 6) {
      $bytes = random_bytes($length);
      return substr(str_replace(['+', '/', '='], '', base64_encode($bytes)), 0, $length);
  }
  
  $password = generateRandomPassword();
  
    

    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $activation_token = bin2hex(random_bytes(16));
    $activation_token_hash = hash("sha256", $activation_token);

    

  
        $sql = "INSERT INTO `userdetail` (`fullname`, `number`, `email`, `password`, `dt`,`user_type`,`account_activation_hash`) VALUES ('$fullname', '$number', '$email', '$hashed_password', current_timestamp(),'Teacher','$activation_token_hash')";
        $result = mysqli_query($con, $sql);

        if ($result) {

            $mail = require __DIR__ . "/../../mailer.php";

            $mail->setFrom("noreply@example.com");
            $mail->addAddress($_POST["email"]);
            $mail->Subject = "Account Activation";
            
            $mail->isHTML(true); // Set email format to HTML
            
            $mail->Body = <<<END
            <div style="background-color: #f0f0f0; padding: 20px; border: 1px solid #ccc; border-radius: 5px;">
                <p style="font-family: Arial, sans-serif; font-size: 16px; color: #333;">Dear Teacher,</p>
                <p style="font-family: Arial, sans-serif; font-size: 16px; color: #333;">Your account has been successfully created.</p>
                <p style="font-family: Arial, sans-serif; font-size: 16px; color: #333;">Please login using the following credentials and change your password:</p>
                
                <div style="background-color: #fff; border: 1px solid #ccc; border-radius: 5px; padding: 10px; margin-top: 10px;">
                    <p style="font-family: Arial, sans-serif; font-size: 16px; color: #333;"><strong>Email:</strong> $email</p>
                    <p style="font-family: Arial, sans-serif; font-size: 16px; color: #333;"><strong>Password:</strong> $password</p>
                </div>
            
                <p style="font-family: Arial, sans-serif; font-size: 16px; color: #333; margin-top: 10px;">Please keep your login credentials secure.</p>
                <p style="font-family: Arial, sans-serif; font-size: 16px; color: #333;">Best regards,<br>HamroOnlineLearning</p>
            </div>
            END;
            
            try {
                $mail->send();
            
            
      
          } catch (Exception $e) {
      
              echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
              exit;
      
          }
          header("Location: Admin_Teacher.php"); // Redirect to the page where  are managed
        } else {
            $errorMessage = "Error: " . mysqli_error($con);
        }
    }

?>


