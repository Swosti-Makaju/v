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
?>

<div class="cd">
    <div class="navbar">
        <div class="icon">
            <img style="height: 50px;" src="images\icon.png" alt="">
        </div>
        <div class="menu">
            <ul>
                
                <li><p class="phello"><a id="pname"><?php echo $rows['FNAME']." ".$rows['LNAME']?></a></p></li>
                <li><a id="stat" href="bookinstatus.php">BOOKING STATUS</a></li>
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
    <ul class="de">
    <?php
        // Store vehicle data for recommendation algorithm
        $vehicleData = [];
        while($result = mysqli_fetch_array($vehicles)) {
            $res = $result['VEHICLE_ID'];
            $vehicleData[] = $result;
        }
        
        // Recommendation Algorithm
        $recommendedVehicles = [];
        if (!empty($bookingHistory)) {
            // Calculate recommendation scores for each vehicle
            $scoredVehicles = [];
            foreach ($vehicleData as $vehicle) {
                $score = 0;
                
                // Preference matching based on booking history
                foreach ($bookingHistory as $history) {
                    if (isset($history['VEHICLE_TYPE']) && 
                        strtolower($history['VEHICLE_TYPE']) === strtolower($vehicle['VEHICLE_TYPE'])) {
                        $score += 10;
                    }
                    if (isset($history['FUEL_TYPE']) && 
                        strtolower($history['FUEL_TYPE']) === strtolower($vehicle['FUEL_TYPE'])) {
                        $score += 5;
                    }
                }
                
                // Price preference (lower prices get higher scores for budget-conscious users)
                $avgPrice = array_sum(array_column($vehicleData, 'PRICE')) / count($vehicleData);
                if ($vehicle['PRICE'] < $avgPrice) {
                    $score += 3;
                }
                
                $scoredVehicles[] = ['vehicle' => $vehicle, 'score' => $score];
            }
            
            // Sort by score (descending) and take top 3
            usort($scoredVehicles, function($a, $b) {
                return $b['score'] - $a['score'];
            });
            
            $recommendedVehicles = array_slice($scoredVehicles, 0, 3);
        } else {
            // For new users, recommend popular vehicles (higher priced ones as proxy)
            $sortedVehicles = $vehicleData;
            usort($sortedVehicles, function($a, $b) {
                return $b['PRICE'] - $a['PRICE'];
            });
            $recommendedVehicles = array_slice($sortedVehicles, 0, 3);
            // Convert to same format as scored vehicles
            $recommendedVehicles = array_map(function($v) {
                return ['vehicle' => $v, 'score' => 0];
            }, $recommendedVehicles);
        }
        
        // Extract recommended vehicle IDs
        $recommendedIds = array_column(array_column($recommendedVehicles, 'vehicle'), 'VEHICLE_ID');
        
        // Display all vehicles
        foreach ($vehicleData as $result) {
            $res = $result['VEHICLE_ID'];
            $isRecommended = in_array($result['VEHICLE_ID'], $recommendedIds);
    ?>    
    <li class="vehicle-item" data-name="<?php echo htmlspecialchars(strtolower($result['VEHICLE_NAME'])); ?>" data-type="<?php echo htmlspecialchars(strtolower($result['VEHICLE_TYPE'])); ?>">
        <form method="POST">
            <div class="box">
                <div class="imgBx">
                    <img src="images/<?php echo $result['VEHICLE_IMG']?>">
                    <?php if ($isRecommended): ?>
                        <div class="recommended-badge">RECOMMENDED</div>
                    <?php endif; ?>
                </div>
                <div class="content">
                    <h1><?php echo $result['VEHICLE_NAME']?></h1>
                    <h2>Fuel Type : <a><?php echo $result['FUEL_TYPE']?></a> </h2>
                    <h2>Capacity : <a><?php echo $result['CAPACITY']?></a> </h2>
                    <h2>Rent Per Day : <a>Rs<?php echo $result['PRICE']?>/-</a></h2>
                    <h2>Vehicle Type : <a><?php echo $result['VEHICLE_TYPE']?></a></h2>
                    <button type="submit" name="booknow" class="utton" style="margin-top: 5px;">
                        <a href="booking.php?id=<?php echo $res;?>">Book</a>
                    </button>
                </div>
            </div>
        </form>
    </li>
    <?php
        }
    ?>
    </ul>
</div>

<footer>
    <p>&copy; 2024 VeloRent. All Rights Reserved.</p>
    <div class="socials">
        <a href="#"><ion-icon name="logo-facebook"></ion-icon></a>
        <a href="#"><ion-icon name="logo-twitter"></ion-icon></a>
        <a href="#"><ion-icon name="logo-instagram"></ion-icon></a>
    </div>
</footer>

<script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js  "></script>
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