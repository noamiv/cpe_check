-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 20, 2016 at 03:24 PM
-- Server version: 5.5.44-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dd_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `bs`
--

CREATE TABLE IF NOT EXISTS `bs` (
  `objid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `ip` varchar(16) NOT NULL COMMENT 'BS IP address',
  `location_lat` varchar(32) DEFAULT NULL,
  `location_long` varchar(32) DEFAULT NULL,
  `ant_direction` int(11) NOT NULL,
  UNIQUE KEY `objid` (`objid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `bs`
--

INSERT INTO `bs` (`objid`, `name`, `ip`, `location_lat`, `location_long`, `ant_direction`) VALUES
(1, 'North runway', '192.168.1.5', '37.63778621307065', '-122.397342643414', 171),
(3, 'South Runway', '168.1.1.2', '37.6139597127346 ', '-122.356431158794', 270),
(4, 'Center', '168.1.1.3', '37.6047551619208', '-122.381555232574', 0),
(6, 'N3', '192.168.1.7', '37.6047551619208', '-122.381555232574', 150);

-- --------------------------------------------------------

--
-- Table structure for table `cpe`
--

CREATE TABLE IF NOT EXISTS `cpe` (
  `objid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `location_lat` varchar(32) NOT NULL,
  `location_long` varchar(32) NOT NULL,
  `ant_direction` int(11) NOT NULL,
  `dl_cinr` int(11) NOT NULL DEFAULT '0',
  `ul_cinr` int(11) NOT NULL DEFAULT '0',
  `dl_rssi` int(11) NOT NULL DEFAULT '0',
  `ul_rssi` int(11) NOT NULL DEFAULT '0',
  `uptime` int(11) NOT NULL,
  `cpe2bs` int(11) DEFAULT NULL,
  `cpe2status` int(11) NOT NULL,
  PRIMARY KEY (`objid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `cpe`
--

INSERT INTO `cpe` (`objid`, `name`, `ip`, `location_lat`, `location_long`, `ant_direction`, `dl_cinr`, `ul_cinr`, `dl_rssi`, `ul_rssi`, `uptime`, `cpe2bs`, `cpe2status`) VALUES
(1, 'cpe_1', '', '', '', 0, 27, 25, -55, -55, 12677, 3, 0),
(2, 'cpe_2', '', '', '', 0, 26, 23, -62, -67, 4230, 4, 0),
(3, 'cpe_3', '', '', '', 0, 27, 25, -58, -59, 345, 3, 0),
(4, 'cpe_4', '', '', '', 0, 18, 16, -72, -78, 120, 6, 0),
(5, 'cpe_5', '', '', '', 0, 22, 22, -68, -72, 1000, 1, 0),
(6, 'Noam temp', '192.178.1.1', '37.62551685835676', '-122.38687992095947', 0, 0, 0, 0, 0, 0, 1, 1),
(7, 'New SS', '192.1.1.3', '37.63408177377812', '-122.39469051361083', 6, 0, 0, 0, 0, 0, 1, 2),
(8, 'another', '192.1.4.6', '37.62334148457268', '-122.39348888397217', 50, 0, 0, 0, 0, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `device_status`
--

CREATE TABLE IF NOT EXISTS `device_status` (
  `objid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `desc` varchar(1024) NOT NULL,
  PRIMARY KEY (`objid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `device_status`
--

INSERT INTO `device_status` (`objid`, `name`, `desc`) VALUES
(1, 'operatinal', 'device is connected'),
(2, 'disconnected', 'device is unreachable ');

-- --------------------------------------------------------

--
-- Table structure for table `event_desc`
--

CREATE TABLE IF NOT EXISTS `event_desc` (
  `objid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `desc` varchar(1024) NOT NULL,
  PRIMARY KEY (`objid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `event_desc`
--

INSERT INTO `event_desc` (`objid`, `desc`) VALUES
(1, 'SS is inaccessible '),
(2, 'BS is inaccessible'),
(3, 'SS accessible again'),
(4, 'BS accessible again');

-- --------------------------------------------------------

--
-- Table structure for table `event_notifier`
--

CREATE TABLE IF NOT EXISTS `event_notifier` (
  `objid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL,
  `event2desc` int(11) NOT NULL,
  `event2ss` int(11) NOT NULL,
  `event2bs` int(11) NOT NULL,
  PRIMARY KEY (`objid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `event_notifier`
--

INSERT INTO `event_notifier` (`objid`, `time`, `event2desc`, `event2ss`, `event2bs`) VALUES
(1, '2016-07-18 10:45:11', 1, 6, 0),
(2, '0000-00-00 00:00:00', 3, 6, 0),
(3, '2016-07-20 10:45:11', 1, 6, 0),
(4, '2016-07-20 00:00:00', 3, 6, 0),
(5, '2016-07-20 10:45:11', 1, 6, 0),
(7, '2016-07-20 00:00:00', 2, 0, 1),
(8, '2016-07-20 00:00:00', 2, 0, 1),
(9, '2016-07-27 00:00:00', 2, 0, 1),
(10, '2016-07-27 00:00:00', 2, 0, 1),
(11, '2016-07-27 00:00:00', 2, 0, 1),
(12, '2016-07-27 00:00:00', 2, 0, 1),
(13, '2016-07-27 00:00:00', 2, 0, 1),
(14, '2016-07-27 00:00:00', 2, 0, 1),
(15, '2016-07-27 00:00:00', 2, 0, 1),
(16, '2016-07-27 00:00:00', 2, 0, 1),
(17, '2016-07-27 00:00:00', 2, 0, 1),
(18, '2016-07-27 00:00:00', 2, 0, 1),
(19, '2016-07-27 00:00:00', 2, 0, 1),
(20, '2016-07-27 00:00:00', 2, 0, 1),
(21, '2016-07-20 10:45:11', 1, 6, 0),
(22, '2016-07-20 10:45:11', 1, 8, 0),
(23, '2016-07-20 10:45:11', 1, 8, 0),
(24, '2016-07-27 00:00:00', 2, 0, 1),
(25, '2016-07-27 00:00:00', 2, 0, 1),
(26, '2016-07-27 00:00:00', 2, 0, 1),
(27, '2016-07-27 00:00:00', 2, 0, 1),
(28, '2016-07-27 00:00:00', 2, 0, 1),
(29, '2016-07-27 00:00:00', 2, 0, 1),
(30, '2016-07-27 00:00:00', 2, 0, 1),
(31, '2016-07-27 00:00:00', 2, 0, 1),
(32, '2016-07-27 00:00:00', 2, 0, 1),
(33, '2016-07-27 00:00:00', 2, 0, 1),
(34, '2016-07-27 00:00:00', 4, 0, 1),
(35, '2016-07-27 00:00:00', 3, 7, 0);

-- --------------------------------------------------------

--
-- Table structure for table `meta_config`
--

CREATE TABLE IF NOT EXISTS `meta_config` (
  `objid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `p_order` int(11) NOT NULL,
  `p_name` varchar(255) NOT NULL,
  `p_desc` varchar(1024) NOT NULL,
  `p_value` varchar(16) NOT NULL,
  PRIMARY KEY (`objid`),
  UNIQUE KEY `objid_2` (`objid`),
  KEY `objid` (`objid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `meta_config`
--

INSERT INTO `meta_config` (`objid`, `p_order`, `p_name`, `p_desc`, `p_value`) VALUES
(1, 1, 'map_refresh_interval', 'java script map refresh interval in mili seconds ', '5000'),
(2, 2, 'SNMPv2 Read Community', 'name of the read community ', 'public55'),
(3, 2, 'SNMPv2 Write Community', 'name of the write community ', 'public4');

-- --------------------------------------------------------

--
-- Table structure for table `meta_groups`
--

CREATE TABLE IF NOT EXISTS `meta_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(225) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `meta_groups`
--

INSERT INTO `meta_groups` (`group_id`, `group_name`) VALUES
(1, 'Standard User'),
(2, 'Admin User');

-- --------------------------------------------------------

--
-- Table structure for table `meta_sessions`
--

CREATE TABLE IF NOT EXISTS `meta_sessions` (
  `session_start` int(11) NOT NULL,
  `session_data` text NOT NULL,
  `session_id` varchar(255) NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `meta_users`
--

CREATE TABLE IF NOT EXISTS `meta_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) NOT NULL,
  `username_clean` varchar(150) NOT NULL,
  `password` varchar(225) NOT NULL,
  `email` varchar(150) NOT NULL,
  `activationtoken` varchar(225) NOT NULL,
  `last_activation_request` int(11) NOT NULL,
  `LostpasswordRequest` int(1) NOT NULL DEFAULT '0',
  `active` int(1) NOT NULL,
  `group_id` int(11) NOT NULL,
  `sign_up_date` int(11) NOT NULL,
  `last_sign_in` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `meta_users`
--

INSERT INTO `meta_users` (`user_id`, `username`, `username_clean`, `password`, `email`, `activationtoken`, `last_activation_request`, `LostpasswordRequest`, `active`, `group_id`, `sign_up_date`, `last_sign_in`) VALUES
(1, 'noami@wasrcm.com', 'noami@wasrcm.com', 'eb6d1350c27ee0c4e207ed989654eb46329f813080030ac29f8ccbc138dc4e48f', 'noami@wasrcm.com', '217c43286a55193cf5673064739255d6', 1451795899, 0, 1, 1, 1451795899, 1456797682),
(2, 'talivri', 'talivri', 'c2196642d527e0fdea448ed87ae21fe34ddaadb737daf7fb0e775e052e17b86f8', 'sdfk@k.com', 'cc38fb2c6325297b8205190a5fdcdb83', 1452053618, 0, 1, 1, 1452053618, 0),
(3, 'noami', 'noami', '8247b3ab601dcedd8b40d8f31f594e6996f81361f1001fb43361c94f6d1ecf15c', 'noam@local.com', 'dccf48f560f4fec74cb6d1824b880dad', 1456798700, 0, 1, 1, 1456798700, 0),
(4, 'noami1', 'noami1', '8d19819372f9aeabae95b56fd4dfffa03280b0736f92d698056dfca4864fda7b2', 'noam11@local.com', '361d357a6256a8de6b5522ff94f66b65', 1456799064, 0, 1, 1, 1456799064, 0),
(5, 'ivri1', 'ivri1', 'ae79b08388eb99c2aaf1db0edc8d305a152c0bbd140677b80e08be0fd49c83fdd', 'ivri@local.com', 'a8bba178b66682efd2cc31dd6982a503', 1456801187, 0, 1, 1, 1456801187, 1468016368),
(6, 'nivri', 'nivri', '9041bce258be015bb0677d35c8557f0d208ce27af3a539c6c480f6349e0b5acc6', 'n@n.com', '690d9163f5602e5c217b1724e6b61a17', 1468518283, 0, 1, 1, 1468518283, 1469050675),
(7, 'admin', 'admin', 'c4a212924d1b131e1b1bc44cd48e077ffa807027708b045eb0cdcf7bbb8f86582', 'admin@admin.com', 'a754a3a5481c69d6669f233f3ab6080b', 1469048455, 0, 1, 2, 1469048455, 1469051029);

-- --------------------------------------------------------

--
-- Table structure for table `neigh_bs_m2m`
--

CREATE TABLE IF NOT EXISTS `neigh_bs_m2m` (
  `bs_objid` int(11) NOT NULL,
  `neigh_bs` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `neigh_bs_m2m`
--

INSERT INTO `neigh_bs_m2m` (`bs_objid`, `neigh_bs`) VALUES
(1, 3),
(1, 4),
(3, 1),
(4, 1),
(4, 6),
(6, 4);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE IF NOT EXISTS `notes` (
  `note_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `note_text` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`note_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user notes' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
