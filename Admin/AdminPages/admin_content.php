<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <title>Admin Dashboard</title>
    <style>
        /* Resetting default margin and padding */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body styles */
body {
    font-family: Arial, sans-serif;
}

/* Sidebar styles */
#sidebar {
    /* Define your sidebar styles here */
}

/* Content section styles */
#content {
    display: flex;
    flex-direction: column;
}

/* Navigation styles */
nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    background-color: #f4f4f4;
    border-bottom: 1px solid #ccc;
}

nav a.nav-link {
    margin: 0 10px;
    text-decoration: none;
    color: #333;
}

nav a.profile img {
    width: 30px;
    height: 30px;
    border-radius: 50%;
}

/* Main section styles */
main {
    padding: 20px;
}

.head-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.head-title h1 {
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

.breadcrumb {
    list-style: none;
    display: flex;
    align-items: center;
}

.breadcrumb li {
    margin-right: 5px;
}

.breadcrumb i {
    font-size: 14px;
    margin: 0 5px;
}

.breadcrumb a {
    text-decoration: none;
    color: #666;
}

.breadcrumb a.active {
    color: #333;
}

/* Form styles */
form {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 5px;
}

input[type="text"] {
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

input[type="submit"] {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
<?php include("SideBar.php"); ?>

<section id="content">
    <nav>
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link">Categories</a>
        <input type="checkbox" id="switch-mode" hidden>
        <a href="#" class="profile">
            <img src="../../Media/Default/default.jpg">
        </a>
    </nav>

    <main>
        <div class="head-title">
            <div class="left">
                <h1>Content</h1>
                <ul class="breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a class="active" href="#">Content</a></li>
                </ul>
            </div>
        </div>

        <?php
            // Include database connection file
            include '../../php/dbconnect.php';

            // Fetch data from database
            $sql = "SELECT * FROM landingpage";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);

            // Set variables for form fields
            $content_id = $row['content_id'];
            $name = $row['Name'];
            $heading1 = $row['heading1'];
            $heading2 = $row['heading2'];
            $teacher = $row['teacher'];
            $course = $row['course'];
            $class = $row['class'];
            $material = $row['material'];
            $video = $row['video'];
        ?>

        <form method="post" action="update_content.php">
            <input type="hidden" name="content_id" value="<?php echo $content_id; ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>"><br>

            <label for="heading1">Heading 1:</label>
            <input type="text" id="heading1" name="heading1" value="<?php echo $heading1; ?>"><br>

            <label for="heading2">Heading 2:</label>
            <input type="text" id="heading2" name="heading2" value="<?php echo $heading2; ?>"><br>

            <label for="teacher">Teacher:</label>
            <input type="text" id="teacher" name="teacher" value="<?php echo $teacher; ?>"><br>

            <label for="course">Course:</label>
            <input type="text" id="course" name="course" value="<?php echo $course; ?>"><br>

            <label for="class">Class:</label>
            <input type="text" id="class" name="class" value="<?php echo $class; ?>"><br>

            <label for="material">Material:</label>
            <input type="text" id="material" name="material" value="<?php echo $material; ?>"><br>

            <!-- <label for="video">Video:</label>
            <input type="text" id="video" name="video" value="<?php echo $video; ?>"><br> -->

            <input type="submit" value="Submit">
        </form>
    </main>
</section>

<script src="../Script/Admin.js"></script>
</body>
</html>
