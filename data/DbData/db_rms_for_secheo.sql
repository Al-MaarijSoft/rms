-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2013 at 09:05 PM
-- Server version: 5.6.11
-- PHP Version: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_rms_for_secheo`
--
CREATE DATABASE IF NOT EXISTS `db_rms_for_secheo` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `db_rms_for_secheo`;

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_type_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `class` smallint(6) NOT NULL,
  `category` smallint(6) NOT NULL,
  `level` smallint(6) NOT NULL,
  `serial` smallint(6) NOT NULL DEFAULT '0',
  `status` smallint(6) NOT NULL,
  `description` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modification_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_CAC89EAC5E237E06` (`name`),
  UNIQUE KEY `name_company_index` (`name`,`company_id`),
  KEY `IDX_CAC89EACC6798DB` (`account_type_id`),
  KEY `IDX_CAC89EACDCD6CC49` (`branch_id`),
  KEY `IDX_CAC89EAC979B1AD6` (`company_id`),
  KEY `IDX_CAC89EAC727ACA70` (`parent_id`),
  KEY `IDX_CAC89EACDE12AB56` (`created_by`),
  KEY `IDX_CAC89EAC25F94802` (`modified_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `account_types`
--

CREATE TABLE IF NOT EXISTS `account_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `is_default` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_6FBF50415E237E06` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `bio_infos`
--

CREATE TABLE IF NOT EXISTS `bio_infos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city_id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `zip_code` smallint(6) DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cell` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone1` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `phone2` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F3F6F5428BAC62AF` (`city_id`),
  KEY `IDX_F3F6F542979B1AD6` (`company_id`),
  KEY `IDX_F3F6F542A76ED395` (`user_id`),
  KEY `IDX_F3F6F542DCD6CC49` (`branch_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE IF NOT EXISTS `branches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `branch_type` smallint(6) NOT NULL DEFAULT '3',
  `status` smallint(6) NOT NULL DEFAULT '1',
  `description` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_company_index` (`name`,`company_id`),
  KEY `IDX_D760D16F979B1AD6` (`company_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D95DB16B5E237E06` (`name`),
  KEY `IDX_D95DB16B5D83CC1` (`state_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '1',
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `modification_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8244AA3A5E237E06` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5D66EBAD5E237E06` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE IF NOT EXISTS `currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `is_local` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_37C446935E237E06` (`name`),
  UNIQUE KEY `UNIQ_37C4469377153098` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `financial_years`
--

CREATE TABLE IF NOT EXISTS `financial_years` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D26902585E237E06` (`name`),
  UNIQUE KEY `name_company_index` (`name`,`company_id`),
  KEY `IDX_D2690258979B1AD6` (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plot_id` int(11) NOT NULL,
  `old_member_id` int(11) NOT NULL,
  `code` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `image_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `nic` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `fname` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sname` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT '1',
  `is_private` smallint(6) NOT NULL DEFAULT '1',
  `creation_date` datetime NOT NULL,
  `modification_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_45A0D2FF77153098` (`code`),
  UNIQUE KEY `UNIQ_45A0D2FFAC199498` (`image_name`),
  UNIQUE KEY `UNIQ_45A0D2FFDD8CDF34` (`nic`),
  UNIQUE KEY `UNIQ_45A0D2FFD4E6F81` (`address`),
  KEY `IDX_45A0D2FF680D0B01` (`plot_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `opening_balances`
--

CREATE TABLE IF NOT EXISTS `opening_balances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `financial_year_id` int(11) DEFAULT NULL,
  `debit` decimal(14,2) NOT NULL,
  `credit` decimal(14,2) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modification_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AC2B0D819B6B5FBA` (`account_id`),
  KEY `IDX_AC2B0D811202BCCD` (`financial_year_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `plots`
--

CREATE TABLE IF NOT EXISTS `plots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plot_no` int(11) NOT NULL,
  `kanal` smallint(6) DEFAULT NULL,
  `marla` smallint(6) DEFAULT NULL,
  `square_feet` decimal(8,2) DEFAULT NULL,
  `is_commercial` smallint(6) DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `modification_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE IF NOT EXISTS `resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `serial` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_EF66EBAE727ACA70` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `resources_to_roles`
--

CREATE TABLE IF NOT EXISTS `resources_to_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `status` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `resource_role_index` (`role_id`,`resource_id`),
  KEY `IDX_6E6AEA5689329D25` (`resource_id`),
  KEY `IDX_6E6AEA56D60322AC` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modification_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_company_index` (`name`,`company_id`),
  KEY `IDX_B63E2EC7979B1AD6` (`company_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `role_parents`
--

CREATE TABLE IF NOT EXISTS `role_parents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_parent_index` (`id`,`role_id`,`parent_id`),
  KEY `IDX_419DB891D60322AC` (`role_id`),
  KEY `IDX_419DB891727ACA70` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE IF NOT EXISTS `states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_31C2774D5E237E06` (`name`),
  KEY `IDX_31C2774DF92F3E70` (`country_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '1',
  `remember_me` smallint(6) DEFAULT '0',
  `creation_date` datetime DEFAULT NULL,
  `modification_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_company_index` (`username`,`company_id`),
  KEY `IDX_1483A5E9979B1AD6` (`company_id`),
  KEY `IDX_1483A5E9D60322AC` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE IF NOT EXISTS `vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_type_id` int(11) DEFAULT NULL,
  `currency_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `voucher_number` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `serial` int(11) NOT NULL,
  `exchange_rate` double NOT NULL,
  `voucher_date` date NOT NULL,
  `cheque_number` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cheque_date` date DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `modification_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_93150748681A694` (`voucher_type_id`),
  KEY `IDX_9315074838248176` (`currency_id`),
  KEY `IDX_93150748DE12AB56` (`created_by`),
  KEY `IDX_9315074825F94802` (`modified_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `voucher_details`
--

CREATE TABLE IF NOT EXISTS `voucher_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_id` int(11) NOT NULL,
  `narration` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `debit` decimal(14,2) NOT NULL,
  `credit` decimal(14,2) NOT NULL,
  `status` smallint(6) NOT NULL,
  `Account_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B1EB218D28AA1B6F` (`voucher_id`),
  KEY `IDX_B1EB218DD4365C6A` (`Account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Table structure for table `voucher_types`
--

CREATE TABLE IF NOT EXISTS `voucher_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_type_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `behaviour` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9C8BDF0EC6798DB` (`account_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `FK_CAC89EAC25F94802` FOREIGN KEY (`modified_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_CAC89EAC727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `FK_CAC89EAC979B1AD6` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `FK_CAC89EACC6798DB` FOREIGN KEY (`account_type_id`) REFERENCES `account_types` (`id`),
  ADD CONSTRAINT `FK_CAC89EACDCD6CC49` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `FK_CAC89EACDE12AB56` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `bio_infos`
--
ALTER TABLE `bio_infos`
  ADD CONSTRAINT `FK_F3F6F5428BAC62AF` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  ADD CONSTRAINT `FK_F3F6F542979B1AD6` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `FK_F3F6F542A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_F3F6F542DCD6CC49` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `FK_D760D16F979B1AD6` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `FK_D95DB16B5D83CC1` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`);

--
-- Constraints for table `financial_years`
--
ALTER TABLE `financial_years`
  ADD CONSTRAINT `FK_D2690258979B1AD6` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `FK_45A0D2FF680D0B01` FOREIGN KEY (`plot_id`) REFERENCES `plots` (`id`);

--
-- Constraints for table `opening_balances`
--
ALTER TABLE `opening_balances`
  ADD CONSTRAINT `FK_AC2B0D811202BCCD` FOREIGN KEY (`financial_year_id`) REFERENCES `financial_years` (`id`),
  ADD CONSTRAINT `FK_AC2B0D819B6B5FBA` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `FK_EF66EBAE727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `resources` (`id`);

--
-- Constraints for table `resources_to_roles`
--
ALTER TABLE `resources_to_roles`
  ADD CONSTRAINT `FK_6E6AEA5689329D25` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`),
  ADD CONSTRAINT `FK_6E6AEA56D60322AC` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `FK_B63E2EC7979B1AD6` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`);

--
-- Constraints for table `role_parents`
--
ALTER TABLE `role_parents`
  ADD CONSTRAINT `FK_419DB891727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `FK_419DB891D60322AC` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `states`
--
ALTER TABLE `states`
  ADD CONSTRAINT `FK_31C2774DF92F3E70` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_1483A5E9979B1AD6` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  ADD CONSTRAINT `FK_1483A5E9D60322AC` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD CONSTRAINT `FK_9315074825F94802` FOREIGN KEY (`modified_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_9315074838248176` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`),
  ADD CONSTRAINT `FK_93150748681A694` FOREIGN KEY (`voucher_type_id`) REFERENCES `voucher_types` (`id`),
  ADD CONSTRAINT `FK_93150748DE12AB56` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `voucher_details`
--
ALTER TABLE `voucher_details`
  ADD CONSTRAINT `FK_B1EB218D28AA1B6F` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`),
  ADD CONSTRAINT `FK_B1EB218DD4365C6A` FOREIGN KEY (`Account_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `voucher_types`
--
ALTER TABLE `voucher_types`
  ADD CONSTRAINT `FK_9C8BDF0EC6798DB` FOREIGN KEY (`account_type_id`) REFERENCES `account_types` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
