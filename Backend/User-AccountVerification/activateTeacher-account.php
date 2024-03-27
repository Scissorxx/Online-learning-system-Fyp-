<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validate password and confirm password
    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit; // Stop further execution
    }

    // Password hashing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Retrieve email from session
    $email = $_SESSION["email"];

    $mysqli = require __DIR__ . '/php/dbconnect.php';

    // Update password in the database
    $sql = "UPDATE userdetail SET password = ? WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $hashed_password, $email);
    $stmt->execute();

    if ($mysqli->affected_rows) {

        $sql = "UPDATE userdetail SET account_activation_hash = NULL WHERE email = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        header("Location: Loginpage.php");
        exit;
    } else {
        echo "Failed to update password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
</head>
<body>
    <h2>Change Password</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
