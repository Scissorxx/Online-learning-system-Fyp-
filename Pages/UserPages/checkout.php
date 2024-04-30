<?php
    session_start();
    include '../../php/dbconnect.php';

    $loggedIn = isset($_SESSION['valid']);

    // Fetch user details from the session
    $userdetail = [];
    if ($loggedIn) {
        $userName = $_SESSION['username'];
        $email = $_SESSION['valid'];
        $number = $_SESSION['number'];
    } 

    // Check if book_id, book_name, book_price, and quantity are set in the URL parameters
    if(isset($_GET['book_id']) && isset($_GET['book_name']) && isset($_GET['book_price']) && isset($_GET['quantity'])) {
        // Get the values of book_id, book_name, book_price, and quantity
        $productIdentity = $_GET['book_id'];
        $productName = $_GET['book_name'];
        $productPrice = $_GET['book_price'];
        $quantity = $_GET['quantity'];
        $price = $productPrice * 100; // Khalti requires the amount in paisa
    } else {
        echo "Book ID, Book Name, Book Price, or Quantity is not set in the URL.";
        exit; // Exit the script if essential parameters are missing
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
</head>
<body>

<div class="container wrapper">
    <div class="row cart-head">
        <div class="container">
            <div class="row">
                <div style="display: table; margin: auto;">
                    <span class="step_thankyou check-bc step_complete">Thank you</span>
                </div>
            </div>
        </div>
    </div>    
    <div class="row cart-body">
        <form class="form-horizontal" id="checkout-form" method="post" action="">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 col-md-push-6 col-sm-push-6">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <?php
                        // Fetch product details from the database
                        $sql = "SELECT * FROM books where BookID=$productIdentity";
                        $result = mysqli_query($con, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            $subtotal = 0;
                            while ($row = mysqli_fetch_assoc($result)) {
                                $productImage = $row['image'];
                            
                                $productQuantity = $quantity;

                                $subtotal = $productPrice * $productQuantity;
                                $total = $subtotal * 100;
                        ?>
                        <div class="form-group">
                            <div class="col-sm-3 col-xs-3">
                                <img class="img-responsive" src="<?php echo $productImage; ?>" />
                            </div>
                            <div class="col-sm-6 col-xs-6">
                                <div class="col-xs-12"><?php echo $productName; ?></div>
                                <div class="col-xs-12"><small>Quantity:<span><?php echo $productQuantity; ?></span></small></div>
                            </div>
                            <div class="col-sm-3 col-xs-3 text-right">
                                <h6><span>RS:</span><?php echo $productPrice; ?></h6>
                            </div>
                        </div>
                        <div class="form-group"><hr /></div>
                        <?php
                            }

                            // Calculate shipping (assuming fixed)
                            $shipping = 0;

                            // Calculate order total
                            $orderTotal = $subtotal + $shipping;
                        ?>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <strong>Subtotal</strong>
                                <div class="pull-right"><span>Rs:</span><?php echo $subtotal; ?></div>
                            </div>
                            <div class="col-xs-12">
                                <small>Shipping</small>
                                <div class="pull-right"><span>RS:</span><?php echo $shipping; ?></div>
                            </div>
                        </div>
                        <div class="form-group"><hr /></div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <strong>Order Total</strong>
                                <div class="pull-right"><span></span><?php echo $orderTotal; ?></div>
                            </div>
                        </div>
                        <?php
                        } else {
                            echo "No products found.";
                        }
                        ?>
                    </div>
                </div>

            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 col-md-pull-6 col-sm-pull-6">
                <div class="panel panel-info">
                    <div class="panel-heading">Address</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-12">
                                <h4>Shipping Address</h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12"><strong>Country:</strong></div>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="country" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <strong>Full Name:</strong>
                                <input type="text" name="first_name" class="form-control" value="<?php echo $userName; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12"><strong>Address:</strong></div>
                            <div class="col-md-12">
                                <input type="text" name="address" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12"><strong>City:</strong></div>
                            <div class="col-md-12">
                                <input type="text" name="city" class="form-control" value="" />
                            </div>
                        </div>
                      
                        <div class="form-group">
                            <div class="col-md-12"><strong>Zip / Postal Code:</strong></div>
                            <div class="col-md-12">
                                <input type="text" name="zip_code" class="form-control" value="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12"><strong>Phone Number:</strong></div>
                            <div class="col-md-12">
                                <input type="text" name="phone_number" class="form-control" value="<?php echo $number; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12"><strong>Email Address:</strong></div>
                            <div class="col-md-12">
                                <input type="text" name="email_address" class="form-control" value="<?php echo $email; ?>" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>


    </div>
    <button id="payment-button" class="btn btn-primary" disabled>Pay with Khalti</button>

</div>

<!-- Payment Script -->
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
<script>
    $(document).ready(function() {
        // Function to check if all form fields are filled
        function checkForm() {
            var filled = true;
            $('#checkout-form input').each(function() {
                if ($(this).val() == '') {
                    filled = false;
                    return false; // Exit the loop if any field is empty
                }
            });
            return filled;
        }

        // Enable/disable payment button based on form completion
        $('#checkout-form input').on('keyup', function() {
            if (checkForm()) {
                $('#payment-button').prop('disabled', false);
            } else {
                $('#payment-button').prop('disabled', true);
            }
        });



        // Payment button click event
        $('#payment-button').click(function() {
            if (checkForm()) {
                var config = {
                    "publicKey": "test_public_key_1176c307ca65409cbb235a503705496f",
                    "productIdentity": "<?php echo $productIdentity; ?>",
                    "productName": "<?php echo $productName; ?>",
                    "productUrl": "http://gameofthrones.wikia.com/wiki/Dragons",
                    "paymentPreference": [
                        "KHALTI",
                        "EBANKING",
                        "MOBILE_BANKING",
                        "CONNECT_IPS",
                        "SCT",
                    ],
                    "eventHandler": {
                        onSuccess(payload) {
                            console.log(payload);
                            // Extract relevant data from the payload
                            const khaltiID = payload.idx; // Tokyo ID

                            // Prepare data to be sent to the server
                            var xhr = new XMLHttpRequest();
                            xhr.open("POST", "save_order.php", true);
                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === 4 && xhr.status === 200) {
                                    // Handle the response from the PHP script if needed
                                    console.log(xhr.responseText);
                                }
                            };
                            var data = "token=" + encodeURIComponent(payload.token) + 
                                       "&amount=" + encodeURIComponent(payload.amount) + 
                                       "&product_id=" + encodeURIComponent(payload.purchase_order_id) + 
                                       "&payload="+ encodeURIComponent(JSON.stringify(payload)) +
                                       "&email=<?php echo $email; ?>" +
                                       "&product_name=" + encodeURIComponent(payload.purchase_order_name) +
                                       "&khalti_id=" + khaltiID +
                                       "&user_name=<?php echo $userName; ?>" +
                                       "&phone_number=<?php echo $number; ?>" +
                                       "&quantity=<?php echo $quantity; ?>" + 
                                       "&shipping_address=" + encodeURIComponent($("input[name='address']").val()) +
                                       "&city=" + encodeURIComponent($("input[name='city']").val()) +
                                       "&zip_code=" + encodeURIComponent($("input[name='zip_code']").val()) +
                                       "&email_address=" + encodeURIComponent($("input[name='email_address']").val());
                            xhr.send(data);


                            var xhrs = new XMLHttpRequest();
                xhrs.open("POST", "save_payment.php", true);
                xhrs.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhrs.onreadystatechange = function() {
                    if (xhrs.readyState === 4 && xhrs.status === 200) {
                        // Handle the response from the PHP script if needed
                        console.log(xhrs.responseText);
                    }
                };
                var data = "token=" + encodeURIComponent(payload.token) + 
                           "&amount=" + encodeURIComponent(payload.amount) + 
                           "&product_id=" + encodeURIComponent(payload.purchase_order_id) + 
                           "&playload="+ encodeURIComponent(JSON.stringify(payload)) +
                           "&email= <?php echo $email; ?>"+
                           "&product_name=" + encodeURIComponent(payload.purchase_order_name);
                xhrs.send(data);

                        },
                        onError(error) {
                            console.log(error);
                        },
                        onClose() {
                            console.log('widget is closing');
                        }
                    }
                };

                var checkout = new KhaltiCheckout(config);
                checkout.show({amount: <?php echo $total; ?>}); 
            }
        });
    });
</script>
</body>
</html>
