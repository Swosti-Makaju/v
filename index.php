<?php
    require_once('connection.php');
    session_start(); 
    
    // Fetch all available vehicles
    $sql = "SELECT * FROM vehicles WHERE AVAILABLE='Y'";
    $vehicles = mysqli_query($con, $sql);
    $vehicleCount = mysqli_num_rows($vehicles);
    $showLimit = 24; 

    if(isset($_POST['login'])) {
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $pass = $_POST['pass'];

        if(empty($email) || empty($pass)) {
            $error_message = "Please fill in all fields";
        } else {
            $query = "SELECT * FROM users WHERE EMAIL='$email'";
            $res = mysqli_query($con, $query);
            if($row = mysqli_fetch_assoc($res)) {
                $db_password = $row['PASSWORD'];
                if(md5($pass) == $db_password) {
                    $_SESSION['email'] = $email;
                    header("location: vehiclesdetails.php");
                    exit();
                } else {
                    $error_message = "Invalid password";
                }
            } else {
                $error_message = "Email not found";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VeloRent - Premium Vehicle Rental Service</title>
    <style>
        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f8f9fa;
            color: #2c3e50;
            line-height: 1.6;
        }

        /* --- Navbar: Clean White Theme --- */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 8%;
            background: #ffffff;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        }

        .menu ul {
            display: flex;
            list-style: none;
        }

        .menu li {
            margin-left: 35px;
        }

        .menu a {
            color: #2c3e50;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: 0.3s;
        }

        .menu a:hover {
            color: #3498db;
        }

        /* --- Hero Section --- */
        .hai {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 90vh;
        }

        .content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            padding: 160px 8% 80px;
            gap: 40px;
        }

        .hero-content h1 {
            font-size: 3.8rem;
            color: #ffffff;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .hero-content h1 span {
            color: #ffd700;
        }

        .par {
            font-size: 1.1rem;
            color: #f0f0f0;
            margin-bottom: 35px;
            max-width: 500px;
        }

        /* --- Login Form: Clean White Card --- */
        .form {
            background: #ffffff;
            padding: 45px;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            border: none;
            width: 100%;
        }

        .form h2 {
            color: #2c3e50;
            margin-bottom: 25px;
            font-weight: 700;
            text-align: center;
        }

        .form input[type="email"],
        .form input[type="password"] {
            width: 100%;
            padding: 16px;
            margin-bottom: 18px;
            border: 2px solid #e0e6ed;
            border-radius: 12px;
            background: #f8f9fa;
            color: #2c3e50;
            outline: none;
            transition: 0.3s;
        }

        .form input:focus {
            border-color: #3498db;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
        }

        .btnn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            padding: 16px;
            border: none;
            border-radius: 12px;
            width: 100%;
            transition: 0.3s;
        }

        .btnn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .book-btn {
            display: inline-block;
            width: 100%;
            text-align: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            margin-top: 15px;
            transition: 0.3s;
        }

        .book-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        /* --- Vehicle Showcase --- */
        .vehicle-showcase {
            padding: 100px 8%;
            background: #f8f9fa;
            text-align: center;
        }

        .vehicle-showcase h2 {
            color: #2c3e50;
        }

        .vehicle-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .vehicle-card {
            background: #ffffff;
            border: 2px solid #e0e6ed;
            border-radius: 20px;
            padding: 25px;
            transition: 0.4s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .vehicle-card:hover {
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.2);
            transform: translateY(-8px);
            border-color: #667eea;
        }

        .vehicle-card img {
            width: 100%;
            border-radius: 12px; 
            height: 180px;
            object-fit: cover;
        }

        .vehicle-card h3 {
            margin-top: 15px;
            color: #2c3e50;
        }

        .vehicle-card p {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .vehicle-card .price {
            font-weight: 700;
            color: #667eea;
            font-size: 1.1rem;
        }

        .forgot-link {
            display: block;
            text-align: right;
            margin-bottom: 20px;
            font-size: 0.85rem;
            color: #7f8c8d;
            text-decoration: none;
            transition: 0.3s;
        }

        .forgot-link:hover {
            color: #3498db;
        }

       footer {
            background: rgba(0, 0, 0, 0.05);
            padding: 10px 5%;
            text-align: center;
            margin-top: 80px;
        }

        footer p {
            margin-bottom: 20px;
            color: #524f4f;
        }

        .socials {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .socials a {
            color: #333;
            font-size: 1.5rem;
            transition: color 0.3s, transform 0.3s;
        }

        .socials a:hover {
            color: #667eea;
        }

        .cta-button {
            background: #ffd700;
            padding: 15px 35px;
            border-radius: 30px;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .cta-button:hover {
            background: #ffed4e;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 215, 0, 0.3);
        }

        .cta-button a {
            color: #2c3e50;
            text-decoration: none;
            font-weight: 600;
        }

        .view-all-btn {
            padding: 12px 30px;
            background: #2c3e50;
            color: #fff;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 500;
            transition: 0.3s;
            display: inline-block;
        }

        .view-all-btn:hover {
            background: #34495e;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(44, 62, 80, 0.3);
        }

        .error-message {
            color: #e74c3c;
            background: #fadbd8;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 0.9rem;
            text-align: center;
            border-left: 4px solid #e74c3c;
        }

        .signup-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .signup-link a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
        }

        .signup-link a:hover {
            color: #764ba2;
        }
        
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="index.php"><img src="images/icon.png" alt="VeloRent Logo" style="height: 55px;"></a>
        <div class="menu" id="menu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="aboutus.html">About Us</a></li>
                <li><a href="services.html">Services</a></li>
                <li><a href="adminlogin.php">Admin</a></li>
            </ul>
        </div>
    </nav>

    <div class="hai">
        <div class="content">
            <div class="hero-content">
                <h1>Rent Your <br><span>Dream Vehicle</span></h1>
                <p class="par">
                    Luxury meets affordability. Explore our collection of premium vehicles and start your journey today.
                </p>
                <button class="cta-button">
                    <a href="register.php">Join Us Now</a>
                </button>
            </div>
            
            <div class="form-container" style="max-width: 420px; width: 100%;">
                <div class="form">
                    <h2>Login</h2>
                    
                    <?php if(isset($error_message)): ?>
                        <div class="error-message">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <input type="email" name="email" placeholder="Email Address" required>
                        <input type="password" name="pass" placeholder="Password" required>
                        <a href="forgotpassword.php" class="forgot-link">Forgot Password?</a>
                        <input class="btnn" type="submit" value="Login" name="login">
                    </form>
                    <p class="signup-link">
                        New here? <a href="register.php">Create an account</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <section class="vehicle-showcase">
        <h2 style="font-size: 2.5rem; margin-bottom: 50px;">Featured Fleet</h2>
        <div class="vehicle-grid">
            <?php
            $count = 0;
            mysqli_data_seek($vehicles, 0);
            while ($row = mysqli_fetch_assoc($vehicles)):
                if ($count >= $showLimit) break;
                $count++;
            ?>
                <div class="vehicle-card">
                    <img src="images/<?php echo $row['VEHICLE_IMG']; ?>" alt="<?php echo $row['VEHICLE_NAME']; ?>">
                    <h3><?php echo $row['VEHICLE_NAME']; ?></h3>
                    <p>
                        <?php echo $row['FUEL_TYPE']; ?> • <?php echo $row['CAPACITY']; ?> Seater
                    </p>
                    <p class="price">Rs. <?php echo $row['PRICE']; ?> / day</p>
                    <a href="register.php?id=<?php echo $row['VEHICLE_ID']; ?>" class="book-btn">Book Now</a>
                </div>
            <?php endwhile; ?>
        </div>
        
        <div style="margin-top: 50px;">
            <a href="register.php" class="view-all-btn">View All Vehicles</a>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 VeloRent. All Rights Reserved.</p>
         <div class="socials">
        <a href="https://www.facebook.com/thomasbhattrai " target="_blank"><ion-icon name="logo-facebook"></ion-icon></a>
        <a href="https://x.com/ " target="_blank"><ion-icon name="logo-twitter"></ion-icon></a>
        <a href="https://www.instagram.com/swostimakaju/ " target="_blank"><ion-icon name="logo-instagram"></ion-icon></a>
    </div>
    </footer>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>