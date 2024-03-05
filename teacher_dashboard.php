<?php
  session_start();
  include 'php/dbconnect.php';

  // Check if the user is logged in
  if (!isset($_SESSION['valid'])) {
    header("Location: Loginpage.php");
    exit();
  }

  // Fetch user details from the database
  $email = $_SESSION['valid'];
  $sql = "SELECT * FROM userdetail WHERE email = '$email'";
  $result = mysqli_query($con, $sql);

  $userdetail = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Online learning Landing page 
    </title>
    <link rel="stylesheet" href="Dash_boardd.css" />
    
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
  <div id="user-logo" onclick="toggleProfileDetails()">
    <img src="Image/Profile.png" alt="User Logo">
   
  </div>
</div>
      
  
      
      </nav>
    </header>

    <div id="profile-details">
      <h3>User Details</h3>
      <?php if ($userdetail['Image'] !== null): ?>
        <img src="<?php echo $userdetail['Image']; ?>" alt="Profile Picture" style="width: 100px; height: 100px;">

  <?php else: ?>
    <img src="Image/default.jpg" alt="User Logo" style="width: 100px; height: 100px;">
    <form action="upload_profile_picture.php" method="post" enctype="multipart/form-data">
  <input type="file" name="profile_picture" id="profile_picture" style="display: none;" onchange="this.form.submit()">
  <button type="button" onclick="document.getElementById('profile_picture').click()">upload</button>
</form>


  <?php endif; ?>


  

      <p>Name: <?php echo $userdetail['username']; ?></p>
      <p>Email: <?php echo $userdetail['email']; ?></p>
      <p>Number: <?php echo $userdetail['number']; ?></p>
      <a href="edit_profile.php" class="edit-profile-button">Edit Profile</a>
      <button id="logout-button" onclick="logout()">Logout</button>
    </div>


    <section class="Mainsecton">
      <div class="content">

        <h1>Your Journey to Excellence Begins Here</h1>
         <h2>Dive into Learning</h2> <br> <br>
        <p>Discover a world of knowledge and expertise as you embark on your learning <br>journey with us. Our platform offers you the opportunity to learn from the best in <br> the field, with top-tier instructors who are passionate about sharing their skills <br>and insights</p> <br>
        
       <br>
       
        <a href="#" class="btn"> Explore   Courses</a><br>

      </div>
    
    
    </section>


    
   

   
    


  
    
      


 
    


    

<script>
    


function toggleProfileDetails() {
    
    var profileDetails = document.getElementById("profile-details");
    profileDetails.style.display = (profileDetails.style.display === "block") ? "none" : "block";
}
</script>


<script>
  function changeProfilePhoto() {
      var input = document.getElementById('profile-photo-input');
      input.click();
  }

  function previewImage() {
      var input = document.getElementById('profile-photo-input');
      var image = document.getElementById('profile-image');

      var file = input.files[0];
      var reader = new FileReader();

      reader.onload = function(e) {
          image.src = e.target.result;
      };

      reader.readAsDataURL(file);
  }
</script>

<script>
  function logout() {
    // You can perform any additional cleanup or server-side logout logic here

    // Redirect to the logout script or page
    window.location.href = 'Loginpage.php'; // Change 'logout.php' to your actual logout script/page
  }
</script>

</body>
</html>