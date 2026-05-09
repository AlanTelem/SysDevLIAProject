-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 09, 2026 at 02:03 AM
-- Server version: 11.8.5-MariaDB-log
-- PHP Version: 8.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tcg_inventory`
--
DROP DATABASE IF EXISTS `tcg_inventory`;
CREATE DATABASE IF NOT EXISTS `tcg_inventory` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `tcg_inventory`;

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `hash` char(72) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `card filter`
-- (See below for the actual view)
--
CREATE TABLE `card filter` (
`stock` bigint(21)
,`physical_condition` varchar(20)
,`foil` varchar(5)
,`name` varchar(200)
,`condition_id` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `blueprint_id` int(11) NOT NULL,
  `condition_id` int(11) NOT NULL,
  `foil` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `card_blueprints`
--

CREATE TABLE `card_blueprints` (
  `id` int(11) NOT NULL,
  `maker_id` text DEFAULT NULL,
  `set_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `thumbnail_url` text DEFAULT NULL,
  `large_art_url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `card_condition`
--

CREATE TABLE `card_condition` (
  `id` int(11) NOT NULL,
  `physical_condition` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_ins`
--

CREATE TABLE `log_ins` (
  `profile_id` int(11) NOT NULL,
  `log_in_time` datetime NOT NULL DEFAULT current_timestamp(),
  `log_out_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `privilege` tinyint(1) NOT NULL,
  `authorization_hash` char(72) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sets`
--

CREATE TABLE `sets` (
  `id` int(11) NOT NULL,
  `tcg_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `maker_designated_id` varchar(30) DEFAULT NULL,
  `release_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trading_card_games`
--

CREATE TABLE `trading_card_games` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_CARDS_CONDITION_ID` (`condition_id`),
  ADD KEY `FK_CARDS_BLUEPRINT_ID` (`blueprint_id`);

--
-- Indexes for table `card_blueprints`
--
ALTER TABLE `card_blueprints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_CARD_BLUEPRINTS_SET_ID` (`set_id`);

--
-- Indexes for table `card_condition`
--
ALTER TABLE `card_condition`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_ins`
--
ALTER TABLE `log_ins`
  ADD KEY `FK_LOG_INS_PROFILE_ID` (`profile_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_PROFILES_ACCOUNT_ID` (`account_id`);

--
-- Indexes for table `sets`
--
ALTER TABLE `sets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_SETS_TCG_ID` (`tcg_id`);

--
-- Indexes for table `trading_card_games`
--
ALTER TABLE `trading_card_games`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `card_blueprints`
--
ALTER TABLE `card_blueprints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `card_condition`
--
ALTER TABLE `card_condition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sets`
--
ALTER TABLE `sets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trading_card_games`
--
ALTER TABLE `trading_card_games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Structure for view `card filter`
--
DROP TABLE IF EXISTS `card filter`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`::1` SQL SECURITY DEFINER VIEW `card filter`  AS SELECT count(0) AS `stock`, `cc`.`physical_condition` AS `physical_condition`, if(`c`.`foil`,'true','false') AS `foil`, `cb`.`name` AS `name`, `c`.`condition_id` AS `condition_id` FROM ((`cards` `c` join `card_blueprints` `cb` on(`cb`.`id` = `c`.`blueprint_id`)) join `card_condition` `cc` on(`cc`.`id` = `c`.`condition_id`)) GROUP BY `c`.`blueprint_id`, `c`.`condition_id`, `c`.`foil` ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `FK_CARDS_BLUEPRINT_ID` FOREIGN KEY (`blueprint_id`) REFERENCES `card_blueprints` (`id`),
  ADD CONSTRAINT `FK_CARDS_CONDITION_ID` FOREIGN KEY (`condition_id`) REFERENCES `card_condition` (`id`);

--
-- Constraints for table `card_blueprints`
--
ALTER TABLE `card_blueprints`
  ADD CONSTRAINT `FK_CARD_BLUEPRINTS_SET_ID` FOREIGN KEY (`set_id`) REFERENCES `sets` (`id`);

--
-- Constraints for table `log_ins`
--
ALTER TABLE `log_ins`
  ADD CONSTRAINT `FK_LOG_INS_PROFILE_ID` FOREIGN KEY (`profile_id`) REFERENCES `profiles` (`id`);

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `FK_PROFILES_ACCOUNT_ID` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `sets`
--
ALTER TABLE `sets`
  ADD CONSTRAINT `FK_SETS_TCG_ID` FOREIGN KEY (`tcg_id`) REFERENCES `trading_card_games` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
