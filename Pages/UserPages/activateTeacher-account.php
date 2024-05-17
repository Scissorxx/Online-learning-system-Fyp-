<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $email = $_SESSION["email"];

        $mysqli = require __DIR__ . '/../../php/dbconnect.php';

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
            $error_message = "Failed to update password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f7f7f7;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 10px;
            color: #333;
        }

        p {
            margin-bottom: 20px;
            color: #666;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s;
        }

        input[type="password"]:focus {
            border-color: #007BFF;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #007BFF;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        input[type="submit"]:hover {
            background: #0056b3;
        }

        .error-message {
            color: red;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, Teacher!</h2>
        <p>Before you continue, please change your password.</p>
        <?php
        if (isset($error_message)) {
            echo "<div class='error-message'>$error_message</div>";
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-group">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="input-group">
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>
