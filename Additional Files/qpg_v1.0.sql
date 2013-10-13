-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 13, 2013 at 06:40 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `moodle_qpg`
--

-- --------------------------------------------------------

--
-- Table structure for table `mdl_qpg_complexities`
--

DROP TABLE IF EXISTS `mdl_qpg_complexities`;
CREATE TABLE IF NOT EXISTS `mdl_qpg_complexities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `mdl_qpg_complexities`
--

INSERT INTO `mdl_qpg_complexities` (`id`, `name`) VALUES
(1, 'Easy'),
(2, 'Medium'),
(3, 'Hard');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_qpg_question_paper`
--

DROP TABLE IF EXISTS `mdl_qpg_question_paper`;
CREATE TABLE IF NOT EXISTS `mdl_qpg_question_paper` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT 'Currently Empty',
  `category_id` int(11) NOT NULL,
  `term` varchar(100) NOT NULL,
  `year` int(4) NOT NULL,
  `total_time` float NOT NULL,
  `course_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `complexity_easy` int(11) NOT NULL,
  `complexity_medium` int(11) NOT NULL,
  `complexity_hard` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mdl_qpg_question_paper`
--


-- --------------------------------------------------------

--
-- Table structure for table `mdl_qpg_question_paper_questions`
--

DROP TABLE IF EXISTS `mdl_qpg_question_paper_questions`;
CREATE TABLE IF NOT EXISTS `mdl_qpg_question_paper_questions` (
  `question_id` int(11) NOT NULL,
  `question_paper_rules_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mdl_qpg_question_paper_questions`
--


-- --------------------------------------------------------

--
-- Table structure for table `mdl_qpg_question_paper_rules`
--

DROP TABLE IF EXISTS `mdl_qpg_question_paper_rules`;
CREATE TABLE IF NOT EXISTS `mdl_qpg_question_paper_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_paper_id` int(11) NOT NULL,
  `question_type_id` tinyint(4) NOT NULL,
  `category_id` int(11) NOT NULL,
  `mark_per_question` int(11) NOT NULL,
  `no_of_question` int(11) NOT NULL,
  `total_marks` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `mdl_qpg_question_paper_rules`
--


-- --------------------------------------------------------

--
-- Table structure for table `mdl_qpg_question_type_master`
--

DROP TABLE IF EXISTS `mdl_qpg_question_type_master`;
CREATE TABLE IF NOT EXISTS `mdl_qpg_question_type_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `default_mark` tinyint(4) NOT NULL DEFAULT '1',
  `visible` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `mdl_qpg_question_type_master`
--

INSERT INTO `mdl_qpg_question_type_master` (`id`, `name`, `description`, `default_mark`, `visible`) VALUES
(1, 'VSA', '', 1, 1),
(2, 'SA-1', '', 2, 1),
(3, 'SA-2', '', 3, 1),
(4, 'LA', NULL, 5, 1),
(5, 'MCQ', NULL, 1, 1);
