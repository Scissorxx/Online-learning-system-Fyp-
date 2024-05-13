<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../../CSS/User-Css/Courses.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-jx6WXSa9CFddo/NQs9Mv78v0CjB+l/5XmlymwHt8bndqvNvhIz1KpRIkAWcJcTbfLs1lxXTIt4deNlOeUHX1Ow==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
.blog {
    padding: 50px 0;
    background-color: #f7f7f7;
}

.box-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    font-size:15px;
}

.box {
    width: 300px;
    margin: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.box:hover {
    transform: translateY(-5px);
}

.image {
    height: 200px;
    overflow: hidden;
}

.image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.image:hover img {
    transform: scale(1.1);
}

.content {
    padding: 20px;
}

.icons a {
    color: #555;
    text-decoration: none;
    font-weight: bold;
    font-size: 0.9em;
}

.icons a i {
    margin-right: 5px;
}

h3 {
    margin: 10px 0;
    font-size: 1.5em;
    color: #333;
}

h4 {
    margin: 10px 0;
    font-size: 1.2em;
    color: #007bff;
}

.btns {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btns:hover {
    background-color: #0056b3;
}

.btn {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #0056b3;
}

.empty {
    text-align: center;
    color: #555;
    font-size: 1.2em;
}

    </style>
    <title>Books</title>
</head>
<body>
    <?php
    include("header.php");

    include '../../php/dbconnect.php';

?>
<section class="page-header">
    <div class="container">
        <div class="page-header_wrap">
            <div class="page-heading"></div>
            <ul class="coustom-breadcrumb">
                <li><a href="#">Home</a></li>/
                <li>Books</li>
            </ul>
        </div>
    </div>
</section>
    
   
<section class="blog" id="blog">
    <div class="box-container">
        <?php

        // Retrieve data from the database
        $select_books = mysqli_query($con, "SELECT * FROM `books`") or die('Query failed');

        // Check if there are any books available
        if (mysqli_num_rows($select_books) > 0) {
            // Loop through each book entry
            while ($fetch_book = mysqli_fetch_assoc($select_books)) {
                ?>
                <div class="box">
                    <div class="image shine">
                        <img src="<?php echo $fetch_book['image']; ?>" alt="">
                    </div>
                    <div class="content">
                        <div class="icons">
                            <a href="#"><i class="fas fa-user"></i>by <?php echo $fetch_book['Authors']; ?></a>
                        </div>
                        <h3><?php echo $fetch_book['Title']; ?></h3>
                        <h4>Price:  <?php echo $fetch_book['Price'];?>/-</h4>
                        <?php

if (!$loggedIn): ?>
        <button type="submit" class="btns"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
     
<?php else: ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input class="hidden_input" type="hidden" name="book_name" value="<?php echo htmlspecialchars($fetch_book['Title']); ?>">
    <input class="hidden_input" type="hidden" name="book_id" value="<?php echo htmlspecialchars($fetch_book['BookID']); ?>">
    <input class="hidden_input" type="hidden" name="book_image" value="<?php echo htmlspecialchars($fetch_book['image']); ?>">
    <input class="hidden_input" type="hidden" name="book_price" value="<?php echo htmlspecialchars($fetch_book['Price']); ?>">
    <button type="submit" class="btns"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
</form>

<?php endif; ?>
                    




                      

                        <a href="BooksDetails.php?book_id=<?php echo $fetch_book['BookID']; ?>" class="btn">
    <span class="text text-1">View details</span>
</a>


                    </div>
                </div>
                <?php
            }
        } else {
            // Display a message if no books are available
            echo '<p class="empty">No books added yet!</p>';
        }
        ?>
    </div>
<br>
<br><br>

</section>


</body>
</html>
