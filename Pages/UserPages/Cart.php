<?php
include 'header.php';

if(isset($_GET['remove'])){
    $remove_id=$_GET['remove'];
    $result = mysqli_query($con, "DELETE FROM `cart` WHERE id='$remove_id'");
    if($result){
        $message[]='Removed Successfully';
        header('location:cart.php');
    } else {
        $message[]='Failed to remove item';
    }
}

if(isset($_POST['update'])){
    $update_cart_id = $_POST['cart_id'];
    $book_price = $_POST['book_price'];
    $update_quantity = $_POST['update_quantity'];
    $total_price = $book_price * $update_quantity;
    $result = mysqli_query($con, "UPDATE `cart` SET `quantity`='$update_quantity', `total`='$total_price' WHERE `id`='$update_cart_id'");
    if($result){
        $message[]="$user_name, your cart updated successfully";
    } else {
        $message[]='Failed to update cart';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="css/hello.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/User-Css/Carts.css">

    <title>Cart</title>
    <style>
        .cart-btn1, .cart-btn2 {
            display: inline-block;
            margin: auto;
            padding: 0.8rem 1.2rem;
            cursor: pointer;
            color: white;
            font-size: 15px;
            border-radius: .5rem;
            text-transform: capitalize;
        }

        .cart-btn1 {
            margin-left: 40%;
            background-color: #ffa41c;
            color: black;
        }

        .cart-btn2 {
            background-color: rgb(0, 167, 245);
            color: black;
        }

        .message {
            position: sticky;
            top: 0;
            margin: 0 auto;
            width: 61%;
            background-color: #fff;
            padding: 6px 9px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 100;
            gap: 0px;
            border: 2px solid rgb(68, 203, 236);
            border-top-right-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .message span {
            font-size: 22px;
            color: rgb(240, 18, 18);
            font-weight: 400;
        }

        .message i {
            cursor: pointer;
            color: rgb(3, 227, 235);
            font-size: 15px;
        }
    </style>
</head>

<body>

    <div class="cart_form">
        <?php
        if(isset($message)){
            foreach($message as $msg){
                echo '
                <div class="message" id="messages"><span>'.$msg.'</span></div>
                ';
            }
        }
        ?>
        <table style="width: 70%; align-items:center; margin:10px auto;">
            <thead>
                <th>Image</th>
                <th>Name</th>
                <th>price</th>
                <th>Quantity</th>
                <th>Total (₹)</th>
            </thead>
            <tbody>
                <?php
                $total = 0;
                if ($loggedIn) {
                    $email = $_SESSION['valid'];
                    $sql = "SELECT * FROM userdetail WHERE email = '$email'";
                    $result = mysqli_query($con, $sql);
                    $userdetail = mysqli_fetch_assoc($result);
                }
                $user_id = $userdetail['SN'];
                $select_book = $con->prepare("SELECT id, name, price, image, quantity, total FROM cart WHERE user_id = ?");
                $select_book->bind_param("i", $user_id);
                $select_book->execute();
                $select_book->store_result();
                if ($select_book->num_rows > 0) {
                    $select_book->bind_result($id, $name, $price, $image, $quantity, $subtotal);
                    while ($select_book->fetch()) {
                        $sub_total = $price * $quantity;
                        $total += $sub_total;
                ?>
                        <tr>
                            <td><img style="height: 90px;" src="<?php echo $image; ?>" alt=""></td>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $price; ?></td>
                            <td>
                                <form action="" method="POST">
                                    <input type="number" name="update_quantity" min="1" max="10" value="<?php echo $quantity; ?>">
                                    <input type="hidden" name="cart_id" value="<?php echo $id; ?>">
                                    <input class="hidden_input" type="hidden" name="book_price" value="<?php echo $price; ?>">
                                    <button style="background: transparent;" name="update">
    <i class="fas fa-trash-alt" style="font-size: 26px; cursor: pointer;"></i>
</button> |
                                    <a style="color: red;" href="cart.php?remove=<?php echo $id; ?>"> Remove</a>
                                </form>
                            </td>
                            <td><?php echo number_format($sub_total); ?></td>
                        </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="5" class="empty">There is nothing in cart yet !!!!!!!!</td></tr>';
                }
                ?>
                <tr>
                    <th style="text-align:center;" colspan="3">Total</th>
                    <th colspan="2">₹ <?php echo number_format($total); ?>/-</th>
                </tr>
            </tbody>
        </table>
        <a href="checkout.php" class="btns cart-btn1" style="display:<?php echo ($total > 1) ? 'inline-block' : 'none'; ?>"> &nbsp; Proceed to Checkout</a>
    </div>

    <script>
        setTimeout(() => {
            const box = document.getElementById('messages');
            box.style.display = 'none';
        }, 5000);
    </script>

</body>

</html>
