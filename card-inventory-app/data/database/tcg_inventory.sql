-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 02, 2026 at 04:22 PM
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

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `email`, `hash`) VALUES
(1, 'admin@example.com', '$2y$10$ExampleHash123456789012345678901234567890123456789012'),
(2, 'collector@example.com', '$2y$10$UserHash0987654321098765432109876543210987654321098'),
(3, 'user1@test.com', 'hash_ref_1'),
(4, 'user2@test.com', 'hash_ref_2'),
(5, 'user3@test.com', 'hash_ref_3'),
(6, 'user4@test.com', 'hash_ref_4'),
(7, 'user5@test.com', 'hash_ref_5');

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

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`, `blueprint_id`, `condition_id`, `foil`) VALUES
(1, 1, 1, 0),
(2, 2, 2, 1),
(3, 3, 3, 1),
(4, 1, 1, 0),
(5, 2, 2, 0),
(6, 3, 3, 1),
(7, 4, 4, 1),
(8, 5, 1, 0),
(9, 6, 2, 1),
(10, 7, 3, 0),
(11, 8, 4, 0),
(12, 1, 2, 1),
(13, 2, 3, 0),
(14, 3, 4, 1),
(15, 4, 1, 0),
(16, 5, 2, 0),
(17, 6, 3, 1),
(18, 7, 4, 1),
(19, 8, 1, 1),
(20, 4, 2, 0),
(21, 5, 3, 1),
(22, 6, 4, 0),
(23, 8, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `card_blueprints`
--

CREATE TABLE `card_blueprints` (
  `id` int(11) NOT NULL,
  `set_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `card_blueprints`
--

INSERT INTO `card_blueprints` (`id`, `set_id`, `name`) VALUES
(1, 1, 'Black Lotus'),
(2, 2, 'Charizard'),
(3, 3, 'Solitude'),
(4, 4, 'Mox Pearl'),
(5, 5, 'Dual Land'),
(6, 6, 'Pikachu'),
(7, 7, 'Gengar'),
(8, 8, 'Blue-Eyes White Dragon');

-- --------------------------------------------------------

--
-- Table structure for table `card_condition`
--

CREATE TABLE `card_condition` (
  `id` int(11) NOT NULL,
  `physical_condition` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `card_condition`
--

INSERT INTO `card_condition` (`id`, `physical_condition`) VALUES
(1, 'Mint'),
(2, 'Near Mint'),
(3, 'Lightly Played'),
(4, 'Damaged');

-- --------------------------------------------------------

--
-- Table structure for table `log_ins`
--

CREATE TABLE `log_ins` (
  `profile_id` int(11) NOT NULL,
  `log_in_time` datetime NOT NULL DEFAULT current_timestamp(),
  `log_out_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `log_ins`
--

INSERT INTO `log_ins` (`profile_id`, `log_in_time`, `log_out_time`) VALUES
(1, '2026-04-21 12:33:24', NULL),
(2, '2026-04-21 10:00:00', '2026-04-21 11:30:00');

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

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `account_id`, `name`, `privilege`, `authorization_hash`) VALUES
(1, 1, 'AdminUser', 1, 'AuthHashAlpha123'),
(2, 2, 'CasualCollector', 0, NULL),
(3, 3, 'Alpha_Mod', 1, 'auth_001'),
(4, 3, 'Alpha_User', 0, NULL),
(5, 4, 'Beta_Mod', 1, 'auth_002'),
(6, 4, 'Beta_User', 0, NULL),
(7, 5, 'Gamma_User', 0, NULL),
(8, 5, 'Gamma_Guest', 0, NULL),
(9, 6, 'Delta_Admin', 1, 'auth_003'),
(10, 6, 'Delta_User', 0, NULL),
(11, 7, 'Epsilon_User', 0, NULL),
(12, 3, 'Alpha_Alt', 0, NULL),
(13, 4, 'Beta_Alt', 0, NULL),
(14, 5, 'Gamma_Alt', 0, NULL),
(15, 6, 'Delta_Alt', 0, NULL),
(16, 7, 'Epsilon_Alt', 0, NULL),
(17, 3, 'Alpha_Tester', 0, NULL),
(18, 4, 'Beta_Tester', 0, NULL),
(19, 5, 'Gamma_Tester', 1, 'auth_004'),
(20, 6, 'Delta_Tester', 1, 'auth_005'),
(21, 7, 'Epsilon_Mod', 1, 'auth_006'),
(22, 7, 'Epsilon_Guest', 0, NULL);

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

--
-- Dumping data for table `sets`
--

INSERT INTO `sets` (`id`, `tcg_id`, `name`, `maker_designated_id`, `release_date`) VALUES
(1, 1, 'Alpha', 'LEA', '2026-04-21 13:45:48'),
(2, 2, 'Base Set', 'BS-01', '2026-04-21 13:45:48'),
(3, 1, 'Modern Horizons 3', 'MH3', '2026-04-21 13:45:48'),
(4, 1, 'Unlimited', '2ED', '2026-04-21 13:45:48'),
(5, 1, 'Revised', '3ED', '2026-04-21 13:45:48'),
(6, 2, 'Jungle', 'BS-02', '2026-04-21 13:45:48'),
(7, 2, 'Fossil', 'BS-03', '2026-04-21 13:45:48'),
(8, 3, 'Legend of Blue Eyes', 'LOB', '2026-04-21 13:45:48');

-- --------------------------------------------------------

--
-- Table structure for table `trading_card_games`
--

CREATE TABLE `trading_card_games` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trading_card_games`
--

INSERT INTO `trading_card_games` (`id`, `name`) VALUES
(1, 'Magic: The Gathering'),
(2, 'Pokémon TCG'),
(3, 'Yu-Gi-Oh!');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `card_blueprints`
--
ALTER TABLE `card_blueprints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `card_condition`
--
ALTER TABLE `card_condition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `sets`
--
ALTER TABLE `sets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `trading_card_games`
--
ALTER TABLE `trading_card_games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
