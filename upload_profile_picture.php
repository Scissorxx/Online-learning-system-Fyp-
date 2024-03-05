<?php
session_start();
include 'php/dbconnect.php'; // Assuming you have a file named "dbconnect.php" for database connection

// Check if the user is logged in
if (!isset($_SESSION['valid'])) {
    header("Location: Loginpage.php");
    exit();
}

// Fetch user details from the database
$email = $_SESSION['valid'];

// Handle profile picture upload
$profilePicturePath = ''; // Default value if no file is uploaded

if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0) {
    $profilePicture = $_FILES["profile_picture"];
    $fileName = time() . '_' . basename($profilePicture['name']); // Create a unique file name
    $targetDirectory = 'upload_profile/'; // Directory where files will be uploaded
    $targetFile = $targetDirectory . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validate file size (e.g., 5MB max)
    if ($profilePicture['size'] > 5000000) {
        echo "Sorry, your profile picture is too large.";
        exit;
    }

    // Validate file type (e.g., only jpg, jpeg, png allowed)
    if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
        echo "Sorry, only JPG, JPEG, PNG files are allowed for profile pictures.";
        exit;
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($profilePicture['tmp_name'], $targetFile)) {
        $profilePicturePath = $targetFile;
    } else {
        echo "Sorry, there was an error uploading your profile picture.";
        exit;
    }
} else {
    echo "No file was uploaded.";
    exit;
}

// Update the user's profile picture path in the database
// Replace "your_database_table_name" with your actual table name
// Assuming your table structure includes a column named "profile_picture" to store the path of the profile picture

// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_system";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute SQL statement
    $sql = "UPDATE userdetail SET Image = ? WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$profilePicturePath, $email]);

    header("Location: Dashboard.php");


} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$pdo = null; // Close the connection
?>
