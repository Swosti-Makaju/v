-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2025 at 02:32 PM
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
('ADMIN', 'ADMIN');

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
(82, 2, 'thomasbhattarai@gmail.com', 'Bhaktapur', '2025-09-17', 15, 9860741579, 'Bhaktapur', '2025-10-02', 94500, 'Canceled', 0.00),
(83, 2, 'thomasbhattarai@gmail.com', 'Bhaktapur', '2025-09-30', 11, 9860741579, 'Bhaktapur', '2025-10-11', 69300, 'UNDER PROCESSING', 0.00),
(84, 2, 'thomasbhattarai@gmail.com', 'Bhaktapur', '2025-09-24', 3, 9860741579, 'Bhaktapur', '2025-09-27', 19950, 'UNDER PROCESSING', 0.00),
(85, 22, 'thomasbhattarai@gmail.com', 'Bhaktapur', '2025-09-17', 10, 9860741579, 'Bhaktapur', '2025-09-27', 1800, 'UNDER PROCESSING', 0.00),
(86, 25, 'thomasbhattarai@gmail.com', 'Bhaktapur', '2025-09-16', 3, 9860741579, 'Bhaktapur', '2025-09-19', 4845, 'UNDER PROCESSING', 0.00),
(87, 25, 'thomasbhattarai@gmail.com', 'Bhaktapur', '2025-09-16', 3, 9860741579, 'Bhaktapur', '2025-09-19', 4845, 'UNDER PROCESSING', 0.00),
(88, 26, 'thomasbhattarai@gmail.com', 'Bhaktapur', '2025-09-18', 10, 9860741579, 'Bhaktapur', '2025-09-28', 7020, 'UNDER PROCESSING', 0.00);

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
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `PAY_ID` int(11) NOT NULL,
  `BOOK_ID` int(11) NOT NULL,
  `CARD_NO` varchar(255) NOT NULL,
  `EXP_DATE` varchar(255) NOT NULL,
  `CVV` int(11) NOT NULL,
  `PRICE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`PAY_ID`, `BOOK_ID`, `CARD_NO`, `EXP_DATE`, `CVV`, `PRICE`) VALUES
(35, 82, '1111-11111-11111', '11/11', 111, 94500);

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
('hello', 'there', 'ram@gmail.com', '7777', 9841502866, 'f4bd9049fce4157a55551da9a966015c', 'male'),
('Swosti', 'Makaju', 'swosti@gmail.com', '45', 986074188, '87ea4bf94165a8a11fe93328b27127db', 'female'),
('Thomas', 'Bhattarai', 'thomasbhattarai@gmail.com', '54545454', 9860741579, '2fef4d4e66fb6538790b4639aa6b6a0e', 'male');

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
(2, 'LAMBORGINI', 'Car', 'DEISEL', 6, 7000, 'lamborghini.webp', 'Y'),
(3, 'PORSCHE', 'Car', 'GAS', 4, 3000, 'porsche.jpg', 'N'),
(21, 'Harley Davidson', 'Motorbike', 'Petrol', 2, 500, 'harley_davidson.jpg', 'Y'),
(22, 'Yamaha R15', 'Motorbike', 'Petrol', 2, 200, 'yamaha_r15.jpg', 'Y'),
(23, 'Harley', 'Bike', 'Petrol', 2, 1700, 'IMG-68c915a9403936.26450202.jpg', 'Y'),
(24, 'Yamaha', 'Scooter', 'Petrol', 1, 990, 'IMG-68c915d52337d5.59492163.jpg', 'Y'),
(25, 'Yamaha rayzr', 'Scooter', 'Petrol', 1, 1700, 'IMG-68c918c00b9c04.60345530.jpg', 'Y'),
(26, 'Yamaha', 'Scooter', 'Petrol', 2, 780, 'IMG-68c919d64bcd89.68745596.jpeg', 'Y');

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
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`PAY_ID`),
  ADD UNIQUE KEY `BOOK_ID` (`BOOK_ID`);

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
  MODIFY `BOOK_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `FED_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `PAY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `VEHICLE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`VEHICLE_ID`) REFERENCES `vehicles` (`VEHICLE_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`EMAIL`) REFERENCES `users` (`EMAIL`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `TEST` FOREIGN KEY (`EMAIL`) REFERENCES `users` (`EMAIL`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`BOOK_ID`) REFERENCES `booking` (`BOOK_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
