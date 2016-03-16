-- MySQL dump 10.13  Distrib 5.6.28, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: basis_bsquared
-- ------------------------------------------------------
-- Server version	5.6.28-0ubuntu0.15.10.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


CREATE DATABASE basis_bsquared;
USE basis_bsquared;
--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(48) DEFAULT NULL,
  `email` varchar(320) DEFAULT NULL,
  `password` char(128) DEFAULT NULL,
  `salt` char(128) DEFAULT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


INSERT INTO `admin` (`userId`, `username`, `email`, `password`, `salt`) VALUES
  (2, 'administrator', 'youradmin@example.com', '9503c70fd14a17cb29276f815d675ee43b468ac21fa7f8fbcc1af7e2d4d17de4a6d3f6732552770b90036f3c60fb324796849437a105a4076fccb67d309c8ce8', 'b8863ab78028b55a3275a255a4c1ae13029a867bec90a2d1aea5ad00fa4735eae88aff4694f69718aacfd2367d3015880b640151acadb9e398915a5142458fc5');

--
-- Table structure for table `file_path_lookup`
--

DROP TABLE IF EXISTS `file_path_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `file_path_lookup` (
  `destination_id` int(11) NOT NULL AUTO_INCREMENT,
  `destination` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`destination_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_attempts` (
  `userId` int(11) NOT NULL,
  `time` varchar(30) NOT NULL,
  PRIMARY KEY (`userId`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `login_attempts_admin`
--

DROP TABLE IF EXISTS `login_attempts_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_attempts_admin` (
  `userId` int(11) NOT NULL,
  `time` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary view structure for view `opening_splash`
--

DROP TABLE IF EXISTS `opening_splash`;
/*!50001 DROP VIEW IF EXISTS `opening_splash`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `opening_splash` AS SELECT 
 1 AS `userID`,
 1 AS `firstName`,
 1 AS `lastName`,
 1 AS `aboutMe`,
 1 AS `path`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `portfolio_columns`
--

DROP TABLE IF EXISTS `portfolio_columns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portfolio_columns` (
  `column_id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `column_text` text,
  `destination_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`column_id`,`userID`),
  KEY `userID` (`userID`),
  KEY `destination_id` (`destination_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `portfolio_labels`
--

DROP TABLE IF EXISTS `portfolio_labels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portfolio_labels` (
  `label_id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `label` varchar(48) DEFAULT NULL,
  `destination_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`label_id`,`userID`),
  KEY `userID` (`userID`),
  KEY `destination_id` (`destination_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `portfolio_members`
--

DROP TABLE IF EXISTS `portfolio_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portfolio_members` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(48) NOT NULL,
  `email` varchar(320) NOT NULL,
  `password` char(128) NOT NULL,
  `salt` char(128) NOT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `portfolio_paths`
--

DROP TABLE IF EXISTS `portfolio_paths`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portfolio_paths` (
  `path_id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `path` varchar(256) DEFAULT NULL,
  `destination_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`path_id`,`userID`),
  KEY `userID` (`userID`),
  KEY `destination_id` (`destination_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `portfolio_profiles`
--

DROP TABLE IF EXISTS `portfolio_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portfolio_profiles` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(48) DEFAULT NULL,
  `lastName` varchar(48) DEFAULT NULL,
  `aboutMe` text,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `portfolio_works`
--

DROP TABLE IF EXISTS `portfolio_works`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portfolio_works` (
  `userID` int(11) NOT NULL DEFAULT '0',
  `worksID` int(1) NOT NULL DEFAULT '0',
  `title` varchar(48) DEFAULT NULL,
  `projectDescription` text,
  `work_link` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`userID`,`worksID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `portfolios_about`
--

DROP TABLE IF EXISTS `portfolios_about`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portfolios_about` (
  `userID` int(11) NOT NULL,
  `overview` text,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `portfolios_statement`
--

DROP TABLE IF EXISTS `portfolios_statement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `portfolios_statement` (
  `userID` int(11) NOT NULL,
  `statement` text,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `portfolios_about`
--
ALTER TABLE `portfolios_about`
ADD CONSTRAINT `portfolios_about_portfolio_members_userId_fk` FOREIGN KEY (`userID`) REFERENCES `portfolio_members` (`userId`);

--
-- Constraints for table `portfolios_statement`
--
ALTER TABLE `portfolios_statement`
ADD CONSTRAINT `portfolios_statement_portfolio_members_userId_fk` FOREIGN KEY (`userID`) REFERENCES `portfolio_members` (`userId`);

--
-- Constraints for table `portfolio_columns`
--
ALTER TABLE `portfolio_columns`
ADD CONSTRAINT `portfolio_columns_ibfk_2` FOREIGN KEY (`destination_id`) REFERENCES `file_path_lookup` (`destination_id`),
ADD CONSTRAINT `portfolio_columns_portfolio_members_userId_fk` FOREIGN KEY (`userID`) REFERENCES `portfolio_members` (`userId`);

--
-- Constraints for table `portfolio_labels`
--
ALTER TABLE `portfolio_labels`
ADD CONSTRAINT `portfolio_labels_ibfk_2` FOREIGN KEY (`destination_id`) REFERENCES `file_path_lookup` (`destination_id`),
ADD CONSTRAINT `portfolio_labels_portfolio_members_userId_fk` FOREIGN KEY (`userID`) REFERENCES `portfolio_members` (`userId`);

--
-- Constraints for table `portfolio_paths`
--
ALTER TABLE `portfolio_paths`
ADD CONSTRAINT `portfolio_paths_ibfk_2` FOREIGN KEY (`destination_id`) REFERENCES `file_path_lookup` (`destination_id`),
ADD CONSTRAINT `portfolio_paths_portfolio_members_userId_fk` FOREIGN KEY (`userID`) REFERENCES `portfolio_members` (`userId`);

--
-- Constraints for table `portfolio_profiles`
--
ALTER TABLE `portfolio_profiles`
ADD CONSTRAINT `portfolio_profiles_portfolio_members_userId_fk` FOREIGN KEY (`userID`) REFERENCES `portfolio_members` (`userId`);


--
-- Final view structure for view `opening_splash`
--

/*!50001 DROP VIEW IF EXISTS `opening_splash`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `opening_splash` AS select `portfolio_profiles`.`userID` AS `userID`,`portfolio_profiles`.`firstName` AS `firstName`,`portfolio_profiles`.`lastName` AS `lastName`,`portfolio_profiles`.`aboutMe` AS `aboutMe`,`portfolio_paths`.`path` AS `path` from (`portfolio_profiles` join `portfolio_paths` on((`portfolio_profiles`.`userID` = `portfolio_paths`.`userID`))) where (`portfolio_paths`.`destination_id` = 36) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-14 22:51:28
