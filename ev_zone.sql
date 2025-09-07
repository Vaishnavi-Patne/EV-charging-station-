-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2024 at 04:23 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ev_zone`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `slot_id`, `start_time`, `end_time`, `total_price`, `payment_status`, `created_at`) VALUES
(1, 3, 1, '2024-07-21 21:30:00', '2024-07-21 21:30:00', '0.00', 'pending', '2024-07-21 16:00:41'),
(2, 3, 2, '2024-07-21 21:34:00', '2024-07-21 21:34:00', '0.00', 'pending', '2024-07-21 16:04:28'),
(3, 3, 2, '2024-07-21 22:39:00', '2024-07-21 21:40:00', '98.33', 'completed', '2024-07-21 16:09:59'),
(4, 3, 1, '2024-07-20 21:48:00', '2024-07-28 21:48:00', '1920.00', 'completed', '2024-07-21 16:18:49'),
(5, 3, 1, '2024-07-20 21:48:00', '2024-07-28 21:48:00', '1920.00', 'pending', '2024-07-21 16:19:05');

-- --------------------------------------------------------

--
-- Table structure for table `ev_stations`
--

CREATE TABLE `ev_stations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `area` varchar(100) NOT NULL,
  `directions_link` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ev_stations`
--

INSERT INTO `ev_stations` (`id`, `name`, `address`, `area`, `directions_link`, `description`, `is_approved`, `created_at`, `user_id`) VALUES
(1, 'Station1', 'guruprasad building sriram colony', 'Belgaum', 'http://sonytech.in/', 'Station1 Station1 Station1 Station1 Station2 Station3 Station4 Station', 1, '2024-07-21 15:48:32', NULL),
(2, 'station2', 'guruprasad building sriram colony', 'Belgaum', 'http://sonytech.in/', 'station2 station2 station2 station2 station2 station2 station2', 1, '2024-07-21 15:54:03', 3),
(3, 'station3', 'guruprasad building sriram colony', 'Belgaum', 'http://sonytech.in/', 'station3\r\nstation3\r\nstation3\r\nstation3\r\nstation3', 1, '2024-07-21 16:25:57', 4);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `content`, `slug`, `created_at`) VALUES
(1, 'lalalla', 'Hii', '1.php', '2024-07-21 16:23:03');

-- --------------------------------------------------------

--
-- Table structure for table `slots`
--

CREATE TABLE `slots` (
  `id` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `slot_number` int(11) NOT NULL,
  `price_per_hour` decimal(10,2) NOT NULL,
  `is_operational` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slots`
--

INSERT INTO `slots` (`id`, `station_id`, `slot_number`, `price_per_hour`, `is_operational`) VALUES
(1, 2, 1, '10.00', 1),
(2, 2, 2, '100.00', 1),
(3, 2, 3, '11.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `full_name`, `created_at`) VALUES
(1, 'admin', '$2y$10$TUlk0v/yC1IR8cQyS1da9.4Z6XRix5t8Gr.PXNNe4MIrGGKANd3XG', 'admin@evzone.com', 'Admin User', '2024-07-21 15:30:51'),
(2, 'station', '$2y$10$8IjGzRw7xQpu1FJCh1Srj.DmXe3.wbzkFjkOXzQHxHrgCI8EKDe1u', 'station@evzone.com', 'Station User', '2024-07-21 15:30:51'),
(3, 'pranay', '$2y$10$TUlk0v/yC1IR8cQyS1da9.4Z6XRix5t8Gr.PXNNe4MIrGGKANd3XG', 'pm@gmail.com', 'pranay', '2024-07-21 15:44:56'),
(4, 'user1', '$2y$10$gUbq9icElukDCL4p6Fh/T.lpmtbN3cTTdjT9Mfx.BlyqA5lo7ffVq', 'user1@gmail.com', 'user1', '2024-07-21 16:24:08');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `model` varchar(100) NOT NULL,
  `license_plate` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `slot_id` (`slot_id`);

--
-- Indexes for table `ev_stations`
--
ALTER TABLE `ev_stations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `slots`
--
ALTER TABLE `slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `station_id` (`station_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ev_stations`
--
ALTER TABLE `ev_stations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `slots`
--
ALTER TABLE `slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`slot_id`) REFERENCES `slots` (`id`);

--
-- Constraints for table `ev_stations`
--
ALTER TABLE `ev_stations`
  ADD CONSTRAINT `ev_stations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `slots`
--
ALTER TABLE `slots`
  ADD CONSTRAINT `slots_ibfk_1` FOREIGN KEY (`station_id`) REFERENCES `ev_stations` (`id`);

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
