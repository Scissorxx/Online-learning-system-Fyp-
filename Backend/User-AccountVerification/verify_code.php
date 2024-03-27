<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification Form</title>
    <link rel="stylesheet" href="style.css" />
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../CSS/User-Css/Verify-code.css">
    <script src="script.js" defer></script>
</head>
<body>
    <video autoplay muted loop id="background-video">
        <source src="../../Media/Default/learning1.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="container">
        <header>
            <i class="bx bxs-check-shield"></i>
        </header>
        <p>We have sent OTP to your email.</p>
        <h4>Enter OTP Code</h4>
        <!-- Modified the form tag to include method="POST" -->
        <form action="verify_code.php" method="POST">
            <div class="input-field">
                <!-- Added the name attribute to input fields -->
                <input type="number" name="otp_1" />
                <input type="number" name="otp_2" disabled />
                <input type="number" name="otp_3" disabled />
                <input type="number" name="otp_4" disabled />
            </div>
            <button type="submit">Verify OTP</button>
        </form>
        <!-- Display error message here -->
        <?php
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["otp_1"]) && isset($_POST["otp_2"]) && isset($_POST["otp_3"]) && isset($_POST["otp_4"])) {
                $verification_code = $_POST["otp_1"] . $_POST["otp_2"] . $_POST["otp_3"] . $_POST["otp_4"];
                // Retrieve email and verification code from session
                $email = $_SESSION["email"];
                $saved_verification_code = $_SESSION["verification_code"];
                if ($verification_code == $saved_verification_code) {
                    header("Location: passwordchange.php");
                    exit; // Add exit to prevent further execution
                } else {
                    echo "<a class='error'>Incorrect verification code.</a>";
                }
            }
        }
        ?>
        <p>Didn't receive the email? <a href="sendpassword.php">Resend</a></p>
    </div>
    <script>
         const inputs = document.querySelectorAll("input"),
  button = document.querySelector("button");

// iterate over all inputs
inputs.forEach((input, index1) => {
  input.addEventListener("keyup", (e) => {
    // This code gets the current input element and stores it in the currentInput variable
    // This code gets the next sibling element of the current input element and stores it in the nextInput variable
    // This code gets the previous sibling element of the current input element and stores it in the prevInput variable
    const currentInput = input,
      nextInput = input.nextElementSibling,
      prevInput = input.previousElementSibling;

    // if the value has more than one character then clear it
    if (currentInput.value.length > 1) {
      currentInput.value = "";
      return;
    }
    // if the next input is disabled and the current value is not empty
    //  enable the next input and focus on it
    if (nextInput && nextInput.hasAttribute("disabled") && currentInput.value !== "") {
      nextInput.removeAttribute("disabled");
      nextInput.focus();
    }

    // if the backspace key is pressed
    if (e.key === "Backspace") {
      // iterate over all inputs again
      inputs.forEach((input, index2) => {
        // if the index1 of the current input is less than or equal to the index2 of the input in the outer loop
        // and the previous element exists, set the disabled attribute on the input and focus on the previous element
        if (index1 <= index2 && prevInput) {
          input.setAttribute("disabled", true);
          input.value = "";
          prevInput.focus();
        }
      });
    }
    //if the fourth input( which index number is 3) is not empty and has not disable attribute then
    //add active class if not then remove the active class.
    if (!inputs[3].disabled && inputs[3].value !== "") {
      button.classList.add("active");
      return;
    }
    button.classList.remove("active");
  });
});

//focus the first input which index is 0 on window load
window.addEventListener("load", () => inputs[0].focus());
    </script>
  </body>
</html>

