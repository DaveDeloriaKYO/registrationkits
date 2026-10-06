-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2025 at 12:21 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nid`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `barangay_id` int(11) NOT NULL,
  `barangay_name` varchar(150) NOT NULL,
  `municipality_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangays`
--

INSERT INTO `barangays` (`barangay_id`, `barangay_name`, `municipality_id`) VALUES
(1, 'Barangay 2', 83739),
(2, 'Nula-tula', 83739),
(3, 'Libertad', 83739),
(4, 'Barangay 5', 83739),
(5, 'Barangay 6', 83739),
(6, 'Barangay 6-A', 83739),
(7, 'Barangay 7', 83739),
(8, 'Barangay 8', 83739),
(9, 'Barangay 100 (San Roque)', 83739),
(10, 'Barangay 101 (New Kawayan)', 83739),
(11, 'Barangay 102 (Old Kawayan)', 83739),
(12, 'Barangay 103 (Palanog)', 83739),
(13, 'Barangay 103-A (San Paglaum)', 83739),
(14, 'Barangay 104 (Salvacion)', 83739),
(15, 'Barangay 105 (Suhi)', 83739),
(16, 'Barangay 106 (Santo Niño)', 83739),
(17, 'Barangay 107 (Santa Elena)', 83739),
(18, 'Barangay 108 (Tagapuro)', 83739),
(19, 'Barangay 12 (Palanog Resettlement)', 83739),
(20, 'Barangay 13', 83739),
(21, 'Barangay 14', 83739),
(22, 'Barangay 15', 83739),
(23, 'Barangay 16', 83739),
(24, 'Barangay 17', 83739),
(25, 'Barangay 18', 83739),
(26, 'Barangay 19', 83739),
(27, 'Barangay 20', 83739),
(28, 'Barangay 21', 83739),
(29, 'Barangay 21-A', 83739),
(30, 'Barangay 22', 83739),
(31, 'Barangay 23', 83739),
(32, 'Barangay 23-A', 83739),
(33, 'Barangay 24', 83739),
(34, 'Barangay 25', 83739),
(35, 'Barangay 26', 83739),
(36, 'Barangay 27', 83739),
(37, 'Barangay 28', 83739),
(38, 'Barangay 29', 83739),
(39, 'Barangay 30', 83739),
(40, 'Barangay 31', 83739),
(41, 'Barangay 32', 83739),
(42, 'Barangay 33', 83739),
(43, 'Barangay 34 (Real)', 83739),
(44, 'Barangay 35', 83739),
(45, 'Barangay 35-A', 83739),
(46, 'Barangay 36', 83739),
(47, 'Barangay 36-A (Sabang)', 83739),
(48, 'Barangay 37', 83739),
(49, 'Barangay 37-A', 83739),
(50, 'Barangay 38 (Calvary Hill)', 83739),
(51, 'Barangay 39 (Calvary Hill)', 83739),
(52, 'Barangay 40 (Calvary Hill)', 83739),
(53, 'Barangay 41 (Calvary Hill)', 83739),
(54, 'Barangay 42', 83739),
(55, 'Barangay 42-A', 83739),
(56, 'Barangay 42-B', 83739),
(57, 'Barangay 43', 83739),
(58, 'Barangay 43-A', 83739),
(59, 'Barangay 43-B', 83739),
(60, 'Barangay 44', 83739),
(61, 'Barangay 44-A', 83739),
(62, 'Barangay 44-B', 83739),
(63, 'Barangay 45', 83739),
(64, 'Barangay 46 (Imelda/Juan Luna)', 83739),
(65, 'Barangay 47', 83739),
(66, 'Barangay 48', 83739),
(67, 'Barangay 48-A', 83739),
(68, 'Barangay 48-B', 83739),
(69, 'Barangay 49 (Youngfield)', 83739),
(70, 'Barangay 50 (Youngfield)', 83739),
(71, 'Barangay 50-A (Youngfield)', 83739),
(72, 'Barangay 50-B (Youngfield)', 83739),
(73, 'Barangay 51', 83739),
(74, 'Barangay 51-A', 83739),
(75, 'Barangay 52 (Lucban/Magallanes)', 83739),
(76, 'Barangay 53 (Magallanes)', 83739),
(77, 'Barangay 54 (Magallanes)', 83739),
(78, 'Barangay 54-A (Magallanes)', 83739),
(79, 'Barangay 55 (El Reposo)', 83739),
(80, 'Barangay 56 (El Reposo)', 83739),
(81, 'Barangay 56-A (El Reposo)', 83739),
(82, 'Barangay 57 (Whitelane/Sampaguita)', 83739),
(83, 'Barangay 58', 83739),
(84, 'Barangay 59 (Sagkahan Picas)', 83739),
(85, 'Barangay 59-A (Sampaguita)', 83739),
(86, 'Barangay 59-B (Sampaguita)', 83739),
(87, 'Barangay 60 (Sagkahan Aslum)', 83739),
(88, 'Barangay 60-A (Sagkahan)', 83739),
(89, 'Barangay 61 (Sagkahan)', 83739),
(90, 'Barangay 62 (Sagkahan Saging)', 83739),
(91, 'Barangay 62-A (Sagkahan Ilong)', 83739),
(92, 'Barangay 62-B (Sagkahan Picas)', 83739),
(93, 'Barangay 63 (Sagkahan Mangga)', 83739),
(94, 'Barangay 64 (Sagkahan Bliss)', 83739),
(95, 'Barangay 65 (Paseo de Legaspi)', 83739),
(96, 'Barangay 66 (Anibong)', 83739),
(97, 'Barangay 66-A (Anibong)', 83739),
(98, 'Barangay 67 (Anibong)', 83739),
(99, 'Barangay 68 (Anibong)', 83739),
(100, 'Barangay 69 (Happy Land Anibong)', 83739),
(101, 'Barangay 70 (Rawis Anibong)', 83739),
(102, 'Barangay 71 (Naga-naga)', 83739),
(103, 'Barangay 72 (PHHC Seaside)', 83739),
(104, 'Barangay 73 (PHHC Mountainside)', 83739),
(105, 'Barangay 74 (Lower Nula-Tula)', 83739),
(106, 'Barangay 75 (Fatima Village)', 83739),
(107, 'Barangay 76 (Fatima Village)', 83739),
(108, 'Barangay 77 (Fatima Village)', 83739),
(109, 'Barangay 78 (Marasbaras)', 83739),
(110, 'Barangay 79 (Marasbaras)', 83739),
(111, 'Barangay 80 (Marasbaras)', 83739),
(112, 'Barangay 81 (Marasbaras)', 83739),
(113, 'Barangay 82 (Marasbaras)', 83739),
(114, 'Barangay 83 (Paraiso)', 83739),
(115, 'Barangay 83-A (Burayan)', 83739),
(116, 'Barangay 83-B (San Jose/Cogon)', 83739),
(117, 'Barangay 83-C (San Jose)', 83739),
(118, 'Barangay 84 (San Jose)', 83739),
(119, 'Barangay 85 (San Jose)', 83739),
(120, 'Barangay 86 (San Jose)', 83739),
(121, 'Barangay 87 (San Jose)', 83739),
(122, 'Barangay 88 (San Jose)', 83739),
(123, 'Barangay 89 (San Jose/Baybay)', 83739),
(124, 'Barangay 90 (San Jose)', 83739),
(125, 'Barangay 91 (Abucay)', 83739),
(126, 'Barangay 92 (Apitong)', 83739),
(127, 'Barangay 93 (Bagacay)', 83739),
(128, 'Barangay 94 (Tigbao)', 83739),
(129, 'Barangay 94-A (Basper)', 83739),
(130, 'Barangay 95 (Caibaan)', 83739),
(131, 'Barangay 95-A (Caibaan)', 83739),
(132, 'Barangay 96 (Calanipawan)', 83739),
(133, 'Barangay 97 (Cabalawan)', 83739),
(134, 'Barangay 98 (Camansinay)', 83739),
(135, 'Barangay 99 (Diit)', 83739),
(136, 'Barangay 109 (V&G Subdivision)', 83739),
(137, 'Barangay 109-A (V&G Subdivision)', 83739),
(138, 'Barangay 110 (Utap)', 83739),
(139, 'Alangilan', 83701),
(140, 'Anibongon', 83701),
(141, 'Buaya', 83701),
(142, 'Bagacay', 83701),
(143, 'Bahay', 83701),
(144, 'Balinsasayao', 83701),
(145, 'Balocawe', 83701),
(146, 'Balocawehay', 83701),
(147, 'Barayong', 83701),
(148, 'Bayabas', 83701),
(149, 'Bito (Poblacion)', 83701),
(150, 'Buenavista', 83701),
(151, 'Bulak', 83701),
(152, 'Buntay (Poblacion)', 83701),
(153, 'Bunga', 83701),
(154, 'Burubud-an', 83701),
(155, 'Cagbolo', 83701),
(156, 'Can-aporong', 83701),
(157, 'Canmarating', 83701),
(158, 'Can-uguib (Poblacion)', 83701),
(159, 'Capilian', 83701),
(160, 'Cadac-an', 83701),
(161, 'Combis', 83701),
(162, 'Dingle', 83701),
(163, 'Guintagbucan (Poblacion)', 83701),
(164, 'Hampipila', 83701),
(165, 'Katipunan', 83701),
(166, 'Kikilo', 83701),
(167, 'Laray', 83701),
(168, 'Lawa-an', 83701),
(169, 'Libertad', 83701),
(170, 'Loyonsawang (Poblacion)', 83701),
(171, 'Mahagna (New Cagbolo)', 83701),
(172, 'Mag-atubang', 83701),
(173, 'Mahayahay', 83701),
(174, 'Maitum', 83701),
(175, 'Malaguicay', 83701),
(176, 'Matagnao', 83701),
(177, 'Nalibunan (Poblacion)', 83701),
(178, 'Nebga', 83701),
(179, 'Odiongan', 83701),
(180, 'Pagsang-an', 83701),
(181, 'Paguite', 83701),
(182, 'Parasanon', 83701),
(183, 'Picas Sur', 83701),
(184, 'Pilar', 83701),
(185, 'Pinamanagan', 83701),
(186, 'Salvacion', 83701),
(187, 'San Francisco', 83701),
(188, 'San Isidro', 83701),
(189, 'San Roque', 83701),
(190, 'Santa Fe (Poblacion)', 83701),
(191, 'Santa Lucia (Poblacion)', 83701),
(192, 'Santo Niño (Poblacion)', 83701),
(193, 'Tabigue', 83701),
(194, 'Tadoc', 83701),
(195, 'Tib-o', 83701),
(196, 'Tinalian', 83701),
(197, 'Tinocolan', 83701),
(198, 'Tuy-a', 83701),
(199, 'Victory (Poblacion)', 83701),
(200, 'Antipolo', 83703),
(201, 'Balugo', 83703),
(202, 'Benolho', 83703),
(203, 'Cambalading', 83703),
(204, 'Damula-an', 83703),
(205, 'Doña Maria (Kangkuirina)', 83703),
(206, 'Mahayag', 83703),
(207, 'Mahayahay', 83703),
(208, 'Poblacion', 83703),
(209, 'Salvacion', 83703),
(210, 'San Pedro', 83703),
(211, 'Seguinon', 83703),
(212, 'Sherwood', 83703),
(213, 'Tabgas', 83703),
(214, 'Talisayan', 83703),
(215, 'Tinag-an', 83703),
(216, 'Aslum', 83702),
(217, 'Astorga (Burabod)', 83702),
(218, 'Bato', 83702),
(219, 'Binongto-an', 83702),
(220, 'Binotong', 83702),
(221, 'Blumentritt (Poblacion)', 83702),
(222, 'Bobonon', 83702),
(223, 'Borseth', 83702),
(224, 'Buenavista', 83702),
(225, 'Bugho', 83702),
(226, 'Buri', 83702),
(227, 'Cabadsan', 83702),
(228, 'Calaasan', 83702),
(229, 'Cambahanon', 83702),
(230, 'Cambolao', 83702),
(231, 'Canvertudes', 83702),
(232, 'Capiz', 83702),
(233, 'Cavite', 83702),
(234, 'Cogon', 83702),
(235, 'Dapdap', 83702),
(236, 'Divisoria', 83702),
(237, 'Ekiran', 83702),
(238, 'Hinapolon (Baras)', 83702),
(239, 'Holy Child I (Poblacion)', 83702),
(240, 'Holy Child II (Poblacion)', 83702),
(241, 'Hubang', 83702),
(242, 'Hupit', 83702),
(243, 'Langit', 83702),
(244, 'Lingayon', 83702),
(245, 'Lourdes', 83702),
(246, 'Lukay', 83702),
(247, 'Magsaysay', 83702),
(248, 'Milagrosa (Poblacion)', 83702),
(249, 'Mudboron', 83702),
(250, 'P. Barrantes', 83702),
(251, 'Peñalosa', 83702),
(252, 'Pepita', 83702),
(253, 'Salvacion (Poblacion)', 83702),
(254, 'San Antonio', 83702),
(255, 'San Antonio (Poblacion)', 83702),
(256, 'San Diego', 83702),
(257, 'San Francisco East', 83702),
(258, 'San Francisco West', 83702),
(259, 'San Isidro', 83702),
(260, 'San Pedro', 83702),
(261, 'San Roque (Poblacion)', 83702),
(262, 'San Vicente', 83702),
(263, 'Santiago', 83702),
(264, 'Santo Niño (Poblacion)', 83702),
(265, 'Santol', 83702),
(266, 'Tabangohay', 83702),
(267, 'Tombo', 83702),
(268, 'Veteranos', 83702),
(269, 'Biasong', 83704),
(270, 'Gov. E. Jaro (Bagahupi)', 83704),
(271, 'Malibago', 83704),
(272, 'Magcasuang', 83704),
(273, 'Planza', 83704),
(274, 'Poblacion District I', 83704),
(275, 'Poblacion District II', 83704),
(276, 'Poblacion District III', 83704),
(277, 'Poblacion District IV', 83704),
(278, 'Rizal I', 83704),
(279, 'San Ricardo', 83704),
(280, 'Sangputan', 83704),
(281, 'Pagsulhugon', 83704),
(282, 'Taguite', 83704),
(283, 'Uban', 83704),
(284, 'Victory', 83704),
(285, 'Villa Magsaysay', 83704),
(286, 'Bacong', 83704),
(287, 'Bagong Silang', 83704),
(288, 'Lukay', 83704),
(289, 'Rizal II', 83704),
(290, 'San Agustin', 83704),
(291, 'Guintigui-an', 83704),
(292, 'Naga-asan', 83704),
(293, 'San Isidro', 83704),
(294, 'Anahaway', 83731),
(295, 'Arado', 83731),
(296, 'Baras', 83731),
(297, 'Barayong', 83731),
(298, 'Cabarasan Daku', 83731),
(299, 'Cabarasan Guti', 83731),
(300, 'Campetic', 83731),
(301, 'Candahug', 83731),
(302, 'Cangumbang', 83731),
(303, 'Canhidoc', 83731),
(304, 'Capirawan', 83731),
(305, 'Castilla', 83731),
(306, 'Cogon', 83731),
(307, 'Gacao', 83731),
(308, 'Guindapunan', 83731),
(309, 'Libertad', 83731),
(310, 'Naga-naga', 83731),
(311, 'Pawing', 83731),
(312, 'Buri (Poblacion)', 83731),
(313, 'Cavite East (Poblacion)', 83731),
(314, 'Cavite West (Poblacion)', 83731),
(315, 'Luntad (Poblacion)', 83731),
(316, 'Santa Cruz (Poblacion)', 83731),
(317, 'Salvacion', 83731),
(318, 'San Agustin', 83731),
(319, 'San Antonio', 83731),
(320, 'San Isidro', 83731),
(321, 'San Jose', 83731),
(322, 'St. Michael (Poblacion)', 83731),
(323, 'Tacuranga', 83731),
(324, 'Teraza', 83731),
(325, 'San Fernando', 83731);

-- --------------------------------------------------------

--
-- Table structure for table `borrower`
--

CREATE TABLE `borrower` (
  `id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `property_no` varchar(1000) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `borrow_datetime` datetime NOT NULL,
  `duration_days` int(11) NOT NULL,
  `borrowed_by` varchar(255) NOT NULL,
  `borrowed_by_designation` varchar(100) NOT NULL,
  `noted_by` varchar(255) NOT NULL,
  `noted_by_designation` varchar(100) NOT NULL,
  `approved_by` varchar(255) NOT NULL,
  `approved_by_designation` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Borrowed','Returned') NOT NULL DEFAULT 'Borrowed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrower`
--

INSERT INTO `borrower` (`id`, `description`, `property_no`, `purpose`, `borrow_datetime`, `duration_days`, `borrowed_by`, `borrowed_by_designation`, `noted_by`, `noted_by_designation`, `approved_by`, `approved_by_designation`, `created_at`, `status`) VALUES
(2, 'NID LAPTOP WITH CHARGER', 'SN#5CD93670JL PRO LPT 91641', 'CBMS-DATA TURNOVER', '2025-07-21 16:24:00', 10, 'ATILANO P AVESTRUS JR.', 'PSO CLERK', 'DAVID F. DELORIA', 'ISA I', 'MYRNA TRINIDAD T CATALUÑA', 'SENIOR STATISTICAL SPECIALIST I', '2025-08-04 07:42:16', 'Returned'),
(3, 'NID LAPTOP WITH CHARGER', 'SN# 5CD9352XLN (REGKIT 133)', '3RD LEVEL TRAINING ON 2025 JULY LABOR FORCE SURVEY & 2025 FIES', '2025-06-26 13:13:00', 7, 'MA. ZOLITA N PANALIGAN', 'SSII', 'DAVID F. DELORIA', 'ISA I', 'MYRNA TRINIDAD T CATALUÑA', 'SENIOR STATISTICAL SPECIALIST I', '2025-08-04 07:46:09', 'Borrowed'),
(4, 'NID LAPTOP WITH CHARGER', 'SN#5CD93670B5 (REGKIT 114), SN#5CD93670NL (REGKIT131), SN#5CD93670JL (REGKIT 126', 'CBMS', '2025-06-26 16:24:00', 5, 'CRISTINA C. PAPEL', 'PSO CLERK', 'DAVID F. DELORIA', 'ISA I', 'MYRNA TRINIDAD T CATALUÑA', 'SENIOR STATISTICAL SPECIALIST I', '2025-08-04 07:49:39', 'Returned'),
(5, 'NID LAPTOP WITH CHARGER', 'SN#5CD93660WM(REGKIT 115)', 'FOR NID REGISTRATION ASSISTANCE - RSS08', '2025-06-26 09:19:00', 10, 'MARIFE R. MOLON', 'ROIII', 'DAVID F. DELORIA', 'ISA I', 'MYRNA TRINIDAD T CATALUÑA', 'SENIOR STATISTICAL SPECIALIST I', '2025-08-04 07:54:27', 'Borrowed'),
(10, 'NID LAPTOP WITH CHARGER', 'SN#5CD93670T5 (REGKIT 127-PRO-LPT-91643 )', 'TRAINING ON THE BIRTH REGISTRATION ASSISTANCE MANAGEMENT SYSTEM (BRAMS)', '2025-09-02 14:44:00', 3, 'REGINA N. DABLO', 'ABRC', 'DAVID F. DELORIA', 'ISA I', 'MYRNA TRINIDAD T CATALUNA', 'SENIOR STATISTICAL SPECIALIST I - OIC', '2025-09-02 06:52:46', 'Borrowed'),
(11, 'NID LAPTOP WITH CHARGER', 'SN#:5CD936616Z (REGKIT222 - PRO-LPT-82250)', 'TRAINING ON THE BIRTH REGISTRATION ASSISTANCE MANAGEMENT SYSTEM (BRAMS)', '2025-09-03 14:58:00', 3, 'BEVERLY R MACAYAN', 'ABRC', 'DAVID F DELORIA', 'ISA', 'MYRNA TRINIDAD T CATALUÑA', 'SENIOR STATISTICAL SPECIALIST  - OIC', '2025-09-02 06:59:00', 'Borrowed'),
(12, 'NID LAPTOP WITH CHARGER', 'SN# 5CD93671MK (REGKIT 41- 91579)', 'FOR NID REGISTRATION ASSISTANCE - RSS08', '2025-09-02 15:02:00', 3, 'ANDRES A MIRANDA', 'BRC', 'DAVID F. DELORIA', 'ISA I', 'MYRNA TRINIDAD T CATALUÑA', 'SENIOR STATISTICAL SPECIALIST  - OIC', '2025-09-02 07:03:59', 'Borrowed'),
(13, 'NID LAPTOP WITH CHARGER', 'PRO-LPT-91582, REG KIT: 230, SN:5CD9366ZN6', 'OFFICE WORK RELATED AT CSS OFFICE', '2025-01-01 08:00:00', 180, 'NAIRA', 'ADA III', 'DAVID F. DELORIA', 'ISA I', 'SHERYL ANN A JAMISOLA', 'CSS', '2025-09-29 02:14:04', 'Borrowed');

-- --------------------------------------------------------

--
-- Table structure for table `excel`
--

CREATE TABLE `excel` (
  `id` int(11) NOT NULL,
  `excel_display` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `excel`
--

INSERT INTO `excel` (`id`, `excel_display`) VALUES
(3, 'Sample_PersonnelNID.xlsx');

-- --------------------------------------------------------

--
-- Table structure for table `ismd_validated_regkits`
--

CREATE TABLE `ismd_validated_regkits` (
  `id` int(11) NOT NULL,
  `computer_name` varchar(255) NOT NULL,
  `machine_id` int(11) NOT NULL,
  `reg_code` varchar(100) NOT NULL,
  `regkit_serial_num` varchar(100) NOT NULL,
  `serial_number` varchar(100) NOT NULL,
  `validated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ismd_validated_regkits`
--

INSERT INTO `ismd_validated_regkits` (`id`, `computer_name`, `machine_id`, `reg_code`, `regkit_serial_num`, `serial_number`, `validated_at`) VALUES
(2, 'PRO-LPT-93015', 0, '63', 'FORVERIFICATION', '5CD93555D4', '2025-04-03 06:52:38'),
(3, 'PRO-LPT-82216', 23994, '178', 'FORVERIFICATION', '5CD9352WZP', '2025-04-03 06:53:51'),
(4, 'PRO-LPT-90039', 0, '73', 'FORVERIFICATION', '5CD93661S9', '2025-04-03 06:54:26'),
(5, 'PRO-LPT-91685', 0, '27', 'FORVERIFICATION', '5CD9366ZV9', '2025-04-03 06:55:00'),
(6, 'PR0-LPT-91679', 0, '34', 'FORVERIFICATION', '5CD93671M', '2025-04-03 06:56:50'),
(7, 'PRO-LPT-91799', 0, '35', 'FORVERIFICATION', '5CD93670SW', '2025-04-03 06:58:37'),
(8, 'PRO-LPT-91761', 21071, '49', 'FORVERIFICATION', '5CD9366Z94', '2025-04-03 06:59:33');

-- --------------------------------------------------------

--
-- Table structure for table `municipalities`
--

CREATE TABLE `municipalities` (
  `municipality_id` int(11) NOT NULL,
  `municipality_name` varchar(150) NOT NULL,
  `province_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `municipalities`
--

INSERT INTO `municipalities` (`municipality_id`, `municipality_name`, `province_id`) VALUES
(83701, 'Abuyog', 837),
(83702, 'Alangalang', 837),
(83703, 'Albuera', 837),
(83704, 'Babatngon', 837),
(83705, 'Barugo', 837),
(83706, 'Bato', 837),
(83707, 'Baybay City', 837),
(83708, 'Burauen', 837),
(83709, 'Calubian', 837),
(83710, 'Capoocan', 837),
(83711, 'Carigara', 837),
(83712, 'Dagami', 837),
(83713, 'Dulag', 837),
(83714, 'Hilongos', 837),
(83715, 'Hindang', 837),
(83716, 'Inopacan', 837),
(83717, 'Isabel', 837),
(83718, 'Jaro', 837),
(83719, 'Javier', 837),
(83720, 'Julita', 837),
(83721, 'Kananga', 837),
(83722, 'La Paz', 837),
(83723, 'Leyte', 837),
(83724, 'MacArthur', 837),
(83725, 'Mahaplag', 837),
(83726, 'Matag-ob', 837),
(83727, 'Matalom', 837),
(83728, 'Mayorga', 837),
(83729, 'Merida', 837),
(83730, 'Ormoc City', 837),
(83731, 'Palo', 837),
(83732, 'Palompon', 837),
(83733, 'Pastrana', 837),
(83734, 'San Isidro', 837),
(83735, 'San Miguel', 837),
(83736, 'Santa Fe', 837),
(83737, 'Tabango', 837),
(83738, 'Tabontabon', 837),
(83739, 'Tacloban City', 837),
(83740, 'Tanauan', 837),
(83741, 'Tolosa', 837),
(83742, 'Tunga', 837),
(83743, 'Villaba', 837);

-- --------------------------------------------------------

--
-- Table structure for table `peripherals`
--

CREATE TABLE `peripherals` (
  `id` int(11) NOT NULL,
  `regno` varchar(50) NOT NULL,
  `year` varchar(4) DEFAULT NULL COMMENT 'Year of entry or equipment purchase year',
  `creation_time` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Timestamp of record creation',
  `fp_scanner` tinyint(1) NOT NULL DEFAULT 0,
  `fp_scanner_status` enum('serviceable','unserviceable','unidentified','for verification','for retrieval','transferred') NOT NULL DEFAULT 'unidentified',
  `fp_scanner_serial` varchar(255) DEFAULT NULL,
  `iris_scanner` tinyint(1) NOT NULL DEFAULT 0,
  `iris_scanner_status` enum('serviceable','unserviceable','unidentified','for verification','for retrieval','transferred') NOT NULL DEFAULT 'unidentified',
  `iris_scanner_serial` varchar(255) DEFAULT NULL,
  `webcam` tinyint(1) NOT NULL DEFAULT 0,
  `webcam_status` enum('serviceable','unserviceable','unidentified','for verification','for retrieval','transferred') NOT NULL DEFAULT 'unidentified',
  `webcam_serial` varchar(255) DEFAULT NULL,
  `document_scanner` tinyint(1) NOT NULL DEFAULT 0,
  `document_scanner_status` enum('serviceable','unserviceable','unidentified','for verification','for retrieval','transferred') NOT NULL DEFAULT 'unidentified',
  `document_scanner_serial` varchar(255) DEFAULT NULL,
  `extended_monitor` tinyint(1) NOT NULL DEFAULT 0,
  `extended_monitor_status` enum('serviceable','unserviceable','unidentified','for verification','for retrieval','transferred') NOT NULL DEFAULT 'unidentified',
  `extended_monitor_serial` varchar(255) DEFAULT NULL,
  `printer` tinyint(1) NOT NULL DEFAULT 0,
  `printer_status` enum('serviceable','unserviceable','unidentified','for verification','for retrieval','transferred') NOT NULL DEFAULT 'unidentified',
  `printer_serial` varchar(255) DEFAULT NULL,
  `battery_pack` tinyint(1) NOT NULL DEFAULT 0,
  `battery_pack_status` enum('serviceable','unserviceable','unidentified','for verification','for retrieval','transferred') NOT NULL DEFAULT 'unidentified',
  `battery_pack_serial` varchar(255) DEFAULT NULL,
  `photobooth_kit` tinyint(1) NOT NULL DEFAULT 0,
  `photobooth_kit_status` enum('serviceable','unserviceable','unidentified','for verification','for retrieval','transferred') NOT NULL DEFAULT 'unidentified',
  `photobooth_kit_serial` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peripherals`
--

INSERT INTO `peripherals` (`id`, `regno`, `year`, `creation_time`, `fp_scanner`, `fp_scanner_status`, `fp_scanner_serial`, `iris_scanner`, `iris_scanner_status`, `iris_scanner_serial`, `webcam`, `webcam_status`, `webcam_serial`, `document_scanner`, `document_scanner_status`, `document_scanner_serial`, `extended_monitor`, `extended_monitor_status`, `extended_monitor_serial`, `printer`, `printer_status`, `printer_serial`, `battery_pack`, `battery_pack_status`, `battery_pack_serial`, `photobooth_kit`, `photobooth_kit_status`, `photobooth_kit_serial`) VALUES
(19, '3', NULL, '2025-10-09 01:09:32', 1, 'serviceable', 'C8RKDT07843', 1, 'serviceable', 'DF0052000012882A', 1, 'for verification', '1934LZ519X28', 1, 'serviceable', 'DOC19010048A', 1, 'for verification', 'BSS-1910-001-01-3927', 1, 'for verification', 'X38FA45178', 1, 'for verification', 'POF0001707', 0, 'unidentified', NULL),
(21, '4', NULL, '2025-10-08 10:51:07', 1, 'serviceable', 'C8RKDT10270', 1, 'serviceable', 'DF0052000014006A', 1, 'unserviceable', '1930LZ50F178', 1, 'for verification', 'DOC19007253A', 1, 'for verification', 'BSS-1910-001-01-5718', 1, 'for verification', 'X38FA44826', 1, 'for verification', 'POF0001995', 0, 'unidentified', NULL),
(23, '5', NULL, '2025-10-08 10:50:49', 1, 'serviceable', 'C8RKDT13199', 1, 'serviceable', 'DF0052000011674A', 1, 'for verification', '1933LZ517908', 1, 'for verification', 'DOC19004065A', 1, 'for verification', 'BSS-1910-001-01-4366', 1, 'for verification', 'X38FA43802', 1, 'for verification', 'POF0002109', 0, 'unidentified', NULL),
(24, '7', NULL, '2025-10-08 10:53:03', 1, 'serviceable', 'C8RKDT13027', 1, 'serviceable', 'DF0052000013487A', 1, 'serviceable', '1934LZ52PAN8', 1, 'serviceable', 'DOC19007006A', 1, 'serviceable', 'BSS-1910-001-01-5073', 1, 'serviceable', 'X38FA44147', 1, 'serviceable', 'POF0001893', 0, 'unidentified', NULL),
(25, '8', NULL, '2025-10-08 10:54:26', 1, 'for verification', 'C8RKDT11366', 1, 'serviceable', 'DF0052000010260A', 1, 'for verification', '1934LZ519F78', 1, 'for verification', 'DOC19006180A', 1, 'for verification', 'BSS-1910-001-01-2485', 1, 'for verification', 'X38FA39888', 1, 'for verification', 'POF0002222', 0, 'unidentified', NULL),
(26, '15', NULL, '2025-10-08 10:56:28', 1, 'serviceable', 'C8RKDT09331', 1, 'serviceable', 'DF0052000012799A', 1, 'for verification', '1931LZ55G8L8', 1, 'serviceable', 'DOC19005807A', 1, 'for verification', 'BSS-1910-001-01-4480', 1, 'for verification', 'X38FA42175', 1, 'for verification', 'POF0002295', 0, 'unidentified', NULL),
(27, '17', NULL, '2025-10-08 10:58:09', 1, 'serviceable', 'C8RKDT12249', 1, 'serviceable', 'DF0052000011898A', 1, 'serviceable', '1934LZ51AGW8', 1, 'for verification', 'DOC19010320A', 1, 'for verification', 'BSS-1910-001-01-5926', 1, 'for verification', 'X38FA44909', 1, 'for verification', 'POF0002208', 0, 'unidentified', NULL),
(28, '19', NULL, '2025-10-09 00:52:55', 1, 'serviceable', 'C8RKDT10286', 1, 'serviceable', 'DF0052000014400A', 1, 'serviceable', '1934LZ52NXP8', 1, 'serviceable', 'DOC19007528A', 1, 'serviceable', 'BSS-1910-001-01-5390', 1, 'serviceable', 'X38FA44317', 1, 'serviceable', 'POF0001994', 0, 'unidentified', NULL),
(29, '25', NULL, '2025-10-09 01:08:59', 1, 'for verification', 'C8RKDT10260', 1, 'serviceable', 'DF0052000013202A', 1, 'for verification', '1930LZ52S0Z8', 1, 'for verification', 'DOC19007732A', 1, 'for verification', 'BSS-1910-001-01-5186', 1, 'for verification', 'X38FA44337', 1, 'for verification', 'POF0001885', 0, 'unidentified', NULL),
(30, '72', NULL, '2025-10-09 01:14:18', 1, 'transferred', 'C8RKDT12763', 1, 'transferred', 'DF0052000012498A', 1, 'transferred', '1934LZ51A3X8', 1, 'transferred', 'DOC19005812A', 1, 'transferred', 'BSS-1910-001-01-3964', 1, 'transferred', 'X38FA41645', 1, 'transferred', 'POF0004852', 0, 'unidentified', NULL),
(31, '73', NULL, '2025-10-09 01:24:43', 1, 'serviceable', 'C8RKDT11539', 1, 'serviceable', 'DF0052000012451A', 1, 'serviceable', '1930LZ50FBV8', 1, 'serviceable', 'DOC19008018A', 1, 'serviceable', 'BSS-1910-001-01-3553', 1, 'serviceable', 'X38FA40247', 1, 'serviceable', 'POF0003417', 0, 'unidentified', NULL),
(32, '74', NULL, '2025-10-09 01:26:48', 1, 'serviceable', 'C8RKDT12515', 1, 'serviceable', 'DF0052000011679A', 1, 'for verification', '1933LZ517GQ8', 1, 'for verification', 'DOC19005795A', 1, 'for verification', 'BSS-1910-001-01-3427', 1, 'for verification', 'X38FA44104', 1, 'serviceable', 'POF0004915', 0, 'unidentified', NULL),
(33, '75', NULL, '2025-10-09 01:28:26', 1, 'serviceable', 'C8RKDT10528', 1, 'serviceable', 'DF0052000013412A', 1, 'serviceable', '1930LZ50ETM8', 1, 'serviceable', 'DOC19010041A', 1, 'serviceable', 'BSS-1910-001-01-5367', 1, 'serviceable', 'X38FA42213', 1, 'serviceable', 'POF0004720', 0, 'unidentified', NULL),
(34, '79', NULL, '2025-10-09 01:30:14', 1, 'serviceable', 'C8RKDT09497', 1, 'serviceable', 'DF0052000014210A', 1, 'serviceable', '1930LZ50FCN8', 1, 'serviceable', 'DOC19005685A', 1, 'serviceable', 'BSS-1910-001-01-3902', 1, 'serviceable', 'X38FA42336', 1, 'serviceable', 'POF0003801', 0, 'unidentified', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personnel_info`
--

CREATE TABLE `personnel_info` (
  `id` int(11) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `contact_num` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `year_started` year(4) NOT NULL,
  `contract_start_date` date NOT NULL,
  `contract_end_date` date NOT NULL,
  `regno` int(100) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `municipality` enum('Abuyog','Alangalang','Albuera','Babatngon','Barugo','Bato','Burauen','Calubian','Capoocan','Carigara','Dagami','Dulag','Hilongos','Hindang','Inopacan','Isabel','Jaro','Javier','Julita','Kananga','La Paz','Leyte','MacArthur','Mahaplag','Matag-Ob','Matalom','Mayorga','Merida','Palo','Palompon','Pastrana','San Isidro','San Miguel','Santa Fe','Tabango','Tabontabon','Tanauan','Tolosa','Tunga','Villaba','Tacloban City','Ormoc City','Baybay City') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personnel_info`
--

INSERT INTO `personnel_info` (`id`, `last_name`, `first_name`, `middle_name`, `position`, `contact_num`, `email`, `year_started`, `contract_start_date`, `contract_end_date`, `regno`, `status`, `municipality`) VALUES
(3, 'ASIS', 'FELOMINA', 'BALDONADO', 'Registration Kit Operator', '12345678910', 'f.asis.psa@gmail.com', '2025', '2025-01-16', '2025-03-31', 134, 'inactive', 'Mayorga'),
(8, 'BULAWAN', 'RENEBOY', 'BALDONADO', 'Registration Kit Operator', '1234567690', 'r.bulawan.psa@gmail.com', '2025', '2025-01-16', '2025-03-31', 90, 'inactive', NULL),
(9, 'OPINIANO', 'PAUL', 'TOLEDO', 'Registration Kit Operator', '1234567690', 'p.opiniano.psa@gmail.com', '2025', '2025-10-01', '2025-12-30', 242, 'active', 'Tacloban City'),
(10, 'PALACIO', 'ELDREDGE EERON', 'n/a', 'Registration Kit Operator', '1234567690', 'e.palacio.psa@gmail.com', '2025', '2025-01-16', '2025-03-31', 216, 'inactive', NULL),
(11, 'BARTOLINI', 'MARK GIL ANGELO', 'MURILLO', 'Registration Kit Operator', '1234567690', 'm.bartolini.psa@gmail.com', '2025', '2025-05-01', '2025-06-30', 94, 'active', 'Burauen'),
(12, 'QUANO', 'JOSE', 'LIBRES', 'Registration Kit Operator', '1234567690', 'q.libre.psa@gmail.com', '2025', '2025-01-16', '2025-03-31', 75, 'inactive', NULL),
(13, 'QUANO', 'JOSE', 'LIBRES', 'RKO', '1234567690', 'q.libres.psa@gmail.com', '2025', '2025-01-16', '2025-03-31', 113, 'inactive', NULL),
(15, 'SABENORIO', 'KHAYCEE', 'BARTE', 'Registration Kit Operator', '12345678910', 'k.sabenorio.psa@gmail.com', '2025', '2025-05-15', '2025-06-30', 194, 'active', 'Tabango'),
(16, 'DOLLER', 'LEAH MAE', 'SALUDAGA', 'Registration Kit Operator', '12345678910', 'leahmaedoller.psa@gmail.com', '2025', '2025-05-15', '2025-06-30', 148, 'active', 'Tabango'),
(17, 'GALVEZ', 'IVY MAE', 'BADION', 'Registration Kit Operator', '12345678910', 'i.galvez.psa@gmail.com', '2025', '2025-05-01', '2025-06-30', 49, 'inactive', 'Mayorga'),
(18, 'ABRERA', 'MARY ROSE', 'YPIL', 'Registration Assistant', '12345678910', 'm.abrera.psa@gmail.com', '2025', '2025-10-01', '2025-12-31', 114, 'active', 'Burauen'),
(19, 'MACAYAN', 'BEVERLY', 'RUÑA', 'Registration Kit Operator', '12345678910', 'b.macayan.psa@gmail.com', '2025', '2025-05-15', '2025-06-30', 178, 'inactive', 'Jaro'),
(20, 'LEONIDO', 'CRISTINA JANE', 'MALQUEZ', 'Registration Kit Operator', '12345678910', 'c.leonido.psa@gmail.com', '2025', '2025-05-15', '2025-06-30', 178, 'active', 'Jaro'),
(21, 'CABIDO', 'MARVIN', 'UGSIMAR', 'Registration Kit Operator', '12345678910', 'm.cabido.psa@gmail.com', '2025', '2025-05-15', '2025-06-30', 73, 'active', 'Ormoc City'),
(22, 'GOJOCO', 'RAYMUND', 'RESTOR', 'Field Validator', '12345678910', 'r.gojoco.psa@gmail.com', '2025', '2025-05-15', '2025-06-30', 217, 'active', 'Ormoc City'),
(23, 'GARCIA', 'DARIEL', '', 'Registration Kit Operator', '12345678910', 'd.abad.garcia.psa@gmail.com', '2025', '2025-05-15', '2025-06-30', 216, 'active', 'Baybay City'),
(24, 'DAPOGIAS', 'NIÑO VYNJIE', 'CONCOLES', 'Field Validator', '12345678910', 'n.dapogias.psa@gmail.com', '2025', '2025-05-15', '2025-06-30', 44, 'active', 'Baybay City'),
(25, 'TUANDO', 'MEAGAN', 'ALCAPARAS', 'Field Validator', '12345678910', 'm.tuando.psa@gmail.com', '2025', '2025-05-15', '2025-06-30', 186, 'inactive', 'Tacloban City'),
(26, 'ZAMORA', 'REY FRANCIS', 'VILBAR', 'Registration Kit Operator', '12345678910', 'rf.zamora.psa@gmail.com', '2025', '2025-05-01', '2025-06-30', 252, 'active', 'Tacloban City'),
(27, 'MALQUEZ', 'NHOHELLE FELEMME', 'ALLUNAM', 'Registration Assistant', '000000000000', 'n.malquez.psa@gmail.com', '2025', '2025-10-01', '2025-12-31', 131, 'active', 'Jaro'),
(28, 'CAGARA', 'MATHEW', 'ALLUNAM', 'Registration Assistant', '0000000000', 'm.cagara.psa@gmail.com', '2025', '2025-10-01', '2025-12-31', 117, 'active', 'Tacloban City'),
(29, 'AUSTRIA', 'ANG', 'N/A', 'Registration Assistant', '1234567690', 'a.austria.psa@gmail.com', '2025', '2025-10-01', '2025-12-31', 1, 'active', 'Tacloban City');

-- --------------------------------------------------------

--
-- Table structure for table `property_number`
--

CREATE TABLE `property_number` (
  `id` int(11) NOT NULL,
  `property` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `province_id` int(11) NOT NULL,
  `province_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`province_id`, `province_name`) VALUES
(837, 'Leyte');

-- --------------------------------------------------------

--
-- Table structure for table `puroks`
--

CREATE TABLE `puroks` (
  `purok_id` int(11) NOT NULL,
  `purok_name` varchar(150) NOT NULL,
  `barangay_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `puroks`
--

INSERT INTO `puroks` (`purok_id`, `purok_name`, `barangay_id`) VALUES
(1, 'Purok 1', 199),
(2, 'Purok 2', 199),
(3, 'Purok 3', 199),
(4, 'Purok 4', 199),
(5, 'Purok 5', 199),
(6, 'Purok 6', 199),
(7, 'Purok 7', 199),
(8, 'Purok 1', 208),
(9, 'Purok 2', 208),
(10, 'Purok 3', 208),
(11, 'Purok 4', 208),
(12, 'Purok 5', 208);

-- --------------------------------------------------------

--
-- Table structure for table `registrationdetails`
--

CREATE TABLE `registrationdetails` (
  `regno` int(11) NOT NULL,
  `property_num` varchar(50) DEFAULT NULL,
  `reg_code` varchar(11) NOT NULL,
  `regkit_serial_num` varchar(255) NOT NULL,
  `computer_name` varchar(255) NOT NULL,
  `machine_id` int(11) NOT NULL,
  `serial_number` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rcs_uname_code` varchar(255) NOT NULL,
  `reg_center_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrationdetails`
--

INSERT INTO `registrationdetails` (`regno`, `property_num`, `reg_code`, `regkit_serial_num`, `computer_name`, `machine_id`, `serial_number`, `password`, `rcs_uname_code`, `reg_center_code`) VALUES
(1, '0', '1', 'FORVERIFICATION', 'PRO-LPT-90165', 0, '5CD935556R', 'dKtDvz5w', '110676', '10697'),
(2, NULL, '2', 'FOR VERIFICATION', 'PRO-LPT-92229', 23334, '5CD935556R', '*3GncKPR', '110367', '10688'),
(3, NULL, '3', 'PH201908POF0003422', 'PRO-LPT-91631', 23380, '5CD9366ZRY', 'Y4$hGXU*', '110386', '10707'),
(4, NULL, '4', 'PH201908POF0004145', 'PRO-LPT-91630', 23357, '5CD93670N0', 'S9nze4t$', '110384', '10705'),
(5, NULL, '5', 'PH201908POF0004787', 'PRO-LPT-92690', 23327, '5CD936705C', 'ELzsVrKd', '110367', '10688'),
(6, NULL, '6', 'FOR VERIFICATION', 'PRO-LPT-82306', 23992, '5cd93660Y1', '6HWfvPMV', '110359', '10679'),
(7, NULL, '7', 'PH201908POF0003982', 'PRO-LPT-82286', 23344, '5CD93661CS', 'CHdBWt5k', '110385', '10706'),
(8, NULL, '8', 'PH201908POF0001083', 'PRO-LPT-82308', 23366, '5CD93660XM', 'e2ydSkfB', '110386', '10707'),
(9, NULL, '9', 'FOR VERIFICATION ', 'PRO-LPT-82302', 23323, '5CD936610B', 'ThU73keY', '110380', '10701'),
(10, NULL, '10', 'FOR VERIFICATION', 'PRO-LPT-82289', 21062, '5CD93661N0', 'HYShU9Wd', '110359', '10679'),
(11, NULL, '11', 'FOR VERIFICATION', 'PRO-LPT-82301', 23328, '5CD93661CJ', 'V2ZkhQxY', '110377', '10698'),
(12, NULL, '12', 'FOR VERIFICATION', 'PRO-LPT-91714', 23595, '5CD93671FY', 'eNk%S83r', '110354', '10674'),
(13, NULL, '13', 'FOR VERIFICATION', 'PRO-LPT-90333', 23309, '5CD93661C6', 'CU6WTrx&', '110355', '10675'),
(14, NULL, '14', 'TRANSFERED TO SURIGAO DEL SUR', 'PRO-LPT-90900', 20058, '5CD9366ZFZ', 'YtseB4Vm', '110220', '10684'),
(15, NULL, '15', 'PH201908POF0004448', 'PRO-LPT-82243', 22145, '5CD936614J', 'sG&6rhUL', '110357', '10677'),
(16, NULL, '16', 'TRANSFERED TO EASTERN SAMAR', 'PRO-LPT-82270', 24002, '5CD93661L9', 'dDAufMC*', '110359', '10679'),
(17, NULL, '17', 'PH201908POF0004850', 'PRO-LPT-90002', 23943, '5CD93473K', '4LB8#nZ7', '110368', '10689'),
(18, NULL, '18', 'TRANSFERED TO SAMAR', 'PRO-LPT-90027', 23356, '5CD9366IC3', 'tVMHGx9N', '110676', '10697'),
(19, NULL, '19', 'PH201908POF0004067', 'PRO-LPT-89638', 23364, '5CD9366ZBR', '7a3tzBXY', '110368', '10689'),
(20, NULL, '20', 'TRANSFERED TO SURIGAO DEL SUR', 'PRO-LPT-89746', 21238, '5CD9367135', 'DgaY7z#m', '110375', '10696'),
(21, NULL, '21', 'TRANSFERED TO LAGUNA', 'PRO-LPT-90036', 23365, '5CD93660Z1', 'Gb5rg9&t', '110676', '10697'),
(22, NULL, '22', 'TRANSFERED TO SAMAR', 'PRO-LPT-90020', 23326, '5CD93661MN', 's8ZFwPKC', '110380', '10701'),
(23, NULL, '23', 'TRANSFERED TO LANAO DEL NORTE', 'PRO-LPT-90005', 23987, '5CD9367OL1', 'f3&zLy2D', '110380', '10704'),
(24, NULL, '24', 'TRANSFERED TO  WEST SAMAR', 'PRO-LPT-90014', 23316, '5CD9366175', 'K8$UM#S6', '110380', '10701'),
(25, NULL, '25', 'PH201908POF0004496', 'PRO-LPT-91800', 24210, '5CD9366ZHP', 'GrT29zkt', '110352', '10038'),
(26, NULL, '26', 'FORVERIFICATION', 'PRO-LPT-82279', 23322, '5CD93661M6', 'sKuN2e47', '110377', '10698'),
(27, NULL, '27', 'PH201908POF0003843', 'PRO-LPT-91685', 0, '5CD9366ZV9', '3bD*6kVN', '110354', '10674'),
(28, NULL, '28', '', 'PRO-LPT-91802', 0, '5CD93671JK', 'x%RAuVN', '110354', '10674'),
(29, NULL, '29', '', 'PRO-LPT-91792', 0, '5CD93662GP', 'GSe*%h3x', '110381', '10702'),
(30, NULL, '30', 'PH201908POF0004515', 'PRO-LPT-91764', 0, '5CD93671LK', 'Mw#Fgh4d', '110381', '10702'),
(31, NULL, '31', '', 'PRO-LPT-93030', 0, '5CD9355586', '2LwcTx7n', '110364', '10685'),
(32, NULL, '32', '', 'PRO-LPT-91653', 0, '5CD9366ZZW', 'Yv2fDKR%', '110353', '10673'),
(33, NULL, '33', '', 'PR0-LPT-91649', 0, '5CD9367167', 'gCzfXcwH', '110387', '10708'),
(34, NULL, '34', 'PH201908POF0002645', 'PRO-LPT-91679', 22146, '5CD93671LM', 'Nar*728L', '110361', '10681'),
(35, NULL, '35', 'PH201908POF0004677', 'PRO-LPT-91799', 0, '5CD93670SW', 'HF4PcAMC', '110381', '10702'),
(36, NULL, '36', '', 'PRO-LPT-91801', 0, '5CD9366ZJQ', '9f#*LP5N', '110366', '10687'),
(37, NULL, '37', '', 'PR0-LPT-91798', 0, '5CD936717D', '4yA8mGge', '110381', '10710'),
(38, NULL, '38', '', 'PR0-LPT-91642', 0, '5CD9367088', 'eDfXC2*n5', '110220', '10684'),
(39, NULL, '39', 'PH201908POF0002641', 'PRO-LPT-91723', 0, '5CD93671QH', '2MaLkVRW', '110382', '10703'),
(40, NULL, '40', '', 'PRO-LPT-91660', 0, '5CD9366ZKP', 'da3mRZTE', '110220', '10684'),
(41, NULL, '41', 'PH201908POF0003326', 'PRO-LPT-91579', 21237, '5CD93671MK', '3ch*W$am', '110353', '10673'),
(42, NULL, '42', '', 'PRO-LPT-91652', 0, '5CD9366ZC3', 'K$5&bU39', '110358', '10678'),
(43, NULL, '43', '', 'PRO-LPT-92741', 0, '5CD934976J', 'VdELKmRB', '110352', '10038'),
(44, NULL, '44', 'PH201908POF0003288', 'PRO-LPT-92067', 0, '5CD93670LB', '*P2&Xvsm', '110358', '10038'),
(45, NULL, '45', '', 'PRO-LPT-91825', 0, '5CD9366ZWQ', 'txaLyBX&', '110367', '10688'),
(46, NULL, '46', 'PH201908POF0004932', 'PRO-LPT-91806', 0, '5CD93671F3', '&2AF3exW', '110363', '10683'),
(47, NULL, '47', '', 'PRO-LPT-82239', 0, '5CD9366163', 'KMTN4f2C', '110372', '10693'),
(48, NULL, '48', '', 'PRO-LPT-82236', 0, '5CD936613J', 'kF85XUMW', '110372', '10693'),
(49, NULL, '49', 'PH201908POF0004897', 'PRO-LPT-91761', 21071, '5CD9366Z94', 'U89T3sHV', '110366', '10687'),
(50, NULL, '50', '', 'PRO-LPT-91803', 0, '5CD9366OM7', 'A7vUVuH5', '110381', '10702'),
(51, NULL, '51', 'PH201908POF0004624', 'PRO-LPT-91637', 20533, '5CD93671MC', 'C7wLPXk4', '110364', '10685'),
(52, NULL, '52', '', 'PRO-LPT-91629', 0, '5CD 9366ZVN', '6nByG#MH', '110358', '10678'),
(53, NULL, '53', 'PH201908POF0002668', 'PRO-LPT-91615', 23333, '5CD936716G', 'zfZ8GCWn', '110358', '10678'),
(54, NULL, '54', 'PH201908POF0003352', 'PRO-LPT-92201', 21225, '5CD93555H4', 'yHRwzA%Z', '110362', '10682'),
(55, NULL, '55', 'PH201908POF0003307', 'PRO-LPT-82290', 21070, '5CD93661DJ', '3CUprdQC', '110359', '10679'),
(56, NULL, '56', '', 'PRO-LPT-91578', 0, '5CD93671NQ', '36sfcwm$', '110364', '10685'),
(57, NULL, '57', '', 'PR0-LPT-82199', 0, '5CD93661MD', 'S5czaG9x', '110374', '10695'),
(58, NULL, '58', '', 'PRO-LPT-82197', 0, '5CD93661MZ', 'xW855jv8', '110359', '10679'),
(59, NULL, '59', 'PH201908POF0003415', 'PRO-LPT-92527', 0, '5CD935551', 'LVW8hGfB', '110373', '10694'),
(60, NULL, '60', '', 'PR0-LPT-91593', 0, '5CD9366ZPT', 'W7b&HaKY', '110220', '10684'),
(61, NULL, '61', '', 'PR0-LPT-91736', 0, '5CD936703W', 'dz3vLZxg', '110366', '10687'),
(62, NULL, '62', '', 'PRO-LPT-91583', 0, '5CD936657G', 'CBSf8rtz', '110355', '10675'),
(63, NULL, '63', 'PH201908POF0000308', 'PRO-LPT-93015', 22946, '5CD93555D4', 'Xw8g&cyA', '110371', '10692'),
(64, NULL, '64', '', 'PRO-LPT-91668', 0, '5CD9366CNJ', 'VAb47x#e', '110392', '10711'),
(65, NULL, '65', 'PH201908POF0002545', 'PRO-LPT-91565', 0, '5cd93670RX', '#GuKM4Pe', '110352', '10038'),
(66, NULL, '66', '', 'PRO-LPT-81789', 0, '5CD9349767', 'DK9FeaBr', '110352', '10038'),
(67, NULL, '67', '', 'PRO-LPT-92341', 0, '5CD93670QYC', 'w6U#zXW&', '110383', '10704'),
(68, NULL, '68', 'PH201908POF0003194', 'PRO-LPT-82220', 0, '5CD936619P', '9YGSYu3p', '110372', '10693'),
(69, NULL, '69', '', 'PRO-LPT-82304', 0, '5CD93660Y4', 'ZfxwE9x3', '110364', '10685'),
(70, NULL, '70', '', 'PRO-LPT-82288', 0, '5CD936619X', 'UV*xCFkN', '110385', '10706'),
(71, NULL, '71', '', 'PRO-LPT-91627', 0, '5CD93670SF', '2M8RWb43', '110364', '10685'),
(72, NULL, '72', 'PH201908POF0004627', 'PRO-LPT-82218', 0, '5CD9352WZS', 'hM4avczU', '110360', '10680'),
(73, NULL, '73', 'PH201908POF0002528', 'PRO-LPT-90039', 23330, '5CD93661S9', '%X2$zuak', '110355', '10675'),
(74, NULL, '74', 'PH201908POF0004653', 'PRO-LPT-89706', 23350, '5CD936716T', 'V5g6AzC#', '110368', '10689'),
(75, NULL, '75', 'PH201908POF0004353', 'PRO-LPT-89660', 20914, '5CD93660NW', '8y9XgDDg', '110383', '10704'),
(76, NULL, '76', '', 'PRO-LPT-90003', 0, '5CD9367130', '%us#wxhE', '110676', '10697'),
(77, NULL, '77', '', 'PRO-LPT-81793', 0, '5CD934972Y', 'zELGtcSC', '110352', '10038'),
(78, NULL, '78', '', 'PRO-LPT-82180', 0, '5CD936617B', '52dspWPp', '110365', '10685'),
(79, NULL, '79', 'PH201908POF0002983', 'PRO-LPT-91872', 21230, '5CD9366ZT3', '*eRfBMXE', '110369', '10690'),
(80, NULL, '80', '', 'PRO-LPT-91796', 0, '5CD934972P', 'c#s67Vrv .', 'NA', 'NA'),
(81, NULL, '81', 'PH201908POF0003656', 'PRO-LPT-91824', 0, '5CD936719Z', 'LcsEthy3', '110363', '10683'),
(82, NULL, '82', 'PH201908POF0003868', 'PRO-LPT-91823', 0, '5CD93671FT', 'E&8bFHWS', '110363', '10683'),
(83, NULL, '83', '', 'PRO-LPT-91540', 0, '5CD93671BL', '*&6zSdP#', '110384', '10705'),
(84, NULL, '84', '', 'PRO-LPT-82913', 0, '5CD9352WZN', 'd57JN3n3', '110362', '10682'),
(85, NULL, '85', '', 'PRO-LPT-82186', 0, '5CD93661M5', 'pT7gv8Ls', '110374', '10695'),
(86, NULL, '86', '', 'PRO-LPT-82187', 0, '5CD936616K', '4*8af9#d', '110374)', '10695'),
(87, NULL, '87', '', 'PRO-LPT-92762', 0, '5CD93670CX', '8AXZW7TN', '110352', '10038'),
(88, NULL, '88', '', 'PRO-LPT-91701', 0, '5CD93671KV', 'gLvVK7XA', '110354', '10674'),
(89, NULL, '89', 'PH201908POF0004647', 'PRO-LPT-92869', 0, '5CD9352XG5', '&NTYHrRs', '110352', '10038'),
(90, '0', '90', 'PH201908POF0004665', 'PRO-LPT-92169', 23611, '5CD9366Z7M', 'YG3xABSf', '110373', '10694'),
(91, NULL, '91', 'PH201908POF0004734', 'PRO-LPT-92076', 0, '5CD93670YR', 'nkd5MgSv', '110369', '10690'),
(92, NULL, '92', '', 'PRO-LPT-91700', 0, '5CD936715F', 'bPZ#wWYs', '110383', '10704'),
(93, NULL, '93', '', 'PRO-LPT-93899', 0, '5CD93661HD', 'y&MmftaH', '110389', '10710'),
(94, NULL, '94', '', 'PRO-LPT-93897', 0, '5CD93662VT', '&B8W3#Hs', '110357', '10677'),
(95, NULL, '95', '', 'PRO-LPT-93898', 0, '5CD93661JO', '5H8ysg93', '110364', '10685'),
(96, NULL, '96', '', 'PRO-LPT-92060', 0, '5CD9366ZHC', 'mXZ*SeD&', '110363', '10683'),
(97, NULL, '97', '', 'PRO-LPT-93900', 0, '5CD93661FJ', 'Zu&VrFxN', '110364', '10585'),
(98, NULL, '98', '', 'PRO-LPT-93797', 0, '5CD9366ZR3', '&YNB2Z54', '110364', '10685'),
(99, NULL, '99', '', 'PRO-LPT-92071', 0, '5CD9366ZLR', '5zVNw2yT', '110361', '10681'),
(100, NULL, '100', '', 'PRO-LPT-82298', 0, '5CD936619D', 'c6eAVZND', '110377', '10698'),
(101, NULL, '101', '', 'PRO-LPT-09102', 0, '5CD9366ZDR', '48&NYSMA', '110374', '10695'),
(102, NULL, '102', 'PH201908POF0003188', 'PRO-LPT-82299', 0, '5CD93661JX', '$W%nVSgR', '110386', '10707'),
(103, NULL, '103', 'PH201908POF0003662', 'PRO-LPT-90894', 0, '5CD93670FR', '8ahX2fBr', 'NA', 'NA'),
(104, NULL, '104', '', 'PRO-LPT-93833', 0, '5CD936610H', '#kuNcT7G', '---', '---'),
(105, NULL, '105', '', 'PRO-LPT-91744', 0, '5CD93671F9', 'E#Y&FAw2', '110220', '10684'),
(106, NULL, '106', '', 'PRO-LPT-93855', 0, '5CD93670HC', '2n*RLkz3', '110366', '10687'),
(107, NULL, '107', '', 'PRO-LPT-93905', 0, '5CD93670JH', 'x5bC&fsu', '110357', '10677'),
(108, NULL, '108', 'PH201908POF0004065', 'PRO-LPT-93860', 0, '5CD93670BW', '9dSRf$mV', '110364', '10685'),
(109, NULL, '109', '0', 'PRO-LPT-93906', 0, '5CD9366ZZM', 'HefKDnR9', '110371', '10692'),
(110, NULL, '110', '', 'PRO-LPT-93906', 0, '5CD9366ZZM', 'HefKDnR9', '110371', '10692'),
(111, NULL, '111', '', 'PRO-LPT-93908', 0, '5CD93352WXF', 'ND*GxZSA', '110353', '10692'),
(112, NULL, '112', 'PH201908POF0004924', 'PRO-LPT-91664 (ORIGINAL REG CODE 112)', 20534, '5CD93670S5J', 'Dyd8MnG&', '110364', '10685'),
(113, NULL, '113', 'PH201908POF0003272', 'PRO-LPT-91555', 0, '5CD9366ZPG', 'uFk9GR6P', '110384', '10705'),
(114, NULL, '114', 'PH201908POF0002140', 'PRO-LPT-92370', 22148, '5CD93670B5', 'YBtrcuXZ', '110361', '10681'),
(115, NULL, '115', 'FORVERIFICATION', 'PRO-LPT-82192', 21227, '5CD93660WM', 'twZjAA8D', '110375', '10696'),
(116, NULL, '116', 'PH201908POF0002887', 'PRO-LPT-92739', 0, '5CD936705Q', '#ZFN4BDu', '110356', '10676'),
(117, NULL, '117', 'PH201908POF0002892', 'PRO-LPT-91635', 0, '5CD93670RB', 'zeEmHZcd', '110383', '10704'),
(118, NULL, '118', 'PH201908POF0002724', 'PRO-LPT-91597', 23314, '5CD93670GZ', 'yWExh2rk', '110355', '10675'),
(119, NULL, '119', 'PH201908POF0004120', 'PRO-LPT-91695', 0, '5CD93671CY', 'CWH9EULM', '110369', '10690'),
(120, NULL, '120', 'PH201908POF0004748', 'PRO-LPT-91694', 0, '5CD93670P2', 'BPSauk7&', '110354', '10674'),
(121, NULL, '121', 'FOR VERIFICATION', 'PRO-LPT-91661', 0, '5CD93670X', 'znL&eK*a', '110388', '10709'),
(122, NULL, '122', '', 'PRO-LPT-92954', 0, '5CD93660H4', 'F5fxgmNt', '110367', '10688'),
(123, NULL, '123', '', 'PRO-LPT-91693', 0, '5CD9367009', 'vXEsPH3R', '110365', '10686'),
(124, NULL, '124', 'PH201908POF0004873', 'PRO-LPT-91692', 0, '5CD9366ZZP', '*maf$vW6', '110354', '10674'),
(125, NULL, '125', '', 'PRO-LPT-91676', 0, '5CD936718B', 'BgR6n$z8', '110354', '10674'),
(126, NULL, '126', 'PH201908POF0003149', 'PRO-LPT-91641', 0, '5CD93670JL', '3v7kgGwz', '110387', '10708'),
(127, NULL, '127', 'PH201908POF0004741', 'PRO-LPT-91643', 21061, '5CD93670T5', 'tBTP3G*w', '110365', '10686'),
(129, NULL, '129', '', 'PRO-LPT-92662', 0, '5CD93555J0', 'BSzG*cwA', '110352', '10038'),
(131, NULL, '131', 'PH201908POF0003663', 'PRO-LPT-91942', 0, '5CD93670NL', '3%MUC*uz', '110363', '10683'),
(132, NULL, '132', 'PH201908POF0004828', 'PRO-LPT-92058', 0, '5CD9366ZLD', 'sM6xrm8N', '110363', '10683'),
(133, NULL, '133', 'PH201908POF0002717', 'PRO-LPT-92649', 23332, '5CD9352XLN', '2$tv9CcF', '110367', '10688'),
(134, NULL, '134', 'PH201908POF0004951', 'PRO-LPT-92515', 23307, '5CD936712V', 'hx4kH*s&', '110358', '10678'),
(135, NULL, '135', '', 'PRO-LPT-92227', 0, '5CD93661Q8', 'NN9HcngR', '110359', '10679'),
(136, NULL, '136', '', 'PRO-LPT-92228', 0, '5CD9352WY4', 'zJMVu3JL', '110377', '10698'),
(137, NULL, '137', '', 'PRO-LPT-92408', 0, '5CD93670F4', 'c&udT8tU', '110361', '10681'),
(138, NULL, '138', 'PH201908POF0004257', 'PRO-LPT-92216', 21068, '5CD931559N', '3TaK$#7L', '110366', '10687'),
(139, NULL, '139', '', 'PRO-LPT-90887', 0, '5CD9366ZKC', 'uzgEcnT3', '110384', '10705'),
(140, NULL, '140', '', 'PRO-LPT-92990', 0, '5CD93661KM', 'eP7DgLmU', '110371', '10692'),
(141, NULL, '141', 'PH201908POF0002749', 'PRO-LPT-91697', 0, '5CD93670NS', 'AXsTMkSx', '110354', '10674'),
(142, NULL, '142', 'PH201908POF0003913', 'PRO-LPT-92855', 0, '5CD93660TG', '4V5gwSEK', '110352', '10038'),
(143, NULL, '143', 'PH201908POF0004651', 'PRO-LPT-92511', 22143, '5CD935552B', '$7Gs6Nmc', '110356', '10676'),
(144, NULL, '144', '', 'PRO-LPT-91698', 0, '5CD93671NT', 'x#TDCSmn', '110354', '10674'),
(145, NULL, '145', '', 'PRO-LPT-91807', 0, '5CD936719V', 'ET#fbS5R', '110361', '10681'),
(146, NULL, '146', '', 'PRO-LPT-91809', 0, '5CD93662NX', 'd#7&Wf%x', '110363', '10683'),
(147, NULL, '147', '', 'PRO-LPT-92656', 0, '5CD9355542', 'D8zxVCZd', '110353', '10673'),
(148, NULL, '148', 'PH201908POF0003387', 'PRO-LPT-92191', 0, '5CD93671K9', '*XPEAL3U', '110373', '10694'),
(149, NULL, '149', '', 'PRO-LPT-92156', 0, '5CD93670VGC', 'f6B*K4x2', '110369', '10690'),
(150, NULL, '150', '', 'PRO-LPT-91848', 0, '5CD936616S', 'XHUegaMN', '110353', '10673'),
(151, NULL, '151', '', 'PRO-LPT-92318', 0, '5CD9366ZQP', 'UVML3x6t', '110356', '10676'),
(152, NULL, '152', '', 'PRO-LPT-90154', 0, '5CD936613W', 'T7&u%9fd', '110370', '10691'),
(153, NULL, '153', '', 'PRO-LPT-93918', 0, '5CD93670C3', '7b5uremn', '110389', '10710'),
(154, NULL, '154', '', 'PRO-LPT-93922', 0, '5CD936700F', 'tuag5hNU', '110367', '10688'),
(155, NULL, '155', '', 'PRO-LPT-93925', 0, '5CD9366ZH7', 'uegXSE6T', '110362', '10682'),
(156, NULL, '156', '', 'PRO-LPT-93921', 0, '5CD93670T9', '5t$SNGBv', '110362', '10682'),
(157, NULL, '157', '', 'PRO-LPT-92542', 0, '5CD93670XR', 'X3S7Hd4%', '110389', '10710'),
(158, NULL, '158', 'PH201908POF0004301', 'PRO-LPT-89718', 22150, '5CD93660VV', 'R5Ef&N6*', '110357', '10677'),
(159, NULL, '159', '', 'PRO-LPT-93913', 0, '5CD9366194', '3WFuaefy', '110353', '10673'),
(160, NULL, '160', 'PH201908POF0003158', 'PRO-LPT-93911', 0, '5CD9367065', 'CL3eKMA2', '110353', '10673'),
(161, NULL, '161', '', 'PRO-LPT-81778', 0, '5CD9349724', '3Rfk2%s4', '110352', '10038'),
(162, NULL, '162', '', 'PRO-LPT-93914', 0, '5CD935557S', 'LWNGtPvK', '110371', '10692'),
(163, NULL, '163', '', 'PRO-LPT-93912', 0, '5CD93670C9', '7E%2USTX', '110371', '10692'),
(164, NULL, '164', '', 'PRO-LPT-92163', 0, '5CD93670JC', '%fCLvawK', '110362', '10682'),
(165, NULL, '165', '', 'PRO-LPT-93885', 0, '5CD93660JK', '75HA*$fZ', '110364', '10685'),
(166, NULL, '166', '', 'PRO-LPT-93108', 0, '5CD93670KK', '%*W4yYHc', '110364', '10685'),
(167, NULL, '167', 'PH201908POF0003932', 'PRO-LPT-89734', 0, '5CD93671G9', 'pphK4TKr', '110368', '10689'),
(168, NULL, '168', 'PH201908POF0002794', 'PRO-LPT-92760', 22948, '5CD9366ZVX', '%G5dHYAn', '110371', '10692'),
(169, NULL, '169', '', 'PRO-LPT-93902', 0, '5CD936615Q', 'veHh&s94', '110366', '10687'),
(170, NULL, '170', '', 'PRO-LPT-92222', 0, '5CD93670YN', '9*CWxz3S', '110362', '10682'),
(171, NULL, '171', '', 'PRO-LPT-93061', 0, '5CD934977D', 'sB%93Cve', '110371', '10692'),
(172, NULL, '172', '', 'PRO-LPT-91805', 0, '5CD93661DW', 'C*5t2Hvm', '110361', '10681'),
(173, NULL, '173', '', 'PRO-LPT-93013', 0, '5CD934971N', 'sbtExf3a', '110371', '10692'),
(174, NULL, '174', '', 'PRO-LPT-91699', 0, '5CD93671D0', 'gtKaFn4V', '110220', '10684'),
(175, NULL, '175', 'PH201908POF0002990', 'PRO-LPT-91811', 0, '5CD9366ZX4', 'PRc7MEm3', '110363', '10683'),
(176, NULL, '176', '', 'PRO-LPT-91810', 0, '5CD9366ZM6', 'L2sGSR76', '110364', '10685'),
(177, NULL, '177', 'PH201908POF0004289', 'PRO-LPT-82251', 2335, '5CD936613Y', 'Nzfs7hYz', '110385', '10706'),
(178, NULL, '178', 'PH201908POF0003469', 'PRO-LPT-82216', 23994, '5CD9352WZP', 'S4L6af3z', '110359', '10679'),
(179, NULL, '179', '', 'PRO-LPT-90006', 0, '5CD93670LK', 'h2b4&WcS', '110676', '10697'),
(180, NULL, '180', '', 'PRO-LPT-90004', 0, '5CD936707D', '6%kmN&ta', '110369', '10690'),
(181, NULL, '181', 'PH201908POF0003435', 'PRO-LPT-92099', 0, '5CD93671HP', '2HtfY*eG', '110363', '10683'),
(182, NULL, '182', 'PH201908POF0002989', 'PRO-LPT-82249', 0, '5CD936617C', 'sGShQC8Y', '110372', '10693'),
(183, NULL, '183', '', 'PRO-LPT-91640', 0, '5CD93670VL', 'BHnVbfC8', '110383', '10704'),
(184, NULL, '184', '', 'PRO-LPT-91628', 0, '5CD936706Y', 'zgr*t982', '110384', '10705'),
(185, NULL, '185', '', 'PRO-LPT-91667', 0, '5CD9366ZXC', 'tmfCYRFU', '110388', '10709'),
(186, NULL, '186', '', 'PRO-LPT-91636', 0, '5CD93671PB', 'HkURBCAc', '110353', '10673'),
(187, NULL, '187', 'PH201908POF0002708', 'PRO-LPT-93894', 0, '5CD93670GL', '2uFMnH5z', '110364', '10685'),
(188, NULL, '188', 'PH201908POF0004164', 'PRO-LPT-81784', 0, '5CD9352XB7', 'XC$fem8G', '110352', '10O38'),
(189, NULL, '189', '', 'PRO-LPT-91982', 0, '5CD9366ZMK', 'hMZvS5uV', '110352', '10038'),
(190, NULL, '190', '', 'PRO-LPT-93896', 0, '5CD93555F9', 'ZuD#bM2%', '110366', '10687'),
(191, NULL, '191', '', 'PRO-LPT-92629', 0, '5CD936700H', 'ceSsX*NC', '110356', '10676'),
(192, NULL, '192', '', 'PRO-LPT-93895', 0, '5CD9366ZT9', 'L7bf2STC', '110383', '10704'),
(193, NULL, '193', '', 'PRO-LPT-93904', 0, '5CD9352X34', 'EtFBawuV', '110353', '10673'),
(194, NULL, '194', 'PH201908POF0003443', 'PRO-LPT-92237', 0, '5CD9366ZXP', 'b&4DPEse', '110352', '10038'),
(195, NULL, '195', '', 'PRO-LPT-93044', 0, '5CD936702H', 'PwG7cX2x', '110364', '10685'),
(196, NULL, '196', '', 'PRO-LPT-93903', 0, '5CD93670DB', '&sm8b9B7', '110364', '10685'),
(197, NULL, '197', '', 'PRO-LPT-93901', 0, '5CD9366ZLQ', 'z3ycECv6', '110367', '10688'),
(198, NULL, '198', '', 'PRO-LPT-93909', 0, '5CD9352X5X', 'dCV$AxeN', '110352', '10038'),
(199, NULL, '199', 'PH201908POF0003584', 'PRO-LPT-93910', 0, '5CD9352X5G', '%fwU4kVa', '110352', '10038'),
(200, NULL, '200', '', 'PRO-LPT-93917', 0, '5CD934976N', 'aySV#W8e', '110367', '10688'),
(201, NULL, '201', 'PH201908POF0000052', 'PRO-LPT-93062', 0, '5CD9349544/5CD934496YY', '3kgGSbKX', '110364', '10685'),
(202, NULL, '202', '', 'PRO-LPT-93916', 0, '5CD9352XFF', 'YvNXktha', '110383', '10704'),
(203, NULL, '203', '', 'PRO-LPT-93915', 0, '5CD936706P', 'e7mzh2w*', '110370', '10691'),
(204, NULL, '204', 'PH201908POF0003400', 'PRO-LPT-82313', 20909, '5CD93661LP', 'N7Rdsbnz', '110364', '10685'),
(205, NULL, '205', 'PH201908POF0003918', 'PRO-LPT-82314', 23329, '5CD93661M2', 'pV2SjReH', '110380', '10701'),
(206, NULL, '206', 'PH201908POF0002943', 'PRO-LPT-89858', 23319, '5CD9366Z8P', 'tTgzFxh4', '110380', '10701'),
(207, NULL, '207', '', 'PRO-LPT-89799', 0, '5CD93660WD', 'e&$X3uU*', '110365', '10686'),
(208, NULL, '208', '', 'PRO-LPT-89632', 0, '5CD9366ZB6', '%mzAcfaX', '110369', '10690'),
(209, NULL, '209', '', 'PRO-LPT-89889', 0, '5CD93661KB', 'M924wnvb', '110383', '10704'),
(210, NULL, '210', '', 'PRO-LPT-89842', 0, '5CD93670R8', '6HHw6ZA3', '110370', '10691'),
(211, NULL, '211', '', 'PRO-LPT-89860', 0, '5CD936718M', 'ge*Z4xHM', '110367', '10688'),
(212, NULL, '212', '', 'PRO-LPT-89842', 0, '5CD93670R8', '6HHw6ZA3', '110370', '10691'),
(213, NULL, '213', '', 'PRO-LPT-89860', 0, '5CD93618M', 'ge*Z4XHM', '110367', '10688'),
(214, NULL, '214', '', 'PRO-LPT-89781', 0, '5CD93660S1', 'vDcHfs98', '110383', '10704'),
(215, NULL, '215', 'FORVERIFICATION', 'PRO-LPT-89607', 0, '5CD93660X9', 'ETvtE7R6', '110359', '10679'),
(216, NULL, '216', 'FORVERIFICATION', 'PRO-LPT-89808 (ORIGINAL REG CODE 216)', 21236, '5CD9367013', 'D3$u2HUx', '110375', '10696'),
(217, NULL, '217', 'PH201908POF0004567', 'PRO-LPT-89661', 0, '5CD93660MC', '&YTKFRaz', '110368', '10689'),
(218, NULL, '218', 'PH201908POF0004653', 'PRO-LPT-82311', 0, '5CD93660VM', '3VfHEz3t', '110365', '10686'),
(219, NULL, '219', 'FORVERIFICATION', 'PRO-LPT-89797 (ORIGINAL REG CODE 219)', 20530, '5CD9366Z8Y', 'hM6rV7Sk', '110364', '10685'),
(220, NULL, '220', 'FOR VERIFICATION', 'PRO-LPT-82250', 0, '5CD936616Z', 'TuH58AI4', '110362', '10682'),
(222, NULL, '222', 'PH201908POF0004619', 'PRO-LPT-82250', 0, '5CD936616Z', 'TuH58AI4', '110362', '10682'),
(223, NULL, '223', 'FORVERIFICATION', 'PRO-LPT-92655', 0, '5CD9352XKQ', 'h$WxSHUX', '110352', '10038'),
(224, NULL, '224', 'FORVERIFICATION', 'PRO-LPT-92639 (ORIGINAL REG CODE 222)', 22944, '5CD9367078', 'Yu8cegXG', '110371', '10692'),
(225, NULL, '225', '', 'PRO-LPT-92655', 0, '5CD9352XKQ', 'h$WxSHUX', '110352', '10038'),
(226, NULL, '226', '', 'PRO-LPT-91822', 0, '5CD9366ZH6', '&%Byse56', '110358', '10678'),
(227, NULL, '227', '', 'PRO-LPT-91724', 0, '5CD93671C3', '&VH9r#7f', '110382', '10703'),
(228, NULL, '228', '', 'PRO-LPT-82268', 0, '5CD93661CY', '38zgzJhT', '110369', '10690'),
(229, NULL, '229', '', 'PRO-LPT-91763', 0, '5CD9366ZBP', 'M&FumdLx', '110382', '10703'),
(230, NULL, '230', 'FORVERIFICATION', 'PRO-LPT-91582', 23321, '5CD9366ZN6', '4Xx3nsa6', '110385', '10706+'),
(231, NULL, '231', 'FOR VERIFICATION', 'PRO-LPT-82206 (ORIGINAL REG CODE 229', 0, '5CD9352XHM', 'wS4rwKqC', '110360', '10680'),
(232, NULL, '232', 'FORVERIFICATION', 'PRO-LPT-82211', 23358, '5CD9352X61', 'r3xxxHVQ', '110360', '10680'),
(233, NULL, '233', '', 'PRO-LPT-91696', 0, '5CD93670KJ', '8E&HBcDx', '110354', '10674'),
(235, NULL, '235', '', 'PRO-LPT-82282', 0, '5CD93661LY', 'P6sx5rKF', '110377', '10698'),
(236, NULL, '236', '', 'PRO-LPT-93923', 0, '5CD93670LY', '*#VKMUsz', '110362', '10682'),
(237, NULL, '237', '', 'PRO-LPT-89762', 0, '5CD936715H', 'TG3zE6Us', '110365', '10686'),
(238, NULL, '238', '', 'PRO-LPT-89619', 0, '5CD936717H', 'vQFdQS3t', '110352', '10038'),
(239, NULL, '239', 'FORVERIFICATION', 'PRO-LPT-92548', 23596, '5CD95552Q', '*v#aBAzt', '110373', '10694'),
(240, NULL, '240', '', 'PRO-LPT-89856', 0, '5CD9367015', '6kMEZHGm', '110380', '10701'),
(242, NULL, '242', 'FORVERIFICATION', 'PRO-LPT-82293 (ORIGINAL REG CODE 242)', 23345, '5CD93661F3', 'b%SxBraP', '110386', '10707'),
(243, NULL, '243', 'PH201908POF0002525', 'PRO-LPT-93027', 0, '5CD9366ZWW', 'VTM3BhUP', '110386', '10707'),
(244, NULL, '244', 'FORVERIFICATION', 'PRO-LPT-82281', 0, '5CD93661C9', '4DKs3zpG', '110385', '10706'),
(245, NULL, '245', 'FOR VERIFICATION', 'PRO-LPT-92979 (ORIGINAL REG CODE 245)', 0, '5CD934979K', 'B5dXMWN$', '110852', '10038'),
(246, NULL, '246', 'FOR VERIFICATION', 'PRO-LPT-82269', 0, '5CD93661KZ', '4myfCVpG', '110359', '10679'),
(248, NULL, '248', '', 'PRO-LPT-82269', 0, '5CD93661KZ', '4MyfCVpG', '110359', '10679'),
(249, NULL, '249', 'PH201908POF0003159', 'PRO-LPT-89915 (ORIGINAL REG CODE 249)', 0, '5CD935554J', 'my6Pwaft', '110383', '10704'),
(250, NULL, '250', '', 'PRO-LPT-82230', 0, '5CD936612T', 'pL6UT3BF', '110372', '10693'),
(252, NULL, '252', 'PH201908POF0000612', 'PRO-LPT-82196 (ORIGINAL REGCODE 252)', 23347, '5CD93661HY', '4GVhjTVu', '110374', '10695'),
(253, NULL, '253', '', 'PRO-LPT-82194', 0, '5CD9352WXG', 'r96suWXt', '110364', '10685'),
(255, NULL, '255', '', 'PRO-LPT-90404', 0, '5CD9366187', '7nZg6AxY', '110384', '10705'),
(256, NULL, '256', '', 'PRO-LPT-93059', 0, '5CD93496ZP', 'DA&a%*xK', '110367', '10688'),
(257, NULL, '257', '', 'PRO-LPT-90828', 0, '5CD936703G', 'Nt%ZTCYe', '110357', '10677'),
(258, NULL, '258', '', 'PRO-LPT-90330', 0, '5CD936615Z', '*g7B3ZnV', '110384', '10705'),
(320, NULL, '320', 'TRANSFERED TO EASTERN SAMAR', 'PRO-LPT-92267', 23629, '5CD9366ZLG', 'r4RL9&yk', '110366', '10687'),
(221, NULL, '221', 'PH201908POF0003240', 'PRO-LPT-82207', 0, '5CD9352X0Z', 'n6VB*GF', '110360', '10680'),
(247, NULL, '247', 'FOR VERIFICATION', 'PRO-LPT-92510', 0, '5CD936705F', 'nB*5LRUN', '110373', '10694'),
(30, NULL, '', '30', 'PRO-LPT-91764', 0, '5CD93671LK', 'Mw#Fgh4d', '110381', '10702');

-- --------------------------------------------------------

--
-- Table structure for table `rts`
--

CREATE TABLE `rts` (
  `trn` varchar(50) NOT NULL,
  `date_received` date NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `province_id` int(11) NOT NULL,
  `municipality_id` int(11) NOT NULL,
  `barangay_id` int(11) DEFAULT NULL,
  `purok_id` int(11) DEFAULT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rts`
--

INSERT INTO `rts` (`trn`, `date_received`, `first_name`, `last_name`, `middle_name`, `province_id`, `municipality_id`, `barangay_id`, `purok_id`, `status`) VALUES
('10704240030148420210426055830', '2025-09-10', 'ROWENA', 'FALCON', 'DE QUINTO', 837, 83731, 321, NULL, 'Unclaimed');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`barangay_id`),
  ADD KEY `municipality_id` (`municipality_id`);

--
-- Indexes for table `borrower`
--
ALTER TABLE `borrower`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `excel`
--
ALTER TABLE `excel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ismd_validated_regkits`
--
ALTER TABLE `ismd_validated_regkits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `municipalities`
--
ALTER TABLE `municipalities`
  ADD PRIMARY KEY (`municipality_id`),
  ADD KEY `province_id` (`province_id`);

--
-- Indexes for table `peripherals`
--
ALTER TABLE `peripherals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `regno` (`regno`),
  ADD UNIQUE KEY `fp_scanner_serial` (`fp_scanner_serial`),
  ADD UNIQUE KEY `iris_scanner_serial` (`iris_scanner_serial`),
  ADD UNIQUE KEY `webcam_serial` (`webcam_serial`),
  ADD UNIQUE KEY `document_scanner_serial` (`document_scanner_serial`),
  ADD UNIQUE KEY `extended_monitor_serial` (`extended_monitor_serial`),
  ADD UNIQUE KEY `printer_serial` (`printer_serial`),
  ADD UNIQUE KEY `battery_pack_serial` (`battery_pack_serial`),
  ADD UNIQUE KEY `photobooth_kit_serial` (`photobooth_kit_serial`);

--
-- Indexes for table `personnel_info`
--
ALTER TABLE `personnel_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `property_number`
--
ALTER TABLE `property_number`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`province_id`);

--
-- Indexes for table `puroks`
--
ALTER TABLE `puroks`
  ADD PRIMARY KEY (`purok_id`),
  ADD KEY `barangay_id` (`barangay_id`);

--
-- Indexes for table `rts`
--
ALTER TABLE `rts`
  ADD PRIMARY KEY (`trn`),
  ADD KEY `province_id` (`province_id`),
  ADD KEY `municipality_id` (`municipality_id`),
  ADD KEY `barangay_id` (`barangay_id`),
  ADD KEY `purok_id` (`purok_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `barangay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=326;

--
-- AUTO_INCREMENT for table `borrower`
--
ALTER TABLE `borrower`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `excel`
--
ALTER TABLE `excel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ismd_validated_regkits`
--
ALTER TABLE `ismd_validated_regkits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `municipalities`
--
ALTER TABLE `municipalities`
  MODIFY `municipality_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83744;

--
-- AUTO_INCREMENT for table `peripherals`
--
ALTER TABLE `peripherals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `personnel_info`
--
ALTER TABLE `personnel_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `property_number`
--
ALTER TABLE `property_number`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `province_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6501;

--
-- AUTO_INCREMENT for table `puroks`
--
ALTER TABLE `puroks`
  MODIFY `purok_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barangays`
--
ALTER TABLE `barangays`
  ADD CONSTRAINT `barangays_ibfk_1` FOREIGN KEY (`municipality_id`) REFERENCES `municipalities` (`municipality_id`);

--
-- Constraints for table `municipalities`
--
ALTER TABLE `municipalities`
  ADD CONSTRAINT `municipalities_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`province_id`);

--
-- Constraints for table `puroks`
--
ALTER TABLE `puroks`
  ADD CONSTRAINT `puroks_ibfk_1` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`barangay_id`);

--
-- Constraints for table `rts`
--
ALTER TABLE `rts`
  ADD CONSTRAINT `rts_ibfk_1` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`province_id`),
  ADD CONSTRAINT `rts_ibfk_2` FOREIGN KEY (`municipality_id`) REFERENCES `municipalities` (`municipality_id`),
  ADD CONSTRAINT `rts_ibfk_3` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`barangay_id`),
  ADD CONSTRAINT `rts_ibfk_4` FOREIGN KEY (`purok_id`) REFERENCES `puroks` (`purok_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
