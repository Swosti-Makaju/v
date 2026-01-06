<?php
require_once('connection.php');
session_start();

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $pass = $_POST['pass'];

    if (empty($email) || empty($pass)) {
        $error = "All fields are required";
    } else {
        $q = "SELECT * FROM users WHERE EMAIL='$email'";
        $res = mysqli_query($con, $q);

        if ($row = mysqli_fetch_assoc($res)) {
            if (md5($pass) == $row['PASSWORD']) {
                $_SESSION['email'] = $email;
                header("location: vehiclesdetails.php");
                exit();
            } else {
                $error = "Invalid password";
            }
        } else {
            $error = "Email not found";
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login | VeloRent</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            background: #f8f9fa;
            font-family: Poppins, sans-serif;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 8%;
            background: #fff;
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
            text-decoration: none;
            color: #2c3e50;
            font-weight: 500;
        }

        .menu a:hover {
            color: #3498db;
        }

        .form {
            background: #fff;
            max-width: 420px;
            margin: 120px auto;
            padding: 45px;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .form h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form input {
            width: 100%;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .btnn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: 12px;
            cursor: pointer;
        }

        .error {
            background: #fadbd8;
            color: #c0392b;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
            text-align: center;
        }

        a {
            text-decoration: none;
            color: #667eea;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a href="index.php">
            <img style="height: 50px;" src="images/icon.png" alt="VeloRent Logo">
        </a>
        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="menu" id="menu">
            <ul>
                <li>
                    <a href="index.php">Home</a>
                </li>
                <li>
                    <a href="aboutus.html">About Us</a>
                </li>
                <li>
                    <a href="services.html">Services</a>
                </li>
                <li><a href="packages.php">Packages</a></li>
                <li><a href="login.php">Login</a></li>
                <li>
                    <a href="adminlogin.php">Admin</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="form">
        <h2>User Login</h2>

        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="pass" placeholder="Password" required>
            <input type="submit" name="login" value="Login" class="btnn">
        </form>

        <p style="text-align:center;margin-top:15px;">
            New user? <a href="register.php">Create Account</a>
        </p>
    </div>

</body>

</html>