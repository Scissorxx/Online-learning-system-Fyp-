

<?php
session_start();
include '../../php/dbconnect.php';

// Check if the user is logged in
$loggedIn = isset($_SESSION['valid']);

// Fetch user details from the database
$userdetail = [];
if ($loggedIn) {
    $email = $_SESSION['valid'];
    $sql = "SELECT * FROM userdetail WHERE email = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $userdetail = mysqli_fetch_assoc($result);
}




$sql = "SELECT * FROM landingpage";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $content_id = $row["content_id"];
        $name = $row["Name"];
        $heading1 = $row["heading1"];
        $heading2 = $row["heading2"];
        $teacher = $row["teacher"];
        $course = $row["course"];
        $class = $row["class"];
        $material = $row["material"];
    }
}
?>
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <!-- custom css link -->
    <link rel="stylesheet" href="../../CSS/User-Css/header_s.css">

   


    <!-- header section starts -->
    <header class="header">
    <a href="Dashboard.php" class="logo"><?php echo $name; ?></a>

        <nav class="navbar">
            <a href="Dashboard.php" class="hover-underline">home</a>
            <a href="Aboutus.php" class="hover-underline">About us</a>
            <a href="Course.php" class="hover-underline">Courses</a>
            <a href="Books.php" class="hover-underline">Books</a>
            <a href="Contactus.php" class="hover-underline">contact</a>
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
        <img src="<?php echo $userdetail['Image']; ?>" alt="Profile Picture" style="width: 70px; height: 70px;">
      <?php else: ?>
        <img src="../../Media/Default/default.jpg" alt="User Logo" style="width: 100px; height: 100px;">
        <form action="../../Backend/UserProfile/upload_profile_picture.php" method="post" enctype="multipart/form-data">
          <input type="file" name="profile_picture" id="profile_picture" style="display: none;" onchange="this.form.submit()">
          <button type="button" onclick="document.getElementById('profile_picture').click()">upload</button>
        </form>
      <?php endif; ?>
      <h1><?php echo $userdetail['fullname']; ?></h1>
      <p><?php echo $userdetail['email']; ?></p>
      <p><?php echo $userdetail['number']; ?></p>
    
    </div>
        <li class="profile-dropdown-list-item">
        
            <a href="edit_profile.php">
                <i class="fa-regular fa-user"></i> 
                Edit Profile
            </a>
        </li>
        <li class="profile-dropdown-list-item">
        
        <a href="Cart.php">
        <i class="fa-solid fa-cart-shopping"></i>
            Cart
        </a>
    </li>

        <li class="profile-dropdown-list-item">
            <a href="inbox.php">
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
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <!-- <script src="../../Script/Landingpage_Scripts.js"></script> -->
    <script src="../../Script/Profiless.js"></script>