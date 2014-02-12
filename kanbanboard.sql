-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Generation Time: Feb 12, 2014 at 01:36 PM
-- Server version: 5.1.72-2
-- PHP Version: 5.3.3-7+squeeze15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kanban`
--
CREATE DATABASE `kanban` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `kanban`;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_blog`
--

DROP TABLE IF EXISTS `kanban_blog`;
CREATE TABLE IF NOT EXISTS `kanban_blog` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `article_text` text NOT NULL,
  `submitted_by` varchar(30) NOT NULL,
  PRIMARY KEY (`article_id`),
  UNIQUE KEY `article_id` (`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_blog_feedback`
--

DROP TABLE IF EXISTS `kanban_blog_feedback`;
CREATE TABLE IF NOT EXISTS `kanban_blog_feedback` (
  `article_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `who` varchar(30) NOT NULL,
  `feedback` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_group`
--

DROP TABLE IF EXISTS `kanban_group`;
CREATE TABLE IF NOT EXISTS `kanban_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `project_id` int(11) NOT NULL,
  `displayorder` int(11) NOT NULL DEFAULT '0',
  `final` int(11) NOT NULL DEFAULT '0',
  `wip` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=514 ;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_history`
--

DROP TABLE IF EXISTS `kanban_history`;
CREATE TABLE IF NOT EXISTS `kanban_history` (
  `project_id` int(11) NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `who` varchar(50) NOT NULL,
  `change` text NOT NULL,
  KEY `project_id` (`project_id`,`modified`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_item`
--

DROP TABLE IF EXISTS `kanban_item`;
CREATE TABLE IF NOT EXISTS `kanban_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `heading` varchar(200) NOT NULL,
  `group_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `colortag` int(11) NOT NULL DEFAULT '1',
  `description` text NOT NULL,
  `estimation` int(11) NOT NULL DEFAULT '0',
  `project_id` int(11) NOT NULL,
  `startdate` date NOT NULL DEFAULT '2010-01-01',
  `enddate` date NOT NULL,
  `added` date NOT NULL,
  `sprint_id` int(11) NOT NULL,
  `workpackage_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1954 ;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_item_comment`
--

DROP TABLE IF EXISTS `kanban_item_comment`;
CREATE TABLE IF NOT EXISTS `kanban_item_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `who` varchar(30) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=58 ;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_progress`
--

DROP TABLE IF EXISTS `kanban_progress`;
CREATE TABLE IF NOT EXISTS `kanban_progress` (
  `item_id` int(11) NOT NULL,
  `date_of_progress` date NOT NULL,
  `new_estimate` int(11) NOT NULL,
  UNIQUE KEY `item_id` (`item_id`,`date_of_progress`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_project`
--

DROP TABLE IF EXISTS `kanban_project`;
CREATE TABLE IF NOT EXISTS `kanban_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `startdate` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=121 ;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_project_properties`
--

DROP TABLE IF EXISTS `kanban_project_properties`;
CREATE TABLE IF NOT EXISTS `kanban_project_properties` (
  `project_id` int(11) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` varchar(500) NOT NULL,
  UNIQUE KEY `project_id` (`project_id`,`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_resource`
--

DROP TABLE IF EXISTS `kanban_resource`;
CREATE TABLE IF NOT EXISTS `kanban_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_resource_schedule`
--

DROP TABLE IF EXISTS `kanban_resource_schedule`;
CREATE TABLE IF NOT EXISTS `kanban_resource_schedule` (
  `resource_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `effort` int(11) NOT NULL,
  UNIQUE KEY `resource_id` (`resource_id`,`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_sprint`
--

DROP TABLE IF EXISTS `kanban_sprint`;
CREATE TABLE IF NOT EXISTS `kanban_sprint` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT 'no-name',
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=155 ;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_ticker`
--

DROP TABLE IF EXISTS `kanban_ticker`;
CREATE TABLE IF NOT EXISTS `kanban_ticker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `enddate` date NOT NULL,
  `message` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_time_reporting`
--

DROP TABLE IF EXISTS `kanban_time_reporting`;
CREATE TABLE IF NOT EXISTS `kanban_time_reporting` (
  `item_id` int(11) NOT NULL,
  `reporting_date` date NOT NULL,
  `hours` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_timeline`
--

DROP TABLE IF EXISTS `kanban_timeline`;
CREATE TABLE IF NOT EXISTS `kanban_timeline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `headline` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_user`
--

DROP TABLE IF EXISTS `kanban_user`;
CREATE TABLE IF NOT EXISTS `kanban_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Table structure for table `kanban_workpackage`
--

DROP TABLE IF EXISTS `kanban_workpackage`;
CREATE TABLE IF NOT EXISTS `kanban_workpackage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=91 ;

-- --------------------------------------------------------


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
