<?php
session_start();

// Check if verification code form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if verification code is provided
    if (isset($_POST["verification_code"])) {
        $verification_code = $_POST["verification_code"];

        // Retrieve email and verification code from session
        $email = $_SESSION["email"];
        $saved_verification_code = $_SESSION["verification_code"];

        // Check if the provided verification code matches the saved one
        if ($verification_code == $saved_verification_code) {
            
            // Redirect to change password page
            header("Location: passwordchange.php");
        
        } else {
            echo "Incorrect verification code.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Verification Code</title>
</head>
<body>
    <h2>Enter Verification Code</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="verification_code">Verification Code:</label><br>
        <input type="text" id="verification_code" name="verification_code"><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
