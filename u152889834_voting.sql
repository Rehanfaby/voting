-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2026 at 11:50 AM
-- Server version: 11.8.6-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u152889834_voting`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `account_no` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `initial_balance` double DEFAULT NULL,
  `total_balance` double NOT NULL,
  `note` text DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `adjustments`
--

CREATE TABLE `adjustments` (
  `id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(191) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `document` varchar(191) DEFAULT NULL,
  `total_qty` double NOT NULL,
  `item` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ambassadors`
--

CREATE TABLE `ambassadors` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `phone_number` varchar(191) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ambassadors`
--

INSERT INTO `ambassadors` (`id`, `name`, `email`, `phone_number`, `user_id`, `image`, `address`, `city`, `country`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Ambassador 1', 'ambassador@gmail.com', '923410060960', NULL, 'ambassadorgmailcom.png', 'new housing colony shujabad', 'multan', 'Pakistan', 0, '2025-02-08 09:50:23', '2025-02-08 09:51:42'),
(2, 'The Prophetic Minstrel', 'minstrel@mulemagc.com', '353454', NULL, 'minstrelmulemagccom.jpeg', NULL, NULL, NULL, 1, '2025-02-14 11:52:25', '2025-02-14 11:52:25'),
(3, 'Thenella', 'thenella@mulemagc.com', '4243243', NULL, 'thenellamulemagccom.jpeg', NULL, NULL, NULL, 1, '2025-02-14 11:53:21', '2025-02-14 11:53:21'),
(4, 'Asheck Blessing', 'asheck@mulemagc.com', '4353454', NULL, 'asheckmulemagccom.jpeg', NULL, NULL, NULL, 1, '2025-02-14 11:53:50', '2025-02-14 11:53:50'),
(5, 'Charles Citenga', 'citenga@mulegc.com', '54353453', NULL, 'citengamulegccom.jpeg', NULL, NULL, NULL, 1, '2025-02-14 11:54:32', '2025-02-14 11:54:32'),
(6, 'Guy Michel', 'guy@mulemagc.com', '7764563', NULL, 'guymulemagccom.jpeg', NULL, NULL, NULL, 1, '2025-02-14 11:55:33', '2025-02-14 11:55:33'),
(7, 'Prosper Menko', 'menko@mulemagc.com', '542534', NULL, 'menkomulemagccom.jpeg', NULL, NULL, NULL, 1, '2025-02-14 11:56:08', '2025-02-14 11:56:08');

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` int(10) UNSIGNED NOT NULL,
  `serial_no` varchar(191) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL,
  `station_id` int(11) DEFAULT NULL,
  `donor_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `image` varchar(191) DEFAULT NULL,
  `manufacturer` varchar(191) DEFAULT NULL,
  `model` varchar(191) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `number_of_Seats` int(11) DEFAULT NULL,
  `serial` varchar(191) DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `physical_location` varchar(191) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `service_date` date DEFAULT NULL,
  `life_span` int(11) DEFAULT NULL,
  `asset_type` varchar(191) DEFAULT NULL,
  `Assign_to` varchar(191) DEFAULT NULL,
  `manager` varchar(191) DEFAULT NULL,
  `set_type` varchar(191) DEFAULT NULL,
  `depreciation_type` varchar(191) DEFAULT NULL,
  `remark` varchar(191) DEFAULT NULL,
  `driver` varchar(191) DEFAULT NULL,
  `milage_at_purchase` varchar(191) DEFAULT NULL,
  `chassi_number` varchar(191) DEFAULT NULL,
  `engine_type` varchar(191) DEFAULT NULL,
  `horse_power` varchar(191) DEFAULT NULL,
  `matricule` varchar(191) DEFAULT NULL,
  `ram` varchar(191) DEFAULT NULL,
  `hard_drive` varchar(191) DEFAULT NULL,
  `operating_system` varchar(191) DEFAULT NULL,
  `processor` varchar(191) DEFAULT NULL,
  `processor_speed` varchar(191) DEFAULT NULL,
  `tv_size` varchar(191) DEFAULT NULL,
  `house_in_land` varchar(191) DEFAULT NULL,
  `furnished` varchar(191) DEFAULT NULL,
  `dimentions_of_the_plot` varchar(191) DEFAULT NULL,
  `number_of_Room` varchar(191) DEFAULT NULL,
  `source_code_owner` varchar(191) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_categories`
--

CREATE TABLE `asset_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `image` text DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_expenses`
--

CREATE TABLE `asset_expenses` (
  `id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(191) NOT NULL,
  `expense_category_id` int(11) DEFAULT NULL,
  `asset_id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `start_km` int(11) DEFAULT NULL,
  `end_km` int(11) DEFAULT NULL,
  `total_km` int(11) DEFAULT NULL,
  `approved` varchar(191) DEFAULT NULL,
  `reason_for_trip` text DEFAULT NULL,
  `num_of_photocopies` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `note` text DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `repairer_name` varchar(191) DEFAULT NULL,
  `repairer_address` varchar(191) DEFAULT NULL,
  `repairer_phone` varchar(191) DEFAULT NULL,
  `repairer_location` varchar(191) DEFAULT NULL,
  `repair_status` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_sales`
--

CREATE TABLE `asset_sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `buyer_name` varchar(191) DEFAULT NULL,
  `buyer_number` varchar(191) DEFAULT NULL,
  `buyer_email` varchar(191) DEFAULT NULL,
  `buyer_id` varchar(191) DEFAULT NULL,
  `buyer_id_date` date DEFAULT NULL,
  `buyer_total_amount` int(11) DEFAULT NULL,
  `buyer_remark` text DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `buyer_title` varchar(191) DEFAULT NULL,
  `buyer_address` varchar(191) DEFAULT NULL,
  `buyer_to` varchar(191) DEFAULT NULL,
  `saller_title` varchar(191) DEFAULT NULL,
  `saller_name` varchar(191) DEFAULT NULL,
  `saller_number` varchar(191) DEFAULT NULL,
  `saller_email` varchar(191) DEFAULT NULL,
  `saller_id` varchar(191) DEFAULT NULL,
  `saller_id_date` date DEFAULT NULL,
  `saller_address` varchar(191) DEFAULT NULL,
  `saller_to` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_sale_details`
--

CREATE TABLE `asset_sale_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `buyer_name` varchar(191) DEFAULT NULL,
  `asset_sale_id` int(11) NOT NULL,
  `asset_id` int(11) NOT NULL,
  `asset_name` varchar(191) NOT NULL,
  `price` double DEFAULT NULL,
  `life_span` double DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_transfers`
--

CREATE TABLE `asset_transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `asset_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `life_span` double(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `employee_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `checkin` varchar(191) NOT NULL,
  `checkout` varchar(191) NOT NULL,
  `status` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billers`
--

CREATE TABLE `billers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `image` varchar(191) DEFAULT NULL,
  `company_name` varchar(191) NOT NULL,
  `vat_number` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `phone_number` varchar(191) NOT NULL,
  `address` varchar(191) NOT NULL,
  `city` varchar(191) NOT NULL,
  `state` varchar(191) DEFAULT NULL,
  `postal_code` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(191) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `biller_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cash_register_id` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `total_qty` double NOT NULL,
  `total_discount` double NOT NULL,
  `total_tax` double NOT NULL,
  `total_price` double NOT NULL,
  `grand_total` double NOT NULL,
  `order_tax_rate` double DEFAULT NULL,
  `order_tax` double DEFAULT NULL,
  `order_discount` double DEFAULT NULL,
  `shipping_cost` double DEFAULT NULL,
  `booking_status` int(11) NOT NULL,
  `payment_status` int(11) NOT NULL,
  `document` varchar(191) DEFAULT NULL,
  `paid_amount` double DEFAULT NULL,
  `booking_note` text DEFAULT NULL,
  `staff_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_products`
--

CREATE TABLE `booking_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `booking_method` int(11) NOT NULL,
  `qty` double NOT NULL,
  `sale_unit_id` int(11) NOT NULL,
  `net_unit_price` double NOT NULL,
  `discount` double NOT NULL,
  `tax_rate` double NOT NULL,
  `tax` double NOT NULL,
  `total` double NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `category_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `product_batch_id` int(11) DEFAULT NULL,
  `multi_product_batch_id` int(11) DEFAULT NULL,
  `multi_product_batch_qty` int(11) DEFAULT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `is_return` int(11) NOT NULL DEFAULT 0,
  `is_notified` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `number_duration` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL,
  `image` varchar(191) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cash_registers`
--

CREATE TABLE `cash_registers` (
  `id` int(10) UNSIGNED NOT NULL,
  `cash_in_hand` double NOT NULL,
  `user_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `image` varchar(191) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coins`
--

CREATE TABLE `coins` (
  `id` int(10) UNSIGNED NOT NULL,
  `phone` varchar(191) NOT NULL,
  `coin` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `is_active` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coins`
--

INSERT INTO `coins` (`id`, `phone`, `coin`, `code`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '237675321739', 0, 938013, 1, '2025-02-06 14:10:09', '2025-02-06 14:10:40'),
(2, '237671309247', 30, 420893, 1, '2025-02-07 13:11:06', '2025-02-07 13:18:01'),
(3, '237693732176', 100, 314363, 1, '2025-02-11 10:33:38', '2025-02-11 10:33:38'),
(4, '237693732176', 120, 231810, 1, '2025-02-11 10:34:09', '2025-02-11 10:36:58'),
(5, '237693732176', 800, 487199, 1, '2025-02-11 10:48:06', '2025-02-11 10:54:16'),
(6, '237693732176', 960, 19640, 1, '2025-02-11 11:17:20', '2025-02-11 11:18:58'),
(7, '16132622362', 470, 547239, 1, '2025-02-12 21:47:14', '2025-02-12 21:49:16');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(191) NOT NULL,
  `type` varchar(191) NOT NULL,
  `amount` double NOT NULL,
  `minimum_amount` double DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `used` int(11) NOT NULL,
  `expired_date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `exchange_rate` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `code`, `exchange_rate`, `created_at`, `updated_at`) VALUES
(1, 'US Dollar', 'USD', 1, '2020-11-01 00:22:58', '2020-11-01 00:34:55'),
(3, 'Canadian Dollar', 'CAD', 1, '2024-07-20 21:25:08', '2025-07-01 16:46:29'),
(4, 'FRANCS CFA', 'CFA', 625, '2024-12-16 17:53:21', '2024-12-16 14:04:51');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_group_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `company_name` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone_number` varchar(191) NOT NULL,
  `tax_no` varchar(191) DEFAULT NULL,
  `address` varchar(191) NOT NULL,
  `city` varchar(191) NOT NULL,
  `state` varchar(191) DEFAULT NULL,
  `postal_code` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `points` double DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deposit` double DEFAULT NULL,
  `expense` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_groups`
--

CREATE TABLE `customer_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `percentage` varchar(191) NOT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(191) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `address` text NOT NULL,
  `delivered_by` varchar(191) DEFAULT NULL,
  `recieved_by` varchar(191) DEFAULT NULL,
  `file` varchar(191) DEFAULT NULL,
  `note` varchar(191) DEFAULT NULL,
  `status` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `code`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'VOTES', '001', 1, '2025-01-21 05:12:13', '2025-01-27 15:30:57');

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` int(10) UNSIGNED NOT NULL,
  `amount` double NOT NULL,
  `customer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disposes`
--

CREATE TABLE `disposes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `asset_id` int(11) NOT NULL,
  `method` varchar(191) NOT NULL,
  `other` varchar(191) DEFAULT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `image` varchar(191) DEFAULT NULL,
  `company_name` varchar(191) NOT NULL,
  `vat_number` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `phone_number` varchar(191) NOT NULL,
  `address` varchar(191) NOT NULL,
  `city` varchar(191) NOT NULL,
  `state` varchar(191) DEFAULT NULL,
  `postal_code` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `phone_number` varchar(191) NOT NULL,
  `department_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_approve` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `email`, `phone_number`, `department_id`, `user_id`, `image`, `address`, `city`, `country`, `is_active`, `created_at`, `updated_at`, `is_approve`) VALUES
(1, 'Mulema Gospel', 'info@mulemagc.com', '00123344', 1, 57, 'infomulemagccom.gif', 'Mile Six, Nkwen', 'Bamenda', 'Cameroon', 0, '2025-01-21 05:12:43', '2025-03-25 09:03:45', 1),
(2, 'Amah', 'info@amah.com', '00093', 1, 58, 'infoamahcom.gif', 'Mile Six', 'Bamenda', 'Cameroon', 0, '2025-02-06 14:00:32', '2025-02-11 10:46:20', 0),
(3, 'rehanfaby36', 'rehanfaby36@gmail.com', '923410060960', 1, 63, 'rehanfaby36gmailcom.png', 'new housing colony shujabad', 'multan', 'Pakistan', 0, '2025-02-08 10:04:52', '2025-02-08 10:11:08', 0),
(4, 'rehan mansoor', 'rehanfaby36@gmail.com', '923410060960', 1, 64, NULL, NULL, NULL, NULL, 0, '2025-02-08 10:12:32', '2025-02-14 11:46:02', 0),
(5, 'Rosy', 'rosy@mulemagc.com', '+237693732176', 1, 66, 'rosymulemagccom.gif', 'Odza borne 12,', 'Yaoundé', 'Cameroun', 0, '2025-02-11 10:30:49', '2025-02-14 11:46:02', 0),
(6, 'Rosy Toure', 'toure@mulemagc.com', '+237680533612', 1, 68, 'touremulemagccom.gif', 'Odza borne 12,', 'Yaoundé', 'Cameroun', 0, '2025-02-11 10:51:15', '2025-02-14 11:46:02', 0),
(7, 'Glorya', 'glorya@mulemagc.com', '699883204', 1, 71, 'gloryamulemagccom.gif', 'Odza borne 12,', 'Yaoundé', NULL, 0, '2025-02-11 11:15:45', '2025-02-14 11:46:02', 1),
(8, 'test contestant', 'editor@gmail.com', '923410060960', 1, 75, NULL, NULL, NULL, NULL, 0, '2025-02-14 05:04:31', '2025-02-14 11:46:02', 0),
(9, 'Rosy Toure', 'toure@mulemagc.com', '4523453', 1, 76, 'touremulemagccom.gif', NULL, NULL, NULL, 0, '2025-02-14 13:08:32', '2025-03-25 09:03:51', 1),
(10, 'Rosy Toure2', 'rosystoure@mulemagc.com', '94848394345', 1, 77, 'rosystouremulemagccom.gif', NULL, NULL, NULL, 0, '2025-02-14 13:10:13', '2025-02-14 13:10:40', 0),
(11, 'Francislot', 'holli.ngsworthwalkley@gmail.com', '82845771165', 1, 82, NULL, NULL, NULL, NULL, 0, '2025-03-10 03:02:58', '2025-04-02 19:58:09', 1),
(12, 'Kalvoksou Tiyoum Sylvain', 'kalvasylva@gmail.com', '+237674696535', 1, 83, 'kalvasylvagmailcom.gif', 'Extreme Nord', 'Maroua', 'Cameroon', 1, '2025-04-02 09:21:51', '2025-04-02 09:28:59', 1),
(13, 'Lehmoug Bougang Lydia', 'fidelesifada@gmail.com', '+237655289825', 1, 84, 'fidelesifadagmailcom.gif', 'Extreme Nord', 'Maroua', 'Cameroon', 1, '2025-04-02 09:25:20', '2025-04-02 09:29:04', 1),
(14, 'Waksou Benjamin Don de Dieu', 'waksoudondedieu@gmail.com', '237692751813', 1, 85, 'waksoudondedieugmailcom.gif', 'Extreme Nord', 'Maroua', 'Cameroon', 1, '2025-04-02 09:26:14', '2025-04-02 09:29:09', 1),
(15, 'Prince Alliance Ahidjo', 'danielahidjo803@gmail.com', '237691998439', 1, 86, 'danielahidjo803gmailcom.gif', 'Extreme Nord', 'Maroua', 'Cameroon', 1, '2025-04-02 09:26:54', '2025-04-02 09:29:16', 1),
(16, 'Precious Ankiambom', 'njungprecious@gmail.com', '237673145808', 1, 87, 'njungpreciousgmailcom.jpeg', 'South West', 'Buea', 'Cameroon', 1, '2025-04-02 09:27:46', '2025-06-23 12:28:22', 1),
(17, 'DIYE MEDI jOYCE', 'diyemedijoyce@gmail.com', '237692140147', 1, 88, 'diyemedijoycegmailcom.jpeg', 'Nord', 'Garoua', 'Cameroon', 1, '2025-04-02 19:44:49', '2025-04-02 19:49:12', 1),
(18, 'Hope Onyinyechi Ibe', 'ibehope608@gmail.com', '237693198215', 1, 89, 'ibehope608gmailcom.jpeg', 'Nord', 'Garoua', 'Cameroon', 1, '2025-04-02 19:45:52', '2025-04-02 19:49:30', 1),
(19, 'MTIMEM MATIBEY LESLIE', 'litiammb0102@gmail.com', '237690928735', 1, 90, 'litiammb0102gmailcom.jpeg', 'Nord', 'Garoua', 'Cameroon', 1, '2025-04-02 19:46:50', '2025-04-02 19:49:42', 1),
(20, 'Malavaye Julie', 'jesusconsole43@gmail.com', '237657053327', 1, 91, 'jesusconsole43gmailcom.jpeg', 'Nord', 'Garoua', 'Cameroon', 1, '2025-04-02 19:47:42', '2025-04-02 19:49:56', 1),
(21, 'Sangbet hermine sonia', 'sangbetsonia@gmail.com', '237698093504', 1, 92, 'sangbetsoniagmailcom.jpeg', 'Nord', 'Garoua', 'Cameroon', 1, '2025-04-02 19:48:38', '2025-04-02 19:50:03', 1),
(22, 'Opdwodowkdwiidwok djwkqdwqofhjqwlsqj jfkmclasdkjfjewlfjkwkdjoiqw fnedkwdkowfwhi jiowjiowhfiwkj rohriowjropwjrofwjrijeiwo mulemagc.com', 'nomin.momin+212e4@mail.ru', '85313718586', 1, 93, NULL, NULL, NULL, NULL, 0, '2025-04-14 01:01:50', '2025-05-26 16:00:45', 0),
(23, 'Dabe Unity Medu', 'dabeunity@gmail.com', '+237657431636', 1, 95, 'dabeunitygmailcom.jpeg', NULL, 'DOUALA', 'CAMEROON', 1, '2025-05-26 15:42:27', '2025-05-26 15:59:47', 1),
(24, 'Bille Ondrade Paule Astride', 'billondrare@gmail.com', '+237655694466', 1, 96, 'billondraregmailcom.jpeg', NULL, 'Douala', 'Cameroon', 1, '2025-05-26 15:44:48', '2025-05-26 15:59:51', 1),
(25, 'Ngan Mayack Arnauld Claude', 'nganmayack@gmail.com', '+237655618182', 1, 97, 'nganmayackgmailcom.jpeg', NULL, 'Douala', 'Cameroon', 1, '2025-05-26 15:47:31', '2025-05-26 16:17:35', 1),
(26, 'Akoumba Jenny', 'akoumbajenny@gmail.com', '+237659571834', 1, 98, 'akoumbajennygmailcom.jpeg', NULL, 'Douala', 'Cameroon', 1, '2025-05-26 15:48:17', '2025-05-26 16:00:01', 1),
(27, 'Christelle Fabiola Bidjeg', 'christellfabiola@gmail.com', '+237698249853', 1, 99, 'christellfabiolagmailcom.jpeg', NULL, 'Douala', 'Cameroon', 1, '2025-05-26 15:48:59', '2025-05-26 16:00:05', 1),
(28, 'Lois Sibefo', 'loissibefor@gmail.com', '+237659585456', 1, 100, 'loissibeforgmailcom.jpeg', NULL, 'Douala', 'Cameroon', 1, '2025-05-26 15:50:03', '2025-05-26 16:00:09', 1),
(29, 'Bangue Sian Obase Bonheur', 'banguesianobase@gmail.com', '+237655283397', 1, 101, 'banguesianobasegmailcom.jpeg', NULL, 'Douala', 'Cameroon', 1, '2025-05-26 15:51:07', '2025-05-26 16:00:13', 1),
(30, 'Grace Eli Glory', 'graceeliglory@gmail.com', '+237691633111', 1, 102, 'graceeliglorygmailcom.jpeg', NULL, 'Douala', 'Cameroon', 1, '2025-05-26 15:52:12', '2025-05-26 16:00:17', 1),
(31, 'Nick Thomas D\'aquin TANG', 'nickthomas@gmail.com', '+237659222507', 1, 103, 'nickthomasgmailcom.jpeg', NULL, 'Douala', 'Cameroon', 1, '2025-05-26 15:53:08', '2025-05-26 16:00:21', 1),
(32, 'Tonye Akoa Eveline Flore', 'oneyakoaeveline@gmail.com', '+237695960745', 1, 104, 'oneyakoaevelinegmailcom.jpeg', NULL, 'Douala', 'Cameroon', 1, '2025-05-26 15:54:26', '2025-05-26 16:14:29', 1),
(33, 'Kogningbo-Te Lackry Athanase omega', 'kogningbotelackry@gmail.com', '+23769948465', 1, 105, 'kogningbotelackrygmailcom.jpeg', NULL, 'Douala', 'Cameroon', 1, '2025-05-26 15:55:56', '2025-05-26 16:00:30', 1),
(34, 'Emmanuel Bitoki Joseph', 'bejbitoki2@gmail.com', '+237658994746', 1, 106, 'bejbitoki2gmailcom.jpeg', 'Adamawa', 'Ngaoundéré', 'CAMEROON', 1, '2025-05-31 13:42:38', '2025-06-13 14:07:03', 1),
(35, 'Bollo Sanda L', 'sandalena9@gmail.com', '+237659332014', 1, 107, 'sandalena9gmailcom.jpeg', 'Adamawa', 'Ngoundere', 'CAMEROON', 1, '2025-05-31 13:43:59', '2025-06-13 14:07:07', 1),
(36, 'Yonkeu Elizabeth', 'yelisabethabigail@gmail.com', '+237657583529', 1, 108, 'yelisabethabigailgmailcom.jpeg', 'Adamawa', 'Ngoundere', 'Cameroon', 0, '2025-05-31 13:57:21', '2025-07-01 11:07:51', 1),
(37, 'Iya Pomna Grace', 'graceiya3@gmail.com', '+237694341690', 1, 109, 'graceiya3gmailcom.jpeg', 'Adamawa', 'Adamawa', 'Ngoundere', 1, '2025-05-31 13:59:02', '2025-06-13 14:07:17', 1),
(38, 'Ngaoul Benoit Appolinaire', 'ngaoulben@gmail.com', '+237692448861', 1, 110, 'ngaoulbengmailcom.jpeg', 'Adamawa', 'Ngaoundere', 'Cameroon', 1, '2025-05-31 14:00:40', '2025-06-13 14:07:27', 1),
(39, 'Emmanuel Maballi', 'emmanuelmaballi@gmail.com', '+237658544392', 1, 111, 'emmanuelmaballigmailcom.jpeg', 'South', 'South', 'Cameroon', 1, '2025-06-13 14:10:03', '2025-06-13 14:14:15', 1),
(40, 'Essomba Abihana Jemima Victoire', 'vickydjems2000@gmail.com', '+237695806291', 1, 112, 'vickydjems2000gmailcom.jpeg', 'South', 'Ebolowa', 'Cameroon', 1, '2025-06-13 14:15:44', '2025-06-13 15:00:36', 1),
(41, 'Haurel Bertrand Ndelle Nzanjo\'o', 'bertrandndelle9@icloud.com', '+237656244400', 1, 113, 'bertrandndelle9icloudcom.jpeg', 'South', 'Ebolowa', 'Cameroon', 1, '2025-06-13 14:17:08', '2025-06-13 15:00:41', 1),
(42, 'Onana Hubert Daniel', 'hubertdanielnlo@gmail.com', '+237697276544', 1, 114, 'hubertdanielnlogmailcom.jpeg', 'South', 'Ebolowa', 'Cameroon', 1, '2025-06-13 14:18:09', '2025-06-13 15:00:45', 1),
(43, 'Axel Bipan', 'bipanaxel@gmail.com', '+237686502524', 1, 115, 'bipanaxelgmailcom.jpeg', 'East', 'Bertoua', 'Cameroon', 1, '2025-06-13 14:20:08', '2025-06-13 15:00:50', 1),
(44, 'Ngo Nguibe Lea Excelle', 'excellelea5@gmail.com', '+237698621194', 1, 116, 'excellelea5gmailcom.jpeg', 'East', 'Bertoua', 'Cameroon', 1, '2025-06-13 14:21:08', '2025-06-13 15:00:54', 1),
(45, 'Evodie Elia', 'evodiemboutou@gmail.com', '+237699298599', 1, 117, 'evodiemboutougmailcom.jpeg', 'East', 'Bertoua', 'Cameroon', 1, '2025-06-13 14:21:52', '2025-06-13 15:00:58', 1),
(46, 'Bayombi Mardochée', 'jamesmardocheebayombi@gmail.com', '+237652057153', 1, 118, 'jamesmardocheebayombigmailcom.jpeg', 'East', 'Bertoua', 'Cameroon', 1, '2025-06-13 14:22:39', '2025-06-13 15:01:03', 1),
(47, 'Kwekem Mboudom Tifen', 'tifenkwekem@gmail.com', '+237656658214', 1, 119, 'tifenkwekemgmailcom.jpeg', 'West', 'Baffoussam', 'Cameroon', 1, '2025-06-13 14:24:10', '2025-06-13 15:01:07', 1),
(48, 'Ellé Rose Pentecote', 'pentecoteelle@gmail.com', '+237658677471', 1, 120, 'pentecoteellegmailcom.jpeg', 'West', 'Baffoussam', 'Cameroon', 1, '2025-06-13 14:24:57', '2025-06-13 15:01:11', 1),
(49, 'DIMEGNY SALOMON', 'dimegnysalomon@gmail.com', '+237693634251', 1, 121, 'dimegnysalomongmailcom.jpeg', 'West', 'Baffoussam', 'Cameroon', 1, '2025-06-13 14:25:38', '2025-06-13 15:01:16', 1),
(50, 'Billy Zengue', 'billy.zengue@gmail.com', '+237655690748', 1, 122, 'billyzenguegmailcom.jpeg', 'West', 'Baffoussam', 'Cameroon', 1, '2025-06-13 14:26:16', '2025-06-13 15:01:20', 1),
(51, 'Perez Kadji', 'perezkanme@gmail.com', '+237694118971', 1, 123, 'perezkanmegmailcom.jpeg', 'West', 'Baffoussam', 'Cameroon', 1, '2025-06-13 14:26:45', '2025-07-01 18:42:20', 1),
(52, 'Onguene mekana Glaube', 'glaubedelphe@gmail.com', '+237691559259', 1, 124, 'glaubedelphegmailcom.jpeg', 'Center', 'Yaounde', 'Cameroon', 1, '2025-06-13 14:28:03', '2025-06-13 15:01:30', 1),
(53, 'Adiyi Barbara Ingrid', 'adoratrice.ingrid@gmail.com', '+237691448025', 1, 125, 'adoratriceingridgmailcom.jpeg', 'West', 'Yaounde', 'Cameroon', 1, '2025-06-13 14:28:42', '2025-06-13 15:01:34', 1),
(54, 'Albert-Régis Pondy Niobe', 'xeralbert@gmail.com', '+237640366667', 1, 126, 'xeralbertgmailcom.jpeg', 'Center', 'Yaounde', 'Cameroon', 1, '2025-06-13 14:29:27', '2025-06-13 15:01:40', 1),
(55, 'Gams gamaliel', 'gamsmatadi2@gmail.com', '+237695434449', 1, 127, 'gamsmatadi2gmailcom.jpeg', 'West', 'Yaounde', 'Cameroon', 1, '2025-06-13 14:31:31', '2025-06-13 15:01:44', 1),
(56, 'Eboto Essong Raymonde', 'raymondeeboto3@gmail.com', '+237688377173', 1, 128, 'raymondeeboto3gmailcom.jpeg', 'Center', 'Yaounde', 'Cameroon', 1, '2025-06-13 14:32:11', '2025-06-13 15:01:49', 1),
(57, 'Laurraine Ndieupe', 'lauryndieups@gmail.com', '+237694884653', 1, 129, 'lauryndieupsgmailcom.jpeg', 'Center', 'Yaounde', 'Cameroon', 1, '2025-06-13 14:32:56', '2025-06-13 15:01:53', 1),
(58, 'JOHAN', 'edoasalomon4@gmail.com', '+237675782453', 1, 130, 'edoasalomon4gmailcom.jpeg', 'Centre', 'Yaounde', 'Cameroon', 1, '2025-06-13 14:33:37', '2025-06-13 15:01:58', 1),
(59, 'Lauretta Mah', 'annakhelane@gmail.com', '+237656766648', 1, 131, 'annakhelanegmailcom.jpeg', 'Center', 'Yaounde', 'Cameroon', 1, '2025-06-13 14:34:25', '2025-06-13 15:02:03', 1),
(60, 'Mfegue Benedicta Lydia', 'benedictanguijdoi@gmail.com', '+237690799542', 1, 132, 'benedictanguijdoigmailcom.jpeg', 'Center', 'Yaounde', 'Cameroon', 1, '2025-06-13 14:35:07', '2025-06-13 15:02:07', 1),
(61, 'Alphir Omgba', 'delgadoalida338@gmail.com', '+237658221907', 1, 133, 'delgadoalida338gmailcom.jpeg', 'Center', 'Yaounde', 'Cameroon', 1, '2025-06-13 14:39:47', '2025-06-17 16:47:58', 1),
(62, 'MVOGO AZIELLE', 'aziellemvogo@icloud.com', '+237693017318', 1, 134, 'aziellemvogoicloudcom.jpeg', 'Center', 'Yaounde', 'Cameroon', 1, '2025-06-13 14:43:33', '2025-06-13 15:02:15', 1),
(63, 'Forpeteuh Glovise forsoh', 'gloviseforsoh@gmail.com', '+237681542224', 1, 135, 'gloviseforsohgmailcom.jpeg', 'South West', 'Buea', 'Cameroon', 1, '2025-06-13 14:44:20', '2025-06-13 15:02:22', 1),
(64, 'Bessem Doreen kange', 'bessdoreen2014@gmail.com', '+23764678045', 1, 136, 'bessdoreen2014gmailcom.jpeg', 'South West', 'Buea', 'Cameroon', 1, '2025-06-13 14:45:06', '2025-06-13 15:02:27', 1),
(65, 'Joshua Orji', 'joshuaorji20@gmail.com', '+237681112873', 1, 137, 'joshuaorji20gmailcom.jpeg', 'South West', 'Buea', 'Cameroon', 1, '2025-06-13 14:45:39', '2025-06-13 15:12:50', 1),
(66, 'Teneng Fabiola Rina Ateh', 'tenengfabiolarina@gmail.com', '+237653483073', 1, 138, 'tenengfabiolarinagmailcom.jpeg', 'South West', 'Buea', 'Cameroon', 1, '2025-06-13 14:46:13', '2025-06-13 15:12:13', 1),
(67, 'Azemchop Nwednjong Dean', 'deannwednjong@gmail.com', '+237681005706', 1, 139, 'deannwednjonggmailcom.jpeg', 'South West', 'Buea', 'Cameroon', 1, '2025-06-13 14:46:57', '2025-06-13 15:11:28', 1),
(68, 'BISANG VANESSA', 'Vanessaawah688@gmail.com', '+2376538616044', 1, 140, 'Vanessaawah688gmailcom.jpeg', 'North West', 'Bamenda', 'Cameroon', 1, '2025-06-13 14:54:52', '2025-06-13 15:02:43', 1),
(69, 'Njilah Joycelyne N.', 'joynjilah95@gmail.com', '+237654992485', 1, 141, 'joynjilah95gmailcom.jpeg', 'North West', 'Bamenda', 'Cameroon', 1, '2025-06-13 14:55:38', '2025-06-13 15:02:48', 1),
(70, 'Kimbi Landry', 'landraebk@gmail.com', '+237676986674', 1, 142, 'landraebkgmailcom.jpeg', 'North West', 'Bamenda', 'Cameroon', 1, '2025-06-13 14:57:42', '2025-06-13 15:03:53', 1),
(71, 'Fokumlah Kareen Neh', 'Nehkareen31@gmail.com', '+237654094852', 1, 143, 'Nehkareen31gmailcom.jpeg', 'North West', 'Bamenda', 'Cameroon', 1, '2025-06-13 14:58:28', '2025-06-13 15:03:58', 1),
(72, 'Menmukah Precious Akweseh', 'menumekaprecious@gmail.com', '+237653205479', 1, 144, 'menumekapreciousgmailcom.jpeg', 'North West', 'Bamenda', 'Cameroon', 1, '2025-06-13 14:59:54', '2025-06-13 15:04:07', 1),
(73, 'NAEWTRER1063323NERTHRTYHR', 'eeiymlvy@aurevoirmail.com', '83728791727', 1, 145, NULL, NULL, NULL, NULL, 1, '2025-06-23 21:25:44', '2025-06-23 21:25:44', 0),
(74, 'NATREGTEGH30526NEHTYHYHTR', 'djiaqsjq@aurevoirmail.com', '86164889468', 1, 146, NULL, NULL, NULL, NULL, 1, '2025-06-28 15:13:34', '2025-06-28 15:13:34', 0);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(191) NOT NULL,
  `expense_category_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cash_register_id` int(11) DEFAULT NULL,
  `amount` double NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE `galleries` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `type` varchar(191) NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galleries`
--

INSERT INTO `galleries` (`id`, `employee_id`, `type`, `file`, `created_at`, `updated_at`) VALUES
(1, 2, 'link', 'https://www.youtube.com/embed/https://www.youtube.com/watch?v=iG5qMQukhKI', '2025-02-06 14:31:59', '2025-02-06 14:31:59'),
(3, 1, 'link', 'https://www.youtube.com/embed/https://www.youtube.com/embed/vNPwjl-TzQ0?si=EHk_VJDy-lHcKlVo', '2025-02-06 17:07:23', '2025-02-06 17:07:23');

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `site_title` varchar(191) NOT NULL,
  `site_logo` varchar(191) DEFAULT NULL,
  `email_header` varchar(191) DEFAULT NULL,
  `email_footer` varchar(191) DEFAULT NULL,
  `email_water_mark` varchar(191) DEFAULT NULL,
  `currency` varchar(191) NOT NULL,
  `staff_access` varchar(191) NOT NULL,
  `date_format` varchar(191) NOT NULL,
  `developed_by` varchar(191) DEFAULT NULL,
  `invoice_format` varchar(191) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `theme` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `currency_position` varchar(191) NOT NULL,
  `letter_serial_no` varchar(191) DEFAULT NULL,
  `profit_percentage` int(11) NOT NULL DEFAULT 10,
  `unit` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `vote_price` int(11) DEFAULT NULL,
  `vote_coin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `site_title`, `site_logo`, `email_header`, `email_footer`, `email_water_mark`, `currency`, `staff_access`, `date_format`, `developed_by`, `invoice_format`, `state`, `theme`, `created_at`, `updated_at`, `currency_position`, `letter_serial_no`, `profit_percentage`, `unit`, `category`, `vote_price`, `vote_coin`) VALUES
(1, 'The Mulema GC', '20250127033542.png', '202312120321.gif', '202311123100.gif', '202211060923.png', '4', 'own', 'd/m/Y', '© Copyright 2025 ®Mulema GC', 'standard', 1, 'default.css', '2018-07-06 06:13:11', '2025-06-23 16:04:13', 'suffix', 'BCL/L', 10, 1, 1, 500, 500);

-- --------------------------------------------------------

--
-- Table structure for table `gift_cards`
--

CREATE TABLE `gift_cards` (
  `id` int(10) UNSIGNED NOT NULL,
  `card_no` varchar(191) NOT NULL,
  `amount` double NOT NULL,
  `expense` double NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `expired_date` date DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gift_card_recharges`
--

CREATE TABLE `gift_card_recharges` (
  `id` int(10) UNSIGNED NOT NULL,
  `gift_card_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `note` text DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_settings`
--

CREATE TABLE `hrm_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `checkin` varchar(191) NOT NULL,
  `checkout` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `image_libraries`
--

CREATE TABLE `image_libraries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `asset_id` int(11) NOT NULL,
  `image` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `judges`
--

CREATE TABLE `judges` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `phone_number` varchar(191) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `judges`
--

INSERT INTO `judges` (`id`, `name`, `email`, `phone_number`, `user_id`, `image`, `address`, `city`, `country`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'George Joseph Moussio', 'moussio@mulemagc.com', '32323', NULL, 'moussiomulemagccom.jpeg', NULL, NULL, NULL, 1, '2025-02-06 14:01:28', '2025-02-07 11:54:12'),
(2, 'Rachel Onalaja', 'onalaja@mulemagc.com', '29384848', NULL, 'onalajamulemagccom.jpeg', NULL, NULL, NULL, 1, '2025-02-06 14:02:08', '2025-02-07 11:53:13'),
(3, 'Papa Lin Nka', 'nka@mulemagc.com', '994944', NULL, 'nkamulemagccom.jpeg', NULL, NULL, NULL, 1, '2025-02-07 11:55:14', '2025-02-14 11:39:57'),
(4, 'Ambassador Prophetic Minstrel', 'minstrel@mulemagc.com', '494949', NULL, 'minstrelmulemagccom.jpeg', NULL, NULL, NULL, 0, '2025-02-07 11:56:43', '2025-02-14 11:47:07'),
(5, 'Ambassador Asheck Blessing', 'asheck@mulemagc.com', '43335', NULL, 'asheckmulemagccom.jpeg', NULL, NULL, NULL, 0, '2025-02-07 12:02:25', '2025-02-14 11:48:03'),
(6, 'Ambassador Charles Citenga', 'citenga@mulegc.com', '432434', NULL, 'citengamulegccom.jpeg', NULL, NULL, NULL, 0, '2025-02-07 12:03:02', '2025-02-14 11:47:55'),
(7, 'Ambassador Prosper Menko', 'menko@mulemagc.com', '545454', NULL, 'menkomulemagccom.jpeg', NULL, NULL, NULL, 0, '2025-02-07 12:03:47', '2025-02-14 11:47:31'),
(8, 'Ambassador Thenella', 'thenella@mulemagc.com', '4412445', NULL, 'thenellamulemagccom.jpeg', NULL, NULL, NULL, 0, '2025-02-07 12:05:11', '2025-02-14 11:47:22'),
(9, 'Ambassador Guy Michel', 'michel@mulemagc.com', '5443434', NULL, 'michelmulemagccom.jpeg', NULL, NULL, NULL, 0, '2025-02-07 12:06:29', '2025-02-14 11:47:13');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `letters`
--

CREATE TABLE `letters` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `template_id` int(11) DEFAULT NULL,
  `reference` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `to` text NOT NULL,
  `cc` text DEFAULT NULL,
  `header` text DEFAULT NULL,
  `subject` text NOT NULL,
  `body` text DEFAULT NULL,
  `footer` text DEFAULT NULL,
  `attachment` text DEFAULT NULL,
  `is_approve` tinyint(1) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `is_sign` tinyint(1) DEFAULT NULL,
  `signed_by` int(11) DEFAULT NULL,
  `is_sent` tinyint(1) NOT NULL DEFAULT 0,
  `sent_by` int(11) DEFAULT NULL,
  `is_edit` tinyint(1) NOT NULL DEFAULT 0,
  `edit_by` int(11) DEFAULT NULL,
  `is_rejected` tinyint(1) NOT NULL DEFAULT 0,
  `reject_by` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `otp` varchar(191) DEFAULT NULL,
  `otp_time` datetime DEFAULT NULL,
  `people_type` enum('user','customer') NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `letter_categories`
--

CREATE TABLE `letter_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `letter_templates`
--

CREATE TABLE `letter_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `header` text DEFAULT NULL,
  `subject` text NOT NULL,
  `body` text DEFAULT NULL,
  `footer` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_by` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2018_02_17_060412_create_categories_table', 1),
(4, '2018_02_20_035727_create_brands_table', 1),
(5, '2018_02_25_100635_create_suppliers_table', 1),
(6, '2018_02_27_101619_create_warehouse_table', 1),
(7, '2018_03_03_040448_create_units_table', 1),
(8, '2018_03_04_041317_create_taxes_table', 1),
(9, '2018_03_10_061915_create_customer_groups_table', 1),
(10, '2018_03_10_090534_create_customers_table', 1),
(11, '2018_03_11_095547_create_billers_table', 1),
(12, '2018_04_05_054401_create_products_table', 1),
(13, '2018_04_06_133606_create_purchases_table', 1),
(14, '2018_04_06_154600_create_product_purchases_table', 1),
(15, '2018_04_06_154915_create_product_warhouse_table', 1),
(16, '2018_04_10_085927_create_sales_table', 1),
(17, '2018_04_10_090133_create_product_sales_table', 1),
(18, '2018_04_10_090254_create_payments_table', 1),
(19, '2018_04_10_090341_create_payment_with_cheque_table', 1),
(20, '2018_04_10_090509_create_payment_with_credit_card_table', 1),
(21, '2018_04_13_121436_create_quotation_table', 1),
(22, '2018_04_13_122324_create_product_quotation_table', 1),
(23, '2018_04_14_121802_create_transfers_table', 1),
(24, '2018_04_14_121913_create_product_transfer_table', 1),
(25, '2018_05_13_082847_add_payment_id_and_change_sale_id_to_payments_table', 1),
(26, '2018_05_13_090906_change_customer_id_to_payment_with_credit_card_table', 1),
(27, '2018_05_20_054532_create_adjustments_table', 1),
(28, '2018_05_20_054859_create_product_adjustments_table', 1),
(29, '2018_05_21_163419_create_returns_table', 1),
(30, '2018_05_21_163443_create_product_returns_table', 1),
(31, '2018_06_02_050905_create_roles_table', 1),
(32, '2018_06_02_073430_add_columns_to_users_table', 1),
(33, '2018_06_03_053738_create_permission_tables', 1),
(34, '2018_06_21_063736_create_pos_setting_table', 1),
(35, '2018_06_21_094155_add_user_id_to_sales_table', 1),
(36, '2018_06_21_101529_add_user_id_to_purchases_table', 1),
(37, '2018_06_21_103512_add_user_id_to_transfers_table', 1),
(38, '2018_06_23_061058_add_user_id_to_quotations_table', 1),
(39, '2018_06_23_082427_add_is_deleted_to_users_table', 1),
(40, '2018_06_25_043308_change_email_to_users_table', 1),
(41, '2018_07_06_115449_create_general_settings_table', 2),
(42, '2018_07_08_043944_create_languages_table', 2),
(43, '2018_07_11_102144_add_user_id_to_returns_table', 2),
(44, '2018_07_11_102334_add_user_id_to_payments_table', 2),
(45, '2018_07_22_130541_add_digital_to_products_table', 2),
(46, '2018_07_24_154250_create_deliveries_table', 2),
(47, '2018_08_16_053336_create_expense_categories_table', 2),
(48, '2018_08_17_115415_create_expenses_table', 2),
(49, '2018_08_18_050418_create_gift_cards_table', 2),
(50, '2018_08_19_063119_create_payment_with_gift_card_table', 2),
(51, '2018_08_25_042333_create_gift_card_recharges_table', 2),
(52, '2018_08_25_101354_add_deposit_expense_to_customers_table', 2),
(53, '2018_08_26_043801_create_deposits_table', 2),
(54, '2018_09_02_044042_add_keybord_active_to_pos_setting_table', 2),
(55, '2018_09_09_092713_create_payment_with_paypal_table', 2),
(56, '2018_09_10_051254_add_currency_to_general_settings_table', 3),
(57, '2018_10_22_084118_add_biller_and_store_id_to_users_table', 3),
(58, '2018_10_26_034927_create_coupons_table', 3),
(59, '2018_10_27_090857_add_coupon_to_sales_table', 3),
(60, '2018_11_07_070155_add_currency_position_to_general_settings_table', 4),
(61, '2018_11_19_094650_add_combo_to_products_table', 4),
(62, '2018_12_09_043712_create_accounts_table', 4),
(63, '2018_12_17_112253_add_is_default_to_accounts_table', 4),
(64, '2018_12_19_103941_add_account_id_to_payments_table', 4),
(65, '2018_12_20_065900_add_account_id_to_expenses_table', 4),
(66, '2018_12_20_082753_add_account_id_to_returns_table', 4),
(67, '2018_12_26_064330_create_return_purchases_table', 4),
(68, '2018_12_26_144708_create_purchase_product_return_table', 4),
(69, '2018_12_27_110018_create_departments_table', 4),
(70, '2018_12_30_054844_create_employees_table', 4),
(71, '2018_12_31_125210_create_payrolls_table', 4),
(72, '2018_12_31_150446_add_department_id_to_employees_table', 4),
(73, '2019_01_01_062708_add_user_id_to_expenses_table', 4),
(74, '2019_01_02_075644_create_hrm_settings_table', 4),
(75, '2019_01_02_090334_create_attendances_table', 4),
(76, '2019_01_27_160956_add_three_columns_to_general_settings_table', 5),
(77, '2019_02_15_183303_create_stock_counts_table', 5),
(78, '2019_02_17_101604_add_is_adjusted_to_stock_counts_table', 5),
(79, '2019_04_13_101707_add_tax_no_to_customers_table', 5),
(80, '2019_08_19_000000_create_failed_jobs_table', 5),
(81, '2019_10_14_111455_create_holidays_table', 5),
(82, '2019_11_13_145619_add_is_variant_to_products_table', 5),
(83, '2019_11_13_150206_create_product_variants_table', 5),
(84, '2019_11_13_153828_create_variants_table', 5),
(85, '2019_11_25_134041_add_qty_to_product_variants_table', 5),
(86, '2019_11_25_134922_add_variant_id_to_product_purchases_table', 5),
(87, '2019_11_25_145341_add_variant_id_to_product_warehouse_table', 5),
(88, '2019_11_29_182201_add_variant_id_to_product_sales_table', 5),
(89, '2019_12_04_121311_add_variant_id_to_product_quotation_table', 5),
(90, '2019_12_05_123802_add_variant_id_to_product_transfer_table', 5),
(91, '2019_12_08_114954_add_variant_id_to_product_returns_table', 5),
(92, '2019_12_08_203146_add_variant_id_to_purchase_product_return_table', 5),
(93, '2020_02_28_103340_create_money_transfers_table', 5),
(94, '2020_07_01_193151_add_image_to_categories_table', 5),
(95, '2020_09_26_130426_add_user_id_to_deliveries_table', 5),
(96, '2020_10_11_125457_create_cash_registers_table', 5),
(97, '2020_10_13_155019_add_cash_register_id_to_sales_table', 5),
(98, '2020_10_13_172624_add_cash_register_id_to_returns_table', 5),
(99, '2020_10_17_212338_add_cash_register_id_to_payments_table', 5),
(100, '2020_10_18_124200_add_cash_register_id_to_expenses_table', 5),
(101, '2020_10_21_121632_add_developed_by_to_general_settings_table', 6),
(102, '2020_10_30_135557_create_notifications_table', 6),
(103, '2020_11_01_044954_create_currencies_table', 7),
(104, '2020_11_01_140736_add_price_to_product_warehouse_table', 7),
(105, '2020_11_02_050633_add_is_diff_price_to_products_table', 7),
(106, '2020_11_09_055222_add_user_id_to_customers_table', 7),
(107, '2020_11_17_054806_add_invoice_format_to_general_settings_table', 8),
(108, '2021_02_10_074859_add_variant_id_to_product_adjustments_table', 8),
(109, '2021_03_07_093606_create_product_batches_table', 8),
(110, '2021_03_07_093759_add_product_batch_id_to_product_warehouse_table', 8),
(111, '2021_03_07_093900_add_product_batch_id_to_product_purchases_table', 8),
(112, '2021_03_11_132603_add_product_batch_id_to_product_sales_table', 8),
(113, '2021_03_25_125421_add_is_batch_to_products_table', 8),
(114, '2021_05_19_120127_add_product_batch_id_to_product_returns_table', 8),
(115, '2021_05_22_105611_add_product_batch_id_to_purchase_product_return_table', 8),
(116, '2021_05_23_124848_add_product_batch_id_to_product_transfer_table', 8),
(117, '2021_05_26_153106_add_product_batch_id_to_product_quotation_table', 8),
(118, '2021_06_08_213007_create_reward_point_settings_table', 8),
(119, '2021_06_16_104155_add_points_to_customers_table', 8),
(120, '2021_06_17_101057_add_used_points_to_payments_table', 8),
(121, '2023_03_16_084635_create_regions_table', 8),
(122, '2023_03_16_084648_create_donors_table', 8),
(123, '2023_03_16_084707_create_asset_categories_table', 8),
(124, '2023_03_16_084718_create_assets_table', 8),
(125, '2023_03_17_060607_create_stations_table', 8),
(126, '2023_03_22_160915_create_image_libraries_table', 8),
(127, '2023_03_30_071415_create_asset_expenses_table', 8),
(128, '2023_04_01_145330_create_disposes_table', 8),
(129, '2023_04_05_154939_create_tranfers_table', 8),
(130, '2023_04_10_125119_add_column_in_asset_expense', 8),
(131, '2023_04_13_084706_create_asset_sales_table', 8),
(132, '2023_04_13_084857_create_asset_sale_details_table', 8),
(133, '2023_04_17_104345_add-column-in-asset-transfer', 8),
(134, '2023_04_17_152516_add-column-in-asset-sales', 8),
(135, '2023_04_17_154947_add-column-in-asset-sale_details', 8),
(136, '2023_05_07_083315_add_column_in_product_table', 8),
(137, '2023_05_14_085448_create_bookings_table', 8),
(138, '2023_05_14_085516_create_booking_products_table', 8),
(139, '2023_05_14_140910_add_column_in_payments', 8),
(140, '2023_05_18_053558_insert-in-permission-table', 8),
(141, '2023_06_01_182050_add_column_in_booking_product', 9),
(142, '2023_06_28_151008_add_booking_duration_column_in_booking_product', 9),
(143, '2023_07_07_092150_create_letters_table', 9),
(144, '2023_07_07_111837_create_letter_categories_table', 9),
(145, '2023_07_07_111857_create_letter_templates_table', 9),
(146, '2023_07_08_043657_add_column_in_general_setting', 10),
(147, '2023_07_08_095354_add_columns_in_users', 10),
(148, '2023_07_26_123103_add_column_in_user_table', 10),
(149, '2023_07_31_170225_create_votes_table', 10),
(150, '2023_08_02_130022_create_judges_table', 10),
(151, '2023_08_04_113116_create_coins_table', 10),
(152, '2025_02_07_094340_create_ambassador_table', 11),
(153, '2025_02_11_063848_add-approve-flag-in-employee', 12);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `money_transfers`
--

CREATE TABLE `money_transfers` (
  `id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(191) NOT NULL,
  `from_account_id` int(11) NOT NULL,
  `to_account_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(191) NOT NULL,
  `notifiable_type` varchar(191) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `cash_register_id` int(11) DEFAULT NULL,
  `account_id` int(11) NOT NULL,
  `payment_reference` varchar(191) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `used_points` double DEFAULT NULL,
  `paying_method` varchar(191) NOT NULL,
  `payment_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `debit_booking_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_with_cheque`
--

CREATE TABLE `payment_with_cheque` (
  `id` int(10) UNSIGNED NOT NULL,
  `payment_id` int(11) NOT NULL,
  `cheque_no` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_with_credit_card`
--

CREATE TABLE `payment_with_credit_card` (
  `id` int(10) UNSIGNED NOT NULL,
  `payment_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_stripe_id` varchar(191) DEFAULT NULL,
  `charge_id` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_with_gift_card`
--

CREATE TABLE `payment_with_gift_card` (
  `id` int(10) UNSIGNED NOT NULL,
  `payment_id` int(11) NOT NULL,
  `gift_card_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_with_paypal`
--

CREATE TABLE `payment_with_paypal` (
  `id` int(10) UNSIGNED NOT NULL,
  `payment_id` int(11) NOT NULL,
  `transaction_id` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls`
--

CREATE TABLE `payrolls` (
  `id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(191) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `paying_method` varchar(191) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(4, 'products-edit', 'web', '2018-06-03 01:00:09', '2018-06-03 01:00:09'),
(5, 'products-delete', 'web', '2018-06-03 22:54:22', '2018-06-03 22:54:22'),
(6, 'products-add', 'web', '2018-06-04 00:34:14', '2018-06-04 00:34:14'),
(7, 'products-index', 'web', '2018-06-04 03:34:27', '2018-06-04 03:34:27'),
(8, 'purchases-index', 'web', '2018-06-04 08:03:19', '2018-06-04 08:03:19'),
(9, 'purchases-add', 'web', '2018-06-04 08:12:25', '2018-06-04 08:12:25'),
(10, 'purchases-edit', 'web', '2018-06-04 09:47:36', '2018-06-04 09:47:36'),
(11, 'purchases-delete', 'web', '2018-06-04 09:47:36', '2018-06-04 09:47:36'),
(12, 'sales-index', 'web', '2018-06-04 10:49:08', '2018-06-04 10:49:08'),
(13, 'sales-add', 'web', '2018-06-04 10:49:52', '2018-06-04 10:49:52'),
(14, 'sales-edit', 'web', '2018-06-04 10:49:52', '2018-06-04 10:49:52'),
(15, 'sales-delete', 'web', '2018-06-04 10:49:53', '2018-06-04 10:49:53'),
(16, 'quotes-index', 'web', '2018-06-04 22:05:10', '2018-06-04 22:05:10'),
(17, 'quotes-add', 'web', '2018-06-04 22:05:10', '2018-06-04 22:05:10'),
(18, 'quotes-edit', 'web', '2018-06-04 22:05:10', '2018-06-04 22:05:10'),
(19, 'quotes-delete', 'web', '2018-06-04 22:05:10', '2018-06-04 22:05:10'),
(20, 'transfers-index', 'web', '2018-06-04 22:30:03', '2018-06-04 22:30:03'),
(21, 'transfers-add', 'web', '2018-06-04 22:30:03', '2018-06-04 22:30:03'),
(22, 'transfers-edit', 'web', '2018-06-04 22:30:03', '2018-06-04 22:30:03'),
(23, 'transfers-delete', 'web', '2018-06-04 22:30:03', '2018-06-04 22:30:03'),
(24, 'returns-index', 'web', '2018-06-04 22:50:24', '2018-06-04 22:50:24'),
(25, 'returns-add', 'web', '2018-06-04 22:50:24', '2018-06-04 22:50:24'),
(26, 'returns-edit', 'web', '2018-06-04 22:50:25', '2018-06-04 22:50:25'),
(27, 'returns-delete', 'web', '2018-06-04 22:50:25', '2018-06-04 22:50:25'),
(28, 'customers-index', 'web', '2018-06-04 23:15:54', '2018-06-04 23:15:54'),
(29, 'customers-add', 'web', '2018-06-04 23:15:55', '2018-06-04 23:15:55'),
(30, 'customers-edit', 'web', '2018-06-04 23:15:55', '2018-06-04 23:15:55'),
(31, 'customers-delete', 'web', '2018-06-04 23:15:55', '2018-06-04 23:15:55'),
(32, 'suppliers-index', 'web', '2018-06-04 23:40:12', '2018-06-04 23:40:12'),
(33, 'suppliers-add', 'web', '2018-06-04 23:40:12', '2018-06-04 23:40:12'),
(34, 'suppliers-edit', 'web', '2018-06-04 23:40:12', '2018-06-04 23:40:12'),
(35, 'suppliers-delete', 'web', '2018-06-04 23:40:12', '2018-06-04 23:40:12'),
(36, 'product-report', 'web', '2018-06-24 23:05:33', '2018-06-24 23:05:33'),
(37, 'purchase-report', 'web', '2018-06-24 23:24:56', '2018-06-24 23:24:56'),
(38, 'sale-report', 'web', '2018-06-24 23:33:13', '2018-06-24 23:33:13'),
(39, 'customer-report', 'web', '2018-06-24 23:36:51', '2018-06-24 23:36:51'),
(40, 'due-report', 'web', '2018-06-24 23:39:52', '2018-06-24 23:39:52'),
(41, 'users-index', 'web', '2018-06-25 00:00:10', '2018-06-25 00:00:10'),
(42, 'users-add', 'web', '2018-06-25 00:00:10', '2018-06-25 00:00:10'),
(43, 'users-edit', 'web', '2018-06-25 00:01:30', '2018-06-25 00:01:30'),
(44, 'users-delete', 'web', '2018-06-25 00:01:30', '2018-06-25 00:01:30'),
(45, 'profit-loss', 'web', '2018-07-14 21:50:05', '2018-07-14 21:50:05'),
(46, 'best-seller', 'web', '2018-07-14 22:01:38', '2018-07-14 22:01:38'),
(47, 'daily-sale', 'web', '2018-07-14 22:24:21', '2018-07-14 22:24:21'),
(48, 'monthly-sale', 'web', '2018-07-14 22:30:41', '2018-07-14 22:30:41'),
(49, 'daily-purchase', 'web', '2018-07-14 22:36:46', '2018-07-14 22:36:46'),
(50, 'monthly-purchase', 'web', '2018-07-14 22:48:17', '2018-07-14 22:48:17'),
(51, 'payment-report', 'web', '2018-07-14 23:10:41', '2018-07-14 23:10:41'),
(52, 'warehouse-stock-report', 'web', '2018-07-14 23:16:55', '2018-07-14 23:16:55'),
(53, 'product-qty-alert', 'web', '2018-07-14 23:33:21', '2018-07-14 23:33:21'),
(54, 'supplier-report', 'web', '2018-07-30 03:00:01', '2018-07-30 03:00:01'),
(55, 'expenses-index', 'web', '2018-09-05 01:07:10', '2018-09-05 01:07:10'),
(56, 'expenses-add', 'web', '2018-09-05 01:07:10', '2018-09-05 01:07:10'),
(57, 'expenses-edit', 'web', '2018-09-05 01:07:10', '2018-09-05 01:07:10'),
(58, 'expenses-delete', 'web', '2018-09-05 01:07:11', '2018-09-05 01:07:11'),
(59, 'general_setting', 'web', '2018-10-19 23:10:04', '2018-10-19 23:10:04'),
(60, 'mail_setting', 'web', '2018-10-19 23:10:04', '2018-10-19 23:10:04'),
(61, 'pos_setting', 'web', '2018-10-19 23:10:04', '2018-10-19 23:10:04'),
(62, 'hrm_setting', 'web', '2019-01-02 10:30:23', '2019-01-02 10:30:23'),
(63, 'purchase-return-index', 'web', '2019-01-02 21:45:14', '2019-01-02 21:45:14'),
(64, 'purchase-return-add', 'web', '2019-01-02 21:45:14', '2019-01-02 21:45:14'),
(65, 'purchase-return-edit', 'web', '2019-01-02 21:45:14', '2019-01-02 21:45:14'),
(66, 'purchase-return-delete', 'web', '2019-01-02 21:45:14', '2019-01-02 21:45:14'),
(67, 'account-index', 'web', '2019-01-02 22:06:13', '2019-01-02 22:06:13'),
(68, 'balance-sheet', 'web', '2019-01-02 22:06:14', '2019-01-02 22:06:14'),
(69, 'account-statement', 'web', '2019-01-02 22:06:14', '2019-01-02 22:06:14'),
(70, 'department', 'web', '2019-01-02 22:30:01', '2019-01-02 22:30:01'),
(71, 'attendance', 'web', '2019-01-02 22:30:01', '2019-01-02 22:30:01'),
(72, 'payroll', 'web', '2019-01-02 22:30:01', '2019-01-02 22:30:01'),
(73, 'employees-index', 'web', '2019-01-02 22:52:19', '2019-01-02 22:52:19'),
(74, 'employees-add', 'web', '2019-01-02 22:52:19', '2019-01-02 22:52:19'),
(75, 'employees-edit', 'web', '2019-01-02 22:52:19', '2019-01-02 22:52:19'),
(76, 'employees-delete', 'web', '2019-01-02 22:52:19', '2019-01-02 22:52:19'),
(77, 'user-report', 'web', '2019-01-16 06:48:18', '2019-01-16 06:48:18'),
(78, 'stock_count', 'web', '2019-02-17 10:32:01', '2019-02-17 10:32:01'),
(79, 'adjustment', 'web', '2019-02-17 10:32:02', '2019-02-17 10:32:02'),
(80, 'sms_setting', 'web', '2019-02-22 05:18:03', '2019-02-22 05:18:03'),
(81, 'create_sms', 'web', '2019-02-22 05:18:03', '2019-02-22 05:18:03'),
(82, 'print_barcode', 'web', '2019-03-07 05:02:19', '2019-03-07 05:02:19'),
(83, 'empty_database', 'web', '2019-03-07 05:02:19', '2019-03-07 05:02:19'),
(84, 'customer_group', 'web', '2019-03-07 05:37:15', '2019-03-07 05:37:15'),
(85, 'unit', 'web', '2019-03-07 05:37:15', '2019-03-07 05:37:15'),
(86, 'tax', 'web', '2019-03-07 05:37:15', '2019-03-07 05:37:15'),
(87, 'gift_card', 'web', '2019-03-07 06:29:38', '2019-03-07 06:29:38'),
(88, 'coupon', 'web', '2019-03-07 06:29:38', '2019-03-07 06:29:38'),
(89, 'holiday', 'web', '2019-10-19 08:57:15', '2019-10-19 08:57:15'),
(90, 'warehouse-report', 'web', '2019-10-22 06:00:23', '2019-10-22 06:00:23'),
(91, 'warehouse', 'web', '2020-02-26 06:47:32', '2020-02-26 06:47:32'),
(92, 'brand', 'web', '2020-02-26 06:59:59', '2020-02-26 06:59:59'),
(93, 'billers-index', 'web', '2020-02-26 07:11:15', '2020-02-26 07:11:15'),
(94, 'billers-add', 'web', '2020-02-26 07:11:15', '2020-02-26 07:11:15'),
(95, 'billers-edit', 'web', '2020-02-26 07:11:15', '2020-02-26 07:11:15'),
(96, 'billers-delete', 'web', '2020-02-26 07:11:15', '2020-02-26 07:11:15'),
(97, 'money-transfer', 'web', '2020-03-02 05:41:48', '2020-03-02 05:41:48'),
(98, 'category', 'web', '2020-07-13 12:13:16', '2020-07-13 12:13:16'),
(99, 'delivery', 'web', '2020-07-13 12:13:16', '2020-07-13 12:13:16'),
(100, 'send_notification', 'web', '2020-10-31 06:21:31', '2020-10-31 06:21:31'),
(101, 'today_sale', 'web', '2020-10-31 06:57:04', '2020-10-31 06:57:04'),
(102, 'today_profit', 'web', '2020-10-31 06:57:04', '2020-10-31 06:57:04'),
(103, 'currency', 'web', '2020-11-09 00:23:11', '2020-11-09 00:23:11'),
(104, 'backup_database', 'web', '2020-11-15 00:16:55', '2020-11-15 00:16:55'),
(105, 'reward_point_setting', 'web', '2021-06-27 04:34:42', '2021-06-27 04:34:42'),
(107, 'price-change', 'web', '2022-12-10 07:12:37', '2022-12-10 07:12:37'),
(108, 'average-report', 'web', '2023-01-18 11:55:10', '2023-01-18 11:55:10'),
(109, 'JE-method', 'web', '2023-02-15 03:35:31', '2023-02-15 03:35:31'),
(110, 'multiple_batch', 'web', '2023-03-04 23:28:12', '2023-03-04 23:28:12'),
(111, 'fixed_assets', 'web', '2023-03-16 01:35:07', '2023-03-16 01:35:07'),
(112, 'fixed_assets_report', 'web', '2023-03-28 13:11:43', '2023-03-28 13:11:43'),
(113, 'asset-index', 'web', '2023-04-15 00:12:29', '2023-04-15 00:12:29'),
(114, 'asset-add', 'web', '2023-04-15 00:12:29', '2023-04-15 00:12:29'),
(115, 'asset-edit', 'web', '2023-04-15 00:12:29', '2023-04-15 00:12:29'),
(116, 'asset-delete', 'web', '2023-04-15 00:12:29', '2023-04-15 00:12:29'),
(117, 'donor-index', 'web', '2023-04-15 00:12:29', '2023-04-15 00:12:29'),
(118, 'donor-add', 'web', '2023-04-15 00:12:29', '2023-04-15 00:12:29'),
(119, 'donor-edit', 'web', '2023-04-15 00:12:29', '2023-04-15 00:12:29'),
(120, 'donor-delete', 'web', '2023-04-15 00:12:29', '2023-04-15 00:12:29'),
(121, 'station-index', 'web', '2023-04-15 00:12:29', '2023-04-15 00:12:29'),
(122, 'station-add', 'web', '2023-04-15 00:12:29', '2023-04-15 00:12:29'),
(123, 'station-edit', 'web', '2023-04-15 00:12:29', '2023-04-15 00:12:29'),
(124, 'station-delete', 'web', '2023-04-15 00:12:29', '2023-04-15 00:12:29'),
(125, 'region-index', 'web', '2023-04-15 00:12:29', '2023-04-15 00:12:29'),
(126, 'region-add', 'web', '2023-04-15 00:12:29', '2023-04-15 00:12:29'),
(127, 'region-edit', 'web', '2023-04-15 00:12:29', '2023-04-15 00:12:29'),
(128, 'region-delete', 'web', '2023-04-15 00:12:30', '2023-04-15 00:12:30'),
(129, 'asset-type-index', 'web', '2023-04-15 00:12:30', '2023-04-15 00:12:30'),
(130, 'asset-type-add', 'web', '2023-04-15 00:12:30', '2023-04-15 00:12:30'),
(131, 'asset-type-edit', 'web', '2023-04-15 00:12:30', '2023-04-15 00:12:30'),
(132, 'asset-type-delete', 'web', '2023-04-15 00:12:30', '2023-04-15 00:12:30'),
(133, 'activity-index', 'web', '2023-04-15 00:12:30', '2023-04-15 00:12:30'),
(134, 'activity-add', 'web', '2023-04-15 00:12:30', '2023-04-15 00:12:30'),
(135, 'activity-edit', 'web', '2023-04-15 00:12:30', '2023-04-15 00:12:30'),
(136, 'activity-delete', 'web', '2023-04-15 00:12:30', '2023-04-15 00:12:30'),
(137, 'asset-expense-index', 'web', '2023-04-15 00:12:30', '2023-04-15 00:12:30'),
(138, 'asset-expense-add', 'web', '2023-04-15 00:12:30', '2023-04-15 00:12:30'),
(139, 'asset-expense-edit', 'web', '2023-04-15 00:12:30', '2023-04-15 00:12:30'),
(140, 'asset-expense-delete', 'web', '2023-04-15 00:12:30', '2023-04-15 00:12:30'),
(141, 'asset-sale', 'web', '2023-04-15 00:12:30', '2023-04-15 00:12:30'),
(142, 'asset-transfer', 'web', '2023-04-15 00:12:30', '2023-04-15 00:12:30'),
(143, 'asset-disppose', 'web', '2023-04-15 00:12:30', '2023-04-15 00:12:30'),
(144, 'booking_module', 'web', '2023-05-23 13:11:05', '2023-05-23 13:11:05'),
(145, 'booking_create', 'web', '2023-05-23 13:11:05', '2023-05-23 13:11:05'),
(146, 'booking_edit', 'web', '2023-05-23 13:11:05', '2023-05-23 13:11:05'),
(147, 'booking_index', 'web', '2023-05-23 13:11:05', '2023-05-23 13:11:05'),
(148, 'booking_delete', 'web', '2023-05-23 13:11:05', '2023-05-23 13:11:05'),
(149, 'booking_return', 'web', '2023-05-23 13:11:05', '2023-05-23 13:11:05'),
(150, 'booking_report', 'web', '2023-06-14 20:38:56', '2023-06-15 20:38:56'),
(152, 'letter_module', 'web', '2023-07-07 09:06:49', '2023-07-07 09:06:49'),
(153, 'letter_index', 'web', '2023-07-07 09:06:50', '2023-07-07 09:06:50'),
(154, 'letter_create', 'web', '2023-07-07 09:06:50', '2023-07-07 09:06:50'),
(155, 'letter_edit', 'web', '2023-07-07 09:06:50', '2023-07-07 09:06:50'),
(156, 'letter_delete', 'web', '2023-07-07 09:06:50', '2023-07-07 09:06:50'),
(157, 'letter_approve', 'web', '2023-07-07 09:06:50', '2023-07-07 09:06:50'),
(158, 'letter_template', 'web', '2023-07-07 09:06:50', '2023-07-07 09:06:50'),
(159, 'letter_category', 'web', '2023-07-07 09:31:25', '2023-07-07 09:31:25'),
(160, 'letter_approve_index', 'web', '2023-07-10 08:27:54', '2023-07-10 08:27:54'),
(161, 'letter_send', 'web', '2023-07-10 08:27:54', '2023-07-10 08:27:54'),
(162, 'letter_send_index', 'web', '2023-07-10 08:27:54', '2023-07-10 08:27:54'),
(163, 'letter_sign', 'web', '2023-07-10 08:27:54', '2023-07-10 08:27:54'),
(164, 'letter_sign_index', 'web', '2023-07-10 08:27:54', '2023-07-10 08:27:54'),
(165, 'letter_rejected', 'web', '2023-07-18 14:16:07', '2023-07-18 14:16:07'),
(166, 'letter_awaiting_edit', 'web', '2023-07-18 14:16:07', '2023-07-18 14:16:07'),
(167, 'letter_edited_index', 'web', '2023-07-18 14:17:23', '2023-07-18 14:17:23'),
(168, 'hrm', 'web', '2023-07-18 20:13:32', '2023-07-18 20:13:32'),
(169, 'dashboard', 'web', '2023-07-18 20:13:32', '2023-07-18 20:13:32'),
(170, 'one_time_otp', 'web', NULL, NULL),
(171, 'forward_letter', 'web', NULL, NULL),
(172, 'one_time_otp', 'web', NULL, NULL),
(173, 'forward_letter', 'web', NULL, NULL),
(174, 'zero_stock', 'web', '2023-09-09 16:55:08', '2023-09-09 16:55:08'),
(175, 'search_all_products', 'web', '2023-09-09 16:56:20', '2023-09-09 16:56:20'),
(176, 'developed_by', 'web', '2023-09-09 16:56:20', '2023-09-09 16:56:20'),
(177, 'star_product', 'web', '2023-09-09 16:56:20', '2023-09-09 16:56:20'),
(178, 'votes-index', 'web', NULL, NULL),
(179, 'coins-index', 'web', NULL, NULL),
(180, 'votes-add', 'web', '2025-01-21 05:09:05', '2025-01-21 05:09:05'),
(181, 'votes-edit', 'web', '2025-01-21 05:09:05', '2025-01-21 05:09:05'),
(182, 'votes-delete', 'web', '2025-01-21 05:09:05', '2025-01-21 05:09:05'),
(183, 'coins-add', 'web', '2025-01-21 05:09:05', '2025-01-21 05:09:05'),
(184, 'coins-edit', 'web', '2025-01-21 05:09:06', '2025-01-21 05:09:06'),
(185, 'coins-delete', 'web', '2025-01-21 05:09:06', '2025-01-21 05:09:06'),
(186, 'see-votes', 'web', '2025-01-21 05:09:06', '2025-01-21 05:09:06'),
(187, 'vote-report', 'web', '2025-01-21 05:09:06', '2025-01-21 05:09:06');

-- --------------------------------------------------------

--
-- Table structure for table `pos_setting`
--

CREATE TABLE `pos_setting` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `biller_id` int(11) NOT NULL,
  `product_number` int(11) NOT NULL,
  `keybord_active` tinyint(1) NOT NULL,
  `stripe_public_key` varchar(191) DEFAULT NULL,
  `stripe_secret_key` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `type` varchar(191) NOT NULL,
  `barcode_symbology` varchar(191) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `purchase_unit_id` int(11) NOT NULL,
  `sale_unit_id` int(11) NOT NULL,
  `cost` varchar(191) NOT NULL,
  `price` varchar(191) NOT NULL,
  `qty` double DEFAULT NULL,
  `alert_quantity` double DEFAULT NULL,
  `promotion` tinyint(4) DEFAULT NULL,
  `promotion_price` varchar(191) DEFAULT NULL,
  `starting_date` date DEFAULT NULL,
  `last_date` date DEFAULT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `tax_method` int(11) DEFAULT NULL,
  `image` longtext DEFAULT NULL,
  `file` varchar(191) DEFAULT NULL,
  `is_variant` tinyint(1) DEFAULT NULL,
  `is_batch` tinyint(1) DEFAULT NULL,
  `is_diffPrice` tinyint(1) DEFAULT NULL,
  `featured` tinyint(4) DEFAULT NULL,
  `product_list` varchar(191) DEFAULT NULL,
  `qty_list` varchar(191) DEFAULT NULL,
  `price_list` varchar(191) DEFAULT NULL,
  `product_details` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rent_price_per_hour` double(8,2) NOT NULL DEFAULT 0.00,
  `rent_price_per_day` double(8,2) NOT NULL DEFAULT 0.00,
  `rent_price_per_month` double(8,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_adjustments`
--

CREATE TABLE `product_adjustments` (
  `id` int(10) UNSIGNED NOT NULL,
  `adjustment_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `qty` double NOT NULL,
  `action` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_batches`
--

CREATE TABLE `product_batches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `batch_no` varchar(191) NOT NULL,
  `expired_date` date NOT NULL,
  `qty` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_purchases`
--

CREATE TABLE `product_purchases` (
  `id` int(10) UNSIGNED NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_batch_id` int(11) DEFAULT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `qty` double NOT NULL,
  `recieved` double NOT NULL,
  `purchase_unit_id` int(11) NOT NULL,
  `net_unit_cost` double NOT NULL,
  `discount` double NOT NULL,
  `tax_rate` double NOT NULL,
  `tax` double NOT NULL,
  `total` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_quotation`
--

CREATE TABLE `product_quotation` (
  `id` int(10) UNSIGNED NOT NULL,
  `quotation_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_batch_id` int(11) DEFAULT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `qty` double NOT NULL,
  `sale_unit_id` int(11) NOT NULL,
  `net_unit_price` double NOT NULL,
  `discount` double NOT NULL,
  `tax_rate` double NOT NULL,
  `tax` double NOT NULL,
  `total` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_returns`
--

CREATE TABLE `product_returns` (
  `id` int(10) UNSIGNED NOT NULL,
  `return_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_batch_id` int(11) DEFAULT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `qty` double NOT NULL,
  `sale_unit_id` int(11) NOT NULL,
  `net_unit_price` double NOT NULL,
  `discount` double NOT NULL,
  `tax_rate` double NOT NULL,
  `tax` double NOT NULL,
  `total` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_sales`
--

CREATE TABLE `product_sales` (
  `id` int(10) UNSIGNED NOT NULL,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_batch_id` int(11) DEFAULT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `qty` double NOT NULL,
  `sale_unit_id` int(11) NOT NULL,
  `net_unit_price` double NOT NULL,
  `discount` double NOT NULL,
  `tax_rate` double NOT NULL,
  `tax` double NOT NULL,
  `total` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_transfer`
--

CREATE TABLE `product_transfer` (
  `id` int(10) UNSIGNED NOT NULL,
  `transfer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_batch_id` int(11) DEFAULT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `qty` double NOT NULL,
  `purchase_unit_id` int(11) NOT NULL,
  `net_unit_cost` double NOT NULL,
  `tax_rate` double NOT NULL,
  `tax` double NOT NULL,
  `total` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `item_code` varchar(191) NOT NULL,
  `additional_price` double DEFAULT NULL,
  `qty` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_warehouse`
--

CREATE TABLE `product_warehouse` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` varchar(191) NOT NULL,
  `product_batch_id` int(11) DEFAULT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) NOT NULL,
  `qty` double NOT NULL,
  `price` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(191) NOT NULL,
  `user_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `item` int(11) NOT NULL,
  `total_qty` int(11) NOT NULL,
  `total_discount` double NOT NULL,
  `total_tax` double NOT NULL,
  `total_cost` double NOT NULL,
  `order_tax_rate` double DEFAULT NULL,
  `order_tax` double DEFAULT NULL,
  `order_discount` double DEFAULT NULL,
  `shipping_cost` double DEFAULT NULL,
  `grand_total` double NOT NULL,
  `paid_amount` double NOT NULL,
  `status` int(11) NOT NULL,
  `payment_status` int(11) NOT NULL,
  `document` varchar(191) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_product_return`
--

CREATE TABLE `purchase_product_return` (
  `id` int(10) UNSIGNED NOT NULL,
  `return_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_batch_id` int(11) DEFAULT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `qty` double NOT NULL,
  `purchase_unit_id` int(11) NOT NULL,
  `net_unit_cost` double NOT NULL,
  `discount` double NOT NULL,
  `tax_rate` double NOT NULL,
  `tax` double NOT NULL,
  `total` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(191) NOT NULL,
  `user_id` int(11) NOT NULL,
  `biller_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `total_qty` double NOT NULL,
  `total_discount` double NOT NULL,
  `total_tax` double NOT NULL,
  `total_price` double NOT NULL,
  `order_tax_rate` double DEFAULT NULL,
  `order_tax` double DEFAULT NULL,
  `order_discount` double DEFAULT NULL,
  `shipping_cost` double DEFAULT NULL,
  `grand_total` double NOT NULL,
  `quotation_status` int(11) NOT NULL,
  `document` varchar(191) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(191) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cash_register_id` int(11) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `biller_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `total_qty` double NOT NULL,
  `total_discount` double NOT NULL,
  `total_tax` double NOT NULL,
  `total_price` double NOT NULL,
  `order_tax_rate` double DEFAULT NULL,
  `order_tax` double DEFAULT NULL,
  `grand_total` double NOT NULL,
  `document` varchar(191) DEFAULT NULL,
  `return_note` text DEFAULT NULL,
  `staff_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return_purchases`
--

CREATE TABLE `return_purchases` (
  `id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(191) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `total_qty` double NOT NULL,
  `total_discount` double NOT NULL,
  `total_tax` double NOT NULL,
  `total_cost` double NOT NULL,
  `order_tax_rate` double DEFAULT NULL,
  `order_tax` double DEFAULT NULL,
  `grand_total` double NOT NULL,
  `document` varchar(191) DEFAULT NULL,
  `return_note` text DEFAULT NULL,
  `staff_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reward_point_settings`
--

CREATE TABLE `reward_point_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `per_point_amount` double NOT NULL,
  `minimum_amount` double NOT NULL,
  `duration` int(11) DEFAULT NULL,
  `type` varchar(191) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `guard_name` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`, `guard_name`) VALUES
(1, 'Dueño', 'Dueño de negocio', 1, '2018-06-01 23:46:44', '2024-02-26 01:54:45', 'web'),
(2, 'Contestant', 'Contestant', 1, '2018-10-22 02:38:13', '2025-02-08 10:46:30', 'web'),
(3, 'Voter', NULL, 1, '2025-02-08 09:54:15', '2025-02-08 09:54:15', 'web'),
(4, 'staff', 'staff has specific acess...', 0, '2018-06-02 00:05:27', '2024-02-26 01:51:03', 'web'),
(5, 'Contador', 'Contador', 1, '2020-11-05 06:43:16', '2024-02-26 01:53:28', 'web'),
(6, 'Modified Owner', 'Modified Owner', 0, '2022-04-18 17:45:43', '2024-02-25 02:26:21', 'web'),
(7, 'CUSTOMER1', 'CUSTOMERS', 0, '2023-02-01 15:45:13', '2024-02-26 01:51:32', 'web'),
(8, 'Test', 'test', 0, '2023-05-26 16:59:12', '2024-02-26 01:51:09', 'web'),
(9, 'Editor', 'Editor', 1, '2023-07-24 23:22:23', '2023-07-24 23:22:23', 'web'),
(10, 'Approval', 'Approval', 0, '2023-07-24 23:22:30', '2024-02-26 01:51:13', 'web'),
(11, 'Signing', 'Signing', 0, '2023-07-24 23:22:41', '2024-02-26 01:50:58', 'web'),
(12, 'Ventas', 'Vendedor/a', 1, '2024-02-25 02:25:44', '2024-02-25 02:26:45', 'web'),
(13, 'Fox', 'receptionist', 1, '2024-06-28 19:05:38', '2024-06-28 19:05:38', 'web'),
(14, 'Ambassador', 'Ambassador', 1, '2025-02-08 09:58:33', '2025-02-08 09:58:33', 'web');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(82, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(97, 1),
(98, 1),
(99, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(105, 1),
(107, 1),
(108, 1),
(109, 1),
(110, 1),
(111, 1),
(112, 1),
(113, 1),
(114, 1),
(115, 1),
(116, 1),
(117, 1),
(118, 1),
(119, 1),
(120, 1),
(121, 1),
(122, 1),
(123, 1),
(124, 1),
(125, 1),
(126, 1),
(127, 1),
(128, 1),
(129, 1),
(130, 1),
(131, 1),
(132, 1),
(133, 1),
(134, 1),
(135, 1),
(136, 1),
(137, 1),
(138, 1),
(139, 1),
(140, 1),
(141, 1),
(142, 1),
(143, 1),
(144, 1),
(145, 1),
(146, 1),
(147, 1),
(148, 1),
(149, 1),
(150, 1),
(152, 1),
(153, 1),
(154, 1),
(155, 1),
(156, 1),
(157, 1),
(158, 1),
(159, 1),
(160, 1),
(161, 1),
(162, 1),
(163, 1),
(164, 1),
(165, 1),
(166, 1),
(167, 1),
(168, 1),
(169, 1),
(170, 1),
(171, 1),
(172, 1),
(173, 1),
(174, 1),
(176, 1),
(177, 1),
(178, 1),
(179, 1),
(180, 1),
(181, 1),
(182, 1),
(183, 1),
(184, 1),
(185, 1),
(186, 1),
(187, 1),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(8, 2),
(9, 2),
(10, 2),
(11, 2),
(12, 2),
(13, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 2),
(22, 2),
(23, 2),
(24, 2),
(25, 2),
(26, 2),
(27, 2),
(28, 2),
(29, 2),
(30, 2),
(31, 2),
(32, 2),
(33, 2),
(34, 2),
(35, 2),
(36, 2),
(37, 2),
(38, 2),
(39, 2),
(40, 2),
(45, 2),
(46, 2),
(47, 2),
(48, 2),
(49, 2),
(50, 2),
(51, 2),
(52, 2),
(53, 2),
(54, 2),
(60, 2),
(61, 2),
(62, 2),
(63, 2),
(64, 2),
(65, 2),
(66, 2),
(68, 2),
(69, 2),
(71, 2),
(72, 2),
(73, 2),
(77, 2),
(78, 2),
(79, 2),
(80, 2),
(81, 2),
(82, 2),
(83, 2),
(84, 2),
(85, 2),
(86, 2),
(87, 2),
(88, 2),
(89, 2),
(90, 2),
(91, 2),
(92, 2),
(93, 2),
(94, 2),
(95, 2),
(96, 2),
(97, 2),
(98, 2),
(99, 2),
(101, 2),
(102, 2),
(104, 2),
(105, 2),
(107, 2),
(110, 2),
(144, 2),
(145, 2),
(146, 2),
(147, 2),
(148, 2),
(149, 2),
(150, 2),
(152, 2),
(153, 2),
(154, 2),
(155, 2),
(157, 2),
(158, 2),
(159, 2),
(160, 2),
(161, 2),
(162, 2),
(164, 2),
(165, 2),
(166, 2),
(169, 2),
(170, 2),
(171, 2),
(174, 2),
(177, 2),
(4, 4),
(6, 4),
(7, 4),
(8, 4),
(9, 4),
(12, 4),
(13, 4),
(16, 4),
(17, 4),
(18, 4),
(20, 4),
(21, 4),
(22, 4),
(24, 4),
(25, 4),
(28, 4),
(29, 4),
(32, 4),
(33, 4),
(34, 4),
(55, 4),
(56, 4),
(57, 4),
(63, 4),
(64, 4),
(144, 4),
(145, 4),
(146, 4),
(147, 4),
(150, 4),
(169, 4),
(4, 6),
(5, 6),
(6, 6),
(7, 6),
(8, 6),
(9, 6),
(10, 6),
(11, 6),
(12, 6),
(13, 6),
(14, 6),
(15, 6),
(16, 6),
(17, 6),
(18, 6),
(19, 6),
(20, 6),
(21, 6),
(22, 6),
(23, 6),
(24, 6),
(25, 6),
(26, 6),
(27, 6),
(28, 6),
(29, 6),
(30, 6),
(31, 6),
(32, 6),
(33, 6),
(34, 6),
(35, 6),
(36, 6),
(37, 6),
(38, 6),
(39, 6),
(40, 6),
(41, 6),
(42, 6),
(43, 6),
(44, 6),
(45, 6),
(46, 6),
(47, 6),
(48, 6),
(49, 6),
(50, 6),
(51, 6),
(52, 6),
(53, 6),
(54, 6),
(55, 6),
(56, 6),
(57, 6),
(58, 6),
(60, 6),
(62, 6),
(63, 6),
(64, 6),
(65, 6),
(66, 6),
(67, 6),
(68, 6),
(69, 6),
(70, 6),
(71, 6),
(72, 6),
(73, 6),
(74, 6),
(75, 6),
(76, 6),
(77, 6),
(78, 6),
(79, 6),
(80, 6),
(81, 6),
(82, 6),
(84, 6),
(85, 6),
(86, 6),
(87, 6),
(88, 6),
(89, 6),
(90, 6),
(91, 6),
(92, 6),
(93, 6),
(94, 6),
(95, 6),
(96, 6),
(97, 6),
(98, 6),
(99, 6),
(100, 6),
(101, 6),
(102, 6),
(103, 6),
(104, 6),
(105, 6),
(55, 7),
(67, 7),
(70, 7),
(111, 7),
(112, 7),
(113, 7),
(117, 7),
(121, 7),
(125, 7),
(129, 7),
(133, 7),
(137, 7),
(141, 7),
(142, 7),
(143, 7),
(155, 9),
(7, 12),
(12, 12),
(13, 12),
(16, 12),
(17, 12),
(18, 12),
(19, 12),
(28, 12),
(29, 12),
(30, 12),
(36, 12),
(39, 12),
(46, 12),
(47, 12),
(48, 12),
(52, 12),
(53, 12);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(191) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cash_register_id` int(11) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `biller_id` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `total_qty` double NOT NULL,
  `total_discount` double NOT NULL,
  `total_tax` double NOT NULL,
  `total_price` double NOT NULL,
  `grand_total` double NOT NULL,
  `order_tax_rate` double DEFAULT NULL,
  `order_tax` double DEFAULT NULL,
  `order_discount` double DEFAULT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `coupon_discount` double DEFAULT NULL,
  `shipping_cost` double DEFAULT NULL,
  `sale_status` int(11) NOT NULL,
  `payment_status` int(11) NOT NULL,
  `document` varchar(191) DEFAULT NULL,
  `paid_amount` double DEFAULT NULL,
  `sale_note` text DEFAULT NULL,
  `staff_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stations`
--

CREATE TABLE `stations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `region_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_counts`
--

CREATE TABLE `stock_counts` (
  `id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(191) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `category_id` varchar(191) DEFAULT NULL,
  `brand_id` varchar(191) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(191) NOT NULL,
  `initial_file` varchar(191) DEFAULT NULL,
  `final_file` varchar(191) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `is_adjusted` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `image` varchar(191) DEFAULT NULL,
  `company_name` varchar(191) NOT NULL,
  `vat_number` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `phone_number` varchar(191) NOT NULL,
  `address` varchar(191) NOT NULL,
  `city` varchar(191) NOT NULL,
  `state` varchar(191) DEFAULT NULL,
  `postal_code` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `rate` double NOT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `id` int(10) UNSIGNED NOT NULL,
  `reference_no` varchar(191) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `from_warehouse_id` int(11) NOT NULL,
  `to_warehouse_id` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `total_qty` double NOT NULL,
  `total_tax` double NOT NULL,
  `total_cost` double NOT NULL,
  `shipping_cost` double DEFAULT NULL,
  `grand_total` double NOT NULL,
  `document` varchar(191) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int(10) UNSIGNED NOT NULL,
  `unit_code` varchar(191) NOT NULL,
  `unit_name` varchar(191) NOT NULL,
  `base_unit` int(11) DEFAULT NULL,
  `operator` varchar(191) DEFAULT NULL,
  `operation_value` double DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `phone` varchar(191) NOT NULL,
  `company_name` varchar(191) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `biller_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sign` text DEFAULT NULL,
  `stemp` text DEFAULT NULL,
  `otp` varchar(191) DEFAULT NULL,
  `otp_time` datetime DEFAULT NULL,
  `otp_verify` tinyint(4) NOT NULL DEFAULT 1,
  `additional_phone` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `phone`, `company_name`, `role_id`, `biller_id`, `warehouse_id`, `is_active`, `is_deleted`, `created_at`, `updated_at`, `sign`, `stemp`, `otp`, `otp_time`, `otp_verify`, `additional_phone`) VALUES
(1, 'admin', 'admin@beyondcompanyltd.com', '$2y$10$3tlztfzPUTpgjm5xnz16E.Q70NHuq3KgLnwUlhjypAB3VBZ54AiwO', 'Va4QeGfML1jr8vTgtzdeCqaMGyKA08ZMKB3zCW9OOXygoGtP9VyAQ5QPoHA8', '+237675321739', 'CENTER', 1, NULL, NULL, 1, 0, '2018-06-02 03:24:15', '2025-07-03 03:21:47', 'tmpphpEQmySD.png', 'tmpphpZtUAi2.png', NULL, NULL, 1, '+923410060960'),
(3, 'dhiman da', 'dhiman@gmail.com', '$2y$10$Fef6vu5E67nm11hX7V5a2u1ThNCQ6n9DRCvRF9TD7stk.Pmt2R6O.', '5ehQM6JIfiQfROgTbB5let0Z93vjLHS7rd9QD5RPNgOxli3xdo7fykU7vtTt', '212', 'lioncoders', 1, NULL, NULL, 0, 1, '2018-06-13 22:00:31', '2020-11-05 07:06:51', NULL, NULL, NULL, NULL, 1, NULL),
(9, 'darell', 'darell@gmail.com', '$2y$10$7BjWuXjVydATNXC1orQaOuY7tOj91MOXkahBBZ59evrRkQ.bEuCpe', 'sGWyWKUZ7hv6aOJzTxy4qVDU6RpYhhLznzzZLQPVyv9KpIEqqgFRk6KjZ8RG', '34433', 'update by rehan', 4, NULL, 1, 0, 1, '2018-07-02 01:08:08', '2024-02-26 02:40:49', NULL, NULL, NULL, NULL, 1, NULL),
(10, 'abul', 'abul@alpha.com', '$2y$10$5zgB2OOMyNBNVAd.QOQIju5a9fhNnTqPx5H6s4oFlXhNiF6kXEsPq', 'x7HlttI5bM0vSKViqATaowHFJkLS3PHwfvl7iJdFl5Z1SsyUgWCVbLSgAoi0', '1234', 'anda', 1, NULL, NULL, 0, 1, '2018-09-07 23:44:48', '2022-03-31 22:17:45', NULL, NULL, NULL, NULL, 1, NULL),
(12, 'john', 'john@gmail.com', '$2y$10$P/pN2J/uyTYNzQy2kRqWwuSv7P2f6GE/ykBwtHdda7yci3XsfOKWe', 'O0f1WJBVjT5eKYl3Js5l1ixMMtoU6kqrH7hbHDx9I1UCcD9CmiSmCBzHbQZg', '10001', NULL, 4, 2, 2, 0, 1, '2018-12-30 00:48:37', '2019-03-06 04:59:49', NULL, NULL, NULL, NULL, 1, NULL),
(13, 'jjj', 'test@test.com', '$2y$10$/Qx3gHWYWUhlF1aPfzXaCeZA7fRzfSEyCIOnk/dcC4ejO8PsoaalG', NULL, '1213', NULL, 1, NULL, NULL, 0, 1, '2019-01-03 00:08:31', '2019-03-03 04:02:29', NULL, NULL, NULL, NULL, 1, NULL),
(21, 'modon', 'modon@gmail.com', '$2y$10$7VpoeGMkP8QCvL5zLwFW..6MYJ5MRumDLDoX.TTQtClS561rpFHY.', NULL, '2222', 'modon company', 5, NULL, NULL, 0, 1, '2020-11-13 07:12:08', '2022-03-31 22:17:45', NULL, NULL, NULL, NULL, 1, NULL),
(22, 'dhiman', 'dhiman@gmail.com', '$2y$10$3mPygsC6wwnDtw/Sg85IpuExtUhgaHx52Lwp7Rz0.FNfuFdfKVpRq', NULL, '+8801111111101', 'lioncoders', 5, NULL, NULL, 0, 1, '2020-11-15 06:14:58', '2022-03-31 22:17:45', NULL, NULL, NULL, NULL, 1, NULL),
(32, 'bb', 'bestb@gmail.com', '$2y$10$KnjDbJiTk4DGs/J1rjocru0r4MTXB..JCBNf6gztiAVgoTBptVyGC', NULL, '6653', 'Best Brothers', 6, 1, 1, 0, 1, '2022-03-31 22:39:50', '2022-04-21 00:32:10', NULL, NULL, NULL, NULL, 1, NULL),
(33, 'casandra', 'cass@gmail.com', '$2y$10$3x5EZsTwKeojLnO3P/S40eRsIOt9ySnv.FSAcL1rIqjDxIanpaLeu', NULL, '46363', 'Best Brothers', 4, 1, 1, 0, 1, '2022-03-31 22:53:11', '2022-04-21 00:32:10', NULL, NULL, NULL, NULL, 1, NULL),
(34, 'BE', 'estherb097@gmail.om', '$2y$10$jkJ/B88PtJf/l9yb6RPz5uBkligVP8rbvIKoMz5j9uj7kBuQXWohi', NULL, '672684865', NULL, 4, 1, 1, 0, 1, '2022-04-07 19:27:43', '2022-04-21 00:32:10', NULL, NULL, NULL, NULL, 1, NULL),
(35, 'DD', 'desmonddinyuy@gmail.com', '$2y$10$k1w/wbXHCoQ.VshlRpDrIuodLKYjDrrz4TPZXbcvxH2aL/0CPsnw.', NULL, '651152477', NULL, 4, 1, 1, 0, 1, '2022-04-07 19:32:09', '2022-04-21 00:32:10', NULL, NULL, NULL, NULL, 1, NULL),
(36, 'DM', 'maisondyfermier@gmail.com', '$2y$10$RgeXXUFOasKle79nY7efieuFZdrnn0RVxgKTc2G/QSmXvlUBnGj1a', NULL, '678643453', NULL, 4, 1, 1, 0, 1, '2022-04-07 19:36:55', '2022-04-21 00:32:10', NULL, NULL, NULL, NULL, 1, NULL),
(37, 'KR', 'RomanusKwasi@gmail.com', '$2y$10$.wRj7Hd09enwfKKqQCfHvObvcdK.uAnrGwai5jr6qIO/0mGWJpo0K', NULL, '651210251', NULL, 4, 1, 1, 0, 1, '2022-04-07 19:40:38', '2022-04-21 00:32:10', NULL, NULL, NULL, NULL, 1, NULL),
(38, 'CC', 'chucasandra15@gmail.com', '$2y$10$MEAmjwkVUIyPVev8AP1gqeeqQD.w.yiVQrmXnK8OGpyHklPkhC86K', NULL, '673540124', NULL, 4, 1, 1, 0, 1, '2022-04-07 19:45:49', '2022-04-21 00:32:10', NULL, NULL, NULL, NULL, 1, NULL),
(39, 'MM', 'Mandamirabel707@gmail.com', '$2y$10$7xXoUBX93MQ333AUaCfOCugNNHjEXLWArLHxpy66gw8qPaEPzHgCC', NULL, '670383748', NULL, 4, 1, 1, 0, 1, '2022-04-07 19:54:17', '2022-06-20 17:19:54', NULL, NULL, NULL, NULL, 1, NULL),
(40, 'NS', 'SaiduNchimining@gmail.com', '$2y$10$z5jeaRKabi4T3tll90ZTWOFJ61z.KaCx8c.KSNyBG7nTgp6PFEOj.', NULL, '681117378', NULL, 4, 1, 1, 0, 1, '2022-04-07 20:06:10', '2022-06-20 17:19:54', NULL, NULL, NULL, NULL, 1, NULL),
(41, 'ngala', 'chantalngala03@gmail.com', '$2y$10$owtQXrgg52m2bfgRpNYOYeCmJjP52sg0RcDf4OMG.sPoemsQBHcEO', NULL, '+237674379252', 'Beyond Company Ltd', 1, NULL, NULL, 0, 1, '2022-06-20 17:18:15', '2024-02-25 02:08:48', NULL, NULL, NULL, NULL, 1, NULL),
(42, 'masango updated', 'masangocaleb@gmail.com', '$2y$10$ok3uhkzVyq4jBOMwiXdiAO4RE/OA92/y9h/..s6QbXxvJlqAUIm1G', NULL, '+237680175716', 'Beyond Comapny Limited', 4, 1, 1, 0, 1, '2022-06-20 17:25:04', '2024-02-25 02:08:48', NULL, NULL, NULL, NULL, 1, NULL),
(43, 'deleted', 'sales@beyondcompanyltd.com', 'deleted', NULL, '+447534661535', 'Beyond Company Limited', 1, NULL, NULL, 0, 1, '2022-06-21 22:32:55', '2023-09-14 13:14:04', NULL, NULL, NULL, NULL, 1, NULL),
(44, 'mbole', 'mbole.willort@beyondcompanyltd.com', '$2y$10$VW9m6Trmm5DPLC/w8t7icOfXtWT8G0y75BVp5G/ZarqJa6KYD17my', NULL, '+237670778788', 'Beyond Company Limited', 2, NULL, NULL, 0, 1, '2022-06-26 19:18:05', '2024-02-25 02:08:23', NULL, NULL, NULL, NULL, 1, NULL),
(46, 'amos', 'amosdorko@gmail.com', '$2y$10$cevmGwGdfLupIgHZWVDbdOEF6AfMwbtzZQcSLQ7vF5LXe0M9Ljv2e', NULL, '+237679257469', NULL, 4, 1, 1, 0, 1, '2022-07-16 17:12:28', '2024-02-25 02:08:48', NULL, NULL, NULL, NULL, 1, NULL),
(48, 'deleted', 'dc@gmail.com', 'deleted', NULL, '929733', 'DC', 5, NULL, NULL, 0, 1, '2023-01-16 15:21:12', '2023-09-14 13:14:43', NULL, NULL, NULL, NULL, 1, NULL),
(49, 'amah', 'amah@beyondcompanyltd.com', '$2y$10$PekAN8FIZD6jsr2kcjes4ujb/a1P8K0UgW0ePN8BOm9vYVVKu/ov2', NULL, '+237695724621', 'BEYOND COMPANY LTD', 1, NULL, NULL, 0, 1, '2023-01-30 18:50:50', '2024-02-25 02:08:48', NULL, NULL, NULL, NULL, 1, NULL),
(50, 'deleted', 'info@beyondcompanyltd.com', 'deleted', NULL, '003', NULL, 12, 1, 2, 0, 1, '2023-02-01 15:31:03', '2025-02-06 14:06:15', NULL, NULL, NULL, NULL, 1, NULL),
(51, 'prophetic', 'lian@beyondcompanyltd.com', '$2y$10$hyid09RpXmLCkkiSIgTT7eAckuEmf4ognFNpzm5e7dz4iCruGF3l.', NULL, '+237697470711', 'Beyond Company Ltd', 2, 1, 1, 0, 1, '2023-02-05 19:47:41', '2024-02-25 02:09:11', NULL, NULL, NULL, NULL, 1, NULL),
(52, 'aurelie', 'aurelieloredana58@gmail.com', '$2y$10$UflQJt78r5pAYgOywB3K2eOVGDjpC/4W4vHAc0FKLc7FKGkMVMB4O', NULL, '+237676030520', 'Beyond Company Ltd', 6, 1, 1, 0, 1, '2023-08-11 13:53:25', '2024-02-25 02:09:11', NULL, NULL, NULL, NULL, 1, NULL),
(53, 'rehan', 'rehanfaby36@gmail.com', '$2y$10$nWSncssOkJXt6wX7Cn31uuxJTrQZpxebb3gdfL9Ma0RX/WtliSzwy', NULL, '923410060960', 'faby developers', 1, NULL, NULL, 0, 1, '2023-09-19 21:14:58', '2024-02-25 02:09:11', 'tmpphpB1LhWz.png', 'tmpphpj2lIib.png', NULL, NULL, 1, NULL),
(54, 'deleted', 'macfebefuhshu@gmail.com', 'deleted', NULL, '237679677472', 'Beyond Company Limited', 2, NULL, NULL, 0, 1, '2024-02-01 14:07:12', '2024-02-01 14:09:01', NULL, NULL, NULL, NULL, 1, '237'),
(55, 'Ngum Macfebe', 'macfebefuhshu@gmail.com', '$2y$10$PeicsQqnt9ex/p65RHe8POTvE5Tm99t0pA.cSu1PstRTlcU5MTTGO', NULL, '237679677472', 'Beyond Company Limited', 2, NULL, NULL, 0, 1, '2024-02-01 14:07:19', '2024-02-25 02:09:11', NULL, NULL, NULL, NULL, 1, '237'),
(56, 'deleted', 'carlos1@gmail.com', 'deleted', NULL, '12312345', 'GAMAX', 12, 1, 3, 0, 1, '2024-02-25 02:21:52', '2025-02-06 14:06:35', NULL, NULL, NULL, NULL, 1, NULL),
(57, 'deleted', 'rehanfaby36@gmail.com', 'deleted', NULL, '03410060960', NULL, 2, NULL, NULL, 0, 1, '2025-01-21 05:12:42', '2025-02-06 14:05:34', NULL, NULL, NULL, NULL, 1, NULL),
(58, 'Amah', 'info@amah.com', '$2y$10$gSJWfWl7sExuIMtxkU/Thee9YtI3o6grHyHdbT8uJtreHycZ96XWq', NULL, '00093', NULL, 2, NULL, NULL, 1, 0, '2025-02-06 14:00:32', '2025-02-06 14:00:32', NULL, NULL, NULL, NULL, 1, NULL),
(59, 'mulema', 'info@mulemagc.com', '$2y$10$IRpK72hMOtvr4RM4Pjn6BOSqFznbKJskotoUuW3G7o0SRpya2CQNS', NULL, '+16132622362', 'Mulema Gospel Competition', 2, NULL, NULL, 1, 0, '2025-02-06 14:04:58', '2025-02-06 14:04:58', NULL, NULL, NULL, NULL, 1, NULL),
(60, '237675321739', 'user@gmail.com', '$2y$10$0Aau1ujaFM5.dzgKHr57NeZGV4teFLyobpyWcuYj8X1lNoSHdBale', NULL, '237675321739', NULL, 3, NULL, NULL, 1, 0, '2025-02-06 14:10:09', '2025-02-06 14:10:09', NULL, NULL, NULL, NULL, 1, NULL),
(61, '+237671309247', 'user@gmail.com', '$2y$10$g2ZHfnQekwI5weLiSkbH9u9Bd6Kd.qp/Opj70I0r7f2At40SJi.8W', NULL, '+237671309247', NULL, 3, NULL, NULL, 1, 0, '2025-02-07 13:00:11', '2025-02-07 13:00:11', NULL, NULL, NULL, NULL, 1, NULL),
(62, '237671309247', 'user@gmail.com', '$2y$10$dqBLCNuZu6AOuE33BTQs0ezhMSgvbljIIagyGbnG4WXmTDXOJ5tYO', NULL, '237671309247', NULL, 3, NULL, NULL, 1, 0, '2025-02-07 13:11:06', '2025-02-07 13:11:06', NULL, NULL, NULL, NULL, 1, NULL),
(64, 'rehan mansoor', 'rehanfaby36@gmail.com', '$2y$10$TXD6e7..8WQ6FLEDhR8l4OK6xEcYyw0htJxusw1Y3wo0TAxnyhslm', NULL, '923410060960', NULL, 2, NULL, NULL, 1, 0, '2025-02-08 10:12:32', '2025-02-08 10:14:23', NULL, NULL, NULL, NULL, 1, NULL),
(65, 'deleted', 'user@gmail.com', 'deleted', NULL, '+237693732176', NULL, 3, NULL, NULL, 0, 1, '2025-02-11 10:18:57', '2025-02-11 11:10:31', NULL, NULL, NULL, NULL, 1, NULL),
(66, 'deleted', 'rosy@mulemagc.com', 'deleted', NULL, '+237693732176', NULL, 2, NULL, NULL, 0, 1, '2025-02-11 10:30:48', '2025-02-11 11:10:24', NULL, NULL, NULL, NULL, 1, NULL),
(67, 'deleted', 'user@gmail.com', 'deleted', NULL, '237693732176', NULL, 3, NULL, NULL, 0, 1, '2025-02-11 10:33:38', '2025-02-11 11:10:11', NULL, NULL, NULL, NULL, 1, NULL),
(68, 'deleted', 'toure@mulemagc.com', 'deleted', NULL, '+237680533612', NULL, 2, NULL, NULL, 0, 1, '2025-02-11 10:51:14', '2025-02-11 11:02:17', NULL, NULL, NULL, NULL, 1, NULL),
(69, 'deleted', 'toure@mulemagc.com', 'deleted', NULL, '+237680533612', 'Mulema Gospel Competition', 1, NULL, NULL, 0, 1, '2025-02-11 11:03:33', '2025-02-11 11:11:10', NULL, NULL, NULL, NULL, 1, NULL),
(71, 'Glorya', 'glorya@mulemagc.com', '$2y$10$ueINI8O3VS7itOzkOS5i6enHbxRypR/63dC1LTsEYuOFIHgk/YVTi', NULL, '699883204', NULL, 2, NULL, NULL, 1, 0, '2025-02-11 11:15:44', '2025-02-11 11:15:44', NULL, NULL, NULL, NULL, 1, NULL),
(72, '237693732176', 'user@gmail.com', '$2y$10$Lnv8H0zHgeG1/zINYWGlC.JmM8BmCm3h5PTdmbGVVmjGGU.yYB9um', NULL, '237693732176', NULL, 3, NULL, NULL, 1, 0, '2025-02-11 11:17:20', '2025-02-11 11:17:20', NULL, NULL, NULL, NULL, 1, NULL),
(73, '16132622362', 'user@gmail.com', '$2y$10$xRXCfau/BDJlyCvyLDCLgONQhWPEAyMomy4bfEJt39Z53cwpfJ0km', NULL, '16132622362', NULL, 3, NULL, NULL, 1, 0, '2025-02-12 21:47:14', '2025-02-12 21:47:14', NULL, NULL, NULL, NULL, 1, NULL),
(74, '+237670294100', 'user@gmail.com', '$2y$10$Cfoz7.Dzz6RZ0xaUC7KFqOTSIElI0CliVJBZ9j4pHrIwwVXajRBCK', NULL, '+237670294100', NULL, 3, NULL, NULL, 1, 0, '2025-02-13 09:37:18', '2025-02-13 09:37:18', NULL, NULL, NULL, NULL, 1, NULL),
(78, '+237670108650', 'user@gmail.com', '$2y$10$Nd9j3Mixn356/2uVFnF5IeiE.emKnlVOpg8.2BzXZixU4asMzuC66', NULL, '+237670108650', NULL, 3, NULL, NULL, 1, 0, '2025-02-14 13:11:23', '2025-02-14 13:11:23', NULL, NULL, NULL, NULL, 1, NULL),
(79, '+237', 'user@gmail.com', '$2y$10$0cNZf7JBevRwgQrCbWOlKuAF5aJnPEYfshkkUzxiei7lHo7J1yg7K', NULL, '+237', NULL, 3, NULL, NULL, 1, 0, '2025-02-26 14:56:01', '2025-02-26 14:56:01', NULL, NULL, NULL, NULL, 1, NULL),
(80, '+2376575555109', 'user@gmail.com', '$2y$10$CPCmaVZkYnbYtwKT8Sek4eU.zPd2UcuwKl88owYXTXBy2TKW7M2bK', NULL, '+2376575555109', NULL, 3, NULL, NULL, 1, 0, '2025-02-26 15:07:50', '2025-02-26 15:07:50', NULL, NULL, NULL, NULL, 1, NULL),
(81, '+237679815171', 'user@gmail.com', '$2y$10$pDlugQLKzTDaxk23FJDatO.Y6XeFLfloTsFGNn3jRJ.8LHF4qiQ2q', NULL, '+237679815171', NULL, 3, NULL, NULL, 1, 0, '2025-03-09 17:39:55', '2025-03-09 17:39:55', NULL, NULL, NULL, NULL, 1, NULL),
(82, 'Francislot', 'holli.ngsworthwalkley@gmail.com', '$2y$10$Vu7ZROg24GYkjpzFAxkngOn50zsy9d.HRFolijBlGCNQuAvOa17O6', NULL, '82845771165', NULL, 2, NULL, NULL, 1, 0, '2025-03-10 03:02:58', '2025-03-10 03:02:59', NULL, NULL, NULL, NULL, 1, NULL),
(83, 'Kalvoksou Tiyoum Sylvain', 'kalvasylva@gmail.com', '$2y$10$c.kxaftdD3BpAsQHoNdD3epxi1wFbAiipy74hd9VMJDwHdt3hA./y', NULL, '+237674696535', NULL, 2, NULL, NULL, 1, 0, '2025-04-02 09:21:50', '2025-04-02 09:21:50', NULL, NULL, NULL, NULL, 1, NULL),
(84, 'Lehmoug Bougang Lydia', 'fidelesifada@gmail.com', '$2y$10$kDk4DRtaIGelCk1prEHzaO5qFs00xYf1PYBzy1o.X/lPBkVEMG0Wu', NULL, '+237655289825', NULL, 2, NULL, NULL, 1, 0, '2025-04-02 09:25:19', '2025-04-02 09:25:19', NULL, NULL, NULL, NULL, 1, NULL),
(85, 'Waksou Benjamin Don de Dieu', 'waksoudondedieu@gmail.com', '$2y$10$IWcYhbTLyFypnCSa1KMTA..LRrCVxWQni4Pz2Vyz.r8LHht8StFAO', NULL, '237692751813', NULL, 2, NULL, NULL, 1, 0, '2025-04-02 09:26:13', '2025-04-02 09:26:13', NULL, NULL, NULL, NULL, 1, NULL),
(86, 'Prince Alliance Ahidjo', 'danielahidjo803@gmail.com', '$2y$10$HBWnsrlGUk.jGK5jekbrQ.TzEImWrnC89pCngDOL6wnp2xVXOmA8i', NULL, '237691998439', NULL, 2, NULL, NULL, 1, 0, '2025-04-02 09:26:53', '2025-04-02 09:26:53', NULL, NULL, NULL, NULL, 1, NULL),
(87, 'Moukam Ornella:', 'moukamorne@gmail.com', '$2y$10$57I.NunNbCXtpIh/e836RusrH7m3XJRnG3nkHtSCpsgZqeFEdqT.O', NULL, '237656712853', NULL, 2, NULL, NULL, 1, 0, '2025-04-02 09:27:45', '2025-04-02 09:27:45', NULL, NULL, NULL, NULL, 1, NULL),
(88, 'DIYE MEDI jOYCE', 'diyemedijoyce@gmail.com', '$2y$10$Dt8MlZvYcElO6L2/K6I1duZ/XTizxLPz2iNWJodlVUtAb/qSiEoKq', NULL, '237692140147', NULL, 2, NULL, NULL, 1, 0, '2025-04-02 19:44:48', '2025-04-02 19:44:48', NULL, NULL, NULL, NULL, 1, NULL),
(89, 'Hope Onyinyechi Ibe', 'ibehope608@gmail.com', '$2y$10$fgSQWlOqiGS4OSEbWU0gmeAmQJ9N7tuSEaAHVH3cZvxSSVYe.rfIC', NULL, '237693198215', NULL, 2, NULL, NULL, 1, 0, '2025-04-02 19:45:51', '2025-04-02 19:45:51', NULL, NULL, NULL, NULL, 1, NULL),
(90, 'MTIMEM MATIBEY LESLIE', 'litiammb0102@gmail.com', '$2y$10$D/pXxWD6rTI2dFo3L6QCk.Rk1BmHRY858JnmOcm5FErUaxAisqP2e', NULL, '237690928735', NULL, 2, NULL, NULL, 1, 0, '2025-04-02 19:46:50', '2025-04-02 19:46:50', NULL, NULL, NULL, NULL, 1, NULL),
(91, 'Malavaye Julie', 'jesusconsole43@gmail.com', '$2y$10$nDYUllIhFqxgcFbUUs2tU.aH6QXDmKd2UCQ/GOOcP5iUscDyETrLe', NULL, '237657053327', NULL, 2, NULL, NULL, 1, 0, '2025-04-02 19:47:41', '2025-04-02 19:47:41', NULL, NULL, NULL, NULL, 1, NULL),
(92, 'Sangbet hermine sonia', 'sangbetsonia@gmail.com', '$2y$10$MpaqhocMRXX4dA5fMsXrv.obf5OHEogWEUkEUVjBRl/BNRIWPPZ5i', NULL, '237698093504', NULL, 2, NULL, NULL, 1, 0, '2025-04-02 19:48:37', '2025-04-02 19:48:37', NULL, NULL, NULL, NULL, 1, NULL),
(94, 'Alice', 'gtfiaafk@dont-reply.me', '$2y$10$fnNe/th03a.zssAWE7Xo2e3YM9E5r9C7VwlO1VZawOISBDtdNvWXe', NULL, '+237', NULL, 3, NULL, NULL, 1, 0, '2025-04-17 02:41:06', '2025-04-17 02:41:06', NULL, NULL, NULL, NULL, 1, NULL),
(95, 'Dabe Unity Medu', 'dabeunity@gmail.com', '$2y$10$YRRO5Nw.ToL068gQDtEWH.YZ/buWS6MAX3.ykXIfnhxq5MDrAG5BW', NULL, '+237657431636', NULL, 2, NULL, NULL, 1, 0, '2025-05-26 15:42:26', '2025-05-26 15:42:26', NULL, NULL, NULL, NULL, 1, NULL),
(96, 'Bille Ondrade Paule Astride', 'billondrare@gmail.com', '$2y$10$DREw59NG3EJ5RKj74VIhtOf2t6yaEowe4fxttesofhzMGBTx6BZsq', NULL, '+237655694466', NULL, 2, NULL, NULL, 1, 0, '2025-05-26 15:44:47', '2025-05-26 15:44:47', NULL, NULL, NULL, NULL, 1, NULL),
(97, 'Ngan Mayack Arnaud Claude', 'nganmayack@gmail.com', '$2y$10$WKxoKXX5vqo8yVm0wOF/4u9NP5x07qBT0ZKWBocruMxLtJh7RkSZy', NULL, '+237655618182', NULL, 2, NULL, NULL, 1, 0, '2025-05-26 15:47:30', '2025-05-26 15:47:30', NULL, NULL, NULL, NULL, 1, NULL),
(98, 'Akoumba Jenny', 'akoumbajenny@gmail.com', '$2y$10$pJwtCfYgQYTVS1PhkBUv..ikzvaliYR94qCm36PVyl2k.yoy5HFtK', NULL, '+237659571834', NULL, 2, NULL, NULL, 1, 0, '2025-05-26 15:48:16', '2025-05-26 15:48:16', NULL, NULL, NULL, NULL, 1, NULL),
(99, 'Christelle Fabiola Bidjeg', 'christellfabiola@gmail.com', '$2y$10$2sATjFjtV7vrMVgFi/Ax7e/cypEfA0gCYZvSl0m63cCj9VVB93nZe', NULL, '+237698249853', NULL, 2, NULL, NULL, 1, 0, '2025-05-26 15:48:58', '2025-05-26 15:48:58', NULL, NULL, NULL, NULL, 1, NULL),
(100, 'Lois Sibefo', 'loissibefor@gmail.com', '$2y$10$xxxhGVqH0ZuzdF7NDeW7UebwomjC2/QoJVCmWY1Xw2frDwak9lzTG', NULL, '+237659585456', NULL, 2, NULL, NULL, 1, 0, '2025-05-26 15:50:02', '2025-05-26 15:50:02', NULL, NULL, NULL, NULL, 1, NULL),
(101, 'Bangue Sian Obase Bonheur', 'banguesianobase@gmail.com', '$2y$10$tmtLfP2vY/SIuT/ZjAem8OKIs5eb9n1.d9ZVXf/Kxw5vpI3C.bOeC', NULL, '+237655283397', NULL, 2, NULL, NULL, 1, 0, '2025-05-26 15:51:05', '2025-05-26 15:51:05', NULL, NULL, NULL, NULL, 1, NULL),
(102, 'Grace Eli Glory', 'graceeliglory@gmail.com', '$2y$10$d6ZaLMaC2OwKy5BjVzEkA.j/3LXgTLmDFPtxhtPkY.Z02JeQdzMNG', NULL, '+237691633111', NULL, 2, NULL, NULL, 1, 0, '2025-05-26 15:52:11', '2025-05-26 15:52:11', NULL, NULL, NULL, NULL, 1, NULL),
(103, 'Nick Thomas D\'aquin TANG', 'nickthomas@gmail.com', '$2y$10$N7BXn3DBgIpVHz.2Mzoev.epmcuADaN5MrBBxGkG1tAavWeAI.5uq', NULL, '+237659222507', NULL, 2, NULL, NULL, 1, 0, '2025-05-26 15:53:07', '2025-05-26 15:53:07', NULL, NULL, NULL, NULL, 1, NULL),
(105, 'Kogningbo-Te Lackry Athanase omega', 'kogningbotelackry@gmail.com', '$2y$10$cLFL9hiN5nGw.wxl.DPNvOqiuwDt3OPLBE8RyBogFtEWVE0wRtk.u', NULL, '+23769948465', NULL, 2, NULL, NULL, 1, 0, '2025-05-26 15:55:55', '2025-05-26 15:55:55', NULL, NULL, NULL, NULL, 1, NULL),
(106, 'Emmanuel Bitoki Joseph', 'bejbitoki2@gmail.com', '$2y$10$stMXv2QlaYmTuWxo3HPgGu2..WHF6ruv1NBO9quRmtOlawQKj93T2', NULL, '+237658994746', NULL, 2, NULL, NULL, 1, 0, '2025-05-31 13:42:37', '2025-05-31 13:42:37', NULL, NULL, NULL, NULL, 1, NULL),
(107, 'Bollo Sanda L', 'sandalena9@gmail.com', '$2y$10$eKS9oODykvx/FkKt7pQqO..t4c8qHZTKquyEv8NMCz.t1HTFg38a6', NULL, '+237659332014', NULL, 2, NULL, NULL, 1, 0, '2025-05-31 13:43:58', '2025-05-31 13:43:58', NULL, NULL, NULL, NULL, 1, NULL),
(108, 'Yonkeu Elizabeth', 'yelisabethabigail@gmail.com', '$2y$10$aU1IpcYCLFZFYmPq4pqPDejzBGwG3H874h1h90VQyOJk8Wfgwbhrm', NULL, '+237657583529', NULL, 2, NULL, NULL, 1, 0, '2025-05-31 13:57:20', '2025-05-31 13:57:20', NULL, NULL, NULL, NULL, 1, NULL),
(109, 'Iya Pomna Grace', 'graceiya3@gmail.com', '$2y$10$XQZrAE872ELy.QkEZy6ZOOeNYySDD61UWO5CuEZSJkrWBYFE2r65q', NULL, '+237694341690', NULL, 2, NULL, NULL, 1, 0, '2025-05-31 13:59:01', '2025-05-31 13:59:01', NULL, NULL, NULL, NULL, 1, NULL),
(110, 'Ngaoul Benoit Appolinaire', 'ngaoulben@gmail.com', '$2y$10$PXPlFBj8gG3TwzmaZEdobO8IG9l0M6k8Zm5ZPAs1s6loGrqumJL0K', NULL, '+237692448861', NULL, 2, NULL, NULL, 1, 0, '2025-05-31 14:00:39', '2025-05-31 14:00:39', NULL, NULL, NULL, NULL, 1, NULL),
(111, 'Emmanuel Maballi', 'emmanuelmaballi@gmail.com', '$2y$10$AdJIAUh14NZhxUo9DhMBseD2DwGJp03iiqrBJTFHyJkqvAL4gYOdG', NULL, '+237658544392', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:10:02', '2025-06-13 14:10:02', NULL, NULL, NULL, NULL, 1, NULL),
(112, 'Essomba Abihana Jemima Victoire', 'vickydjems2000@gmail.com', '$2y$10$IzDYdmx8aR42tbkxH2o8au0507NScb/dIfpPFbp4nqKZ6u7hO1Y9y', NULL, '+237695806291', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:15:43', '2025-07-01 14:31:36', NULL, NULL, NULL, NULL, 1, NULL),
(113, 'Haurel Bertrand Ndelle Nzanjo\'o', 'bertrandndelle9@icloud.com', '$2y$10$.Hq.nOwDAkf0daiyh3i36O9tWZtwJ49fZFHi59cdB8WmoCm.YbV7m', NULL, '+237656244400', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:17:07', '2025-06-13 14:17:07', NULL, NULL, NULL, NULL, 1, NULL),
(114, 'Onana Hubert Daniel', 'hubertdanielnlo@gmail.com', '$2y$10$P0j9GLuiNVvmeC9aFn3wd.ZSEK9CvL3J47UjOuPCU.D1QDZ1WQDX.', NULL, '+237697276544', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:18:08', '2025-07-01 12:42:30', NULL, NULL, NULL, NULL, 1, NULL),
(115, 'Axel Bipan', 'bipanaxel@gmail.com', '$2y$10$cvxqcCS1VooJI9XV0TP0iO87e6VKqcmC99fFd2vin.1wa8CAvFsyu', NULL, '+237686502524', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:20:07', '2025-06-13 14:20:07', NULL, NULL, NULL, NULL, 1, NULL),
(116, 'Ngo Nguibe Lea Excelle', 'excellelea5@gmail.com', '$2y$10$5bDYTRvBsHIETWKZVb9bS./stneXR9yGEBDuZ4m22JYt3ypIgowdi', NULL, '+237698621194', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:21:07', '2025-06-13 14:21:07', NULL, NULL, NULL, NULL, 1, NULL),
(117, 'Evodie Elia', 'evodiemboutou@gmail.com', '$2y$10$dXsJR0kZJoCee8kSZlG4b.BPLztv1yJJRvfHqxGzdhlm/TsQaSmQq', NULL, '+237699298599', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:21:51', '2025-06-13 14:21:51', NULL, NULL, NULL, NULL, 1, NULL),
(118, 'Bayombi Mardochée', 'jamesmardocheebayombi@gmail.com', '$2y$10$HrLr1dGeTb9ZOn0/wSkqyuyedVLKhrcIQ66WpuE7/EYCf6AfqQkA2', NULL, '+237652057153', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:22:38', '2025-06-13 14:22:38', NULL, NULL, NULL, NULL, 1, NULL),
(119, 'Kwekem Mboudom Tifen', 'tifenkwekem@gmail.com', '$2y$10$C4jTjdTN/6QeybzL4tiDwu/tRFskH3q6x2CkfgMOdDwwAdwsl6yf.', NULL, '+237656658214', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:24:09', '2025-06-13 15:40:44', NULL, NULL, NULL, NULL, 1, NULL),
(120, 'Ellé Rose Pentecote', 'pentecoteelle@gmail.com', '$2y$10$p4Y/GvyL6TcV2ox7dUieEO/zcHEf3zXQIZz2aTeHr5RiBgd.vm/aO', NULL, '+237658677471', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:24:56', '2025-06-13 14:24:56', NULL, NULL, NULL, NULL, 1, NULL),
(121, 'DIMEGNY SALOMON', 'dimegnysalomon@gmail.com', '$2y$10$bGGB.3sSMy.HMgMzTh99/OiY7tpakZttQXxWt5bbqC8asPIqpFMH.', NULL, '+237693634251', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:25:37', '2025-06-13 14:25:37', NULL, NULL, NULL, NULL, 1, NULL),
(122, 'Billy Zengue', 'billy.zengue@gmail.com', '$2y$10$NF8Tn/toA4q1zlWJYFQnP.0F9QF9grqE2jA7.6YTbYiDY0kCk.NyC', NULL, '+237655690748', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:26:15', '2025-06-13 14:26:15', NULL, NULL, NULL, NULL, 1, NULL),
(123, 'Perez Kadji', 'perezkanme@gmail.com', '$2y$10$WkJKRYZzDURfkOtbXDaQ1uM0y6ABXBhj6r1g6vMzLQksajJSLnT6u', NULL, '+237694118971', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:26:45', '2025-06-13 14:26:45', NULL, NULL, NULL, NULL, 1, NULL),
(124, 'Onguene mekana Glaube', 'glaubedelphe@gmail.com', '$2y$10$Pmaa1y3TBp1M4j4vLO8BAOdnfX3.idSCbQK.ApTzmgBaKzeJrnW/m', NULL, '+237691559259', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:28:02', '2025-06-13 14:28:02', NULL, NULL, NULL, NULL, 1, NULL),
(125, 'Adiyi Barbara Ingrid', 'adoratrice.ingrid@gmail.com', '$2y$10$NgrquThoybjbcdk03udT3eSK0QAmxmbOQAOWLq8qS.hQdsm3KnJdq', NULL, '+237691448025', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:28:41', '2025-06-13 14:28:41', NULL, NULL, NULL, NULL, 1, NULL),
(126, 'Albert-Régis Pondy Niobe', 'xeralbert@gmail.com', '$2y$10$2wKge8wnOn45ohhI7pV8Iu0dAS2meUFzeElGfFoWRFY5liax2fQO.', NULL, '+237640366667', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:29:26', '2025-06-13 14:29:26', NULL, NULL, NULL, NULL, 1, NULL),
(127, 'Gams gamaliel', 'gamsmatadi2@gmail.com', '$2y$10$kexeYh73ORHfySE/i0gYreFBnIYrwKcV16EjBQI3f6MIbbfmaj2dm', NULL, '+237695434449', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:31:30', '2025-06-13 14:31:30', NULL, NULL, NULL, NULL, 1, NULL),
(128, 'Eboto Essong Raymonde', 'raymondeeboto3@gmail.com', '$2y$10$G2p4IEkGNNHAqEYKSXL3EOsbWrB0ORMHEfyIVUDCLGT8LlXW2BaH2', NULL, '+237688377173', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:32:10', '2025-06-13 14:32:10', NULL, NULL, NULL, NULL, 1, NULL),
(129, 'Laurraine Ndieupe', 'lauryndieups@gmail.com', '$2y$10$RgVUVGZDbsEkpylgFf.COuYQz4byONs0jaZcl8y6v0reuYFMgmlo2', NULL, '+237694884653', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:32:55', '2025-06-13 14:32:55', NULL, NULL, NULL, NULL, 1, NULL),
(130, 'JOHAN', 'edoasalomon4@gmail.com', '$2y$10$U/A4rl3goEK1F4C20AeKTOi1nu1OS.1Is9hcF7WwtIRyaJ77BQc46', NULL, '+237675782453', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:33:36', '2025-06-13 14:33:36', NULL, NULL, NULL, NULL, 1, NULL),
(131, 'Lauretta Mah', 'annakhelane@gmail.com', '$2y$10$fpR7GlepmlSAaYWkQe6rBe9cXp5xKePo0AJpx/vK0DNPkVvr9zxHC', NULL, '+237656766648', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:34:24', '2025-06-13 14:34:24', NULL, NULL, NULL, NULL, 1, NULL),
(132, 'Mfegue Benedicta Lydia', 'benedictanguijdoi@gmail.com', '$2y$10$xuQa1taDxo.EvL81OateOeouMkBeACJON26K2T7lSCASRXoTZ6YEu', NULL, '+237690799542', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:35:06', '2025-06-13 14:35:06', NULL, NULL, NULL, NULL, 1, NULL),
(133, 'Alphir Omgba', 'delgadoalida338@gmail.com', '$2y$10$OJvpJ.9AQ4CyllHWxx8I2ulON6jbagZD/R51I3A0.T9YAzqln1LZu', NULL, '+237653070097', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:39:46', '2025-06-13 14:39:46', NULL, NULL, NULL, NULL, 1, NULL),
(134, 'MVOGO AZIELLE', 'aziellemvogo@icloud.com', '$2y$10$VTvCV3bYyLeXgn.7KKWaPu/MaXE5M3XJ6358irGyaBLh7t3QhTj/W', NULL, '+237693017318', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:43:32', '2025-06-13 14:43:32', NULL, NULL, NULL, NULL, 1, NULL),
(135, 'Forpeteuh Glovise forsoh', 'gloviseforsoh@gmail.com', '$2y$10$x99leHv//p7Ybp6MTXEiWOF1YvBienP3aqk29Vu65pMsLEbN/DsMW', NULL, '+237681542224', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:44:20', '2025-06-13 14:44:20', NULL, NULL, NULL, NULL, 1, NULL),
(136, 'Bessem Doreen kange', 'bessdoreen2014@gmail.com', '$2y$10$UbQIzOwcoTZB7HciAoVuUOURRWJZSz/46X6xCsXvdKYvyf6g7Jjce', NULL, '+23764678045', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:45:05', '2025-06-13 14:45:05', NULL, NULL, NULL, NULL, 1, NULL),
(137, 'Joshua Orji', 'joshuaorji20@gmail.com', '$2y$10$mNLEs2CZOb.btLAO15gd6u1JIPbtBwD2Mm7Fo/WIFp6ZDib4O1B.S', NULL, '+237681112873', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:45:38', '2025-06-13 14:45:38', NULL, NULL, NULL, NULL, 1, NULL),
(138, 'Teneng Fabiola Rina Ateh', 'tenengfabiolarina@gmail.com', '$2y$10$shJ9J8uJCIHaNw7yWOgDc.yijF7iKmmI/up35v9f.FsD0n/tFUsO2', NULL, '+237653483073', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:46:12', '2025-07-01 17:02:01', NULL, NULL, NULL, NULL, 1, NULL),
(139, 'Azemchop Nwednjong Dean', 'deannwednjong@gmail.com', '$2y$10$k755n33eTvhbwZsHjhkqDeH7HoMgCe9VzKjTax.HK4yiboP9hbJtC', NULL, '+237681005706', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:46:56', '2025-06-13 14:46:56', NULL, NULL, NULL, NULL, 1, NULL),
(140, 'BISANG VANESSA', 'Vanessaawah688@gmail.com', '$2y$10$Ui2f1fBGKtly0oqx20X7ruj4fvotdd5QvVrVMtIE9/31fnFAbe/mK', NULL, '+2376538616044', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:54:51', '2025-06-13 14:54:51', NULL, NULL, NULL, NULL, 1, NULL),
(141, 'Njilah Joycelyne N.', 'joynjilah95@gmail.com', '$2y$10$H74sWTWxzZ0pi65kMQI/KuORYRW2Ud.Wu6iT/2qpuYv0QZb.AMX9e', NULL, '+237654992485', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:55:37', '2025-06-13 14:55:37', NULL, NULL, NULL, NULL, 1, NULL),
(142, 'Kimbi Landry', 'landraebk@gmail.com', '$2y$10$TgxTHyZUEuhhKSmCZ0xJo.lTqEGDwnOcIeU3p6kUsJ4vaX069uH1u', NULL, '+237676986674', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:57:41', '2025-06-13 14:57:41', NULL, NULL, NULL, NULL, 1, NULL),
(143, 'Fokumlah Kareen Neh', 'Nehkareen31@gmail.com', '$2y$10$ZqoQJp74gpc8UnbBsacyt.hDDS6sbB.azzPmmmXHsT0zYCmUiua7W', NULL, '+237654094852', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:58:27', '2025-06-13 14:58:27', NULL, NULL, NULL, NULL, 1, NULL),
(144, 'Menmukah Precious Akweseh', 'menumekaprecious@gmail.com', '$2y$10$3EjVIfBQnCeUlc9iACt9menIJxQywvIcAI8o7TAVUw1b5o0GDC292', NULL, '+237653205479', NULL, 2, NULL, NULL, 1, 0, '2025-06-13 14:59:53', '2025-06-13 14:59:53', NULL, NULL, NULL, NULL, 1, NULL),
(145, 'NAEWTRER1063323NERTHRTYHR', 'eeiymlvy@aurevoirmail.com', '$2y$10$CboiesDpSFAHwKxWAUd.dOTDLYgcOttV0phP0MOHcxwA62XZWjbdm', NULL, '83728791727', NULL, 2, NULL, NULL, 1, 0, '2025-06-23 21:25:44', '2025-06-23 21:25:45', NULL, NULL, NULL, NULL, 1, NULL),
(146, 'NATREGTEGH30526NEHTYHYHTR', 'djiaqsjq@aurevoirmail.com', '$2y$10$Jxw8MHpPK2yhwgnRC3FE5e7X5QaxJ2umrDlNAEqBDeMXOlzkPRF9m', NULL, '86164889468', NULL, 2, NULL, NULL, 1, 0, '2025-06-28 15:13:34', '2025-06-28 15:13:36', NULL, NULL, NULL, NULL, 1, NULL),
(147, '+237672515140', 'user@gmail.com', '$2y$10$Rk4OKHyOm/NsTdXLUUpW7uTWmEc19q2Eff1FlP2BLcJKJJ4.M8pki', NULL, '+237672515140', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 06:23:15', '2025-07-01 06:23:15', NULL, NULL, NULL, NULL, 1, NULL),
(148, '+237 77735159', 'user@gmail.com', '$2y$10$GXfWSILDa0abbBfy4edt7.pwkQyyUnp3eXf0PVJY0rcyyYes5tSKS', NULL, '+237 77735159', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 06:35:54', '2025-07-01 06:35:54', NULL, NULL, NULL, NULL, 1, NULL),
(149, '+237676760243', 'user@gmail.com', '$2y$10$ymctLcSjdEYqagHMRsknD.Vz1Sc/T7/m15r9jasnxT7uBKc6aILQS', NULL, '+237676760243', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 06:42:06', '2025-07-01 06:42:06', NULL, NULL, NULL, NULL, 1, NULL),
(150, '+237690708950', 'user@gmail.com', '$2y$10$ZCTfjNOPkXG6grqCA8vkoODWUgfQbn2BFCVO5kIBf.vHXG9MZ7NNK', NULL, '+237690708950', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 06:50:13', '2025-07-01 12:44:34', NULL, NULL, NULL, NULL, 1, NULL),
(151, '+237697633111', 'user@gmail.com', '$2y$10$37oRRDnFb/ubU7FlxtjQPuyWStLA8IS8T2WwmRkYR1rmeTHuBjDjG', NULL, '+237697633111', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 08:32:32', '2025-07-01 08:32:32', NULL, NULL, NULL, NULL, 1, NULL),
(152, '+237679091512', 'user@gmail.com', '$2y$10$2iv5vtQhlStQxYM3pHf8LuESMHlacU/jmC.hCK4760Yg.IIeldfhm', NULL, '+237679091512', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 08:34:40', '2025-07-01 08:34:40', NULL, NULL, NULL, NULL, 1, NULL),
(153, '+237657993640', 'user@gmail.com', '$2y$10$huq0sW6wjrDxAllQJI7H5Ogw/qK3U49xHspjMZStL9TU5ErHoF6zO', NULL, '+237657993640', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 08:35:42', '2025-07-01 08:35:42', NULL, NULL, NULL, NULL, 1, NULL),
(154, '+237698545361', 'user@gmail.com', '$2y$10$7xmRfqk4Xq.TrZVKpLRiNeerlfE.Tn/O53M6hXjIw6CFQgkDMeW8y', NULL, '+237698545361', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 08:46:02', '2025-07-01 08:50:44', NULL, NULL, NULL, NULL, 1, NULL),
(155, '697049053', 'user@gmail.com', '$2y$10$KIE2RmN.yFlBkZQF09Jn.e9fP8Jzs6/7el/SkOgtgQ7woffSiwvi.', NULL, '697049053', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 08:49:26', '2025-07-01 08:49:26', NULL, NULL, NULL, NULL, 1, NULL),
(156, '+237692887812', 'user@gmail.com', '$2y$10$SOW1h53d25udwmnc/Wrk2.bTduetbnY02zLwZE8ryRRoJFOkyUG46', NULL, '+237692887812', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 08:50:03', '2025-07-01 08:50:03', NULL, NULL, NULL, NULL, 1, NULL),
(157, '+237656449882', 'user@gmail.com', '$2y$10$n2GxBg9lBgvuBzc99x5SFudwVrxY53y97oP9RKLX8Neh68PK1ZEDa', NULL, '+237656449882', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:02:17', '2025-07-01 09:02:17', NULL, NULL, NULL, NULL, 1, NULL),
(158, '+237678301790', 'user@gmail.com', '$2y$10$ynx0.XvGI4kcJtDyVq5ZSOo60KY3F.g4uHXhC13Vde84S9dMdNRb6', NULL, '+237678301790', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:05:22', '2025-07-01 09:05:22', NULL, NULL, NULL, NULL, 1, NULL),
(159, '+237676444626', 'user@gmail.com', '$2y$10$yqC.mknYSFzQGPdJmgG7D.XbaMB5nUC4OoT1GVSs3TuWvID41/Ike', NULL, '+237676444626', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:13:38', '2025-07-01 09:13:38', NULL, NULL, NULL, NULL, 1, NULL),
(160, '+237656379892', 'user@gmail.com', '$2y$10$PjqCROtFeJBI6cfClLKS1uV7C5np94zFhV2ZlbmVGN8JqFD2lTGbO', NULL, '+237656379892', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:19:41', '2025-07-01 09:19:41', NULL, NULL, NULL, NULL, 1, NULL),
(161, '+237670223579', 'user@gmail.com', '$2y$10$3FgahNcNV64r2CPAPqHpW.mOtZstdrg2dmwDD4ZzNN04dVqXk.VM.', NULL, '+237670223579', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:21:53', '2025-07-01 09:21:53', NULL, NULL, NULL, NULL, 1, NULL),
(162, '+237670095922', 'user@gmail.com', '$2y$10$gvatNWXJ5tCnG7wsyxMvD.hqS6CCHnNGn5/9IKthD0VRvib0M76fy', NULL, '+237670095922', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:23:34', '2025-07-01 09:23:34', NULL, NULL, NULL, NULL, 1, NULL),
(163, '+237 65839933', 'user@gmail.com', '$2y$10$/5JK8vf1eKCT8ol17ogAiOUaJYXi6vWo.x9F6Ea.ppip/lbxnNEzW', NULL, '+237 65839933', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:25:19', '2025-07-01 09:25:19', NULL, NULL, NULL, NULL, 1, NULL),
(164, '+237670581302', 'user@gmail.com', '$2y$10$5nohAfZryRCsSV6eXHcUNuBIu4GjJ6FksA6HLpzsHadxvn3Hv/lYW', NULL, '+237670581302', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:30:44', '2025-07-01 09:30:44', NULL, NULL, NULL, NULL, 1, NULL),
(165, '+237672166840', 'user@gmail.com', '$2y$10$QbngRhOkVheGVMyNimC/fObqBbermQspxgY5qs3AvfAoFHun5Kuli', NULL, '+237672166840', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:32:46', '2025-07-01 09:32:46', NULL, NULL, NULL, NULL, 1, NULL),
(166, '+2376934696', 'user@gmail.com', '$2y$10$g7OSf8rEPjkxDlA/vWM9wuA9bl6goTfLiNVeO6k6kLMZPZCbkZ1FO', NULL, '+2376934696', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:36:10', '2025-07-01 09:36:10', NULL, NULL, NULL, NULL, 1, NULL),
(167, '+237656148360', 'user@gmail.com', '$2y$10$Y04PecGyfT7DJH78GVAqXez.RN6AjqCvNNkI7Mzs0UR5gXriMnGjm', NULL, '+237656148360', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:36:12', '2025-07-01 09:36:12', NULL, NULL, NULL, NULL, 1, NULL),
(168, '+237658399339', 'user@gmail.com', '$2y$10$kIc7peV.ygtT6kOtoMscmuc3hcdjtmBtigwI8XUXhHXuxI74ntEty', NULL, '+237658399339', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:36:30', '2025-07-01 09:36:30', NULL, NULL, NULL, NULL, 1, NULL),
(169, '+23769149566', 'user@gmail.com', '$2y$10$KQFjAHlhwICn.7aV1jSAwuSVC0LFEr27FYouHHHHY3HQEsKPVVbXG', NULL, '+23769149566', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:37:06', '2025-07-01 09:37:06', NULL, NULL, NULL, NULL, 1, NULL),
(170, 'JOEL DJEMO', 'joelfongba@gmail.com', '$2y$10$WUOD3yRF8YSfnEgEubC3CufcYEeL0mK1MTCIQKieG7MLHqk76KOee', NULL, '+237 691495666', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:40:11', '2025-07-01 09:41:19', NULL, NULL, NULL, NULL, 1, NULL),
(171, '+237653217354', 'user@gmail.com', '$2y$10$58TAKW63uxLpacaT3c0tVuTEagMMeOMTy.nxkZDvJ7HGTRYQUdBPC', NULL, '+237653217354', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:44:24', '2025-07-01 09:44:24', NULL, NULL, NULL, NULL, 1, NULL),
(172, '+237689696968', 'user@gmail.com', '$2y$10$35dsFfudWIUqfPOGkD4gQ.ZE8Qdv0GauaRmb/vRUoY82K/3PUJ0u2', NULL, '+237689696968', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:44:31', '2025-07-01 09:44:31', NULL, NULL, NULL, NULL, 1, NULL),
(173, '+237698456691', 'user@gmail.com', '$2y$10$WU1cjyZAPsIqeZAGQUWpqujpakfCj5AeJdHCwso5T44WjIVEmkc6.', NULL, '+237698456691', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:49:45', '2025-07-01 09:49:45', NULL, NULL, NULL, NULL, 1, NULL),
(174, '+237699732906', 'user@gmail.com', '$2y$10$5HWAAyEFne.FXQnS3/9RAegfORunNYTbvT3Tutt/9kOiWWMDMOc7e', NULL, '+237699732906', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:52:24', '2025-07-01 09:52:24', NULL, NULL, NULL, NULL, 1, NULL),
(175, '+237 698227888', 'user@gmail.com', '$2y$10$GVPwdEtFEKiP2oT0BMHIKeAf82ajQMPDJR3MbCvwha3yjrfFkJheO', NULL, '+237 698227888', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:59:06', '2025-07-01 09:59:06', NULL, NULL, NULL, NULL, 1, NULL),
(176, '+237674420121', 'user@gmail.com', '$2y$10$U4HEZ911Tr2QfZTLBLCW/u1Lk4zSUVszpi3IFbLOS5Dmt/K6d26sC', NULL, '+237674420121', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 09:59:27', '2025-07-01 09:59:27', NULL, NULL, NULL, NULL, 1, NULL),
(177, '+237674255435', 'user@gmail.com', '$2y$10$yzQb0tz2b014Ntu.r6vjZuBV.din6Hc02KA.QIN9QkPj1T9Yv2ePe', NULL, '+237674255435', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:00:44', '2025-07-01 10:00:44', NULL, NULL, NULL, NULL, 1, NULL),
(178, '+237680560974', 'user@gmail.com', '$2y$10$cWMZcKcsSL5bmylvSq55wOgiUoKJy/16BcuB5Svi6WiRkBUzkA/9q', NULL, '+237680560974', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:01:33', '2025-07-01 10:01:33', NULL, NULL, NULL, NULL, 1, NULL),
(179, '+23751341047', 'user@gmail.com', '$2y$10$20YLdMR4t8yFaMog0sJX.O.t9j8eWEIHf.bg1TEsrYAs5iRxkFtR.', NULL, '+23751341047', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:05:08', '2025-07-01 10:08:06', NULL, NULL, NULL, NULL, 1, NULL),
(180, '+237674574449', 'user@gmail.com', '$2y$10$Nkaajg/n3AqnPNOCbFk8N.GW9a.NF1DFqlwHgcajrKVdd/kAeGJPG', NULL, '+237674574449', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:09:07', '2025-07-01 10:09:07', NULL, NULL, NULL, NULL, 1, NULL),
(181, '+237691913751', 'user@gmail.com', '$2y$10$WVHtAj4Cy1uDTEa./Srxf.txjxqx5KWciYFz8Q85B1C43aNcKhoie', NULL, '+237691913751', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:09:13', '2025-07-01 10:09:13', NULL, NULL, NULL, NULL, 1, NULL),
(182, '+237659432504', 'user@gmail.com', '$2y$10$9hes.8KG86gA3DQ/Cc/3iOXvHA8/NgRfzGMb/umDG9CwiXQolDGJG', NULL, '+237659432504', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:14:12', '2025-07-01 10:14:12', NULL, NULL, NULL, NULL, 1, NULL),
(183, '+237698540602', 'user@gmail.com', '$2y$10$BFUOC5cmi0TR1G6OBQcrHu1qw5ko3vU4suw5gUPamB/QvSC0O0TfW', NULL, '+237698540602', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:15:27', '2025-07-01 10:15:27', NULL, NULL, NULL, NULL, 1, NULL),
(184, '+237696242657', 'user@gmail.com', '$2y$10$S8T4S959jDq0hI/6kOlXLuFr74rU071gxGxxKLehQTs.R3t7IKvNe', NULL, '+237696242657', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:18:10', '2025-07-01 10:18:10', NULL, NULL, NULL, NULL, 1, NULL),
(185, '+237 75782453', 'user@gmail.com', '$2y$10$G5nT4KjWCiMFf4Zl.i0hCufIoWqqUOaJ1HjV9wrxXn7ulCXY6f.QC', NULL, '+237 75782453', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:22:09', '2025-07-01 10:22:09', NULL, NULL, NULL, NULL, 1, NULL),
(186, '+237671573865', 'user@gmail.com', '$2y$10$I1A4wqLApah4koqFSBeR5OOaw//iNf9aO5RlGw2dsdmkCQiNhlkQS', NULL, '+237671573865', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:24:52', '2025-07-01 10:24:52', NULL, NULL, NULL, NULL, 1, NULL),
(187, '+237 68952891', 'user@gmail.com', '$2y$10$Z9LlgiE9B5fKtogu2PoQ5urz6ca//qAJ90psyR1BaqWTG64GYpNmC', NULL, '+237 68952891', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:30:59', '2025-07-01 10:30:59', NULL, NULL, NULL, NULL, 1, NULL),
(188, '+237 67887744', 'user@gmail.com', '$2y$10$k9MJ.arGzRCeq3Mhl/od9uFiPRE8.13h92fFK0ip4ijE9v8uv5ES.', NULL, '+237 67887744', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:32:39', '2025-07-01 10:32:39', NULL, NULL, NULL, NULL, 1, NULL),
(189, '+237 69212076', 'user@gmail.com', '$2y$10$CwcQ/8N3dZElSoqsY742buXMw1BUQho8AgBtTHQ96wzxQf8ydkP0i', NULL, '+237 69212076', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:36:20', '2025-07-01 10:36:20', NULL, NULL, NULL, NULL, 1, NULL),
(190, '+237 92120766', 'user@gmail.com', '$2y$10$ODMZ2eIQMIRxknFQcLHx8eUaNc2dB/FHBSwqahkX/I3lUlqHiwMYO', NULL, '+237 92120766', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:36:41', '2025-07-01 10:36:41', NULL, NULL, NULL, NULL, 1, NULL),
(191, '+237690116287', 'user@gmail.com', '$2y$10$qLlg6wYU9VtC6ZsxGmrJiOK2X851ZJ.iUr1gf8F62oZDVGXam8kvG', NULL, '+237690116287', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:37:03', '2025-07-01 10:37:03', NULL, NULL, NULL, NULL, 1, NULL),
(192, '+237698425181', 'user@gmail.com', '$2y$10$ZsHW2GBLV44GEKQ7eaKcWO.gnXygOIxes85F7yMuaW4YdXM.Vh14q', NULL, '+237698425181', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:38:52', '2025-07-01 10:38:52', NULL, NULL, NULL, NULL, 1, NULL),
(193, '+237674253097', 'user@gmail.com', '$2y$10$1A2MD.hvEvSaAX/jOH71KOBxiVK67dLIj6HWBKYu9Hj0TprHntQAC', NULL, '+237674253097', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:40:13', '2025-07-01 10:40:13', NULL, NULL, NULL, NULL, 1, NULL),
(194, '+225 07598215', 'user@gmail.com', '$2y$10$/5bJeAIdlYveBdRg3gzTQunUqO0rfZjioAGrfLKC.glEsR6Twnut2', NULL, '+225 07598215', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:44:45', '2025-07-01 10:44:45', NULL, NULL, NULL, NULL, 1, NULL),
(195, '695066629', 'user@gmail.com', '$2y$10$FfcDB.VUaNKeXZTu4t4G4OuiHWybAKfYdUVVFVnPKrnEz.0NqMA56', NULL, '695066629', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:45:30', '2025-07-01 10:45:30', NULL, NULL, NULL, NULL, 1, NULL),
(196, '+237656484113', 'user@gmail.com', '$2y$10$tV5VDge6r2J7MJscmr83belFVSaE68fCP78ZcO7IUSuH7959jkkRa', NULL, '+237656484113', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:46:39', '2025-07-01 10:46:39', NULL, NULL, NULL, NULL, 1, NULL),
(197, '+237694581851', 'user@gmail.com', '$2y$10$jU45sIlPxSQbXuCcdxYJ4OSWkZlIuZaH2J2Y6OQRloaqNNcZwZ27a', NULL, '+237694581851', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:47:02', '2025-07-01 10:47:02', NULL, NULL, NULL, NULL, 1, NULL),
(198, '+237694324250', 'user@gmail.com', '$2y$10$X2iX2uSpKKxbbGiwzeCvceWVoFzmaQlydbMvkS9t7Q5C7XgklACFW', NULL, '+237694324250', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:47:35', '2025-07-01 10:47:35', NULL, NULL, NULL, NULL, 1, NULL),
(199, 'Kamdem', 'kamdemabraham237@gmail.com', '$2y$10$6FbiXg8gNajQ/ouNB7ANQOPRX3phOgaFWsMGNIc10Wk3r/aT5RqBy', NULL, '+237655716268', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:48:22', '2025-07-01 10:49:31', NULL, NULL, NULL, NULL, 1, NULL),
(200, '+237675754684', 'user@gmail.com', '$2y$10$Miqk9UgL0r84uJWPKJMHfenWMBXCIjpEX4dEFqumzVPYGUHPfvIEC', NULL, '+237675754684', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:50:07', '2025-07-01 10:50:07', NULL, NULL, NULL, NULL, 1, NULL),
(201, '+237695066629', 'user@gmail.com', '$2y$10$zvTSiwlNvTr3Ofvgx2M2lu3EC65h33zzp3E6bH4CfZbDFHO/nR1pq', NULL, '+237695066629', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:50:24', '2025-07-01 10:50:24', NULL, NULL, NULL, NULL, 1, NULL),
(202, '+237 65957547', 'user@gmail.com', '$2y$10$iORViJ546P9qId84qB4oxehRumGKn1jH38CdbCVS.7eiry0DC9Ure', NULL, '+237 65957547', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:50:28', '2025-07-01 10:50:28', NULL, NULL, NULL, NULL, 1, NULL),
(203, '+237656233295', 'user@gmail.com', '$2y$10$NeZtNikvHUSp29m2dIpQEuZDJhyLkJ8dR4iesa9bDWt0tRdBx8Jee', NULL, '+237656233295', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:51:02', '2025-07-01 10:51:02', NULL, NULL, NULL, NULL, 1, NULL),
(204, '+237697765582', 'user@gmail.com', '$2y$10$GP4tq6o5pcwDQOarLwIRi.iusfxyRToF07WmL3HzFwIn0Z/q3aTo2', NULL, '+237697765582', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:53:40', '2025-07-01 10:53:40', NULL, NULL, NULL, NULL, 1, NULL),
(205, '+237694952107', 'user@gmail.com', '$2y$10$mTtiF1y1y4KDeQnXx.I6YuJGJIYUDJlcCxXymD/EjKcsBRqB6q.fG', NULL, '+237694952107', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:54:50', '2025-07-01 10:54:50', NULL, NULL, NULL, NULL, 1, NULL),
(206, '+237674010188', 'user@gmail.com', '$2y$10$KFd9I4Z51tS47uy2KYmvq.FQvmU9u6e4kPgoykAyh2scfwUfEE/z2', NULL, '+237674010188', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:55:26', '2025-07-01 10:55:26', NULL, NULL, NULL, NULL, 1, NULL),
(207, '+237694712724', 'user@gmail.com', '$2y$10$LouJgOYbxGKeEGMZafESru.0UMlgC4gYqr49yMwlwTKu4likUiBya', NULL, '+237694712724', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:56:39', '2025-07-01 10:56:39', NULL, NULL, NULL, NULL, 1, NULL),
(208, '+237694836978', 'user@gmail.com', '$2y$10$tot.UGooH3xD2LTibtnKE.0zjP1h4FYn82UPJUIEiVm6XKPhADjjG', NULL, '+237694836978', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:57:04', '2025-07-01 10:57:04', NULL, NULL, NULL, NULL, 1, NULL),
(209, '+237697288179', 'user@gmail.com', '$2y$10$mzmOMswWKJZnBXN6wtwyxeUoprSrCtWZX9/y9lpINQDnyfrOENkRm', NULL, '+237697288179', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:57:06', '2025-07-01 10:57:06', NULL, NULL, NULL, NULL, 1, NULL),
(210, '+237692336361', 'user@gmail.com', '$2y$10$VMuBpWLe.hPFq7czO2ybm.Gkc0VDjPsR6vih6vuYJHfGUh2PpyUSG', NULL, '+237692336361', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:57:53', '2025-07-01 10:57:53', NULL, NULL, NULL, NULL, 1, NULL),
(211, '+237691813094', 'user@gmail.com', '$2y$10$Bvkia52NZA3MOmFBapUKxe4u3D1JQvspdqXj2CtfVvlc0AYY7wwsK', NULL, '+237691813094', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 10:59:29', '2025-07-01 10:59:29', NULL, NULL, NULL, NULL, 1, NULL),
(212, '+237656676079', 'user@gmail.com', '$2y$10$Oik2x9NGX8o/jJvLwuyyGesYcqjoNYN449MhCqtDMBx1qOHoCg9ta', NULL, '+237656676079', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:00:20', '2025-07-01 11:00:20', NULL, NULL, NULL, NULL, 1, NULL),
(213, '+237689528919', 'user@gmail.com', '$2y$10$u9Gl8pIgmh.DjxvRJUogo.qnUmzsBIsC9v5t5I39XwOkVu/2O01/G', NULL, '+237689528919', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:00:39', '2025-07-01 11:00:39', NULL, NULL, NULL, NULL, 1, NULL),
(214, '+237 69545434', 'user@gmail.com', '$2y$10$55eaSuGNLdMPSou97GgvQ.IW8/OOyOZhqBv0or99pXZtekMXoPdoy', NULL, '+237 69545434', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:02:31', '2025-07-01 11:02:31', NULL, NULL, NULL, NULL, 1, NULL),
(215, '+237695454348', 'user@gmail.com', '$2y$10$V/zU1LW05DU/ZEXIvo4HBeBalkH6q4Z8OqQ3fIGQQdRVk6wbXQIii', NULL, '+237695454348', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:04:34', '2025-07-01 11:04:34', NULL, NULL, NULL, NULL, 1, NULL),
(216, '+237 69363425', 'user@gmail.com', '$2y$10$kuaNbrGgrdEbcNdv1cLxneuV3kKmPxAQehJC7aG.rcPG8lGWwVlHW', NULL, '+237 69363425', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:05:52', '2025-07-01 11:05:52', NULL, NULL, NULL, NULL, 1, NULL),
(217, '+237 69887657', 'user@gmail.com', '$2y$10$t1havr2gN9kXrPH8v9g6L.vzUyMmWL7URIt/H3dl52tF82PFFn5vC', NULL, '+237 69887657', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:08:10', '2025-07-01 11:08:10', NULL, NULL, NULL, NULL, 1, NULL),
(218, '+237694880439', 'user@gmail.com', '$2y$10$idzfTbsE0ioOpsRFM5vj9.HRQnCExK3802VUtD3kSrmAaQPNn20oS', NULL, '+237694880439', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:08:31', '2025-07-01 11:08:31', NULL, NULL, NULL, NULL, 1, NULL),
(219, '+237693655969', 'user@gmail.com', '$2y$10$jHimx/w6Qg4bFtLoMLpTju1YaNXC206MIdyb3xbmq5bibtJMKGaKq', NULL, '+237693655969', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:08:59', '2025-07-01 11:08:59', NULL, NULL, NULL, NULL, 1, NULL),
(220, '+237657600788', 'user@gmail.com', '$2y$10$WrZSdWitmTyFWJm9v91CBuGF4EV5zXQ.uWknvrvzoO8K1PZbOZ/ka', NULL, '+237657600788', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:10:22', '2025-07-01 11:10:22', NULL, NULL, NULL, NULL, 1, NULL),
(221, 'Neyom', 'neypatricia148@gmail.com', '$2y$10$hLT9oklVbKDtdKZ70qgYhuG7DXTeG4xE93UdqxgSGLoWnnOou9feC', NULL, '+237 698876573', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:12:41', '2025-07-01 11:37:00', NULL, NULL, NULL, NULL, 1, NULL),
(222, '+237680734600', 'user@gmail.com', '$2y$10$RfZuyYY00fbJmP0R28Vlj.hkcPTokqklodY13tLgXh/ASsyzGNL56', NULL, '+237680734600', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:13:19', '2025-07-01 11:13:19', NULL, NULL, NULL, NULL, 1, NULL),
(223, '+237676925320', 'user@gmail.com', '$2y$10$gw.9y7SJJhZMC23RjN1K8u.axTE.oeXAcOGPae9IWf7u4I7BtDwBK', NULL, '+237676925320', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:18:56', '2025-07-01 11:18:56', NULL, NULL, NULL, NULL, 1, NULL),
(224, '+237694215183', 'user@gmail.com', '$2y$10$AQ.Mrj7jQcINlyU.Si7e..0ob.lERYEXENrfn727TIkAANtarl5rW', NULL, '+237694215183', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:19:03', '2025-07-01 11:19:03', NULL, NULL, NULL, NULL, 1, NULL),
(225, '+237697785767', 'user@gmail.com', '$2y$10$Rg9bBg8dqyt690koRxSnEuMYbTU1f6vDFTrsuj/aWS2h4x5Bk0fzK', NULL, '+237697785767', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:19:18', '2025-07-01 11:19:18', NULL, NULL, NULL, NULL, 1, NULL),
(226, '+225 05766863', 'user@gmail.com', '$2y$10$A6aqmGAhehbyj5SjOGbT/OvuW11V95EODMYU02xdGJrxFmHYnPot.', NULL, '+225 05766863', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:20:46', '2025-07-01 11:20:46', NULL, NULL, NULL, NULL, 1, NULL),
(227, '+237675119087', 'user@gmail.com', '$2y$10$LGOad34OlXavZOshF3BnSu3e/J95Q5RwVyrHOZS7CxA/WnV.R8ZWa', NULL, '+237675119087', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:20:58', '2025-07-01 11:20:58', NULL, NULL, NULL, NULL, 1, NULL),
(228, '+237698852472', 'user@gmail.com', '$2y$10$nQbK5qPr0rBSV06vBget/uWdjJYHTFZNk11QjrgQzWOGB9jrn8qEy', NULL, '+237698852472', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:21:16', '2025-07-01 11:21:16', NULL, NULL, NULL, NULL, 1, NULL),
(229, '0576686378', 'user@gmail.com', '$2y$10$.O7z6DV5PfmqlxnxXd6DyOI0RmWwYKqp7WoySV..RvU5oTvk8XW4a', NULL, '0576686378', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:23:44', '2025-07-01 11:23:44', NULL, NULL, NULL, NULL, 1, NULL),
(230, '+237 691953021', 'user@gmail.com', '$2y$10$GJyMh1gozYbotfs68UHX3.6ap0/ZQ978L.hDS2h7Y.C/zLeRzpwCq', NULL, '+237 691953021', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:25:19', '2025-07-01 11:25:19', NULL, NULL, NULL, NULL, 1, NULL),
(231, '+237 68035408', 'user@gmail.com', '$2y$10$s6wQLpaHXbdgPSACqcgAqOIZSMFTptf9jpPp08fLxEEpuz5.jO5be', NULL, '+237 68035408', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:32:23', '2025-07-01 11:32:23', NULL, NULL, NULL, NULL, 1, NULL),
(232, '+237650361160', 'user@gmail.com', '$2y$10$NgTA7eZ.zSL.p8G0zC6b2Ol6UIO9NBabtqKJoQqiL3sd47Au52FVe', NULL, '+237650361160', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:35:14', '2025-07-01 11:35:14', NULL, NULL, NULL, NULL, 1, NULL),
(233, '+237699903399', 'user@gmail.com', '$2y$10$t0UgEmymaiylV2z/D7hhWehC4Cvp3O7EMNiK6jQu7toLCU9NW4t1O', NULL, '+237699903399', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:35:26', '2025-07-01 11:35:26', NULL, NULL, NULL, NULL, 1, NULL),
(234, '+237+237 6 56', 'user@gmail.com', '$2y$10$Z5cOJmoTucBpEzOk.qMZu.hTldXK77xuqF6yLaIgxhBTXg7AoAPPi', NULL, '+237+237 6 56', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:35:45', '2025-07-01 11:35:45', NULL, NULL, NULL, NULL, 1, NULL),
(235, '+237 6 56 42', 'user@gmail.com', '$2y$10$qqOYCzyCAjr0SOwhNJ27a.N.TKSPzO3UehA6KA6R5FQBsbupjhZj6', NULL, '+237 6 56 42', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:36:40', '2025-07-01 11:36:40', NULL, NULL, NULL, NULL, 1, NULL),
(236, '+237693501070', 'user@gmail.com', '$2y$10$wgLn6HLYb34hMjw1hoF9quQ0oR3IRsTmQkzc5BPYyuj.IvIyuY.ze', NULL, '+237693501070', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:37:01', '2025-07-01 11:37:01', NULL, NULL, NULL, NULL, 1, NULL),
(237, '+237673884520', 'user@gmail.com', '$2y$10$UhlnhJajYMZ4xdqUPkngduyBGN5qWT4dBzISstfGVl6ORFO4e90Xe', NULL, '+237673884520', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:37:30', '2025-07-01 11:37:30', NULL, NULL, NULL, NULL, 1, NULL),
(238, '+237 65642889', 'user@gmail.com', '$2y$10$KmrtvCOJ/NcwNbSdlOuKxu8OEacEWxPCS5UYX4dZH1Gkp4EDLzZiG', NULL, '+237 65642889', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:37:54', '2025-07-01 11:37:54', NULL, NULL, NULL, NULL, 1, NULL),
(239, '+237656428895', 'user@gmail.com', '$2y$10$2gx3jWupFu1yFq9U/Dj6W.JqJe1M5Kqcx6I3iAcjwagTOBtZeTK5a', NULL, '+237656428895', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:38:02', '2025-07-01 11:38:02', NULL, NULL, NULL, NULL, 1, NULL),
(240, '+237 696453074', 'user@gmail.com', '$2y$10$GB9VS9HcaLwzmOXiz7iJPe5zcfkKg17tPgsB0P6ffi5G5coqdT/3C', NULL, '+237 696453074', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:39:08', '2025-07-01 11:39:08', NULL, NULL, NULL, NULL, 1, NULL),
(241, '+237673680481', 'user@gmail.com', '$2y$10$Kmv2BtwRfU8mPwZ/44Ci9e8R7r9sGJ06DzzW2GdCB1kY8SJqUBESy', NULL, '+237673680481', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:42:08', '2025-07-01 11:42:08', NULL, NULL, NULL, NULL, 1, NULL),
(242, '+237696453074', 'user@gmail.com', '$2y$10$75NhfnHsOc2ch0Klm.nPDuoTmPgIs27gQ1bs5s.8dPLJg6/TTpzYS', NULL, '+237696453074', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:43:46', '2025-07-01 11:43:46', NULL, NULL, NULL, NULL, 1, NULL),
(243, '+237659212910', 'user@gmail.com', '$2y$10$ZkS2UwTZXmrYQNvtUdocWuCYgq4FATwp4jEhc62cfSpakhn.brmSK', NULL, '+237659212910', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:46:13', '2025-07-01 11:46:13', NULL, NULL, NULL, NULL, 1, NULL);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `phone`, `company_name`, `role_id`, `biller_id`, `warehouse_id`, `is_active`, `is_deleted`, `created_at`, `updated_at`, `sign`, `stemp`, `otp`, `otp_time`, `otp_verify`, `additional_phone`) VALUES
(244, '+237698117745', 'user@gmail.com', '$2y$10$Sb8TreRmLoaBZjVTVbHk..5hmkQXiYpBhIrMu8tMcqmbi6khVl5qW', NULL, '+237698117745', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:46:29', '2025-07-01 11:46:29', NULL, NULL, NULL, NULL, 1, NULL),
(245, '+237674678045', 'user@gmail.com', '$2y$10$8PdUdGkTmTiPhOWzO9VrjOinlWv.coHflsPTKdUWrkETSLbN.yT4u', NULL, '+237674678045', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:46:49', '2025-07-01 11:46:49', NULL, NULL, NULL, NULL, 1, NULL),
(246, '+237679997918', 'user@gmail.com', '$2y$10$CIyKnLhZuHDz4gxrkf3E4etNykqzi4ulvL/RPITPnXBzbF7pxpu0K', NULL, '+237679997918', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:49:23', '2025-07-01 11:49:23', NULL, NULL, NULL, NULL, 1, NULL),
(247, '+221763649207', 'user@gmail.com', '$2y$10$Z7Z7EIHAmRqbQ3pLfY724.s79n9Rc/ryt8wuhdpcvkdlm1ywOXdki', NULL, '+221763649207', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:52:49', '2025-07-01 11:54:21', NULL, NULL, NULL, NULL, 1, NULL),
(248, '+237 79269555', 'user@gmail.com', '$2y$10$NvpZIK5ibALpX5P0REUtHes8GUKBKMLqaOEKiy0yELVxjHnmILcom', NULL, '+237 79269555', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:54:06', '2025-07-01 12:29:20', NULL, NULL, NULL, NULL, 1, NULL),
(249, '+237698845533', 'user@gmail.com', '$2y$10$RcripJkkrrKxCsDYLQdlNehbXpUJBdYnxbuEkgKHFkQn6/KU6UZ5O', NULL, '+237698845533', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:56:25', '2025-07-01 11:56:25', NULL, NULL, NULL, NULL, 1, NULL),
(250, '+237698673619', 'user@gmail.com', '$2y$10$kOuv4jhX5fYPa.z//YJedO8IU9Cb06o4wpvDnk3SmNaregr7svu/S', NULL, '+237698673619', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:56:30', '2025-07-01 11:56:30', NULL, NULL, NULL, NULL, 1, NULL),
(251, '+237 691380', 'user@gmail.com', '$2y$10$ziGrli9FlVO3YN/P7xwCoOo99G0z/b.e95qlRTMgIN./VUK28HVVO', NULL, '+237 691380', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:56:44', '2025-07-01 11:56:44', NULL, NULL, NULL, NULL, 1, NULL),
(252, '+237698471693', 'user@gmail.com', '$2y$10$ykFMyGi9YE/KrcgWZleK4OBh9ifPAbKMi18IsOn3aTpOlSZRUFkVa', NULL, '+237698471693', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:57:17', '2025-07-01 11:57:17', NULL, NULL, NULL, NULL, 1, NULL),
(253, '+237691380097', 'user@gmail.com', '$2y$10$MtAntRoYbA2WjhFgjBewDeorDU1Cc9uFVy8aAHNqR3cx2Z4ACJ76m', NULL, '+237691380097', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 11:57:33', '2025-07-01 11:57:33', NULL, NULL, NULL, NULL, 1, NULL),
(254, '+225056728362', 'user@gmail.com', '$2y$10$F5KmIM8EzO21tF9YAjZwwuu.NW8Q.21Z3hEZQFNLPXboVXJo7g9n6', NULL, '+225056728362', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:02:46', '2025-07-01 12:02:46', NULL, NULL, NULL, NULL, 1, NULL),
(255, '+237673327531', 'user@gmail.com', '$2y$10$jkXB9IaLPOKtKAvipCmJO.iWfDZiv/X/Bmmv6wQVRvZ8VaDOZKo.6', NULL, '+237673327531', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:04:02', '2025-07-01 12:04:02', NULL, NULL, NULL, NULL, 1, NULL),
(256, '+237696854316', 'user@gmail.com', '$2y$10$KSmckrU8EavuSdI6kpLNEuVo9SrAtuTjQ9Nwxf3DRwbSiQDhQvUCe', NULL, '+237696854316', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:04:03', '2025-07-01 12:04:03', NULL, NULL, NULL, NULL, 1, NULL),
(257, '+237677257542', 'user@gmail.com', '$2y$10$MTnpecqrnhg1rT8sIeLY9.Up1JTTtRnJ099.S67aWWpGcYoYjyWri', NULL, '+237677257542', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:04:53', '2025-07-01 12:04:53', NULL, NULL, NULL, NULL, 1, NULL),
(258, '+23799380664', 'user@gmail.com', '$2y$10$97tVE62OjO7X1WBi.nfXu.4eKce5LC7Xa7Z1.o7U.SX9Cdx5jurOa', NULL, '+23799380664', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:05:18', '2025-07-01 12:05:18', NULL, NULL, NULL, NULL, 1, NULL),
(259, '+237698684137', 'user@gmail.com', '$2y$10$DQlyR5ZHvEIOZsHxlfcLz.wiVtpCugqUKVjZgj3xaK3Ky4S9LCtPq', NULL, '+237698684137', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:05:25', '2025-07-01 12:05:25', NULL, NULL, NULL, NULL, 1, NULL),
(260, '+237 672', 'user@gmail.com', '$2y$10$D7ec0SXRGWKdY5qrscqOh.y8XF3opVVmQcLMJnQANQl9FkIBP.3Lu', NULL, '+237 672', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:07:43', '2025-07-01 12:07:43', NULL, NULL, NULL, NULL, 1, NULL),
(261, '+237 6 75 81', 'user@gmail.com', '$2y$10$Yn84PLOMYbkDjtwMh3tOw.afAol9TGPetITS7Rf78o6qMtjJ/uS4S', NULL, '+237 6 75 81', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:07:53', '2025-07-01 12:07:53', NULL, NULL, NULL, NULL, 1, NULL),
(262, '+237676189091', 'user@gmail.com', '$2y$10$zu9PGLmjK6etzSAm9D.m7.Z6KpdL.QKLUFY6ofwnsRZyKwujvdpXW', NULL, '+237676189091', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:09:51', '2025-07-01 12:09:51', NULL, NULL, NULL, NULL, 1, NULL),
(263, '+237655318374', 'user@gmail.com', '$2y$10$gPBDP/Pkyi23qPfAQDw0oemmJfxnlxeh2dVYrJHs4DgtndBAyuu06', NULL, '+237655318374', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:15:19', '2025-07-01 12:19:33', NULL, NULL, NULL, NULL, 1, NULL),
(264, '+237694533664', 'user@gmail.com', '$2y$10$bcKoK1V4gV2QlS6va6.dFux5jQg2Nud1Xbiv1A8YoF78y/9cDPyq2', NULL, '+237694533664', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:18:40', '2025-07-01 12:18:40', NULL, NULL, NULL, NULL, 1, NULL),
(265, '+237654313974', 'user@gmail.com', '$2y$10$cK.ihNmlI5u263nyX2AL4eztsa5A6Xos4OgF7o5U1F3rBC3vbkWn.', NULL, '+237654313974', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:19:48', '2025-07-01 12:19:48', NULL, NULL, NULL, NULL, 1, NULL),
(266, '+23779269555', 'user@gmail.com', '$2y$10$cV4ZMxSpOavHogcHWat2Ou1CVOiipYETtVvXfeZkqa3Qg17Js1weS', NULL, '+23779269555', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:20:48', '2025-07-01 12:20:48', NULL, NULL, NULL, NULL, 1, NULL),
(267, '+237655791908', 'user@gmail.com', '$2y$10$3uZwMX30wSdsgqPnAOnQTukg.zCsT7A6BvH617ZIrLW8WezKeZkSK', NULL, '+237655791908', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:22:10', '2025-07-01 12:22:10', NULL, NULL, NULL, NULL, 1, NULL),
(268, '698729198', 'user@gmail.com', '$2y$10$WwTV5.vqNQ3Dc1tgvcRyxu9ssxB3jkdcjXKiOvbdo5ogiza6Q/2Fu', NULL, '698729198', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:22:48', '2025-07-01 12:22:48', NULL, NULL, NULL, NULL, 1, NULL),
(269, '+237657899552', 'user@gmail.com', '$2y$10$U9V5lqZvcUfk0fpgueUpceLVJs3SkCni4JjiQ9F.Kp/jONQRmLHj.', NULL, '+237657899552', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:23:15', '2025-07-01 12:23:15', NULL, NULL, NULL, NULL, 1, NULL),
(270, '+237694247598', 'user@gmail.com', '$2y$10$VW2zxUXtdxdWIXRRH.uZJ.TvM.MlYVtcJtyYoqRtUR3K89Vgky7ma', NULL, '+237694247598', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:24:31', '2025-07-01 12:24:31', NULL, NULL, NULL, NULL, 1, NULL),
(271, '+237679201708', 'user@gmail.com', '$2y$10$KkYDjfYVOTnGnLnrWjRX6ehSanLr30YeL/szKXNqBRpzIMwl7Xkgm', NULL, '+237679201708', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:25:15', '2025-07-01 12:38:02', NULL, NULL, NULL, NULL, 1, NULL),
(272, '+237 690 885', 'user@gmail.com', '$2y$10$mdL1zu.WhBMq.AyXdvl1tO8Jv1PRoAknJhjgB.b0qcj8k1.baeebS', NULL, '+237 690 885', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:29:13', '2025-07-01 12:29:13', NULL, NULL, NULL, NULL, 1, NULL),
(273, 'Benjamin LOWE', 'bendjtankeu@gmail.com', '$2y$10$OV1Y1o21X8kWDdZQdmipK.ii0n.HJOMkv2aljodnT1Bbz5/sAKFPq', NULL, '+237 6 90 88 53 15', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:30:46', '2025-07-01 12:38:44', NULL, NULL, NULL, NULL, 1, NULL),
(274, '+237698155754', 'user@gmail.com', '$2y$10$OCQlTHtxeRqrJV5h1PXcveNi6cgDRKOewxpjLFKOxWFr.ujIar0Fm', NULL, '+237698155754', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:31:23', '2025-07-01 12:31:23', NULL, NULL, NULL, NULL, 1, NULL),
(275, '+237 92399346', 'user@gmail.com', '$2y$10$OHpMAUlrasjrWOx1EuYpeOIE8W9K9fF4Ydrmc7s.OCypmAoFlgIDq', NULL, '+237 92399346', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:39:46', '2025-07-01 12:39:46', NULL, NULL, NULL, NULL, 1, NULL),
(276, '+237670829675', 'user@gmail.com', '$2y$10$IUZ7dviE8EfcE5MKdyqqxuLg2hsvzFh8waJzSxUNEiH7VaA607DyC', NULL, '+237670829675', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:41:18', '2025-07-01 12:41:18', NULL, NULL, NULL, NULL, 1, NULL),
(277, 'BOIMANDI FIMANOU NATHALIE', 'nathboimandi@gmail.com', '$2y$10$2uVpfmyKb5AV5eROUXrOsOtv4VtdEFsJasaPkl0OjGt7Mxi3UAVrq', NULL, '+237696757049', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:43:31', '2025-07-01 12:43:33', NULL, NULL, NULL, NULL, 1, NULL),
(278, '+237696615468', 'user@gmail.com', '$2y$10$BQBFYOZx6MTZp54ABisuW.PNli4k01jXB68gFstsD1KNiIuLf3OfO', NULL, '+237696615468', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:46:43', '2025-07-01 12:46:43', NULL, NULL, NULL, NULL, 1, NULL),
(279, '+237690885315', 'user@gmail.com', '$2y$10$IxTxVmoQhxjHgRUhH3RG4uEEbUMiBGjTN7I.sQaEO1QfFcgF8uyj2', NULL, '+237690885315', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:47:27', '2025-07-01 12:47:27', NULL, NULL, NULL, NULL, 1, NULL),
(280, '+237695285343', 'user@gmail.com', '$2y$10$6uds17AI2sTVMHgBfTQqUuN4ZBQahRDPsw6MNnZQZVXJlHrExDJf2', NULL, '+237695285343', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:51:35', '2025-07-01 12:51:35', NULL, NULL, NULL, NULL, 1, NULL),
(281, '+237696251710', 'user@gmail.com', '$2y$10$EbM4RHX0GnKNUWLrpOav.OaEn3t5L7b55dCEGsvBtMR6KW/B42YJ6', NULL, '+237696251710', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:52:27', '2025-07-01 12:52:27', NULL, NULL, NULL, NULL, 1, NULL),
(282, '+237674258352', 'user@gmail.com', '$2y$10$nICEyqwwRtK1RcK.jBoQbuk8SVWS.1RxmFJ/4INcr5Jd2yC7D5Nsi', NULL, '+237674258352', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:53:26', '2025-07-01 12:53:26', NULL, NULL, NULL, NULL, 1, NULL),
(283, '+237695593859', 'user@gmail.com', '$2y$10$LGf.V6qYjdumSQBVUCWP4.munyNEjNWaZbkmfgiGR5fGbXwsgzbKG', NULL, '+237695593859', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 12:57:28', '2025-07-01 12:57:28', NULL, NULL, NULL, NULL, 1, NULL),
(284, '+237699387451', 'user@gmail.com', '$2y$10$XQjKbx7aHGWaj41le.HrHeGhO903T/.82zALSI80uSqYJum1r48dm', NULL, '+237699387451', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:00:51', '2025-07-01 13:00:51', NULL, NULL, NULL, NULL, 1, NULL),
(285, '+237670715656', 'user@gmail.com', '$2y$10$aDOz.iHakOF.npwkefw7LuXAoMJbzwg/X0byWX7ONNk7pLxYh0DIm', NULL, '+237670715656', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:06:39', '2025-07-01 13:06:39', NULL, NULL, NULL, NULL, 1, NULL),
(286, '+237697515300', 'user@gmail.com', '$2y$10$7z2nXNvL9UETSVnu5I6oYO7VhfoiK.OgMbLIqXdo2Ee91bWNsLI0C', NULL, '+237697515300', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:07:15', '2025-07-01 13:07:15', NULL, NULL, NULL, NULL, 1, NULL),
(287, '+237653810774', 'user@gmail.com', '$2y$10$wicUbexTCKae3SZBIPT0J.iiq4EKo611V.IXqS7ovGpYzEH1wcWqe', NULL, '+237653810774', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:13:30', '2025-07-01 13:13:30', NULL, NULL, NULL, NULL, 1, NULL),
(288, '+237 65072507', 'user@gmail.com', '$2y$10$tjk1dEgn5zzzG3qJFyLIseF7/6jyPNAjahPDKmo4sAJLaOZEeDE2a', NULL, '+237 65072507', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:15:42', '2025-07-01 13:15:42', NULL, NULL, NULL, NULL, 1, NULL),
(289, '+237 93742692', 'user@gmail.com', '$2y$10$33dWgwGBnYy32nLhRyUXDO5xSFnUgvb2IFDpr9U7ZFcGMoroUA8.W', NULL, '+237 93742692', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:15:58', '2025-07-01 13:15:58', NULL, NULL, NULL, NULL, 1, NULL),
(290, '+237694818118', 'user@gmail.com', '$2y$10$ICyvFwHtrlHm6tiC7m7TTOwfawXc.2k7H2Ni2k1YaYj0.8O9kyenK', NULL, '+237694818118', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:17:32', '2025-07-01 13:17:32', NULL, NULL, NULL, NULL, 1, NULL),
(291, '+237695988824', 'user@gmail.com', '$2y$10$vH/DTQg4dKEujK2ecj8b/.tBzm0qgqeJzJecBh/3shLmyg9G5F.AW', NULL, '+237695988824', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:20:28', '2025-07-01 13:20:28', NULL, NULL, NULL, NULL, 1, NULL),
(292, '+237651477512', 'user@gmail.com', '$2y$10$2fHdMjLImXJGCDEdSFANPelzw4TTGNoVWP1WXV2Hkd/LCMuUSjaQ2', NULL, '+237651477512', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:25:06', '2025-07-01 13:25:06', NULL, NULL, NULL, NULL, 1, NULL),
(293, '+237656278478', 'user@gmail.com', '$2y$10$Kz7KpyYbdbDX3zxBL6nsguJzeyf/CbKd8ciMZL3Axee8C/4AaMeui', NULL, '+237656278478', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:26:05', '2025-07-01 13:26:05', NULL, NULL, NULL, NULL, 1, NULL),
(294, '+237656891939', 'user@gmail.com', '$2y$10$CwCiSbj17bhng1vgAERey.at7Og.spW7ZBVEmbxaTMq151aqEkSzu', NULL, '+237656891939', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:27:11', '2025-07-01 13:27:11', NULL, NULL, NULL, NULL, 1, NULL),
(295, '+237675906394', 'user@gmail.com', '$2y$10$Wb/./aqOm9tJqiGxKWM3MOm/fEeBBTSuudgzKEbWePLpj9B9q8tla', NULL, '+237675906394', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:38:39', '2025-07-01 13:38:39', NULL, NULL, NULL, NULL, 1, NULL),
(296, '+237 69122777', 'user@gmail.com', '$2y$10$Yhxs4Y09osbBpHnjvRzEquIMyIX0LS2vWNv3uizbEoIW0398CiE16', NULL, '+237 69122777', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:40:32', '2025-07-01 13:40:32', NULL, NULL, NULL, NULL, 1, NULL),
(297, '+237677884749', 'user@gmail.com', '$2y$10$71uMdjnFcgUHqEQOLX3kquR2FBsbKrQnVNxz1beMVWKeswoXGJo/q', NULL, '+237677884749', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:41:49', '2025-07-01 13:41:49', NULL, NULL, NULL, NULL, 1, NULL),
(298, '+237655957597', 'user@gmail.com', '$2y$10$RlUxZVycpTFg3glNz5w37.hWvq6XmHHgHHGDFNKh2Iz61S7oJ2Q4O', NULL, '+237655957597', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:46:16', '2025-07-01 13:46:16', NULL, NULL, NULL, NULL, 1, NULL),
(299, '+237691911017', 'user@gmail.com', '$2y$10$0ocSkm...a6kGQIEv3lXTOxxlxVKU.nNhqT.IudhZODJcfwXjuTVu', NULL, '+237691911017', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:48:04', '2025-07-01 13:48:04', NULL, NULL, NULL, NULL, 1, NULL),
(300, '+237682050308', 'user@gmail.com', '$2y$10$LQJ/Ii8cZAZ9Hg0/.G3kK.yPfT1oIJLfhaRoHFzr8gsu33K6bEAI.', NULL, '+237682050308', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:48:10', '2025-07-01 13:48:10', NULL, NULL, NULL, NULL, 1, NULL),
(301, '+237694142621', 'user@gmail.com', '$2y$10$LVjJxwQMKpO7F4GRAZqYS./NK0hyi4L3s7HwVRdGpFf3kG9hF60Fm', NULL, '+237694142621', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:48:33', '2025-07-01 13:48:33', NULL, NULL, NULL, NULL, 1, NULL),
(302, '+237656520367', 'user@gmail.com', '$2y$10$r048I/mxDj1mYpD162sw7O3u3lfnOUpcS.UgKKY2XAIeX.8M9DZS.', NULL, '+237656520367', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:50:54', '2025-07-01 13:50:54', NULL, NULL, NULL, NULL, 1, NULL),
(303, '+237695956618', 'user@gmail.com', '$2y$10$.SE4h51ycbXO48GXkRZvReCOMspsvTJKyrylsclExA8Re/mv3u36m', NULL, '+237695956618', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:51:30', '2025-07-01 13:51:30', NULL, NULL, NULL, NULL, 1, NULL),
(304, '+237674565504', 'user@gmail.com', '$2y$10$TvpevAxPvgDaANO.GCzxlOAnL8pR8C.ewUY6Vr3QF9O0ztR6Z36jK', NULL, '+237674565504', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:53:57', '2025-07-01 13:53:57', NULL, NULL, NULL, NULL, 1, NULL),
(305, '+237 67704495', 'user@gmail.com', '$2y$10$ENDjNAx/ZgRcrVCv43iM5Os7oKKjN9nPoFDMQT73tCm49VHn6G3U6', NULL, '+237 67704495', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:54:16', '2025-07-01 13:54:16', NULL, NULL, NULL, NULL, 1, NULL),
(306, '+237693270013', 'user@gmail.com', '$2y$10$m6M5azj9I/WewYQb4JiOQum/c2RkC03mDw9L0ajDrpadEjvHyzhb6', NULL, '+237693270013', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:58:23', '2025-07-01 13:58:23', NULL, NULL, NULL, NULL, 1, NULL),
(307, '+237658530414', 'user@gmail.com', '$2y$10$IvYG.an0gwaYPrWIIFE2kuGXGgq6DhyoVuBLtVXV8a9YU0/RAhG9m', NULL, '+237658530414', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 13:59:03', '2025-07-01 13:59:03', NULL, NULL, NULL, NULL, 1, NULL),
(308, '+237695454368', 'user@gmail.com', '$2y$10$hCrY9RmeyrOsIv7v0R6p5.zK/kjUNMX/u6eHGmkyQYrzezlq5lHD6', NULL, '+237695454368', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 14:07:53', '2025-07-01 14:07:53', NULL, NULL, NULL, NULL, 1, NULL),
(309, '+237698081976', 'user@gmail.com', '$2y$10$hkneODoBsd.jf86udwbhvutqkL0ySqdJstsmmVUCYxOo.b24pemG.', NULL, '+237698081976', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 14:13:35', '2025-07-01 14:18:17', NULL, NULL, NULL, NULL, 1, NULL),
(310, '+237694967222', 'user@gmail.com', '$2y$10$i/aaF8JyHVmp76EvJGIeauMg7OefJkQvIL6htuaUIw3L7ZMiN32cm', NULL, '+237694967222', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 14:17:47', '2025-07-01 14:17:47', NULL, NULL, NULL, NULL, 1, NULL),
(311, '+237 69082241', 'user@gmail.com', '$2y$10$y6pzB0yd4AAeb1jVB5LjiOV83K3gJl81kXkeNLIpgB8Ss.EUNluyi', NULL, '+237 69082241', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 14:20:32', '2025-07-01 14:20:32', NULL, NULL, NULL, NULL, 1, NULL),
(312, '+237693246568', 'user@gmail.com', '$2y$10$0Q1U.QWUrWZAcnJZwZiTz.XABKJhlKyzllYHTy44QfVrR9Kj6iCx2', NULL, '+237693246568', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 14:26:01', '2025-07-01 14:26:01', NULL, NULL, NULL, NULL, 1, NULL),
(313, '+237681313117', 'user@gmail.com', '$2y$10$LKeFrd94.fzwoGOVtzB48eHnDGDX1Ej84WaAuh1BgOCYyoheeEMsC', NULL, '+237681313117', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 14:33:32', '2025-07-01 14:33:32', NULL, NULL, NULL, NULL, 1, NULL),
(314, '+237652137665', 'user@gmail.com', '$2y$10$Yc2g.qUcv5qPbaT3HEgcp.cM.HcrZ5HA8wQ0nJYhPqgPauMmebKX.', NULL, '+237652137665', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 14:35:26', '2025-07-01 14:35:26', NULL, NULL, NULL, NULL, 1, NULL),
(315, '+237676239554', 'user@gmail.com', '$2y$10$AwLSapa7dVaUbdRZiRaFEuC0.ayvk4wlAU9W33gOqGx5D91O8/dBO', NULL, '+237676239554', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 14:39:48', '2025-07-01 14:39:48', NULL, NULL, NULL, NULL, 1, NULL),
(316, '2407040808', 'user@gmail.com', '$2y$10$5ORVSIRbZON7szh1R97KQe4S/jObFslKYXl98Misv6cdIUppCRewS', NULL, '2407040808', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 14:45:57', '2025-07-01 14:45:57', NULL, NULL, NULL, NULL, 1, NULL),
(317, '+237672239040', 'user@gmail.com', '$2y$10$Y71XQytNs5mLbo4NE7xeseNkrM12h.h/VlhNkmbBTr5P2vnZg90ta', NULL, '+237672239040', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 14:51:28', '2025-07-01 14:51:28', NULL, NULL, NULL, NULL, 1, NULL),
(318, '+237673602389', 'user@gmail.com', '$2y$10$kDXsI77w3EHDrbkL1o3UmO0wvtEBwWFB7U3.uxxgJ6ocxAucZe3oi', NULL, '+237673602389', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 14:54:57', '2025-07-01 14:54:57', NULL, NULL, NULL, NULL, 1, NULL),
(319, '+237654409971', 'user@gmail.com', '$2y$10$GvWx3e6aHlhd2/KFxi1oC.a0vKg24hks/qj.LFMaguiEVNR8ITAtS', NULL, '+237654409971', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:00:14', '2025-07-01 15:00:14', NULL, NULL, NULL, NULL, 1, NULL),
(320, '+237694607594', 'user@gmail.com', '$2y$10$1vXAAqLbDDnPOkAFKrRnm.Dj3.1I2yd89eVcvQ0kh.rwtu8wfw6/e', NULL, '+237694607594', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:01:28', '2025-07-01 15:06:47', NULL, NULL, NULL, NULL, 1, NULL),
(321, '+237654144492', 'user@gmail.com', '$2y$10$do9kSLLL0vpMl5zBBzyi2.QC4K6aS5tu0EyW84qVn68Mf868UdGIe', NULL, '+237654144492', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:02:38', '2025-07-01 15:02:38', NULL, NULL, NULL, NULL, 1, NULL),
(322, 'Bomra', 'bomrawosse@gmail.com', '$2y$10$peb2XKHsSzkJwRK.wtRaNeDOkiihhvvib4KHVvhfXeX64pOpzamrG', NULL, '+237656143414', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:02:45', '2025-07-01 15:32:41', NULL, NULL, NULL, NULL, 1, NULL),
(323, '+237699348581', 'user@gmail.com', '$2y$10$Ne2EOccOQB1ZLJKsrTuRV.RdZ8wc9rJ1NDgPsUFJRydEnkRdmF47q', NULL, '+237699348581', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:05:50', '2025-07-01 15:05:50', NULL, NULL, NULL, NULL, 1, NULL),
(324, '+237653285084', 'user@gmail.com', '$2y$10$t3RXIQFKBxKvvJzf9d59gecwB/aPL8fXofSBVsvcJ.hEZEOhBCbhW', NULL, '+237653285084', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:13:32', '2025-07-01 15:13:32', NULL, NULL, NULL, NULL, 1, NULL),
(325, '+237670650600', 'user@gmail.com', '$2y$10$z6T7Bp1Q779jl9rVKCUVpuzyaGcAwIfqkcH3ki5f0DaQ/1gfPz8hm', NULL, '+237670650600', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:16:44', '2025-07-01 15:16:44', NULL, NULL, NULL, NULL, 1, NULL),
(326, '+237656304244', 'user@gmail.com', '$2y$10$K.5qHesURS3TTju97J8pC.tcvHxxj3eWyJY46EvUzwJuDw.wE9EqW', NULL, '+237656304244', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:20:59', '2025-07-01 15:20:59', NULL, NULL, NULL, NULL, 1, NULL),
(327, '+237679660146', 'user@gmail.com', '$2y$10$MOumtT7CYRlHISMF8.RbuOEH.KZsW1xOlaflvm1/mBO57zN4.Bszi', NULL, '+237679660146', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:22:09', '2025-07-01 15:22:09', NULL, NULL, NULL, NULL, 1, NULL),
(328, '+237679528958', 'user@gmail.com', '$2y$10$DBQKmghgmR7MDzl6yFt0eOrxNJjYTsu.WxoHhuvkXljasvMw67H0O', NULL, '+237679528958', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:22:40', '2025-07-01 15:22:40', NULL, NULL, NULL, NULL, 1, NULL),
(329, '+237675828094', 'user@gmail.com', '$2y$10$qEfBY4f0a63mdCHYWiK4mur.N.ZDaZanaKDMSUn7fp7xIdf55.lqG', NULL, '+237675828094', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:23:38', '2025-07-01 15:23:38', NULL, NULL, NULL, NULL, 1, NULL),
(330, '+237670860410', 'user@gmail.com', '$2y$10$/8NeC7jc92rLlp5OXbkKze3/x3gOn8gBA7QMK4tmVFT1gHmVrejpS', NULL, '+237670860410', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:24:12', '2025-07-01 15:24:12', NULL, NULL, NULL, NULL, 1, NULL),
(331, '+237699177380', 'user@gmail.com', '$2y$10$7yMXFkIF2nf2rEB7Vc4gpu/I.dkoYNypPQIQliNjLIRXIry8aUjw.', NULL, '+237699177380', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:25:10', '2025-07-01 15:25:10', NULL, NULL, NULL, NULL, 1, NULL),
(332, '+237671101278', 'user@gmail.com', '$2y$10$cRRcklhckFpaPbXCRw1No.UQwjaz3jW2.ZuE27UVl5sZ4.8HNrdle', NULL, '+237671101278', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:26:00', '2025-07-01 15:26:00', NULL, NULL, NULL, NULL, 1, NULL),
(333, '+237694750573', 'user@gmail.com', '$2y$10$y4DnS4M7wCI.Phym/a8qZeyZD0Fi2A6b6LveAEJhR7hSbhtZt1zUu', NULL, '+237694750573', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:27:03', '2025-07-01 15:27:03', NULL, NULL, NULL, NULL, 1, NULL),
(334, '+237670157072', 'user@gmail.com', '$2y$10$0n2nX4bhRR1AYXyxZ5iXyOnQM7DniVywIBCjMpyf6EC.N2jIlA3Gm', NULL, '+237670157072', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:27:32', '2025-07-01 15:27:32', NULL, NULL, NULL, NULL, 1, NULL),
(335, '+237 69936027', 'user@gmail.com', '$2y$10$s68//8nGQ/vpOeEPk5ERhONb9NkL6UHnRl0qandzas/Li2YU2xYiu', NULL, '+237 69936027', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:28:37', '2025-07-01 15:28:37', NULL, NULL, NULL, NULL, 1, NULL),
(336, '+237699360275', 'user@gmail.com', '$2y$10$7pY0jNFcwpU9PUYab4moW.a4jw98Kf51D5e1XwpSMxubFIsWAUTMK', NULL, '+237699360275', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:29:11', '2025-07-01 15:29:11', NULL, NULL, NULL, NULL, 1, NULL),
(337, '+237679116142', 'user@gmail.com', '$2y$10$tKYvz5flzYhtwBco1xrXnOQmQTC5rd/F1q3.6Lt2K.wPgJ45PiRnm', NULL, '+237679116142', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:35:17', '2025-07-01 15:48:28', NULL, NULL, NULL, NULL, 1, NULL),
(338, '+237 96387904', 'user@gmail.com', '$2y$10$fgpUpT7ATOtskj0bIcgxKe58hpwDqG3kXA8bfc7T.rSXAwgDxaYC2', NULL, '+237 96387904', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:36:58', '2025-07-01 15:36:58', NULL, NULL, NULL, NULL, 1, NULL),
(339, '+237655275874', 'user@gmail.com', '$2y$10$YUyPjqRNmXxZ5m/rZwXi4.FpqNlK1jAF.ZZfWdOSFAx7nhtjYwEy2', NULL, '+237655275874', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:38:46', '2025-07-01 15:38:46', NULL, NULL, NULL, NULL, 1, NULL),
(340, '+237 51007330', 'user@gmail.com', '$2y$10$1K.QIMvZgMDph69qbHglt.MpxZvNAI2XO.B5f9IArjnAC2C8rZsVO', NULL, '+237 51007330', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:39:09', '2025-07-01 15:39:09', NULL, NULL, NULL, NULL, 1, NULL),
(341, '+237699469557', 'user@gmail.com', '$2y$10$gpfnfWGDlisH1LmjUArreOf.TRc8ZJcgIMvNENgIkVlEXXrmoJe.i', NULL, '+237699469557', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:49:28', '2025-07-01 15:49:28', NULL, NULL, NULL, NULL, 1, NULL),
(342, '+237691580655', 'user@gmail.com', '$2y$10$o.mDNP4v88CHOQjehlChneErNWBLOWRsHDFmXMtFG99sXx1l4I4TG', NULL, '+237691580655', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:53:34', '2025-07-01 15:53:34', NULL, NULL, NULL, NULL, 1, NULL),
(343, '+237697331888', 'user@gmail.com', '$2y$10$g0pMxDqTSRHKMXyK1xmjUOS98F/2AaGCDa7PZ6no9MbISaA4t9K.S', NULL, '+237697331888', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:54:27', '2025-07-01 15:54:27', NULL, NULL, NULL, NULL, 1, NULL),
(344, '+237670604743', 'user@gmail.com', '$2y$10$90CPIFCCLcRC2/CPckR2KuNnl3fbUYGTZW3b2KxKFIpIVqs7Nhl2G', NULL, '+237670604743', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 15:58:47', '2025-07-01 15:58:47', NULL, NULL, NULL, NULL, 1, NULL),
(345, '+237654750734', 'user@gmail.com', '$2y$10$wdEpJTxEvi.wqptF.n3P0O/zD9ic8gtj98BkAr9eJFSkF8i1s8z0G', NULL, '+237654750734', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:03:23', '2025-07-01 16:03:23', NULL, NULL, NULL, NULL, 1, NULL),
(346, '4510140513355833', 'user@gmail.com', '$2y$10$LssbMfOU7nRA3HJdd6Plf.Q3FXOH7fGSJK0jum7okgxW/NPRdFfwW', NULL, '4510140513355833', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:03:56', '2025-07-01 16:03:56', NULL, NULL, NULL, NULL, 1, NULL),
(347, '+237670875042', 'user@gmail.com', '$2y$10$wrsCb8dQwufFuJlAKU45cOeoIbrzb1O65dP44dTrN4g4t6wOg2DG.', NULL, '+237670875042', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:05:10', '2025-07-01 16:05:10', NULL, NULL, NULL, NULL, 1, NULL),
(348, '+237655207657', 'user@gmail.com', '$2y$10$00lOepCyOlQKAXdC4ABEF.NcDGph6cNZ3YXqru9/njYc27bvAecmW', NULL, '+237655207657', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:13:36', '2025-07-01 16:13:36', NULL, NULL, NULL, NULL, 1, NULL),
(349, '+237 81941662', 'user@gmail.com', '$2y$10$jm.nXkZ2Wy5f0W4uuJ4KUu29QQlst0szb4JI14f2fiN1V7U98As7K', NULL, '+237 81941662', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:18:33', '2025-07-01 16:18:33', NULL, NULL, NULL, NULL, 1, NULL),
(350, '+237694264429', 'user@gmail.com', '$2y$10$pZH.ZoyKih/t1Zd0FKxeUeA5mPElEAuwVwtn.P5frhO8TO3ismm56', NULL, '+237694264429', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:25:10', '2025-07-01 16:25:10', NULL, NULL, NULL, NULL, 1, NULL),
(351, '+237677090261', 'user@gmail.com', '$2y$10$1skzvsLcpihvY0xo6HgOBeDMjJFnxXaB4Tt/eMaTXyOENv432aAG2', NULL, '+237677090261', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:29:58', '2025-07-01 16:29:58', NULL, NULL, NULL, NULL, 1, NULL),
(352, '+237695591709', 'user@gmail.com', '$2y$10$YbHdhyazCguSFpb2mOlGZOcKixzPIz8pZAS6eEJJejFSZP4NFqznG', NULL, '+237695591709', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:32:41', '2025-07-01 16:32:41', NULL, NULL, NULL, NULL, 1, NULL),
(353, '+237691780593', 'user@gmail.com', '$2y$10$03t2lvotx7BZAupng4kNgeOvH0oX1Eo7UgUaOTo4t.4r7h3hCicRu', NULL, '+237691780593', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:33:14', '2025-07-01 16:33:14', NULL, NULL, NULL, NULL, 1, NULL),
(354, '+237651341047', 'user@gmail.com', '$2y$10$fxzOWDh5CbZP0CK3CUcgPeas/PoWSatK2qBv46SmU3x4l99nO9292', NULL, '+237651341047', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:34:01', '2025-07-01 16:40:28', NULL, NULL, NULL, NULL, 1, NULL),
(355, '+237671972652', 'user@gmail.com', '$2y$10$N9ilh79h2h7rwjAeuxrjt.QJuBb0Q6npPWb0I.nJfqqMZpTFrgvG2', NULL, '+237671972652', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:38:55', '2025-07-01 16:38:55', NULL, NULL, NULL, NULL, 1, NULL),
(356, '+237698671708', 'user@gmail.com', '$2y$10$sE740nnvC/ia5Sb.6JmblOmiEzJdaH0BfuzW0jGnp.5LQpAnprJfq', NULL, '+237698671708', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:42:08', '2025-07-01 16:42:08', NULL, NULL, NULL, NULL, 1, NULL),
(357, '+237 96789984', 'user@gmail.com', '$2y$10$1aZggQuYf7GTlQOm46Df3eJPSBoKk1BXbmO7sIFmIIvzAXRw2bvsm', NULL, '+237 96789984', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:47:37', '2025-07-01 16:47:37', NULL, NULL, NULL, NULL, 1, NULL),
(358, '+237693787468', 'user@gmail.com', '$2y$10$b1e8dFKu2LGHrhWnK9iA0exdXWqIdA1RRLZmeSkeKjYy739gBxFvC', NULL, '+237693787468', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:47:55', '2025-07-01 16:47:55', NULL, NULL, NULL, NULL, 1, NULL),
(359, '+237696789984', 'user@gmail.com', '$2y$10$5W7jk.OcWj8qpDiu3GcetOei5cLsFuxbr0v9T/qU2yyEgHbPZ8jlS', NULL, '+237696789984', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:54:02', '2025-07-01 16:54:02', NULL, NULL, NULL, NULL, 1, NULL),
(360, '+237695112028', 'user@gmail.com', '$2y$10$N/4INElByzYuuaNCzk5Z/eH6f0/wIfqwvf68UHCqhZN2fogWolg9i', NULL, '+237695112028', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:56:38', '2025-07-01 16:56:38', NULL, NULL, NULL, NULL, 1, NULL),
(361, '+237 69763103', 'user@gmail.com', '$2y$10$9p54Hvx6ERBvjv/wn8Lob.EvFH8qCVYPA0lsNymZX0sGyWjyuGF8u', NULL, '+237 69763103', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:58:01', '2025-07-01 16:58:01', NULL, NULL, NULL, NULL, 1, NULL),
(362, '+237698032828', 'user@gmail.com', '$2y$10$7pPe9pSjEpFECgjGUnczduyie62Y8Tr7atH0wrwwR/5n/aKU0dTZ.', NULL, '+237698032828', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:59:22', '2025-07-01 16:59:22', NULL, NULL, NULL, NULL, 1, NULL),
(363, '+237694236685', 'user@gmail.com', '$2y$10$RSV8gUSLwYYowppjAhFpF.v1RKN/4nx/Z.lq4KQ6VUH7G.7ucrJce', NULL, '+237694236685', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 16:59:41', '2025-07-01 16:59:41', NULL, NULL, NULL, NULL, 1, NULL),
(364, '+237697631039', 'user@gmail.com', '$2y$10$c7Bs1GDi3ozkaMUMWNo1gOgLmC.3scBdo15jPVWWkAESuV/Yi1H82', NULL, '+237697631039', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:00:32', '2025-07-01 17:00:32', NULL, NULL, NULL, NULL, 1, NULL),
(365, '+237683860586', 'user@gmail.com', '$2y$10$7bXxF7smC7BC0fUSZR4kyOyooNxch/yGijEkCXyHu6mySu5X/IBW2', NULL, '+237683860586', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:03:45', '2025-07-01 17:03:45', NULL, NULL, NULL, NULL, 1, NULL),
(366, '+237 80354087', 'user@gmail.com', '$2y$10$hN2SCfG/j9nsEApcBcnoh.MDQXxx7HmhPFfJ5j5rtaU3X.ymiPbf.', NULL, '+237 80354087', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:04:35', '2025-07-01 17:04:35', NULL, NULL, NULL, NULL, 1, NULL),
(367, '+23780354087', 'user@gmail.com', '$2y$10$8TtV4/tE0kaziftQXCfOHuSNPBfyPvS0JU7O2SiuSS52qccnz/b7m', NULL, '+23780354087', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:08:07', '2025-07-01 17:08:07', NULL, NULL, NULL, NULL, 1, NULL),
(368, '+237659871700', 'user@gmail.com', '$2y$10$mB5BXHpKeSk85.LBdmASEe30ycStECzJe.nhrgz17TmYhGOX8fnQC', NULL, '+237659871700', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:09:42', '2025-07-01 17:09:42', NULL, NULL, NULL, NULL, 1, NULL),
(369, '+237678250763', 'user@gmail.com', '$2y$10$eSDO5WWKkySw7ymbTnrZt.NpC.ntd9VWCbH5aO7BERSSPbGg/y2cm', NULL, '+237678250763', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:10:51', '2025-07-01 17:10:51', NULL, NULL, NULL, NULL, 1, NULL),
(370, '+237695569922', 'user@gmail.com', '$2y$10$Pkv0ugmz3qtkNnv8xLWTXea9ubWyE5NFE7Nm4qDzhTmOqcEg/yvza', NULL, '+237695569922', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:12:34', '2025-07-01 17:12:34', NULL, NULL, NULL, NULL, 1, NULL),
(371, '+237655133243', 'user@gmail.com', '$2y$10$RDhoWi7g/mlBhjZagq/K3O0oUvRYVclX0qHMjanNvpTgiaCnhA56m', NULL, '+237655133243', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:14:17', '2025-07-01 17:14:17', NULL, NULL, NULL, NULL, 1, NULL),
(372, '+237680354087', 'user@gmail.com', '$2y$10$ZX07hd7erYB/aRvIouSPPuRn63RDg//lKYnlnTO55wWyGTcfdvsOO', NULL, '+237680354087', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:14:36', '2025-07-01 17:14:36', NULL, NULL, NULL, NULL, 1, NULL),
(373, '+237 57643756', 'user@gmail.com', '$2y$10$2YXXFxhigWiAPnt.HNolzOfEJY76QHXXgryh2r8AvloAE/oePBmTS', NULL, '+237 57643756', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:15:33', '2025-07-01 17:15:33', NULL, NULL, NULL, NULL, 1, NULL),
(374, 'Yannickdrums', 'isaacedjoo@gmail.com', '$2y$10$AGaCSmgq.AvZqUlmcs7CmeHDPIIfr47P1j3F39w/1K9dTqqGkeyQu', NULL, '+237 57643756', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:21:33', '2025-07-01 17:24:42', NULL, NULL, NULL, NULL, 1, NULL),
(375, '+237679810403', 'user@gmail.com', '$2y$10$mIayby0/FexeNlTglp5WuuOTeVg2.6OSt/KFhbM/G3BW2PulbdxZm', NULL, '+237679810403', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:24:26', '2025-07-01 17:24:26', NULL, NULL, NULL, NULL, 1, NULL),
(376, '+237 67652686', 'user@gmail.com', '$2y$10$vQOjGcYm69yFNdodISX/LeH.jiLjnew6kicdkZkdhTfQ/KdAe2PFG', NULL, '+237 67652686', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:26:13', '2025-07-01 17:26:13', NULL, NULL, NULL, NULL, 1, NULL),
(377, '+237 67946135', 'user@gmail.com', '$2y$10$xnuyKj.YiVs2ASPuJ8nnSO4pZv/8/dZEgaAFJpZkFJNv9tUfIiBKG', NULL, '+237 67946135', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:26:18', '2025-07-01 17:26:18', NULL, NULL, NULL, NULL, 1, NULL),
(378, '+237653873359', 'user@gmail.com', '$2y$10$Pr..25cs9ZcpEoJCE5zR6.Sy4A6sJplOZpupQDUSd25VT3bSch8WO', NULL, '+237653873359', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:27:23', '2025-07-01 17:27:23', NULL, NULL, NULL, NULL, 1, NULL),
(379, '+237679461353', 'user@gmail.com', '$2y$10$lPkG5UqY5pBEqt8TbdJWTeYhOQ5u/zL1mGg96OXHYPnrjHySkWh5.', NULL, '+237679461353', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:28:45', '2025-07-01 17:28:45', NULL, NULL, NULL, NULL, 1, NULL),
(380, '+237690311754', 'user@gmail.com', '$2y$10$S4VXdsC4w9cqr079oy3TWeydCe3EFccCHGvOAzIV2vUabq.d2SKdu', NULL, '+237690311754', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:35:12', '2025-07-01 17:35:12', NULL, NULL, NULL, NULL, 1, NULL),
(381, '+237698792148', 'user@gmail.com', '$2y$10$qLDPMVu71u2rP4JpfQ3geeQulWyhKWmsgR6IuHcCSHbBVdqGsTipu', NULL, '+237698792148', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:35:50', '2025-07-01 17:35:50', NULL, NULL, NULL, NULL, 1, NULL),
(382, '+237676526869', 'user@gmail.com', '$2y$10$ahX1UAdzzpem3Cx4KrSUM.criMrKz42sYEMDNCgztpYjkCBh4qGWS', NULL, '+237676526869', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:35:51', '2025-07-01 17:35:51', NULL, NULL, NULL, NULL, 1, NULL),
(383, '+237675271833', 'user@gmail.com', '$2y$10$V9uS177pUkpFQlL2j/4nQeWvz.FWc/1AnW0Hrpdvjh4V7ZkG5W5Ja', NULL, '+237675271833', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:37:59', '2025-07-01 17:37:59', NULL, NULL, NULL, NULL, 1, NULL),
(384, '+21655289869', 'user@gmail.com', '$2y$10$jVfDSDbx.qujNOy5Iq65ROFYLgY.2A2HgoDJJvFjNa/7lIXmQ3lJ6', NULL, '+21655289869', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:40:51', '2025-07-01 17:40:51', NULL, NULL, NULL, NULL, 1, NULL),
(385, '+237 72811207', 'user@gmail.com', '$2y$10$GHEjUsIYYpo/EOZV7EFJmuGXQGurxhd7dKKbdomeGFz4kFfFS4cIy', NULL, '+237 72811207', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:45:08', '2025-07-01 17:45:08', NULL, NULL, NULL, NULL, 1, NULL),
(386, '+23672750041', 'user@gmail.com', '$2y$10$y0hZ7txAagwoCiyVCBNQAOSrHLmPtFsZJuGOdmNoPiKWxxy13x4vy', NULL, '+23672750041', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:50:16', '2025-07-01 17:50:16', NULL, NULL, NULL, NULL, 1, NULL),
(387, '+23774411780', 'user@gmail.com', '$2y$10$r.8XGKwhIc14D/ncV.4cge.7/vlZyjCHh4zuas9KUfamT9VPqTOh.', NULL, '+23774411780', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 17:55:51', '2025-07-01 17:55:51', NULL, NULL, NULL, NULL, 1, NULL),
(388, '+237657109891', 'user@gmail.com', '$2y$10$U/R1BiZGH6crAsnlJfjmKeu3gF8M90iCqsl/DGmjPIjEHugc0.dtq', NULL, '+237657109891', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:04:20', '2025-07-01 18:04:20', NULL, NULL, NULL, NULL, 1, NULL),
(389, '+237670265762', 'user@gmail.com', '$2y$10$kohM0zbzkJ5IV7r6ouCE7u2k08N1258NpGJIL3t2QsMQqmWioZWrK', NULL, '+237670265762', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:05:20', '2025-07-01 18:05:20', NULL, NULL, NULL, NULL, 1, NULL),
(390, '+237696766677', 'user@gmail.com', '$2y$10$tT04WZaThGucp/FwUTRLXuUYQsCd3S3KsKo9l9C4Xb2Yc/.1Mp572', NULL, '+237696766677', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:09:13', '2025-07-01 18:09:13', NULL, NULL, NULL, NULL, 1, NULL),
(391, '+237652811111', 'user@gmail.com', '$2y$10$lwiInw.OPxLeA8Kvf3UFdu.rTYXUG5/ua.gH38hTJMzm50oQbltp6', NULL, '+237652811111', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:09:32', '2025-07-01 18:09:32', NULL, NULL, NULL, NULL, 1, NULL),
(392, '+236 82287360', 'user@gmail.com', '$2y$10$bG/d8ekfbpAnE.XjBGQIVuRFP4d7joQqaQhRPS3sWs8QMcXrdvNWG', NULL, '+236 82287360', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:11:45', '2025-07-01 18:11:45', NULL, NULL, NULL, NULL, 1, NULL),
(393, '699207841', 'user@gmail.com', '$2y$10$hMidpgnCpJAUfMjm1wzQdu9jWtvedBskHzGpfBQMZbhlaGLC7ZEY6', NULL, '699207841', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:11:58', '2025-07-01 18:11:58', NULL, NULL, NULL, NULL, 1, NULL),
(394, '+236 72724070', 'user@gmail.com', '$2y$10$zLLsoeWlaxMJCKuCZmrBEO7MSKvyf0ZNrmxd7lSsgv06V3qAeNoJm', NULL, '+236 72724070', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:20:24', '2025-07-01 18:20:24', NULL, NULL, NULL, NULL, 1, NULL),
(395, '+221 78183696', 'user@gmail.com', '$2y$10$.PnIXTop1I91U0RJjPqDJ.ZExw1RdveHxk4Nqw5S0CVnjKMDZR8XK', NULL, '+221 78183696', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:21:22', '2025-07-01 18:21:22', NULL, NULL, NULL, NULL, 1, NULL),
(396, '+23672566749', 'user@gmail.com', '$2y$10$xNeCECuxpj6QqFaTgBQxpO5pS4/zt4RRSazxRmNZMwherolJHMyZa', NULL, '+23672566749', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:29:53', '2025-07-01 18:29:53', NULL, NULL, NULL, NULL, 1, NULL),
(397, '+237690921833', 'user@gmail.com', '$2y$10$oGnn.EQOoKJe2Fb6flyw6.dn0vLI5NXBLAcpjGLIF0xELYpyy85xG', NULL, '+237690921833', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:38:13', '2025-07-01 18:38:13', NULL, NULL, NULL, NULL, 1, NULL),
(398, '+237658719048', 'user@gmail.com', '$2y$10$q1KZNo2wTQDBP1FD4zcv9efm.m/uYrmpL/M/bBCraHh0DU.LJTTcG', NULL, '+237658719048', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:39:10', '2025-07-01 18:39:10', NULL, NULL, NULL, NULL, 1, NULL),
(399, '+237657272462', 'user@gmail.com', '$2y$10$LuiCm8llPHrrb4TLtr1SJ.8BeWjTY2o5XvhhFA8c/aO1pW5fBCWga', NULL, '+237657272462', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:41:14', '2025-07-01 18:41:14', NULL, NULL, NULL, NULL, 1, NULL),
(400, '+237655525919', 'user@gmail.com', '$2y$10$29.wbemu4WIJEkw6XSVzvuNs0Bv1kxd.Sv0zh52PJD80NQQfCYtPy', NULL, '+237655525919', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:42:29', '2025-07-01 18:42:29', NULL, NULL, NULL, NULL, 1, NULL),
(401, '+237696301201', 'user@gmail.com', '$2y$10$YdyBAB0JegnFrK6ztsE75ub4ECzYkUfz7jDjPxRTYCP4sQykfMT.C', NULL, '+237696301201', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:45:18', '2025-07-01 18:45:18', NULL, NULL, NULL, NULL, 1, NULL),
(402, '+237683077492', 'user@gmail.com', '$2y$10$.znKRowVGLSFF0.TsfI5hudGVuN9KMn9sXrMlmfuSYq9lhY3E96Oa', NULL, '+237683077492', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:54:35', '2025-07-01 18:54:35', NULL, NULL, NULL, NULL, 1, NULL),
(403, '+237655215652', 'user@gmail.com', '$2y$10$kSGBC2EcE5qUFCWCJwGNtePBT5SYamEbWNR0BxW7IjsJIgO4W7aAO', NULL, '+237655215652', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:55:38', '2025-07-01 18:55:38', NULL, NULL, NULL, NULL, 1, NULL),
(404, '+237690779028', 'user@gmail.com', '$2y$10$geA4Su.5dZNTnCwS7ekx2utJfab7cEqHVyYtlhwFrn/FIaUJVmvti', NULL, '+237690779028', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:56:22', '2025-07-01 18:56:22', NULL, NULL, NULL, NULL, 1, NULL),
(405, '+237679499631', 'user@gmail.com', '$2y$10$r3buKU9/tS2uzzbSuhCdv.wB7ECa4IKVjem.t5gYfh.MjFR.UQSFS', NULL, '+237679499631', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:57:43', '2025-07-01 18:57:43', NULL, NULL, NULL, NULL, 1, NULL),
(406, '+237 67312425', 'user@gmail.com', '$2y$10$WmGfK0qjH7/ApfpiXbmuk.wFUKHP.bDj9E1/gz5zjX7XQHqSTHefa', NULL, '+237 67312425', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:57:47', '2025-07-01 18:57:47', NULL, NULL, NULL, NULL, 1, NULL),
(407, '+237677690629', 'user@gmail.com', '$2y$10$DIeK45hAXxFE8MRefTvaUeHIBGuWKy56LZE.C6/5Bbamzcr6JH9Wu', NULL, '+237677690629', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:57:47', '2025-07-01 18:57:47', NULL, NULL, NULL, NULL, 1, NULL),
(408, '+237654112502', 'user@gmail.com', '$2y$10$r48bIEDxuBy84Fr4ADkkJuBLpjLof.06GQu6WA.pyiVWMlLaSKrCu', NULL, '+237654112502', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 18:59:32', '2025-07-01 18:59:32', NULL, NULL, NULL, NULL, 1, NULL),
(409, '656244400', 'user@gmail.com', '$2y$10$ZWfmMnL79pkLxyV38oAM2uTyZMvg1Cxx9x9JTy1xwb5XAs3H5Ybf6', NULL, '656244400', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 19:01:31', '2025-07-01 19:07:57', NULL, NULL, NULL, NULL, 1, NULL),
(410, '+237696311990', 'user@gmail.com', '$2y$10$YKbyusodA0Kl5WwWa6j62.dIEcDc2/k8cehwRplFIkeQalgyO0WaO', NULL, '+237696311990', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 19:01:59', '2025-07-01 19:01:59', NULL, NULL, NULL, NULL, 1, NULL),
(411, '+237654287563', 'user@gmail.com', '$2y$10$QdcLHC1UMK277IYHssnE7.gPyhS1IiWtwvB5v7wzbThhxrWFbi4Vm', NULL, '+237654287563', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 19:11:27', '2025-07-01 19:11:27', NULL, NULL, NULL, NULL, 1, NULL),
(412, '+237671624501', 'user@gmail.com', '$2y$10$e3Vcl0SxLXLGCpX56CMJcObqQnSzKnySINMlxJy0WecU9iWD/YkYe', NULL, '+237671624501', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 19:11:31', '2025-07-01 19:11:31', NULL, NULL, NULL, NULL, 1, NULL),
(413, '+237657727946', 'user@gmail.com', '$2y$10$Q2HzFzdeDs/kvKbnZenuOuiSwyuxZRK2SgVQOIYkYD1SSuZlEI1Ou', NULL, '+237657727946', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 19:17:09', '2025-07-01 19:17:09', NULL, NULL, NULL, NULL, 1, NULL),
(414, '+237671257466', 'user@gmail.com', '$2y$10$/QTpbV3K6zgQlShxXn/UmOfliJ3XBtxKf3fU9zhPu8nQboduqjq7S', NULL, '+237671257466', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 19:27:57', '2025-07-01 19:27:57', NULL, NULL, NULL, NULL, 1, NULL),
(415, '+237690940399', 'user@gmail.com', '$2y$10$bNysQ.CU3ZQtN1f.k4CmB.IkCbPD83xpUhWljWw9Newn5hpvuR9Ty', NULL, '+237690940399', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 19:31:45', '2025-07-01 19:31:45', NULL, NULL, NULL, NULL, 1, NULL),
(416, '+237670691253', 'user@gmail.com', '$2y$10$B4pEwNgGTWMtlleIjt8wDe4C5r3rk0o5DMMOSCp6Va0I3qx.R.Avi', NULL, '+237670691253', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 19:33:23', '2025-07-01 19:33:23', NULL, NULL, NULL, NULL, 1, NULL),
(417, '+237680678940', 'user@gmail.com', '$2y$10$OHwOrkpLf6M6HnB0Q02oD.afSaeKwSZlnEsDEf.DjkIF.GlmMHV9G', NULL, '+237680678940', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 19:34:38', '2025-07-01 19:34:38', NULL, NULL, NULL, NULL, 1, NULL),
(418, '+237699715738', 'user@gmail.com', '$2y$10$hh2CDW.CcufyjAtxLqAvXe/toYBqkBb6N2WdFcz7.IymB4Pj1BHvK', NULL, '+237699715738', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 19:38:00', '2025-07-01 19:38:00', NULL, NULL, NULL, NULL, 1, NULL),
(419, '+23768377', 'user@gmail.com', '$2y$10$B/fcyh31Lt2aemcHQlFC/.3aiheMWP8IBoEvT/pXTGulW4PvxbdxS', NULL, '+23768377', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 19:41:37', '2025-07-01 19:41:37', NULL, NULL, NULL, NULL, 1, NULL),
(420, '+237683777865', 'user@gmail.com', '$2y$10$yLU2G0Owyyp2Gw5waaxAkudBvldH.pWgByrOyH0H.OGYarANRviYC', NULL, '+237683777865', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 19:42:32', '2025-07-01 19:42:32', NULL, NULL, NULL, NULL, 1, NULL),
(421, 'Marguerite', 'maguionana@gmail.com', '$2y$10$xqookqhz7MzFxwl0JxF9v.3vGhIdvx505SpqO8cHjFbMn32lTG9Wy', NULL, '+237699715738', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 19:47:38', '2025-07-01 19:57:43', NULL, NULL, NULL, NULL, 1, NULL),
(422, '+237693314256', 'user@gmail.com', '$2y$10$koal5ZjWZSylD6vUcVzKbezpj8/kEFDWTc0BX3aDEdXtI7wYQkck.', NULL, '+237693314256', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 19:55:58', '2025-07-01 19:55:58', NULL, NULL, NULL, NULL, 1, NULL),
(423, '+237657371757', 'user@gmail.com', '$2y$10$s6yLMW1Q/KO3DIJZPTWMJOOPJX/zxgKlqt3s1W5a3Ir/6jm2.ivXu', NULL, '+237657371757', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 20:04:49', '2025-07-01 20:04:49', NULL, NULL, NULL, NULL, 1, NULL),
(424, '+237696590004', 'user@gmail.com', '$2y$10$kRqfWbrDgh3Y.bhNPLd7deRxpUg6yr7iSPsiw7NV.8C1lrOwsoi6m', NULL, '+237696590004', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 20:06:07', '2025-07-01 20:06:07', NULL, NULL, NULL, NULL, 1, NULL),
(425, '+237695591723', 'user@gmail.com', '$2y$10$Tiv8z/mX8tDe39iEzSDgOODIfl/oL17bh5wIuv/5Ke2T2JF.0gyKm', NULL, '+237695591723', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 20:11:26', '2025-07-01 20:11:26', NULL, NULL, NULL, NULL, 1, NULL),
(426, '676738155', 'user@gmail.com', '$2y$10$u4d0srF3hBO6ZAKzMikA.us8aHwFwymc9EjmJKdAmUM1jOMbXCaEi', NULL, '676738155', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 20:14:16', '2025-07-01 20:14:16', NULL, NULL, NULL, NULL, 1, NULL),
(427, '+237698475625', 'user@gmail.com', '$2y$10$UcZI/fITl2awB/ZypmztGO7Cdu3ZXQo.G5.k6Kd26bsugCj12NCru', NULL, '+237698475625', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 20:15:57', '2025-07-01 20:15:57', NULL, NULL, NULL, NULL, 1, NULL),
(428, '+237691872865', 'user@gmail.com', '$2y$10$NBnB/w0yo.wABexDW92TxOVB1Bc5cQDe83QNrC3X6/xiPbUzbB0xO', NULL, '+237691872865', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 20:16:45', '2025-07-01 20:16:45', NULL, NULL, NULL, NULL, 1, NULL),
(429, '+237691796708', 'user@gmail.com', '$2y$10$wmSxb1OGBiI4Wuz78N91NOaD30eFO7ppjg3FRN1NuRkz20C2aJA6y', NULL, '+237691796708', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 20:18:36', '2025-07-01 20:18:36', NULL, NULL, NULL, NULL, 1, NULL),
(430, '+23799307316', 'user@gmail.com', '$2y$10$yXZNCweWPAP.SXZVLrxVEeFZJPWsh87wJZf8rr/1Tfc.y/Nc6u3.C', NULL, '+23799307316', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 20:21:15', '2025-07-01 20:21:15', NULL, NULL, NULL, NULL, 1, NULL),
(431, '+237672571433', 'user@gmail.com', '$2y$10$axNmObQLctvDXGo7/BEwOuLcQTwZps1IFkKqSR8FDLWmSSHuX0vzS', NULL, '+237672571433', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 20:23:00', '2025-07-01 20:23:00', NULL, NULL, NULL, NULL, 1, NULL),
(432, '+221787562266', 'user@gmail.com', '$2y$10$G.729DhE/Ri/GE8CjhPkHOP71ln0e/G2awhs/a662UxBFliajJ1zW', NULL, '+221787562266', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 20:34:14', '2025-07-01 20:35:28', NULL, NULL, NULL, NULL, 1, NULL),
(433, '+237695932525', 'user@gmail.com', '$2y$10$fUxyp6fkK3op8RIA3XPDoeo0I3ua63IvBJ62y8pN9p83g9s1YbpL2', NULL, '+237695932525', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 20:38:28', '2025-07-01 20:38:28', NULL, NULL, NULL, NULL, 1, NULL),
(434, '690285600', 'user@gmail.com', '$2y$10$xFbLZtu9GOr8sXYqCR3Zg.WpVwFVtx5fMFG8a3XcszRPAVwAOnOze', NULL, '690285600', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 20:42:09', '2025-07-01 20:43:25', NULL, NULL, NULL, NULL, 1, NULL),
(435, '+237682129880', 'user@gmail.com', '$2y$10$X5McWG8kkydS/CIoNZIZpeBdzBkp5UFMDiiLAUVeJ6UPayu4KjGpG', NULL, '+237682129880', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 20:49:44', '2025-07-01 20:49:44', NULL, NULL, NULL, NULL, 1, NULL),
(436, '+237693898950', 'user@gmail.com', '$2y$10$f4qo7WXIpnSb7lHRr/rE3.yft6BafWoSj7qKy7haCITW1UwNK0Fpa', NULL, '+237693898950', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 20:52:07', '2025-07-01 20:52:07', NULL, NULL, NULL, NULL, 1, NULL),
(437, '+23772571433', 'user@gmail.com', '$2y$10$Gn7AS1rRpQjm.xpwCH.DxO/I2.L1TQzl7G3gDp9n1ZO7zN9/exzwO', NULL, '+23772571433', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 20:54:48', '2025-07-01 20:54:48', NULL, NULL, NULL, NULL, 1, NULL),
(438, '+237 69389895', 'user@gmail.com', '$2y$10$pfeKAS2v7cC1Pt2.xXklYus1sMw481xkyEZmBKj5wxzRbPHsDEM1m', NULL, '+237 69389895', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 20:57:27', '2025-07-01 20:57:27', NULL, NULL, NULL, NULL, 1, NULL),
(439, '+237697187861', 'user@gmail.com', '$2y$10$bkosvSeVeHO90GZu5d1Dj.B4xa1HZxRerrdP90LDbdnF2dZaMgreC', NULL, '+237697187861', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 21:02:05', '2025-07-01 21:02:05', NULL, NULL, NULL, NULL, 1, NULL),
(440, '+237674406868', 'user@gmail.com', '$2y$10$ZppjMotIWhj3n1eKhxYPe.eWD4VVG999OxBtGIt/cACQ1SFzdQC1u', NULL, '+237674406868', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 21:07:00', '2025-07-01 21:07:00', NULL, NULL, NULL, NULL, 1, NULL),
(441, '+237 67628169', 'user@gmail.com', '$2y$10$/q0UcX9jlMqmzpvqtVI9M.k9y8tVsy3s7GZZJCb58rELMD9qmc8sm', NULL, '+237 67628169', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 21:07:09', '2025-07-01 21:07:09', NULL, NULL, NULL, NULL, 1, NULL),
(442, '+237 68117776', 'user@gmail.com', '$2y$10$DhBiGOIJOkZzX8zNRCBeG.cxyOYNViQPNs3rz40EqOzTnML0LS3Lu', NULL, '+237 68117776', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 21:08:03', '2025-07-01 21:08:03', NULL, NULL, NULL, NULL, 1, NULL),
(443, '+237681329440', 'user@gmail.com', '$2y$10$GNmfbTNr5mpdByWNahepXe./BVo8zmYB..H7AemxxdhLbftxVB1M2', NULL, '+237681329440', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 21:09:37', '2025-07-01 21:09:37', NULL, NULL, NULL, NULL, 1, NULL),
(444, '+237697213862', 'user@gmail.com', '$2y$10$aX0DjOR2O2QiPvVFif3zX.ZxEGnH4iJp1yktuT2qV7tIQyk5Xs7na', NULL, '+237697213862', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 21:13:02', '2025-07-01 21:13:02', NULL, NULL, NULL, NULL, 1, NULL),
(445, 'ALYO SANDRINE AYMAR', 'alyosandrine@gmail.com', '$2y$10$dDlfgyOrcjKYeCk7J4.tc.A9V.cpJ0qJYp0twsZc2X5oc11d2WvnO', NULL, '+237694142621', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 21:13:31', '2025-07-01 21:21:06', NULL, NULL, NULL, NULL, 1, NULL),
(446, '+237699991785', 'user@gmail.com', '$2y$10$Um.gA5iC0xTxExEpiauLjORiBmOMsfrJqLDtibHVZ/1w/sqG5FNbi', NULL, '+237699991785', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 21:15:26', '2025-07-01 21:15:26', NULL, NULL, NULL, NULL, 1, NULL),
(447, '+237696449132', 'user@gmail.com', '$2y$10$xOhnvW/MPjcRAaJn9DPc/..VWViaDyznwStIc63illJrncPibFpjO', NULL, '+237696449132', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 21:26:44', '2025-07-01 21:26:44', NULL, NULL, NULL, NULL, 1, NULL),
(448, '+236 72303144', 'user@gmail.com', '$2y$10$Fwn.yOdwoA.5zLEN7wkRE.y.tmLy/U2gcsAHxwUPxyRxzrtgLmgli', NULL, '+236 72303144', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 21:28:02', '2025-07-01 21:36:37', NULL, NULL, NULL, NULL, 1, NULL),
(449, '+237673704486', 'user@gmail.com', '$2y$10$OaGvTxRC1MkmZ66oqV3RqOmoX1DlY2wz6aCO/qf0JFTslyExGJlJi', NULL, '+237673704486', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 21:34:04', '2025-07-01 21:34:04', NULL, NULL, NULL, NULL, 1, NULL),
(450, '+237693279064', 'user@gmail.com', '$2y$10$b/Kl600V/WnrOGbDN6mAeuyBvgVIcEAZ.r83Vo8uIkPy1IE8lc.6K', NULL, '+237693279064', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 21:34:49', '2025-07-01 21:34:49', NULL, NULL, NULL, NULL, 1, NULL),
(451, '+237694455748', 'user@gmail.com', '$2y$10$8zpaZT3lkkW7E4b0MJOvn.mrODGezShAta4/Escjbv/MQ5egp7yra', NULL, '+237694455748', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 21:42:54', '2025-07-01 21:42:54', NULL, NULL, NULL, NULL, 1, NULL),
(452, '+237697520586', 'user@gmail.com', '$2y$10$ZZL4L600Co72/uzaT91bAeDH5ytzBR4ds6vZGHZp/f1R7EevnlWvW', NULL, '+237697520586', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 21:45:31', '2025-07-01 21:45:31', NULL, NULL, NULL, NULL, 1, NULL),
(453, '+237697307273', 'user@gmail.com', '$2y$10$m.LrzqamMEsVqikJp2xenOPcWg5k.X7z851iPgTI0DRXPlMbbdiZm', NULL, '+237697307273', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 21:48:00', '2025-07-01 21:48:00', NULL, NULL, NULL, NULL, 1, NULL),
(454, '+237658221907', 'user@gmail.com', '$2y$10$y3SXbmrv75vZsQ1VuRtMie/VVjft0vkCHp2PwQRK0aF840l7lOVI2', NULL, '+237658221907', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 21:48:01', '2025-07-01 21:48:01', NULL, NULL, NULL, NULL, 1, NULL),
(455, '+237693516053', 'user@gmail.com', '$2y$10$QXeZ4LEsZ7W84ju/cE36lu96EYl0DjfO9P3mmZUu/JcpzRKjRx4yC', NULL, '+237693516053', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 21:59:31', '2025-07-01 21:59:31', NULL, NULL, NULL, NULL, 1, NULL),
(456, '+237698859132', 'user@gmail.com', '$2y$10$Q7J5E0Spw6MdCmRytlEGmehprEUpt3Y0e.e/.g6WrNdTTudt1dsVO', NULL, '+237698859132', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 22:02:46', '2025-07-01 22:02:46', NULL, NULL, NULL, NULL, 1, NULL);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `phone`, `company_name`, `role_id`, `biller_id`, `warehouse_id`, `is_active`, `is_deleted`, `created_at`, `updated_at`, `sign`, `stemp`, `otp`, `otp_time`, `otp_verify`, `additional_phone`) VALUES
(457, 'Natems', 'Natoumiyaemmanuel@gmail.com', '$2y$10$3Dy5QvyaxUqX1KWUf32Lyu6Uu./Gko5TuD7maoLcuuE81R3QpS9cS', NULL, '+237693130438', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 22:06:57', '2025-07-01 22:07:28', NULL, NULL, NULL, NULL, 1, NULL),
(458, '+237673190184', 'user@gmail.com', '$2y$10$piHwXe8NIcIDLT.6Fxq5wuKdCgBvNSWNKZO.x3SaFZ02cEzvSdp/u', NULL, '+237673190184', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 22:16:21', '2025-07-01 22:16:21', NULL, NULL, NULL, NULL, 1, NULL),
(459, '+237657916474', 'user@gmail.com', '$2y$10$FrUQExIFSO55UZNRXWoLleaxkIBFp7VTblQna72PQ29yvXu4yodky', NULL, '+237657916474', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 22:19:10', '2025-07-01 22:19:10', NULL, NULL, NULL, NULL, 1, NULL),
(460, '+237 98834863', 'user@gmail.com', '$2y$10$x/aEPwREMzVKkvgukpyTsuhYUNaga7Elq.ohMoUDeb80PO4L1h2c2', NULL, '+237 98834863', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 22:21:55', '2025-07-01 22:21:55', NULL, NULL, NULL, NULL, 1, NULL),
(461, '+23672765831', 'user@gmail.com', '$2y$10$4V8P9snRVUGa2PkfprHklO8NB4rb8/0Gf9Zts24T1mikHzUXDzZwG', NULL, '+23672765831', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 22:22:52', '2025-07-01 22:22:52', NULL, NULL, NULL, NULL, 1, NULL),
(462, '+237655770537', 'user@gmail.com', '$2y$10$hnPZkF2HDLjZsaOLnrvBX.RRITf7./rN0yBskpUTOybQc4MXC7ur6', NULL, '+237655770537', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 22:23:24', '2025-07-01 22:23:24', NULL, NULL, NULL, NULL, 1, NULL),
(463, '+237697119551', 'user@gmail.com', '$2y$10$IuBlNVy6fUbMaSbJ7qcA.ecWf1coUiyPz3b4NEzbNpjBBz.dWaMaO', NULL, '+237697119551', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 22:29:09', '2025-07-01 22:29:09', NULL, NULL, NULL, NULL, 1, NULL),
(464, '+237696281687', 'user@gmail.com', '$2y$10$EChiQ8NW7ISdXKWgcrrdbezp.2EtvwtjXgCXt3Zs5dAep1Up6Lzzu', NULL, '+237696281687', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 22:34:30', '2025-07-01 22:34:30', NULL, NULL, NULL, NULL, 1, NULL),
(465, '+237696551081', 'user@gmail.com', '$2y$10$t49HRPN0qPcMVVH1.WjJ0Od3TytiIGZmGM1ijK1DMZN84RgPjxTBS', NULL, '+237696551081', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 22:37:38', '2025-07-01 22:37:38', NULL, NULL, NULL, NULL, 1, NULL),
(466, '+237675322808', 'user@gmail.com', '$2y$10$XIWpZ5ODkfjJI6OoYCEpPeBAoc.4EC2cs5AChqouwpKvnTMZv00yS', NULL, '+237675322808', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 22:37:47', '2025-07-01 22:37:47', NULL, NULL, NULL, NULL, 1, NULL),
(467, '+237687534123', 'user@gmail.com', '$2y$10$CeL/LY5HrYC9k7/iwmYEneHkVniiXaCFirP9ukF3ttR0AxeMGKIVa', NULL, '+237687534123', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 22:40:08', '2025-07-01 22:40:08', NULL, NULL, NULL, NULL, 1, NULL),
(468, '+237697054853', 'user@gmail.com', '$2y$10$SdhOVT4FxyQB7dIUw2N.b.HBa6GPMmwTpaSoP2fE1MKh7mK1.vZVG', NULL, '+237697054853', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 22:44:05', '2025-07-01 22:44:05', NULL, NULL, NULL, NULL, 1, NULL),
(469, '+237695245767', 'user@gmail.com', '$2y$10$Va5DQfEdWWJtaS7GTO6jwe2AZbIKZLD8LqHZvPUGKsw/jlpGKPKb.', NULL, '+237695245767', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 22:46:37', '2025-07-01 22:46:37', NULL, NULL, NULL, NULL, 1, NULL),
(470, '+237697274176', 'user@gmail.com', '$2y$10$TWIN5sR8gaEt7tuOziPIb.E5B36MiiMb.mpKzn8D.xHUBoBhB9w/u', NULL, '+237697274176', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 22:52:28', '2025-07-01 22:52:28', NULL, NULL, NULL, NULL, 1, NULL),
(471, '+237696959323', 'user@gmail.com', '$2y$10$5edQMmmJAakbouy6QddwBe6yE3TG1kKwxeJvaeqfeH9MJ.Dn9hziC', NULL, '+237696959323', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 22:55:44', '2025-07-01 22:55:44', NULL, NULL, NULL, NULL, 1, NULL),
(472, '+237654950775', 'user@gmail.com', '$2y$10$csJW4Q36RZSmc0wLw.054uLUTMyEtodbD8DNOjyDk0OLqB/J1XFhG', NULL, '+237654950775', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 22:55:55', '2025-07-01 22:55:55', NULL, NULL, NULL, NULL, 1, NULL),
(473, '+237671003133', 'user@gmail.com', '$2y$10$Z7qsLUevBuyG6qRwc/5bAecPXsPKT6V8XVXvQnRg39nPOISn2QFQO', NULL, '+237671003133', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 23:16:05', '2025-07-01 23:16:05', NULL, NULL, NULL, NULL, 1, NULL),
(474, '+237697421552', 'user@gmail.com', '$2y$10$myjvYRNvXgoO2n9IJtLpDON5VhQjjcGOhnER0we/KVqyq45sbqanS', NULL, '+237697421552', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 23:19:11', '2025-07-01 23:19:11', NULL, NULL, NULL, NULL, 1, NULL),
(475, '691300360', 'user@gmail.com', '$2y$10$iuyJIPr4xj2PW33j38j0AenXJ4qOTAYq/S6G..A3b5sKqGNZK8GBS', NULL, '691300360', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 23:54:47', '2025-07-01 23:54:47', NULL, NULL, NULL, NULL, 1, NULL),
(476, '+237691300360', 'user@gmail.com', '$2y$10$C5Tq1p7gMPzMzQnWm3UAbOfk1WQMF4cDCseHmv0zqNAAYAaL6epPS', NULL, '+237691300360', NULL, 3, NULL, NULL, 1, 0, '2025-07-01 23:57:01', '2025-07-01 23:57:01', NULL, NULL, NULL, NULL, 1, NULL),
(477, '+237695427991', 'user@gmail.com', '$2y$10$D5dThDS6CFtOSIUaN1K19Opucl38x/MbLKcV.ZISCYo1qJ2Iw.che', NULL, '+237695427991', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 00:07:05', '2025-07-02 00:07:05', NULL, NULL, NULL, NULL, 1, NULL),
(478, '+237698136371', 'user@gmail.com', '$2y$10$ojHuMTeD0oFqgbs8dyOA9u88JB0iJ/3Auze.NNtoETYrGpUGdw9E.', NULL, '+237698136371', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 00:07:44', '2025-07-02 00:07:44', NULL, NULL, NULL, NULL, 1, NULL),
(479, '+237656186021', 'user@gmail.com', '$2y$10$SUw7kuQdZSWhv5v7kNyf7.sPveh7Dk1Wm1BYnPKvT3jvEHmO10FGC', NULL, '+237656186021', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 00:28:12', '2025-07-02 00:28:12', NULL, NULL, NULL, NULL, 1, NULL),
(480, '+237653889696', 'user@gmail.com', '$2y$10$RBfYuU.ukK32rWxyNioM/.4BJhpyh/U9uuNvp61AYaEb1tYtKZqda', NULL, '+237653889696', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 01:03:43', '2025-07-02 01:03:43', NULL, NULL, NULL, NULL, 1, NULL),
(481, '+237696855896', 'user@gmail.com', '$2y$10$VTv8sNHIHPiytnPUhjDWy.JOFyfICw6IkyXYDjQMJtFMpPDf1DP0K', NULL, '+237696855896', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 01:11:13', '2025-07-02 01:11:13', NULL, NULL, NULL, NULL, 1, NULL),
(482, '+237682430414', 'user@gmail.com', '$2y$10$8.PqZEjnxxUtrkzUlmArQ.NfEQZI9dafdpaaL0y2PCNJ023JwPpwG', NULL, '+237682430414', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 01:23:24', '2025-07-02 01:23:24', NULL, NULL, NULL, NULL, 1, NULL),
(483, '+237654845554', 'user@gmail.com', '$2y$10$Zn.AwRmm6gWgQIoL5Cg.6uIoVyNlOjTDiLhU2JEXGJajZ980CWM.a', NULL, '+237654845554', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 01:24:33', '2025-07-02 01:24:33', NULL, NULL, NULL, NULL, 1, NULL),
(484, '+237698925474', 'user@gmail.com', '$2y$10$AtMch.HYlMYIBMrh4AnGYOgt313wR0W26tca3mzVP20N5T4nqqME6', NULL, '+237698925474', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 02:30:11', '2025-07-02 02:30:11', NULL, NULL, NULL, NULL, 1, NULL),
(485, '+237682318222', 'user@gmail.com', '$2y$10$czBI/Mhg18zrd866oxaGku0J661.ejMUoV6It75KP15XFJvzIAjnS', NULL, '+237682318222', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 02:31:25', '2025-07-02 02:31:25', NULL, NULL, NULL, NULL, 1, NULL),
(486, '+237680372249', 'user@gmail.com', '$2y$10$1USrm7qGb7otHqVnlciCVumtASrcwTqbOmw8UZBsJg.K.MxO9dLUK', NULL, '+237680372249', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 02:35:01', '2025-07-02 02:35:01', NULL, NULL, NULL, NULL, 1, NULL),
(487, '699205005', 'user@gmail.com', '$2y$10$r45zy07sSEdWK/0UKo93menJiQKvz0ZiSqGo3o/mF7w.ITQnB74HO', NULL, '699205005', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 04:46:19', '2025-07-02 04:57:36', NULL, NULL, NULL, NULL, 1, NULL),
(488, '+237696862553', 'user@gmail.com', '$2y$10$/YbT/N2HJdtoImoE4fOv2OlXpImjihD5T/qS64Lnk4HXtwSwTSYaK', NULL, '+237696862553', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 04:57:34', '2025-07-02 04:57:34', NULL, NULL, NULL, NULL, 1, NULL),
(489, '+237697796120', 'user@gmail.com', '$2y$10$fQXx1mueAxj/xyv9l17wZ.P3rjMG9eSR4WNTosUKmg97j.MMF0aUm', NULL, '+237697796120', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 06:23:20', '2025-07-02 06:23:20', NULL, NULL, NULL, NULL, 1, NULL),
(490, '+237696786120', 'user@gmail.com', '$2y$10$qq3zZTjiZVhQnltheuFxNO.5XYFte4tTDvpkDM9llD5BR3ArXVQwq', NULL, '+237696786120', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 06:24:00', '2025-07-02 06:24:00', NULL, NULL, NULL, NULL, 1, NULL),
(491, '+237656934222', 'user@gmail.com', '$2y$10$Y6qnWL16JL6wkMfkc9EUiu2TaGuPaznHNUaNsz4AokXn.NzEyQFBq', NULL, '+237656934222', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 06:24:18', '2025-07-02 06:24:18', NULL, NULL, NULL, NULL, 1, NULL),
(492, '+237679387754', 'user@gmail.com', '$2y$10$kfkWNAYa1tN.homcq5mB5u0n.2.KSBPFho9oBNl/Cb3FDqs/a3U8K', NULL, '+237679387754', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 06:42:53', '2025-07-02 06:42:53', NULL, NULL, NULL, NULL, 1, NULL),
(493, '+237677629062', 'user@gmail.com', '$2y$10$ROFmqYy15mO1.KDvAtj.DeaQtfMtljZ4k6ziY4zwMzMfAkauHCk2i', NULL, '+237677629062', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 06:43:05', '2025-07-02 06:43:05', NULL, NULL, NULL, NULL, 1, NULL),
(494, '+971504087657', 'user@gmail.com', '$2y$10$yPY2IFYO1TfT.77RD8rBXO5A7xUi.to3gReIctAq4YLhefPNZcmTm', NULL, '+971504087657', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 06:44:03', '2025-07-02 06:44:03', NULL, NULL, NULL, NULL, 1, NULL),
(495, '+23754561638', 'user@gmail.com', '$2y$10$llItPq.wictWLNZhOclke.2WtzagSmBctPj/nk7uTCx78YYQQDRwG', NULL, '+23754561638', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 06:46:15', '2025-07-02 06:46:15', NULL, NULL, NULL, NULL, 1, NULL),
(496, '+237674076322', 'user@gmail.com', '$2y$10$6TFCp0HEQmZ5IQqOUZsOMeblueovvPX8yeJr94K07td5Wzksbzi7K', NULL, '+237674076322', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 06:48:07', '2025-07-02 06:48:07', NULL, NULL, NULL, NULL, 1, NULL),
(497, '+237 65668238', 'user@gmail.com', '$2y$10$lwmfuIwCqn/VkJXVoJsCMeuAOzeAyEw6pfFlZlfMwAyianpGcK3qS', NULL, '+237 65668238', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 06:54:14', '2025-07-02 06:54:14', NULL, NULL, NULL, NULL, 1, NULL),
(498, '+237699386135', 'user@gmail.com', '$2y$10$n/s.Zgwq1aDXYhF.fY3J3uFKBiywTpFfxWXwxXOZ7772mWREPNb5.', NULL, '+237699386135', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 06:56:29', '2025-07-02 06:56:29', NULL, NULL, NULL, NULL, 1, NULL),
(499, '+237 68316852', 'user@gmail.com', '$2y$10$ZOafkmoX3QwMHgzFQ0pwUOW/sg9KwiPzCqmX0ZfDVCWebsSi88E.e', NULL, '+237 68316852', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 07:06:33', '2025-07-02 07:06:33', NULL, NULL, NULL, NULL, 1, NULL),
(500, '+237 68207227', 'user@gmail.com', '$2y$10$IkwZkEmy4aoJRDLBaDFXVeT66XaMpPIOorMHnpbuoTlprrmkp5a/K', NULL, '+237 68207227', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 07:09:59', '2025-07-02 07:09:59', NULL, NULL, NULL, NULL, 1, NULL),
(501, '+237656137183', 'user@gmail.com', '$2y$10$rZ0l1g0KCj12abjFOsgeOuP73rMqswcyO5Ab/v1AyFdUaDd6DxXh.', NULL, '+237656137183', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 07:15:29', '2025-07-02 07:15:29', NULL, NULL, NULL, NULL, 1, NULL),
(502, '+23782700353', 'user@gmail.com', '$2y$10$f.3z71sY7goeixRskagPve2uTkhPKNKQkTOc3DSRB0LAp3/WFwnsm', NULL, '+23782700353', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 07:18:56', '2025-07-02 07:18:56', NULL, NULL, NULL, NULL, 1, NULL),
(503, '+237699142503', 'user@gmail.com', '$2y$10$e7YB.XEq3n3GeAkwYGQk3ujpgzmA3.r6UFC62VUk9iQ4zcoSD1LmC', NULL, '+237699142503', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 07:27:17', '2025-07-02 07:27:17', NULL, NULL, NULL, NULL, 1, NULL),
(504, '+237671991361', 'user@gmail.com', '$2y$10$XdwzJBix1YUUirsqDU.sXuCP8zagFWCQQ5JVopowz.T1Ox1vzuI3C', NULL, '+237671991361', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 07:30:08', '2025-07-02 07:30:08', NULL, NULL, NULL, NULL, 1, NULL),
(505, '+491624124125', 'user@gmail.com', '$2y$10$en/u0d/WDkIdgPPK5op1v.Bz0O9D.wFYldJ6O59K/lUoI69K0OdUa', NULL, '+491624124125', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 07:30:56', '2025-07-02 07:30:56', NULL, NULL, NULL, NULL, 1, NULL),
(506, '+237659419696', 'user@gmail.com', '$2y$10$73zQV8C1DbLGHMrWQDMA1OOzHVLrGlc0JXDR7znSK/hz4YYh589Qq', NULL, '+237659419696', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 07:35:53', '2025-07-02 07:35:53', NULL, NULL, NULL, NULL, 1, NULL),
(507, '+237697562349', 'user@gmail.com', '$2y$10$qoR0oa/e8dIG7xEOfgr2keb41hFEnITqwK6Jh90B90PfPvK78qujW', NULL, '+237697562349', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 07:49:15', '2025-07-02 07:49:15', NULL, NULL, NULL, NULL, 1, NULL),
(508, '(780) 818-0613', 'user@gmail.com', '$2y$10$VYOIDacMd1NH18d5ksxjhuKvhv1XSflDzDYUN6Odnp0ixdKw80Z0.', NULL, '(780) 818-0613', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 07:50:31', '2025-07-02 07:50:31', NULL, NULL, NULL, NULL, 1, NULL),
(509, '+237658945004', 'user@gmail.com', '$2y$10$j7srvyoLUQMYTxZt8R1nR.KH96r5le.Ni.dprB4IKlxzm9O4o8z5G', NULL, '+237658945004', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 07:56:48', '2025-07-02 07:56:48', NULL, NULL, NULL, NULL, 1, NULL),
(510, '+237 6 94 67', 'user@gmail.com', '$2y$10$derk96gRA3dinD8IYzKfD.68Ocu4HC0QLTcoSQggmfcg9Dr6x4FGy', NULL, '+237 6 94 67', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 08:02:17', '2025-07-02 08:02:17', NULL, NULL, NULL, NULL, 1, NULL),
(511, '+237 683683811', 'user@gmail.com', '$2y$10$W7MvO0BjGFS6gbtPsWeQs.4NojycUa9pBAODVdNOnNkFd96y9slzS', NULL, '+237 683683811', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 08:02:28', '2025-07-02 08:02:28', NULL, NULL, NULL, NULL, 1, NULL),
(512, 'IYALE Liliane', 'iyaleliliane@yahoo.fr', '$2y$10$odHfIuNUQIkjL/9uKfj1l.LDBSvXJipsLcuiM18U4.voGMT6tJ0KC', NULL, '+237694674744', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 08:07:12', '2025-07-02 08:13:18', NULL, NULL, NULL, NULL, 1, NULL),
(513, '+237656789305', 'user@gmail.com', '$2y$10$EYcKEPJMco0ZuVHfVyaLpusG0UoROi7HNMi45yL1kwlpHzrlHnvua', NULL, '+237656789305', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 08:21:36', '2025-07-02 08:21:36', NULL, NULL, NULL, NULL, 1, NULL),
(514, '+237678732369', 'user@gmail.com', '$2y$10$Q5ElqmMbM9hACu4LDau7jO.oPbrRM06pU9Mb6vPTMhKyDU4SRQuWu', NULL, '+237678732369', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 08:30:26', '2025-07-02 08:30:26', NULL, NULL, NULL, NULL, 1, NULL),
(515, '+237682125726', 'user@gmail.com', '$2y$10$6/3e3xzzODkG0aF2BHbZaerY1Tvpz/XxT5ixEkLsDiuqaIIQcUW7G', NULL, '+237682125726', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 08:37:41', '2025-07-02 08:37:41', NULL, NULL, NULL, NULL, 1, NULL),
(516, '+237697060149', 'user@gmail.com', '$2y$10$1OpWxdaGsXHwMq6V44ZB7uJHYSKrv68M2A4/e2/I6l2mjeSC4JkYu', NULL, '+237697060149', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 08:38:13', '2025-07-02 08:38:13', NULL, NULL, NULL, NULL, 1, NULL),
(517, '+237676292603', 'user@gmail.com', '$2y$10$AInURZhmsP6AhdStpOdGW.V8XnhzbeBbIAl1DHDdY2UQ1705IPPJa', NULL, '+237676292603', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 08:47:29', '2025-07-02 08:47:29', NULL, NULL, NULL, NULL, 1, NULL),
(518, '+237690694509', 'user@gmail.com', '$2y$10$MtfyqQVw8zM8ASPFDSG7guNdj7eOfZRIs5kgAgNMOtZpQC8BDL1P2', NULL, '+237690694509', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 08:48:19', '2025-07-02 08:48:19', NULL, NULL, NULL, NULL, 1, NULL),
(519, '+237697187867', 'user@gmail.com', '$2y$10$PRxY1q7IsY0xXYqe4PFxwOxSlWAK7Asv.zSMj7mK2lJ8dL/U47ZXi', NULL, '+237697187867', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 08:53:38', '2025-07-02 08:53:38', NULL, NULL, NULL, NULL, 1, NULL),
(520, '+237694353445', 'user@gmail.com', '$2y$10$EwFj86JfKm1cD8DNRvqpaOFZ1.IgVe6PuWl7WHtQXM0cbp26XIByW', NULL, '+237694353445', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 08:57:50', '2025-07-02 08:57:50', NULL, NULL, NULL, NULL, 1, NULL),
(521, '+237696969123', 'user@gmail.com', '$2y$10$FUFMmBIDCmGgYCd2RfOLoe2I.Q1vfh.QVH4qFgORy/B3Ld4h5tr4q', NULL, '+237696969123', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:03:03', '2025-07-02 09:03:03', NULL, NULL, NULL, NULL, 1, NULL),
(522, '+237696969127', 'user@gmail.com', '$2y$10$MJKYGV/ne1qIt0R7TC1r5O7RTHmGdnTGGe3UPcySSTB4or0Jnu.Tq', NULL, '+237696969127', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:03:34', '2025-07-02 09:03:34', NULL, NULL, NULL, NULL, 1, NULL),
(523, '+22961914503', 'user@gmail.com', '$2y$10$kcqVNMX75b8LPqQqEYCVz.Iqjcd2wzifKpl9KeyL13PGqNTtQi18G', NULL, '+22961914503', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:12:14', '2025-07-02 09:12:14', NULL, NULL, NULL, NULL, 1, NULL),
(524, '+237699182812', 'user@gmail.com', '$2y$10$IWoTa/ajotzvRAopJtx8WupwWpHHS3onlc3OC/ef.9AwTzbzDz9qG', NULL, '+237699182812', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:14:28', '2025-07-02 09:14:28', NULL, NULL, NULL, NULL, 1, NULL),
(525, '+237694143775', 'user@gmail.com', '$2y$10$W7KIOnWn9R7M517doERmJOiyT1quzBeTkFA7BBBnOJxbxVLBSlfbi', NULL, '+237694143775', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:15:29', '2025-07-02 09:15:29', NULL, NULL, NULL, NULL, 1, NULL),
(526, '+237682708572', 'user@gmail.com', '$2y$10$Ef7TRzJLeY9zFIw/ypu5J.5Km/ND7tEe62Q9NXXKkUb5sG9acUEAa', NULL, '+237682708572', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:16:41', '2025-07-02 09:16:41', NULL, NULL, NULL, NULL, 1, NULL),
(527, '+237651477322', 'user@gmail.com', '$2y$10$gZJ4cRl1Sluv/ewcOvN1Lu4qMjdAae085y7VyAoAGtij0KnrVoILi', NULL, '+237651477322', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:18:16', '2025-07-02 09:18:16', NULL, NULL, NULL, NULL, 1, NULL),
(528, '+237675487345', 'user@gmail.com', '$2y$10$kIvDlesaUKFjRv8mL1FIC.T..HMa84YHHPv9yEOrHuUDPiJM04CQm', NULL, '+237675487345', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:18:55', '2025-07-02 09:18:55', NULL, NULL, NULL, NULL, 1, NULL),
(529, '+237699773565', 'user@gmail.com', '$2y$10$FlC4QSb6sUQ1csmM.wIwE.t8j.5h6WabbK3ZepMpUztRV7ajseTvO', NULL, '+237699773565', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:26:03', '2025-07-02 09:26:03', NULL, NULL, NULL, NULL, 1, NULL),
(530, 'DELICIOUS CLARITA', 'bouchengclaire@yahoo.fr', '$2y$10$D3q5.F446TrqNG6EtbS0KeEad9O9Zn2E/9AjvPcE7mIxY0ZQ2wvAe', NULL, '+237699307316', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:27:30', '2025-07-02 09:32:27', NULL, NULL, NULL, NULL, 1, NULL),
(531, '+237694742347', 'user@gmail.com', '$2y$10$qhU0klknHpoFow1IzaE7eu1GOdP86MmaHrQPHdUBypQzHscfjx75.', NULL, '+237694742347', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:31:11', '2025-07-02 09:31:11', NULL, NULL, NULL, NULL, 1, NULL),
(532, '+237690390761', 'user@gmail.com', '$2y$10$XtDlXk823C6MXXvfDfVJQeHqFB6c3YcVBjWyosqb5BYEtXSZXxZxy', NULL, '+237690390761', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:31:36', '2025-07-02 09:31:36', NULL, NULL, NULL, NULL, 1, NULL),
(533, '+330625706556', 'user@gmail.com', '$2y$10$1NrXUCQwuRwx/K.MfEcJCup6xgCJzhCefYQQkcfcr.46QmUepH7Oq', NULL, '+330625706556', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:39:35', '2025-07-02 09:39:35', NULL, NULL, NULL, NULL, 1, NULL),
(534, '+237653271262', 'user@gmail.com', '$2y$10$19QQXFwus9G/fsalQiQHsOFoiFhtGp.tVn2Z8DCS0zierBwqkPnju', NULL, '+237653271262', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:40:34', '2025-07-02 09:40:34', NULL, NULL, NULL, NULL, 1, NULL),
(535, '+33 6 48 87 50 65', 'user@gmail.com', '$2y$10$nChNG8n.W.Oxa9RqOARoNOCIpk5/cH8f9XieHJDPvXUzUswuBd4U6', NULL, '+33 6 48 87 50 65', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:40:38', '2025-07-02 09:40:38', NULL, NULL, NULL, NULL, 1, NULL),
(536, '+237696850793', 'user@gmail.com', '$2y$10$mRSzifPjVQheu9fCKw8/TOAj3xsauhkWCoeis/.SCj0KPLyinjwVu', NULL, '+237696850793', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:45:44', '2025-07-02 09:45:44', NULL, NULL, NULL, NULL, 1, NULL),
(537, '+237 67151520', 'user@gmail.com', '$2y$10$tqs3LFRVyGtvi8g10xcixOkW53nWWqZVaaV9o4.3b31h2nywLw09e', NULL, '+237 67151520', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:49:30', '2025-07-02 09:49:30', NULL, NULL, NULL, NULL, 1, NULL),
(538, '+237694247939', 'user@gmail.com', '$2y$10$gK7uz40Tqt/7UpUk1.U.xeR4B9Lj.76ImkEg3lJm0ShWKlGdYzs1a', NULL, '+237694247939', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:54:04', '2025-07-02 09:54:04', NULL, NULL, NULL, NULL, 1, NULL),
(539, '+237 65519441', 'user@gmail.com', '$2y$10$s2OZKBiNzqvRpwDwA6Hhy.wcGaoXBkAyr5tnSdkIeyhwOT.N5rc/6', NULL, '+237 65519441', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 09:55:35', '2025-07-02 09:55:35', NULL, NULL, NULL, NULL, 1, NULL),
(540, '+237695166064', 'user@gmail.com', '$2y$10$c3/5EpcmfO7Re3883Z80Q./hQGDgfQXV3MaqEZPR.Qw74SMTUFit2', NULL, '+237695166064', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 10:01:53', '2025-07-02 10:01:53', NULL, NULL, NULL, NULL, 1, NULL),
(541, '+237 89416296', 'user@gmail.com', '$2y$10$U19F0s7Jtq8AEfpMi99VX.Cbmjpfb116eXvmWr1YwYDJ36VWniiB2', NULL, '+237 89416296', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 10:05:52', '2025-07-02 10:05:52', NULL, NULL, NULL, NULL, 1, NULL),
(542, '+237675138134', 'user@gmail.com', '$2y$10$RWzYf.6KGgU8rN16IAPAGug5KSt4aMdHtL9qhkbJFx9NEkUE1xqnG', NULL, '+237675138134', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 10:12:38', '2025-07-02 10:12:38', NULL, NULL, NULL, NULL, 1, NULL),
(543, '+237698451030', 'user@gmail.com', '$2y$10$Xk8p.G7VhTwaX//PTUVJMuWsP3yjgsNIRfc42Kb0QXACn2bxl0NQq', NULL, '+237698451030', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 10:25:27', '2025-07-02 10:25:27', NULL, NULL, NULL, NULL, 1, NULL),
(544, '+237 67060647', 'user@gmail.com', '$2y$10$CnZeCR//rwEXeUJ9JogoVuWZZXmWRMtSWkSgkIuyNy7KKlrAFfkx2', NULL, '+237 67060647', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 10:29:31', '2025-07-02 10:29:31', NULL, NULL, NULL, NULL, 1, NULL),
(545, '+237695724450', 'user@gmail.com', '$2y$10$PugQ5E5DQS/8DLiZWaPCKuUYMysmnCVyAvDR..bzjY4/K/HK28b3a', NULL, '+237695724450', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 10:34:47', '2025-07-02 10:34:47', NULL, NULL, NULL, NULL, 1, NULL),
(546, '+237681631210', 'user@gmail.com', '$2y$10$eBpHTJP6hqfYI.ZGenCHjelvP3x08guCHCjEl3ZiXgXLtcyMx/E2i', NULL, '+237681631210', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 10:35:55', '2025-07-02 10:35:55', NULL, NULL, NULL, NULL, 1, NULL),
(547, '+237694020297', 'user@gmail.com', '$2y$10$qBryYuY20iM8RuIjI6H2EuJ4MEoORs/xGrg0E.cyBYCOtDDGZOWQ.', NULL, '+237694020297', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 10:37:31', '2025-07-02 10:37:31', NULL, NULL, NULL, NULL, 1, NULL),
(548, '+237670606427', 'user@gmail.com', '$2y$10$bu/wuj2fnkgACA.DbLgpUezCz0uhiZbPDXT/AvgowmRTZ7FHFW8qy', NULL, '+237670606427', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 10:43:31', '2025-07-02 10:43:31', NULL, NULL, NULL, NULL, 1, NULL),
(549, '695 86 57 38', 'user@gmail.com', '$2y$10$pHzshzt0RlTw7bjT.6Fs8eInHj7xrz83mEucwR4kJSjGfwMRZs9Mq', NULL, '695 86 57 38', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 10:52:54', '2025-07-02 10:52:54', NULL, NULL, NULL, NULL, 1, NULL),
(550, '+237695277786', 'user@gmail.com', '$2y$10$6TohE3Wfo.ELy8B8mElGDOvh7pxqwo8rmfwAWb/Or5Dj65Nnx9Dc2', NULL, '+237695277786', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 10:59:29', '2025-07-02 10:59:29', NULL, NULL, NULL, NULL, 1, NULL),
(551, '+237679914979', 'user@gmail.com', '$2y$10$Kq0BLXHmWJwA8fQzKP2EJexG0W2cvr4wDf7fqD68FgzK4rjVn.bC6', NULL, '+237679914979', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 11:01:58', '2025-07-02 11:01:58', NULL, NULL, NULL, NULL, 1, NULL),
(552, '+237674868505', 'user@gmail.com', '$2y$10$1rvb2uPCnYUvnn31x5x7cu4tiutRQwkBR9wgtJrHLkksjxD4DxMVK', NULL, '+237674868505', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 11:04:03', '2025-07-02 11:04:03', NULL, NULL, NULL, NULL, 1, NULL),
(553, '+23790751337', 'user@gmail.com', '$2y$10$xgRm4JGixRSLT4ardP1hgeBXmWdqrfGj5VWZOxcVAjEebuy8ck8Iq', NULL, '+23790751337', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 11:04:56', '2025-07-02 11:04:56', NULL, NULL, NULL, NULL, 1, NULL),
(554, '+237697097820', 'user@gmail.com', '$2y$10$sm.WuCkZd9Ql1/u2jVKi4.vu1cRBOeJuLKdayW2VMK9NCRy357R3u', NULL, '+237697097820', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 11:07:04', '2025-07-02 11:07:04', NULL, NULL, NULL, NULL, 1, NULL),
(555, '+237677882551', 'user@gmail.com', '$2y$10$FJBkLtwHiigjalWY/a3zH.n2Lj38u6XbD/riQYkVrKCgfX2HOTm6K', NULL, '+237677882551', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 11:14:53', '2025-07-02 11:14:53', NULL, NULL, NULL, NULL, 1, NULL),
(556, '+1 8199199932', 'user@gmail.com', '$2y$10$Mxtp7HkWTn0c4nbl.jD04OJxdrItaEhOcX.VCOxFw37xZx2oR5S6u', NULL, '+1 8199199932', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 11:22:15', '2025-07-02 11:22:15', NULL, NULL, NULL, NULL, 1, NULL),
(557, '+237677551314', 'user@gmail.com', '$2y$10$oblqH9EPOSmKOY/vOAmJLupylg3QB.VPp0DWlxf40G8P/6S1snhgi', NULL, '+237677551314', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 11:35:52', '2025-07-02 11:35:52', NULL, NULL, NULL, NULL, 1, NULL),
(558, '+237655996195', 'user@gmail.com', '$2y$10$ZkQxn82zJYS9EKYkkOLhReytvlVU5089ayMkUYL6pQz0yv.Uvar8q', NULL, '+237655996195', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 11:39:46', '2025-07-02 11:39:46', NULL, NULL, NULL, NULL, 1, NULL),
(559, '+237696851878', 'user@gmail.com', '$2y$10$k1FYgPuFrnTYrQYZ1j3rM.xhYeTk52ftnDZICfsUzf9S8q90jsHIS', NULL, '+237696851878', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 11:44:37', '2025-07-02 11:44:37', NULL, NULL, NULL, NULL, 1, NULL),
(560, '+237656577538', 'user@gmail.com', '$2y$10$zrjyHDrJTkBhfoVml8GHPekMAXa8g123SW7A8PZST1kLKUVKFQSDa', NULL, '+237656577538', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 11:50:08', '2025-07-02 11:50:08', NULL, NULL, NULL, NULL, 1, NULL),
(561, '+237698345786', 'user@gmail.com', '$2y$10$WZz7BOMXnlf8CJHJR27mhOLtzq2c3ZBqsOB31Ubv7JoNtDjWjUxBS', NULL, '+237698345786', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 11:50:21', '2025-07-02 11:50:21', NULL, NULL, NULL, NULL, 1, NULL),
(562, '+237 69586573', 'user@gmail.com', '$2y$10$/Mdmq60tg2YjcrjNGjlz0ODEdo3.HsNgV8JRHbtRLvhQHnpJx/Tiq', NULL, '+237 69586573', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 11:50:58', '2025-07-02 11:50:58', NULL, NULL, NULL, NULL, 1, NULL),
(563, '+237695658054', 'user@gmail.com', '$2y$10$rTjX.GXilVyYrvmgoV0Fwezu0tWFbw6AoWPWyj/nmBynjrzvhHPS6', NULL, '+237695658054', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 11:56:36', '2025-07-02 11:56:36', NULL, NULL, NULL, NULL, 1, NULL),
(564, '+237698613816', 'user@gmail.com', '$2y$10$UCB1eEfMc62e6PVSOwPQMu9QAh8b9yoDnW50.uLXYPWyzMQSO/u8K', NULL, '+237698613816', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 11:57:11', '2025-07-02 11:57:11', NULL, NULL, NULL, NULL, 1, NULL),
(565, '+23767337924', 'user@gmail.com', '$2y$10$/UVfmJ7SnkOTOiP6bXlKT.zGnC9kPKN5dmmDf9G.FbF.0WzOAhieq', NULL, '+23767337924', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 12:07:09', '2025-07-02 12:07:09', NULL, NULL, NULL, NULL, 1, NULL),
(566, '+237695865738', 'user@gmail.com', '$2y$10$OGMTu8bZjwJuPLykUbXQcOHFjubWlnMdR6TH7qROMv414Gl83Ofre', NULL, '+237695865738', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 12:15:52', '2025-07-02 12:15:52', NULL, NULL, NULL, NULL, 1, NULL),
(567, '+237670576037', 'user@gmail.com', '$2y$10$y.yNWYfFeutsAlA3Ojwvo.qWx5tZKCHBANkjshFb9RPBoYIVyo7sO', NULL, '+237670576037', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 12:15:58', '2025-07-02 12:15:58', NULL, NULL, NULL, NULL, 1, NULL),
(568, '+237697868085', 'user@gmail.com', '$2y$10$qKNX9pDTC0sdRZRVFnyGXOQuQCMjtBaKtPqBnsso46.Rr7lccAYUK', NULL, '+237697868085', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 12:17:42', '2025-07-02 12:17:42', NULL, NULL, NULL, NULL, 1, NULL),
(569, '+237694573814', 'user@gmail.com', '$2y$10$IKGOjaGLsD8U7Fe7Fe5wWuE9JMut/Lh.2BB16srNnUVlsnMVppgsa', NULL, '+237694573814', NULL, 3, NULL, NULL, 1, 0, '2025-07-02 12:29:30', '2025-07-02 12:29:30', NULL, NULL, NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `variants`
--

CREATE TABLE `variants` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `musician_id` int(11) NOT NULL,
  `vote` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `reference` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `user_id`, `musician_id`, `vote`, `status`, `reference`, `created_at`, `updated_at`) VALUES
(8, 1, 2, 50, 1, '607871', '2025-02-06 14:10:40', '2025-02-06 14:10:40'),
(10, 62, 2, 10, 1, '50397', '2025-02-07 13:18:01', '2025-02-07 13:18:01'),
(11, 1, 1, 20, 1, '492f4a0b-f105-4c35-aa30-8375085a0f1e', '2025-02-11 10:16:06', '2025-02-11 10:16:30'),
(12, 65, 1, 10, 1, 'e3add4ce-e449-46f1-9136-79875aba5d69', '2025-02-11 10:18:58', '2025-02-11 10:21:58'),
(13, 67, 5, 90, 1, '49294', '2025-02-11 10:36:58', '2025-02-11 10:36:58'),
(14, 1, 6, 2, 1, '04ca795b-b5ed-4714-94b0-31c2a20c43df', '2025-02-11 10:52:20', '2025-02-11 10:52:41'),
(15, 67, 6, 4, 1, '44716', '2025-02-11 10:54:16', '2025-02-11 10:54:16'),
(16, 67, 7, 4, 1, '34995', '2025-02-11 11:18:58', '2025-02-11 11:18:58'),
(17, 1, 1, 3, 1, 'a0754ec3-a866-462b-a5f9-814df8d58096', '2025-02-12 21:42:44', '2025-02-12 21:43:24'),
(18, 73, 1, 3, 1, '640687', '2025-02-12 21:49:16', '2025-02-12 21:49:16'),
(19, 1, 1, 5, 1, '0086e404-14a3-465f-8ccc-acf1edf7b6e1', '2025-02-12 21:58:17', '2025-02-12 21:59:35'),
(21, 74, 7, 5, 1, 'fbb88ee9-0ec0-4f8b-9f9b-a909883fb18f', '2025-02-13 09:37:19', '2025-02-13 09:38:05'),
(23, 1, 7, 1, 1, '50242724-12a2-4594-9968-2bb6f59bba8f', '2025-02-14 11:31:07', '2025-02-14 11:31:38'),
(24, 78, 1, 1, 1, '46bae740-5a9d-4945-b468-98fca35cdf98', '2025-02-14 13:11:24', '2025-02-14 13:12:07'),
(33, 80, 9, 5, 1, 'pi_3QwmEzJUl8IbwjDS0YrqNJs5', '2025-02-26 15:07:51', '2025-02-26 15:09:51'),
(34, 59, 9, 5, 1, 'pi_3QwnwZJUl8IbwjDS1q4NKK8X', '2025-02-26 16:56:12', '2025-02-26 16:58:51'),
(35, 1, 9, 3, 1, 'pi_3Qx3UBJUl8IbwjDS1T5r3Koo', '2025-02-27 09:33:24', '2025-02-27 09:35:20'),
(37, 59, 9, 5, 1, 'pi_3QxqegJUl8IbwjDS0uo1cs4V', '2025-03-01 14:04:05', '2025-03-01 14:04:43'),
(38, 1, 9, 1, 1, '68399561-dee1-429a-bcf3-45fb199efdb9', '2025-03-02 15:21:40', '2025-03-02 15:22:01'),
(41, 81, 9, 1, 1, '5f51ff6a-2126-4075-a60a-4da26d2ad0ed', '2025-03-09 17:39:56', '2025-03-09 17:40:48'),
(43, 59, 70, 2, 1, 'pi_3Rfv7eJUl8IbwjDS1cdBtIZv', '2025-07-01 04:42:20', '2025-07-01 04:44:45'),
(45, 59, 14, 2, 1, 'pi_3RfvBrJUl8IbwjDS1ZYlx8Os', '2025-07-01 04:48:08', '2025-07-01 04:49:06'),
(46, 103, 31, 2, 1, 'c387df3d-d951-4754-917e-ec0008a06ff5', '2025-07-01 06:21:04', '2025-07-01 06:22:09'),
(48, 147, 48, 3, 1, '2184bfa6-7202-4e8b-ac8f-85d72cdc3f76', '2025-07-01 06:32:19', '2025-07-01 06:32:53'),
(51, 149, 48, 2, 1, '7944433a-df3b-49c5-abe0-633e3b05c7bc', '2025-07-01 06:42:07', '2025-07-01 06:42:36'),
(57, 151, 30, 1, 1, 'e485d07f-150e-4932-b074-fd69c7364d42', '2025-07-01 08:32:40', '2025-07-01 08:33:34'),
(60, 153, 27, 1, 1, 'e804ec28-9cca-494d-873b-1af86b717777', '2025-07-01 08:35:43', '2025-07-01 08:36:55'),
(61, 153, 27, 1, 1, 'cb8dd2db-a866-4c4e-a6b2-bb934cbcc8ea', '2025-07-01 08:37:35', '2025-07-01 08:37:58'),
(63, 116, 44, 2, 1, 'e2272c20-2c36-435d-ac9c-caa04daaf5fb', '2025-07-01 08:47:51', '2025-07-01 08:48:25'),
(66, 156, 48, 1, 1, 'b9f00dea-5869-4ce6-b79a-134c46878cdc', '2025-07-01 08:50:04', '2025-07-01 08:50:47'),
(68, 158, 71, 1, 1, '66195a6f-4cca-4399-aae5-689993be4ab9', '2025-07-01 09:05:23', '2025-07-01 09:06:16'),
(69, 159, 71, 2, 1, '5f1ca0c6-60fc-4d7a-a2ec-43c1a0193ec0', '2025-07-01 09:13:39', '2025-07-01 09:14:12'),
(72, 159, 71, 1, 1, '0eabd12d-d023-4dc3-b510-27c4aa1a3dfd', '2025-07-01 09:15:12', '2025-07-01 09:15:29'),
(73, 159, 71, 1, 1, 'c8a738f5-dc7f-46a0-b215-9b2de7351921', '2025-07-01 09:16:37', '2025-07-01 09:17:00'),
(74, 160, 44, 2, 1, 'edf1e073-a755-4f3e-8d2d-29125d7036c5', '2025-07-01 09:19:42', '2025-07-01 09:20:47'),
(77, 161, 46, 1, 1, '45403ec4-ace5-4a43-a2c2-fdb8dd4aacb9', '2025-07-01 09:21:54', '2025-07-01 09:22:37'),
(78, 162, 60, 1, 1, 'd72c27e3-3aba-4287-9568-167338c6cdaf', '2025-07-01 09:23:35', '2025-07-01 09:24:14'),
(102, 162, 60, 6, 1, 'e6fdf4f0-0884-4b07-9adf-0472b49768db', '2025-07-01 09:25:10', '2025-07-01 09:25:31'),
(114, 164, 71, 1, 1, 'a791a881-2d89-4458-b6a0-16fc7be553a5', '2025-07-01 09:30:45', '2025-07-01 09:31:07'),
(117, 165, 48, 1, 1, '9b6f5bdb-7fd0-4b9a-b98a-c03854d75a08', '2025-07-01 09:32:47', '2025-07-01 09:33:37'),
(118, 79, 60, 2, 1, 'pi_3RfzeOJUl8IbwjDS0sxxFP45', '2025-07-01 09:33:20', '2025-07-01 09:35:00'),
(121, 168, 40, 1, 1, '5b332e9b-05a4-4143-baea-403dbcf97545', '2025-07-01 09:36:31', '2025-07-01 09:37:20'),
(125, 168, 39, 1, 1, '89e369db-cf86-4c0f-8ca1-ca214e93d4ea', '2025-07-01 09:41:20', '2025-07-01 09:41:54'),
(126, 170, 44, 2, 1, '097727a6-226b-41ce-aea6-4af44d696ce4', '2025-07-01 09:43:13', '2025-07-01 09:43:50'),
(127, 171, 71, 1, 1, 'd43cce4b-de94-4cbf-bd29-27f9ef06fecf', '2025-07-01 09:44:25', '2025-07-01 09:45:48'),
(128, 172, 21, 1, 1, '3353345b-e648-4501-b01b-681b41f49ac7', '2025-07-01 09:44:32', '2025-07-01 09:45:16'),
(135, 173, 42, 2, 1, '4383ea98-639a-4788-876b-355d3f6b624e', '2025-07-01 09:51:12', '2025-07-01 09:52:02'),
(150, 177, 71, 1, 1, 'b448ad45-0268-4f9d-9b8b-3642d173ee5e', '2025-07-01 10:00:45', '2025-07-01 10:01:27'),
(163, 181, 12, 1, 1, '10acdd21-1a3c-43cd-b685-c8846556f5bd', '2025-07-01 10:09:14', '2025-07-01 10:10:19'),
(166, 180, 14, 1, 1, '612c8d4a-99b2-4ea6-8ee3-f84da6d46ab4', '2025-07-01 10:11:09', '2025-07-01 10:11:26'),
(171, 79, 60, 1, 1, 'pi_3Rg0JQJUl8IbwjDS1rbviW6T', '2025-07-01 10:15:48', '2025-07-01 10:17:15'),
(172, 180, 14, 1, 1, '1f984fe8-89cc-4412-ba41-4b5b7578c1ec', '2025-07-01 10:17:16', '2025-07-01 10:17:36'),
(190, 186, 18, 1, 1, '863f38ab-3f4d-4b96-901d-cf3ddb04751e', '2025-07-01 10:24:53', '2025-07-01 10:25:32'),
(193, 186, 40, 1, 1, '7b2793e3-0506-4b65-afda-75a0d626d340', '2025-07-01 10:27:01', '2025-07-01 10:27:22'),
(201, 191, 46, 1, 1, 'b61804a9-2c1f-4ac2-aa2a-2b7214f2268e', '2025-07-01 10:37:04', '2025-07-01 10:38:24'),
(204, 191, 46, 1, 1, '1e7a97ec-cc3e-4f95-8e18-2f4237e67f94', '2025-07-01 10:39:14', '2025-07-01 10:39:48'),
(206, 193, 71, 2, 1, '184d31d4-a182-40d3-ba3d-e46c66a43e54', '2025-07-01 10:40:17', '2025-07-01 10:40:49'),
(215, 197, 21, 1, 1, '459bac23-9e8e-4a49-8d33-24e21c38e901', '2025-07-01 10:47:02', '2025-07-01 10:48:02'),
(219, 200, 71, 1, 1, 'a77b0c7a-9670-49ce-bf1a-b7dca24ec159', '2025-07-01 10:50:08', '2025-07-01 10:50:35'),
(220, 201, 62, 1, 1, '44a90d02-8a3c-4e11-bb09-b1f955e0f2ec', '2025-07-01 10:50:25', '2025-07-01 10:51:13'),
(225, 205, 12, 1, 1, '0e3c3369-c49f-4eea-96da-ac9e0c04ee6e', '2025-07-01 10:54:52', '2025-07-01 10:56:01'),
(226, 206, 27, 1, 1, 'ab631f2b-7f5e-4148-9322-34b4368dc782', '2025-07-01 10:55:27', '2025-07-01 10:56:12'),
(227, 207, 62, 1, 1, 'e2b758de-ee0c-456c-aa5f-44b6441ebdf1', '2025-07-01 10:56:39', '2025-07-01 10:57:19'),
(234, 212, 21, 1, 1, '139ef0bd-e71c-4a4e-97b0-23268929df77', '2025-07-01 11:00:21', '2025-07-01 11:01:45'),
(240, 215, 60, 1, 1, '492e615c-1e55-4971-8be3-d5aab0e2bb8f', '2025-07-01 11:04:34', '2025-07-01 11:05:35'),
(242, 213, 45, 1, 1, 'a3868dbc-2432-45a0-a145-d081302b1c8e', '2025-07-01 11:04:57', '2025-07-01 11:05:30'),
(253, 219, 60, 1, 1, 'dc8eec88-6375-408b-822f-83bcf9fc8467', '2025-07-01 11:09:01', '2025-07-01 11:10:31'),
(255, 220, 60, 1, 1, 'f581a69e-4ea7-4cf8-99b9-b66ad7e6cf4f', '2025-07-01 11:10:23', '2025-07-01 11:11:10'),
(259, 132, 60, 1, 1, 'a2848b5a-402b-4c56-b599-54a62fde57d5', '2025-07-01 11:13:15', '2025-07-01 11:13:58'),
(261, 222, 71, 1, 1, '829c3813-90d0-4f5f-8d5a-164ea71fbff9', '2025-07-01 11:13:22', '2025-07-01 11:13:44'),
(271, 227, 60, 3, 1, '4261f1bf-8a4e-4929-9bc7-3931c984f857', '2025-07-01 11:20:58', '2025-07-01 11:21:23'),
(273, 228, 48, 1, 1, '18717b89-0119-4910-8e44-ab44883d9344', '2025-07-01 11:21:17', '2025-07-01 11:21:56'),
(278, 230, 14, 1, 1, 'f2f885d8-86e8-4d4b-9fbf-afce343caf67', '2025-07-01 11:25:55', '2025-07-01 11:28:01'),
(287, 233, 60, 1, 1, '1ff10811-aed3-4ee8-9758-fb0d12d1e26f', '2025-07-01 11:35:27', '2025-07-01 11:36:16'),
(290, 236, 42, 1, 1, 'a0d72084-be94-41ef-aa8e-b6c52b26d0ab', '2025-07-01 11:37:01', '2025-07-01 11:37:52'),
(311, 241, 48, 1, 1, '2e861438-f7b2-4504-8f3d-8f816e1e0cc9', '2025-07-01 11:42:09', '2025-07-01 11:43:07'),
(317, 244, 60, 1, 1, 'a9f2b4c8-05fa-455d-9576-920e4ec4e95d', '2025-07-01 11:46:29', '2025-07-01 11:47:44'),
(318, 245, 64, 4, 1, 'f9559303-fafb-42d3-b81c-1aab943175b9', '2025-07-01 11:46:50', '2025-07-01 11:47:42'),
(323, 209, 40, 3, 1, 'd0d1d21f-3968-4dda-84dc-a4962efd1e76', '2025-07-01 11:53:05', '2025-07-01 11:56:27'),
(330, 250, 61, 1, 1, '49b53b67-4bea-4508-9b5a-e73f19c493a3', '2025-07-01 11:56:40', '2025-07-01 11:57:47'),
(332, 252, 12, 1, 1, '44bd461b-183b-4ab1-a8c9-782c98daedda', '2025-07-01 11:57:17', '2025-07-01 11:57:51'),
(333, 253, 62, 2, 1, 'bb365ff7-e83d-432c-8f63-6bfae7c789ed', '2025-07-01 11:57:33', '2025-07-01 11:59:00'),
(334, 209, 39, 3, 1, 'c7bf095b-ed3b-411a-980e-205aeec57f8a', '2025-07-01 11:59:26', '2025-07-01 12:00:04'),
(337, 256, 14, 1, 1, '0739af0f-3f09-4eb1-8647-aaa67b421d3e', '2025-07-01 12:04:04', '2025-07-01 12:04:35'),
(338, 257, 40, 1, 1, '54d5697d-06bf-47d6-b188-5e8fa3ce29f9', '2025-07-01 12:04:54', '2025-07-01 12:05:13'),
(340, 259, 12, 4, 1, '516417d5-7486-4791-8397-e3d7774625d7', '2025-07-01 12:05:26', '2025-07-01 12:05:58'),
(344, 262, 12, 1, 1, '008229ee-7a27-4fb0-9d69-254ab55c5b6c', '2025-07-01 12:09:55', '2025-07-01 12:10:29'),
(346, 263, 12, 1, 1, 'c7344799-aa2a-4177-b923-8935953f1819', '2025-07-01 12:15:20', '2025-07-01 12:16:18'),
(353, 267, 12, 1, 1, 'f3865fac-ad53-433f-8fb2-7925cd4f6c4f', '2025-07-01 12:22:11', '2025-07-01 12:24:55'),
(357, 271, 12, 2, 1, '72feffff-04eb-4cba-ac0c-0988c7d39924', '2025-07-01 12:25:15', '2025-07-01 12:25:42'),
(361, 274, 12, 2, 1, '4f35998c-bc35-420f-8bc0-937963d34d18', '2025-07-01 12:31:24', '2025-07-01 12:31:58'),
(367, 274, 12, 1, 1, '2a4f1a2d-0593-4226-bdbf-c233594a1af9', '2025-07-01 12:42:08', '2025-07-01 12:42:36'),
(368, 276, 12, 1, 1, '234ce6c5-cfb1-4ee5-8611-4f217345de47', '2025-07-01 12:43:43', '2025-07-01 12:44:05'),
(369, 276, 12, 1, 1, 'b07ed96d-0ea5-43ca-a54d-d34230d16f85', '2025-07-01 12:45:47', '2025-07-01 12:46:05'),
(370, 278, 47, 1, 1, 'c7ca6e07-bcfc-4823-9e8f-c9797775e5d6', '2025-07-01 12:46:44', '2025-07-01 12:47:27'),
(371, 279, 28, 4, 1, 'ebd3474f-ba13-42f7-9291-7640010d51ca', '2025-07-01 12:47:28', '2025-07-01 12:48:17'),
(376, 280, 12, 1, 1, '06a3bd2e-e9f6-4f13-9e0d-005b2d4f8cc2', '2025-07-01 12:51:51', '2025-07-01 12:54:38'),
(377, 281, 60, 1, 1, '16bb5174-6769-4d75-a0a1-388d380ee155', '2025-07-01 12:52:28', '2025-07-01 12:53:47'),
(379, 283, 14, 2, 1, '54541e0b-21fa-4d20-810c-95a6035acfe4', '2025-07-01 12:57:29', '2025-07-01 12:57:57'),
(382, 284, 12, 1, 1, '91d32709-0fb4-475e-9769-e97ddfa1195a', '2025-07-01 13:00:54', '2025-07-01 13:01:47'),
(384, 285, 39, 1, 1, 'cfb76216-0b12-4469-845a-f09fec6374bb', '2025-07-01 13:06:40', '2025-07-01 13:07:06'),
(394, 292, 14, 1, 1, '08dc487a-4628-48b9-8d74-58b275ee626c', '2025-07-01 13:25:08', '2025-07-01 13:25:34'),
(396, 293, 14, 2, 1, '52f0156e-fdc2-489e-a5c9-4388417c5bd3', '2025-07-01 13:26:06', '2025-07-01 13:26:44'),
(404, 297, 40, 1, 1, 'f1392601-cb48-4f87-ae15-89c0eb212e23', '2025-07-01 13:41:50', '2025-07-01 13:42:28'),
(405, 298, 47, 2, 1, '5e43fded-0d97-48a4-86de-e85c10820768', '2025-07-01 13:46:17', '2025-07-01 13:46:57'),
(408, 299, 12, 2, 1, '8e0c35ac-9b87-41ca-a41a-903de4072cde', '2025-07-01 13:48:05', '2025-07-01 13:48:34'),
(409, 300, 27, 1, 1, '1c5386a3-e2dd-48a6-910b-28e9a9bcd864', '2025-07-01 13:48:11', '2025-07-01 13:49:03'),
(427, 303, 27, 1, 1, '3922c065-98a4-4b6d-b7c1-413f7c396e9c', '2025-07-01 13:51:31', '2025-07-01 13:52:48'),
(435, 308, 40, 2, 1, 'e1ceddc2-4759-4a9d-858b-da5d53a31a12', '2025-07-01 14:07:54', '2025-07-01 14:08:30'),
(436, 309, 14, 2, 1, 'bc65bab8-f674-40f7-b91a-25de930dc861', '2025-07-01 14:13:36', '2025-07-01 14:14:14'),
(441, 309, 14, 10, 1, 'b7dde3cb-69c0-4e24-92b4-0b55e3634806', '2025-07-01 14:25:25', '2025-07-01 14:25:52'),
(442, 312, 71, 1, 1, 'c78209ef-d73a-49a8-8b73-08043a5d5690', '2025-07-01 14:26:02', '2025-07-01 14:26:43'),
(445, 314, 16, 2, 1, '6ca2614b-8a5c-4a64-8eb1-e633f00755b9', '2025-07-01 14:35:27', '2025-07-01 14:36:21'),
(447, 315, 39, 1, 1, '355fcd94-e1f7-413b-80ef-5b527c2f2a5a', '2025-07-01 14:39:48', '2025-07-01 14:40:40'),
(448, 316, 52, 20, 1, 'pi_3Rg4WFJUl8IbwjDS0MWG32Ad', '2025-07-01 14:45:58', '2025-07-01 14:46:49'),
(449, 316, 52, 12, 1, 'pi_3Rg4a3JUl8IbwjDS0VyT5ZPR', '2025-07-01 14:50:31', '2025-07-01 14:50:45'),
(452, 318, 16, 1, 1, '8d1d4840-0ea6-4167-882c-5f36c4e3b5ff', '2025-07-01 14:55:01', '2025-07-01 14:56:36'),
(454, 319, 16, 1, 1, '52ca645f-a886-4e53-a196-cbab367bace6', '2025-07-01 15:00:15', '2025-07-01 15:00:37'),
(457, 320, 28, 1, 1, 'cdb10e3b-a789-484f-aa0e-2b3c644eca8f', '2025-07-01 15:01:29', '2025-07-01 15:02:18'),
(459, 321, 16, 1, 1, '9ff7a0b4-7a6f-4b93-b9ff-9eb0bc58b93a', '2025-07-01 15:02:40', '2025-07-01 15:03:11'),
(460, 323, 37, 1, 1, '01f66acc-f83e-4582-8e3a-faf1712d1ecb', '2025-07-01 15:05:51', '2025-07-01 15:06:50'),
(462, 324, 16, 1, 1, 'ba82a924-f686-402b-befc-7a384747840b', '2025-07-01 15:13:33', '2025-07-01 15:14:02'),
(466, 328, 16, 2, 1, '35b893e2-9c5f-4235-abd2-7015b26ea605', '2025-07-01 15:22:40', '2025-07-01 15:23:06'),
(475, 327, 48, 2, 1, '0f12c9a0-c7dc-47bf-a3b2-df461d9fdd77', '2025-07-01 15:24:37', '2025-07-01 15:25:18'),
(478, 314, 16, 2, 1, 'd172a728-849b-4a23-bc8b-f274393d97a6', '2025-07-01 15:25:31', '2025-07-01 15:25:53'),
(481, 331, 12, 2, 1, '65ac109c-52b4-4d44-9a10-28dfe1f06777', '2025-07-01 15:25:54', '2025-07-01 15:26:19'),
(482, 332, 16, 1, 1, '50953ee2-3300-4fb9-ae90-f9e6915237c4', '2025-07-01 15:26:01', '2025-07-01 15:26:49'),
(485, 334, 52, 1, 1, '5de1f618-f9dd-478e-b1e9-6052b8c9ac9a', '2025-07-01 15:27:32', '2025-07-01 15:28:20'),
(486, 198, 58, 10, 1, 'f060e28a-5b6d-4cdd-a949-b819d921e631', '2025-07-01 15:28:37', '2025-07-01 15:29:14'),
(488, 336, 45, 2, 1, '5e414b2a-7d73-4333-a202-4a3391da2dcf', '2025-07-01 15:29:11', '2025-07-01 15:29:50'),
(497, 341, 28, 1, 1, '87c5743d-590b-4fee-ae10-1a8aef4a66da', '2025-07-01 15:49:29', '2025-07-01 15:50:32'),
(498, 342, 14, 1, 1, 'b0ab85c4-c407-4209-a1f8-f697b15a9eaf', '2025-07-01 15:53:35', '2025-07-01 15:54:15'),
(499, 343, 48, 2, 1, '2fc00afb-f787-43da-9199-30bda2dc9a73', '2025-07-01 15:54:27', '2025-07-01 15:54:58'),
(506, 346, 14, 4, 1, 'pi_3Rg5kqJUl8IbwjDS0P8eVBjr', '2025-07-01 16:03:57', '2025-07-01 16:05:57'),
(516, 350, 60, 3, 1, '1b953bc7-7e06-4104-86cc-aa5cc3d946e8', '2025-07-01 16:25:11', '2025-07-01 16:25:46'),
(519, 351, 49, 1, 1, 'b8539b0f-f318-487c-95f6-5fcea060393b', '2025-07-01 16:29:58', '2025-07-01 16:30:47'),
(521, 353, 48, 10, 1, 'e6f9c148-7950-4298-8fde-1c6971480ceb', '2025-07-01 16:33:15', '2025-07-01 16:33:53'),
(523, 351, 49, 1, 1, '661c4c42-45c1-4d85-85d1-f9c85805e16c', '2025-07-01 16:36:04', '2025-07-01 16:36:27'),
(527, 356, 27, 1, 1, '9cdb0230-b696-4c0c-b9dd-5afd10c98660', '2025-07-01 16:42:09', '2025-07-01 16:42:43'),
(531, 358, 27, 1, 1, 'd6e98e6a-a7b9-4f1c-9fa3-321b29d725ec', '2025-07-01 16:47:56', '2025-07-01 16:48:27'),
(532, 322, 21, 2, 1, '5a39d9fc-eb03-46c9-b69e-78c9541401d9', '2025-07-01 16:48:06', '2025-07-01 16:48:44'),
(542, 364, 28, 1, 1, '6658b33e-b5c7-4992-8299-0d95a28a6d1b', '2025-07-01 17:00:33', '2025-07-01 17:01:23'),
(545, 365, 23, 1, 1, '16666b80-7875-4c98-ba18-b2d70d40e702', '2025-07-01 17:03:47', '2025-07-01 17:04:23'),
(548, 363, 21, 1, 1, '09aae4b6-2fbf-4d71-bfcd-79e35e0d8b83', '2025-07-01 17:06:36', '2025-07-01 17:07:47'),
(550, 368, 61, 1, 1, '02622254-dfc5-4400-8113-573c7ea75624', '2025-07-01 17:09:43', '2025-07-01 17:10:08'),
(552, 369, 49, 1, 1, 'c6adb2a1-1817-42f0-a387-60293844a6e7', '2025-07-01 17:10:52', '2025-07-01 17:12:22'),
(553, 370, 61, 1, 1, 'b44da5c1-de03-42c1-97ca-180bba897702', '2025-07-01 17:12:35', '2025-07-01 17:13:12'),
(558, 372, 14, 1, 1, '012b0fa0-591d-428e-9df7-6efefc056a1a', '2025-07-01 17:15:27', '2025-07-01 17:15:54'),
(562, 375, 63, 1, 1, 'a63809a0-b5a3-44a5-9050-eaaa24bad636', '2025-07-01 17:24:27', '2025-07-01 17:25:52'),
(570, 378, 39, 1, 1, '3ec41754-1f65-4dde-8b11-50c7b6403af1', '2025-07-01 17:27:27', '2025-07-01 17:28:00'),
(571, 379, 39, 1, 1, '8534c755-12f9-4238-ab95-c352043e7ca4', '2025-07-01 17:28:46', '2025-07-01 17:30:38'),
(573, 381, 12, 1, 1, '9bb0f875-2509-456a-91a0-95a7223f3912', '2025-07-01 17:35:51', '2025-07-01 17:36:42'),
(575, 382, 60, 2, 1, '92893ce4-5359-4961-87e5-6e3763474c2c', '2025-07-01 17:35:55', '2025-07-01 17:36:31'),
(586, 388, 61, 1, 1, '1ece71b0-1b85-44e7-b1b4-7159dc4cdbab', '2025-07-01 18:04:21', '2025-07-01 18:04:56'),
(591, 390, 48, 1, 1, '709c4724-fcc1-4d53-a13a-e720544920e3', '2025-07-01 18:10:27', '2025-07-01 18:11:00'),
(609, 399, 49, 1, 1, '60965311-9e7f-4801-b88f-f2c9c5e27577', '2025-07-01 18:41:14', '2025-07-01 18:41:45'),
(615, 402, 16, 1, 1, '50cc9d90-58bf-45a6-a6a0-83ab44cca558', '2025-07-01 18:54:36', '2025-07-01 18:55:05'),
(618, 404, 41, 1, 1, 'f8221729-2cc4-4463-ae08-0d328a65043f', '2025-07-01 18:56:22', '2025-07-01 18:57:54'),
(621, 407, 40, 1, 1, '503484a7-7693-436a-9979-677148991104', '2025-07-01 18:57:48', '2025-07-01 18:58:40'),
(626, 404, 41, 1, 1, '85f05ed5-d390-4334-b20e-42e6a8d1bea3', '2025-07-01 18:58:51', '2025-07-01 18:59:14'),
(634, 403, 52, 1, 1, '2886a969-e539-4925-9e60-a3d513bb3a3b', '2025-07-01 18:59:13', '2025-07-01 18:59:35'),
(638, 410, 41, 1, 1, 'e65e0638-c985-4ef1-b0eb-422a6f5e06f3', '2025-07-01 19:02:02', '2025-07-01 19:03:06'),
(639, 411, 16, 1, 1, 'e34e6bf4-53cf-4a8e-ad3c-c51d1da4e26e', '2025-07-01 19:11:28', '2025-07-01 19:12:19'),
(640, 412, 71, 10, 1, 'd43d2212-bbb9-4c56-bfbe-db2c5adcce22', '2025-07-01 19:11:31', '2025-07-01 19:11:51'),
(641, 413, 52, 4, 1, '5d0017ec-c296-4ead-aafe-2449b76919dd', '2025-07-01 19:17:10', '2025-07-01 19:17:36'),
(647, 416, 16, 1, 1, '3fac6842-98a7-4010-b09c-9fffb227afb2', '2025-07-01 19:33:24', '2025-07-01 19:33:58'),
(657, 301, 21, 1, 1, '755e8300-1e32-4e64-b0b7-4556a8f7009a', '2025-07-01 20:03:23', '2025-07-01 20:03:51'),
(658, 423, 47, 2, 0, 'abc', '2025-07-01 20:04:50', '2025-07-01 20:04:50'),
(659, 423, 47, 2, 0, 'abc', '2025-07-01 20:05:00', '2025-07-01 20:05:00'),
(660, 423, 47, 2, 1, '8aea6e85-bb4e-4eb0-8d06-6ba7bf92593e', '2025-07-01 20:05:01', '2025-07-01 20:07:38'),
(661, 424, 21, 2, 1, '255408a4-6c03-4cde-97a5-9fd061ae092c', '2025-07-01 20:06:07', '2025-07-01 20:06:43'),
(662, 242, 21, 1, 1, 'c4dace0c-a13e-4e4b-83a5-837495d82a8e', '2025-07-01 20:08:07', '2025-07-01 20:12:02'),
(663, 1, 13, 1, 1, 'd6a69912-2c07-4671-b5c3-b5978b21309d', '2025-07-01 20:11:26', '2025-07-01 20:12:09'),
(664, 425, 12, 2, 1, '6ef425f6-c4d9-420f-acaf-6c5f8a468f32', '2025-07-01 20:11:26', '2025-07-01 20:12:12'),
(665, 426, 52, 1, 0, 'abc', '2025-07-01 20:14:17', '2025-07-01 20:14:17'),
(666, 427, 21, 1, 0, 'abc', '2025-07-01 20:15:58', '2025-07-01 20:15:58'),
(667, 427, 21, 1, 0, 'abc', '2025-07-01 20:15:59', '2025-07-01 20:15:59'),
(668, 427, 21, 1, 0, 'abc', '2025-07-01 20:16:01', '2025-07-01 20:16:01'),
(669, 427, 21, 1, 0, 'abc', '2025-07-01 20:16:02', '2025-07-01 20:16:02'),
(670, 428, 48, 1, 0, 'abc', '2025-07-01 20:16:46', '2025-07-01 20:16:46'),
(671, 429, 39, 1, 1, '6f82ce4d-e63b-4184-aa67-481c7a3fbb92', '2025-07-01 20:18:37', '2025-07-01 20:19:22'),
(672, 430, 39, 10, 0, 'abc', '2025-07-01 20:21:16', '2025-07-01 20:21:16'),
(673, 79, 58, 1, 0, 'abc', '2025-07-01 20:21:52', '2025-07-01 20:21:52'),
(674, 431, 58, 1, 0, 'abc', '2025-07-01 20:23:01', '2025-07-01 20:23:01'),
(675, 431, 58, 1, 0, 'abc', '2025-07-01 20:23:05', '2025-07-01 20:23:05'),
(676, 79, 14, 1, 0, 'abc', '2025-07-01 20:29:41', '2025-07-01 20:29:41'),
(677, 432, 33, 1, 0, 'abc', '2025-07-01 20:34:15', '2025-07-01 20:34:15'),
(678, 79, 16, 1, 0, 'abc', '2025-07-01 20:37:18', '2025-07-01 20:37:18'),
(679, 433, 48, 1, 1, '65d40cb5-eaf5-4590-a8d1-95f69f70d699', '2025-07-01 20:38:29', '2025-07-01 20:39:26'),
(680, 434, 47, 6, 0, 'abc', '2025-07-01 20:42:10', '2025-07-01 20:42:10'),
(681, 434, 47, 6, 0, 'abc', '2025-07-01 20:47:09', '2025-07-01 20:47:09'),
(682, 434, 47, 6, 0, 'abc', '2025-07-01 20:47:25', '2025-07-01 20:47:25'),
(683, 434, 47, 6, 0, 'abc', '2025-07-01 20:47:58', '2025-07-01 20:47:58'),
(685, 434, 47, 6, 0, 'abc', '2025-07-01 20:50:09', '2025-07-01 20:50:09'),
(687, 436, 40, 1, 1, '2456026a-0acb-4614-aac5-fb5b6a808d50', '2025-07-01 20:52:08', '2025-07-01 20:52:54'),
(688, 436, 40, 1, 0, 'abc', '2025-07-01 20:54:01', '2025-07-01 20:54:01'),
(689, 436, 40, 1, 0, 'abc', '2025-07-01 20:54:04', '2025-07-01 20:54:04'),
(690, 436, 40, 1, 1, 'fa68e7c3-0531-4d5a-9054-ca4802de0db3', '2025-07-01 20:54:07', '2025-07-01 20:55:01'),
(691, 437, 58, 1, 0, 'abc', '2025-07-01 20:54:49', '2025-07-01 20:54:49'),
(692, 434, 47, 6, 0, 'abc', '2025-07-01 20:55:17', '2025-07-01 20:55:17'),
(693, 436, 40, 1, 1, 'd0312571-cc57-4a19-b6c4-7f5a3d514e50', '2025-07-01 20:55:58', '2025-07-01 20:56:31'),
(694, 438, 40, 1, 0, 'abc', '2025-07-01 20:57:28', '2025-07-01 20:57:28'),
(695, 439, 13, 1, 1, '3912f9bd-77f3-45e0-b7ce-2e20983d72ab', '2025-07-01 21:02:06', '2025-07-01 21:03:35'),
(696, 440, 39, 1, 0, 'abc', '2025-07-01 21:07:01', '2025-07-01 21:07:01'),
(697, 441, 28, 1, 0, 'abc', '2025-07-01 21:07:10', '2025-07-01 21:07:10'),
(698, 442, 23, 1, 0, 'abc', '2025-07-01 21:08:04', '2025-07-01 21:08:04'),
(699, 79, 48, 1, 0, 'abc', '2025-07-01 21:08:08', '2025-07-01 21:08:08'),
(700, 443, 48, 2, 0, 'abc', '2025-07-01 21:09:38', '2025-07-01 21:09:38'),
(701, 443, 48, 2, 0, 'abc', '2025-07-01 21:09:40', '2025-07-01 21:09:40'),
(702, 442, 23, 1, 0, 'abc', '2025-07-01 21:11:19', '2025-07-01 21:11:19'),
(703, 227, 60, 2, 1, '3bfaeaee-a785-4533-94d5-ba7fe86ec5b9', '2025-07-01 21:12:08', '2025-07-01 21:12:27'),
(704, 397, 14, 1, 0, 'abc', '2025-07-01 21:12:50', '2025-07-01 21:12:50'),
(705, 444, 13, 1, 0, 'abc', '2025-07-01 21:13:03', '2025-07-01 21:13:03'),
(706, 79, 14, 1, 0, 'abc', '2025-07-01 21:13:32', '2025-07-01 21:13:32'),
(707, 436, 40, 3, 1, '5ee9e212-b5fc-4451-ad0e-c05097b8611a', '2025-07-01 21:13:40', '2025-07-01 21:14:30'),
(708, 342, 14, 1, 1, '6038b5a3-25a0-4b7a-9831-8b8af488bda9', '2025-07-01 21:13:54', '2025-07-01 21:14:36'),
(709, 436, 40, 1, 0, 'abc', '2025-07-01 21:15:21', '2025-07-01 21:15:21'),
(710, 436, 40, 1, 1, 'd504abf5-47f7-4514-9550-c36e38987a09', '2025-07-01 21:15:26', '2025-07-01 21:15:56'),
(711, 446, 60, 1, 0, 'abc', '2025-07-01 21:15:27', '2025-07-01 21:15:27'),
(712, 436, 40, 1, 1, '00b16f47-6262-4ad6-9205-b3b0584be7f2', '2025-07-01 21:19:07', '2025-07-01 21:19:48'),
(713, 79, 71, 1, 0, 'abc', '2025-07-01 21:19:11', '2025-07-01 21:19:11'),
(714, 397, 14, 2, 0, 'abc', '2025-07-01 21:20:51', '2025-07-01 21:20:51'),
(715, 79, 21, 1, 0, 'abc', '2025-07-01 21:26:15', '2025-07-01 21:26:15'),
(716, 447, 21, 1, 1, '8e42fe44-4a1e-43ec-8b31-c611e3ab5c01', '2025-07-01 21:26:45', '2025-07-01 21:27:29'),
(717, 79, 52, 1, 0, 'abc', '2025-07-01 21:27:46', '2025-07-01 21:27:46'),
(718, 448, 33, 1, 0, 'abc', '2025-07-01 21:28:02', '2025-07-01 21:28:02'),
(719, 449, 16, 1, 1, '8659c34b-9562-4b13-81aa-a5dd709b01ed', '2025-07-01 21:34:05', '2025-07-01 21:34:36'),
(720, 450, 21, 1, 0, 'abc', '2025-07-01 21:34:49', '2025-07-01 21:34:49'),
(721, 79, 32, 1, 0, 'abc', '2025-07-01 21:36:59', '2025-07-01 21:36:59'),
(722, 448, 33, 1, 0, 'abc', '2025-07-01 21:39:37', '2025-07-01 21:39:37'),
(723, 239, 60, 5, 0, 'abc', '2025-07-01 21:41:49', '2025-07-01 21:41:49'),
(724, 451, 60, 3, 0, 'abc', '2025-07-01 21:42:55', '2025-07-01 21:42:55'),
(725, 452, 13, 1, 0, 'abc', '2025-07-01 21:45:32', '2025-07-01 21:45:32'),
(726, 239, 60, 5, 0, 'abc', '2025-07-01 21:45:42', '2025-07-01 21:45:42'),
(727, 239, 60, 5, 0, 'abc', '2025-07-01 21:45:53', '2025-07-01 21:45:53'),
(728, 239, 60, 5, 0, 'abc', '2025-07-01 21:48:00', '2025-07-01 21:48:00'),
(729, 453, 61, 2, 1, 'd8349338-0b22-4da6-8784-1254b84a6e38', '2025-07-01 21:48:01', '2025-07-01 21:48:42'),
(730, 454, 61, 1, 1, '10a3959e-4b66-48d7-8038-cbccefe63e29', '2025-07-01 21:48:02', '2025-07-01 21:49:09'),
(731, 239, 60, 5, 0, 'abc', '2025-07-01 21:48:03', '2025-07-01 21:48:03'),
(732, 448, 33, 1, 0, 'abc', '2025-07-01 21:57:47', '2025-07-01 21:57:47'),
(733, 455, 37, 1, 1, '82021444-365c-4f38-b1ea-6ed2a8448461', '2025-07-01 21:59:32', '2025-07-01 22:00:42'),
(734, 456, 13, 1, 0, 'abc', '2025-07-01 22:02:47', '2025-07-01 22:02:47'),
(735, 59, 15, 6, 1, 'pi_3RgBQaJUl8IbwjDS0GVa6RwU', '2025-07-01 22:08:34', '2025-07-01 22:09:23'),
(736, 458, 32, 1, 0, 'abc', '2025-07-01 22:16:22', '2025-07-01 22:16:22'),
(737, 458, 32, 1, 0, 'abc', '2025-07-01 22:16:27', '2025-07-01 22:16:27'),
(738, 79, 52, 1, 0, 'abc', '2025-07-01 22:18:03', '2025-07-01 22:18:03'),
(739, 459, 61, 1, 1, '342e1abf-a4a1-4b27-a7c2-9533c07c993e', '2025-07-01 22:19:10', '2025-07-01 22:19:45'),
(740, 459, 61, 1, 1, '74fbad3b-035f-4f61-986f-e1bcced1d858', '2025-07-01 22:21:40', '2025-07-01 22:22:15'),
(741, 460, 47, 1, 0, 'abc', '2025-07-01 22:21:56', '2025-07-01 22:21:56'),
(742, 461, 33, 1, 0, 'abc', '2025-07-01 22:22:53', '2025-07-01 22:22:53'),
(743, 462, 45, 1, 1, '456c8fe2-2b55-406e-b603-1b78a6ce7c4f', '2025-07-01 22:23:25', '2025-07-01 22:24:02'),
(744, 461, 33, 1, 0, 'abc', '2025-07-01 22:24:07', '2025-07-01 22:24:07'),
(745, 79, 47, 1, 0, 'abc', '2025-07-01 22:25:36', '2025-07-01 22:25:36'),
(746, 463, 32, 1, 1, '1a578e4a-3f78-4b66-9d3a-9944ec642126', '2025-07-01 22:29:10', '2025-07-01 22:30:39'),
(747, 79, 61, 1, 0, 'abc', '2025-07-01 22:34:04', '2025-07-01 22:34:04'),
(748, 464, 61, 1, 0, 'abc', '2025-07-01 22:34:31', '2025-07-01 22:34:31'),
(749, 465, 13, 1, 1, '721ce8d2-0a5c-4300-a01f-4c5912292423', '2025-07-01 22:37:39', '2025-07-01 22:38:25'),
(750, 466, 21, 1, 1, '72f36cf8-da75-4413-acd8-5d34325a12ef', '2025-07-01 22:37:48', '2025-07-01 22:38:16'),
(751, 79, 45, 1, 0, 'abc', '2025-07-01 22:39:37', '2025-07-01 22:39:37'),
(752, 467, 45, 1, 1, '929234b8-2f35-4faa-b57a-3f98f5ccb32a', '2025-07-01 22:40:09', '2025-07-01 22:40:52'),
(753, 468, 13, 1, 0, 'abc', '2025-07-01 22:44:06', '2025-07-01 22:44:06'),
(754, 469, 13, 1, 0, 'abc', '2025-07-01 22:46:38', '2025-07-01 22:46:38'),
(755, 180, 14, 1, 0, 'abc', '2025-07-01 22:49:33', '2025-07-01 22:49:33'),
(756, 180, 14, 1, 0, 'abc', '2025-07-01 22:49:35', '2025-07-01 22:49:35'),
(757, 180, 14, 1, 0, 'abc', '2025-07-01 22:49:35', '2025-07-01 22:49:35'),
(758, 470, 13, 1, 1, '6796673e-aa5d-4814-91c9-5997db99cf75', '2025-07-01 22:52:29', '2025-07-01 22:53:10'),
(759, 471, 13, 1, 0, 'abc', '2025-07-01 22:55:45', '2025-07-01 22:55:45'),
(760, 472, 32, 1, 1, '1773c9bd-8760-4f12-86c0-00b437f24bb6', '2025-07-01 22:55:56', '2025-07-01 22:56:46'),
(761, 471, 13, 1, 1, '67bbbb70-dd66-4908-9619-6ad572eac47a', '2025-07-01 22:56:20', '2025-07-01 22:57:09'),
(762, 473, 13, 1, 0, 'abc', '2025-07-01 23:16:06', '2025-07-01 23:16:06'),
(763, 473, 13, 1, 0, 'abc', '2025-07-01 23:17:59', '2025-07-01 23:17:59'),
(764, 474, 12, 1, 1, 'ed8e9880-e558-46a8-a3fd-012d264d0470', '2025-07-01 23:19:11', '2025-07-01 23:20:01'),
(765, 475, 12, 1, 0, 'abc', '2025-07-01 23:54:48', '2025-07-01 23:54:48'),
(766, 476, 12, 1, 0, 'abc', '2025-07-01 23:57:02', '2025-07-01 23:57:02'),
(767, 477, 44, 2, 0, 'abc', '2025-07-02 00:07:06', '2025-07-02 00:07:06'),
(768, 477, 44, 2, 1, '06849edf-038b-4044-ad9d-ea3ad613d7b2', '2025-07-02 00:07:09', '2025-07-02 00:07:51'),
(769, 478, 62, 2, 0, 'abc', '2025-07-02 00:07:45', '2025-07-02 00:07:45'),
(770, 478, 62, 2, 1, 'fe4363eb-60bf-47fc-ba95-1cef9f065d57', '2025-07-02 00:07:48', '2025-07-02 00:08:56'),
(771, 79, 21, 2, 0, 'abc', '2025-07-02 00:20:55', '2025-07-02 00:20:55'),
(772, 479, 45, 1, 1, 'b0811958-40c0-4773-b420-cd312c47e309', '2025-07-02 00:28:13', '2025-07-02 00:28:39'),
(773, 79, 13, 1, 0, 'abc', '2025-07-02 00:29:25', '2025-07-02 00:29:25'),
(774, 480, 68, 20, 0, 'abc', '2025-07-02 01:03:44', '2025-07-02 01:03:44'),
(775, 481, 13, 1, 1, 'd0e84e1a-98b8-4084-97eb-acd6063b6a59', '2025-07-02 01:11:14', '2025-07-02 01:11:51'),
(776, 482, 71, 1, 0, 'abc', '2025-07-02 01:23:25', '2025-07-02 01:23:25'),
(777, 483, 71, 1, 1, '7702c31e-2d30-4aac-aa12-9afb3faaa4d4', '2025-07-02 01:24:33', '2025-07-02 01:24:50'),
(778, 484, 26, 20, 0, 'abc', '2025-07-02 02:30:12', '2025-07-02 02:30:12'),
(779, 485, 49, 1, 0, 'abc', '2025-07-02 02:31:26', '2025-07-02 02:31:26'),
(780, 486, 32, 1, 0, 'abc', '2025-07-02 02:35:02', '2025-07-02 02:35:02'),
(781, 79, 50, 1, 0, 'abc', '2025-07-02 04:25:45', '2025-07-02 04:25:45'),
(782, 487, 60, 1, 0, 'abc', '2025-07-02 04:46:20', '2025-07-02 04:46:20'),
(783, 487, 60, 1, 0, 'abc', '2025-07-02 04:49:29', '2025-07-02 04:49:29'),
(784, 79, 13, 1, 0, 'abc', '2025-07-02 04:55:15', '2025-07-02 04:55:15'),
(785, 488, 13, 1, 0, 'abc', '2025-07-02 04:57:35', '2025-07-02 04:57:35'),
(786, 487, 60, 1, 0, 'abc', '2025-07-02 04:59:19', '2025-07-02 04:59:19'),
(787, 79, 48, 1, 0, 'abc', '2025-07-02 05:51:37', '2025-07-02 05:51:37'),
(788, 79, 48, 1, 0, 'abc', '2025-07-02 05:54:35', '2025-07-02 05:54:35'),
(789, 489, 13, 1, 0, 'abc', '2025-07-02 06:23:21', '2025-07-02 06:23:21'),
(790, 490, 13, 1, 0, 'abc', '2025-07-02 06:24:01', '2025-07-02 06:24:01'),
(791, 490, 13, 1, 1, '709b0120-d7f8-44f4-8bcd-4c6a78081b2a', '2025-07-02 06:24:03', '2025-07-02 06:24:44'),
(792, 491, 48, 1, 0, 'abc', '2025-07-02 06:24:19', '2025-07-02 06:24:19'),
(793, 79, 21, 2, 0, 'abc', '2025-07-02 06:40:04', '2025-07-02 06:40:04'),
(794, 492, 13, 1, 1, '71dbddf8-f9bc-4403-a83c-d29dc9f85e40', '2025-07-02 06:42:54', '2025-07-02 06:43:25'),
(795, 493, 27, 1, 1, '64a1673e-8406-44ec-be60-509eaa7ed284', '2025-07-02 06:43:05', '2025-07-02 06:43:51'),
(796, 494, 48, 1, 0, 'abc', '2025-07-02 06:44:03', '2025-07-02 06:44:03'),
(797, 494, 48, 1, 0, 'abc', '2025-07-02 06:44:04', '2025-07-02 06:44:04'),
(798, 494, 48, 1, 1, 'pi_3RgJULJUl8IbwjDS1308WuDB', '2025-07-02 06:44:53', '2025-07-02 06:45:51'),
(799, 495, 49, 1, 0, 'abc', '2025-07-02 06:46:15', '2025-07-02 06:46:15'),
(800, 492, 12, 4, 1, 'b7e831ad-0113-4c9d-9f10-80bf24d71634', '2025-07-02 06:46:22', '2025-07-02 06:46:42'),
(802, 492, 13, 5, 1, 'd200195c-140e-4324-b567-8de4bffd0b91', '2025-07-02 06:48:46', '2025-07-02 06:49:05'),
(803, 497, 18, 1, 0, 'abc', '2025-07-02 06:54:15', '2025-07-02 06:54:15'),
(804, 498, 13, 1, 0, 'abc', '2025-07-02 06:56:30', '2025-07-02 06:56:30'),
(805, 498, 13, 1, 0, 'abc', '2025-07-02 06:56:31', '2025-07-02 06:56:31'),
(806, 499, 45, 1, 0, 'abc', '2025-07-02 07:06:34', '2025-07-02 07:06:34'),
(807, 500, 45, 2, 0, 'abc', '2025-07-02 07:10:00', '2025-07-02 07:10:00'),
(808, 500, 45, 2, 0, 'abc', '2025-07-02 07:10:01', '2025-07-02 07:10:01'),
(809, 501, 27, 1, 1, '5303b1a2-a303-45e9-92c4-d5695a1799c5', '2025-07-02 07:15:30', '2025-07-02 07:16:08'),
(810, 79, 71, 1, 0, 'abc', '2025-07-02 07:18:29', '2025-07-02 07:18:29'),
(811, 502, 71, 1, 0, 'abc', '2025-07-02 07:18:57', '2025-07-02 07:18:57'),
(812, 503, 13, 1, 1, 'bb398c64-32ea-4fa2-8444-197c415cab4d', '2025-07-02 07:27:18', '2025-07-02 07:28:08'),
(813, 504, 71, 4, 1, 'e1566f75-198c-40ee-bc9e-cddfbb4e531f', '2025-07-02 07:30:09', '2025-07-02 07:30:42'),
(814, 505, 48, 1, 0, 'abc', '2025-07-02 07:30:56', '2025-07-02 07:30:56'),
(815, 505, 48, 3, 0, 'abc', '2025-07-02 07:33:06', '2025-07-02 07:33:06'),
(816, 506, 20, 1, 0, 'abc', '2025-07-02 07:35:54', '2025-07-02 07:35:54'),
(817, 79, 13, 1, 0, 'abc', '2025-07-02 07:48:14', '2025-07-02 07:48:14'),
(818, 507, 13, 1, 1, '979589ec-984a-48c0-bda2-7ce017a4c15b', '2025-07-02 07:49:16', '2025-07-02 07:50:04'),
(819, 508, 68, 15, 1, 'pi_3RgKWzJUl8IbwjDS1eR1TqfE', '2025-07-02 07:50:32', '2025-07-02 07:52:35'),
(820, 79, 68, 15, 0, 'abc', '2025-07-02 07:53:42', '2025-07-02 07:53:42'),
(821, 508, 68, 15, 1, 'pi_3RgKYcJUl8IbwjDS16NIq61D', '2025-07-02 07:53:53', '2025-07-02 07:54:16'),
(822, 79, 71, 1, 0, 'abc', '2025-07-02 07:55:34', '2025-07-02 07:55:34'),
(823, 509, 71, 2, 0, 'abc', '2025-07-02 07:56:49', '2025-07-02 07:56:49'),
(824, 510, 13, 1, 0, 'abc', '2025-07-02 08:02:18', '2025-07-02 08:02:18'),
(825, 511, 71, 1, 0, 'abc', '2025-07-02 08:02:28', '2025-07-02 08:02:28'),
(826, 79, 48, 1, 0, 'abc', '2025-07-02 08:18:24', '2025-07-02 08:18:24'),
(827, 513, 45, 1, 1, 'b27dbd15-e57c-4ecc-a9f0-aed6be9c7d3e', '2025-07-02 08:21:37', '2025-07-02 08:22:29'),
(828, 79, 13, 1, 0, 'abc', '2025-07-02 08:24:54', '2025-07-02 08:24:54'),
(829, 79, 13, 1, 0, 'abc', '2025-07-02 08:24:55', '2025-07-02 08:24:55'),
(830, 79, 48, 1, 0, 'abc', '2025-07-02 08:27:06', '2025-07-02 08:27:06'),
(831, 514, 27, 1, 0, 'abc', '2025-07-02 08:30:27', '2025-07-02 08:30:27'),
(832, 79, 48, 1, 0, 'abc', '2025-07-02 08:35:33', '2025-07-02 08:35:33'),
(833, 79, 45, 1, 0, 'abc', '2025-07-02 08:37:17', '2025-07-02 08:37:17'),
(834, 515, 45, 1, 1, 'f6fe5d86-0a8a-4bfb-8d8b-ff9d6ae04bda', '2025-07-02 08:37:43', '2025-07-02 08:39:02'),
(836, 79, 48, 1, 0, 'abc', '2025-07-02 08:39:18', '2025-07-02 08:39:18'),
(837, 516, 45, 1, 1, '5ce11e33-c310-4c67-89b4-c1c65b52c490', '2025-07-02 08:41:07', '2025-07-02 08:41:40'),
(838, 79, 32, 1, 0, 'abc', '2025-07-02 08:45:13', '2025-07-02 08:45:13'),
(839, 79, 48, 1, 0, 'abc', '2025-07-02 08:45:39', '2025-07-02 08:45:39'),
(840, 517, 48, 1, 1, 'd77e3710-077f-4e72-b88f-5240ffbe8977', '2025-07-02 08:47:30', '2025-07-02 08:48:00'),
(841, 518, 48, 1, 0, 'abc', '2025-07-02 08:48:19', '2025-07-02 08:48:19'),
(842, 518, 48, 1, 1, 'a3228aa1-4c39-4f23-907f-12690a236a86', '2025-07-02 08:50:56', '2025-07-02 08:52:03'),
(843, 519, 13, 1, 1, '80a386be-b1dc-4269-885d-a5343640f6a4', '2025-07-02 08:53:39', '2025-07-02 08:54:15'),
(844, 520, 40, 1, 0, 'abc', '2025-07-02 08:57:51', '2025-07-02 08:57:51'),
(845, 520, 40, 1, 0, 'abc', '2025-07-02 08:57:51', '2025-07-02 08:57:51'),
(846, 521, 37, 3, 0, 'abc', '2025-07-02 09:03:04', '2025-07-02 09:03:04'),
(847, 522, 37, 3, 1, 'e652daea-a3ea-4e59-a992-d69f21dcfcbf', '2025-07-02 09:03:35', '2025-07-02 09:04:17'),
(848, 523, 48, 1, 0, 'abc', '2025-07-02 09:12:15', '2025-07-02 09:12:15'),
(850, 525, 39, 1, 0, 'abc', '2025-07-02 09:15:30', '2025-07-02 09:15:30'),
(851, 525, 39, 1, 1, '37d4ef18-9cea-4645-a943-f8fcb9de102a', '2025-07-02 09:15:32', '2025-07-02 09:16:14'),
(852, 79, 71, 1, 0, 'abc', '2025-07-02 09:16:23', '2025-07-02 09:16:23'),
(853, 526, 71, 1, 0, 'abc', '2025-07-02 09:16:42', '2025-07-02 09:16:42'),
(854, 527, 45, 1, 1, '21d18fb7-cb7c-4787-b60e-ef10ae78472e', '2025-07-02 09:18:16', '2025-07-02 09:19:08'),
(855, 528, 45, 1, 1, 'e6bebaa8-598d-461a-9124-cfe1c741f605', '2025-07-02 09:18:56', '2025-07-02 09:19:37'),
(856, 79, 71, 1, 0, 'abc', '2025-07-02 09:19:56', '2025-07-02 09:19:56'),
(857, 187, 45, 1, 0, 'abc', '2025-07-02 09:24:05', '2025-07-02 09:24:05'),
(858, 529, 28, 1, 0, 'abc', '2025-07-02 09:26:04', '2025-07-02 09:26:04'),
(859, 430, 39, 10, 0, 'abc', '2025-07-02 09:26:22', '2025-07-02 09:26:22'),
(860, 529, 28, 1, 1, 'f1baef70-d177-49d8-b160-34e8bf76edc9', '2025-07-02 09:27:41', '2025-07-02 09:28:14'),
(861, 531, 39, 10, 0, 'abc', '2025-07-02 09:31:12', '2025-07-02 09:31:12'),
(862, 532, 12, 1, 0, 'abc', '2025-07-02 09:31:37', '2025-07-02 09:31:37'),
(863, 530, 39, 10, 1, '6f9085fc-de1e-4846-9cf6-7a423763f7f2', '2025-07-02 09:33:01', '2025-07-02 09:33:49'),
(864, 79, 71, 1, 0, 'abc', '2025-07-02 09:36:59', '2025-07-02 09:36:59'),
(865, 533, 39, 20, 1, 'pi_3RgMDDJUl8IbwjDS0yR0hTUz', '2025-07-02 09:39:36', '2025-07-02 09:40:18'),
(866, 534, 40, 1, 1, '6f0aae39-4b85-4d1d-a626-3058653e5e43', '2025-07-02 09:40:35', '2025-07-02 09:41:07'),
(867, 535, 47, 20, 1, 'pi_3RgMDtJUl8IbwjDS0RR0SPKI', '2025-07-02 09:40:38', '2025-07-02 09:41:03'),
(868, 536, 34, 1, 0, 'abc', '2025-07-02 09:45:45', '2025-07-02 09:45:45'),
(869, 536, 34, 1, 1, '1dea3a7b-108d-4808-908a-acae84ca1365', '2025-07-02 09:45:47', '2025-07-02 09:46:19'),
(870, 535, 47, 20, 1, 'pi_3RgMLCJUl8IbwjDS1UuOC0Nu', '2025-07-02 09:48:17', '2025-07-02 09:48:36'),
(871, 537, 49, 1, 0, 'abc', '2025-07-02 09:49:31', '2025-07-02 09:49:31'),
(872, 538, 28, 1, 1, '6bf91ff3-5fbd-4ad4-aec7-32d66f6f5d8a', '2025-07-02 09:54:05', '2025-07-02 09:55:06'),
(873, 539, 12, 1, 0, 'abc', '2025-07-02 09:55:35', '2025-07-02 09:55:35'),
(874, 540, 18, 1, 0, 'abc', '2025-07-02 10:01:54', '2025-07-02 10:01:54'),
(875, 540, 18, 1, 1, '70cdd838-0490-484f-acc6-5b568735d64f', '2025-07-02 10:01:56', '2025-07-02 10:02:36'),
(876, 541, 45, 1, 0, 'abc', '2025-07-02 10:05:53', '2025-07-02 10:05:53'),
(877, 79, 33, 1, 0, 'abc', '2025-07-02 10:06:53', '2025-07-02 10:06:53'),
(878, 79, 48, 1, 0, 'abc', '2025-07-02 10:07:34', '2025-07-02 10:07:34'),
(879, 79, 48, 1, 0, 'abc', '2025-07-02 10:07:37', '2025-07-02 10:07:37'),
(880, 79, 33, 1, 0, 'abc', '2025-07-02 10:11:09', '2025-07-02 10:11:09'),
(881, 542, 66, 1, 1, '0636f222-aef2-4b18-bc1e-fb38d5c138b8', '2025-07-02 10:12:39', '2025-07-02 10:13:03'),
(882, 79, 13, 1, 0, 'abc', '2025-07-02 10:12:40', '2025-07-02 10:12:40'),
(883, 79, 34, 1, 0, 'abc', '2025-07-02 10:21:48', '2025-07-02 10:21:48'),
(884, 543, 47, 2, 0, 'abc', '2025-07-02 10:25:28', '2025-07-02 10:25:28'),
(885, 544, 16, 1, 0, 'abc', '2025-07-02 10:29:32', '2025-07-02 10:29:32'),
(886, 452, 13, 1, 0, 'abc', '2025-07-02 10:31:39', '2025-07-02 10:31:39'),
(887, 545, 21, 1, 0, 'abc', '2025-07-02 10:34:48', '2025-07-02 10:34:48'),
(888, 545, 21, 1, 0, 'abc', '2025-07-02 10:34:49', '2025-07-02 10:34:49'),
(889, 545, 21, 1, 0, 'abc', '2025-07-02 10:34:50', '2025-07-02 10:34:50'),
(890, 546, 47, 2, 0, 'abc', '2025-07-02 10:35:56', '2025-07-02 10:35:56'),
(891, 546, 47, 2, 0, 'abc', '2025-07-02 10:35:57', '2025-07-02 10:35:57'),
(892, 79, 21, 1, 0, 'abc', '2025-07-02 10:36:18', '2025-07-02 10:36:18'),
(893, 547, 21, 1, 0, 'abc', '2025-07-02 10:37:32', '2025-07-02 10:37:32'),
(894, 548, 16, 1, 1, 'bc72cc31-3c12-4758-b97e-91eacebda24d', '2025-07-02 10:43:32', '2025-07-02 10:43:50'),
(895, 79, 13, 1, 0, 'abc', '2025-07-02 10:45:58', '2025-07-02 10:45:58'),
(896, 301, 21, 1, 0, 'abc', '2025-07-02 10:49:59', '2025-07-02 10:49:59'),
(897, 301, 21, 1, 0, 'abc', '2025-07-02 10:50:05', '2025-07-02 10:50:05'),
(898, 549, 13, 1, 0, 'abc', '2025-07-02 10:52:55', '2025-07-02 10:52:55'),
(899, 118, 46, 1, 0, 'abc', '2025-07-02 10:54:56', '2025-07-02 10:54:56'),
(900, 550, 21, 1, 0, 'abc', '2025-07-02 10:59:30', '2025-07-02 10:59:30'),
(901, 550, 21, 1, 0, 'abc', '2025-07-02 10:59:33', '2025-07-02 10:59:33'),
(902, 551, 13, 1, 1, '458a1f7b-8bf0-4acf-b26b-8ffc25275d1b', '2025-07-02 11:01:59', '2025-07-02 11:02:21'),
(903, 550, 21, 1, 0, 'abc', '2025-07-02 11:02:36', '2025-07-02 11:02:36'),
(904, 552, 46, 4, 1, '99173f42-f005-43a2-adce-c10b4bd349cf', '2025-07-02 11:04:04', '2025-07-02 11:07:08'),
(905, 553, 21, 1, 0, 'abc', '2025-07-02 11:04:57', '2025-07-02 11:04:57'),
(906, 79, 15, 1, 0, 'abc', '2025-07-02 11:05:12', '2025-07-02 11:05:12'),
(907, 554, 34, 1, 1, 'af1d4960-9e96-4b1f-8fc4-d35f72d9eb20', '2025-07-02 11:07:05', '2025-07-02 11:07:35'),
(908, 555, 13, 1, 1, '94177b8d-660b-4784-b478-73dde48f4ce0', '2025-07-02 11:14:54', '2025-07-02 11:15:33'),
(909, 556, 12, 15, 0, 'abc', '2025-07-02 11:22:16', '2025-07-02 11:22:16'),
(910, 556, 12, 15, 0, 'abc', '2025-07-02 11:22:17', '2025-07-02 11:22:17'),
(911, 79, 13, 1, 0, 'abc', '2025-07-02 11:29:51', '2025-07-02 11:29:51'),
(912, 79, 71, 1, 0, 'abc', '2025-07-02 11:31:38', '2025-07-02 11:31:38'),
(913, 557, 46, 1, 1, 'af3e901c-7f15-4b98-888a-48ce8b3adb26', '2025-07-02 11:35:53', '2025-07-02 11:36:31'),
(914, 79, 46, 1, 0, 'abc', '2025-07-02 11:38:10', '2025-07-02 11:38:10'),
(915, 557, 46, 1, 1, 'b8080d6c-33d6-442d-a32c-ab297e4734d4', '2025-07-02 11:39:05', '2025-07-02 11:39:20'),
(916, 558, 39, 1, 1, '3590c56d-0a10-48eb-b795-423453ec5086', '2025-07-02 11:39:46', '2025-07-02 11:40:21'),
(917, 559, 12, 1, 1, 'c397c1f6-dcf0-48bb-8c96-11ba948674ac', '2025-07-02 11:44:38', '2025-07-02 11:45:26'),
(918, 173, 42, 10, 1, '16103fa4-02db-4226-a3a9-ba4822060f05', '2025-07-02 11:47:01', '2025-07-02 11:47:39'),
(919, 79, 13, 1, 0, 'abc', '2025-07-02 11:50:07', '2025-07-02 11:50:07'),
(920, 560, 14, 1, 0, 'abc', '2025-07-02 11:50:09', '2025-07-02 11:50:09'),
(921, 561, 13, 1, 0, 'abc', '2025-07-02 11:50:22', '2025-07-02 11:50:22'),
(922, 561, 13, 1, 0, 'abc', '2025-07-02 11:50:26', '2025-07-02 11:50:26'),
(923, 562, 13, 1, 0, 'abc', '2025-07-02 11:50:59', '2025-07-02 11:50:59'),
(924, 562, 13, 1, 0, 'abc', '2025-07-02 11:50:59', '2025-07-02 11:50:59'),
(925, 79, 64, 1, 0, 'abc', '2025-07-02 11:55:43', '2025-07-02 11:55:43'),
(926, 563, 14, 1, 0, 'abc', '2025-07-02 11:56:37', '2025-07-02 11:56:37'),
(927, 563, 14, 1, 0, 'abc', '2025-07-02 11:56:44', '2025-07-02 11:56:44'),
(928, 564, 13, 1, 0, 'abc', '2025-07-02 11:57:12', '2025-07-02 11:57:12'),
(929, 79, 64, 1, 0, 'abc', '2025-07-02 12:03:40', '2025-07-02 12:03:40'),
(930, 79, 64, 1, 0, 'abc', '2025-07-02 12:03:42', '2025-07-02 12:03:42'),
(931, 274, 12, 5, 1, '54449010-02a2-44d4-8d96-4b06bfa997ef', '2025-07-02 12:04:45', '2025-07-02 12:05:31'),
(932, 565, 13, 1, 0, 'abc', '2025-07-02 12:07:10', '2025-07-02 12:07:10'),
(933, 79, 33, 1, 0, 'abc', '2025-07-02 12:14:47', '2025-07-02 12:14:47'),
(934, 566, 13, 1, 0, 'abc', '2025-07-02 12:15:53', '2025-07-02 12:15:53'),
(936, 496, 40, 2, 0, 'abc', '2025-07-02 12:16:37', '2025-07-02 12:16:37'),
(937, 496, 40, 2, 0, 'abc', '2025-07-02 12:16:55', '2025-07-02 12:16:55'),
(938, 496, 40, 2, 0, 'abc', '2025-07-02 12:16:58', '2025-07-02 12:16:58'),
(939, 496, 40, 2, 0, 'abc', '2025-07-02 12:17:18', '2025-07-02 12:17:18'),
(940, 496, 40, 2, 1, '8342b177-10bd-4912-82ec-59ecc28fedea', '2025-07-02 12:17:20', '2025-07-02 12:17:51'),
(941, 568, 57, 1, 1, '0fdda24b-2cd1-45c0-b4f2-e53927bba366', '2025-07-02 12:17:43', '2025-07-02 12:18:16'),
(942, 79, 39, 1, 0, 'abc', '2025-07-02 12:18:48', '2025-07-02 12:18:48'),
(943, 496, 39, 1, 1, '4452a005-8246-4d9a-9aae-ccf013e71fad', '2025-07-02 12:18:59', '2025-07-02 12:19:47'),
(944, 213, 45, 1, 1, '9da1eb6d-68fc-4a8d-ac97-977e76599a1f', '2025-07-02 12:20:03', '2025-07-02 12:20:38'),
(945, 79, 34, 1, 0, 'abc', '2025-07-02 12:28:43', '2025-07-02 12:28:43'),
(946, 569, 34, 1, 0, 'abc', '2025-07-02 12:29:31', '2025-07-02 12:29:31');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `address` text NOT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `adjustments`
--
ALTER TABLE `adjustments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ambassadors`
--
ALTER TABLE `ambassadors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `asset_categories`
--
ALTER TABLE `asset_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `asset_expenses`
--
ALTER TABLE `asset_expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `asset_sales`
--
ALTER TABLE `asset_sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `asset_sale_details`
--
ALTER TABLE `asset_sale_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `asset_transfers`
--
ALTER TABLE `asset_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `billers`
--
ALTER TABLE `billers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_products`
--
ALTER TABLE `booking_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cash_registers`
--
ALTER TABLE `cash_registers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coins`
--
ALTER TABLE `coins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_groups`
--
ALTER TABLE `customer_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disposes`
--
ALTER TABLE `disposes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gift_cards`
--
ALTER TABLE `gift_cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gift_card_recharges`
--
ALTER TABLE `gift_card_recharges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hrm_settings`
--
ALTER TABLE `hrm_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `image_libraries`
--
ALTER TABLE `image_libraries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `judges`
--
ALTER TABLE `judges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `letters`
--
ALTER TABLE `letters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `letter_categories`
--
ALTER TABLE `letter_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `letter_templates`
--
ALTER TABLE `letter_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indexes for table `money_transfers`
--
ALTER TABLE `money_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_with_cheque`
--
ALTER TABLE `payment_with_cheque`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_with_credit_card`
--
ALTER TABLE `payment_with_credit_card`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_with_gift_card`
--
ALTER TABLE `payment_with_gift_card`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_with_paypal`
--
ALTER TABLE `payment_with_paypal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_setting`
--
ALTER TABLE `pos_setting`
  ADD UNIQUE KEY `pos_setting_id_unique` (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_adjustments`
--
ALTER TABLE `product_adjustments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_batches`
--
ALTER TABLE `product_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_purchases`
--
ALTER TABLE `product_purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_quotation`
--
ALTER TABLE `product_quotation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_returns`
--
ALTER TABLE `product_returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_sales`
--
ALTER TABLE `product_sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_transfer`
--
ALTER TABLE `product_transfer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_warehouse`
--
ALTER TABLE `product_warehouse`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_product_return`
--
ALTER TABLE `purchase_product_return`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `return_purchases`
--
ALTER TABLE `return_purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reward_point_settings`
--
ALTER TABLE `reward_point_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stations`
--
ALTER TABLE `stations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_counts`
--
ALTER TABLE `stock_counts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `variants`
--
ALTER TABLE `variants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `adjustments`
--
ALTER TABLE `adjustments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ambassadors`
--
ALTER TABLE `ambassadors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_categories`
--
ALTER TABLE `asset_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_expenses`
--
ALTER TABLE `asset_expenses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_sales`
--
ALTER TABLE `asset_sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_sale_details`
--
ALTER TABLE `asset_sale_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_transfers`
--
ALTER TABLE `asset_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `billers`
--
ALTER TABLE `billers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_products`
--
ALTER TABLE `booking_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_registers`
--
ALTER TABLE `cash_registers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coins`
--
ALTER TABLE `coins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_groups`
--
ALTER TABLE `customer_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disposes`
--
ALTER TABLE `disposes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gift_cards`
--
ALTER TABLE `gift_cards`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gift_card_recharges`
--
ALTER TABLE `gift_card_recharges`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_settings`
--
ALTER TABLE `hrm_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `image_libraries`
--
ALTER TABLE `image_libraries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `judges`
--
ALTER TABLE `judges`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `letters`
--
ALTER TABLE `letters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `letter_categories`
--
ALTER TABLE `letter_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `letter_templates`
--
ALTER TABLE `letter_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT for table `money_transfers`
--
ALTER TABLE `money_transfers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_with_cheque`
--
ALTER TABLE `payment_with_cheque`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_with_credit_card`
--
ALTER TABLE `payment_with_credit_card`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_with_gift_card`
--
ALTER TABLE `payment_with_gift_card`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_with_paypal`
--
ALTER TABLE `payment_with_paypal`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrolls`
--
ALTER TABLE `payrolls`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_adjustments`
--
ALTER TABLE `product_adjustments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_batches`
--
ALTER TABLE `product_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_purchases`
--
ALTER TABLE `product_purchases`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_quotation`
--
ALTER TABLE `product_quotation`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_returns`
--
ALTER TABLE `product_returns`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_sales`
--
ALTER TABLE `product_sales`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_transfer`
--
ALTER TABLE `product_transfer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_warehouse`
--
ALTER TABLE `product_warehouse`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_product_return`
--
ALTER TABLE `purchase_product_return`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `return_purchases`
--
ALTER TABLE `return_purchases`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reward_point_settings`
--
ALTER TABLE `reward_point_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stations`
--
ALTER TABLE `stations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_counts`
--
ALTER TABLE `stock_counts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=570;

--
-- AUTO_INCREMENT for table `variants`
--
ALTER TABLE `variants`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=947;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
