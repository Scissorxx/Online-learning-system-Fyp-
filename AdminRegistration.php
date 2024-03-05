<?php
$showAlert = false;

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
    } elseif ($password == $confirmpassword) {
        $sql = "INSERT INTO `userdetail` (`fullname`, `username`, `number`, `email`, `password`, `dt`, `user_type`) VALUES ('$fullrname', '$username', '$number', '$email', '$hashed_password', current_timestamp(), 'Admin')";
        $result = mysqli_query($con, $sql);

        if ($result) {
            $successMessage = "Admin registration successful!!!";
        }
    } else {
        $errorMessage = "Passwords do not match.";
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

    <title>Admin Registration Page</title>
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
            <li><a href="">Home</a></li>
            <li><a href="#">Courses</a></li>
            <li><a href="#">Books</a></li>
            <li><a href="#">About us</a></li>
            
          </ul>
          <div class="buttons">
            <a href="" class="signin">Log In</a>
            <a href="" class="signup">Admin Register</a>
          </div>
        </nav>
      </header>

      <section class="Main-section">
        <div class="container">
            <div class="title">Admin Registration</div>
            <div class="content">
              <form action="AdminRegistration.php" method="post">
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
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                  </div>
                  <div class="input-box">
                    <span class="details">Confirm Password</span>
                    <input type="password" name="confirmpassword" id="confirmpassword" placeholder="Confirm your password" required>
                  </div>
                </div>
                <?php
                if (isset($errorMessage)) {
                  echo "<div id='error-message' class='message'>$errorMessage</div>";
                }
                if (isset($successMessage)) {
                    echo "<div id='login-success-message' class='message'>$successMessage</div>";
                }
                ?>
                <div class="button">
                  <input type="submit" name="submit" id="submit" value="Register">
                </div>
              </form>
            </div>
          </div>
      </section>
</body>
</html>
