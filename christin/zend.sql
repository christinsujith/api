-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 09, 2012 at 08:30 AM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zend`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
  `blogid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(500) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`blogid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`blogid`, `title`, `description`, `image`, `created`, `updated`) VALUES
(5, 'No third person in race for Uttar Pradesh chief minister, Samajwadi Party leader Ramgopal Yadav says', 'ETAWAH: Suspense continued on the issue of chief ministership in Uttar Pradesh with a Samajwadi Party leader today saying that either Mulayam Singh Yadav or his son Akhilesh would head the government.\r\n\r\n', 'uploads/files/WeddingJewellery_20248.jpg', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'No third person in race for Uttar Pradesh chief minister, Samajwadi Party leader Ramgopal Yadav says', 'ETAWAH: Suspense continued on the issue of chief ministership in Uttar Pradesh with a Samajwadi Party leader today saying that either Mulayam Singh Yadav or his son Akhilesh would head the government.\r\n', 'uploads/files/WeddingJewellery_20248.jpg', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'Mining mafia: IPS officer crushed to death in Madhya Pradesh', 'BHOPAL: A young Indian Police Service officer was crushed to death in the Morena district of Madhya Pradesh, police said on Thursday.', 'uploads/files/Tanishq-Diamond-Jewellery.jpg', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'prabhakar', 'testing blog section', 'uploads/files/images.jpg', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'prabhakar', 'dsfasdfasdfsd', 'uploads/files/WeddingJewellery_20248.jpg', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `typeid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(10) NOT NULL,
  `password` varchar(100) NOT NULL,
  `lastlogin` datetime NOT NULL,
  `enabled` bit(1) NOT NULL DEFAULT b'1',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`userid`),
  KEY `typeid` (`typeid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `typeid`, `name`, `username`, `password`, `lastlogin`, `enabled`, `created`, `updated`) VALUES
(1, 3, 'Administra', 'prabhakar', 'testing', '2012-02-03 14:41:52', '1', '2012-02-03 14:41:52', '2012-02-07 15:19:03');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
