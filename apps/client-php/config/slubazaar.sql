-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2025 at 09:36 PM
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
(22, 32, 4, 300.00, '2025-11-28 05:28:03');

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
(7, 20, 2, 3, 'Active');

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
(1, 2, 'Calculus TC7 Book', 'Standard engineering math book.', 500.00, 550.00, '2025-11-30 05:28:03', '2025-11-30 13:28:03', '2025-12-05 13:28:03', 'Active', NULL, 'Textbooks', NULL, NULL),
(2, 2, 'Rotring Tech Pen', '0.5mm tip.', 600.00, 650.00, '2025-11-30 05:28:03', '2025-11-30 13:28:03', '2025-12-02 13:28:03', 'Active', NULL, 'Stationery', NULL, NULL),
(3, 5, 'Casio fx-991EX', 'Classwiz calculator.', 900.00, 1000.00, '2025-11-30 05:28:03', '2025-11-30 13:28:03', '2025-12-03 13:28:03', 'Active', NULL, 'Electronics', NULL, NULL),
(4, 4, 'Basketball', 'Molten generic.', 400.00, 420.00, '2025-11-30 05:28:03', '2025-11-30 13:28:03', '2025-12-04 13:28:03', 'Active', NULL, 'Sports Equipment', NULL, NULL),
(5, 2, 'Umbrella', 'Foldable black.', 150.00, 180.00, '2025-11-30 05:28:03', '2025-11-30 13:28:03', '2025-12-01 13:28:03', 'Active', NULL, 'Accessories', NULL, NULL),
(6, 4, 'Kpop Photocard', 'Twice.', 100.00, 300.00, '2025-11-30 05:28:03', '2025-11-30 13:28:03', '2025-12-06 13:28:03', 'Active', NULL, 'Collectibles', NULL, NULL),
(7, 5, 'Anatomy & Physiology', 'Hardbound.', 1200.00, 1200.00, '2025-11-30 05:28:03', '2025-12-02 13:28:03', '2025-12-09 13:28:03', 'Pending', NULL, 'Textbooks', NULL, NULL),
(8, 2, 'Lab Gown', 'Canvas material.', 300.00, 300.00, '2025-11-30 05:28:03', '2025-12-01 13:28:03', '2025-12-05 13:28:03', 'Pending', NULL, 'Clothing', NULL, NULL),
(9, 2, 'Volleyball Knee Pads', 'Asics.', 200.00, 200.00, '2025-11-30 05:28:03', '2025-12-03 13:28:03', '2025-12-10 13:28:03', 'Pending', NULL, 'Sports Equipment', NULL, NULL),
(10, 4, 'Drafting Table', 'Adjustable height.', 1500.00, 1500.00, '2025-11-30 05:28:03', '2025-12-05 13:28:03', '2025-12-12 13:28:03', 'Pending', NULL, 'Furniture', NULL, NULL),
(11, 3, 'Gundam Model', 'HG scale built.', 400.00, 400.00, '2025-11-30 05:28:03', '2025-12-04 13:28:03', '2025-12-11 13:28:03', 'Pending', NULL, 'Collectibles', NULL, NULL),
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
(33, 3, 'Change of Mind', 'Not selling anymore.', 100.00, 100.00, '2025-11-30 05:28:03', '2025-11-29 13:28:03', '2025-12-06 13:28:03', 'Cancelled By Seller', NULL, 'Stationery', NULL, NULL);

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
(1, 1, 'uploads/items/textbook1_front.jpg'),
(2, 1, 'uploads/items/textbook1_back.webp'),
(3, 2, 'uploads/items/stationery1_pen.webp'),
(4, 3, 'uploads/items/elec1_calc.jpg'),
(5, 4, 'uploads/items/sports1_front.jpg'),
(6, 4, 'uploads/items/sports1_back.jpg'),
(7, 4, 'uploads/items/sports1_pump.jpg'),
(8, 5, 'uploads/items/acc1_umb.jpeg'),
(9, 6, 'uploads/items/col1_card.jpg'),
(10, 6, 'uploads/items/col1_back.jpg'),
(11, 7, 'uploads/items/textbook2_cover.jpg'),
(12, 8, 'uploads/items/clothes1_gown.jpg'),
(13, 9, 'uploads/items/sports2_pads.jpg'),
(14, 10, 'uploads/items/furn1_full.jpg'),
(15, 10, 'uploads/items/furn1_folded.jpg'),
(16, 11, 'uploads/items/col2_pose1.jpg'),
(17, 11, 'uploads/items/col2_box.jpg'),
(18, 12, 'uploads/items/textbook3_front.jpg'),
(19, 13, 'uploads/items/elec2_top.jpg'),
(20, 13, 'uploads/items/elec2_bottom.jpg'),
(21, 14, 'uploads/items/clothes2_set.jpg'),
(22, 15, 'uploads/items/acc2_bag.jpg'),
(23, 16, 'uploads/items/col3_front.jpg'),
(24, 16, 'uploads/items/col3_back.jpg'),
(25, 17, 'uploads/items/other3_jug.jpg'),
(26, 18, 'uploads/items/stationery2_full.jpg'),
(27, 19, 'uploads/items/elec3_main.jpg'),
(28, 20, 'uploads/items/sports3_racket.jpg'),
(29, 20, 'uploads/items/sports3_bag.jpg'),
(30, 21, 'uploads/items/furn3_drawer.jpg'),
(31, 22, 'uploads/items/other2_cord.jpg'),
(32, 23, 'uploads/items/stationery3_set.jpg'),
(33, 24, 'uploads/items/clothes3_shirt.jpg'),
(34, 25, 'uploads/items/acc3_lace.jpg'),
(35, 26, 'uploads/items/furn2_lamp.jpg'),
(36, 27, 'uploads/items/other1_box.jpg'),
(37, 28, 'uploads/items/fake_shoe.jpg'),
(38, 29, 'uploads/items/brandy.jpg'),
(39, 30, 'uploads/items/paper_leak.jpg'),
(40, 31, 'uploads/items/broken_screen.jpg'),
(41, 32, 'uploads/items/wallet.jpg'),
(42, 33, 'uploads/items/notebook.jpg');

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
(22, 7, 'Just minor ones on the top, but no cracks.', 1, '2025-11-29 05:45:00', 0);

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
(2, 3, 'Item Sold', 'Accounting 101 Sold.', 'System', '2025-11-28 05:28:03');

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
(2, 13, 3, 2, 4, 'Works fine.', '2025-11-30 05:28:03');

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
(5, 5, NULL, 2, 'Item', 'Other', 'Seller is rude.', 'Pending', NULL, '2025-11-30 05:28:03');

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
(2, 'Juan', 'Dela Cruz', 'juan@slu.edu.ph', 1, '$2y$10$faX6.tFlKh5jlPsJcDPxcOPkVMlowJjkXG9l0hi/T.qH8hOd7QR.q', 4.50, '2025-11-30 05:28:03', 'active', 'Member', '/assets/img/default-profile-pic.jpg'),
(3, 'Maria', 'Santos', 'maria@slu.edu.ph', 1, '$2y$10$faX6.tFlKh5jlPsJcDPxcOPkVMlowJjkXG9l0hi/T.qH8hOd7QR.q', 4.80, '2025-11-30 05:28:03', 'active', 'Member', '/assets/img/default-profile-pic.jpg'),
(4, 'Pedro', 'Penduko', 'pedro@slu.edu.ph', 1, '$2y$10$faX6.tFlKh5jlPsJcDPxcOPkVMlowJjkXG9l0hi/T.qH8hOd7QR.q', 3.50, '2025-11-30 05:28:03', 'active', 'Member', '/assets/img/default-profile-pic.jpg'),
(5, 'Clara', 'Oswald', 'clara@slu.edu.ph', 1, '$2y$10$faX6.tFlKh5jlPsJcDPxcOPkVMlowJjkXG9l0hi/T.qH8hOd7QR.q', 5.00, '2025-11-30 05:28:03', 'active', 'Member', '/assets/img/default-profile-pic.jpg');

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
(10, 3, 19, '2025-11-30 05:28:03');

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
  MODIFY `bid_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `conversation`
--
ALTER TABLE `conversation`
  MODIFY `conversation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `item_image`
--
ALTER TABLE `item_image`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notif_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `report_image`
--
ALTER TABLE `report_image`
  MODIFY `report_image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `watchlist`
--
ALTER TABLE `watchlist`
  MODIFY `watchlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
