-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2017 at 12:03 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `footerdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID_category` int(11) NOT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID_category`, `category`) VALUES
(1, 'ayam'),
(2, 'special');

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE `foods` (
  `ID_food` int(11) NOT NULL,
  `food_name` varchar(50) NOT NULL,
  `descriptions` text NOT NULL,
  `price` int(11) NOT NULL,
  `ID_restaurant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `foods`
--

INSERT INTO `foods` (`ID_food`, `food_name`, `descriptions`, `price`, `ID_restaurant`) VALUES
(1, 'makanan ayam', 'sacnjsncajscna', 0, 1),
(2, 'lele', 'casncscsacm', 0, 2),
(3, 'ayam geprek', 'jcnasn', 132312, 1),
(4, 'ayam kampus', 'scjsajccsa', 21323813, 2),
(5, 'makanan hehe', 'sdsadacs', 1231231, 4),
(6, 'hehe makanandsad', 'sacacasc', 123123, 4);

-- --------------------------------------------------------

--
-- Table structure for table `link_foods_categories`
--

CREATE TABLE `link_foods_categories` (
  `ID_food` int(11) NOT NULL,
  `ID_category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `link_foods_categories`
--

INSERT INTO `link_foods_categories` (`ID_food`, `ID_category`) VALUES
(1, 1),
(1, 2),
(2, 2),
(3, 1),
(4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `link_users_restaurants`
--

CREATE TABLE `link_users_restaurants` (
  `ID_user` int(11) NOT NULL,
  `ID_restaurant` int(11) NOT NULL,
  `love` tinyint(1) NOT NULL DEFAULT '0',
  `favorite` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `link_users_restaurants`
--

INSERT INTO `link_users_restaurants` (`ID_user`, `ID_restaurant`, `love`, `favorite`) VALUES
(25, 1, 1, 0),
(26, 1, 1, 0),
(35, 1, 1, 1),
(35, 2, 0, 1),
(35, 4, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `ID_restaurant` int(11) NOT NULL,
  `restaurant_name` varchar(50) NOT NULL,
  `location` varchar(50) NOT NULL,
  `location_latitude` int(11) NOT NULL,
  `location_longitude` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `open` time NOT NULL,
  `close` time NOT NULL,
  `photo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`ID_restaurant`, `restaurant_name`, `location`, `location_latitude`, `location_longitude`, `phone`, `open`, `close`, `photo`) VALUES
(1, 'SPG', 'Jatinangor', 1, 1, '092309', '00:00:00', '00:00:00', ''),
(2, 'Ayam Geprek', 'Jatinangor', 1, 1, '13123213', '03:26:22', '10:15:33', ''),
(4, 'restaurant', 'sdndqdnqd', 1, 1, '1', '00:00:00', '00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `ID_token` int(11) NOT NULL,
  `token` text NOT NULL,
  `ID_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID_user` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `fullname` varchar(30) NOT NULL,
  `nickname` varchar(10) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `token` text NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID_user`, `username`, `fullname`, `nickname`, `gender`, `email`, `password`, `photo`, `token`, `status`) VALUES
(25, '140810150062', 'Agung Teja', 'agung', 'pria', 'agungteja64@yahoo.co.id', '$2y$10$0h5qI9W4.IaPl6T1eVPazOKM/shu9Uz2Zo6Db2AFRxvm4js82hEoK', '', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6IjE0MDgxMDE1MDA2MiIsInBhc3N3b3JkIjoiJDJ5JDEwJDBoNXFJOVc0LklhUGw2VDFlVlBhek9LTVwvc2h1OVV6MlpvNkRiMkFGUnh2bTRqczgyaEVvSyIsImlhdCI6MTQ5MDAwMDkzMSwiZXhwIjoxNDkwMDE4OTMxfQ.7gtoeijf2he7opaNZIC4rMRzpTLiGexSWrN4hVWjTss', 0),
(26, '140810150064', 'futun', 'agung', 'pria', 'agungteja64@yahoo.co.id', '$2y$10$d91HSwBv7/b1aPTgdOOo3u52.57qb6qu172rOMCkOQXBLQCtO1j62', '', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6IjE0MDgxMDE1MDA2NCIsInBhc3N3b3JkIjoiJDJ5JDEwJGQ5MUhTd0J2N1wvYjFhUFRnZE9PbzN1NTIuNTdxYjZxdTE3MnJPTUNrT1FYQkxRQ3RPMWo2MiIsImlhdCI6MTQ5MDAwMTM3OCwiZXhwIjoxNDkwMDE5Mzc4fQ.x4TvwxLFthdE2sQbedtpQrJ5sgMVJ-AbM3G0zEeFPdE', 0),
(35, '140810150014', 'pony', 'agung', 'pria', 'agungteja64@yahoo.co.id', '$2y$10$LD7ZPOUmK/x0KPxinw00p.NDGcL5JENnReTKpXT4u5WMIZpnIsWdq', '', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6IjE0MDgxMDE1MDAxNCIsInBhc3N3b3JkIjoiJDJ5JDEwJExEN1pQT1VtS1wveDBLUHhpbncwMHAuTkRHY0w1SkVOblJlVEtwWFQ0dTVXTUlacG5Jc1dkcSIsImlhdCI6MTQ5MDAwMjk4NSwiZXhwIjoxNDkwMDIwOTg1fQ.EzNP2U3RO01czRSUNZDtQyDxZ41vmv2XMFur4aUPWu8', 1),
(36, 'atepagung', 'Agung Teja Pratama', 'Atep', 'Pria', 'agungteja64@yahoo.co.id', '$2y$10$6/IXK85cS9VELk3GnYx68eTVGAzQYzhaNEXdTgCdtzCfnUp2TZgrW', '', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6ImF0ZXBhZ3VuZyIsInBhc3N3b3JkIjoiJDJ5JDEwJDZcL0lYSzg1Y1M5VkVMazNHbll4NjhlVFZHQXpRWXpoYU5FWGRUZ0NkdHpDZm5VcDJUWmdyVyIsImlhdCI6MTQ5MDM1Mjk4OCwiZXhwIjoxNDkwMzcwOTg4fQ.auQrNitQSjDM2upZHzHzWyPHWtRD1j7N0HLxgkV2GJQ', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID_category`);

--
-- Indexes for table `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`ID_food`),
  ADD KEY `ID_restaurant` (`ID_restaurant`);

--
-- Indexes for table `link_foods_categories`
--
ALTER TABLE `link_foods_categories`
  ADD PRIMARY KEY (`ID_food`,`ID_category`),
  ADD KEY `ID_category` (`ID_category`);

--
-- Indexes for table `link_users_restaurants`
--
ALTER TABLE `link_users_restaurants`
  ADD PRIMARY KEY (`ID_user`,`ID_restaurant`),
  ADD KEY `ID_restaurant` (`ID_restaurant`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`ID_restaurant`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`ID_token`),
  ADD KEY `ID_user` (`ID_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `ID_food` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `ID_restaurant` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `ID_token` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `foods`
--
ALTER TABLE `foods`
  ADD CONSTRAINT `foods_ibfk_1` FOREIGN KEY (`ID_restaurant`) REFERENCES `restaurants` (`ID_restaurant`);

--
-- Constraints for table `link_foods_categories`
--
ALTER TABLE `link_foods_categories`
  ADD CONSTRAINT `link_foods_categories_ibfk_1` FOREIGN KEY (`ID_food`) REFERENCES `foods` (`ID_food`),
  ADD CONSTRAINT `link_foods_categories_ibfk_2` FOREIGN KEY (`ID_category`) REFERENCES `categories` (`ID_category`);

--
-- Constraints for table `link_users_restaurants`
--
ALTER TABLE `link_users_restaurants`
  ADD CONSTRAINT `link_users_restaurants_ibfk_1` FOREIGN KEY (`ID_user`) REFERENCES `users` (`ID_user`),
  ADD CONSTRAINT `link_users_restaurants_ibfk_2` FOREIGN KEY (`ID_restaurant`) REFERENCES `restaurants` (`ID_restaurant`);

--
-- Constraints for table `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`ID_user`) REFERENCES `users` (`ID_user`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
