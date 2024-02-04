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
    <link rel="stylesheet" href="Dashb.css" />
    
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
          <li><a href="Dashboard.php">Home</a></li>
          <li><a href="#">Courses</a></li>
          <li><a href="#">Books</a></li>
          <li><a href="#">About us</a></li>
          
        </ul>
        <div id="navbar">
  <div class="buttons">
    <div id="user-logo" onclick="toggleProfileDetails()">
      <img src="Image/Profile.png" alt="User Logo">
    </div>
  </div>
</div>

  
      
      </nav>
    </header>
    
    <div id="profile-details" style="display: none;">

    <!-- <img id="profile-image" src=" -->
    <?php 
    // echo $userdetail['profile_image'] ? $userdetail['profile_image'] : 'default-profile.png'; 
    ?>
    <!-- " alt="User Profile"> -->


  <p>Name: <?php echo $userdetail['username']; ?></p>
  <p>Email: <?php echo $userdetail['email']; ?></p>
  <p>Number: <?php echo $userdetail['number']; ?></p>
  <!-- Add more user details as needed -->
 
  <button id="logout-button" onclick="logout()">Logout</button>
</div>


    <section class="Mainsecton">
      <div class="content">

        <h1>Your Journey to Excellence Begins Here</h1>
         <h2>Dive into Learning</h2> <br> <br>
        <p>Discover a world of knowledge and expertise as you embark on your learning <br>journey with us. Our platform offers you the opportunity to learn from the best in <br> the field, with top-tier instructors who are passionate about sharing their skills <br>and insights</p> <br>
        
       <br>
       
        <a href="#" class="btn">Learn More</a><br>

      </div>
    
    
    </section>

    <section class="ExploreCourses">
    <h2>Explore Courses</h2>
  
    <div class="contents">
        <!-- Explore Courses content goes here -->
       

        <?php
        // Dynamically fetch and display courses from the database
        $coursesQuery = "SELECT * FROM courses";
        $coursesResult = mysqli_query($con, $coursesQuery);

        if ($coursesResult->num_rows > 0) {
            while ($course = mysqli_fetch_assoc($coursesResult)) {
        ?>
                <div class="course">
                    <img src="<?php echo $course['image']; ?>" alt="<?php echo $course['title']; ?>">
                    <h4><?php echo $course['title']; ?></h4>
                    <p>Level: <?php echo $course['level']; ?></p>
                    <p>Instructor: <?php echo $course['instructor']; ?></p>
                    <a href="course_details.php?course_id=<?php echo $course['id']; ?>">Enroll Now</a>
                </div>
        <?php
            }
        } else {
            echo "<p>No available courses found.</p>";
        }
        ?>
    </div>
</section>



    
   

   
    


  
    
      


 
    

    <script>
   function toggleProfileDetails() {
    var profileDetails = document.getElementById("profile-details");
    if (profileDetails.style.display === "none") {
      profileDetails.style.display = "block";
    } else {
      profileDetails.style.display = "none";
    }
  }

  function logout() {
    // You can perform any additional cleanup or server-side logout logic here

    // Redirect to the logout script or page
    window.location.href = 'Loginpage.php'; // Change 'logout.php' to your actual logout script/page
  }
</script>
    








  </body>
</html>