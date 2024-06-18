-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2024 at 02:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CatID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Ordering` int(11) DEFAULT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT 0,
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT 0,
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CatID`, `Name`, `Description`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(1, 'Computers', 'Shop laptops, desktops, monitors, tablets, PC gaming, hard drives and storage, accessories and more', 0, 0, 0, 0),
(2, 'TVs', '', 0, 0, 0, 0),
(3, 'Toys', '', 1, 0, 0, 0),
(4, 'Fashion', '', 0, 0, 0, 0),
(5, 'Mobiles', 'Mobiles & accessories | New arrivals', 0, 0, 0, 0),
(6, 'Car Accessories', '', 0, 0, 0, 0),
(7, 'Other', '', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `C_ID` int(11) NOT NULL,
  `Comment` text NOT NULL,
  `Status` tinyint(4) NOT NULL,
  `Comment_Date` date NOT NULL,
  `Item_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`C_ID`, `Comment`, `Status`, `Comment_Date`, `Item_ID`, `User_ID`) VALUES
(1, 'Its a macbook that has everything with it\r\n', 0, '2024-06-16', 7, 1),
(2, 'nice phone will buy again\r\n', 0, '2024-06-16', 1, 6),
(3, 'جهاز ممتاز وآداء محترم', 0, '2024-06-16', 5, 3),
(4, 'لابتوب جميل وسريع حاجه محترمه\r\n', 0, '2024-06-16', 3, 1),
(5, 'Very good in everything but the sound is not high, you have to raise volume .. except that, it is very good in everything else, quality of picture is really good!\r\n', 0, '2024-06-16', 6, 2),
(6, 'It one of the great smart phones out there. , but I don\'t like the fact that one need to remove one sim in order to use SD card.123456\r\n', 0, '2024-06-16', 5, 4);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `Item_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Rating` smallint(4) NOT NULL,
  `Cat_ID` int(11) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT 0,
  `Member_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Image`, `Status`, `Rating`, `Cat_ID`, `Approve`, `Member_ID`) VALUES
(1, 'iPhone 13', '6.1-inch Super Retina XDR display Cinematic mode adds shallow depth of field and shifts focus automatically in your videos Advanced dual-camera system with 12MP Wide and Ultra Wide cameras; Photographic Styles, Smart HDR 4, Night mode, 4K Dolby Vision HDR recording 12MP TrueDepth front camera with Night mode, 4K Dolby Vision HDR recording A15 Bionic chip for lightning-fast performance', '28500', '2024-06-16', 'USA', '', '3', 0, 5, 0, 1),
(2, 'BACKPACK', 'adidas POWER VII IVORY/SEMSPA/BLACK BACKPACK for Unisex size NS', '2000', '2024-06-16', 'UK', '', '3', 0, 4, 0, 6),
(3, 'Dell G15', 'Dell G15 5511 Gaming Laptop - 11th Intel Core i5-11260H 6-Cores, 8GB RAM, 512GB SSD, NVIDIA Geforce RTX3050 4GB GDDR6 Graphics, 15.6\" FHD 120Hz', '32000', '2024-06-16', 'USA', '', '2', 0, 1, 0, 3),
(4, 'Bike', 'Kids Bikes with Elephant Design', '1250', '2024-06-16', 'EG', '', '1', 0, 3, 1, 5),
(5, 'S23 Ultra', 'Samsung Galaxy S23 Ultra, 12GB, Phantom Black, Mobile Phone, Dual SIM, Android Smartphone, 1 Year Manufacturer Warranty', '54000', '2024-06-16', 'EG', '', '2', 0, 5, 0, 4),
(6, 'Smart TV', 'TORNADO Shield Smart LED TV 43 Inch HD With Built-In Receiver, 2 HDMI and 2 USB Inputs', '18000', '2024-06-16', 'EG', '', '1', 0, 2, 1, 2),
(7, 'MacBook Pro', 'Apple 2023 MacBook Pro laptop with Apple M2 Pro chip with 12 core CPU and 19 core GPU: 16.2-inch Liquid Retina XDR display, 16GB, 1TB SSD storage. Works with iPhone/iPad; Space Grey; English', '128000', '2024-06-16', 'USA', '', '1', 0, 1, 0, 2),
(8, 'Vap', 'Drag1000', '2500', '2024-06-16', 'EG', '', '1', 0, 4, 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'To Identify User',
  `Username` varchar(255) NOT NULL COMMENT 'Username To LOgin',
  `Password` varchar(255) NOT NULL COMMENT 'Password To LOgin',
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) CHARACTER SET utf8 COLLATE utf8_danish_ci NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT 0 COMMENT 'To Identify User Group',
  `TrustStatus` int(11) NOT NULL DEFAULT 0 COMMENT 'Seller Rank',
  `RegStatus` int(11) NOT NULL DEFAULT 0 COMMENT 'User Approval',
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `FullName`, `GroupID`, `TrustStatus`, `RegStatus`, `Date`) VALUES
(1, 'mohamed', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'mohamedbasiouny.swe@gmail.com', 'Mohamed Basiouny', 1, 0, 1, '2024-06-16'),
(2, 'hayat', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'hayat@gmail.com', 'hayat Sabry', 0, 0, 0, '2024-06-16'),
(3, 'ahmed', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'ahmed@gmail.com', 'Ahmed Basiouny', 1, 0, 0, '2024-06-16'),
(4, 'sama', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'sama@gmail.com', 'Sama Basiouny', 0, 0, 1, '2024-06-16'),
(5, 'karma', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'karma@gmail.com', 'Karma Ahmed', 0, 0, 0, '2024-06-16'),
(6, 'doaa', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'Doaa@gmail.com', 'Doaa Gobran', 0, 0, 1, '2024-06-16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CatID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`C_ID`),
  ADD KEY `items_comment` (`Item_ID`),
  ADD KEY `comment_user` (`User_ID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `member_1` (`Member_ID`),
  ADD KEY `cat_1` (`Cat_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CatID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `C_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To Identify User', AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comment_user` FOREIGN KEY (`User_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_comment` FOREIGN KEY (`Item_ID`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`CatID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_1` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
