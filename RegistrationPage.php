<?php 

$showAlert = false;
 if($_SERVER["REQUEST_METHOD"]=="POST"){
include 'php/dbconnect.php';
// if(isset($_POST['submit'])){
$fullrname =$_POST["fullname"];
$username =$_POST["username"];
$email =$_POST["email"];
$number =$_POST["number"];
$password = $_POST["password"];
$confirmpassword =$_POST["confirmpassword"];
$exists=false;

// $verifyEmail_query = mysqli_query($con,"SELECT email from userdetails where email ='$email' ");
// if(mysqli_num_rows($verifyEmail_query)!=0){
//  echo "<div class ='message'>
//     <p>This email is used already.. try again!</p>
//     </div>";
//    echo "<a href ='JavaScript:self.history.back'> <button class ='btn'> Try again</button>";

if(($password ==$confirmpassword)&& $exists==false){
  $sql ="INSERT INTO `userdetails` ( `fullname`, `username`, `number`, `email`, `password`, `dt`) VALUES ('$fullrname', '$username', '$number', '$email', '$password', current_timestamp())";
  $result = mysqli_query($con,$sql);
  if ($result){
    $showAlert = true;
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
    <link rel="stylesheet" href="RegistrationPage.css">
    <script src="https://use.fontawesome.com/80976cfcfc.js"></script>
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
            <a href="RegistrationPage.php" class="signup">Create Account</a>
          </div>
        </nav>
      </header>

      <Section class="Main-section">
        <div class="container">
            <div class="title">Registration</div>
            <div class="content">
              <form action="RegistrationPage.php" method="post">
                <div class="user-details">
                    
                  <div class="input-box">
                    <span class="details"> <i class='bx bx-user'></i> Full Name</span>
                    
                    
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
                <div class="button">
                  <input type="submit" name="submit" id="submit" value="Register">
                </div>
              </form>
            </div>
            

          </div>

          

      </Section>
</body>
</html>