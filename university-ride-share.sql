-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2024 at 11:14 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `isd-project`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) NOT NULL,
  `email` varchar(150) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `name`, `password`, `status`) VALUES
(1, 'admin@gmail.com', 'admin', '$2y$10$BpSERvtt884KWRm1OfMvMuNmN7wiKgTUvnaRzJc/mVd9RI3dpy3IS', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `destinationarea`
--

CREATE TABLE `destinationarea` (
  `area_id` int(11) NOT NULL,
  `area_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `driver`
--

CREATE TABLE `driver` (
  `driver_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `car_make` varchar(255) DEFAULT NULL,
  `car_model` varchar(255) DEFAULT NULL,
  `seating_capacity` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `reservation_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `reserved_seats` int(11) DEFAULT NULL,
  `reservation_date` datetime DEFAULT NULL,
  `ride_id` int(10) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ride`
--

CREATE TABLE `ride` (
  `ride_id` int(10) NOT NULL,
  `ride_to` varchar(200) NOT NULL,
  `ride_from` varchar(200) NOT NULL,
  `seats_available` int(10) NOT NULL,
  `time` time NOT NULL,
  `driver_id` int(10) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `destinationarea`
--
ALTER TABLE `destinationarea`
  ADD PRIMARY KEY (`area_id`);

--
-- Indexes for table `driver`
--
ALTER TABLE `driver`
  ADD PRIMARY KEY (`driver_id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `ride_id` (`ride_id`);

--
-- Indexes for table `ride`
--
ALTER TABLE `ride`
  ADD PRIMARY KEY (`ride_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `destinationarea`
--
ALTER TABLE `destinationarea`
  MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver`
--
ALTER TABLE `driver`
  MODIFY `driver_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ride`
--
ALTER TABLE `ride`
  MODIFY `ride_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `driver` (`driver_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ride_id` FOREIGN KEY (`ride_id`) REFERENCES `ride` (`ride_id`) ON DELETE CASCADE;

--
-- Constraints for table `ride`
--
ALTER TABLE `ride`
  ADD CONSTRAINT `driver_id` FOREIGN KEY (`driver_id`) REFERENCES `driver` (`driver_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
