-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jun 10, 2017 at 08:00 AM
-- Server version: 5.5.54-38.6-log
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `faveohel_agora`
--

-- --------------------------------------------------------

--
-- Table structure for table `tax_by_states`
--

CREATE TABLE IF NOT EXISTS `tax_by_states` (
  `id` smallint(5) unsigned NOT NULL,
  `state` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `c_gst` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `s_gst` varchar(80) CHARACTER SET utf8 DEFAULT NULL,
  `i_gst` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `ut_gst` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `tax_by_states` (`id`, `state`, `c_gst`, `s_gst` ,`i_gst` , `ut_gst`,`created_at`, `updated_at`) VALUES
(1, 'Andaman Nicobar Islands', '9', 'NULL', '18','9','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(2, 'Andhra Pradesh', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(3, 'Arunachal Pradesh', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(4, 'Assam', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(5, 'Bihar', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(6, 'Chandigarh', '9', 'NULL', '18','9','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(7, 'Chhattisgarh', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(8, 'Dadra and Nagar Haveli', '9', 'NULL', '18','9','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(9, 'Daman Diu', '9', 'NULL', '18','9','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(10, 'Delhi', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(11, 'Goa', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(12, 'Gujarat', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(13, 'Haryana', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(14, 'Himachal pradesh', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(15, 'Jammu and Kashmir', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(16, 'Jharkand', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(17, 'Karnataka', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(18, 'KeralA', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(19, 'Lakshadweep', '9', 'NULL', '18','9','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(20, 'Madhya Pradesh', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(21, 'Maharashtra', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(22, 'Manipur', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(23, 'Megalaya', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(24, 'Mizoram', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(25, 'Nagaland', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(26, 'Orissa', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(27, 'Orissa', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(28, 'Pondichery', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(29, 'Punjab', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(30, 'Rajastan', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(31, 'Sikkim', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(32, 'Tamilnadu', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(33, 'Telangana', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(34, 'Tiripura', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(35, 'Uttar Pradesh', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(36, 'Uttaranchal', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58'),
(37, 'West Bengal', '9', '9', '18','NULL','2018-04-13 12:53:58','2018-04-13 12:53:58');









