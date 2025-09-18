<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOKING STATUS</title>
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            position: relative;
        }

        .cancel-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(90deg, #e74c3c, #e74c3c);
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-shadow: 0 0 3px rgba(0, 0, 0, 0.3);
            z-index: 1000;
        }

        .cancel-btn:hover {
            background: linear-gradient(90deg, #c0392b, #c0392b);
            transform: translateY(-2px);
        }

        .cancel-btn a {
            color: white;
            text-decoration: none;
        }

        .utton {
            position: absolute;
            top: 20px;
            left: 20px;
            background: linear-gradient(90deg, #6c5ce7, #a29bfe);
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-shadow: 0 0 3px rgba(0, 0, 0, 0.3);
            z-index: 1000;
        }

        .utton:hover {
            background: linear-gradient(90deg, #5a4ed1, #8e83ff);
            transform: translateY(-2px);
        }

        .utton a {
            color: white;
            text-decoration: none;
        }

        .name {
            font-size: 24px;
            font-weight: bold;
            color: white;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            animation: glow 1.5s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 10px #6c5ce7, 0 0 20px #6c5ce7, 0 0 30px #6c5ce7, 0 0 40px #6c5ce7, 0 0 50px #6c5ce7, 0 0 60px #6c5ce7, 0 0 70px #6c5ce7;
            }
            to {
                text-shadow: 0 0 20px #a29bfe, 0 0 30px #a29bfe, 0 0 40px #a29bfe, 0 0 50px #a29bfe, 0 0 60px #a29bfe, 0 0 70px #a29bfe, 0 0 80px #a29bfe;
            }
        }

        .box {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.2);
            padding: 40px;
            margin: 20px 0;
            width: 80%;
            max-width: 600px;
            backdrop-filter: blur(10px);
        }

        .content {
            text-align: center;
        }

        h1 {
            color: #ffffff;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .no-bookings {
            font-size: 18px;
            color: #a29bfe;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php
    require_once('connection.php');
    session_start();
    $email = $_SESSION['email'];

    $sql = "SELECT * FROM booking WHERE EMAIL='$email' AND BOOK_STATUS != 'Canceled' ORDER BY BOOK_ID DESC LIMIT 1";
    $name = mysqli_query($con, $sql);
    $rows = mysqli_fetch_assoc($name);

    if ($rows == null) {
        echo '<script>alert("There are no active booking details")</script>';
        ?>
        <button class="utton"><a href="vehiclesdetails.php">Go to Home</a></button>
        <div class="name">HELLO!</div>
        <div class="box">
            <div class="content">
                <p class="no-bookings">No active bookings</p>
            </div>
        </div>
        <?php
    } else {
        $sql2 = "SELECT * FROM users WHERE EMAIL='$email'";
        $name2 = mysqli_query($con, $sql2);
        $rows2 = mysqli_fetch_assoc($name2);
        $vehicle_id = $rows['VEHICLE_ID'];
        $sql3 = "SELECT * FROM vehicles WHERE VEHICLE_ID='$vehicle_id'";
        $name3 = mysqli_query($con, $sql3);
        $rows3 = mysqli_fetch_assoc($name3);
?>

    <button class="utton"><a href="vehiclesdetails.php">Go to Home</a></button>
    <div class="name">HELLO!</div>
    <button class="cancel-btn"><a href="cancelbooking.php">Cancel Booking</a></button>
    <div class="box">
        <div class="content">
            <h1>VEHICLE NAME: <?php echo htmlspecialchars($rows3['VEHICLE_NAME']); ?></h1><br>
            <h1>NO OF DAYS: <?php echo $rows['DURATION']; ?></h1><br>
            <h1>BOOKING STATUS: <?php echo $rows['BOOK_STATUS']; ?></h1><br>
        </div>
    </div>

<?php } ?>
    
</body>
</html>