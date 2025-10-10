<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VEHICLE Details</title>
    <link rel="stylesheet" href="css/vehiclesdetails.css">
    <style>
        .search-filter {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin: 20px 0;
            position: relative;
        }
        .search-filter input {
            padding: 10px 15px;
            width: 250px;
            border: 1px solid #d1d1d1;
            border-radius: 6px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }
        .search-filter input:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.2);
        }
        .filter-container {
            position: relative;
        }
        .filter-button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .filter-button:hover {
            background-color: #45a049;
            transform: translateY(-1px);
        }
        .filter-button ion-icon {
            font-size: 20px;
        }
        .filter-dropdown {
            display: none;
            position: absolute;
            top: 110%;
            right: 0;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 10;
            min-width: 180px;
        }
        .filter-dropdown.show {
            display: block;
        }
        .filter-dropdown a {
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.2s ease;
        }
        .filter-dropdown a:hover {
            background-color: #f5f5f5;
            color: #4CAF50;
        }
        .vehicle-item {
            display: none;
        }
        .vehicle-item.visible {
            display: block;
        }
        .recommended-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            z-index: 5;
        }
        .imgBx {
            position: relative;
        }
        .recommended-section {
            margin-bottom: 40px;
        }
        .recommended-section h2 {
            text-align: center;
           
            margin-bottom: 20px;
         
        }
        .other-vehicles-section h2 {
            text-align: center;
           
            margin-bottom: 20px;
          
        }
    </style>
</head>

<body class="body">

<?php 
    require_once('connection.php');
    session_start();

    $value = $_SESSION['email'];
    $_SESSION['email'] = $value;
    
    $sql = "select * from users where EMAIL='$value'";
    $name = mysqli_query($con, $sql);
    $rows = mysqli_fetch_assoc($name);
    
    // Handle sorting
    $sort = '';
    if (isset($_GET['sort'])) {
        if ($_GET['sort'] == 'price_asc') {
            $sort = ' ORDER BY PRICE ASC';
        } elseif ($_GET['sort'] == 'price_desc') {
            $sort = ' ORDER BY PRICE DESC';
        }
    }
    
    $sql2 = "select * from vehicles where AVAILABLE='Y'";
    $sql2 .= $sort;
    
    $vehicles = mysqli_query($con, $sql2);
    
    // Get user's booking history for recommendations (with error handling)
    $bookingHistory = [];
    $userId = isset($rows['USER_ID']) ? $rows['USER_ID'] : null;
    
    if ($userId) {
        // Check if bookings table exists
        $tableCheck = mysqli_query($con, "SHOW TABLES LIKE 'bookings'");
        if(mysqli_num_rows($tableCheck) > 0) {
            $historyQuery = "SELECT v.VEHICLE_TYPE, v.FUEL_TYPE FROM bookings b 
                             JOIN vehicles v ON b.VEHICLE_ID = v.VEHICLE_ID 
                             WHERE b.USER_ID = '$userId'";
            $historyResult = mysqli_query($con, $historyQuery);
            if($historyResult) {
                while($history = mysqli_fetch_assoc($historyResult)) {
                    $bookingHistory[] = $history;
                }
            }
        }
    }

    // Store vehicle data for recommendation algorithm
    $vehicleData = [];
    while($result = mysqli_fetch_array($vehicles)) {
        $vehicleData[] = $result;
    }
    
// Recommendation Algorithm
$recommendedVehicles = [];
$categories = ['Car', 'Bike', 'Scooter'];

// Function to calculate similarity between two users based on booking history
function calculateUserSimilarity($userHistory, $otherUserHistory) {
    $similarity = 0;
    foreach ($userHistory as $userBooking) {
        foreach ($otherUserHistory as $otherBooking) {
            if (strtolower($userBooking['VEHICLE_TYPE']) === strtolower($otherBooking['VEHICLE_TYPE'])) {
                $similarity += 10; // Weight for vehicle type match
            }
            if (strtolower($userBooking['FUEL_TYPE']) === strtolower($otherBooking['FUEL_TYPE'])) {
                $similarity += 5; // Weight for fuel type match
            }
        }
    }
    return $similarity;
}

// Get booking history for all users (for collaborative filtering and popularity)
$allUsersHistory = [];
$vehiclePopularity = []; // Track booking frequency per vehicle
if ($userId && mysqli_num_rows(mysqli_query($con, "SHOW TABLES LIKE 'bookings'")) > 0) {
    $allUsersQuery = "SELECT b.USER_ID, b.VEHICLE_ID, v.VEHICLE_TYPE, v.FUEL_TYPE, v.CAPACITY, v.PRICE, b.BOOKING_DATE 
                     FROM bookings b 
                     JOIN vehicles v ON b.VEHICLE_ID = v.VEHICLE_ID";
    $allUsersResult = mysqli_query($con, $allUsersQuery);
    if ($allUsersResult) {
        while ($row = mysqli_fetch_assoc($allUsersResult)) {
            $allUsersHistory[$row['USER_ID']][] = [
                'VEHICLE_ID' => $row['VEHICLE_ID'],
                'VEHICLE_TYPE' => $row['VEHICLE_TYPE'],
                'FUEL_TYPE' => $row['FUEL_TYPE'],
                'CAPACITY' => $row['CAPACITY'],
                'PRICE' => $row['PRICE'],
                'BOOKING_DATE' => $row['BOOKING_DATE']
            ];
            // Increment popularity score
            $vehiclePopularity[$row['VEHICLE_ID']] = ($vehiclePopularity[$row['VEHICLE_ID']] ?? 0) + 1;
        }
    }
}

// Score vehicles
$scoredVehicles = [];
foreach ($vehicleData as $vehicle) {
    $score = 0;

    if (!empty($bookingHistory)) {
        // Content-based scoring (based on user's booking history)
        foreach ($bookingHistory as $history) {
            if (strtolower($history['VEHICLE_TYPE']) === strtolower($vehicle['VEHICLE_TYPE'])) {
                $score += 15; // Higher weight for vehicle type match
            }
            if (strtolower($history['FUEL_TYPE']) === strtolower($vehicle['FUEL_TYPE'])) {
                $score += 8; // Weight for fuel type match
            }
            if (isset($vehicle['CAPACITY']) && isset($history['CAPACITY']) && 
                abs($vehicle['CAPACITY'] - $history['CAPACITY']) <= 2) {
                $score += 5; // Weight for similar capacity
            }
        }

        // Collaborative filtering: score based on similar users' bookings
        $similarUsers = [];
        foreach ($allUsersHistory as $otherUserId => $otherHistory) {
            if ($otherUserId != $userId) {
                $similarity = calculateUserSimilarity($bookingHistory, $otherHistory);
                if ($similarity > 0) {
                    $similarUsers[$otherUserId] = $similarity;
                }
            }
        }
        arsort($similarUsers); // Sort users by similarity descending
        $topSimilarUsers = array_slice($similarUsers, 0, 3, true); // Top 3 similar users

        foreach ($topSimilarUsers as $similarUserId => $similarity) {
            foreach ($allUsersHistory[$similarUserId] as $similarBooking) {
                if ($similarBooking['VEHICLE_ID'] == $vehicle['VEHICLE_ID']) {
                    $score += $similarity * 0.7; // Higher weight for exact vehicle match
                } elseif (strtolower($similarBooking['VEHICLE_TYPE']) === strtolower($vehicle['VEHICLE_TYPE'])) {
                    $score += $similarity * 0.5; // Weight for vehicle type match
                }
                if (strtolower($similarBooking['FUEL_TYPE']) === strtolower($vehicle['FUEL_TYPE'])) {
                    $score += $similarity * 0.3; // Weight for fuel type match
                }
            }
        }

        // Price preference (favor vehicles closer to average price of bookings)
        $avgBookingPrice = array_sum(array_column($bookingHistory, 'PRICE') ?: [0]) / (count($bookingHistory) ?: 1);
        if ($avgBookingPrice > 0 && abs($vehicle['PRICE'] - $avgBookingPrice) < 500) {
            $score += 5; // Weight for price proximity
        }

        // Recency bias (if booking date is available)
        foreach ($bookingHistory as $history) {
            if (isset($history['BOOKING_DATE']) && $history['VEHICLE_ID'] == $vehicle['VEHICLE_ID']) {
                $bookingDate = new DateTime($history['BOOKING_DATE']);
                $now = new DateTime();
                $daysDiff = $now->diff($bookingDate)->days;
                if ($daysDiff <= 30) {
                    $score += 10 / ($daysDiff + 1); // Higher score for recent bookings
                }
            }
        }
    } else {
        // For users without booking history
        // Content-based scoring: favor premium or eco-friendly vehicles
        $avgPrice = array_sum(array_column($vehicleData, 'PRICE')) / count($vehicleData);
        if ($vehicle['PRICE'] > $avgPrice) {
            $score += 5; // Favor "premium" vehicles
        }
        if (strtolower($vehicle['FUEL_TYPE']) === 'electric') {
            $score += 7; // Favor eco-friendly vehicles
        }
        if ($vehicle['CAPACITY'] >= 4) {
            $score += 3; // Favor higher capacity
        }
    }

    // Popularity-based scoring
    $popularityScore = $vehiclePopularity[$vehicle['VEHICLE_ID']] ?? 0;
    $score += $popularityScore * (!empty($bookingHistory) ? 3 : 5); // Higher weight for new users

    $scoredVehicles[] = ['vehicle' => $vehicle, 'score' => $score];
}

// Select one vehicle per category
$categoryVehicles = [];
foreach ($categories as $category) {
    $categoryVehicles[$category] = array_filter($scoredVehicles, function($v) use ($category) {
        return strtolower($v['vehicle']['VEHICLE_TYPE']) === strtolower($category);
    });
    usort($categoryVehicles[$category], function($a, $b) {
        return $b['score'] - $a['score'];
    });
}

// Add top vehicle from each category to recommendations
foreach ($categories as $category) {
    if (!empty($categoryVehicles[$category])) {
        $recommendedVehicles[] = $categoryVehicles[$category][0];
    }
}

// Extract recommended vehicle IDs
$recommendedIds = array_column(array_column($recommendedVehicles, 'vehicle'), 'VEHICLE_ID');
?>

<div class="cd">
    <div class="navbar">
        <div class="icon">
            <a href="vehiclesdetails.php"><img style="height: 50px;" src="images\icon.png" alt=""></a>
        </div>
        <div class="menu">
            <ul>
                <li><p class="phello"><a id="pname"><?php echo $rows['FNAME']." ".$rows['LNAME']?></a></p></li>
                <li><a id="stat" href="bookingstatus.php">BOOKING STATUS</a></li>
                <li><button class="nn"><a href="index.php">LOGOUT</a></button></li>
            </ul>
        </div>
    </div>

    <div class="search-filter">
        <input type="text" id="searchInput" placeholder="Search vehicle name...">
        <div class="filter-container">
            <button class="filter-button" onclick="toggleFilterDropdown('type')">
                <ion-icon name="car-outline"></ion-icon> Vehicle Type
            </button>
            <div class="filter-dropdown" id="typeFilterDropdown">
                <a href="#" onclick="filterByType('')">All</a>
                <a href="#" onclick="filterByType('Car')">Car</a>
                <a href="#" onclick="filterByType('Bike')">Bike</a>
                <a href="#" onclick="filterByType('Scooter')">Scooter</a>
            </div>
        </div>
        <div class="filter-container">
            <button class="filter-button" onclick="toggleFilterDropdown('price')">
                <ion-icon name="options-outline"></ion-icon> Filter
            </button>
            <div class="filter-dropdown" id="priceFilterDropdown">
                <a href="?sort=price_asc">Price: Low to High</a>
                <a href="?sort=price_desc">Price: High to Low</a>
            </div>
        </div>
    </div>

    <h1 class="overview">OUR VEHICLE OVERVIEW</h1>

    <!-- Recommended Vehicles Section -->
    <?php if (!empty($recommendedVehicles)): ?>
    <div class="recommended-section">
        <h2>Recommended Vehicles</h2>
        <ul class="de">
            <?php foreach ($recommendedVehicles as $recVehicle): 
                $vehicle = $recVehicle['vehicle'];
                $res = $vehicle['VEHICLE_ID'];
            ?>
            <li class="vehicle-item" data-name="<?php echo htmlspecialchars(strtolower($vehicle['VEHICLE_NAME'])); ?>" data-type="<?php echo htmlspecialchars(strtolower($vehicle['VEHICLE_TYPE'])); ?>">
                <form method="POST">
                    <div class="box">
                        <div class="imgBx">
                            <img src="images/<?php echo $vehicle['VEHICLE_IMG']?>">
                            <div class="recommended-badge">RECOMMENDED</div>
                        </div>
                        <div class="content">
                            <h1><?php echo $vehicle['VEHICLE_NAME']?></h1>
                            <h2>Fuel Type: <a><?php echo $vehicle['FUEL_TYPE']?></a></h2>
                            <h2>Capacity: <a><?php echo $vehicle['CAPACITY']?></a></h2>
                            <h2>Rent Per Day: <a>Rs<?php echo $vehicle['PRICE']?>/-</a></h2>
                            <h2>Vehicle Type: <a><?php echo $vehicle['VEHICLE_TYPE']?></a></h2>
                            <button type="submit" name="booknow" class="utton" style="margin-top: 5px;">
                                <a href="booking.php?id=<?php echo $res;?>">Book</a>
                            </button>
                        </div>
                    </div>
                </form>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- Other Vehicles Section -->
    <?php
        // Display non-recommended vehicles
        $hasOtherVehicles = false;
        foreach ($vehicleData as $result) {
            if (!in_array($result['VEHICLE_ID'], $recommendedIds)) {
                $hasOtherVehicles = true;
                break;
            }
        }
    ?>
    <?php if ($hasOtherVehicles): ?>
    <div class="other-vehicles-section">
        <h2>Other Vehicles</h2>
        <ul class="de">
            <?php foreach ($vehicleData as $result): 
                $res = $result['VEHICLE_ID'];
                if (!in_array($res, $recommendedIds)):
            ?>
            <li class="vehicle-item" data-name="<?php echo htmlspecialchars(strtolower($result['VEHICLE_NAME'])); ?>" data-type="<?php echo htmlspecialchars(strtolower($result['VEHICLE_TYPE'])); ?>">
                <form method="POST">
                    <div class="box">
                        <div class="imgBx">
                            <img src="images/<?php echo $result['VEHICLE_IMG']?>">
                        </div>
                        <div class="content">
                            <h1><?php echo $result['VEHICLE_NAME']?></h1>
                            <h2>Fuel Type: <a><?php echo $result['FUEL_TYPE']?></a></h2>
                            <h2>Capacity: <a><?php echo $result['CAPACITY']?></a></h2>
                            <h2>Rent Per Day: <a>Rs<?php echo $result['PRICE']?>/-</a></h2>
                            <h2>Vehicle Type: <a><?php echo $result['VEHICLE_TYPE']?></a></h2>
                            <button type="submit" name="booknow" class="utton" style="margin-top: 5px;">
                                <a href="booking.php?id=<?php echo $res;?>">Book</a>
                            </button>
                        </div>
                    </div>
                </form>
            </li>
            <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>

    <footer>
        <p>&copy; 2024 VeloRent. All Rights Reserved.</p>
        <div class="socials">
            <a href="https://www.facebook.com/thomasbhattrai" target="_blank"><ion-icon name="logo-facebook"></ion-icon></a>
            <a href="https://x.com/thomashbhattarai" target="_blank"><ion-icon name="logo-twitter"></ion-icon></a>
            <a href="https://www.instagram.com/swostimakaju/" target="_blank"><ion-icon name="logo-instagram"></ion-icon></a>
        </div>
    </footer>

<script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
<script>
    let selectedType = '';

    // Initialize page with all vehicles visible
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const vehicleItems = document.querySelectorAll('.vehicle-item');

        // Show all vehicles initially
        vehicleItems.forEach(item => item.classList.add('visible'));

        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            filterVehicles(searchTerm, selectedType);
        });
    });

    // Toggle filter dropdown
    function toggleFilterDropdown(type) {
        const dropdowns = {
            'type': document.getElementById('typeFilterDropdown'),
            'price': document.getElementById('priceFilterDropdown')
        };
        const dropdown = dropdowns[type];
        const otherDropdown = type === 'type' ? dropdowns['price'] : dropdowns['type'];
        
        // Toggle the selected dropdown, close the other
        dropdown.classList.toggle('show');
        otherDropdown.classList.remove('show');
    }

    // Filter by vehicle type
    function filterByType(type) {
        selectedType = type.toLowerCase();
        const searchInput = document.getElementById('searchInput');
        filterVehicles(searchInput.value.toLowerCase().trim(), selectedType);
        
        // Close the dropdown
        document.getElementById('typeFilterDropdown').classList.remove('show');
    }

    // Combined filtering function
    function filterVehicles(searchTerm, vehicleType) {
        const vehicleItems = document.querySelectorAll('.vehicle-item');

        vehicleItems.forEach(item => {
            const vehicleName = item.getAttribute('data-name');
            const itemType = item.getAttribute('data-type');
            
            const matchesSearch = searchTerm === '' || vehicleName.includes(searchTerm);
            const matchesType = vehicleType === '' || itemType === vehicleType;

            if (matchesSearch && matchesType) {
                item.classList.add('visible');
            } else {
                item.classList.remove('visible');
            }
        });
    }

    // Close dropdown when clicking outside
    window.onclick = function(event) {
        if (!event.target.closest('.filter-button') && !event.target.closest('.filter-dropdown')) {
            document.getElementById('typeFilterDropdown').classList.remove('show');
            document.getElementById('priceFilterDropdown').classList.remove('show');
        }
    }
</script>
</body>
</html>