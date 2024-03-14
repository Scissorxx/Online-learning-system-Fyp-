<?php
$message = false;
$invalidEmail = false;
$invalidPassword = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'php/dbconnect.php';

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
                                header("Location: admin_dashboard.php");
                                break;
                            case 'Teacher':
                                header("Location: teacher_dashboard.php");
                                break;
                            case 'Student':
                                header("Location: User/Dashboard.php");
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="loginP.css">
    
    <script src="Script/LoginpageScript.js"></script>
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
        <div class="signup"><br>
            Don't have account?
            <a href="RegistrationPage.php">Signup Now</a>
        </div>
    </div>
</section>

<script>
    // JavaScript to remove error message when the field is focused
    document.addEventListener("DOMContentLoaded", function() {
        const fields = document.querySelectorAll('.field.error input');
        fields.forEach(field => {
            field.addEventListener('focus', function() {
                this.parentNode.querySelector('.error-message').style.display = 'none';
            });
        });
    });
</script>
</body>
</html>
