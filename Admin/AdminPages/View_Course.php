<?php
include '../../php/dbconnect.php';

// Fetch course details based on the provided course ID
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $course_id = $_GET['id'];
    $sql = "SELECT * FROM courses WHERE Course_ID = '$course_id'";
    $result = mysqli_query($con, $sql);

    if(mysqli_num_rows($result) > 0) {
        $course = mysqli_fetch_assoc($result);
    } else {
        echo "Course not found!";
        exit;
    }
} else {
    echo "Invalid course ID!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../Admin-Css/view_Course.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-jx6WXSa9CFddo/NQs9Mv78v0CjB+l/5XmlymwHt8bndqvNvhIz1KpRIkAWcJcTbfLs1lxXTIt4deNlOeUHX1Ow==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/ynu5oa33yc3yq0jzt1s9xfpmfic0m7knokyzlvwehwtkkm9c/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: 'textarea',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
          { value: 'First.Name', title: 'First Name' },
          { value: 'Email', title: 'Email' },
        ],
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
      });
    </script>

    <title>Admin Courses</title>
</head>
<body>
<?php include("SideBar.php"); ?>
    
<section id="content">
    <nav>
        <i class='bx bx-menu' ></i>
        <a href="#" class="nav-link">Categories</a>
        <input type="checkbox" id="switch-mode" hidden>
        <a href="#" class="profile">
            <img src="../../Media/Default/default.jpg">
        </a>
    </nav>
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Dashboard</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right' ></i></li>
                    <li>
                        <a class="active" href="#">Courses</a>
                    </li>
                </ul>
            </div>
        </div>

        <section class="Main-section">
            <div class="containers">
                <div class="course-details">
                    <div class="image-section">
                        <img src="<?php echo $course['Course_Image']; ?>" alt="Course Image">
                        <p><?php echo $course['Course_Name']; ?></p>
                    </div>
                    <div class="course-info">
                        <h1><?php echo ($course['Price'] == 'free') ? 'Free' : 'Paid'; ?></h1>
                        <?php if ($course['Price'] == 'paid'): ?>
        <section class="course-price">
            <div class="content">
                <div class="price-details">
                    <h1><?php echo $course['Cost']; ?></h1>
                </div>
            </div>
        </section>
        <?php endif; ?>
                        <div class="course-container">
                            <h2>Course Information</h2>
                            <div class="course-information">
                                <table>
                                    <tr>
                                        <th><i class="fas fa-clock"></i> Duration</th>
                                        <td><?php echo $course['Course_Duration']; ?></td>
                                    </tr>
                                    <tr>
                                        <th><i class="fas fa-bolt"></i> Difficulty</th>
                                        <td><?php echo ucfirst($course['Course_Difficulty']); ?></td>
                                    </tr>
                                    <tr>
                                        <th><i class="fas fa-book"></i> Lessons</th>
                                        <td>12</td> <!-- Replace with actual number of lessons -->
                                    </tr>
                                    <tr>
                                        <th><i class="fas fa-user"></i> Instructor</th>
                                        <td><?php echo $course['Teacher']; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="course-description">
            <div class="content">
                <div class="about-the-course">
                    <h1>About the Course</h1>
                    <p><?php echo $course['Course_Description']; ?></p>
                </div>
            </div>
        </section>
    </main>
</section>
<script src="../Script/Admin.js"></script>
</body>
</html>
