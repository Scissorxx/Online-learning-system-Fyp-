<?php
$errorMessage = $successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'php/dbconnect.php';

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

          $mail = require __DIR__ . "/mailer.php";

          $mail->setFrom("noreply@example.com");
          $mail->addAddress($_POST["email"]);
          $mail->Subject = "Account Activation";
          $mail->Body = <<<END

          your account is created <br>
          email: $email <br>
          password : $password <br>
          <br>
      
         
      
          END;
      
          try {
      
              $mail->send();
      
          } catch (Exception $e) {
      
              echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
              exit;
      
          }
            $successMessage = "Registration Sucessfull <br> please check your mail to activate your account";
        } else {
            $errorMessage = "Error: " . mysqli_error($con);
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Teacher</title>
    <link rel="stylesheet" href="addteacher.css">
</head>
<body>
    <div class="container">
        <h2>Add Teacher</h2>
        <form action="addTeacher.php" method="POST">
            <div class="form-group">
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="number">Number:</label><br>
                <input type="text" id="number" name="number" required>
            </div>

            <div class="form-group">
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>
