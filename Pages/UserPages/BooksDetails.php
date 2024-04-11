<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Explore details of your favorite books.">

    <link rel="stylesheet" href="../../CSS/User-Css/booksdetails.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-jx6WXSa9CFddo/NQs9Mv78v0CjB+l/5XmlymwHt8bndqvNvhIz1KpRIkAWcJcTbfLs1lxXTIt4deNlOeUHX1Ow==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <title>Books Details</title>
</head>
<body>
    <div class="container">
        <?php include("header.php"); ?>
        
        <main>
            <?php
            include '../../php/dbconnect.php';

            $book_id = $_GET['book_id'];
            $sql = "SELECT * FROM books WHERE BookID = $book_id";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<section class="page-header">
                            <div>
                                <ul class="breadcrumb">
                                    <li><a href="#">Home</a></li>
                                    <li><a href="#">/</a></li>

                                    <li>' . $row["Title"] . '</li>
                                </ul>
                            </div>
                        </section>

                        
                        <section class="book-details">
                            <div class="image-section">
                                <img src="' . $row["image"] . '" alt="Book Image">
                            </div>
                            <div class="info-section">
                                <h1>' . $row["Title"] . '</h1>
                                <p class="price">' . $row["Price"] . '</p>
                                
                                <label for="quantity">Quantity:</label>
                                <input type="number" name="quantity" value="1" min="1" max="10" id="quantity">
                                
                                    <a class="add-to-cart-btn"><i class="fas fa-shopping-cart"></i>Add to Cart</a><br><br>
                                
                                    <button class="buy-btn">Buy Now</button>
                            
                                <div class="book-container">
                                    <h2>Book Information</h2>
                                    
                                    <table class="book-information">
                                        <tr>
                                            <th><i class="fas fa-user"></i> Authors</th>
                                            <td>' . $row["Authors"] . '</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-book"></i> Publisher</th>
                                            <td>' . $row["Publisher"] . '</td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-certificate"></i> Edition</th>
                                            <td>' . $row["Edition"] . '</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="description">
                                    <h2>Description</h2>
                                    <p>' . $row["Description"] . '</p>
                                </div>
                                
                                
                        </section>';
                }
            } else {
                echo "0 results";
            }
            $con->close();
            ?>
        </main>
        
    </div>
</body>
</html>
