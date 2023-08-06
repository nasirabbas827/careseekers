-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 06, 2023 at 06:35 AM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `careseeker`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `care_seekers`
--

CREATE TABLE `care_seekers` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `care_seekers`
--

INSERT INTO `care_seekers` (`id`, `full_name`, `email`, `password`, `contact_number`, `address`, `status`) VALUES
(1, 'NASIR ABBAS', 'nasiryt.@gmail.com', '123', '3176526827', 'Street jeff xxxx\r\nAp04', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'elder'),
(2, 'sick people'),
(3, 'baby care'),
(4, 'cooking'),
(5, 'personal care'),
(6, 'animal care'),
(7, 'gym instructor'),
(8, 'domestic assistance');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `worker_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `reply` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `job_id`, `worker_id`, `message`, `timestamp`, `reply`) VALUES
(1, 3, 1, 'Hy ', '2023-08-06 03:15:26', 'Yes'),
(2, 3, 1, 'i want a job', '2023-08-06 03:36:32', 'yes i will tell you'),
(3, 3, 1, 'Hy ', '2023-08-06 03:40:22', NULL),
(4, 3, 1, 'i want a job', '2023-08-06 03:41:07', NULL),
(5, 3, 1, 'Hy ', '2023-08-06 03:43:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `care_seeker_id` int(11) NOT NULL,
  `required_service` varchar(255) NOT NULL,
  `detail` text NOT NULL,
  `address` text NOT NULL,
  `estimated_hourly_budget` decimal(10,2) NOT NULL,
  `time_of_service` datetime NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `care_seeker_id`, `required_service`, `detail`, `address`, `estimated_hourly_budget`, `time_of_service`, `status`, `category_id`) VALUES
(1, 1, 'baby care', 'i want a person for my baby', 'jampur', '100.00', '2023-08-05 13:59:00', 'approved', 1),
(2, 1, 'sick people', 'asdsadd', 'gad', '2000.00', '2023-08-05 14:05:00', 'accepted', 2),
(3, 1, 'animal care', 'kk', 'kkk', '2.00', '2023-08-05 14:05:00', 'accepted', 6);

-- --------------------------------------------------------

--
-- Table structure for table `job_accepted`
--

CREATE TABLE `job_accepted` (
  `id` int(11) NOT NULL,
  `worker_id` int(11) DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `careseeker_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `required_service` varchar(255) DEFAULT NULL,
  `accepted_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `job_accepted`
--

INSERT INTO `job_accepted` (`id`, `worker_id`, `job_id`, `careseeker_id`, `category_id`, `required_service`, `accepted_date`) VALUES
(1, 1, 3, 1, 6, 'animal care', '2023-08-06 04:24:26'),
(2, 1, 2, 1, 2, 'sick people', '2023-08-06 04:24:28');

-- --------------------------------------------------------

--
-- Table structure for table `support_workers`
--

CREATE TABLE `support_workers` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `hourly_rate` decimal(10,2) NOT NULL,
  `experience` text NOT NULL,
  `reference1` varchar(255) NOT NULL,
  `reference2` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `support_workers`
--

INSERT INTO `support_workers` (`id`, `full_name`, `email`, `password`, `contact_number`, `picture`, `hourly_rate`, `experience`, `reference1`, `reference2`, `category`, `status`, `registration_date`) VALUES
(1, 'NASIR ABBAS', 'nasiryt.827@gmail.com', '123', '3176526827', 'uploads/2.PNG', '20.00', 'fdd', 'sf', 'sfsd', '1', 'approved', '2023-08-05 08:25:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `care_seekers`
--
ALTER TABLE `care_seekers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `worker_id` (`worker_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `care_seeker_id` (`care_seeker_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `job_accepted`
--
ALTER TABLE `job_accepted`
  ADD PRIMARY KEY (`id`),
  ADD KEY `worker_id` (`worker_id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `careseeker_id` (`careseeker_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `support_workers`
--
ALTER TABLE `support_workers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `care_seekers`
--
ALTER TABLE `care_seekers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `job_accepted`
--
ALTER TABLE `job_accepted`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `support_workers`
--
ALTER TABLE `support_workers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`),
  ADD CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`worker_id`) REFERENCES `support_workers` (`id`);

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`care_seeker_id`) REFERENCES `care_seekers` (`id`),
  ADD CONSTRAINT `jobs_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `job_accepted`
--
ALTER TABLE `job_accepted`
  ADD CONSTRAINT `job_accepted_ibfk_1` FOREIGN KEY (`worker_id`) REFERENCES `support_workers` (`id`),
  ADD CONSTRAINT `job_accepted_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`),
  ADD CONSTRAINT `job_accepted_ibfk_3` FOREIGN KEY (`careseeker_id`) REFERENCES `care_seekers` (`id`),
  ADD CONSTRAINT `job_accepted_ibfk_4` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
