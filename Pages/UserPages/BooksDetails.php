<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Explore details of your favorite books.">
    <title>Books Details</title>
    <link rel="stylesheet" href="../../CSS/User-Css/booksd_detailss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-jx6WXSa9CFddo/NQs9Mv78v0CjB+l/5XmlymwHt8bndqvNvhIz1KpRIkAWcJcTbfLs1lxXTIt4deNlOeUHX1Ow==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container">
        <!-- Header -->
        <?php include("header.php"); ?>
        <main>
            <?php
            include '../../php/dbconnect.php';
            // Check if user is logged in
            $loggedIn = isset($_SESSION['valid']);

            // Fetch user details from the database
            $userdetail = [];
            if ($loggedIn) {
                $email = $_SESSION['valid'];
                $sql = "SELECT * FROM userdetail WHERE email = ?";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $userdetail = mysqli_fetch_assoc($result);
            }
            
            
          
        

            if (isset($_GET['book_id'])) {
                $book_id = $_GET['book_id'];
                $sql = "SELECT * FROM books WHERE BookID = $book_id";
                $result = $con->query($sql);

                if ($result) {
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <section class="page-header">
                                <div>
                                    <ul class="breadcrumb">
                                        <li><a href="#">Home</a></li>
                                        <li><a href="#">/</a></li>
                                        <li><?php echo $row["Title"]; ?></li>
                                    </ul>
                                </div>
                            </section>

                            <section class="book-details">
                                <div class="image-section">
                                    <img src="<?php echo $row["image"]; ?>" alt="Book Image">
                                </div>
                                <div class="info-section">
                                    <h1><?php echo $row["Title"]; ?></h1>
                                    <p class="price"><?php echo $row["Price"]; ?></p>
                                    <div class="description">
                                        <h2>Description</h2>
                                        <p><?php echo $row["Description"]; ?></p>
                                    </div>
                                    <div class="book-container">
                                        <h2>Book Information</h2>
                                        <table class="book-information">
                                            <tr>
                                                <th><i class="fas fa-user"></i> Authors</th>
                                                <td><?php echo $row["Authors"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-book"></i> Publisher</th>
                                                <td><?php echo $row["Publisher"]; ?></td>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-certificate"></i> Edition</th>
                                                <td><?php echo $row["Edition"]; ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                   
                                    <?php 
?>

<?php

if ($loggedIn): ?>
    <form method="get" action="addToCart.php">
        <div class="quantity">    
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" value="1" min="1" max="10" id="quantity">
        </div>
        <input type="hidden" name="book_name" value="<?php echo htmlspecialchars($row['Title']); ?>">
        <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($row['BookID']); ?>">
        <input type="hidden" name="book_image" value="<?php echo htmlspecialchars($row['image']); ?>">
        <input type="hidden" name="book_price" value="<?php echo htmlspecialchars($row['Price']); ?>">
        <button type="submit" class="btns" id="addToCartButton"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
    </form>
<br>
    <a href="checkout.php?book_id=<?php echo $row["BookID"]; ?>&book_name=<?php echo $row["Title"]; ?>&book_price=<?php echo $row["Price"]; ?>&quantity=1" class="btns" id="buy-now-btn"><i class=""></i> Buy Now</a>

<?php else: ?>
 <button type="submit" class="btns" id="addToCartButtonss"><i class="fas fa-shopping-cart"></i> Add to Cart</button>     
 <button type="submit" class="btns" id="addToCartButtons"><i class=""></i>Buy Now</button>     
   
<?php endif; ?>


                                </div>
                            </section>
                            <?php
                        }
                    } else {
                        echo "0 results";
                    }
                } else {
                    echo "Error: " . $con->error;
                }
            } else {
                echo "Book ID not provided";
            }
            $con->close();
            ?>
        </main>
    </div>


    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


    <script>
  document.getElementById('addToCartButtonss').addEventListener('click', function() {
        // Display SweetAlert when the button is clicked
        swal("Warning!", "Please Log in  First!", "info");
    });    </script>
         <script>
  document.getElementById('addToCartButtons').addEventListener('click', function() {
        // Display SweetAlert when the button is clicked
        swal("Warning!", "Please Log in  First!", "info");
    });    </script>
    <script>
      

        document.getElementById("quantity").addEventListener("input", function() {
            var quantity = this.value;
            document.getElementById("cart-quantity").value = quantity;
        });

        document.getElementById("buy-now-btn").addEventListener("click", function() {
            var quantity = document.getElementById("quantity").value;
            var buyNowLink = document.getElementById("buy-now-btn").getAttribute("href");
            buyNowLink += '&quantity=' + quantity;
            document.getElementById("buy-now-btn").setAttribute("href", buyNowLink);
        });
    </script>
</body>
</html>
