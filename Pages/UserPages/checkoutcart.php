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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
                        $total = 0;
                        if ($loggedIn) {
                            $email = $_SESSION['valid'];
                            $sql = "SELECT * FROM userdetail WHERE email = ?";
                            $stmt = $con->prepare($sql);
                            $stmt->bind_param("s", $email);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $userdetail = $result->fetch_assoc();
                        }
                        $user_id = $userdetail['SN'];
                        $select_book = $con->prepare("SELECT id, name, price, image, quantity, total FROM cart WHERE user_id = ?");
                        $select_book->bind_param("i", $user_id);
                        $select_book->execute();
                        $result = $select_book->get_result();
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $sub_total = $row['price'] * $row['quantity'];
                                $total += $sub_total;
                                ?>
                                <div class="form-group">
                                    <div class="col-sm-3 col-xs-3">
                                        <img class="img-responsive" src="<?php echo $row['image']; ?>" />
                                    </div>
                                    <div class="col-sm-6 col-xs-6">
                                        <div class="col-xs-12"><?php echo $row['name']; ?></div>
                                        <div class="col-xs-12"><small>Quantity:<span><?php echo $row['quantity']; ?></span></small></div>
                                    </div>
                                    <div class="col-sm-3 col-xs-3 text-right">
                                        <h6><span>RS:</span><?php echo $row['price']; ?></h6>
                                    </div>
                                    <!-- Hidden input fields for product details -->
                                    <input type="hidden" name="product_ids[]" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="product_names[]" value="<?php echo $row['name']; ?>">
                                    <input type="hidden" name="product_quantities[]" value="<?php echo $row['quantity']; ?>">
                                    <input type="hidden" name="product_amounts[]" value="<?php echo $sub_total; ?>">
                                </div>
                                <div class="form-group"><hr /></div>
                                <?php
                            }

                            // Calculate shipping (assuming fixed)
                            $shipping = 0;

                            // Calculate order total
                            $orderTotal = $total + $shipping;
                            ?>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <strong>Subtotal</strong>
                                    <div class="pull-right"><span>Rs:</span><?php echo $total; ?></div>
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
        $('#payment-button').prop('disabled', !checkForm());
    });

    // Payment button click event
    $('#payment-button').click(function() {
        if (checkForm()) {
            var productIds = [];
            var productNames = [];
            var productQuantities = [];
            var productAmounts = [];
            // Collect all product IDs, names, quantities, and amounts
            $('input[name="product_ids[]"]').each(function(index) {
                productIds.push($(this).val());
                productNames.push($('input[name="product_names[]"]').eq(index).val());
                productQuantities.push($('input[name="product_quantities[]"]').eq(index).val());
                productAmounts.push($('input[name="product_amounts[]"]').eq(index).val());
            });

            var config = {
                "publicKey": "test_public_key_1176c307ca65409cbb235a503705496f",
                "productIdentity": productIds.join(','), // Pass comma-separated IDs
                "productName": productNames.join(','), // Pass comma-separated names
                "productUrl": "http://example.com/product",
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
                        const khaltiID = payload.idx; // Tokyo ID

                        // Define products array
                        var products = [];
                        // Loop through each product and push it to products array
                        for (var i = 0; i < productIds.length; i++) {
                            products.push({
                                id: productIds[i],
                                name: productNames[i],
                                quantity: productQuantities[i],
                                amount: productAmounts[i]
                            });
                        }

                        // Send products array along with payload data
                        var data = "token=" + encodeURIComponent(payload.token) + 
                                   "&amount=" + <?php echo $total*100; ?> + // Amount in cents
                                   "&payload=" + encodeURIComponent(JSON.stringify(payload)) +
                                   "&email=<?php echo $email; ?>" +
                                   "&products=" + encodeURIComponent(JSON.stringify(products)) + // Pass products array
                                   "&khalti_id=" + khaltiID +
                                   "&user_name=<?php echo $userName; ?>" +
                                   "&phone_number=<?php echo $number; ?>" +
                                   "&shipping_address=" + encodeURIComponent($("input[name='address']").val()) +
                                   "&city=" + encodeURIComponent($("input[name='city']").val()) +
                                   "&zip_code=" + encodeURIComponent($("input[name='zip_code']").val()) +
                                   "&email_address=" + encodeURIComponent($("input[name='email_address']").val());

                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "save_order_checkout.php", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                console.log(xhr.responseText);
                            }
                        };
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

                          
                        Swal.fire({
                title: 'Your order has been placed successfully!',
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: 'OK',
                cancelButtonText: 'View',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to cart page
                    window.location.href = 'cart.php';
                } else {
                    // Redirect to inbox page
                    window.location.href = 'inbox.php';
                }
            });
                     

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
            checkout.show({amount: <?php echo $total*100; ?>}); 
        }
    });
});

</script>
</body>
</html>
