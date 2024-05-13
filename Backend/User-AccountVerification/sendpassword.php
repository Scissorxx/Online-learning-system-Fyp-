<?php
session_start();
$email = $_POST["email"];
$email = $_SESSION["email"];


$verification_code = sprintf("%04d", mt_rand(0, 9999));

$mysqli = require __DIR__ . '/../../php/dbconnect.php';

$sql = "UPDATE userdetail
        SET verify_code = ?
        WHERE email = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $verification_code, $email);
$stmt->execute(); 

if ($mysqli->affected_rows) {
    $_SESSION["email"] = $email;
    $_SESSION["verification_code"] = $verification_code;

    $mail = require __DIR__ . "/../../mailer.php";

    $mail->setFrom("noreply@example.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END
Your verification code for password reset is: $verification_code
END ;

    try {
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }
}

header("Location: verify_code.php");
       
?>


