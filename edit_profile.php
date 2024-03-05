<?php
session_start();
include 'php/dbconnect.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['valid'])) {
    header("Location: Loginpage.php");
    exit();
}

// Fetch user details from the database
$email = $_SESSION['valid'];
$sql = "SELECT * FROM userdetail WHERE email = '$email'";
$result = mysqli_query($con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $userdetail = mysqli_fetch_assoc($result);
} else {
    // Handle error if user details are not found
    echo "Error: User details not found";
    exit();
}

// Handle form submission
$updateMessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update profile picture if uploaded
    if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $profilePicture = $_FILES['profile_picture'];
        $targetDirectory = 'upload_profile/';
        $targetFile = $targetDirectory . basename($profilePicture['name']);

        // Check file type
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
            $updateMessage = "Sorry, only JPG, JPEG, PNG files are allowed.";
        } else {
            // Move uploaded file to target directory
            if(move_uploaded_file($profilePicture['tmp_name'], $targetFile)) {
                $profilePicturePath = $targetFile;

                // Update profile picture path in database
                $updateImageSQL = "UPDATE userdetail SET Image = '$profilePicturePath' WHERE email = '$email'";
                if(mysqli_query($con, $updateImageSQL)) {
                    $updateMessage = "Profile picture updated successfully.";
                } else {
                    $updateMessage = "Error updating profile picture: " . mysqli_error($con);
                }
            } else {
                $updateMessage = "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Update other profile information
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];

    // Update the user's profile in the database
    $updateProfileSQL = "UPDATE userdetail SET fullname = '$fullname', username = '$username', number = '$phone' WHERE email = '$email'";
    if(mysqli_query($con, $updateProfileSQL)) {
        $updateMessage = "Profile updated successfully.";
        // Fetch updated user details
        $updatedResult = mysqli_query($con, $sql);
        if ($updatedResult && mysqli_num_rows($updatedResult) > 0) {
            $userdetail = mysqli_fetch_assoc($updatedResult);
        } else {
            $updateMessage .= " Error fetching updated details.";
        }
    } else {
        $updateMessage = "Error updating profile: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile</title>
  <link rel="stylesheet" href="edit_profile.css">
</head>
<body>
  <div class="container">
    <h2>Edit Profile</h2>
    <?php if(!empty($updateMessage)): ?>
    <div class="message"><?php echo $updateMessage; ?></div>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
      <div class="profile-picture">
      <?php if ($userdetail['Image'] !== null): ?>
        <img id="profile-image" src="<?php echo $userdetail['Image'] ? $userdetail['Image'] : 'upload_profile/default.jpg'; ?>" alt="Profile Picture">
        <input type="file" name="profile_picture" id="profile-picture-input" style="display: none;" onchange="displaySelectedImage(event)">

        <?php else: ?>

          <img src="Image/default.jpg" alt="User Logo" style="width: 100px; height: 100px;">
    <form action="upload_profile_picture.php" method="post" enctype="multipart/form-data">
  <input type="file" name="profile_picture" id="profile_picture" style="display: none;" onchange="this.form.submit()">
  <button type="button" onclick="document.getElementById('profile_picture').click()">upload</button>
</form>

          <?php endif; ?>
        <button type="button" onclick="document.getElementById('profile-picture-input').click()">Change Profile Picture</button>
      </div>
      <div class="profile-info">
        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname" value="<?php echo $userdetail['fullname']; ?>">

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $userdetail['username']; ?>">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $userdetail['email']; ?>" readonly>

        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" value="<?php echo $userdetail['number']; ?>">
      </div>
      <button type="submit">Save</button>
    </form>
  </div>
  <script>
  function displaySelectedImage(event) {
    var reader = new FileReader();
    reader.onload = function () {
      var image = document.getElementById('profile-image');
      image.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  }
</script>
</body>
</html>
