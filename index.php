<?php
require_once('connection.php');
session_start(); 

$sql = "SELECT * FROM vehicles WHERE AVAILABLE='Y'";
$vehicles = mysqli_query($con, $sql);
$showLimit = 24;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>VeloRent - Premium Vehicle Rental Service</title>

<style>
/* ---------- Base ---------- */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}
body{
    background:#f8f9fa;
    color:#2c3e50;
    line-height:1.6;
}

/* ---------- Navbar ---------- */
.navbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 8%;
    background:#fff;
    position:fixed;
    top:0;
    width:100%;
    z-index:1000;
    box-shadow:0 2px 20px rgba(0,0,0,0.06);
}
.menu ul{
    display:flex;
    list-style:none;
}
.menu li{ margin-left:35px; }
.menu a{
    text-decoration:none;
    color:#2c3e50;
    font-weight:500;
}
.menu a:hover{ color:#3498db; }

/* ---------- Hero Section ---------- */
.hai{
    position:relative;
    min-height:80vh;
    background:url(images/car.png) no-repeat right center;
    background-size:65%;
}

/* Overlay */
.hai::before{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(
        to right,
        rgba(255,255,255,0.95) 45%,
        rgba(255,255,255,0.4) 70%,
        rgba(255,255,255,0) 100%
    );
}

.content{
    position:relative;
    z-index:1;
    min-height:80vh;
    display:flex;
    align-items:center;
    padding:160px 4% 80px;
}

.hero-content{
    max-width:480px;
    margin-left:-30px;
}

.hero-content h1{
    font-size:3.8rem;
    line-height:1.1;
    color:#2c3e50;
}
.hero-content h1 span{
    color:#ffd700;
}
.par{
    font-size:1.1rem;
    color:#555;
    margin:25px 0 35px;
}

.cta-button{
    background:#ffd700;
    padding:15px 35px;
    border-radius:30px;
    border:none;
    cursor:pointer;
}
.cta-button a{
    text-decoration:none;
    font-weight:600;
    color:#2c3e50;
}

/* ---------- Vehicle Section ---------- */
.vehicle-showcase{
    padding:100px 8%;
    text-align:center;
}
.vehicle-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:30px;
}
.vehicle-card{
    background:#fff;
    border-radius:20px;
    padding:25px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
    transition:.3s;
}
.vehicle-card:hover{
    transform:translateY(-8px);
    box-shadow:0 15px 40px rgba(102,126,234,.25);
}
.vehicle-card img{
    width:100%;
    height:180px;
    object-fit:cover;
    border-radius:12px;
}
.vehicle-card h3{ margin-top:15px; }
.price{
    font-weight:700;
    color:#667eea;
}
.book-btn{
    display:block;
    margin-top:20px;
    padding:14px;
    background:linear-gradient(135deg,#667eea,#764ba2);
    color:#fff;
    text-decoration:none;
    border-radius:12px;
}

/* ---------- Footer ---------- */
footer{
    margin-top:80px;
    background:rgba(0,0,0,0.05);
    padding:20px 5%;
    text-align:center;
}
.socials{
    display:flex;
    justify-content:center;
    gap:20px;
}
.socials a{
    font-size:1.5rem;
    color:#333;
}
.socials a:hover{ color:#667eea; }

/* ---------- Responsive ---------- */
@media(max-width:768px){
    .hai{
        background-position:center bottom;
        background-size:90%;
    }
    .content{
        padding:120px 6% 60px;
        text-align:center;
    }
    .hero-content{
        margin-left:0;
        max-width:100%;
    }
}
</style>
</head>

<body>

<nav class="navbar">
    <a href="index.php"><img src="images/icon.png" height="55"></a>
    <div class="menu">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="aboutus.html">About Us</a></li>
            <li><a href="services.html">Services</a></li>
            <li><a href="packages.php">Packages</a></li>
             <li><a href="login.php">Login</a></li>
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
    </div>
</div>

<section class="vehicle-showcase">
<h2 style="font-size:2.5rem;margin-bottom:50px;">Featured Fleet</h2>
<div class="vehicle-grid">
<?php
$count=0;
while($row=mysqli_fetch_assoc($vehicles)){
    if($count++ >= $showLimit) break;
?>
<div class="vehicle-card">
    <img src="images/<?php echo $row['VEHICLE_IMG']; ?>">
    <h3><?php echo $row['VEHICLE_NAME']; ?></h3>
    <p><?php echo $row['FUEL_TYPE']; ?> • <?php echo $row['CAPACITY']; ?> Seater</p>
    <p class="price">Rs. <?php echo $row['PRICE']; ?> / day</p>
    <a href="login.php?id=<?php echo $row['VEHICLE_ID']; ?>" class="book-btn">Book Now</a>
</div>
<?php } ?>
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
