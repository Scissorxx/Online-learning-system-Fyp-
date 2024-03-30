<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />

    <title>Admin Dashboard</title>
</head>
<body>
<?php
     include("SideBar.php");
    ?>
    
      <section id="content">
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link">Categories</a>
			
			<input type="checkbox" id="switch-mode" hidden>
			
			<a href="#" class="profile">
				<img src="../../Media/Default/default.jpg">
			</a>
		</nav>

        <main>
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Home</a>
						</li>
					</ul>
				</div>
				
			</div>

	
		</main>
        </section>
        <script src="../Script/Admin.js"></script>
    
    
</body>
</html>
