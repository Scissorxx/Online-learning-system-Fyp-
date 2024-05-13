<?php
session_start();

include '../../php/dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Check if all necessary parameters are set
    if (isset($_GET['book_id']) && isset($_GET['quantity']) && isset($_GET['book_name']) && isset($_GET['book_image']) && isset($_GET['book_price'])) {
        // Retrieve data from URL parameters
        $book_id = $_GET['book_id'];
        $quantity = $_GET['quantity'];
        $book_name = $_GET['book_name'];
        $book_image = $_GET['book_image'];
        $book_price = $_GET['book_price'];

        insertIntoCart($book_name, $book_id, $book_image, $book_price, $quantity);
    } else {
        echo "Missing parameters";
    }
} else {
    echo "Invalid request method";
}

function insertIntoCart($bookName, $bookID, $bookImage, $bookPrice, $quantity) {
    global $con, $userdetail;

   

    // Escape user inputs for security
    $bookName = mysqli_real_escape_string($con, $bookName);
    $bookID = mysqli_real_escape_string($con, $bookID);
    $bookImage = mysqli_real_escape_string($con, $bookImage);
    $bookPrice = mysqli_real_escape_string($con, $bookPrice);
    $userid = $_SESSION['SN'];

    // Check if the item already exists in the cart
    $check_sql = "SELECT * FROM cart WHERE user_id = ? AND book_id = ?";
    $check_stmt = mysqli_prepare($con, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "ii", $userid, $bookID);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>";
        echo "document.addEventListener('DOMContentLoaded', function() {";
        echo "    swal({";
        echo "        title: 'Warning!',";
        echo "        text: 'This item is already in your cart',";
        echo "        icon: 'error',";
        echo "        buttons: {";
        echo "            viewCart: 'View Cart',";
        echo "            ok: 'OK',";
        echo "        },";
        echo "    }).then((value) => {";
        echo "        if (value === 'viewCart') {";
        echo "            window.location.href = 'cart.php';"; // Redirect to cart page
        echo "        } else {";
        echo "            window.location.href = 'BooksDetails.php?book_id=$bookID';"; // Redirect back to book details page
        echo "        }";
        echo "    });";
        echo "});";
        echo "</script>";
    } else {
        // Insert data into database
        $sql = "INSERT INTO cart (user_id, name, book_id, image, price, quantity) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "issssi", $userid, $bookName, $bookID, $bookImage, $bookPrice, $quantity);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>";
            echo "document.addEventListener('DOMContentLoaded', function() {";
            echo "    swal({";
            echo "        title: 'Success!',";
            echo "        text: 'Item added to your cart',";
            echo "        icon: 'success',";
            echo "        buttons: {";
            echo "            viewCart: 'View Cart',";
            echo "            ok: 'OK',";
            echo "        },";
            echo "    }).then((value) => {";
            echo "        if (value === 'viewCart') {";
            echo "            window.location.href = 'cart.php';"; // Redirect to cart page
            echo "        } else {";
            echo "            window.location.href = 'BooksDetails.php?book_id=$bookID';"; // Redirect back to book details page
            echo "        }";
            echo "    });";
            echo "});";
            echo "</script>";
        } else {
            echo "<script>";
            echo "document.addEventListener('DOMContentLoaded', function() {";
            echo "    swal('Error!', 'Failed to add item to cart', 'error').then((value) => {";
            echo "        window.location.href = 'BooksDetails.php?book_id=$bookID';"; // Redirect back to book details page
            echo "    });";
            echo "});";
            echo "</script>";
        }
    }

    // Close the statement
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }
}
?>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
