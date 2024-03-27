<?php
session_start();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Check if fields are empty
    if (empty($password) || empty($confirm_password)) {
        $error = "Please fill in all fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Password hashing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Retrieve email from session
        $email = $_SESSION["email"];

        $mysqli = require __DIR__ . '/../../php/dbconnect.php';

        // Update password in the database
        $sql = "UPDATE userdetail SET password = ? WHERE email = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $email);
        $stmt->execute();

        if ($mysqli->affected_rows) {
            $success = "Password changed!! <a href='../../Pages/UserPages/Loginpage.php'>Login</a> " ;
        } else {
            $error = "Failed to update password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/User-Css/passwordchange.css">

    <title>Change Password</title>
</head>
<body>
<video autoplay muted loop id="background-video">
    <source src="../../Media/Default/learning1.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>
<div class="container">
    <h2>Change Password</h2>
    <?php if ($error) : ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($success) : ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password" placeholder="New password" ><br><br>
        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" ><br><br>
        <input type="submit" value="Submit">
    </form>
</div>
</body>
</html>