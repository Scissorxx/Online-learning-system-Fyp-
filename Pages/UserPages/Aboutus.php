<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../CSS/User-Css/Courses.css">
    <style>
        .about .container .about-content {
            padding-top: 20rem;
        }
        .about .container .about-content h3 {
            font-size: 3rem;
            color: var(--primary-color);
        }
        
       
        .about {
            padding: 80px 0;
            background-color: #f9f9f9;
        }

        .about-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .about-image {
            margin-bottom: 40px;
        }

        .about-image img {
            max-width: 100%;
            height: auto;
        }

        .about-content h3 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 20px;
        }

        .about-content p {
            font-size: 1.6rem;
            color: #666;
            line-height: 1.8;
            margin-bottom: 15px;
        }

        .about-content ul {
            list-style-type: none;
            padding: 0;
            margin-bottom: 20px;
        }

        .about-content ul li {
            font-size: 2rem;
            color: #888;
            margin-bottom: 10px;
        }

        /* Media query for smaller screens */
        @media (max-width: 768px) {
            .about-content {
                padding: 0 20px;
            }
        }
    </style>
    <title>About us</title>
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
                    <li>About Us</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="about" id="about">
        <div class="container">
            <figure class="about-image">
                <!-- <img src="../../Media/Default/about.png" alt="About Us Image" height="500"> -->
                <!-- <img src="../../Media/Default/aboutus.jpg" alt="About Us Image" class="about-img"> -->
            </figure>
            <div class="about-content">
                <h3>Empowering Learning for 18 Years</h3>
                <p>Welcome to our online learning platform! With 18 years of experience, we have been dedicated to providing accessible and high-quality education to learners worldwide. Our mission is to make learning engaging, interactive, and convenient for everyone.</p>
                <p>Through our innovative courses and expert instructors, we strive to empower individuals to achieve their academic and professional goals. Whether you're looking to enhance your skills, explore new interests, or advance your career, we have the resources and support you need to succeed.</p>
                <p>At our core, we believe in:</p>
                <ul>
                    <li>Continuous improvement and innovation in education</li>
                    <li>Creating a supportive and inclusive learning community</li>
                    <li>Adapting to the ever-changing needs of our learners</li>
                    <li>Providing personalized learning experiences</li>
                </ul>
                <p>Join us in our journey of lifelong learning and discovery!</p>
            </div>
        </div>
    </section>
    
</body>

</html>
