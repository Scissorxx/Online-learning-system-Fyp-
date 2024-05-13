<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-66Nd2syB6aLlQWwmssFtVp1eMc73feqRw8vYm5XObBlHtAySm1HwWdL/5GIBlZy9sRJk/5GuTTiWAIrqRiPFyQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../CSS/User-Css/forget-passwords.css">
    
    <style>
        .error-message {
            color: red;
            display: none;
        }
    </style>
</head>
<body>
<video autoplay muted loop id="background-video">
            <source src="../../Media/Default/learning1.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    <div class="container">
        <h2><i class="fas fa-unlock-alt icon"></i>Forgot Password</h2>
        <form id="forgotPasswordForm" action="../../Backend/User-AccountVerification/sendpassword.php" method="POST" title="Forgot Password Form">
            <div class="input-box">
                <span class="details">Enter your email </span>
                <input type="text" name="email" id="email" placeholder="Email">
                <div id="emailError" class="error-message">Please enter your email</div>
            </div>
            <input type="submit" value="Submit">
        </form>
    </div>

    <script>
        document.getElementById("forgotPasswordForm").addEventListener("submit", function(event) {
            var emailInput = document.getElementById("email");
            var emailError = document.getElementById("emailError");

            if (emailInput.value.trim() === "") {
                emailError.style.display = "block";
                event.preventDefault(); // Prevent the form from submitting
            } else {
                emailError.style.display = "none";
            }
        });
    </script>
</body>
</html>
