-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2022 at 07:56 AM
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
-- Database: `ucas`
--
CREATE DATABASE IF NOT EXISTS `ucas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ucas`;

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
(11, 'Pan Mee Sup', '7.00', 1, 'mitem_id_11.png'),
(12, 'Pan Mee Kari', '8.00', 1, 'mitem_id_12.png'),
(13, 'Pan Mee Goreng', '8.00', 1, 'mitem_id_13.png'),
(14, 'Pan Mee Mala', '9.00', 1, 'mitem_id_14.png');

-- --------------------------------------------------------

--
-- Table structure for table `odr`
--

CREATE TABLE `odr` (
  `odr_id` int(11) NOT NULL,
  `odr_ref` varchar(50) NOT NULL,
  `odr_placedtime` timestamp NOT NULL DEFAULT current_timestamp(),
  `odr_status` varchar(10) NOT NULL,
  `odr_compltime` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `odr`
--

INSERT INTO `odr` (`odr_id`, `odr_ref`, `odr_placedtime`, `odr_status`, `odr_compltime`, `user_id`) VALUES
(1, 'ref1', '2022-10-01 10:05:21', 'CMPLT', '2022-10-01 20:05:21', 38),
(2, 'ref2', '2022-10-02 08:05:21', 'CMPLT', '2022-10-02 20:05:21', 37),
(3, 'ref3', '2022-10-03 12:05:21', 'CMPLT', '2022-10-03 20:05:21', 37),
(4, 'ref4', '2022-10-04 10:05:21', 'CMPLT', '2022-10-04 20:05:21', 38),
(5, 'ref5', '2022-10-05 10:05:21', 'CMPLT', '2022-10-05 20:05:21', 37),
(6, 'ref6', '2022-10-06 10:08:18', 'CMPLT', '2022-10-06 20:08:18', 37),
(7, 'ref7', '2022-10-07 12:08:18', 'CMPLT', '2022-10-07 20:08:18', 38),
(8, 'ref8', '2022-10-08 12:08:55', 'CMPLT', '2022-10-08 20:08:55', 37),
(9, 'ref9', '2022-10-09 12:08:55', 'CMPLT', '2022-10-09 20:08:55', 38),
(10, 'ref10', '2022-10-10 12:08:55', 'CMPLT', '2022-10-10 20:08:55', 38);

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
(1, 1, 13, 2, '8.00', 'No Fungus Mushroom'),
(2, 2, 11, 2, '7.00', ''),
(5, 5, 12, 1, '8.00', ''),
(6, 5, 11, 1, '7.00', 'No Anchovies'),
(7, 6, 14, 1, '9.00', ''),
(8, 7, 13, 2, '8.00', 'More Soy Sauce'),
(9, 8, 14, 3, '9.00', ''),
(11, 10, 14, 2, '9.00', ''),
(12, 10, 11, 1, '7.00', ''),
(13, 10, 12, 2, '8.00', '');

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
(35, 'Pan Mee', 'Lot 2', '07:32:00', '18:25:00', 0, 'store_id_35.png'),
(37, 'indian', 'black', '23:19:00', '15:25:00', 1, 'store_id_37.png');

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
(30, 'foong', '12345678', 'foong@mail.com', 'foong', 'wai tuck', 'CSTAFF'),
(31, 'goh', '12345678', 'goh@mail.com', 'goh', 'teng song', 'CSTAFF'),
(37, 'hui', '12345678', 'hui@gmail.com', 'hui', 'hui', 'CUST'),
(38, 'nigga', '12345678', 'nigga@mail.com', 'black', 'nigga', 'CUST'),
(39, 'junshen', '12345678', 'huihui@gmail.com', 'jun', 'shen', 'CSTAFF');

--
-- Indexes for dumped tables
--

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
  ADD KEY `user_id-odr_table` (`user_id`);

--
-- Indexes for table `odr_detail`
--
ALTER TABLE `odr_detail`
  ADD PRIMARY KEY (`odr_detail_id`),
  ADD KEY `mitem_id-odr_details_table` (`mitem_id`),
  ADD KEY `odr_id-odr_details_table` (`odr_id`);

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
-- AUTO_INCREMENT for table `mitem`
--
ALTER TABLE `mitem`
  MODIFY `mitem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `odr`
--
ALTER TABLE `odr`
  MODIFY `odr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `odr_detail`
--
ALTER TABLE `odr_detail`
  MODIFY `odr_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `store_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `odr`
--
ALTER TABLE `odr`
  ADD CONSTRAINT `user_id-odr_table` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `odr_detail`
--
ALTER TABLE `odr_detail`
  ADD CONSTRAINT `mitem_id-odr_details_table` FOREIGN KEY (`mitem_id`) REFERENCES `mitem` (`mitem_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `odr_id-odr_details_table` FOREIGN KEY (`odr_id`) REFERENCES `odr` (`odr_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
