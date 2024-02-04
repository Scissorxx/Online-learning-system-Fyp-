<?php
$message = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'php/dbconnect.php';

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM userdetail WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($con, $sql);

    $row = mysqli_fetch_assoc($result);

    if (is_array($row) && !empty($row)) {
        session_start();
        $_SESSION['valid'] = $row['email'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['number'] = $row['number'];
        $_SESSION['email'] = $row['email'];

        // Set success message
        $successMessage = "Login successful! Redirecting...";
    } else {
        $message = true;
    }
}
?>
     



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="loginP.css">
    
    <script src="Script/LoginpageScript.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
 

    <title>Login page</title>
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
            <a href="RegistrationPage.php" class="signup">Create Account</a>
          </div>
        </nav>
      </header>
<Section class="Main-section">
    <div class="content">
    <h1>LOG IN</h1>
        <form action="Loginpage.php" method="post">
          <div class="field">
            <i class='bx bx-user'></i>
            <input type="text" name="email" id="email" required placeholder="Email or Phone">
          </div>
          <div class="field space">
            <i class='bx bx-lock-alt'></i>
            <input type="password" name="password" id="password" class="pass-key" required placeholder="Password">
            <i class="show">SHOW</i>
          </div>
          <div class="pass">
            <a href="#">Forgot Password?</a>
          </div>
          
    <?php
    if ($message) {
       
        echo "<div id='login-fail-message' class='message'>Email and password don't match</div>";
        echo "<script>
                // JavaScript to handle login failure message disappearance
                $(document).ready(function () {
                    // Hide the login failure message after 5 seconds
                    setTimeout(function () {
                        $('#login-fail-message').fadeOut();
                    }, 3000);
                });
            </script>";
    }

    if (isset($successMessage)) {
        echo "<div id='login-success-message' class='message'>$successMessage</div>";
        echo "<script>
                // JavaScript to handle delayed redirection and message disappearance
                $(document).ready(function () {
                    // Delayed redirection after 2 seconds
                    setTimeout(function () {
                        window.location.href = 'Dashboard.php';
                    }, 2000);

                    // Hide the login success message after 2 seconds
                    setTimeout(function () {
                        $('#login-success-message').fadeOut();
                    }, 2000);
                });
            </script>";
    }
    ?>
          
          <div class="field">
            <input type="submit" value="Continue">
          </div>
        </form>

       
        <div class="signup"><br>
          Don't have account?
          <a href="RegistrationPage.php">Signup Now</a>
        </div>
      </div>
    
      
      
     
         

     
     
    </Section>
    


    
    
</body>
</html>