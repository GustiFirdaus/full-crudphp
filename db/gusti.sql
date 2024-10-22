-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2024 at 02:23 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gusti`
--

-- --------------------------------------------------------

--
-- Table structure for table `firdaus`
--

CREATE TABLE `firdaus` (
  `NIM` bigint(20) NOT NULL,
  `NAMA` varchar(50) NOT NULL,
  `FAKULTAS` varchar(50) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tanggal_input` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `firdaus`
--

INSERT INTO `firdaus` (`NIM`, `NAMA`, `FAKULTAS`, `gambar`, `tanggal_input`) VALUES
(22539901, 'Susanto', 'FBI', '6715bcf4523b4.jpg', '2024-10-21'),
(22539903, 'Spongebob', 'FBI', 'susanto.jpg', '2024-10-21'),
(22539904, 'Sweja', 'FBI', 'rezaldi.jpg', '2024-10-21'),
(22539905, 'Akhmed', 'FBI', 'mamat.jpg', '2024-10-21'),
(225302219, 'Saleh', 'FBI', '6715ba25888f0.jpg', '2024-10-15'),
(225302220, 'Mei-Mei', 'FBI', '6715ba357063a.jpg', '2024-10-14'),
(225302221, 'Susanti', 'FBI', '6715ba5ae228b.jpg', '2024-10-16'),
(2253025487, 'Gusti Firdaus', 'FBI', 'logo-umpr.png', '2024-10-09'),
(2253025495, 'Muhammad Fakhri', 'FBI', '6715ba9a1f2f1.jpg', '2024-10-16'),
(2253025496, 'Fadel N.M', 'FBI', 'Untitled4.jpg', '2024-10-17'),
(2253025497, 'Muhammad Bakhri', 'FBI', '6715bd707eeb6.jpg', '2024-10-20'),
(2253025498, 'Rezaldi', 'FBI', '6715bdf0f24d2.jpg', '2024-10-18'),
(2253025499, 'Angelina Bethany', 'FBI', 'Untitled8.jpg', '2024-10-19'),
(2253025501, 'Cecilia Arabel', 'FBI', '6711de59905c1.jpg', '2024-10-20'),
(22530259128, 'Mamat Santoso', 'FBI', 'mamat.jpg', '2024-10-21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`) VALUES
(3, 'admin', '$2y$10$mLUCjhiE2PQiFirC2.leYeS5JleaXXaAfHrYlg6ZVOytF2i0LfrPq', 'Firdaus');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `firdaus`
--
ALTER TABLE `firdaus`
  ADD PRIMARY KEY (`NIM`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
