<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/adminusers.css">
    <title>ADMINISTRATOR</title>
    
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

    <h1 class="header">USERS</h1>

    <table class="content-table">
        <thead>
            <tr>
                <th>NAME</th> 
                <th>EMAIL</th>
                <th>LICENSE NUMBER</th>
                <th>PHONE NUMBER</th> 
                <th>GENDER</th> 
                <th>DELETE USERS</th>
            </tr>
        </thead>
        <tbody>
        <?php
        require_once('connection.php');
        $query = "SELECT * FROM users";
        $queryy = mysqli_query($con, $query);
        while ($res = mysqli_fetch_array($queryy)) { ?>
            <tr>
                <td><?php echo $res['FNAME'] . " " . $res['LNAME']; ?></td>
                <td><?php echo $res['EMAIL']; ?></td>
                <td><?php echo $res['LIC_NUM']; ?></td>
                <td><?php echo $res['PHONE_NUMBER']; ?></td>
                <td><?php echo $res['GENDER']; ?></td>
                <td><a href="deleteuser.php?id=<?php echo $res['EMAIL']; ?>" class="button">DELETE USER</a></td>
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
