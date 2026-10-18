-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2026 at 09:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ceac_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `admin_id`, `action`, `description`, `ip_address`, `created_at`) VALUES
(1, 1, 'login', 'Admin login successful', '::1', '2026-06-26 10:37:48'),
(2, 1, 'booking_approve', 'Booking CEAC-61F8-4230 set to approved', '::1', '2026-06-26 10:44:24'),
(3, 1, 'booking_complete', 'Booking CEAC-61F8-4230 set to completed', '::1', '2026-06-26 10:44:46'),
(4, 1, 'logout', 'Admin logged out', '::1', '2026-06-26 11:46:43'),
(5, 1, 'login', 'Admin login successful', '::1', '2026-06-26 19:35:05'),
(6, 1, 'update_hall', 'Hall updated: CEAC Hall', '::1', '2026-06-26 19:37:55'),
(7, 1, 'update_hall', 'Hall updated: CEAC Hall', '::1', '2026-06-26 19:37:59'),
(8, 1, 'update_hall', 'Hall updated: CEAC Hall', '::1', '2026-06-26 19:38:06'),
(9, 1, 'update_hall', 'Hall updated: CEAC Hall', '::1', '2026-06-26 19:38:21'),
(10, 1, 'update_hall', 'Hall updated: CEAC Hall', '::1', '2026-06-26 19:38:35'),
(11, 1, 'update_hall', 'Hall updated: CEAC Hall', '::1', '2026-06-26 19:38:41'),
(12, 1, 'login', 'Admin login successful', '::1', '2026-06-27 00:19:36'),
(13, 1, 'add_hall', 'New hall: No Hall', '::1', '2026-06-27 00:20:46'),
(14, 1, 'delete_hall', 'Hall deleted: No Hall', '::1', '2026-06-27 00:23:12'),
(15, 1, 'update_hall', 'Hall updated: CEAC Hall', '::1', '2026-06-27 00:23:44'),
(16, 1, 'logout', 'Admin logged out', '::1', '2026-06-27 00:30:34'),
(17, 1, 'login', 'Admin login successful', '::1', '2026-06-27 01:10:15'),
(18, 1, 'add_hall', 'New hall: No Hall', '::1', '2026-06-27 01:10:53'),
(19, 1, 'logout', 'Admin logged out', '::1', '2026-06-27 01:10:59'),
(20, 1, 'login', 'Admin login successful', '::1', '2026-06-27 01:11:21'),
(21, 1, 'add_hall', 'New hall: Nice Hall', '::1', '2026-06-27 01:11:57'),
(22, 1, 'logout', 'Admin logged out', '::1', '2026-06-27 01:12:02'),
(23, 1, 'login', 'Admin login successful', '::1', '2026-06-27 09:56:02'),
(24, 1, 'update_hall', 'Hall updated: Nice Hall', '::1', '2026-06-27 10:32:38'),
(25, 1, 'update_hall', 'Hall updated: CEAC Hall', '::1', '2026-06-27 10:33:52'),
(26, 1, 'update_hall', 'Hall updated: No Hall', '::1', '2026-06-27 10:44:47'),
(27, 1, 'update_hall', 'Hall updated: No Hall', '::1', '2026-06-27 10:55:13'),
(28, 1, 'update_hall', 'Hall updated: No Hall', '::1', '2026-06-27 10:56:06'),
(29, 1, 'change_password', 'Admin changed their password', '::1', '2026-06-27 11:16:05'),
(30, 1, 'logout', 'Admin logged out', '::1', '2026-06-27 11:16:17'),
(31, 1, 'login', 'Admin login successful', '::1', '2026-06-27 11:16:35'),
(32, 1, 'create_event', 'Event: Holy Ghost Convention', '::1', '2026-06-27 11:23:33'),
(33, 1, 'logout', 'Admin logged out', '::1', '2026-06-27 12:03:10'),
(34, 2, 'login', 'Admin login successful', '::1', '2026-06-27 12:04:30'),
(35, 2, 'logout', 'Admin logged out', '::1', '2026-06-27 12:06:41'),
(36, 2, 'login', 'Admin login successful', '::1', '2026-06-27 12:08:54'),
(37, 2, 'logout', 'Admin logged out', '::1', '2026-06-27 12:10:24'),
(38, 1, 'login', 'Admin login successful', '::1', '2026-06-27 12:11:24'),
(39, 1, 'logout', 'Admin logged out', '::1', '2026-06-27 12:37:04'),
(40, 1, 'login', 'Admin login successful', '::1', '2026-06-27 14:14:11'),
(41, 1, 'update_hall', 'Hall updated: No Hall', '::1', '2026-06-27 14:16:51'),
(42, 1, 'update_hall', 'Hall updated: CEAC Hall', '::1', '2026-06-27 14:17:34'),
(43, 1, 'update_hall', 'Hall updated: Nice Hall', '::1', '2026-06-27 14:17:50'),
(44, 1, 'login', 'Admin login successful', '::1', '2026-06-27 19:19:16'),
(45, 1, 'update_event', 'Event: Cell Meetings', '::1', '2026-06-27 19:23:44'),
(46, 1, 'update_event', 'Event: Holy Ghost Convention', '::1', '2026-06-27 19:24:10'),
(47, 1, 'logout', 'Admin logged out', '::1', '2026-06-27 19:31:03'),
(48, 1, 'login', 'Admin login successful', '::1', '2026-06-27 19:33:31'),
(49, 1, 'booking_approve', 'Booking CEAC-EDB6-1571 set to approved', '::1', '2026-06-27 19:58:24'),
(50, 1, 'booking_complete', 'Booking CEAC-EDB6-1571 set to completed', '::1', '2026-06-27 19:58:40'),
(51, 2, 'login', 'Admin login successful', '::1', '2026-06-28 09:13:54'),
(52, 2, 'create_event', 'Event: Holy Ghost Conventin', '::1', '2026-06-28 10:09:28'),
(53, 2, 'create_event', 'Event: Holy Ghost Conventin', '::1', '2026-06-28 10:16:05'),
(54, 2, 'update_event', 'Event: Fellowship Meetings', '::1', '2026-06-28 10:32:59'),
(55, 2, 'update_event', 'Event: Holy Ghost Conventin', '::1', '2026-06-28 10:36:46'),
(56, 2, 'update_event', 'Event: Holy Ghost Conventin', '::1', '2026-06-28 10:58:36'),
(57, 2, 'update_event', 'Event: Holy Ghost Conventin', '::1', '2026-06-28 11:19:34'),
(58, 2, 'update_event', 'Event: Executive Meetings', '::1', '2026-06-28 11:20:31'),
(59, 1, 'login', 'Admin login successful', '::1', '2026-06-29 04:05:18'),
(60, 1, 'login', 'Admin login successful', '::1', '2026-06-29 09:48:32'),
(61, 1, 'booking_reject', 'Booking CEAC-247B-8787 set to rejected', '::1', '2026-06-29 09:56:45'),
(62, 1, 'update_hall', 'Hall updated: Nice Hall', '::1', '2026-06-29 10:44:07'),
(63, 1, 'login', 'Admin login successful', '::1', '2026-06-29 17:39:06'),
(64, 1, 'update_event', 'Event: Executive Meetings', '::1', '2026-06-29 17:40:06'),
(65, 2, 'login', 'Admin login successful', '::1', '2026-06-29 19:46:55'),
(66, 2, 'login', 'Admin login successful', '::1', '2026-06-30 00:29:44'),
(67, 2, 'booking_reject', 'Booking CEAC-0D48-7394 set to rejected', '::1', '2026-06-30 00:32:40'),
(68, 1, 'login', 'Admin login successful', '::1', '2026-06-30 08:44:41'),
(69, 1, 'login', 'Admin login successful', '::1', '2026-06-30 17:11:40'),
(70, 1, 'login', 'Admin login successful', '::1', '2026-06-30 19:18:55'),
(71, 1, 'login', 'Admin login successful', '::1', '2026-06-30 22:51:49'),
(72, 1, 'booking_approve', 'Booking CEAC-CCCF-5966 set to approved', '::1', '2026-06-30 22:59:36'),
(73, 1, 'update_event', 'Event: Holy Ghost Conventin', '::1', '2026-06-30 23:06:27'),
(74, 1, 'update_event', 'Event: Holy Ghost Conventin', '::1', '2026-06-30 23:06:57'),
(75, 1, 'update_event', 'Event: Holy Ghost Conventin', '::1', '2026-06-30 23:17:59'),
(76, 1, 'update_event', 'Event: Holy Ghost Conventin', '::1', '2026-06-30 23:18:24'),
(77, 1, 'update_event', 'Event: Fellowship Meetings', '::1', '2026-06-30 23:20:42'),
(78, 1, 'update_event', 'Event: Holy Ghost Conventin', '::1', '2026-06-30 23:20:59'),
(79, 1, 'update_event', 'Event: Holy Ghost Conventin', '::1', '2026-06-30 23:22:52'),
(80, 1, 'update_event', 'Event: Holy Ghost Conventin', '::1', '2026-06-30 23:23:22'),
(81, 1, 'update_event', 'Event: Cell Meetings', '::1', '2026-06-30 23:26:53'),
(82, 1, 'update_event', 'Event: Fellowship Meetings', '::1', '2026-06-30 23:27:24'),
(83, 1, 'update_event', 'Event: Fellowship Meetings', '::1', '2026-06-30 23:28:13'),
(84, 1, 'update_event', 'Event: Cell Meetings', '::1', '2026-06-30 23:28:38'),
(85, 1, 'login', 'Admin login successful', '::1', '2026-07-01 10:06:55'),
(86, 1, 'booking_complete', 'Booking CEAC-CCCF-5966 set to completed', '::1', '2026-07-01 11:12:38'),
(87, 1, 'logout', 'Admin logged out', '::1', '2026-07-01 11:14:18'),
(88, 2, 'login', 'Admin login successful', '::1', '2026-07-01 11:14:35'),
(89, 2, 'logout', 'Admin logged out', '::1', '2026-07-01 11:15:15'),
(90, 1, 'login', 'Admin login successful', '::1', '2026-07-01 11:15:47'),
(91, 2, 'login', 'Admin login successful', '::1', '2026-07-01 17:08:45'),
(92, 2, 'hard_delete_booking', 'Permanently deleted booking ID 1', '::1', '2026-07-01 17:09:05'),
(93, 2, 'hard_delete_booking', 'Permanently deleted booking ID 2', '::1', '2026-07-01 17:09:10'),
(94, 2, 'hard_delete_booking', 'Permanently deleted booking ID 3', '::1', '2026-07-01 17:09:13'),
(95, 2, 'hard_delete_booking', 'Permanently deleted booking ID 4', '::1', '2026-07-01 17:09:16'),
(96, 2, 'hard_delete_booking', 'Permanently deleted booking ID 5', '::1', '2026-07-01 17:09:20'),
(97, 2, 'booking_approve', 'Booking CEAC-E569-1600 set to approved', '::1', '2026-07-01 17:54:17'),
(98, 2, 'booking_reject', 'Booking CEAC-6C3B-7628 set to rejected', '::1', '2026-07-01 17:55:20'),
(99, 2, 'login', 'Admin login successful', '::1', '2026-07-02 07:13:13'),
(100, 2, 'login', 'Admin login successful', '::1', '2026-07-02 13:33:22'),
(101, 2, 'booking_reject', 'Booking CEAC-E92E-5138 set to rejected', '::1', '2026-07-02 13:33:45'),
(102, 2, 'logout', 'Admin logged out', '::1', '2026-07-02 13:35:13'),
(103, 2, 'login', 'Admin login successful', '::1', '2026-07-02 13:36:30'),
(104, 2, 'booking_approve', 'Booking CEAC-637B-4678 set to approved', '::1', '2026-07-02 13:57:07'),
(105, 2, 'hard_delete_booking', 'Permanently deleted booking ID 8', '::1', '2026-07-02 13:57:28'),
(106, 2, 'hard_delete_booking', 'Permanently deleted booking ID 9', '::1', '2026-07-02 13:57:32'),
(107, 2, 'hard_delete_booking', 'Permanently deleted booking ID 7', '::1', '2026-07-02 13:57:35'),
(108, 2, 'hard_delete_booking', 'Permanently deleted booking ID 6', '::1', '2026-07-02 13:57:38'),
(109, 2, 'delete_hall', 'Deleted hall: Nice Hall', '::1', '2026-07-02 13:57:57'),
(110, 2, 'delete_hall', 'Deleted hall: No Hall', '::1', '2026-07-02 13:58:03'),
(111, 2, 'update_hall', 'Hall updated: CEAC Hall', '::1', '2026-07-02 13:58:13'),
(112, 1, 'login', 'Admin login successful', '154.161.29.31', '2026-07-02 14:58:27'),
(113, 1, 'login', 'Admin login successful', '154.164.146.11', '2026-07-02 17:13:40'),
(114, 1, 'booking_approve', 'Booking CEAC-DCC7-8557 set to approved', '154.164.146.11', '2026-07-02 17:20:30'),
(115, 1, 'booking_reject', 'Booking CEAC-C987-8830 set to rejected', '154.164.146.11', '2026-07-02 17:38:32'),
(116, 1, 'logout', 'Admin logged out', '154.164.146.11', '2026-07-02 17:51:31'),
(117, 1, 'login', 'Admin login successful', '154.164.146.11', '2026-07-02 17:58:12'),
(118, 1, 'logout', 'Admin logged out', '154.164.146.11', '2026-07-02 17:58:28'),
(119, 2, 'login', 'Admin login successful', '154.164.146.11', '2026-07-02 17:58:44'),
(120, 2, 'logout', 'Admin logged out', '154.164.146.11', '2026-07-02 17:59:21'),
(121, 1, 'login', 'Admin login successful', '154.164.146.11', '2026-07-03 12:57:12'),
(122, 2, 'login', 'Admin login successful', '154.164.146.11', '2026-07-03 13:00:34'),
(123, 2, 'login', 'Admin login successful', '154.164.146.11', '2026-07-03 18:39:38'),
(124, 2, 'login', 'Admin login successful', '154.164.146.11', '2026-07-04 02:08:06'),
(125, 2, 'login', 'Admin login successful', '154.164.146.11', '2026-07-04 15:17:04'),
(126, 2, 'update_hall', 'Hall updated: CEAC Hall', '154.164.146.11', '2026-07-04 15:18:06'),
(127, 2, 'login', 'Admin login successful', '154.160.22.64', '2026-07-09 04:23:15'),
(128, 2, 'login', 'Admin login successful', '154.161.28.123', '2026-07-09 17:29:01'),
(129, 2, 'update_hall', 'Hall updated: CEAC Hall', '154.161.28.123', '2026-07-09 17:29:50'),
(130, 2, 'update_hall', 'Hall updated: CEAC Hall', '154.161.28.123', '2026-07-09 17:37:51'),
(131, 2, 'login', 'Admin login successful', '154.161.152.51', '2026-07-09 22:49:23'),
(132, 2, 'update_hall', 'Hall updated: CEAC Hall', '154.161.152.51', '2026-07-09 22:49:36'),
(133, 2, 'update_hall', 'Hall updated: CEAC Hall', '154.161.152.51', '2026-07-09 22:49:46'),
(134, 2, 'update_hall', 'Hall updated: CEAC Hall', '154.161.152.51', '2026-07-09 22:50:06'),
(135, 2, 'update_hall', 'Hall updated: CEAC Hall', '154.161.152.51', '2026-07-09 22:50:30'),
(136, 2, 'update_hall', 'Hall updated: CEAC Hall', '154.161.152.51', '2026-07-09 22:50:42'),
(137, 2, 'update_hall', 'Hall updated: CEAC Hall', '154.161.152.51', '2026-07-09 22:52:15'),
(138, 2, 'update_hall', 'Hall updated: CEAC Hall', '154.161.152.51', '2026-07-09 22:59:18'),
(139, 2, 'login', 'Admin login successful', '154.164.146.11', '2026-07-10 15:39:33'),
(140, 2, 'update_hall', 'Hall updated: CEAC Hall', '154.164.146.11', '2026-07-10 15:39:42'),
(141, 2, 'login', 'Admin login successful', '154.164.146.11', '2026-07-10 18:34:18'),
(142, 2, 'login', 'Admin login successful', '154.164.146.11', '2026-07-11 07:14:55'),
(143, 2, 'hard_delete_booking', 'Permanently deleted booking ID 12', '154.161.132.10', '2026-07-11 07:31:11'),
(144, 2, 'hard_delete_booking', 'Permanently deleted booking ID 13', '154.161.132.10', '2026-07-11 07:31:15'),
(145, 2, 'login', 'Admin login successful', '154.164.146.11', '2026-07-11 11:48:58'),
(146, 2, 'booking_approve', 'Booking CEAC-B2C9-1486 set to approved', '154.164.146.11', '2026-07-11 13:06:46'),
(147, 2, 'logout', 'Admin logged out', '154.164.146.11', '2026-07-11 13:44:29'),
(148, 1, 'login', 'Admin login successful', '154.164.146.11', '2026-07-11 13:45:37'),
(149, 2, 'login', 'Admin login successful', '154.164.146.11', '2026-07-12 12:26:03'),
(150, 2, 'hard_delete_booking', 'Permanently deleted booking ID 14', '154.164.146.11', '2026-07-12 12:26:20'),
(151, 2, 'hard_delete_booking', 'Permanently deleted booking ID 16', '154.164.146.11', '2026-07-12 12:26:25'),
(152, 2, 'hard_delete_booking', 'Permanently deleted booking ID 17', '154.160.1.109', '2026-07-12 12:26:30'),
(153, 2, 'booking_approve', 'Booking CEAC-E55B-3283 set to approved', '154.164.146.11', '2026-07-12 12:26:38'),
(154, 2, 'booking_reject', 'Booking CEAC-F609-8907 set to rejected', '154.164.146.11', '2026-07-12 12:27:01'),
(155, 2, 'login', 'Admin login successful', '154.160.1.109', '2026-07-12 13:27:29'),
(156, 2, 'booking_approve', 'Booking CEAC-DFD2-9925 set to approved', '154.164.146.11', '2026-07-12 13:40:45'),
(157, 2, 'hard_delete_booking', 'Permanently deleted booking ID 10', '154.164.146.11', '2026-07-12 13:42:17'),
(158, 2, 'hard_delete_booking', 'Permanently deleted booking ID 11', '154.164.146.11', '2026-07-12 13:42:21'),
(159, 2, 'hard_delete_booking', 'Permanently deleted booking ID 15', '154.164.146.11', '2026-07-12 13:42:25'),
(160, 2, 'booking_approve', 'Booking CEAC-06A0-7421 set to approved', '154.164.146.11', '2026-07-12 13:45:23'),
(161, 2, 'login', 'Admin login successful', '154.164.146.11', '2026-07-12 18:49:28'),
(162, 2, 'login', 'Admin login successful', '154.164.146.11', '2026-07-13 01:55:06'),
(163, 2, 'login', 'Admin login successful', '154.164.146.11', '2026-07-13 03:03:59'),
(164, 2, 'hard_delete_booking', 'Permanently deleted booking ID 19', '154.164.146.11', '2026-07-13 03:05:02'),
(165, 2, 'hard_delete_booking', 'Permanently deleted booking ID 18', '154.164.146.11', '2026-07-13 03:05:10'),
(166, 2, 'hard_delete_booking', 'Permanently deleted booking ID 27', '154.164.146.11', '2026-07-13 05:03:30'),
(167, 2, 'hard_delete_booking', 'Permanently deleted booking ID 25', '154.164.146.11', '2026-07-13 05:03:34'),
(168, 2, 'hard_delete_booking', 'Permanently deleted booking ID 24', '154.164.146.11', '2026-07-13 05:03:38'),
(169, 2, 'hard_delete_booking', 'Permanently deleted booking ID 22', '154.164.146.11', '2026-07-13 05:03:57'),
(170, 2, 'login', 'Admin login successful', '154.164.146.11', '2026-07-13 11:17:59'),
(171, 2, 'login', 'Admin login successful', '154.164.146.11', '2026-07-13 12:11:27'),
(172, 2, 'login', 'Admin login successful', '154.160.1.109', '2026-07-13 14:23:37'),
(173, 2, 'update_hall', 'Hall updated: CEAC Hall', '154.164.146.11', '2026-07-13 14:28:50'),
(174, 2, 'update_hall', 'Hall updated: CEAC Hall', '154.164.146.11', '2026-07-13 14:33:58'),
(175, 2, 'booking_approve', 'Booking CEAC-3AAE-5578 set to approved', '154.164.146.11', '2026-07-13 16:01:28'),
(176, 2, 'login', 'Admin login successful', '154.161.129.232', '2026-07-13 20:10:29'),
(177, 2, 'login', 'Admin login successful', '154.161.129.232', '2026-07-14 11:50:33'),
(178, 2, 'login', 'Admin login successful', '154.161.129.232', '2026-07-14 16:35:33'),
(179, 2, 'login', 'Admin login successful', '154.161.129.232', '2026-07-14 17:36:16'),
(180, 2, 'login', 'Admin login successful', '127.0.0.1', '2026-10-16 15:52:30'),
(181, 2, 'hard_delete_booking', 'Permanently deleted booking ID 21', '127.0.0.1', '2026-10-16 15:59:41'),
(182, 2, 'hard_delete_booking', 'Permanently deleted booking ID 20', '127.0.0.1', '2026-10-16 15:59:51'),
(183, 2, 'hard_delete_booking', 'Permanently deleted booking ID 23', '127.0.0.1', '2026-10-16 15:59:55'),
(184, 2, 'hard_delete_booking', 'Permanently deleted booking ID 28', '127.0.0.1', '2026-10-16 16:00:02'),
(185, 2, 'hard_delete_booking', 'Permanently deleted booking ID 26', '127.0.0.1', '2026-10-16 16:00:05'),
(186, 2, 'hard_delete_booking', 'Permanently deleted booking ID 29', '127.0.0.1', '2026-10-16 16:00:10'),
(187, 2, 'hard_delete_booking', 'Permanently deleted booking ID 30', '127.0.0.1', '2026-10-16 16:00:13'),
(188, 2, 'logout', 'Admin logged out', '127.0.0.1', '2026-10-16 16:08:30'),
(189, 2, 'login', 'Admin login successful', '127.0.0.1', '2026-10-16 17:07:38'),
(190, 2, 'update_hall', 'Hall updated: CEAC Hall', '127.0.0.1', '2026-10-16 17:12:31'),
(191, 2, 'logout', 'Admin logged out', '127.0.0.1', '2026-10-16 17:12:36'),
(192, 2, 'login', 'Admin login successful', '127.0.0.1', '2026-10-16 17:14:35'),
(193, 2, 'update_event', 'Event: Meetings', '127.0.0.1', '2026-10-16 17:18:54');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(80) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `role` enum('super_admin','admin','viewer') DEFAULT 'admin',
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `login_attempts` int(11) DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `email`, `password_hash`, `full_name`, `role`, `is_active`, `last_login`, `login_attempts`, `locked_until`, `reset_token`, `reset_token_expires`, `created_at`, `updated_at`) VALUES
(1, 'ceac_admin', 'lispoundspoj06@gmail.com', '$2y$12$lDdFkXd09yz94dD0M5pi0uwDmLi4DZt.UbwhSjWb9vVhcDA2SCOem', 'CEAC Administrator', 'super_admin', 1, '2026-07-11 06:45:37', 0, NULL, NULL, NULL, '2026-06-26 04:22:38', '2026-07-11 13:45:37'),
(2, 'Super_Admin', 'ayimnhyirahamos@gmail.com', '$2y$12$Jzxqn4PkHaIybNxxkdNNQu31lTfKYVYtep8GDHz5QmOxXJfFAp6/G', 'Amos Oware', 'admin', 1, '2026-10-16 17:14:35', 0, NULL, NULL, NULL, '2026-06-27 11:37:14', '2026-10-16 17:14:35');

-- --------------------------------------------------------

--
-- Table structure for table `blocked_ips`
--

CREATE TABLE `blocked_ips` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `blocked_until` datetime DEFAULT NULL COMMENT 'NULL = permanent',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `booking_ref` varchar(20) NOT NULL,
  `hall_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `organization` varchar(150) DEFAULT NULL,
  `event_title` varchar(200) NOT NULL,
  `event_type` enum('cell_meeting','fellowship_meeting','executive_meeting','all_night','special','other') DEFAULT 'other',
  `other_event_type` varchar(100) DEFAULT NULL,
  `booking_duration_type` varchar(10) DEFAULT 'half',
  `event_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `expected_attendees` int(11) NOT NULL,
  `special_requirements` text DEFAULT NULL,
  `needs_projector` tinyint(1) DEFAULT 0,
  `needs_pa_system` tinyint(1) DEFAULT 0,
  `needs_ac` tinyint(1) DEFAULT 1,
  `needs_whiteboard` tinyint(1) DEFAULT 0,
  `status` enum('pending','approved','rejected','cancelled','completed') DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_payments`
--

CREATE TABLE `booking_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `booking_id` int(10) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` varchar(50) NOT NULL,
  `reference_note` varchar(255) DEFAULT NULL,
  `recorded_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `replied` tinyint(1) DEFAULT 0,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `full_name`, `email`, `phone`, `subject`, `message`, `is_read`, `replied`, `ip_address`, `created_at`, `deleted_at`) VALUES
(1, 'Amos Oware', 'ayimnhyirahamos@gmail.com', '0559895145', 'Network Problems', 'Please fix your network', 1, 0, '::1', '2026-06-27 00:43:36', '2026-07-01 01:46:10'),
(9, 'Michael Morgan', 'morgandorfenyo@gmail.com', '0559895145', 'Demonstration', 'This is a demonstration', 1, 0, '154.164.146.11', '2026-07-02 17:08:52', NULL),
(10, 'Ebenezer Ayim', 'ebenezerayim0@gmail.com', '0544507267', 'The Soul', 'What was I supposed to do?', 1, 0, '154.164.146.11', '2026-07-10 16:55:01', NULL),
(12, 'Nhyira Ayim', 'nhyirahayim@gmail.com', '0544507267', 'Demonstration', 'Demonstrate', 1, 0, '154.164.146.11', '2026-07-11 13:19:32', NULL),
(18, 'Ebenezer Ayim', 'ebenezerayim0@gmail.com', '0542926891', 'The Soul', 'Alec I want a divorce', 0, 0, '154.164.146.11', '2026-07-13 04:40:31', NULL),
(19, 'Ebenezer Ayim', 'ebenezerayim0@gmail.com', '0542926891', 'The Soul', 'Doing nothing', 0, 0, '154.160.1.109', '2026-07-13 04:44:26', NULL),
(20, 'Nhyira Ayim', 'nhyirahayim@gmail.com', '0559895145', 'My life', 'Don\'t care', 0, 0, '154.160.1.109', '2026-07-13 04:45:49', NULL),
(21, 'Amos Oware', 'ayimnhyirahamos@gmail.com', '0544507267', 'Demonstration', 'Of course.', 0, 0, '154.160.1.109', '2026-07-13 04:48:06', NULL),
(22, 'Nhyira Ayim', 'nhyirahayim@gmail.com', '0559895145', 'My life', 'Doing nothing anymore', 0, 0, '154.160.1.109', '2026-07-13 04:49:10', NULL),
(23, 'Nhyira Ayim', 'nhyirahayim@gmail.com', '0559895145', 'My life', 'No way will I die today', 0, 0, '154.160.1.109', '2026-07-13 04:51:42', NULL),
(24, 'Amos Oware', 'ayimnhyirahamos@gmail.com', '0544507267', 'Demonstration', 'So why should I care?', 0, 0, '154.160.1.109', '2026-07-13 04:52:45', NULL),
(25, 'Nhyira Ayim', 'nhyirahayim@gmail.com', '0559895145', 'My life', 'No way is my life turning like this', 0, 0, '154.160.1.109', '2026-07-13 04:53:37', NULL),
(26, 'Nhyira Ayim', 'nhyirahayim@gmail.com', '0559895145', 'My life', 'To die or not to die', 0, 0, '154.160.1.109', '2026-07-13 04:59:52', NULL),
(27, 'Nhyira Ayim', 'nhyirahayim@gmail.com', '0559895145', 'My life', 'Next time sleep well', 0, 0, '154.160.1.109', '2026-07-13 05:00:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `hall_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `event_type` enum('cell_meeting','fellowship_meeting','executive_meeting','all_night','special','other') DEFAULT 'other',
  `other_event_type` varchar(100) DEFAULT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `organizer` varchar(150) DEFAULT NULL,
  `max_attendees` int(11) DEFAULT NULL,
  `status` enum('upcoming','ongoing','completed','cancelled') DEFAULT 'upcoming',
  `is_recurring` tinyint(1) DEFAULT 0,
  `recurrence_pattern` varchar(50) DEFAULT NULL COMMENT 'weekly, monthly, etc',
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `hall_id`, `title`, `slug`, `description`, `event_type`, `other_event_type`, `start_datetime`, `end_datetime`, `organizer`, `max_attendees`, `status`, `is_recurring`, `recurrence_pattern`, `image`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 1, 'Meetings', 'executive-meetings', 'Meetings are strategic leadership sessions bringing together the church executives, department heads, and ministry leaders of Christ Embassy Airport City. These meetings are purpose-driven gatherings where vision is clarified, decisions are made, and strategies are deployed to advance the work of the ministry in our zone and beyond.', '', 'Prayer Request', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 40, 'upcoming', 0, NULL, NULL, '2026-06-26 04:22:38', '2026-10-16 17:18:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `halls`
--

CREATE TABLE `halls` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `about_extended` text DEFAULT NULL,
  `seating_info` text DEFAULT NULL,
  `features_json` text DEFAULT NULL,
  `ideal_for_json` text DEFAULT NULL,
  `pricing_json` text DEFAULT NULL,
  `capacity` int(11) NOT NULL DEFAULT 50,
  `seating_style` varchar(100) DEFAULT 'Theatre Style',
  `has_projector` tinyint(1) DEFAULT 1,
  `has_ac` tinyint(1) DEFAULT 1,
  `has_pa_system` tinyint(1) DEFAULT 1,
  `has_wifi` tinyint(1) DEFAULT 1,
  `has_whiteboard` tinyint(1) DEFAULT 1,
  `has_parking` tinyint(1) DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `images_json` text DEFAULT NULL,
  `status` enum('active','inactive','maintenance') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `halls`
--

INSERT INTO `halls` (`id`, `name`, `slug`, `description`, `about_extended`, `seating_info`, `features_json`, `ideal_for_json`, `pricing_json`, `capacity`, `seating_style`, `has_projector`, `has_ac`, `has_pa_system`, `has_wifi`, `has_whiteboard`, `has_parking`, `image`, `images_json`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'CEAC Hall', 'ceac-hall', 'The CEAC Hall is the premier gathering space of Christ Embassy Airport City. Thoughtfully designed to serve the community with excellence, this 50-seat hall in theatre-style arrangement is the perfect venue for cell meetings, fellowship gatherings, executive meetings, all-night services, and other church-related events. Equipped with modern amenities including a large display screen, air conditioning, and a professional sound system, the CEAC Hall ensures every gathering is conducted in a comfortable, dignified, and spiritually enriching atmosphere.', '', '', NULL, NULL, '{\"currency\":\"GHS\",\"penalty_per_hour\":500,\"facility_projector\":200,\"facility_pa_system\":200,\"facility_ac\":0,\"facility_whiteboard\":200,\"meetings_half_hours\":2,\"meetings_half_price\":500,\"meetings_full_hours\":4,\"meetings_full_price\":1000}', 50, 'Theatre Style', 1, 1, 1, 1, 1, 1, 'halls/hall_1783637430_1_78e627.jpg', '[]', 'active', '2026-06-26 04:22:38', '2026-10-16 17:12:31', NULL);

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
(1, '2026_10_15_000001_create_admin_users_table', 1),
(2, '2026_10_15_000002_create_halls_table', 1),
(3, '2026_10_15_000003_create_bookings_table', 1),
(4, '2026_10_15_000004_create_booking_payments_table', 2),
(5, '2026_10_15_000005_create_events_table', 2),
(6, '2026_10_15_000006_create_contact_messages_table', 2),
(7, '2026_10_15_000007_create_sent_messages_table', 2),
(8, '2026_10_15_000008_create_admin_logs_table', 2),
(9, '2026_10_15_000009_create_rate_limits_table', 2),
(10, '2026_10_15_000010_create_blocked_ips_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `rate_limits`
--

CREATE TABLE `rate_limits` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `action` varchar(80) NOT NULL,
  `attempts` int(11) DEFAULT 1,
  `window_start` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rate_limits`
--

INSERT INTO `rate_limits` (`id`, `ip_address`, `action`, `attempts`, `window_start`) VALUES
(54, '154.161.129.232', 'booking', 1, '2026-07-14 11:49:11'),
(55, '154.161.129.232', 'contact', 1, '2026-07-14 11:52:44');

-- --------------------------------------------------------

--
-- Table structure for table `sent_messages`
--

CREATE TABLE `sent_messages` (
  `id` int(11) NOT NULL,
  `sent_to_email` varchar(255) NOT NULL,
  `sent_to_name` varchar(150) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `sent_by` int(11) DEFAULT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sent_messages`
--

INSERT INTO `sent_messages` (`id`, `sent_to_email`, `sent_to_name`, `subject`, `body`, `sent_by`, `sent_at`, `deleted_at`) VALUES
(1, 'ebenezerayim0@gmail.com', 'Ebenezer Ayim', 'Re: The Soul', 'Dear Ebenezer Ayim,\r\n\r\n\r\n\r\n— From Admin, CEAC Hall Booking Team —\r\nThis is not my role\r\n---\r\nOriginal message from Ebenezer Ayim:\r\nWhy is it always me?', 2, '2026-07-12 20:42:19', NULL),
(2, 'ebenezerayim0@gmail.com', 'Ebenezer Ayim', 'Re: The Soul', 'Dear Ebenezer Ayim,\r\n\r\n\r\n\r\n— From Admin, CEAC Hall Booking Team —\r\nThis is not my role\r\n---\r\nOriginal message from Ebenezer Ayim:\r\nWhy is it always me?', 2, '2026-07-12 20:42:21', NULL),
(3, 'ebenezerayim0@gmail.com', 'Ebenezer Ayim', 'Re: The Soul', 'Dear Ebenezer Ayim,\r\n\r\n\r\n\r\n— From Admin, CEAC Hall Booking Team —\r\nThis is not my role\r\n---\r\nOriginal message from Ebenezer Ayim:\r\nWhy is it always me?', 2, '2026-07-12 20:42:22', NULL),
(4, 'nhyirahayim@gmail.com', 'Nhyira Ayim', 'Re: Demonstration', 'Dear Nhyira Ayim,\r\n\r\n\r\n\r\n— From Admin, CEAC Hall Booking Team —\r\nNone of your business\r\n---\r\nOriginal message from Nhyira Ayim:\r\nWhat is today\'s date?', 2, '2026-07-12 20:50:20', NULL),
(5, 'morgandorfenyo@gmail.com', 'Michael Morgan', 'Re: Your Hall Booking – CEAC-E3FF-6079', 'Dear Michael Morgan,\r\n\r\nRegarding your booking reference: CEAC-E3FF-6079\r\nEvent: Holy Ghost Convention\r\n\r\nGood to go\r\n\r\n— From Admin, CEAC Hall Booking Team —', 2, '2026-07-13 07:35:02', NULL),
(6, 'nhyirahayim@gmail.com', 'Nhyira Ayim', 'Re: Your Hall Booking – CEAC-3AAE-5578', 'Dear Nhyira Ayim,\r\n\r\nRegarding your booking reference: CEAC-3AAE-5578\r\nEvent: Holy Ghost Convention\r\n\r\n\r\n\r\n— From Admin, CEAC Hall Booking Team —\r\nGood to go', 2, '2026-07-13 07:35:28', NULL),
(7, 'ayimnhyirahamos@gmail.com', 'Amos Oware', 'Re: Your Hall Booking – CEAC-DFD2-9925', 'Dear Amos Oware,\r\n\r\nRegarding your booking reference: CEAC-DFD2-9925\r\nEvent: The Everlasting Seed\r\n\r\n\r\n\r\n— From Admin, CEAC Hall Booking Team —\r\n\r\nGood to go', 2, '2026-07-13 07:36:15', NULL),
(8, 'nhyirahayim@gmail.com', 'Nhyirah', 'Re: Your Hall Booking – CEAC-262B-2086', 'Dear ,\r\n\r\nRegarding your booking reference: \r\nEvent: \r\n\r\n\r\n\r\n— From Admin, CEAC Hall Booking Team —\r\n\r\nNo problem', 2, '2026-07-14 11:51:08', NULL),
(9, 'nhyirahayim@gmail.com', 'Nhyirah', 'Re: Your Hall Booking – CEAC-262B-2086', 'Dear ,\r\n\r\nRegarding your booking reference: \r\nEvent: \r\n\r\n\r\n\r\n— From Admin, CEAC Hall Booking Team —\r\nNo one cares', 2, '2026-07-14 16:35:54', NULL),
(10, 'nhyirahayim@gmail.com', 'Nhyirah', 'Re: Your Hall Booking – CEAC-262B-2086', 'Dear ,\r\n\r\nRegarding your booking reference: \r\nEvent: \r\n\r\n\r\n\r\n— From Admin, CEAC Hall Booking Team —\r\nMy problem', 2, '2026-07-14 17:36:32', NULL),
(11, 'nhyirahayim@gmail.com', 'Nhyirah', 'Re: Blood', 'Dear Nhyirah,\r\n\r\n\r\n\r\n— From Admin, CEAC Hall Booking Team —\r\nNot my problem\r\n---\r\nOriginal message from Nhyirah:\r\nI need blood', 2, '2026-07-14 17:38:39', NULL),
(12, 'nhyirahayim@gmail.com', 'Nhyirah', 'Re: Blood', 'Dear Nhyirah,\r\n\r\n\r\n\r\n— From Admin, CEAC Hall Booking Team —\r\nTry and catch me\r\n---\r\nOriginal message from Nhyirah:\r\nI need blood', 2, '2026-07-14 17:47:53', NULL),
(13, 'ebenezerayim0@gmail.com', 'Ebenezer Ayim', 'Re: The Soul', 'Dear Ebenezer Ayim,\r\n\r\n\r\n\r\n— From Admin, CEAC Hall Booking Team —\r\nMy life matters to me\r\n---\r\nOriginal message from Ebenezer Ayim:\r\nDo whatever you want', 2, '2026-07-14 18:10:06', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `blocked_ips`
--
ALTER TABLE `blocked_ips`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ip_address` (`ip_address`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_ref` (`booking_ref`),
  ADD KEY `hall_id` (`hall_id`),
  ADD KEY `idx_booking_ref` (`booking_ref`),
  ADD KEY `idx_event_date` (`event_date`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `booking_payments`
--
ALTER TABLE `booking_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hall_id` (`hall_id`),
  ADD KEY `idx_start_datetime` (`start_datetime`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_event_type` (`event_type`);

--
-- Indexes for table `halls`
--
ALTER TABLE `halls`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rate_limits`
--
ALTER TABLE `rate_limits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ip_action` (`ip_address`,`action`);

--
-- Indexes for table `sent_messages`
--
ALTER TABLE `sent_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sent_by` (`sent_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blocked_ips`
--
ALTER TABLE `blocked_ips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `booking_payments`
--
ALTER TABLE `booking_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `halls`
--
ALTER TABLE `halls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `rate_limits`
--
ALTER TABLE `rate_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `sent_messages`
--
ALTER TABLE `sent_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`hall_id`) REFERENCES `halls` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`hall_id`) REFERENCES `halls` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
