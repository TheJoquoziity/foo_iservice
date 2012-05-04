-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 03, 2012 at 03:24 PM
-- Server version: 5.5.9
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `foo_iservice`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_type`
--

CREATE TABLE `account_type` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `detail` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `account_type`
--

INSERT INTO `account_type` VALUES(1, 'Employee');
INSERT INTO `account_type` VALUES(2, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `checkin`
--

CREATE TABLE `checkin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `location` varchar(30) NOT NULL,
  `by_device_id` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `checkin`
--


-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `group_id` int(10) NOT NULL,
  `content` varchar(120) NOT NULL,
  `remark` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `comment`
--


-- --------------------------------------------------------

--
-- Table structure for table `comment_group`
--

CREATE TABLE `comment_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `remark` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `comment_group`
--


-- --------------------------------------------------------

--
-- Table structure for table `dealer`
--

CREATE TABLE `dealer` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `dealer`
--


-- --------------------------------------------------------

--
-- Table structure for table `dealer_branch`
--

CREATE TABLE `dealer_branch` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `dealer_id` int(4) NOT NULL,
  `name` varchar(30) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `location` varchar(30) NOT NULL,
  `map` longblob,
  `lat_lon` varchar(15) DEFAULT NULL,
  `status` int(1) NOT NULL,
  `remark` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `dealer_branch`
--


-- --------------------------------------------------------

--
-- Table structure for table `dealer_status`
--

CREATE TABLE `dealer_status` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `detail` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `dealer_status`
--

INSERT INTO `dealer_status` VALUES(1, 'Supplier');
INSERT INTO `dealer_status` VALUES(2, 'Customer');
INSERT INTO `dealer_status` VALUES(3, 'Supplier & Customer');

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE `device` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `mac_addr` varchar(20) NOT NULL,
  `os_ver` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `device`
--

INSERT INTO `device` VALUES(1, '12:12:12:12:12:12', 'ios5.1');
INSERT INTO `device` VALUES(2, '13:13:13:13:13:13', 'ios4.2');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `position_id` int(2) NOT NULL,
  `tel` int(10) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `is_active` int(1) NOT NULL,
  `device_id` int(4) DEFAULT NULL,
  `account_type` int(1) NOT NULL,
  `security_code` varchar(8) NOT NULL DEFAULT '00000000',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` VALUES(1, 'test', '1234', 'mana', 'jaidee', 4, 800001234, 'test@fireoneone.com', 1, 1, 2, '00000000');
INSERT INTO `employee` VALUES(2, 'test2', '1234', 'thana', 'tongdee', 4, 800001235, 'test2@fireoneone.com', 1, 2, 1, '00000000');

-- --------------------------------------------------------

--
-- Table structure for table `employee_position`
--

CREATE TABLE `employee_position` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `detail` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `employee_position`
--

INSERT INTO `employee_position` VALUES(1, 'CEO');
INSERT INTO `employee_position` VALUES(2, 'Technical');
INSERT INTO `employee_position` VALUES(3, 'Developer');
INSERT INTO `employee_position` VALUES(4, 'IT');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `brand` varchar(15) NOT NULL,
  `model` varchar(20) DEFAULT NULL,
  `barcode` varchar(20) NOT NULL,
  `pic` longblob,
  `bought_from` int(4) NOT NULL,
  `status` int(1) NOT NULL,
  `setup_at` int(4) NOT NULL,
  `remark` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `item`
--


-- --------------------------------------------------------

--
-- Table structure for table `item_status`
--

CREATE TABLE `item_status` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `detail` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `item_status`
--

INSERT INTO `item_status` VALUES(1, 'Bought');
INSERT INTO `item_status` VALUES(2, 'Rent');

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `detail` varchar(120) NOT NULL,
  `how_to_fix` varchar(120) DEFAULT NULL,
  `problem_inform` varchar(120) DEFAULT NULL,
  `problem_actual` varchar(120) DEFAULT NULL,
  `checkin_start` datetime DEFAULT NULL,
  `checkin_finish` datetime DEFAULT NULL,
  `pic` longblob,
  `comment_group_id` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `job`
--


-- --------------------------------------------------------

--
-- Table structure for table `job_holder`
--

CREATE TABLE `job_holder` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `job_id` int(10) NOT NULL,
  `employee_id` int(4) NOT NULL,
  `remark` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `job_holder`
--

