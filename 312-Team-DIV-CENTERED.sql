-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2025 at 11:12 AM
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
-- Database: `slubazaar`
--

-- --------------------------------------------------------

--
-- Table structure for table `bid`
--

CREATE TABLE `bid` (
  `bid_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `bidder_id` int(11) NOT NULL,
  `bid_amount` decimal(10,2) NOT NULL,
  `bid_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bid`
--

INSERT INTO `bid` (`bid_id`, `item_id`, `bidder_id`, `bid_amount`, `bid_timestamp`) VALUES
(1, 1, 4, 500.00, '2025-11-29 05:28:03'),
(2, 1, 5, 550.00, '2025-11-29 17:28:03'),
(3, 2, 5, 650.00, '2025-11-30 03:28:03'),
(4, 3, 2, 950.00, '2025-11-28 05:28:03'),
(5, 3, 4, 1000.00, '2025-11-29 05:28:03'),
(6, 4, 3, 420.00, '2025-11-30 03:28:03'),
(7, 5, 5, 180.00, '2025-11-30 05:28:03'),
(8, 6, 2, 300.00, '2025-11-29 05:28:03'),
(9, 12, 4, 400.00, '2025-11-27 05:28:03'),
(10, 13, 3, 600.00, '2025-11-29 05:28:03'),
(11, 14, 5, 300.00, '2025-11-25 05:28:03'),
(12, 15, 4, 150.00, '2025-11-28 05:28:03'),
(13, 16, 5, 600.00, '2025-11-22 05:28:03'),
(14, 17, 3, 500.00, '2025-11-23 05:28:03'),
(15, 18, 5, 250.00, '2025-11-30 03:28:03'),
(16, 19, 4, 450.00, '2025-11-30 02:28:03'),
(17, 20, 2, 900.00, '2025-11-29 05:28:03'),
(18, 21, 2, 700.00, '2025-11-29 23:28:03'),
(19, 22, 4, 250.00, '2025-11-30 05:18:03'),
(20, 28, 5, 850.00, '2025-11-26 05:28:03'),
(21, 31, 2, 1800.00, '2025-11-26 05:28:03'),
(22, 32, 4, 300.00, '2025-11-28 05:28:03'),
(23, 54, 3, 200.00, '2025-12-17 06:02:18'),
(24, 56, 3, 1050.00, '2025-12-17 06:37:25'),
(25, 56, 3, 1200.00, '2025-12-17 06:37:54'),
(26, 56, 3, 1300.00, '2025-12-17 06:38:04'),
(27, 56, 4, 1500.00, '2025-12-17 06:52:30'),
(28, 56, 3, 1600.00, '2025-12-17 06:53:29'),
(29, 57, 5, 1700.00, '2025-12-17 07:22:49'),
(30, 56, 3, 1650.00, '2025-12-17 07:42:24');

-- --------------------------------------------------------

--
-- Table structure for table `conversation`
--

CREATE TABLE `conversation` (
  `conversation_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `status` enum('Active','Archived') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conversation`
--

INSERT INTO `conversation` (`conversation_id`, `item_id`, `buyer_id`, `seller_id`, `status`) VALUES
(1, 12, 4, 3, 'Archived'),
(2, 18, 5, 3, 'Active'),
(3, 13, 3, 2, 'Archived'),
(4, 14, 5, 4, 'Archived'),
(5, 15, 4, 3, 'Archived'),
(6, 19, 4, 3, 'Active'),
(7, 20, 2, 3, 'Active'),
(8, 1, 5, 2, 'Active'),
(9, 2, 5, 2, 'Active'),
(10, 3, 4, 5, 'Active'),
(11, 4, 3, 4, 'Active'),
(12, 5, 5, 2, 'Active'),
(13, 6, 2, 4, 'Active'),
(14, 57, 5, 3, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `starting_bid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `current_bid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `auction_start` datetime NOT NULL,
  `auction_end` datetime NOT NULL,
  `item_status` enum('Pending','Active','Expired','Awaiting Meetup','Sold','Disputed','Cancelled By Seller','Removed By Admin') NOT NULL DEFAULT 'Pending',
  `meetup_code` varchar(6) DEFAULT NULL,
  `category` enum('Textbooks','Stationery','Electronics','Clothing','Sports Equipment','Accessories','Furniture','Collectibles','Other') NOT NULL,
  `date_sold` timestamp NULL DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `seller_id`, `title`, `description`, `starting_bid`, `current_bid`, `created_at`, `auction_start`, `auction_end`, `item_status`, `meetup_code`, `category`, `date_sold`, `buyer_id`) VALUES
(1, 2, 'Calculus TC7 Book', 'Standard engineering math book.', 500.00, 550.00, '2025-11-30 05:28:03', '2025-11-30 13:28:03', '2025-12-05 13:28:03', 'Awaiting Meetup', '071509', 'Textbooks', '2025-12-12 13:40:55', 5),
(2, 2, 'Rotring Tech Pen', '0.5mm tip.', 600.00, 650.00, '2025-11-30 05:28:03', '2025-11-30 13:28:03', '2025-12-02 13:28:03', 'Awaiting Meetup', '642604', 'Stationery', '2025-12-12 13:40:55', 5),
(3, 5, 'Casio fx-991EX', 'Classwiz calculator.', 900.00, 1000.00, '2025-11-30 05:28:03', '2025-11-30 13:28:03', '2025-12-03 13:28:03', 'Awaiting Meetup', '323207', 'Electronics', '2025-12-12 13:40:55', 4),
(4, 4, 'Basketball', 'Molten generic.', 400.00, 420.00, '2025-11-30 05:28:03', '2025-11-30 13:28:03', '2025-12-04 13:28:03', 'Awaiting Meetup', '940840', 'Sports Equipment', '2025-12-12 13:40:55', 3),
(5, 2, 'Umbrella', 'Foldable black.', 150.00, 180.00, '2025-11-30 05:28:03', '2025-11-30 13:28:03', '2025-12-01 13:28:03', 'Awaiting Meetup', '628184', 'Accessories', '2025-12-12 13:40:55', 5),
(6, 4, 'Kpop Photocard', 'Twice.', 100.00, 300.00, '2025-11-30 05:28:03', '2025-11-30 13:28:03', '2025-12-06 13:28:03', 'Awaiting Meetup', '551690', 'Collectibles', '2025-12-12 13:40:55', 2),
(7, 5, 'Anatomy & Physiology', 'Hardbound.', 1200.00, 1200.00, '2025-11-30 05:28:03', '2025-12-02 13:28:03', '2025-12-09 13:28:03', 'Expired', NULL, 'Textbooks', NULL, NULL),
(8, 2, 'Lab Gown', 'Canvas material.', 300.00, 300.00, '2025-11-30 05:28:03', '2025-12-01 13:28:03', '2025-12-05 13:28:03', 'Expired', NULL, 'Clothing', NULL, NULL),
(9, 2, 'Volleyball Knee Pads', 'Asics.', 200.00, 200.00, '2025-11-30 05:28:03', '2025-12-03 13:28:03', '2025-12-10 13:28:03', 'Expired', NULL, 'Sports Equipment', NULL, NULL),
(10, 4, 'Drafting Table', 'Adjustable height.', 1500.00, 1500.00, '2025-11-30 05:28:03', '2025-12-05 13:28:03', '2025-12-12 13:28:03', 'Expired', NULL, 'Furniture', NULL, NULL),
(11, 3, 'Gundam Model', 'HG scale built.', 400.00, 400.00, '2025-11-30 05:28:03', '2025-12-04 13:28:03', '2025-12-11 13:28:03', 'Expired', NULL, 'Collectibles', NULL, NULL),
(12, 3, 'Accounting 101', 'For BSA students.', 400.00, 400.00, '2025-11-30 05:28:03', '2025-11-20 13:28:03', '2025-11-27 13:28:03', 'Sold', '111111', 'Textbooks', '2025-11-28 05:28:03', 4),
(13, 2, 'Logitech Mouse', 'Wireless silent.', 500.00, 600.00, '2025-11-30 05:28:03', '2025-11-22 13:28:03', '2025-11-29 13:28:03', 'Sold', '333333', 'Electronics', '2025-11-29 17:28:03', 3),
(14, 4, 'PE Uniform (S)', 'Top and bottom.', 250.00, 300.00, '2025-11-30 05:28:03', '2025-11-15 13:28:03', '2025-11-25 13:28:03', 'Sold', '555555', 'Clothing', '2025-11-26 05:28:03', 5),
(15, 3, 'Tote Bag', 'Canvas bag with print.', 100.00, 150.00, '2025-11-30 05:28:03', '2025-11-21 13:28:03', '2025-11-28 13:28:03', 'Sold', '777777', 'Accessories', '2025-11-29 05:28:03', 4),
(16, 2, 'Funko Pop', 'Iron Man.', 500.00, 600.00, '2025-11-30 05:28:03', '2025-11-18 13:28:03', '2025-11-22 13:28:03', 'Sold', '999999', 'Collectibles', '2025-11-23 05:28:03', 5),
(17, 4, 'Water Jug', '2 Liters insulated.', 400.00, 500.00, '2025-11-30 05:28:03', '2025-11-16 13:28:03', '2025-11-23 13:28:03', 'Sold', '123123', 'Other', '2025-11-24 05:28:03', 3),
(18, 3, 'T-Square 24inch', 'Aluminum.', 200.00, 250.00, '2025-11-30 05:28:03', '2025-11-25 13:28:03', '2025-11-30 12:28:03', 'Awaiting Meetup', '222222', 'Stationery', NULL, 5),
(19, 3, 'Powerbank 20k', 'Romoss.', 400.00, 450.00, '2025-11-30 05:28:03', '2025-11-26 13:28:03', '2025-11-30 11:28:03', 'Awaiting Meetup', '444444', 'Electronics', NULL, 4),
(20, 3, 'Yonex Racket', 'Original with bag.', 800.00, 900.00, '2025-11-30 05:28:03', '2025-11-24 13:28:03', '2025-11-29 13:28:03', 'Awaiting Meetup', '666666', 'Sports Equipment', NULL, 2),
(21, 3, 'Plastic Drawer', '4 layers.', 600.00, 700.00, '2025-11-30 05:28:03', '2025-11-23 13:28:03', '2025-11-30 08:28:03', 'Awaiting Meetup', '888888', 'Furniture', NULL, 2),
(22, 2, 'Extension Cord', '5 meters.', 200.00, 250.00, '2025-11-30 05:28:03', '2025-11-27 13:28:03', '2025-11-30 13:27:03', 'Awaiting Meetup', '000000', 'Other', NULL, 4),
(23, 4, 'Acrylic Paints Set', 'Used once.', 150.00, 150.00, '2025-11-30 05:28:03', '2025-11-10 13:28:03', '2025-11-20 13:28:03', 'Expired', NULL, 'Stationery', NULL, NULL),
(24, 5, 'Department Shirt', 'SEA dept shirt size M.', 150.00, 150.00, '2025-11-30 05:28:03', '2025-10-31 13:28:03', '2025-11-10 13:28:03', 'Expired', NULL, 'Clothing', NULL, NULL),
(25, 5, 'SLU ID Lace', 'Latest design.', 50.00, 50.00, '2025-11-30 05:28:03', '2025-11-05 13:28:03', '2025-11-12 13:28:03', 'Expired', NULL, 'Accessories', NULL, NULL),
(26, 5, 'Study Lamp', 'Clip on.', 250.00, 250.00, '2025-11-30 05:28:03', '2025-10-21 13:28:03', '2025-10-28 13:28:03', 'Expired', NULL, 'Furniture', NULL, NULL),
(27, 5, 'Storage Box', 'Megabox 50L.', 300.00, 300.00, '2025-11-30 05:28:03', '2025-10-11 13:28:03', '2025-10-18 13:28:03', 'Expired', NULL, 'Other', NULL, NULL),
(28, 2, 'Nike Fake Shoes', 'Class A imitation.', 800.00, 850.00, '2025-11-30 05:28:03', '2025-11-25 13:28:03', '2025-12-02 13:28:03', 'Removed By Admin', NULL, 'Clothing', NULL, NULL),
(29, 3, 'Liquor Bottle', 'Unopened brandy.', 500.00, 500.00, '2025-11-30 05:28:03', '2025-11-28 13:28:03', '2025-12-05 13:28:03', 'Removed By Admin', NULL, 'Other', NULL, NULL),
(30, 4, 'Answer Key for Exam', 'Leaks for finals.', 1000.00, 1000.00, '2025-11-30 05:28:03', '2025-11-29 13:28:03', '2025-12-01 13:28:03', 'Removed By Admin', NULL, 'Textbooks', NULL, NULL),
(31, 5, 'Broken Monitor', 'Oops dropped it.', 1500.00, 1800.00, '2025-11-30 05:28:03', '2025-11-25 13:28:03', '2025-12-02 13:28:03', 'Cancelled By Seller', NULL, 'Electronics', NULL, NULL),
(32, 2, 'Lost Wallet', 'Cant find it anymore.', 200.00, 300.00, '2025-11-30 05:28:03', '2025-11-27 13:28:03', '2025-12-04 13:28:03', 'Cancelled By Seller', NULL, 'Accessories', NULL, NULL),
(33, 3, 'Change of Mind', 'Not selling anymore.', 100.00, 100.00, '2025-11-30 05:28:03', '2025-11-29 13:28:03', '2025-12-06 13:28:03', 'Cancelled By Seller', NULL, 'Stationery', NULL, NULL),
(34, 2, 'Aula F75 Keyboard', 'Condition: 10/10', 1800.00, 1800.00, '2025-12-12 01:48:49', '2025-12-12 09:48:49', '2025-12-12 16:56:00', 'Expired', NULL, 'Electronics', NULL, NULL),
(35, 2, 'Puma Speedcat OG', 'Regular fit\r\nRounded toe\r\nHeel type: Flat\r\nLace closure\r\nPUMA branding details\r\nUpper: Leather; Lining: Textile; Midsole: Rubber; Outsole: Rubber', 6000.00, 6000.00, '2025-12-12 01:52:03', '2025-12-12 09:52:03', '2025-12-12 16:55:00', 'Expired', NULL, 'Accessories', NULL, NULL),
(36, 2, 'Test', 'aaaaa', 1000.00, 1000.00, '2025-12-12 02:05:41', '2025-12-12 10:05:41', '2025-12-12 17:07:00', 'Expired', NULL, 'Textbooks', NULL, NULL),
(37, 2, 'test2', 'bbbbbbbbbbbbbbbbb', 2000.00, 2000.00, '2025-12-12 02:10:42', '2025-12-12 10:10:42', '2025-12-12 17:12:00', 'Expired', NULL, 'Electronics', NULL, NULL),
(38, 2, 'test3', 'ccccccccccccccccc', 3000.00, 3000.00, '2025-12-12 09:16:27', '2025-12-12 17:16:27', '2025-12-12 17:18:00', 'Expired', NULL, 'Clothing', NULL, NULL),
(39, 2, 'test4', 'dddddddddddddddd', 4000.00, 4000.00, '2025-12-12 09:20:18', '2025-12-12 17:20:18', '2025-12-12 17:22:00', 'Expired', NULL, 'Sports Equipment', NULL, NULL),
(40, 2, 'test1', 'aaaaaa', 1000.00, 1000.00, '2025-12-12 03:19:07', '2025-12-12 11:19:07', '2025-12-12 18:21:00', 'Expired', NULL, 'Stationery', NULL, NULL),
(41, 2, 'test1', 'aaaaaaaaaaa', 9999.00, 9999.00, '2025-12-12 13:42:43', '2025-12-12 21:42:43', '2025-12-12 21:44:00', 'Expired', NULL, 'Electronics', NULL, NULL),
(42, 2, 'test11', 'aaaaa', 6868.00, 6868.00, '2025-12-12 18:42:14', '2025-12-13 02:42:14', '2025-12-13 02:43:00', 'Expired', NULL, 'Stationery', NULL, NULL),
(43, 2, 'test5', 'aaaaaaaaaaaaaaaaaaaaa', 900.00, 900.00, '2025-12-12 18:45:14', '2025-12-13 02:45:14', '2025-12-13 02:46:00', 'Expired', NULL, 'Clothing', NULL, NULL),
(44, 2, 'test8', '7777', 5555.00, 5555.00, '2025-12-12 18:52:08', '2025-12-13 02:52:08', '2025-12-13 02:53:00', 'Expired', NULL, 'Electronics', NULL, NULL),
(45, 2, 'test15', 'aaa', 1000.00, 1000.00, '2025-12-12 19:16:28', '2025-12-13 03:16:28', '2025-12-13 03:17:00', 'Expired', NULL, 'Clothing', NULL, NULL),
(46, 2, 'test16', 'aa', 1000.00, 1000.00, '2025-12-12 19:17:39', '2025-12-13 03:17:39', '2025-12-13 03:18:00', 'Expired', NULL, 'Stationery', NULL, NULL),
(47, 2, 'aaa', 'aaa', 1000.00, 1000.00, '2025-12-12 19:23:48', '2025-12-13 03:23:48', '2025-12-13 03:24:00', 'Expired', NULL, 'Stationery', NULL, NULL),
(48, 2, '123123', '1111', 1000.00, 1000.00, '2025-12-12 19:56:44', '2025-12-13 03:56:44', '2025-12-13 03:58:00', 'Expired', NULL, 'Clothing', NULL, NULL),
(49, 2, 'testttt', '12123', 1000.00, 1000.00, '2025-12-12 20:04:13', '2025-12-13 04:04:13', '2025-12-13 04:05:00', 'Expired', NULL, 'Electronics', NULL, NULL),
(50, 2, 'aaa', 'aaa', 1111.00, 1111.00, '2025-12-12 20:31:04', '2025-12-13 04:31:04', '2025-12-13 06:30:00', 'Expired', NULL, 'Stationery', NULL, NULL),
(51, 2, 'hey', 'heyeeee', 1111.00, 1111.00, '2025-12-12 20:44:15', '2025-12-13 04:44:15', '2025-12-13 04:47:00', 'Expired', NULL, 'Electronics', NULL, NULL),
(52, 2, 'Aula F75 Keyboard', 'Condition 10/10\r\nRFS: Upgrade Keyboard', 1500.00, 1500.00, '2025-12-16 15:03:21', '2025-12-16 23:03:21', '2025-12-17 01:10:00', 'Expired', NULL, 'Electronics', NULL, NULL),
(53, 2, 'Aula F75 Keyboard', 'Condition: 10/10', 1500.00, 1500.00, '2025-12-16 15:06:20', '2025-12-16 23:06:20', '2025-12-17 13:10:00', 'Expired', NULL, 'Electronics', NULL, NULL),
(54, 5, 'Id lace', 'Selling it for a low price', 10.00, 200.00, '2025-12-17 05:12:53', '2025-12-19 13:12:00', '2025-12-25 13:12:00', 'Active', NULL, 'Clothing', NULL, NULL),
(55, 5, 'Id lace', 'Selling it for a low price', 10.00, 10.00, '2025-12-17 05:12:53', '2025-12-19 13:12:00', '2025-12-25 13:12:00', 'Active', NULL, 'Clothing', NULL, NULL),
(56, 2, 'Calculator', 'I need money', 1000.00, 1650.00, '2025-12-17 06:35:44', '2025-12-17 14:37:00', '2025-12-25 14:36:00', 'Active', NULL, 'Electronics', NULL, NULL),
(57, 3, 'Jeans', 'Not needed anymore', 1500.00, 1700.00, '2025-12-17 07:21:44', '2025-12-17 15:21:44', '2025-12-17 15:27:49', 'Sold', '695288', 'Clothing', '2025-12-17 07:33:52', 5),
(58, 5, 'Poco x6 pro', 'Upgrading to a newer phone\r\nCondition: 9/10\r\nAge: 1 year', 7000.00, 7000.00, '2025-12-17 07:25:24', '2025-12-17 15:25:24', '2025-12-17 15:27:00', 'Cancelled By Seller', NULL, 'Electronics', NULL, NULL),
(59, 5, 'Poco x6 pro', 'Upgrading to a newer phone\r\nCondition: 9/10\r\nAge: 1 year', 7000.00, 7000.00, '2025-12-17 07:25:24', '2025-12-17 15:25:24', '2025-12-17 15:27:00', 'Expired', NULL, 'Electronics', NULL, NULL),
(60, 3, 'Lacoste Wallet', 'Selling this at a very low price', 1000.00, 1000.00, '2025-12-17 07:45:34', '2025-12-17 15:45:34', '2025-12-26 15:45:00', 'Active', NULL, 'Accessories', NULL, NULL),
(61, 3, 'test', 'aaa', 1000.00, 1000.00, '2025-12-17 07:47:42', '2025-12-17 15:47:42', '2025-12-27 15:47:00', 'Removed By Admin', NULL, 'Stationery', NULL, NULL),
(62, 3, 'testinggggggs', 'bbbbbbb', 1111.00, 1111.00, '2025-12-17 07:51:11', '2025-12-17 15:51:11', '2025-12-27 15:51:00', 'Removed By Admin', NULL, 'Electronics', NULL, NULL),
(63, 3, 'test', 'saaa', 1111.00, 1111.00, '2025-12-17 07:53:56', '2025-12-17 15:53:56', '2025-12-26 15:53:00', 'Cancelled By Seller', NULL, 'Stationery', NULL, NULL),
(64, 3, 'test', 'aaa', 111.00, 111.00, '2025-12-17 07:54:47', '2025-12-17 15:54:47', '2025-12-19 15:54:00', 'Cancelled By Seller', NULL, 'Stationery', NULL, NULL),
(65, 3, 'aaaa', 'aaaa', 1111.00, 1111.00, '2025-12-17 07:59:44', '2025-12-17 15:59:44', '2025-12-27 15:59:00', 'Cancelled By Seller', NULL, 'Stationery', NULL, NULL),
(66, 3, 'aaaa', 'aaaa', 1111.00, 1111.00, '2025-12-17 07:59:44', '2025-12-17 15:59:44', '2025-12-27 15:59:00', 'Cancelled By Seller', NULL, 'Stationery', NULL, NULL),
(67, 3, 'aaaa', 'aaaa', 1111.00, 1111.00, '2025-12-17 08:05:22', '2025-12-17 16:05:22', '2025-12-20 16:05:00', 'Cancelled By Seller', NULL, 'Electronics', NULL, NULL),
(68, 3, 'aaa', 'aaaa', 1111.00, 1111.00, '2025-12-17 08:09:35', '2025-12-17 16:09:35', '2025-12-20 16:12:00', 'Cancelled By Seller', NULL, 'Electronics', NULL, NULL),
(69, 3, 'aaa', 'aaaa', 1111.00, 1111.00, '2025-12-17 08:10:02', '2025-12-17 16:10:02', '2025-12-26 16:09:00', 'Removed By Admin', NULL, 'Clothing', NULL, NULL),
(70, 3, 'aaaa', 'aaa', 11111.00, 11111.00, '2025-12-17 08:28:17', '2025-12-17 16:28:17', '2025-12-19 16:28:00', 'Removed By Admin', NULL, 'Stationery', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item_image`
--

CREATE TABLE `item_image` (
  `image_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_image`
--

INSERT INTO `item_image` (`image_id`, `item_id`, `image_url`) VALUES
(1, 1, 'assets/uploads/items/textbook1_front.webp'),
(2, 1, 'assets/uploads/items/textbook1_back.webp'),
(3, 2, 'assets/uploads/items/stationery1_pen.webp'),
(4, 3, 'assets/uploads/items/elec1_calc.jpg'),
(5, 4, 'assets/uploads/items/sports1_front.jpg'),
(6, 4, 'assets/uploads/items/sports1_back.jpg'),
(7, 4, 'assets/uploads/items/sports1_pump.webp'),
(8, 5, 'assets/uploads/items/acc1_umb.jpeg'),
(9, 6, 'assets/uploads/items/col1_card.webp'),
(10, 6, 'assets/uploads/items/col1_back.webp'),
(11, 7, 'assets/uploads/items/textbook2_cover.png'),
(12, 8, 'assets/uploads/items/clothes1_gown.jpg'),
(13, 9, 'assets/uploads/items/sports2_pads.webp'),
(14, 10, 'assets/uploads/items/furn1_full.jpg'),
(15, 10, 'assets/uploads/items/furn1_folded.webp'),
(16, 11, 'assets/uploads/items/col2_pose1.webp'),
(17, 11, 'assets/uploads/items/col2_box.webp'),
(18, 12, 'assets/uploads/items/textbook3_front.jpg'),
(19, 13, 'assets/uploads/items/elec2_top.webp'),
(20, 13, 'assets/uploads/items/elec2_bottom.webp'),
(21, 14, 'assets/uploads/items/clothes2_set.jpg'),
(22, 15, 'assets/uploads/items/acc2_bag.jpg'),
(23, 16, 'assets/uploads/items/col3_front.jpg'),
(24, 16, 'assets/uploads/items/col3_back.webp'),
(25, 17, 'assets/uploads/items/other3_jug.jpg'),
(26, 18, 'assets/uploads/items/stationery2_full.jpg'),
(27, 19, 'assets/uploads/items/elec3_main.webp'),
(28, 20, 'assets/uploads/items/sports3_racket.jpg'),
(29, 20, 'assets/uploads/items/sports3_bag.jpeg'),
(30, 21, 'assets/uploads/items/furn3_drawer.jpg'),
(31, 22, 'assets/uploads/items/other2_cord.webp'),
(32, 23, 'assets/uploads/items/stationery3_set.jpg'),
(33, 24, 'assets/uploads/items/clothes3_shirt.webp'),
(34, 25, 'assets/uploads/items/acc3_lace.jpg'),
(35, 26, 'assets/uploads/items/furn2_lamp.jpg'),
(36, 27, 'assets/uploads/items/other1_box.jpg'),
(37, 28, 'assets/uploads/items/fake_shoe.jpg'),
(38, 29, 'assets/uploads/items/brandy.jpg'),
(39, 30, 'assets/uploads/items/paper_leak.png'),
(40, 31, 'assets/uploads/items/broken_screen.jpg'),
(41, 32, 'assets/uploads/items/wallet.jpg'),
(42, 33, 'assets/uploads/items/notebook.jpg'),
(43, 34, 'assets/uploads/items/item_34_693bd6f142962.jpg'),
(44, 35, 'assets/uploads/items/item_35_693bd7b3aa2cc.webp'),
(45, 36, 'assets/uploads/items/item_36_693bdae52e22f.webp'),
(46, 37, 'assets/uploads/items/item_37_693bdc12bbc9b.webp'),
(47, 38, 'assets/uploads/items/item_38_693bdd6bde53d.webp'),
(48, 39, 'assets/uploads/items/item_39_693bde5229330.webp'),
(49, 40, 'assets/uploads/items/item_40_693bec1b1bd11.webp'),
(50, 41, 'assets/uploads/items/item_41_693c1bd35ab4e.jpg'),
(51, 42, 'assets/uploads/items/item_42_693c620630857.webp'),
(52, 43, 'assets/uploads/items/item_43_693c62ba6facd.webp'),
(53, 44, 'assets/uploads/items/item_44_693c645873565.webp'),
(54, 45, 'assets/uploads/items/item_45_693c6a0cc09bf.jpg'),
(55, 46, 'assets/uploads/items/item_46_693c6a53c238b.jpg'),
(56, 47, 'assets/uploads/items/item_47_693c6bc48d045.jpg'),
(57, 48, 'assets/uploads/items/item_48_693c737c0875e.png'),
(58, 49, 'assets/uploads/items/item_49_693c753d34891.webp'),
(59, 50, 'assets/uploads/items/item_50_693c7b88e3123.webp'),
(60, 51, 'assets/uploads/items/item_51_693c7e9f80b3f.webp'),
(61, 52, 'assets/uploads/items/item_52_694174b974e95.jpg'),
(62, 52, 'assets/uploads/items/item_52_694174b9752e3.jpg'),
(63, 52, 'assets/uploads/items/item_52_694174b98ff68.webp'),
(64, 53, 'assets/uploads/items/item_53_6941756c43dc6.jpg'),
(65, 53, 'assets/uploads/items/item_53_6941756c440fc.jpg'),
(66, 53, 'assets/uploads/items/item_53_6941756c44618.webp'),
(67, 54, 'assets/uploads/items/item_54_69423bd5693a5.png'),
(68, 55, 'assets/uploads/items/item_55_69423bd570287.png'),
(69, 56, 'assets/uploads/items/item_56_69424f40b3e3c.png'),
(70, 57, 'assets/uploads/items/item_57_69425a084d41d.jpg'),
(71, 58, 'assets/uploads/items/item_58_69425ae4a9ca6.jpg'),
(72, 59, 'assets/uploads/items/item_59_69425ae4b5d9e.jpg'),
(73, 60, 'assets/uploads/items/item_60_69425f9e8f588.png'),
(74, 61, 'assets/uploads/items/item_61_6942601e17eb3.png'),
(75, 62, 'assets/uploads/items/item_62_694260ef0fe44.png'),
(76, 63, 'assets/uploads/items/item_63_6942619474286.png'),
(77, 64, 'assets/uploads/items/item_64_694261c7c667a.png'),
(78, 65, 'assets/uploads/items/item_65_694262f03887d.png'),
(79, 66, 'assets/uploads/items/item_66_694262f03bb41.png'),
(80, 67, 'assets/uploads/items/item_67_694264428b768.png'),
(81, 68, 'assets/uploads/items/item_68_6942653f18583.png'),
(82, 69, 'assets/uploads/items/item_69_6942655a1ddf0.png'),
(83, 70, 'assets/uploads/items/item_70_694269a186f54.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `message_id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `message_text` text NOT NULL,
  `is_seller` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`message_id`, `conversation_id`, `message_text`, `is_seller`, `created_at`, `is_read`) VALUES
(1, 1, 'I won the book.', 0, '2025-11-27 05:28:03', 0),
(2, 2, 'Where to meet?', 0, '2025-11-30 04:28:03', 0),
(3, 3, 'Hi Juan! I won the mouse. Is it still working well?', 0, '2025-11-29 09:30:00', 1),
(4, 3, 'Yes Maria, barely used. Just upgraded.', 1, '2025-11-29 09:35:00', 1),
(5, 3, 'Great. Can we meet at the library tomorrow?', 0, '2025-11-29 09:40:00', 1),
(6, 3, 'Sure, see you at 10am.', 1, '2025-11-29 09:45:00', 1),
(7, 4, 'Hello, I got the PE Uniform. Is this strictly Small size?', 0, '2025-11-25 22:00:00', 1),
(8, 4, 'Yes, standard SLU small size.', 1, '2025-11-25 22:10:00', 1),
(9, 4, 'Okay good. I am at Otto Hahn building.', 0, '2025-11-25 22:15:00', 1),
(10, 4, 'Coming down now.', 1, '2025-11-25 22:16:00', 1),
(11, 5, 'Hi Maria, thanks for accepting the bid.', 0, '2025-11-28 22:00:00', 1),
(12, 5, 'No problem! Do you want to pick it up today?', 1, '2025-11-28 22:30:00', 1),
(13, 5, 'Yes, I have class at Charles V.', 0, '2025-11-28 23:00:00', 1),
(14, 5, 'Okay, message me when you are out.', 1, '2025-11-28 23:05:00', 1),
(15, 6, 'Is this fully charged?', 0, '2025-11-30 03:30:00', 1),
(16, 6, 'Yes, 100%. Ready to use.', 1, '2025-11-30 03:32:00', 1),
(17, 6, 'Okay, I will bring exact cash.', 0, '2025-11-30 03:35:00', 0),
(18, 6, 'Thanks, see you at the Canteen.', 1, '2025-11-30 03:36:00', 0),
(19, 7, 'Does this include the bag?', 0, '2025-11-29 05:30:00', 1),
(20, 7, 'Yes, the original black bag.', 1, '2025-11-29 05:35:00', 1),
(21, 7, 'Are there any scratches on the frame?', 0, '2025-11-29 05:40:00', 0),
(22, 7, 'Just minor ones on the top, but no cracks.', 1, '2025-11-29 05:45:00', 0),
(23, 11, 'aaaaaaaa', 0, '2025-12-14 12:54:45', 0),
(24, 13, 'aaaa', 0, '2025-12-15 18:18:02', 0),
(25, 11, 'hey where are you?', 0, '2025-12-17 07:06:29', 0),
(26, 11, 'what do you mean', 1, '2025-12-17 07:07:10', 0),
(27, 11, 'nothing', 0, '2025-12-17 07:07:23', 0),
(28, 11, '?', 0, '2025-12-17 07:07:49', 0),
(29, 11, 'hey', 0, '2025-12-17 07:15:51', 0),
(30, 11, 'heyy', 0, '2025-12-17 07:16:43', 0),
(31, 11, 'hello?', 0, '2025-12-17 07:17:17', 0),
(32, 14, 'Where are you, I\'ll give this to you right now.', 1, '2025-12-17 07:32:11', 0),
(33, 14, 'Here at the school canteen.', 0, '2025-12-17 07:32:37', 0),
(34, 14, 'aaa', 1, '2025-12-17 08:27:05', 0),
(35, 14, 'hello', 1, '2025-12-17 08:31:15', 0);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notif_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notif_title` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `notif_type` varchar(255) NOT NULL,
  `notif_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notif_id`, `user_id`, `notif_title`, `content`, `notif_type`, `notif_time`) VALUES
(1, 2, 'Item Removed', 'Your item \"Nike Fake Shoes\" was removed due to policy violation.', 'System', '2025-11-27 05:28:03'),
(2, 3, 'Item Sold', 'Accounting 101 Sold.', 'System', '2025-11-28 05:28:03'),
(3, 2, 'Item Sold! 💰', 'Your item \'Calculus TC7 Book\' sold for ₱550.00. Chat with the buyer now.', 'SOLD', '2025-12-12 13:40:55'),
(4, 5, 'Congratulations! 🎉', 'You won the auction for \'Calculus TC7 Book\'. Check your messages to arrange the meetup.', 'WIN', '2025-12-12 13:40:55'),
(5, 2, 'Item Sold! 💰', 'Your item \'Rotring Tech Pen\' sold for ₱650.00. Chat with the buyer now.', 'SOLD', '2025-12-12 13:40:55'),
(6, 5, 'Congratulations! 🎉', 'You won the auction for \'Rotring Tech Pen\'. Check your messages to arrange the meetup.', 'WIN', '2025-12-12 13:40:55'),
(7, 5, 'Item Sold! 💰', 'Your item \'Casio fx-991EX\' sold for ₱1,000.00. Chat with the buyer now.', 'SOLD', '2025-12-12 13:40:55'),
(8, 4, 'Congratulations! 🎉', 'You won the auction for \'Casio fx-991EX\'. Check your messages to arrange the meetup.', 'WIN', '2025-12-12 13:40:55'),
(9, 4, 'Item Sold! 💰', 'Your item \'Basketball\' sold for ₱420.00. Chat with the buyer now.', 'SOLD', '2025-12-12 13:40:55'),
(10, 3, 'Congratulations! 🎉', 'You won the auction for \'Basketball\'. Check your messages to arrange the meetup.', 'WIN', '2025-12-12 13:40:55'),
(11, 2, 'Item Sold! 💰', 'Your item \'Umbrella\' sold for ₱180.00. Chat with the buyer now.', 'SOLD', '2025-12-12 13:40:55'),
(12, 5, 'Congratulations! 🎉', 'You won the auction for \'Umbrella\'. Check your messages to arrange the meetup.', 'WIN', '2025-12-12 13:40:55'),
(13, 4, 'Item Sold! 💰', 'Your item \'Kpop Photocard\' sold for ₱300.00. Chat with the buyer now.', 'SOLD', '2025-12-12 13:40:55'),
(14, 2, 'Congratulations! 🎉', 'You won the auction for \'Kpop Photocard\'. Check your messages to arrange the meetup.', 'WIN', '2025-12-12 13:40:55'),
(15, 2, 'Auction Expired', 'Your listing \'Aula F75 Keyboard\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-12 13:40:55'),
(16, 2, 'Auction Expired', 'Your listing \'Puma Speedcat OG\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-12 13:40:55'),
(17, 2, 'Auction Expired', 'Your listing \'Test\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-12 13:40:55'),
(18, 2, 'Auction Expired', 'Your listing \'test2\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-12 13:40:55'),
(19, 2, 'Auction Expired', 'Your listing \'test3\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-12 13:40:55'),
(20, 2, 'Auction Expired', 'Your listing \'test4\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-12 13:40:55'),
(21, 2, 'Auction Expired', 'Your listing \'test1\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-12 13:40:55'),
(22, 2, 'Auction Expired', 'Your listing \'test1\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-12 13:44:00'),
(23, 2, 'Auction Expired', 'Your listing \'test11\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-12 18:43:03'),
(24, 2, 'Auction Expired', 'Your listing \'test5\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-12 18:46:03'),
(25, 2, 'Auction Expired', 'Your listing \'test8\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-12 18:53:00'),
(26, 2, 'Auction Expired', 'Your listing \'test15\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-15 18:16:16'),
(27, 2, 'Auction Expired', 'Your listing \'test16\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-15 18:16:16'),
(28, 2, 'Auction Expired', 'Your listing \'aaa\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-15 18:16:16'),
(29, 2, 'Auction Expired', 'Your listing \'123123\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-15 18:16:16'),
(30, 2, 'Auction Expired', 'Your listing \'testttt\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-15 18:16:16'),
(31, 2, 'Auction Expired', 'Your listing \'aaa\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-15 18:16:16'),
(32, 2, 'Auction Expired', 'Your listing \'aaaa\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-15 18:16:16'),
(33, 2, 'Auction Expired', 'Your listing \'Aula F75 Keyboard\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-16 17:10:03'),
(34, 2, 'Auction Expired', 'Your listing \'Aula F75 Keyboard\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-17 06:06:09'),
(35, 3, 'You\'ve been outbid!', 'Someone placed a bid of ₱1,500.00 on \'Calculator\'. Bid again now!', 'OUTBID', '2025-12-17 06:52:30'),
(36, 4, 'You\'ve been outbid!', 'Someone placed a bid of ₱1,600.00 on \'Calculator\'. Bid again now!', 'OUTBID', '2025-12-17 06:53:29'),
(37, 5, 'Auction Expired', 'Your listing \'Poco x6 pro\' ended with no bids. You can relist it from your profile.', 'EXPIRED', '2025-12-17 07:27:01'),
(38, 3, 'Item Sold! 💰', 'Your item \'Jeans\' sold for ₱1,700.00. Chat with the buyer now.', 'SOLD', '2025-12-17 07:27:52'),
(39, 5, 'Congratulations! 🎉', 'You won the auction for \'Jeans\'. Check your messages to arrange the meetup.', 'WIN', '2025-12-17 07:27:52');

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `rating_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `rater_id` int(11) NOT NULL,
  `ratee_id` int(11) NOT NULL,
  `rating_value` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`rating_id`, `item_id`, `rater_id`, `ratee_id`, `rating_value`, `comment`, `created_at`) VALUES
(1, 12, 4, 3, 5, 'Great book.', '2025-11-30 05:28:03'),
(2, 13, 3, 2, 4, 'Works fine.', '2025-11-30 05:28:03'),
(3, 57, 5, 3, 3, 'The item is overpriced and the seller is rude', '2025-12-17 07:36:27');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `report_id` int(11) NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `target_user_id` int(11) DEFAULT NULL,
  `target_item_id` int(11) DEFAULT NULL,
  `report_type` enum('User','Item') NOT NULL,
  `reason_type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `report_status` enum('Pending','In Review','Resolved','Dismissed') NOT NULL DEFAULT 'Pending',
  `admin_notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`report_id`, `reporter_id`, `target_user_id`, `target_item_id`, `report_type`, `reason_type`, `description`, `report_status`, `admin_notes`, `created_at`) VALUES
(1, 3, NULL, 28, 'Item', 'Counterfeit', 'These Nikes are clearly fake, logo is wrong.', 'Resolved', NULL, '2025-11-27 05:28:03'),
(2, 4, NULL, 29, 'Item', 'Prohibited Item', 'Alcohol is not allowed in SLU Bazaar.', 'Resolved', NULL, '2025-11-29 05:28:03'),
(3, 5, NULL, 30, 'Item', 'Inappropriate', 'Selling exam leaks is cheating.', 'Resolved', NULL, '2025-11-29 17:28:03'),
(4, 4, NULL, 1, 'Item', 'Inaccurate Description', 'Says good condition but photo looks old.', 'Dismissed', NULL, '2025-11-30 03:28:03'),
(5, 5, NULL, 2, 'Item', 'Other', 'Seller is rude.', 'Dismissed', '', '2025-11-30 05:28:03'),
(6, 4, NULL, 56, 'Item', 'Counterfeit', 'This seller is selling a fake calculator', 'Dismissed', '', '2025-12-17 06:44:09'),
(7, 4, NULL, 62, 'Item', 'Inappropriate Content', 'What in the item is this?', 'Resolved', '', '2025-12-17 08:35:12'),
(8, 3, NULL, 56, 'Item', 'Counterfeit', 'Fake calculator', 'Resolved', '', '2025-12-17 09:05:45'),
(9, 3, NULL, 56, 'Item', 'Counterfeit', 'This seller is selling an obvious fake calculator', 'Resolved', '', '2025-12-17 09:15:15'),
(10, 3, NULL, 56, 'Item', 'Counterfeit', 'This is a fake calculator', 'Pending', NULL, '2025-12-17 09:57:32');

-- --------------------------------------------------------

--
-- Table structure for table `report_image`
--

CREATE TABLE `report_image` (
  `report_image_id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `password_hash` varchar(255) NOT NULL,
  `average_rating` decimal(3,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `account_status` enum('active','unverified','banned') NOT NULL DEFAULT 'unverified',
  `role` enum('Admin','Member') NOT NULL DEFAULT 'Member',
  `profile_picture_url` varchar(255) NOT NULL DEFAULT '/assets/img/default-profile-pic.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `fname`, `lname`, `email`, `email_verified`, `password_hash`, `average_rating`, `created_at`, `account_status`, `role`, `profile_picture_url`) VALUES
(1, 'Super', 'Admin', 'admin@slu.edu.ph', 1, '$2y$10$faX6.tFlKh5jlPsJcDPxcOPkVMlowJjkXG9l0hi/T.qH8hOd7QR.q', 0.00, '2025-11-30 05:28:03', 'active', 'Admin', '/assets/img/default-profile-pic.jpg'),
(2, 'Juan', 'Dela Cruz', 'juan@slu.edu.ph', 1, '$2y$10$faX6.tFlKh5jlPsJcDPxcOPkVMlowJjkXG9l0hi/T.qH8hOd7QR.q', 4.00, '2025-11-30 05:28:03', 'active', 'Member', '/assets/img/default-profile-pic.jpg'),
(3, 'Maria', 'Santos', 'maria@slu.edu.ph', 1, '$2y$10$63x06uGFXhIi0Ck3e15o9O6CE1CJ5wa1d2.NeWXexSsu6SlX8JQqO', 4.00, '2025-11-30 05:28:03', 'active', 'Member', '/assets/img/default-profile-pic.jpg'),
(4, 'Pedro', 'Penduko', 'pedro@slu.edu.ph', 1, '$2y$10$faX6.tFlKh5jlPsJcDPxcOPkVMlowJjkXG9l0hi/T.qH8hOd7QR.q', 0.00, '2025-11-30 05:28:03', 'active', 'Member', '/assets/img/default-profile-pic.jpg'),
(5, 'Clara', 'Oswald', 'clara@slu.edu.ph', 1, '$2y$10$faX6.tFlKh5jlPsJcDPxcOPkVMlowJjkXG9l0hi/T.qH8hOd7QR.q', 0.00, '2025-11-30 05:28:03', 'active', 'Member', '/assets/img/default-profile-pic.jpg'),
(6, 'New', 'Admin', 'newadmin@slu.edu.ph', 1, '$2y$10$lndyI0kzszUOUyHcl4Zc0u/.bVathPGFyH4dhddM4hC8RmFekGosS', 0.00, '2025-12-16 02:31:40', 'active', 'Admin', '/assets/img/default-profile-pic.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `watchlist`
--

CREATE TABLE `watchlist` (
  `watchlist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `watchlist`
--

INSERT INTO `watchlist` (`watchlist_id`, `user_id`, `item_id`, `created_at`) VALUES
(1, 2, 3, '2025-11-30 05:28:03'),
(2, 2, 6, '2025-11-30 05:28:03'),
(3, 3, 1, '2025-11-30 05:28:03'),
(4, 3, 2, '2025-11-30 05:28:03'),
(5, 4, 4, '2025-11-30 05:28:03'),
(6, 4, 5, '2025-11-30 05:28:03'),
(7, 5, 3, '2025-11-30 05:28:03'),
(8, 5, 1, '2025-11-30 05:28:03'),
(9, 2, 18, '2025-11-30 05:28:03'),
(10, 3, 19, '2025-11-30 05:28:03'),
(11, 3, 53, '2025-12-17 05:58:40'),
(12, 3, 54, '2025-12-17 06:33:19'),
(13, 3, 56, '2025-12-17 06:36:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bid`
--
ALTER TABLE `bid`
  ADD PRIMARY KEY (`bid_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `bidder_id` (`bidder_id`);

--
-- Indexes for table `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`conversation_id`),
  ADD UNIQUE KEY `item_id` (`item_id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `item_status` (`item_status`),
  ADD KEY `category` (`category`),
  ADD KEY `buyer_id` (`buyer_id`);

--
-- Indexes for table `item_image`
--
ALTER TABLE `item_image`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `conversation_id` (`conversation_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notif_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `rater_id` (`rater_id`),
  ADD KEY `ratee_id` (`ratee_id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `reporter_id` (`reporter_id`),
  ADD KEY `target_user_id` (`target_user_id`),
  ADD KEY `target_item_id` (`target_item_id`);

--
-- Indexes for table `report_image`
--
ALTER TABLE `report_image`
  ADD PRIMARY KEY (`report_image_id`),
  ADD KEY `report_id` (`report_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `watchlist`
--
ALTER TABLE `watchlist`
  ADD PRIMARY KEY (`watchlist_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bid`
--
ALTER TABLE `bid`
  MODIFY `bid_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `conversation`
--
ALTER TABLE `conversation`
  MODIFY `conversation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `item_image`
--
ALTER TABLE `item_image`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notif_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `report_image`
--
ALTER TABLE `report_image`
  MODIFY `report_image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `watchlist`
--
ALTER TABLE `watchlist`
  MODIFY `watchlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bid`
--
ALTER TABLE `bid`
  ADD CONSTRAINT `bid_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bid_ibfk_2` FOREIGN KEY (`bidder_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `conversation`
--
ALTER TABLE `conversation`
  ADD CONSTRAINT `conversation_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversation_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversation_ibfk_3` FOREIGN KEY (`seller_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `item_image`
--
ALTER TABLE `item_image`
  ADD CONSTRAINT `item_image_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversation` (`conversation_id`) ON DELETE CASCADE;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rating_ibfk_2` FOREIGN KEY (`rater_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rating_ibfk_3` FOREIGN KEY (`ratee_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`reporter_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_ibfk_2` FOREIGN KEY (`target_user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_ibfk_3` FOREIGN KEY (`target_item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `report_image`
--
ALTER TABLE `report_image`
  ADD CONSTRAINT `report_image_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `report` (`report_id`) ON DELETE CASCADE;

--
-- Constraints for table `watchlist`
--
ALTER TABLE `watchlist`
  ADD CONSTRAINT `watchlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `watchlist_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
