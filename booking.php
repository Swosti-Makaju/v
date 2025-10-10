<?php
session_start();
require_once('connection.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$vehicleid = $_GET['id'];

// Fetch vehicle details
$sql = "SELECT * FROM vehicles WHERE VEHICLE_ID='$vehicleid'";
$cname = mysqli_query($con, $sql);
$vehicle = mysqli_fetch_assoc($cname);

// Fetch user details
$value = $_SESSION['email'];
$sql = "SELECT * FROM users WHERE EMAIL='$value'";
$name = mysqli_query($con, $sql);
$user = mysqli_fetch_assoc($name);
$uemail = $user['EMAIL'];
$base_price = $vehicle['PRICE'];

// Dynamic Pricing Algorithm
function calculateDynamicPrice($base_price, $booking_date, $duration, $vehicle_type)
{
    // Demand Factor: Increase price by 20% on weekends
    $date = new DateTime($booking_date);
    $day_of_week = $date->format('N'); // 1 (Monday) to 7 (Sunday)
    $demand_factor = ($day_of_week >= 6) ? 1.2 : 1.0; // 20% increase on weekends (Saturday/Sunday)

    // Seasonal Factor: Adjust based on month (e.g., peak season: June, July, December)
    $month = $date->format('n'); // 1 to 12
    $peak_months = [6, 7, 12]; // June, July, December
    $seasonal_factor = in_array($month, $peak_months) ? 1.3 : 1.0; // 30% increase in peak season

    // Duration Factor: Discount for longer bookings
    $duration_factor = 1.0;
    if ($duration >= 7) {
        $duration_factor = 0.9; // 10% discount for bookings of 7+ days
    } elseif ($duration >= 3) {
        $duration_factor = 0.95; // 5% discount for bookings of 3-6 days
    }

    // Calculate final price
    $dynamic_price = $base_price * $demand_factor * $seasonal_factor * $duration_factor;
    return round($dynamic_price, 2);
}

if (isset($_POST['book'])) {
    $bplace = mysqli_real_escape_string($con, $_POST['place']);
    $bdate = date('Y-m-d', strtotime($_POST['date']));
    $dur = mysqli_real_escape_string($con, $_POST['dur']);
    $phno = mysqli_real_escape_string($con, $_POST['ph']);
    $des = mysqli_real_escape_string($con, $_POST['des']);
    $rdate = date('Y-m-d', strtotime($_POST['rdate']));

    if (empty($bplace) || empty($bdate) || empty($dur) || empty($phno) || empty($des) || empty($rdate)) {
        echo '<script>alert("Please fill all fields")</script>';
    } else {
        if ($bdate < $rdate) {
            // Assume vehicle type is stored in the vehicles table; adjust as per your database schema
            $vehicle_type = isset($vehicle['VEHICLE_TYPE']) ? $vehicle['VEHICLE_TYPE'] : 'Standard';
            $dynamic_price = calculateDynamicPrice($base_price, $bdate, $dur, $vehicle_type);
            $total_price = $dynamic_price * $dur;

            // Store booking data in session instead of inserting into database
            $_SESSION['pending_booking'] = [
                'vehicle_id' => $vehicleid,
                'email' => $uemail,
                'book_place' => $bplace,
                'book_date' => $bdate,
                'duration' => $dur,
                'phone_number' => $phno,
                'destination' => $des,
                'price' => $total_price,
                'return_date' => $rdate,
                'vehicle_name' => $vehicle['VEHICLE_NAME']
            ];
            
            $_SESSION['total_price'] = $total_price;
            
            header("Location: esewa-intregation.php");
            exit();
        } else {
            echo '<script>alert("Please enter a correct return date")</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VEHICLE BOOKING - VeloRent</title>
    <link rel="stylesheet" href="css/booking.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .price-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            font-size: 16px;
            color: #333;
        }

        .price-box label {
            font-weight: 600;
            margin-right: 5px;
        }

        .price-box p {
            margin: 5px 0;
            display: flex;
            align-items: center;
        }
        
        .note {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
            font-size: 14px;
            color: #856404;
        }
        
        .btnn {
            background: linear-gradient(45deg, #FF6B6B, #EE5A24);
            border: none;
            color: white;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btnn:hover {
            background: linear-gradient(45deg, #EE5A24, #FF6B6B);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .price-display {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 16px;
            color: #495057;
            margin-bottom: 15px;
        }

     
    </style>
</head>

<body>

    <button class="cancel-btn" onclick="window.location.href='vehiclesdetails.php'">CANCEL</button>

    <div class="content-box">
        <div class="register">
            <h2>BOOKING</h2>
            <form id="register" method="POST">
                <h2>VEHICLE NAME: <?php echo htmlspecialchars($vehicle['VEHICLE_NAME']); ?></h2>
                
                <label for="baseprice">BASE PRICE PER DAY:</label>
                <div class="price-display">
                    Rs.<?php echo number_format($base_price, 2); ?>
                </div>

                <label for="place">BOOKING PLACE:</label>
                <input type="text" name="place" id="place" placeholder="Enter Booking Place" required>

                <label for="date">BOOKING DATE:</label>
                <input type="date" name="date" id="datefield" required onchange="updatePriceAndDuration()">

                <label for="rdate">RETURN DATE:</label>
                <input type="date" name="rdate" id="dfield" required onchange="updatePriceAndDuration()">

                <label for="dur">DURATION (days):</label>
                <input type="number" name="dur" id="dur" readonly>

                <label for="ph">PHONE NUMBER:</label>
                <input type="tel" name="ph" id="ph" maxlength="10" placeholder="Enter Your Phone Number" required>

                <label for="des">DESTINATION:</label>
                <input type="text" name="des" id="des" placeholder="Enter Your Destination" required>

                <label for="totalprice">TOTAL PRICE:</label>
                <div class="price-display" id="total-price">
                    Select valid dates to see total price
                </div>

                <input type="submit" class="btnn" value="PROCEED TO PAYMENT" name="book">
            </form>
        </div>
    </div>

    <script>
        // Pass PHP variables to JavaScript
        const basePrice = <?php echo json_encode($base_price); ?>;
        const vehicleType = <?php echo json_encode(isset($vehicle['VEHICLE_TYPE']) ? $vehicle['VEHICLE_TYPE'] : 'Standard'); ?>;

        function calculateDynamicPrice(bookingDate, duration) {
            // Demand Factor: Increase price by 20% on weekends
            const date = new Date(bookingDate);
            const dayOfWeek = date.getDay() + 1; // 1 (Monday) to 7 (Sunday)
            const demandFactor = (dayOfWeek >= 6) ? 1.2 : 1.0;

            // Seasonal Factor: Adjust based on month (June, July, December)
            const month = date.getMonth() + 1; // 1 to 12
            const peakMonths = [6, 7, 12];
            const seasonalFactor = peakMonths.includes(month) ? 1.3 : 1.0;

            // Duration Factor: Discount for longer bookings
            let durationFactor = 1.0;
            if (duration >= 7) {
                durationFactor = 0.9;
            } else if (duration >= 3) {
                durationFactor = 0.95;
            }

            // Calculate final price
            const dynamicPrice = basePrice * demandFactor * seasonalFactor * durationFactor;
            return Math.round(dynamicPrice * 100) / 100;
        }

        function updatePriceAndDuration() {
            const bookingDate = document.getElementById('datefield').value;
            const returnDate = document.getElementById('dfield').value;
            const totalPriceElement = document.getElementById('total-price');

            if (bookingDate && returnDate && new Date(bookingDate) < new Date(returnDate)) {
                const differenceInTime = new Date(returnDate).getTime() - new Date(bookingDate).getTime();
                const differenceInDays = Math.ceil(differenceInTime / (1000 * 3600 * 24));
                document.getElementById('dur').value = differenceInDays;

                // Calculate and display dynamic price
                const dynamicPrice = calculateDynamicPrice(bookingDate, differenceInDays);
                const totalPrice = dynamicPrice * differenceInDays;
                totalPriceElement.innerHTML = `<span class="price-value">Rs.${totalPrice.toFixed(2)} (Rs.${dynamicPrice.toFixed(2)}/day)</span>`;
            } else {
                document.getElementById('dur').value = '';
                totalPriceElement.innerHTML = 'Select valid dates to see total price';
                if (bookingDate && returnDate && new Date(bookingDate) >= new Date(returnDate)) {
                    alert("Return date must be after the booking date.");
                    document.getElementById('dfield').value = ''; // Reset invalid return date
                }
            }

            // Update return date min attribute
            if (bookingDate) {
                document.getElementById('dfield').setAttribute('min', bookingDate);
                const returnDate = document.getElementById('dfield').value;
                if (returnDate && new Date(returnDate) < new Date(bookingDate)) {
                    document.getElementById('dfield').value = '';
                    document.getElementById('dur').value = '';
                    totalPriceElement.innerHTML = 'Select valid dates to see total price';
                    alert("Return date has been reset as it was earlier than the new pickup date.");
                }
            }
        }

        // Set minimum date for both date pickers to today
        const today = new Date();
        const dd = today.getDate();
        const mm = today.getMonth() + 1;
        const yyyy = today.getFullYear();
        const formattedToday = `${yyyy}-${mm < 10 ? '0' + mm : mm}-${dd < 10 ? '0' + dd : dd}`;
        document.getElementById("datefield").setAttribute("min", formattedToday);
        document.getElementById("dfield").setAttribute("min", formattedToday);

        // Event listeners
        document.getElementById("datefield").addEventListener('change', updatePriceAndDuration);
        document.getElementById("dfield").addEventListener('change', updatePriceAndDuration);
    </script>

</body>

</html>