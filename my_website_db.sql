-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 25, 2025 at 02:21 AM
-- Server version: 8.0.41-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_website_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `email`, `password`, `first_name`, `last_name`, `created_at`, `updated_at`) VALUES
(1, 'first-admin', 'admin@gmail.com', '$2y$10$TXq1PeM3UmyaHwa8SREoCO.wbtFJLZQOcA4rPhYbpRGq1CSBNmYqq', 'dan', 'lam', '2025-04-17 04:12:02', '2025-04-19 08:18:56'),
(3, 'admin-danlam', 'admin123@gmail.com', '$2y$10$Os6g.w9mpiwDkG5HUPJch.R/WHXeQeCnp1TLBGcsmEWMggBN4Vjnq', 'admin-dan', 'admin-lam', '2025-04-19 10:06:36', '2025-04-19 10:06:36'),
(4, 'user111', 'fakeuser@gmail.com', '$2y$10$GUdlnSQCS98TEy8SOCyCOO8p5K0peUuyWtwgNoCWwvWkjLvjgBPWe', 'dada', 'lala', '2025-04-19 10:28:47', '2025-04-19 10:28:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthday` date DEFAULT NULL,
  `sex` enum('Male','Female') COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = Active, 0 = Deactivated',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `first_name`, `last_name`, `birthday`, `sex`, `profile_photo_path`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 'user1', 'random@gmail.com', '$2y$10$H5EDb2yhIsj8QJD2tmVRiuE4JcdP1boroNAzfsxsdl4FWVU50Zism', 'erik123', 'Agabon23', '2024-10-25', 'Female', NULL, 0, '2025-04-18 05:23:21', '2025-04-19 16:19:18'),
(5, 'Marijuanster', 'lamat444@gmail.com', '$2y$10$PEPekYC/K5HOaL/LKFi0nehIaG.VqxxD33SRSNvORYmzMvU53Iu9S', 'daniel', 'lamaton', '2025-04-04', 'Female', NULL, 1, '2025-04-18 12:19:33', '2025-04-19 10:34:16'),
(6, 'admin123', 'admin123@gmail.com', '$2y$10$.sAxASHIgaBwYigVF9ZKKu2w0.1lIuzxl3Nhnvz490Ei6C4xIonpu', 'admin', 'minminmin', '2025-04-02', 'Female', NULL, 1, '2025-04-18 15:52:43', '2025-04-19 10:53:45'),
(7, 'dsadsadsa', 'random1233@gmail.com', '$2y$10$6jGn/e35Wj1zEmaNSm12JeT0HDsOHF4fYf78hDXYn1FV1wSb4HSpS', 'fakeuser', 'user', '2025-04-23', 'Male', NULL, 1, '2025-04-19 04:19:20', '2025-04-19 10:16:45'),
(8, 'sadas', 'sadas@gmail.com', '$2y$10$Kt7bJ195mW7VHDwUF66c4OpsFuiBhFJQosQdETiAw0DL7n2HXyX3O', 'ewq', 'wqeqw', '2025-04-09', 'Male', NULL, 0, '2025-04-19 16:21:39', '2025-04-19 16:21:57'),
(9, 'imagetest', 'imaeg123@gmail.com', '$2y$10$0IEs0EQw7laPtsi2ZJPcfuuduTmeGW4eqMY9y9isbAO.dS2EK8uuG', 'imagedan', 'imagelam', '2025-04-01', 'Male', '/webdevfinal/assets/uploads/user_9_a75ef12d1de4c3f0.jpg', 1, '2025-04-20 06:22:10', '2025-04-22 04:52:50'),
(10, 'imageimage', 'imageimage@gmail.com', '$2y$10$28jXdj3gTXfWXB7krSGKlO40F497aUKSwMIwSisKz37VhLNdI9KHe', 'test', 'testtest', '2025-04-08', 'Male', '/webdevfinal/assets/uploads/user_10_291aedf2114035cf.png', 1, '2025-04-20 10:50:22', '2025-04-20 11:07:10'),
(11, 'Gizaki', 'gainaraneta@gmail.com', '$2y$10$1XgoRC2IVc26QL4Q0o7Am.wagzxRyzGUrchThjpkEVH7VQMUpCLwC', 'gian', 'Araneta', '2025-04-01', 'Male', '/webdevfinal/assets/uploads/user_6805d84f839545.11223457.jpg', 1, '2025-04-21 05:31:59', '2025-04-21 05:31:59'),
(12, 'gian123', 'gain@gmail.com', '$2y$10$WRnVnwuQJqv3i21S6AGAluWn4DelsyIzIMky00iCK4xqhpd3..XKK', 'giangay', 'araneta', '2025-06-04', 'Female', '/webdevfinal/assets/uploads/user_6805d8bb61e4d6.44393065.jpg', 0, '2025-04-21 05:33:47', '2025-04-21 05:34:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_admin_username` (`username`),
  ADD KEY `idx_admin_email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_last_name` (`last_name`),
  ADD KEY `idx_first_name` (`first_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
