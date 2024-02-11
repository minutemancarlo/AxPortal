-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 11, 2024 at 12:00 PM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `axportal`
--

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `category_name`) VALUES
(1, 'Building Maintenance'),
(2, 'Electrical System'),
(3, 'Water System'),
(4, 'Faculty/Staff Housing'),
(5, 'Farm Shop Maintenance'),
(6, 'Ground Maintenance'),
(7, 'RAC/Other Equipments (Sound System)'),
(8, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_name`) VALUES
(1, 'Instructional Projects'),
(2, 'Research Projects'),
(3, 'Extension Projects'),
(4, 'Production Projects'),
(5, 'Other Projects');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

DROP TABLE IF EXISTS `requests`;
CREATE TABLE IF NOT EXISTS `requests` (
  `rid` varchar(255) NOT NULL,
  `job_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `jobdescription` varchar(255) DEFAULT NULL,
  `projectdescription` varchar(255) DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT '0' COMMENT '0 - End User, \r\n1 - Auxiliary, \r\n2 - Councilor, \r\n3 - Auxiliary,\r\n4 - Approved',
  `is_rejected` int(11) NOT NULL DEFAULT '0',
  `is_sent` int(11) NOT NULL DEFAULT '0',
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reject_description` varchar(255) DEFAULT NULL,
  `feedback` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`rid`),
  KEY `job_id` (`job_id`),
  KEY `project_id` (`project_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`rid`, `job_id`, `project_id`, `user_id`, `description`, `jobdescription`, `projectdescription`, `level`, `is_rejected`, `is_sent`, `created_on`, `reject_description`, `feedback`) VALUES
('202433423CN1Z00129', 2, 2, 4, 'test 3', '', '', 1, 1, 0, '2024-01-29 12:26:56', '', 'test'),
('2024A5C6CTMC300130', 4, 4, 4, 'test 4', '', '', 2, 1, 0, '2024-01-29 16:42:47', 'last reject', 'last feedback'),
('2024ABB35WRINS0207', 2, 1, 4, 'sample', '', '', 2, 0, 0, '2024-02-06 16:28:22', '', NULL),
('2024CC04FRONMH0130', 2, 3, 4, 'test 5', '', '', 2, 1, 0, '2024-01-29 16:55:09', 'test', 'test feedback'),
('2024D0401I267Y0129', 1, 1, 4, 'test 1', '', '', 0, 1, 0, '2024-01-29 12:16:51', NULL, 'last test'),
('2024F0351TZ8FK0129', 1, 1, 4, 'test 2', '', '', 3, 0, 0, '2024-01-29 12:23:43', '', 'test feedback for approved');

-- --------------------------------------------------------

--
-- Table structure for table `request_history`
--

DROP TABLE IF EXISTS `request_history`;
CREATE TABLE IF NOT EXISTS `request_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_id` varchar(255) NOT NULL,
  `approver_id` int(11) NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_sent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `request_id` (`request_id`),
  KEY `approver_id` (`approver_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request_history`
--

INSERT INTO `request_history` (`id`, `request_id`, `approver_id`, `updated_on`, `is_sent`) VALUES
(1, '2024F0351TZ8FK0129', 2, '2024-01-29 12:25:00', 0),
(2, '2024F0351TZ8FK0129', 3, '2024-01-29 12:25:53', 0),
(3, '2024F0351TZ8FK0129', 2, '2024-01-29 12:26:31', 0),
(4, '202433423CN1Z00129', 2, '2024-01-29 12:27:17', 0),
(5, '2024A5C6CTMC300130', 2, '2024-01-29 16:43:23', 0),
(6, '2024A5C6CTMC300130', 3, '2024-01-29 16:43:49', 0),
(7, '2024CC04FRONMH0130', 2, '2024-01-29 16:55:33', 0),
(8, '2024CC04FRONMH0130', 4, '2024-02-06 11:49:58', 0),
(9, '2024F0351TZ8FK0129', 4, '2024-02-06 15:19:24', 0),
(10, '2024ABB35WRINS0207', 2, '2024-02-06 16:45:24', 0),
(11, '2024ABB35WRINS0207', 3, '2024-02-06 16:46:09', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) DEFAULT '2' COMMENT '0 - Super User\r\n1 - Auxiliary\r\n2 - End User\r\n3 - Councilor',
  `is_verified` int(11) NOT NULL DEFAULT '0',
  `is_active` int(11) NOT NULL DEFAULT '0',
  `verification_token` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `name`, `password`, `role`, `is_verified`, `is_active`, `verification_token`) VALUES
(1, 'carloboado18@gmail.com', 'Superuser', '$2y$10$uqNwYna7Np5vTDpTD9jJVO4kiJ2KfEXffUL6p3xxrBr3Yi7jmdeQu', 0, 1, 1, 'WFEMFluAoZBszYwbnwcEIqVgDdqSDhT3'),
(2, 's4dev.main@gmail.com', 'Auxiliary', '$2y$10$Z49dsjwOKoxKDRzZG0oUg.DQhfk0PBZ6Olv.c3v18v.lYUgMR0YtW', 1, 1, 1, 'GeXPpyJKHL6QpoWUM5oK97EGrK5UsmsD'),
(3, 'developer.sentinelsystems@gmail.com', 'Councilor', '$2y$10$4nzVwMbgFFDTMkrAVvqjB.pjsP.UHnvVD719OWCCQBHEutl6jFjuS', 3, 1, 1, 'N6STSJC82BWRbuf2cUgVSOnFHeWWO2K2'),
(4, 'cboado@teligent.ph', 'End User', '$2y$10$Uz0BSP7KQ.Uzv2bW.eFUY.bxJBh1B7jPFIHfghc3pkfwNqyHGdZEO', 2, 1, 1, 'Q58TFZDfw4sHqA16L8HwDutXSefWjiRY'),
(5, 'joshuaborbon0828@gmail.com', 'Joshua', '$2y$10$22pMQSjok6zhFyDy1i6EheoPJieub3X012keWvOv1WZHyH89fHGdi', 2, 1, 1, '2xaGekUP9kcpZ2k1xkiBToUhEcsNfgPM'),
(6, 'leynarddcollado@gmail.com', 'Leynard Collado', '$2y$10$TIiwsxusDrCa0zRVeAZQ0uTIes6KdYTEfRnpkKs1xnxtg7I58YZBO', 2, 1, 1, 'fgGYzEMvSTflQPSCyzQZDbG2OenpbF9l'),
(7, 'jborbon@student.dmmmsu.edu.ph', 'Joshua Local Test', '$2y$10$f15t5V340XaOw1bDi5e7muAp5ntV9h6E3QjgCfBLGQ.l/F7Ebqk1K', NULL, 0, 0, 'cI2FiBSZsqyv1fjWPVmtwhXBLesVgHHE');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
