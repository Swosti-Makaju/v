<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMINISTRATOR</title>
    <link rel="stylesheet" href="css/adminbook.css">
</head>
<body>
<div class="main-content">
    <div class="navbar">
        <img style="height: 50px;" src="images/icon.png" alt="">
        <div class="menu">
            <ul>
                <li><a href="adminvehicle.php">VEHICLE MANAGEMENT</a></li>
                <li><a href="adminusers.php">USERS</a></li>
                <li><a href="adminbook.php">BOOKING REQUEST</a></li>
                <li><a href="index.php" class="button">LOGOUT</a></li>
            </ul>
        </div>
    </div>

    <h1 class="header">BOOKINGS</h1>

    <table class="content-table">
        <thead>
            <tr>
                <th>VEHICLE ID</th>
                <th>EMAIL</th>
                <th>BOOK PLACE</th>
                <th>BOOK DATE</th>
                <th>DURATION</th>
                <th>PHONE NUMBER</th>
                <th>DESTINATION</th>
                <th>RETURN DATE</th>
                <th>BOOKING STATUS</th>
                <th>APPROVE</th>
                <th>VEHICLE RETURNED</th>
            </tr>
        </thead>
        <tbody>
        <?php
            require_once('connection.php');
            $query = "SELECT * FROM booking ORDER BY BOOK_ID DESC";
            $queryy = mysqli_query($con, $query);
            while ($res = mysqli_fetch_array($queryy)) {
                $isApproved = $res['BOOK_STATUS'] === 'APPROVED';
        ?>
            <tr id="row-<?php echo $res['BOOK_ID']; ?>">
                <td><?php echo $res['VEHICLE_ID']; ?></td>
                <td><?php echo $res['EMAIL']; ?></td>
                <td><?php echo $res['BOOK_PLACE']; ?></td>
                <td><?php echo $res['BOOK_DATE']; ?></td>
                <td><?php echo $res['DURATION']; ?></td>
                <td><?php echo $res['PHONE_NUMBER']; ?></td>
                <td><?php echo $res['DESTINATION']; ?></td>
                <td><?php echo $res['RETURN_DATE']; ?></td>
                <td><?php echo $res['BOOK_STATUS']; ?></td>
                <td>
                    <a href="approve.php?id=<?php echo $res['BOOK_ID']; ?>" 
                       class="button approve-btn" 
                       data-bookid="<?php echo $res['BOOK_ID']; ?>" 
                       <?php echo $isApproved ? 'style="display:none;"' : ''; ?>>APPROVE</a>
                </td>
                <td>
                    <a href="adminreturn.php?id=<?php echo $res['VEHICLE_ID']; ?>&bookid=<?php echo $res['BOOK_ID']; ?>" 
                       class="button return-btn" 
                       <?php echo !$isApproved ? 'style="display:none;"' : ''; ?>>RETURNED</a>
                </td>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.approve-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const bookId = this.getAttribute('data-bookid');
            fetch(`approve.php?id=${bookId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const row = document.getElementById(`row-${bookId}`);
                        row.querySelector('.approve-btn').style.display = 'none';
                        row.querySelector('.return-btn').style.display = 'inline-block';
                        row.querySelector('td:nth-child(9)').textContent = 'APPROVED';
                    } else {
                        alert('Error approving booking');
                    }
                });
        });
    });

    document.querySelectorAll('.return-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const row = this.closest('tr');
                        row.remove();
                    } else {
                        alert('Error processing return');
                    }
                });
        });
    });
});
</script>
</body>
</html>