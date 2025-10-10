<?php
session_start();
if (!isset($_SESSION['confirmed_booking_id'])) {
    header("Location: vehiclesdetails.php");
    exit();
}

$booking_id = $_SESSION['confirmed_booking_id'];
unset($_SESSION['confirmed_booking_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed - VeloRent</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
        }
        .success-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">✅</div>
        <h2 class="text-success">Booking Confirmed!</h2>
        <p class="text-muted">Your vehicle has been successfully booked.</p>
        <div class="alert alert-success">
            <strong>Booking ID:</strong> #<?php echo htmlspecialchars($booking_id); ?>
        </div>
        <p>Thank you for choosing VeloRent. You will receive a confirmation email shortly.</p>
        
        <div class="mt-4">
            <a href="bookingstatus.php" class="btn btn-success me-2">View My Bookings</a>
            <a href="vehiclesdetails.php" class="btn btn-outline-secondary">Return to Home</a>
        </div>
    </div>
</body>
</html>