<?php 

// $showAlert = false;
 if($_SERVER["REQUEST_METHOD"]=="POST"){
include 'php/dbconnect.php';
// if(isset($_POST['submit'])){
// $fullrname =$_POST["fullname"];
// $username =$_POST["username"];
$email =$_POST["email"];
// $number =$_POST["number"];
$password = $_POST["password"];
// $confirmpassword =$_POST["confirmpassword"];
// $exists=false;

// $verifyEmail_query = mysqli_query($con,"SELECT email from userdetails where email ='$email' ");
// if(mysqli_num_rows($verifyEmail_query)!=0){
//  echo "<div class ='message'>
//     <p>This email is used already.. try again!</p>
//     </div>";
//    echo "<a href ='JavaScript:self.history.back'> <button class ='btn'> Try again</button>";


  $sql ="SELECT * from userdetails where email ='$email' AND password =$password" 
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
    <link rel="stylesheet" href="loginpage.css">
    
    <script src="Script/LoginpageScript.js"></script>

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