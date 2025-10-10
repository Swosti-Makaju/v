<?php
session_start();

// Check if there's a pending booking in session
if (!isset($_SESSION['pending_booking'])) {
    die("No pending booking found. Please start the booking process again.");
}

// Retrieve data from the PHP session
$booking_data = $_SESSION['pending_booking'];
$total_amount = $_SESSION['total_price'] ?? 0;

// Generate a temporary booking ID for payment reference
$temp_booking_id = uniqid('temp_', true);
$_SESSION['temp_booking_id'] = $temp_booking_id;

// Store the booking data with temp ID for verification after payment
$_SESSION['pending_booking']['temp_id'] = $temp_booking_id;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esewa Payment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .payment-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 500px;
            margin: auto;
        }
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
        .booking-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
        }
        .detail-value {
            color: #212529;
        }
        .total-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: #28a745;
            text-align: center;
            margin: 15px 0;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="payment-container text-center">
        <div class="mb-4">
            <h2 class="text-primary">Redirecting to eSewa</h2>
            <p class="text-muted">Please wait while we process your payment</p>
        </div>

        <div class="booking-details text-start">
            <h5 class="text-center mb-3">Booking Summary</h5>
            <div class="detail-item">
                <span class="detail-label">Vehicle:</span>
                <span class="detail-value"><?php echo htmlspecialchars($booking_data['vehicle_name']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Booking Date:</span>
                <span class="detail-value"><?php echo htmlspecialchars($booking_data['book_date']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Return Date:</span>
                <span class="detail-value"><?php echo htmlspecialchars($booking_data['return_date']); ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Duration:</span>
                <span class="detail-value"><?php echo htmlspecialchars($booking_data['duration']); ?> days</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Destination:</span>
                <span class="detail-value"><?php echo htmlspecialchars($booking_data['destination']); ?></span>
            </div>
            <div class="total-amount">
                Total: Rs. <?php echo number_format($total_amount, 2); ?>
            </div>
        </div>

        <div class="spinner-border text-primary mb-3" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="text-muted small">Do not close or refresh this page</p>
    </div>

    <form id="esewaForm" action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
        <input type="hidden" id="amount" name="amount" value="<?php echo htmlspecialchars($total_amount); ?>">
        <input type="hidden" id="tax_amount" name="tax_amount" value="0">
        <input type="hidden" id="total_amount" name="total_amount" value="<?php echo htmlspecialchars($total_amount); ?>">
        <input type="hidden" id="transaction_uuid" name="transaction_uuid">
        <input type="hidden" id="product_code" name="product_code" value="EPAYTEST">
        <input type="hidden" id="product_service_charge" name="product_service_charge" value="0">
        <input type="hidden" id="product_delivery_charge" name="product_delivery_charge" value="0">
        <input type="hidden" id="success_url" name="success_url" value="http://localhost/v/psucess.php">
        <input type="hidden" id="failure_url" name="failure_url" value="http://localhost/v/pfailure.php">
        <input type="hidden" id="signed_field_names" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
        <input type="hidden" id="signature" name="signature">
        
        <noscript>
            <div class="text-center mt-3">
                <p class="text-warning">Please enable JavaScript to continue with the payment.</p>
                <input type="submit" class="btn btn-primary" value="Continue to Payment">
            </div>
        </noscript>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get data from the PHP-populated form fields
            const totalAmount = document.getElementById("total_amount").value;
            const productCode = document.getElementById("product_code").value;
            const tempBookingId = "<?php echo $temp_booking_id; ?>";

            // Generate a unique transaction UUID using the temp booking ID and current timestamp
            const transactionUuid = `<?php echo $temp_booking_id; ?>_${Date.now()}`;
            document.getElementById("transaction_uuid").value = transactionUuid;

            // Construct the message string for signature
            const message = `total_amount=${totalAmount},transaction_uuid=${transactionUuid},product_code=${productCode}`;
            const secret = "8gBm/:&EnhH.1/q"; // This is the UAT key, use your real key in production.

            // Generate the signature using HMAC-SHA256
            const hash = CryptoJS.HmacSHA256(message, secret);
            const signature = CryptoJS.enc.Base64.stringify(hash);
            document.getElementById("signature").value = signature;

            // Add a small delay to show the loading screen before redirecting
            setTimeout(function() {
                document.getElementById("esewaForm").submit();
            }, 2000); // 2 second delay to show the booking summary
        });
    </script>
</body>
</html>