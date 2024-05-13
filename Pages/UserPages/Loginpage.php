<?php
$message = false;
$invalidEmail = false;
$invalidPassword = false;
include '../../php/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if email and password are set in the POST data
    if(isset($_POST["email"]) && isset($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Escape user inputs to prevent SQL injection
        $email = mysqli_real_escape_string($con, $email);

        $sql = "SELECT * FROM userdetail WHERE email = '$email'";
        $result = mysqli_query($con, $sql);

        // Check if the query executed successfully
        if($result) {
            // Check if there is a row returned from the query
            if(mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);

                // Check if the user account is activated
                if ($row["user_type"] == "Teacher" && $row["account_activation_hash"] != null) {
                    session_start();
                    $_SESSION['email'] = $row['email'];
                    header("Location: activateTeacher-account.php");

                    exit();
                }

                // Check if account activation hash is null
                if ($row["account_activation_hash"] === null) {
                    // Verify the password
                    if (password_verify($password, $row['password'])) {
                        session_start();
                        $_SESSION['valid'] = $row['email'];
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['number'] = $row['number'];
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['SN'] = $row['SN'];

                        // Redirect based on user type
                        switch ($row['user_type']) {
                            case 'Admin':
                                header("Location: ../../admin/adminPages/admin_dashboard.php");
                                break;
                            case 'Teacher':
                                header("Location: teacher_dashboard.php");
                                break;
                            case 'Student':
                                header("Location: Dashboard.php");
                                break;
                        }
                        exit(); // Terminate the script after redirection
                    } else {
                        $invalidPassword = true; // Password verification failed
                    }
                } else {
                    $invalidEmail = true; // Email not found in database
                }
            } else {
                $invalidEmail = true; // Email not found in database
            }
        } else {
            // Handle database query error
            echo "Error: " . mysqli_error($con);
        }
    }
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../../CSS/User-Css/Login-page.css">
    <link rel="stylesheet" href="../../CSS/User-Css/Landingpage.css">

    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <title>Login page</title>

    <style>
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
<video autoplay muted loop id="background-video">
            <source src="../../Media/Default/learning1.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
<header class="header">
<a href="Dashboard.php" class="logo"><?php echo $name; ?></a>

        <nav class="navbar">
            <a href="Dashboard.php" class="hover-underline">home</a>
            <a href="#about" class="hover-underline">About us</a>
            <a href="#courses" class="hover-underline">Courses</a>
            <a href="#teacher" class="hover-underline">Books</a>
            <a href="#contact" class="hover-underline">contact</a>
        </nav>
        <div class="buttons">
            <a href="#" class="Login">login</a>
            <a href="#" class="button">
                <span class="signup">Sign up</span>
                <span class="signup-2" aria-hidden="true">Sign up</span>
            </a>
            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
            </div>
        </div>
    </header>
<section class="Main-section">
    <div class="content">
        <h1>LOG IN</h1>
        <form action="Loginpage.php" method="post">
            <div class="field <?php if ($invalidEmail) echo 'error'; ?>">
                <i class='bx bx-user'></i>
                <input type="text" name="email" id="email" required placeholder="Email or Phone">
                <?php if ($invalidEmail) echo '<span class="error-message">Email not found</span>'; ?>
            </div>
            <div class="field space <?php if ($invalidPassword) echo 'error'; ?>">
                <i class='bx bx-lock-alt'></i>
                <input type="password" name="password" id="password" class="pass-key" required placeholder="Password">
                <?php if ($invalidPassword) echo '<span class="error-message">Incorrect password</span>'; ?>
                <i class="show">SHOW</i>
            </div>
            <div class="pass">
                <a href="forgetpassword.php">Forgot Password?</a>
            </div>
            <div class="field">
                <input type="submit" value="Continue">
            </div>
        </form>
        <div class="Another"><br>
            Don't have account?
            <a href="RegistrationPage.php">Signup Now</a>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fields = document.querySelectorAll('.field.error input');
        fields.forEach(field => {
            field.addEventListener('focus', function() {
                this.parentNode.querySelector('.error-message').style.display = 'none';
            });
        });
    });
</script>
<script src="../../Script/LandingpageScript.js"></script>

</body>
</html>
