<?php
session_start();
include '../../php/dbconnect.php';

// Check if the user is logged in
$loggedIn = isset($_SESSION['valid']);

// Fetch user details from the database
$userdetail = [];
if ($loggedIn) {
    $email = $_SESSION['valid'];
    $sql = "SELECT * FROM userdetail WHERE email = '$email'";
    $result = mysqli_query($con, $sql);
    $userdetail = mysqli_fetch_assoc($result);
}

// Fetch courses from the database
$courses = [];
$sql1 = "SELECT * FROM courses";
$result1 = mysqli_query($con, $sql1);
while ($row = mysqli_fetch_assoc($result1)) {
    $courses[] = $row;
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<link rel="stylesheet" href="../../CSS/User-Css/header.css">

    <header class="header">
        <a href="Dashboard.php" class="logo">Online learning</a>
        <nav class="navbar">
            <a href="#home" class="hover-underline">home</a>
            <a href="#about" class="hover-underline">About us</a>
            <a href="#courses" class="hover-underline">Courses</a>
            <a href="#teacher" class="hover-underline">Books</a>
            <a href="#contact" class="hover-underline">contact</a>
        </nav>
        <div class="buttons">
            <?php if ($loggedIn): ?>
                <div class="profile-dropdown">
    <div onclick="toggle()" class="profile-dropdown-btn">
        <div class="profile-img">

        <?php if ($userdetail['Image'] !== null): ?>
        <img src="<?php echo $userdetail['Image']; ?>" alt="Profile Picture">
      <?php else: ?>
        <img src="../../Media/Default/default.jpg" alt="Profile Image">
       
      <?php endif; ?>
        </div>

        <span><?php echo $userdetail['fullname']; ?> <i class="fa-solid fa-angle-down"></i></span>
    </div>
  
        
    <ul class="profile-dropdown-list">
    <div id="profile-details">
      <?php if ($userdetail['Image'] !== null): ?>
        <img src="<?php echo $userdetail['Image']; ?>" alt="Profile Picture" style="width: 100px; height: 100px;">
      <?php else: ?>
        <img src="../../Media/Default/default.jpg" alt="User Logo" style="width: 100px; height: 100px;">
        <form action="../../Backend/UserProfile/upload_profile_picture.php" method="post" enctype="multipart/form-data">
          <input type="file" name="profile_picture" id="profile_picture" style="display: none;" onchange="this.form.submit()">
          <button type="button" onclick="document.getElementById('profile_picture').click()">upload</button>
        </form>
      <?php endif; ?>
      <p><?php echo $userdetail['fullname']; ?></p>
      <p><?php echo $userdetail['email']; ?></p>
      <p><?php echo $userdetail['number']; ?></p>
    
    </div>
        <li class="profile-dropdown-list-item">
        
            <a href="#">
                <i class="fa-regular fa-user"></i> 
                Edit Profile
            </a>
        </li>

        <li class="profile-dropdown-list-item">
            <a href="#">
                <i class="fa-regular fa-envelope"></i>
                Inbox
            </a>
        </li>

     

        <hr />

        <li class="profile-dropdown-list-item">
            <a href="Logout.php">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                Log out
            </a>
        </li>
    </ul>
</div>

            <?php else: ?>
                <a href="Loginpage.php" class="Login">login</a>
                <a href="Registration.php" class="button">
                    <span class="signup">Sign up</span>
                    <span class="signup-2" aria-hidden="true">Sign up</span>
                </a>
            <?php endif; ?>
            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
            </div>
        </div>
    </header>


    



    <!-- Scripts -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="../../Script/LandingpageScript.js"></script>
    <script src="../../Script/Profile.js"></script>

   
    

