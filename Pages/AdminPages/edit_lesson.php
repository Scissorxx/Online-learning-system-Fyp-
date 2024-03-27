<?php
include '../php/dbconnect.php';

// Initialize variables
$lesson_id = $_GET['lesson_id'] ?? '';
$lesson_name_from_database = '';
$lesson_description_from_database = '';

// Retrieve lesson details from the database
if (!empty($lesson_id)) {
    $get_lesson_sql = "SELECT Lesson_Name, Lesson_Description FROM lesson WHERE Lesson_ID = '$lesson_id'";
    $result = mysqli_query($con, $get_lesson_sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Check if the array keys exist before accessing them
        $lesson_name_from_database = isset($row['Lesson_Name']) ? $row['Lesson_Name'] : '';
        $lesson_description_from_database = isset($row['Lesson_Description']) ? $row['Lesson_Description'] : '';
    } else {
        // Handle case where lesson details are not found
        // You can redirect to an error page or display an error message
        echo "Lesson details not found.";
        exit();
    }
}

// Other code for form submission and processing
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lesson</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
        }

        input[type="text"],
        select,
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Lesson</h1>
        <form method="post" enctype="multipart/form-data" action="update_lesson.php">
            <div class="form-group">
                <label for="lesson_name">Lesson Name:</label>
                <input type="text" id="lesson_name" name="lesson_name" value="<?php echo htmlspecialchars($lesson_name_from_database); ?>">
            </div>
            <div class="form-group">
                <label for="lesson_description">Lesson Description:</label>
                <textarea id="lesson_description" name="lesson_description"><?php echo htmlspecialchars($lesson_description_from_database); ?></textarea>
            </div>
            <div class="form-group">
                <label for="lesson_video">Lesson Video:</label>
                <input type="file" id="lesson_video" name="lesson_video">
            </div>
            <button type="submit" name="submit">Save</button>
        </form>
    </div>
</body>
</html>
