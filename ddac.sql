-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2022 at 07:07 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ddac`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mitem_id` int(11) NOT NULL,
  `cart_amount` int(11) NOT NULL,
  `cart_remark` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mitem`
--

CREATE TABLE `mitem` (
  `mitem_id` int(11) NOT NULL,
  `mitem_name` varchar(50) NOT NULL,
  `mitem_price` decimal(6,2) NOT NULL,
  `mitem_status` tinyint(1) NOT NULL,
  `mitem_pic` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mitem`
--

INSERT INTO `mitem` (`mitem_id`, `mitem_name`, `mitem_price`, `mitem_status`, `mitem_pic`) VALUES
(32, 'Apple Puff', '5.00', 1, 'https://dcczugkilqv0c.cloudfront.net/32apple%20puff.png'),
(33, 'Red Velvet Cake', '10.00', 1, 'https://dcczugkilqv0c.cloudfront.net/33cake.png'),
(34, 'Kaya puff', '3.00', 1, 'https://dcczugkilqv0c.cloudfront.net/34Kaya%20puff.png');

-- --------------------------------------------------------

--
-- Table structure for table `odr`
--

CREATE TABLE `odr` (
  `odr_id` int(11) NOT NULL,
  `odr_ref` varchar(30) NOT NULL,
  `odr_placedtime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `odr_status` varchar(20) NOT NULL,
  `odr_compltime` datetime NOT NULL,
  `odr_cxldtime` datetime NOT NULL,
  `odr_rate_status` tinyint(4) NOT NULL,
  `store_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `odr`
--

INSERT INTO `odr` (`odr_id`, `odr_ref`, `odr_placedtime`, `odr_status`, `odr_compltime`, `odr_cxldtime`, `odr_rate_status`, `store_id`, `payment_id`, `user_id`, `rating_id`) VALUES
(22, '20221226F64EOID22', '2022-12-26 06:02:16', 'PREP', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 32, 44, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `odr_detail`
--

CREATE TABLE `odr_detail` (
  `odr_detail_id` int(11) NOT NULL,
  `odr_id` int(11) NOT NULL,
  `mitem_id` int(11) NOT NULL,
  `odr_detail_amount` int(11) NOT NULL,
  `odr_detail_price` decimal(6,2) NOT NULL,
  `odr_detail_remark` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `odr_detail`
--

INSERT INTO `odr_detail` (`odr_detail_id`, `odr_id`, `mitem_id`, `odr_detail_amount`, `odr_detail_price`, `odr_detail_remark`) VALUES
(38, 22, 32, 3, '5.00', ''),
(39, 22, 33, 1, '10.00', '');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_type` varchar(10) NOT NULL,
  `payment_amount` decimal(7,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `user_id`, `payment_type`, `payment_amount`) VALUES
(32, 44, 'PAC', '25.00');

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `store_id` int(11) NOT NULL,
  `store_name` varchar(50) NOT NULL,
  `store_location` varchar(50) NOT NULL,
  `store_openhour` time NOT NULL,
  `store_closehour` time NOT NULL,
  `store_status` tinyint(1) NOT NULL,
  `store_pic` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`store_id`, `store_name`, `store_location`, `store_openhour`, `store_closehour`, `store_status`, `store_pic`) VALUES
(35, 'Pan Mee', 'Lot 2', '07:32:00', '18:25:00', 1, 'store_id_35.png');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_username` varchar(50) NOT NULL,
  `user_pwd` varchar(50) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_fname` varchar(50) NOT NULL,
  `user_lname` varchar(50) NOT NULL,
  `user_role` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_username`, `user_pwd`, `user_email`, `user_fname`, `user_lname`, `user_role`) VALUES
(30, 'foongG', '12345678', 'foong@mail.com', 'foongg', 'wai tuck', 'CSTAFF'),
(31, 'goh', '12345678', 'goh@mail.com', 'goh', 'teng song', 'CSTAFF'),
(37, 'hui', '12345678', 'hui@gmail.com', 'hui', 'hui', 'CUST'),
(38, 'nigga', '12345678', 'nigga@mail.com', 'black', 'nigga', 'CUST'),
(39, 'junshen', '12345678', 'huihui@gmail.com', 'jun', 'shen', 'CSTAFF'),
(42, 'HEHEH', '12345678', 'haolliao@gmail.com', 'Teng song', 'Goh', 'CUST'),
(43, 'test1', '12345678', 'haolliao@gmail.com', 'GGGG', 'GGGG', 'CUST'),
(44, 'Test2', '87654321', 'gohtengsong98@gmail.com', 'HH', 'aA', 'CUST');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id-cart_table` (`user_id`),
  ADD KEY `mitem_id-cart_table` (`mitem_id`);

--
-- Indexes for table `mitem`
--
ALTER TABLE `mitem`
  ADD PRIMARY KEY (`mitem_id`);

--
-- Indexes for table `odr`
--
ALTER TABLE `odr`
  ADD PRIMARY KEY (`odr_id`),
  ADD KEY `payment_id-odr_table` (`payment_id`),
  ADD KEY `rating_id-odr_table` (`rating_id`),
  ADD KEY `store_id-odr_table` (`store_id`),
  ADD KEY `user_id-odr_table` (`user_id`);

--
-- Indexes for table `odr_detail`
--
ALTER TABLE `odr_detail`
  ADD PRIMARY KEY (`odr_detail_id`),
  ADD KEY `mitem_id-odr_details_table` (`mitem_id`),
  ADD KEY `odr_id-odr_details_table` (`odr_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_id-payment-table` (`user_id`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`store_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `mitem`
--
ALTER TABLE `mitem`
  MODIFY `mitem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `odr`
--
ALTER TABLE `odr`
  MODIFY `odr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `odr_detail`
--
ALTER TABLE `odr_detail`
  MODIFY `odr_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `store_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `mitem_id-cart_table` FOREIGN KEY (`mitem_id`) REFERENCES `mitem` (`mitem_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_id-cart_table` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `odr_detail`
--
ALTER TABLE `odr_detail`
  ADD CONSTRAINT `mitem_id-odr_details_table` FOREIGN KEY (`mitem_id`) REFERENCES `mitem` (`mitem_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `odr_id-odr_details_table` FOREIGN KEY (`odr_id`) REFERENCES `odr` (`odr_id`) ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `user_id-payment-table` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
