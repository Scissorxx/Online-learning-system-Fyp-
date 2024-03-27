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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landingpage</title>
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <!-- custom css link -->
    <link rel="stylesheet" href="../../CSS/User-Css/Landing-page.css">
    <link rel="stylesheet" href="../../CSS/Animation.css">

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</head>
<body>
    <!-- header section starts -->
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

    <!-- home section starts -->
    <section class="home" id="home">
        <video autoplay muted loop id="background-video">
            <source src="../../Media/Default/learning1.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="content">
            <h3 class="slideInDown">Your Journey to Excellence Begins Here</h3>
            <p class="slideInUp">Discover a world of knowledge and expertise as you embark on your learning journey with us. Our platform offers you the opportunity to learn from the best in the field, with top-tier instructors.</p>
            <a href="#" class="btn slideInSide">
                <span class="text text-1 slideInDown">Get Started</span>
                <span class="text text-2" aria-hidden="true">Get Started</span>
            </a>
        </div>
    </section>

<!-- Our Services section -->
<section class="subjects">
    <h1 class="heading">Our Services</h1>
    <div class="box-container">
        <div class="box">
            <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
            <h3 class="fadeInLeft">Expert Instructors</h3>
            <p class="fadeInLeft">Learn from the most knowledgeable and experienced instructors.</p>
        </div>
        <div class="box"> <!-- Add class here -->
            <div class="icon"><i class="fas fa-book-open"></i></div>
            <h3 class="fadeInLeft">High-Quality Courses</h3>
            <p class="fadeInLeft">Explore our extensive library of high-quality courses covering various topics.</p>
        </div>
        <div class="box"> <!-- Add class here -->
            <div class="icon"><i class="fas fa-laptop"></i></div>
            <h3 class="fadeInRight">Virtual Live Classes</h3>
            <p class="fadeInRight">Participate in interactive sessions with live instructors from anywhere.</p>
        </div>
        <div class="box"> <!-- Add class here -->
            <div class="icon"><i class="fas fa-file-alt"></i></div>
            <h3 class="fadeInRight">Comprehensive Course Materials</h3>
            <p class="fadeInRight">Access the best course materials to enhance your learning experience.</p>
        </div>
    </div>
</section>

<!-- Courses section -->
<section class="courses" id="courses">
        <h1 class="heading">Our famous courses</h1>
        <div class="box-container">
            <?php foreach ($courses as $course): ?>
                <div class="box">
                    <div class="image shine">
                        <img src="<?php echo $course['Course_Image']; ?>" alt="<?php echo $course['Course_Name']; ?>">
                        <!-- Static content for course difficulty -->
                        <h3>Basic</h3>
                    </div>
                    <div class="content">
                        <h4><?php echo $course['Price']; ?></h4>
                        <h3><?php echo $course['Course_Name']; ?></h3>
                        <!-- Static content for rating and reviews -->
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span>(785)</span>
                        </div>
                        <!-- Static content for lessons -->
                        <div class="icons">
                            <span><i class="far fa-bookmark"></i> 15 lessons</span>
                            <!-- Static content for duration -->
                            <span><i class="far fa-clock"></i> 8h 25m 9s</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <!-- End of Courses section -->



    <!-- about section starts -->

    <section class="about" id="about">

        <h1 class="heading">about us</h1>

        <div class="container">

            <figure class="about-image">
                <img src="Media/Default/BestTeacher.jpg" alt="" height="500">
                <img src="Media/Default/BestTeacher.jpg" alt="" class="about-img">
            </figure>

            <div class="about-content">
                <h3>18 years of experience</h3>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Natus aut impedit expedita aliquam 
                    provident quod excepturi minus. Similique eveniet fugiat doloribus nisi saepe cupiditate iusto itaque totam! Officia, enim qui.</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni voluptatum ipsa quod dolores officia at excepturi quas numquam vero dolorem vitae 
                    veritatis quisquam fugit voluptates doloribus, id pariatur in ipsam?</p>    
                <a href="#" class="btn">
                    <span class="text text-1">read more</span>
                    <span class="text text-2" aria-hidden="true">read more</span>
                </a>        
            </div>

        </div>

    </section>

    <!-- about section ends -->


        <!-- footer section stars -->

        <section class="footer">

            <div class="box-container">
    
                <div class="box">
                    <h3>find us here</h3>
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Illum beatae.</p>
                 
                </div>
    
                <div class="box">
                    <h3>contact us</h3>
                    <p>+1234 587 1478</p>
                    <a href="#" class="link">Onlinelearning4@gmail.com</a>
                </div>
    
                <div class="box">
                    <h3>localization</h3>
                    <p>230 points of the pines <br>
                    colorado springs <br>
                    united states</p>
                </div>
    
            </div>
            <div class="credit">created by <span>Online learning</span>| all rights are reserved!</div>
        </section>
    
        <!-- footer section ends -->
    



    <!-- Scripts -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="../../Script/LandingpageScript.js"></script>
    <script>
        let profileDropdownList = document.querySelector(".profile-dropdown-list");
let btn = document.querySelector(".profile-dropdown-btn");

let classList = profileDropdownList.classList;

const toggle = () => classList.toggle("active");

window.addEventListener("click", function (e) {
  if (!btn.contains(e.target)) classList.remove("active");
});
    </script>
</body>
</html>

