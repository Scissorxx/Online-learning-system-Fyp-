<?php

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . '/../../php/dbconnect.php';


$sql = "SELECT * FROM userdetail
        WHERE account_activation_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("token not found");
}

$sql = "UPDATE userdetail
        SET account_activation_hash = NULL
        WHERE SN = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $user["SN"]);

$stmt->execute();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Account Activated</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>

    <h1>Account Activated</h1>

    <p>Account activated successfully. You can now
       <a href="../../Pages/UserPages/Loginpage.php">log in</a>.</p>

</body>
</html>