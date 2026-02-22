-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2026 at 02:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vehicleproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ADMIN_ID` varchar(255) NOT NULL,
  `ADMIN_PASSWORD` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ADMIN_ID`, `ADMIN_PASSWORD`) VALUES
('ADMIN', '$2y$10$aqL3vJgOkkZCzRDua6sF5.w305W7YOtUH1CkPu6YZhBOm5lOVnuD.');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `BOOK_ID` int(11) NOT NULL,
  `VEHICLE_ID` int(11) NOT NULL,
  `EMAIL` varchar(255) NOT NULL,
  `BOOK_PLACE` varchar(255) NOT NULL,
  `BOOK_DATE` date NOT NULL,
  `DURATION` int(11) NOT NULL,
  `PHONE_NUMBER` bigint(20) NOT NULL,
  `DESTINATION` varchar(255) NOT NULL,
  `RETURN_DATE` date NOT NULL,
  `PRICE` int(11) NOT NULL,
  `BOOK_STATUS` varchar(255) NOT NULL DEFAULT 'UNDER PROCESSING',
  `FINE` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`BOOK_ID`, `VEHICLE_ID`, `EMAIL`, `BOOK_PLACE`, `BOOK_DATE`, `DURATION`, `PHONE_NUMBER`, `DESTINATION`, `RETURN_DATE`, `PRICE`, `BOOK_STATUS`, `FINE`) VALUES
(108, 29, 'thomasbhattarai@gmail.com', 'bhaktapur', '2026-01-09', 3, 9860741579, 'ktm', '2026-01-12', 1568, 'RETURNED', 0.00),
(109, 29, 'thomasbhattarai@gmail.com', 'bhaktapur', '2026-01-09', 1, 9860741579, 'ktm', '2026-01-10', 550, 'RETURNED', 0.00),
(110, 30, 'thomasbhattarai@gmail.com', 'bhaktapur', '2026-01-09', 2, 9860741579, 'ktm', '2026-01-11', 1176, 'RETURNED', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `FED_ID` int(11) NOT NULL,
  `EMAIL` varchar(255) NOT NULL,
  `COMMENT` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`FED_ID`, `EMAIL`, `COMMENT`) VALUES
(11, 'ram@gmail.com', 'fsdafsadfasf');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `REVIEW_ID` int(11) NOT NULL,
  `VEHICLE_ID` int(11) NOT NULL,
  `EMAIL` varchar(255) NOT NULL,
  `COMMENT` text NOT NULL,
  `RATING` tinyint(4) DEFAULT NULL,
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`REVIEW_ID`, `VEHICLE_ID`, `EMAIL`, `COMMENT`, `RATING`, `CREATED_AT`) VALUES
(1, 29, 'thomasbhattarai@gmail.com', 'damnnnnn', 5, '2026-01-09 12:38:42'),
(2, 30, 'thomasbhattarai@gmail.com', '', 1, '2026-01-09 12:43:09');

-- --------------------------------------------------------

--
-- Table structure for table `tour_packages`
--

CREATE TABLE `tour_packages` (
  `id` int(11) NOT NULL,
  `tour_name` varchar(100) NOT NULL,
  `destination` varchar(100) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `FNAME` varchar(255) NOT NULL,
  `LNAME` varchar(255) NOT NULL,
  `EMAIL` varchar(255) NOT NULL,
  `LIC_NUM` varchar(255) NOT NULL,
  `PHONE_NUMBER` bigint(11) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `GENDER` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`FNAME`, `LNAME`, `EMAIL`, `LIC_NUM`, `PHONE_NUMBER`, `PASSWORD`, `GENDER`) VALUES
('Thomas', 'Bhattarai', 'alexx@gmail.com', '7494964', 9498645, '$2y$10$cGRbUwA58GkqzMeT/42Uy.PlNN.WOkHktnh7w9..I3sXZViPEUhPW', 'male'),
('hello', 'there', 'ram@gmail.com', '7777', 9841502866, 'f4bd9049fce4157a55551da9a966015c', 'male'),
('Suman', 'Bhattarai', 'suman@gmail.com', '123', 9813689305, '$2y$10$y1u83wNDEb62bA3xovJG1eFFSEG/KGaFykF6rj98Oam1aJ4omH4Nq', 'male'),
('Swosti', 'Makaju', 'swosti@gmail.com', '45', 986074188, '87ea4bf94165a8a11fe93328b27127db', 'female'),
('Thomas', 'Bhattarai', 'thomasbhattarai@gmail.com', '54545454', 9860741579, '$2y$10$26eUh5xFwpvZ9wLEThTw5.d7AspDhBKRzWtSt7fHwMVvHknjs1viu', 'male');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `VEHICLE_ID` int(11) NOT NULL,
  `VEHICLE_NAME` varchar(255) NOT NULL,
  `VEHICLE_TYPE` varchar(255) NOT NULL,
  `FUEL_TYPE` varchar(255) NOT NULL,
  `CAPACITY` int(11) NOT NULL,
  `PRICE` int(11) NOT NULL,
  `VEHICLE_IMG` varchar(255) NOT NULL,
  `AVAILABLE` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`VEHICLE_ID`, `VEHICLE_NAME`, `VEHICLE_TYPE`, `FUEL_TYPE`, `CAPACITY`, `PRICE`, `VEHICLE_IMG`, `AVAILABLE`) VALUES
(28, 'Aprilia', 'Scooter', 'Petrol', 2, 500, 'IMG-68d27140de4b71.38753356.png', 'Y'),
(29, 'Komaki', 'Scooter', 'Petrol', 2, 550, 'IMG-68d27179bfac42.60699057.png', 'Y'),
(30, 'Komaki-Ly', 'Scooter', 'Petrol', 2, 600, 'IMG-68d271a5a5fae8.22353209.png', 'Y'),
(31, 'TVS-ev', 'Scooter', 'EV', 2, 450, 'IMG-68d271cdc52c43.55289021.png', 'Y'),
(32, 'Vespa', 'Scooter', 'Petrol', 2, 600, 'IMG-68d271f7e09343.99123146.png', 'Y'),
(33, 'TVS-ntorq', 'Scooter', 'Petrol', 2, 700, 'IMG-68d273f7a10c04.37367873.png', 'Y'),
(34, 'TVS-jupitor', 'Scooter', 'Petrol', 2, 550, 'IMG-68d27425a2b369.03295970.png', 'Y'),
(35, 'Yamaha-Ray-zr', 'Scooter', 'Petrol', 2, 650, 'IMG-68d27445237254.13049238.png', 'Y'),
(36, 'Aprilia-xr', 'Scooter', 'Petrol', 2, 500, 'IMG-68d2745f8c10f8.30582514.png', 'Y'),
(37, 'Yamaha-aerox', 'Scooter', 'Petrol', 2, 550, 'IMG-68d2748c219654.03920387.png', 'Y'),
(38, 'Royal Enfield', 'Bike', 'Petrol', 2, 1000, 'IMG-68d277e81330c5.79759855.png', 'Y'),
(39, 'MT15', 'Bike', 'Petrol', 2, 1100, 'IMG-68d27863540695.98658714.png', 'Y'),
(40, 'R15M', 'Bike', 'Petrol', 2, 900, 'IMG-68d2787fb3c797.75346386.png', 'Y'),
(41, 'Harley Davidson', 'Bike', 'Petrol', 2, 1100, 'IMG-68d278a48a8126.63581138.png', 'Y'),
(42, 'TVS Apache', 'Bike', 'Petrol', 2, 800, 'IMG-68d278c4818cd7.97945436.png', 'Y'),
(43, 'FZ Yamaha', 'Bike', 'Petrol', 2, 750, 'IMG-68d27a31361b06.26450099.png', 'Y'),
(44, 'Honda', 'Bike', 'Petrol', 2, 1200, 'IMG-68d27a4a69d145.24807193.png', 'Y'),
(45, 'KTM', 'Bike', 'Petrol', 2, 860, 'IMG-68d27aa2624f40.14606218.png', 'Y'),
(46, 'NS Pulser', 'Bike', 'Petrol', 2, 850, 'IMG-68d27abdb3b287.57395677.png', 'Y'),
(47, 'Royal Enfield', 'Bike', 'Petrol', 2, 1250, 'IMG-68d27ae9338170.84666158.png', 'Y'),
(48, 'BYD Dolphin', 'Car', 'EV', 4, 1900, 'IMG-68d27cbe947cf5.78627318.png', 'Y'),
(49, 'Hyundai', 'Car', 'Diesel', 4, 2000, 'IMG-68d27d556d5aa6.46888955.png', 'Y'),
(50, 'Renault KWID', 'Car', 'Petrol', 4, 2100, 'IMG-68d27d8c601400.91573840.png', 'Y'),
(51, 'Proton emas', 'Car', 'EV', 4, 1800, 'IMG-68d27dc0d37149.67229036.png', 'Y'),
(52, 'Sujuki Swift', 'Car', 'Petrol', 4, 2500, 'IMG-68d27def0f1141.32474626.png', 'Y'),
(53, 'BYD Atto3', 'Car', 'EV', 4, 2100, 'IMG-68d27f65049b99.57289799.png', 'Y'),
(54, 'Deepal L07', 'Car', 'EV', 4, 2500, 'IMG-68d27f8a916543.55769829.png', 'Y'),
(55, 'Omoda E5', 'Car', 'EV', 4, 2400, 'IMG-68d27faeaca2e7.96686252.png', 'Y'),
(56, 'Tata punch', 'Car', 'EV', 4, 2200, 'IMG-68d27fcd69f761.28358486.png', 'Y'),
(57, 'Toyota LC', 'Car', 'Petrol/Diesel', 6, 4500, 'IMG-68d28036c892c7.23899560.png', 'Y');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ADMIN_ID`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`BOOK_ID`),
  ADD KEY `VEHICLE_ID` (`VEHICLE_ID`),
  ADD KEY `EMAIL` (`EMAIL`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`FED_ID`),
  ADD KEY `TEST` (`EMAIL`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`REVIEW_ID`),
  ADD KEY `idx_vehicle` (`VEHICLE_ID`),
  ADD KEY `idx_created` (`CREATED_AT`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`k`);

--
-- Indexes for table `tour_packages`
--
ALTER TABLE `tour_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`EMAIL`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`VEHICLE_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `BOOK_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `FED_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `REVIEW_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tour_packages`
--
ALTER TABLE `tour_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `VEHICLE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`VEHICLE_ID`) REFERENCES `vehicles` (`VEHICLE_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`EMAIL`) REFERENCES `users` (`EMAIL`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
