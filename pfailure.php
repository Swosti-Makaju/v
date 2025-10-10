<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed - VeloRent</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .failure-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
        }
        .failure-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="failure-container">
        <div class="failure-icon">❌</div>
        <h2 class="text-danger">Payment Failed</h2>
        <p class="text-muted">We're sorry, but your payment could not be processed.</p>
        
        <?php
        $reason = $_GET['reason'] ?? 'unknown';
        switch($reason) {
            case 'no_booking_data':
                echo "<p class='alert alert-warning'>No booking data found. Please start the booking process again.</p>";
                break;
            case 'database_error':
                echo "<p class='alert alert-warning'>There was an error processing your booking. Please contact support.</p>";
                break;
            case 'payment_verification_failed':
                echo "<p class='alert alert-warning'>Payment verification failed. Please try again.</p>";
                break;
            default:
                echo "<p class='alert alert-warning'>An unexpected error occurred. Please try again.</p>";
        }
        ?>
        
        <div class="mt-4">
            <a href="vehiclesdetails.php" class="btn btn-primary me-2">Try Again</a>
            <a href="index.php" class="btn btn-outline-secondary">Return to Home</a>
        </div>
        
        <?php
        // Clear session data on failure
        unset($_SESSION['pending_booking']);
        unset($_SESSION['total_price']);
        unset($_SESSION['temp_booking_id']);
        ?>
    </div>
</body>
</html>