<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMINISTRATOR</title>
    <link rel="stylesheet" href="css/adminvehicle.css">
    
</head>
<body>

<div class="main-content">
    <div class="navbar">
          <img style="height: 50px; " src="images\icon.png" alt="">
        <div class="menu">
            <ul>
                <li><a href="adminvehicle.php">VEHICLE MANAGEMENT</a></li>
                <li><a href="adminusers.php">USERS</a></li>
                <li><a href="adminbook.php">BOOKING REQUEST</a></li>
                <li><a href="index.php" class="button">LOGOUT</a></li>
            </ul>
        </div>
    </div>

    <div class="header-container">
        <h1 class="header">VEHICLES</h1>
        <a href="addvehicle.php" class="add">+ ADD VEHICLES</a>
    </div>

    <table class="content-table">
        <thead>
            <tr>
                <th>VEHICLE ID</th>
                <th>VEHICLE NAME</th>
                <th>VEHICLE TYPE</th>
                <th>FUEL TYPE</th>
                <th>CAPACITY</th>
                <th>PRICE</th>
                <th>AVAILABLE</th>
                <th>DELETE</th>
            </tr>
        </thead>
        <tbody>
        <?php
            require_once('connection.php');
            $query="SELECT * FROM vehicles";    
            $queryy=mysqli_query($con,$query);
            while($res=mysqli_fetch_array($queryy)) {
        ?>
            <tr class="active-row">
                <td><?php echo $res['VEHICLE_ID']; ?></td>
                <td><?php echo $res['VEHICLE_NAME']; ?></td>
                <td><?php echo $res['VEHICLE_TYPE']; ?></td>
                <td><?php echo $res['FUEL_TYPE']; ?></td>
                <td><?php echo $res['CAPACITY']; ?></td>
                <td><?php echo $res['PRICE']; ?></td>
                <td><?php echo $res['AVAILABLE'] == 'Y' ? 'YES' : 'NO'; ?></td>
                <td><button class="button"><a href="deletevehicle.php?id=<?php echo $res['VEHICLE_ID']; ?>" style="color: white; text-decoration: none;">DELETE VEHICLE</a></button></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<footer>
    <p>&copy; 2024 VeloRent. All Rights Reserved.</p>
    <div class="socials">
        <a href="#"><ion-icon name="logo-facebook"></ion-icon></a>
        <a href="#"><ion-icon name="logo-twitter"></ion-icon></a>
        <a href="#"><ion-icon name="logo-instagram"></ion-icon></a>
    </div>
</footer>

<script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>
