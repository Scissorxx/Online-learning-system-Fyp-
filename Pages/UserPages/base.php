<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../../CSS/User-Css/checkoutpage.css">


</head>
<body>

<?php
    include("header.php");

    include '../../php/dbconnect.php';

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
        $userID = $userdetail['SN'];
        $userName = $userdetail['username'];
        $number = $userdetail['number'];
    } 

    // Check if course_id, Course_name, and Course_price are set in the URL parameters
    if(isset($_GET['course_id']) && isset($_GET['Course_name']) && isset($_GET['Course_price'])) {
        // Get the values of course_id, Course_name, and Course_price
        $productIdentity = $_GET['course_id'];
        $productName = $_GET['Course_name'];
        $productPrice = $_GET['Course_price'];
        $price = $productPrice * 100; // Khalti requires the amount in paisa
        
        // Fetch course image URL from the database
        $sql = "SELECT course_image FROM courses WHERE course_id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $productIdentity);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $course = mysqli_fetch_assoc($result);
        $courseImage = $course['course_image'];
    } else {
        echo "Course ID, Course Name, or Course Price is not set in the URL.";
    }
?>
<section class="Main-section">
    <div class="container">
        <div class="book-details">
            <div class="image-section">
                <img src="<?php echo $courseImage; ?>" alt="Course Image" class="course-image">
            </div>
            <div class="book-info">
                <h2><?php echo htmlspecialchars($productName); ?></h2>
                <div class="book-container">
                    <div class="book-information">
                        <form id="checkout-form">
                            <div class="form-group">
                                <label for="FullName">Full Name</label>
                                <input type="text" class="form-control" id="FullName" placeholder="<?php echo htmlspecialchars($userName); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="<?php echo htmlspecialchars($email); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="phoneNumber">Phone Number</label>
                                <input type="tel" class="form-control" id="phoneNumber" placeholder="<?php echo htmlspecialchars($number); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="productprice">Product Price</label>
                                <input type="text" class="form-control" id="productprice" value="<?php echo htmlspecialchars($productPrice); ?>" readonly>
                            </div>
                            <button id="payment-button" type="button" class="payment-button"><i class="fas fa-money-check-alt"></i> Pay with Khalti</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    var form = document.getElementById('checkout-form');
    var button = document.getElementById('payment-button');
    var successMessage = document.getElementById('success-message');



    var config = {
        // Replace the publicKey with your Khalti public key
        "publicKey": "test_public_key_1176c307ca65409cbb235a503705496f",
        "productIdentity": "<?php echo $productIdentity; ?>",
        "productName": "<?php echo $productName; ?>",
        "productUrl": "http://yourwebsite.com/course_details.php?course_id=<?php echo $productIdentity; ?>",
        "paymentPreference": [
            "KHALTI",
            "EBANKING",
            "MOBILE_BANKING",
            "CONNECT_IPS",
            "SCT"
        ],
        "eventHandler": {
            onSuccess(payload) {
                // Send the payment details to the PHP script using AJAX
                console.log(payload);
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "save_payment.php", true);
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
                           "&playload="+ encodeURIComponent(JSON.stringify(payload)) +
                           "&email=<?php echo $email; ?>" +
                           "&product_name=" + encodeURIComponent(payload.purchase_order_name);
                xhr.send(data);


                var enrollXhr = new XMLHttpRequest();
                enrollXhr.open("POST", "enroll.php", true);
                enrollXhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                enrollXhr.onreadystatechange = function() {
                    if (enrollXhr.readyState === 4 && enrollXhr.status === 200) {
                        // Handle the response from the PHP script if needed
                        console.log(enrollXhr.responseText);
                    }
                };
                var enrollData = "user_id=<?php echo $userID; ?>" + 
                                "&user_name=<?php echo $userName; ?>" + 
                                "&course_id=" + encodeURIComponent(payload.purchase_order_id) + 
                                "&course_name=" + encodeURIComponent(payload.purchase_order_name);
                enrollXhr.send(enrollData);

                Swal.fire({
                    title: 'Success!',
                    text: 'You have been enrolled in the course <?php echo $productName; ?>',
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'Coursedetails.php?course_id=<?php echo $productIdentity; ?>';
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
    var btn = document.getElementById("payment-button");
    btn.onclick = function() {
        // Minimum transaction amount must be 10, i.e., 1000 in paisa.
        checkout.show({ amount: "<?php echo $price; ?>" });
    }
</script>

</body>
</html>
