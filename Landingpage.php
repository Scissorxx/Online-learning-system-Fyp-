<?php
session_start();
include 'php/dbconnect.php';

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

function insertIntoCart($bookName, $bookID, $bookImage, $bookPrice) {
    global $con, $userdetail;
    // Escape user inputs for security
    $bookName = mysqli_real_escape_string($con, $bookName);
    $bookID = mysqli_real_escape_string($con, $bookID);
    $bookImage = mysqli_real_escape_string($con, $bookImage);
    $bookPrice = mysqli_real_escape_string($con, $bookPrice);
    $userid = $userdetail["SN"];
    $quantity =1;

    // Insert data into database
    $sql = "INSERT INTO cart (user_id, name, book_id, image, price,quantity) VALUES (?, ?, ?, ?, ?,?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "isssss", $userid, $bookName, $bookID, $bookImage, $bookPrice,$quantity);
    if (mysqli_stmt_execute($stmt)) {
        echo "Book added to cart successfully";
    } else {
        echo "Error: " . mysqli_error($con);
    }
    mysqli_stmt_close($stmt);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Insert book into cart
    insertIntoCart($_POST['book_name'], $_POST['book_id'], $_POST['book_image'], $_POST['book_price']);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-bUQ/eoryB1Jmn5Sy9xFdplXN9rKiW9OAdZK8kpjM5E8m5YsrJCNkRPvhOcS64+5x8x2gYG0oLhawUBk2tHKfMQ==" crossorigin="anonymous" />

    <!-- custom css link -->
    <link rel="stylesheet" href="CSS/User-Css/Landing-page.css">
    <link rel="stylesheet" href="CSS/Animation.css">

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
                <a href="Pages/UserPages/Loginpage.php" class="Login">login</a>
                <a href="Pages/UserPages/RegistrationPage.php" class="button">
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
            <source src="Media/Default/learning1.mp4" type="video/mp4">
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
            <a href="Pages/UserPages/Coursedetails.php?course_id=<?php echo $course['Course_ID']; ?>" class="box">
                <div class="image shine">
                    <img src="<?php echo $course['Course_Image']; ?>" alt="<?php echo $course['Course_Name']; ?>">
                    <!-- Static content for course difficulty -->
                    <h3><?php echo $course['Course_Difficulty']; ?></h3>
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
                        <span><i class="far fa-bookmark"></i> 
                      <?php  
                            $sql_lesson_count = "SELECT COUNT(*) AS lesson_count FROM lesson WHERE Course_ID = " . $course['Course_ID'];
                            $result_lesson_count = $con->query($sql_lesson_count);
                                                
                            if ($result_lesson_count->num_rows > 0) {
                                $row_lesson_count = $result_lesson_count->fetch_assoc();
                                echo $row_lesson_count["lesson_count"];
                            } else {
                                echo "0";
                            }
                        ?>
                        </span>
                        <!-- Static content for duration -->
                        <span><i class="far fa-clock"></i> 8h 25m 9s</span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>



<section class="blog" id="blog">
    <h1 class="heading">Our Books</h1>
    <div class="box-container">
        <?php

        // Retrieve data from the database
        $select_books = mysqli_query($con, "SELECT * FROM `books`") or die('Query failed');

        // Check if there are any books available
        if (mysqli_num_rows($select_books) > 0) {
            // Loop through each book entry
            while ($fetch_book = mysqli_fetch_assoc($select_books)) {
                ?>
                <div class="box">
                    <div class="image shine">
                        <img src="<?php echo $fetch_book['image']; ?>" alt="">
                    </div>
                    <div class="content">
                        <div class="icons">
                            <a href="#"><i class="fas fa-user"></i>by <?php echo $fetch_book['Authors']; ?></a>
                        </div>
                        <h3><?php echo $fetch_book['Title']; ?></h3>
                        <h4>Price:  <?php echo $fetch_book['Price'];?>/-</h4>
                        <?php

if (!$loggedIn): ?>
        <button type="submit" class="btns"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
     
<?php else: ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input class="hidden_input" type="hidden" name="book_name" value="<?php echo htmlspecialchars($fetch_book['Title']); ?>">
    <input class="hidden_input" type="hidden" name="book_id" value="<?php echo htmlspecialchars($fetch_book['BookID']); ?>">
    <input class="hidden_input" type="hidden" name="book_image" value="<?php echo htmlspecialchars($fetch_book['image']); ?>">
    <input class="hidden_input" type="hidden" name="book_price" value="<?php echo htmlspecialchars($fetch_book['Price']); ?>">
    <button type="submit" class="btns"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
</form>

<?php endif; ?>
                    




                      

                        <a href="BooksDetails.php?book_id=<?php echo $fetch_book['BookID']; ?>" class="btn">
    <span class="text text-1">View details</span>
</a>


                    </div>
                </div>
                <?php
            }
        } else {
            // Display a message if no books are available
            echo '<p class="empty">No books added yet!</p>';
        }
        ?>
    </div>
</section>


    <!-- blog section ends -->

  


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


    



    <!-- Scripts -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="Script/Landingpage_Scripts.js"></script>
    <script src="Script/Profile.js"></script>
  
</body>
</html>

