<?php
$errorMessage = $successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'php/dbconnect.php';

    $fullrname = mysqli_real_escape_string($con, $_POST["fullname"]);
    $username = mysqli_real_escape_string($con, $_POST["username"]);
    $email = mysqli_real_escape_string($con, $_POST["email"]);
    $number = mysqli_real_escape_string($con, $_POST["number"]);
    $password = mysqli_real_escape_string($con, $_POST["password"]);
    $confirmpassword = mysqli_real_escape_string($con, $_POST["confirmpassword"]);
    
    
    

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $activation_token = bin2hex(random_bytes(16));
    $activation_token_hash = hash("sha256", $activation_token);

    // Check for duplicate username
    $checkUsernameQuery = "SELECT * FROM `userdetail` WHERE `username`='$username'";
    $resultUsername = mysqli_query($con, $checkUsernameQuery);
    $numUsernameRows = mysqli_num_rows($resultUsername);

    // Check for duplicate email
    $checkEmailQuery = "SELECT * FROM `userdetail` WHERE `email`='$email'";
    $resultEmail = mysqli_query($con, $checkEmailQuery);
    $numEmailRows = mysqli_num_rows($resultEmail);

    if ($numUsernameRows > 0) {
        $errorMessage = "Username already exists. Please choose a different username.";
    } elseif ($numEmailRows > 0) {
        $errorMessage = "Email already registered. Please use a different email.";
    } elseif ($password != $confirmpassword) {
        $errorMessage = "Passwords do not match.";
    } else {
        $sql = "INSERT INTO `userdetail` (`fullname`, `username`, `number`, `email`, `password`, `dt`,`user_type`,`account_activation_hash`) VALUES ('$fullrname', '$username', '$number', '$email', '$hashed_password', current_timestamp(),'Student','$activation_token_hash')";
        $result = mysqli_query($con, $sql);

        if ($result) {

          $mail = require __DIR__ . "/mailer.php";

          $mail->setFrom("noreply@example.com");
          $mail->addAddress($_POST["email"]);
          $mail->Subject = "Account Activation";
          $mail->Body = <<<END
      
          Click <a href="http://localhost/myproject/activate-account.php?token=$activation_token">here</a> 
          to activate your account.
      
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="RegistrationsPages.css">
    <script src="https://use.fontawesome.com/80976cfcfc.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    

    <title>RegistrationPage</title>
</head>
<body>
    <header class="header">
        <nav class="navbar">
          <h2 class="logo"><a href="#">Online Learning</a></h2>
          <input type="checkbox" id="menu-toggle" />
          <label for="menu-toggle" id="hamburger-btn">
            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
              <path d="M3 12h18M3 6h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </label>
          <ul class="links">
            <li><a href="Landingpage.php">Home</a></li>
            <li><a href="#">Courses</a></li>
            <li><a href="#">Books</a></li>
            <li><a href="#">About us</a></li>
            
          </ul>
          <div class="buttons">
            <a href="Loginpage.php" class="signin">Log In</a>
            <a href="RegistrationPages.php" class="signup">Create Account</a>
          </div>
        </nav>
      </header>

      <section class="Main-section">
        <div class="container">
            <div class="title">Registration</div>
            <div class="content">

            <br>
              
              <?php if(isset($successMessage)): ?>
                <div class="success-message"><?php echo $successMessage; ?></div>
              <?php endif; ?>
              <form action="RegistrationPage.php" method="post">
                <div class="user-details">
                    
                  <div class="input-box">
                    <span class="details">Full Name</span>
                    <input type="text" name="fullname" id="fullname" placeholder="Enter your name" required>
                  </div>
                  
                  <div class="input-box">
                    <span class="details">Username</span>
                    <input type="text" name="username" id="username" placeholder="Enter your username" required>
                  </div>
                  <div class="input-box">
                    <span class="details">Email</span>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required>
                  </div>
                  <div class="input-box">
                    <span class="details">Phone Number</span>
                    <input type="text" name="number" id="number" placeholder="Enter your number" required>
                  </div>
  

<div class="input-box">
  <span class="details">Password</span>
  <input type="password" name="password" id="password" placeholder="Enter your password" required oninput="toggleShowButtonVisibility(this)">
  <button class="show-password-btn" type="button" onclick="togglePasswordVisibility('password')">Show</button>
</div>

<div class="input-box">
  <span class="details">Confirm Password</span>
  <input type="password" name="confirmpassword" id="confirmpassword" placeholder="Confirm your password" required oninput="toggleShowButtonVisibility(this)">
  <button class="show-password-btn" type="button" onclick="togglePasswordVisibility('confirmpassword')">Show</button>
</div>
                  
                </div>
                <?php if(isset($errorMessage)): ?>
                <div class="error-message"><?php echo $errorMessage; ?></div>
              <?php endif; ?>
              <b></b>
                <div class="input-box">
                    <input type="checkbox" name="terms" id="terms" required>
                    <label for="terms">I agree to the <a href="terms_and_conditions.php" target="_blank">terms and conditions</a></label>
                  </div>
                 
                <div class="button">
                  <input type="submit" name="submit" id="submit" value="Register">
                </div>
                
              </form>
              
            </div>
          </div>
      </section>

      <script>
  function toggleShowButtonVisibility(inputElement) {
    var showButton = inputElement.nextElementSibling;
    if (inputElement.value.trim() !== "") {
      showButton.style.display = "block";
    } else {
      showButton.style.display = "none";
    }
  }

  function togglePasswordVisibility(inputId) {
    var input = document.getElementById(inputId);
    if (input.type === "password") {
      input.type = "text";
      document.querySelector(`button[onclick="togglePasswordVisibility('${inputId}')"]`).textContent = "Hide";
    } else {
      input.type = "password";
      document.querySelector(`button[onclick="togglePasswordVisibility('${inputId}')"]`).textContent = "Show";
    }
  }
</script>
</body>
</html>
