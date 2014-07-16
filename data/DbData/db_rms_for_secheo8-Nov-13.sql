-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2013 at 02:46 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_rms_for_secheo`
--

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `account_type_id`, `branch_id`, `company_id`, `parent_id`, `name`, `code`, `class`, `category`, `level`, `serial`, `status`, `description`, `creation_date`, `modification_date`) VALUES
(1, NULL, NULL, 1, NULL, 'Assets', '01', 1, 1, 1, 0, 1, '', '2013-09-12 18:03:23', NULL),
(2, NULL, NULL, 1, NULL, 'Income', '02', 2, 1, 1, 0, 1, '', '2013-09-12 18:03:48', NULL),
(3, NULL, NULL, 1, NULL, 'Expenses', '03', 3, 1, 1, 0, 1, '', '2013-09-12 18:04:09', NULL),
(4, NULL, NULL, 1, NULL, 'Capital', '05', 5, 1, 1, 0, 1, '', '2013-09-12 18:04:29', NULL),
(5, NULL, NULL, 1, 1, 'Fix Assets', '01-001', 1, 2, 2, 1, 1, '', '2013-09-12 18:05:30', NULL),
(6, NULL, NULL, 1, 1, 'Current Asset', '01-002', 1, 2, 2, 2, 1, '', '2013-09-12 18:05:50', NULL),
(7, 3, 1, 1, 5, 'Car', '01-001-0001', 1, 3, 3, 1, 1, '', '2013-09-12 18:09:30', NULL),
(8, 3, 1, 1, 5, 'Furniture', '01-001-0002', 1, 3, 3, 2, 1, '', '2013-09-12 18:09:54', NULL),
(9, 3, 1, 1, 5, 'Air Conditiner', '01-001-0003', 1, 3, 3, 3, 1, '', '2013-09-12 18:10:16', NULL),
(10, NULL, NULL, 1, 6, 'Bank Account', '01-002-001', 1, 2, 3, 1, 1, '', '2013-09-12 18:13:36', NULL),
(11, NULL, NULL, 1, 6, 'Cash Account', '01-002-002', 1, 2, 3, 2, 1, '', '2013-09-12 18:14:15', NULL),
(12, 2, 1, 1, 10, 'Allied Bank', '01-002-001-0001', 1, 3, 4, 1, 1, '', '2013-09-12 18:15:39', NULL),
(13, 2, 1, 1, 10, 'NIB Bank', '01-002-001-0002', 1, 3, 4, 2, 1, '', '2013-09-12 18:15:54', NULL),
(14, 2, 1, 1, 10, 'Habib Bank', '01-002-001-0003', 1, 3, 4, 3, 0, '', '2013-09-12 18:16:41', NULL),
(15, 1, 1, 1, 11, 'Petty Cash 5000-10000', '01-002-002-0001', 1, 3, 4, 1, 1, '', '2013-09-12 18:17:14', NULL),
(16, 1, 1, 1, 11, 'Petty Cash 10000-50000', '01-002-002-0002', 1, 3, 4, 2, 1, '', '2013-09-12 18:17:55', NULL),
(17, NULL, NULL, 1, 3, 'Bills', '03-001', 3, 2, 2, 1, 1, '', '2013-09-12 18:20:06', NULL),
(18, NULL, NULL, 1, 3, 'Internet', '03-002', 3, 2, 2, 2, 1, '', '2013-09-12 18:20:21', NULL),
(19, NULL, NULL, 1, 3, 'Miscellaneous', '03-003', 3, 2, 2, 3, 1, '', '2013-09-12 18:21:01', NULL),
(20, NULL, NULL, 1, 4, 'Owner''s Capital Account', '05-001', 5, 2, 2, 1, 1, '', '2013-09-12 19:03:02', NULL),
(21, 3, 1, 1, 20, 'Mr Munawaar', '05-001-0001', 5, 3, 3, 1, 1, '', '2013-09-12 19:03:45', NULL),
(22, 3, 1, 1, 20, 'Mr Imran', '05-001-0002', 5, 3, 3, 2, 1, '', '2013-09-12 19:04:44', NULL),
(23, 3, 1, 1, 20, 'Mr Ahmad', '05-001-0003', 5, 3, 3, 3, 1, '', '2013-09-12 19:05:00', NULL);

--
-- Dumping data for table `account_types`
--

INSERT INTO `account_types` (`id`, `name`, `is_default`) VALUES
(1, 'Cash', 0),
(2, 'Bank', 0),
(3, 'Others', 0);

--
-- Dumping data for table `bio_infos`
--

INSERT INTO `bio_infos` (`id`, `city_id`, `company_id`, `user_id`, `branch_id`, `zip_code`, `address`, `email`, `cell`, `phone1`, `phone2`, `fax`) VALUES
(1, 1, 1, NULL, NULL, 32767, '162 C Margzar Colony.', 'info@digitalnet.us', '0304-5476106', '0423-7499439', '', ''),
(2, 1, 2, NULL, NULL, 32767, 'MM ALam Road', 'info@sdsol.com', '03334545855', '0423-9856899', '', ''),
(3, 1, 3, NULL, NULL, 32767, 'Wahdat Road Lahore', 'info@randdsol.us', '0332-42542121', '0423-7499439', '', ''),
(4, 1, NULL, NULL, 1, 32767, '162C margzar Colony Lahore', 'info@digitalnet.pk', '0332-42542121', '0423-7499439', '', ''),
(5, 1, NULL, 1, NULL, 0, 'abc', 'm.rashid.se@gmail.com', '', '', '', '');

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `company_id`, `name`, `branch_type`, `status`, `description`) VALUES
(1, 1, 'Lahore Branch', 1, 1, '');

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `state_id`, `name`) VALUES
(1, 1, 'Lahore'),
(2, 2, 'Gujranwala');

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `status`, `description`, `creation_date`, `modification_date`) VALUES
(1, 'SECHEO', 1, 'Housing Society Scheme in Lahore, Pakistan', '2013-09-12 17:54:55', '0000-00-00 00:00:00'),
(2, 'SDsol', 1, 'Software house in Lahore', '2013-09-12 17:56:01', '0000-00-00 00:00:00'),
(3, 'RandDsol', 1, 'This is randdsol. Software house in lahore. Working on CRM, Microsoft Dynamics.', '2013-09-12 17:59:41', '0000-00-00 00:00:00');

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`) VALUES
(4, 'Afganistan'),
(5, 'Argentina'),
(8, 'Bangladesh'),
(3, 'India'),
(7, 'Malaysia'),
(1, 'Pakistan'),
(10, 'Saudia'),
(6, 'Syria'),
(9, 'UAE'),
(2, 'USA');

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `code`, `is_local`) VALUES
(1, 'Pakistani Rupees', 'PKR', 1),
(2, 'US Dollar', 'USD', 0);

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `parent_id`, `name`, `code`, `level`, `serial`) VALUES
(1, NULL, 'administration', '01', 1, NULL),
(2, 1, 'role', '01-01', 2, 1),
(3, 1, 'resource', '01-02', 2, 2),
(4, 1, 'user', '01-03', 2, 3),
(5, NULL, 'account', '02', 1, NULL),
(6, 5, 'account', '02-01', 2, 1),
(7, 5, 'voucher', '02-02', 2, 2),
(8, 5, 'financial_year', '02-03', 2, 3),
(9, NULL, 'application', '03', 1, NULL),
(10, 9, 'home', '03-01', 2, 1),
(11, 1, 'login', '01-04', 2, 4),
(12, 1, 'company', '01-05', 2, 5);

--
-- Dumping data for table `resources_to_roles`
--

INSERT INTO `resources_to_roles` (`id`, `resource_id`, `role_id`, `status`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 1),
(3, 3, 1, 1),
(4, 4, 1, 1),
(5, 5, 1, 1),
(6, 6, 1, 1),
(7, 7, 1, 1),
(8, 8, 1, 1),
(9, 1, 3, 1),
(10, 10, 1, 1),
(11, 11, 2, 1),
(12, 11, 1, 1),
(13, 12, 1, 1);

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `company_id`, `name`) VALUES
(1, 1, 'Guest'),
(2, 1, 'Jnr. Data Operator'),
(3, 1, 'Snr. Data Operator'),
(4, 1, 'Assistant Member Billings'),
(5, 1, 'Assistant Accounts'),
(6, 1, 'Member Billings Manager'),
(7, 1, 'Accounts Manager'),
(8, 1, 'Snr. Manager'),
(9, 1, 'Administrator'),
(10, 1, 'SuperAdministrator');

--
-- Dumping data for table `role_parents`
--

INSERT INTO `role_parents` (`id`, `role_id`, `parent_id`) VALUES
(1, 1, NULL),
(2, 2, 1),
(3, 3, 2),
(4, 4, 3),
(5, 5, 3),
(6, 6, 4),
(7, 7, 5),
(8, 8, 6),
(9, 8, 7),
(10, 9, 8),
(11, 10, 9);
--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `country_id`, `name`) VALUES
(1, 1, 'Punjab'),
(2, 1, 'Sindh'),
(3, 1, 'KhaiberPakhtoon Khawan'),
(4, 1, 'Blochistan');

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `company_id`,`role_id`, `username`, `name`, `password`, `salt`, `status`, `remember_me`, `creation_date`, `modification_date`) VALUES
(1, 1, 9,'admin', 'Muhammad Rashid', '$2y$14$AFsgz4bA9/6wfWsibET6.uUU9LoIiK0QDY49S20AlFHYzcEUZ6Jim', 'AFsgz4bA9/6wfWsibET6+w==', 1, NULL, '2013-10-15 14:45:15', NULL);

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `voucher_type_id`, `currency_id`, `voucher_number`, `serial`, `exchange_rate`, `voucher_date`, `cheque_number`, `cheque_date`, `creation_date`, `modification_date`) VALUES
(1, 1, 1, 'CPV-001', 1, 1, '2013-03-18', '', NULL, '2013-09-13 22:00:17', NULL),
(2, 2, 1, 'CRV-001', 1, 1, '2013-04-01', '', NULL, '2013-09-13 22:02:57', NULL),
(4, 4, 1, 'BPV-001', 1, 1, '2013-05-15', '', NULL, '2013-09-13 22:10:02', NULL),
(5, 1, 1, 'CPV-002', 1, 1, '2013-05-30', '', NULL, '2013-09-12 22:12:01', NULL),
(6, 1, 1, 'CPV-002', 1, 1, '2013-09-12', '', NULL, '2013-09-12 22:34:24', NULL),
(7, 3, 1, 'BPV-001', 1, 1, '2013-09-12', '12345678', '2013-09-11', '2013-09-12 22:38:44', NULL);

--
-- Dumping data for table `voucher_details`
--

INSERT INTO `voucher_details` (`id`, `voucher_id`, `narration`, `debit`, `credit`, `status`, `Account_id`) VALUES
(1, 1, 'Bill of Different devices', 0.00, 5000.00, 1, 15),
(2, 1, 'Allied Bank use for that', 5000.00, 0.00, 1, 12),
(3, 2, 'Recieve cash for daily expense', 2000.00, 0.00, 1, 15),
(4, 2, 'From company account', 0.00, 2000.00, 1, 13),
(7, 4, 'Cash withdraw', 60000.00, 0.00, 1, 12),
(8, 4, '', 0.00, 60000.00, 1, 23),
(9, 5, 'Payment to labor for daily works ', 0.00, 500.00, 1, 16),
(10, 5, '', 500.00, 0.00, 1, 23),
(11, 6, '', 0.00, 17000.00, 1, 16),
(12, 6, 'for small furniture expense', 4000.00, 0.00, 1, 8),
(13, 6, 'monthly installment of car', 6000.00, 0.00, 1, 7),
(14, 6, 'new bought', 7000.00, 0.00, 1, 9),
(15, 7, 'Habib bank transaction.', 0.00, 57000.00, 1, 14),
(16, 7, 'for monthly epense', 10000.00, 0.00, 1, 15),
(17, 7, 'for new room', 42000.00, 0.00, 1, 9),
(18, 7, 'for new room', 5000.00, 0.00, 1, 8);

--
-- Dumping data for table `voucher_types`
--

INSERT INTO `voucher_types` (`id`, `account_type_id`, `name`, `code`, `behaviour`) VALUES
(1, 1, 'Cash Payment Voucher', 'CPV', 1),
(2, 1, 'Cash Receipt Voucher ', 'CRV', 2),
(3, 2, 'Bank Payment Voucher', 'BPV', 1),
(4, 2, 'Bank Receipt Voucher ', 'BPV', 2),
(5, 3, 'Journal Voucher', 'JV', 3);
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
