<?php
  session_start();
  include '../php/dbconnect.php';


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
    <link rel="stylesheet" href="../Dash_boarddd.css" />
    <link rel="stylesheet" href="../Landingpage.css">
   <!-- <link rel="stylesheet" href="navbar.css"> -->
   
    
  </head>
  <body>
  <video autoplay loop muted plays-inline class="backvideo">
        <source src="../Image/learning1.mp4">
    </video>
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
    <img src="../Image/Profile.png" alt="User Logo">
   
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
           <h2>Dive into Learning</h2> <br> 
          <p>Discover a world of knowledge and expertise as you embark on your learning <br>journey with us. Our platform offers you the opportunity to learn from the best in <br> the field, with top-tier instructors who are passionate about sharing their skills <br>and insights</p> <br>
          
         
          <a href="" class="btn">Explore courses</a>
  
        </div>
      
      
      </section>

      <section class="services" id="services">
        
        <ul class="cards">
          <li class="card">
          <img src="../Image/BestTeacher.jpg" alt="img">

            <h3>Best Teacher</h3>
            <p>Learn from the most knowledgeable and <br>experienced instructors.</p>
          </li>
          <li class="card">
          <img src="../Image/bestcourse.jpg" alt="img">

            <h3>Best Course</h3>
            <p>Explore our extensive library of high-quality <br>courses covering various topics.</p>
          </li>
         
          <li class="card">
          <img src="../Image/liveclasses.jpg" alt="img">

            <h3>Virtual Live Classes</h3>
            <p>Participate in interactive sessions with live<br> instructors from anywhere.</p>
          </li>
        </ul>
      </section>

      <section class="course-section" id="courses">
  <h2>Explore Courses</h2>
  <div class="course-container">
    <?php
    // Fetch course data from the database
    $sql_courses = "SELECT * FROM courses";
    $result_courses = mysqli_query($con, $sql_courses);

    // Check if there are any courses
    if (mysqli_num_rows($result_courses) > 0) {
      // Loop through each course
      $counter = 0;
      while ($course = mysqli_fetch_assoc($result_courses)) {
        $counter++;
        ?>
        
        <div class="course-card">
          <a href="course_details.php?course_id=<?php echo $course['Course_ID']; ?>">
            <img src="<?php echo htmlspecialchars($course['Course_Image']); ?>" alt="<?php echo isset($course['Course_Name']) ? htmlspecialchars($course['Course_Name']) : 'Course Image'; ?>">
            <div class="course-details">
              <h3><?php echo isset($course['Course_Name']) ? $course['Course_Name'] : 'Course Name'; ?></h3>
              <div class="additional-details">
                <p><strong>Duration:</strong> <?php echo isset($course['Course_Duration']) ? $course['Course_Duration'] : 'N/A'; ?></p>
                <p><strong>Difficulty:</strong> <?php echo isset($course['Course_Difficulty']) ? $course['Course_Difficulty'] : 'N/A'; ?></p>
                <?php if ($course['Cost'] == 0) : ?>
                  <p><strong>Cost:</strong> Free</p>
                <?php else : ?>
                  <p><strong>Cost:</strong> <?php echo isset($course['Cost']) ? $course['Cost'] : 'N/A'; ?></p>
                <?php endif; ?>
              </div>
              <a href="#" class="btn">enroll now</a>
            </div>
          </a>
        </div>
        <?php
        if ($counter == 3) {
          // Display only 3 courses per row
          echo '<div style="flex-basis: 100%; height: 0;"></div>'; // Add an empty div to break the row
          $counter = 0;
        }
      }
    } else {
      echo "<p>No courses available at the moment.</p>";
    }
    ?>
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
    window.location.href = '../Loginpage.php'; // Change 'logout.php' to your actual logout script/page
  }
</script>

</body>
</html>