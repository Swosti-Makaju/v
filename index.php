<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VeloRent - Premium Vehicle Rental Service</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Prevent back button -->
    <script type="text/javascript">
        window.history.forward();
        function noBack() {
            window.history.forward();
        }
    </script>
</head>
<body>

<?php
    require_once('connection.php');
    if(isset($_POST['login']))
    {
        $email=$_POST['email'];
        $pass=$_POST['pass'];

        if(empty($email) || empty($pass))
        {
            echo '<script>alert("Please fill the blanks")</script>';
        }
        else{
            $query="SELECT * FROM users WHERE EMAIL='$email'";
            $res=mysqli_query($con,$query);
            if($row=mysqli_fetch_assoc($res)){
                $db_password = $row['PASSWORD'];
                if(md5($pass) == $db_password)
                {
                    header("location: vehiclesdetails.php");
                    session_start();
                    $_SESSION['email'] = $email;
                }
                else{
                    echo '<script>alert("Enter a proper password")</script>';
                }
            }
            else{
                echo '<script>alert("Enter a proper email")</script>';
            }
        }
    }
    ?>
    <!-- Loading Animation -->
    <div class="loading">
        <div class="loader"></div>
    </div>

    <div class="hai">
        <!-- Navigation Bar -->
        <nav class="navbar">
              <img style="height: 50px; " src="images\icon.png" alt="">
            <div class="menu">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="aboutus.html">About Us</a></li>
                    <li><a href="services.html">Services</a></li>
                    <li><a href="adminlogin.php">Admin</a></li>
            
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="content">
            <!-- Left Side - Hero Content -->
            <div class="hero-content">
                <h1>Rent Your <br><span>Dream Vehicle</span></h1>
                <p class="par">
                    Live the life of Luxury.<br>
                    Rent a vehicle of your wish from our vast collection.<br>
                    Enjoy every moment with your family.<br>
                    Join us to make this family vast.
                </p>
                <button class="cn"><a href="register.php">Join Us</a></button>
            </div>
            
            <!-- Right Side - Login Form -->
<div class="form-container">
    <div class="form">
        <h2>Login Here</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter Email Here" required>
            <input type="password" name="pass" placeholder="Enter Password Here" required>
            <input class="btnn" type="submit" value="Login" name="login">
        </form>
        <p class="link">Don't have an account?<br>
            <a href="register.php">Sign up</a> here
        </p>
       
    </div>
</div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 VeloRent. All Rights Reserved.</p>
        <div class="socials">
            <a href="https://www.facebook.com/thomasbhattrai"><ion-icon name="logo-facebook"></ion-icon></a>
            <a href="https://x.com/thomashbhattarai"><ion-icon name="logo-twitter"></ion-icon></a>
            <a href="https://www.instagram.com/swostimakaju/"><ion-icon name="logo-instagram"></ion-icon></a>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>