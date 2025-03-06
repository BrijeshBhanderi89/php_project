-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2025 at 06:14 AM
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
-- Database: `brijesh`
--

-- --------------------------------------------------------

--
-- Table structure for table `insert-data`
--

CREATE TABLE `insert-data` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `hobby` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `insert-data`
--

INSERT INTO `insert-data` (`id`, `name`, `email`, `hobby`, `image`, `phone`, `message`) VALUES
(1, 'Yash Bhanderi', 'yashbhanderi89@gmailcom', 'cricket,coding,singing,dancing', '67990baeba943.jpg', '9924349580', 'Very Good'),
(2, 'Vivek Bhanderi', 'vivek890@gmail.com', 'cricket,coding,singing,dancing', '67990bc0d2bb6.jpg', '9924349580', 'Very Good'),
(3, 'Raj Bhanderi', 'rajbhanderi89@gmailcom', 'cricket,coding,singing,dancing', '67990bcf4be31.jpg', '9924349523', 'Very Good'),
(4, 'Brijesh Bhanderi', 'brijeshbhanderi@gmail.com', 'cricket,coding,singing,dancing', '67990bdd187d7.jpg', '9924349580', 'Very Good'),
(5, 'Kash Bhanderi', 'kashbhanderi@gmail.com', 'cricket,coding,singing,dancing', '67990ba1e664d.jpg', '9924349570', 'Very Good'),
(52, 'BRIJESH BHANDERI', 'BRIJESHBHANDERI@GMAIL.COM', 'cricket,coding', 'assets/image/6799a344dde9b.jpg', '9924349570', 'very good');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `insert-data`
--
ALTER TABLE `insert-data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `insert-data`
--
ALTER TABLE `insert-data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
