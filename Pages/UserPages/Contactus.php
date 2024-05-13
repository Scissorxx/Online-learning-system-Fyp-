<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../../CSS/User-Css/Courses.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-jx6WXSa9CFddo/NQs9Mv78v0CjB+l/5XmlymwHt8bndqvNvhIz1KpRIkAWcJcTbfLs1lxXTIt4deNlOeUHX1Ow==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* ends */
.Contactus{
  background-color: #eeeded;
}
.wrapper{
  margin-left: 300px;
  width: 715px;
  background: #fff;
  border-radius: 5px;
  box-shadow: 10px 10px 10px rgba(0,0,0,0.05);
}
.wrapper header{
  font-size: 22px;
  font-weight: 600;
  padding: 20px 30px;
  border-bottom: 1px solid #ccc;
}
.wrapper form{
  margin: 35px 30px;
}
.wrapper form.disabled{
  pointer-events: none;
  opacity: 0.7;
}
form .dbl-field{
  display: flex;
  margin-bottom: 25px;
  justify-content: space-between;
}
.dbl-field .field{
  height: 50px;
  display: flex;
  position: relative;
  width: calc(100% / 2 - 13px);
}
.wrapper form i{
  position: absolute;
  top: 50%;
  left: 18px;
  color: #ccc;
  font-size: 17px;
  pointer-events: none;
  transform: translateY(-50%);
}
form .field input,
form .message textarea{
  width: 100%;
  height: 100%;
  outline: none;
  padding: 0 18px 0 48px;
  font-size: 16px;
  border-radius: 5px;
  border: 1px solid #ccc;
}
.field input::placeholder,
.message textarea::placeholder{
  color: #ccc;
}
.field input:focus,
.message textarea:focus{
  padding-left: 47px;
  border: 2px solid #0D6EFD;
}
.field input:focus ~ i,
.message textarea:focus ~ i{
  color: #0D6EFD;
}
form .message{
  position: relative;
}
form .message i{
  top: 30px;
  font-size: 20px;
}
form .message textarea{
  min-height: 130px;
  max-height: 230px;
  max-width: 100%;
  min-width: 100%;
  padding: 15px 20px 0 48px;
}
form .message textarea::-webkit-scrollbar{
  width: 0px;
}
.message textarea:focus{
  padding-top: 14px;
}
form .button-area{
  margin: 25px 0;
  display: flex;
  align-items: center;
}
.button-area button{
  color: #fff;
  border: none;
  outline: none;
  font-size: 18px;
  cursor: pointer;
  border-radius: 5px;
  padding: 13px 25px;
  background: #0D6EFD;
  transition: background 0.3s ease;
}
.button-area button:hover{
  background: #025ce3;
}
.button-area span{
  font-size: 17px;
  margin-left: 30px;
  display: none;
}
@media (max-width: 600px){
  .wrapper header{
    text-align: center;
  }
  .wrapper form{
    margin: 35px 20px;
  }
  form .dbl-field{
    flex-direction: column;
    margin-bottom: 0px;
  }
  form .dbl-field .field{
    width: 100%;
    height: 45px;
    margin-bottom: 20px;
  }
  form .message textarea{
    resize: none;
  }
  form .button-area{
    margin-top: 20px;
    flex-direction: column;
  }
  .button-area button{
    width: 100%;
    padding: 11px 0;
    font-size: 16px;
  }
  .button-area span{
    margin: 20px 0 0;
    text-align: center;
  }
}
    </style>
    <title>Courses</title>
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
                <li>Contact Us</li>
            </ul>
        </div>
    </div>
</section>
    
   
<section class="Contactus" id="contact">
    <!-- <h1 class="heading">Contact Us</h1> -->
    <div class="wrapper">
        <header>Send us a Message</header>
        <form action="process_form.php" method="post">
            <div class="dbl-field">
                <div class="field">
                    <input type="text" name="name" placeholder="Enter your name">
                    <i class='fas fa-user'></i>
                </div>
                <div class="field">
                    <input type="text" name="email" placeholder="Enter your email">
                    <i class='fas fa-envelope'></i>
                </div>
            </div>
            <div class="dbl-field">
                <div class="field">
                    <input type="text" name="phone" placeholder="Enter your phone">
                    <i class='fas fa-phone-alt'></i>
                </div>

            </div>
            <div class="message">
                <textarea placeholder="Write your message" name="message"></textarea>
                <i class="material-icons">message</i>
            </div>
            <div class="button-area">
                <button type="submit" name="submit">Send Message</button>
                <span></span>
            </div>
        </form>
    </div>
</section>
<?php
include("footer.php");
?>

   
</body>
</html>
