<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esewa Payment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">
        <h2>Redirecting to Esewa...</h2>
        <p>Please do not close this window.</p>
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <?php 
        session_start();
        // Retrieve data from the PHP session
        $total_amount = $_SESSION['total_price'] ?? 0;
        $booking_id = $_SESSION['booking_id'] ?? 0;

        // Clear session variables after use to prevent duplicate payments
        unset($_SESSION['total_price']);
        unset($_SESSION['booking_id']);
    ?>

    <form id="esewaForm" action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
        <input type="hidden" id="amount" name="amount" value="<?php echo htmlspecialchars($total_amount - ($total_amount * 0)); ?>">
        <input type="hidden" id="tax_amount" name="tax_amount" value="<?php echo htmlspecialchars($total_amount * 0); ?>">
        <input type="hidden" id="total_amount" name="total_amount" value="<?php echo htmlspecialchars($total_amount); ?>">
        <input type="hidden" id="transaction_uuid" name="transaction_uuid">
        <input type="hidden" id="product_code" name="product_code" value="EPAYTEST">
        <input type="hidden" id="product_service_charge" name="product_service_charge" value="0">
        <input type="hidden" id="product_delivery_charge" name="product_delivery_charge" value="0">
        <input type="hidden" id="success_url" name="success_url" value="http://localhost/v/psucess.php">
        <input type="hidden" id="failure_url" name="failure_url" value="https://developer.esewa.com.np/failure">
        <input type="hidden" id="signed_field_names" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
        <input type="hidden" id="signature" name="signature">
        
        <noscript>
            <p>Please enable JavaScript to continue with the payment.</p>
            <input type="submit" value="Continue to Payment">
        </noscript>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get data from the PHP-populated form fields
            const totalAmount = document.getElementById("total_amount").value;
            const productCode = document.getElementById("product_code").value;
            const bookingId = <?php echo json_encode($booking_id); ?>;

            // Generate a unique transaction UUID using the booking ID and current timestamp
            const transactionUuid = `${bookingId}_${Date.now()}`;
            document.getElementById("transaction_uuid").value = transactionUuid;

            // Construct the message string for signature
            const message = `total_amount=${totalAmount},transaction_uuid=${transactionUuid},product_code=${productCode}`;
            const secret = "8gBm/:&EnhH.1/q"; // This is the UAT key, use your real key in production.

            // Generate the signature using HMAC-SHA256
            const hash = CryptoJS.HmacSHA256(message, secret);
            const signature = CryptoJS.enc.Base64.stringify(hash);
            document.getElementById("signature").value = signature;

            // Automatically submit the form
            document.getElementById("esewaForm").submit();
        });
    </script>
</body>
</html>