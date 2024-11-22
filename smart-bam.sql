-- phpMyAdmin SQL Dump
-- version 5.3.0-dev+20221014.c92621d023
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2024 at 07:59 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart-bam`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id_admin`, `username`, `password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_alternatif`
--

CREATE TABLE `tbl_alternatif` (
  `id_alternatif` int(11) NOT NULL,
  `nama_alternatif` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_alternatif`
--

INSERT INTO `tbl_alternatif` (`id_alternatif`, `nama_alternatif`) VALUES
(3, 'Berkat Agro Mandiri'),
(4, 'GAT'),
(5, 'Cahaya Meat CPS'),
(6, 'Super Daging Tanggerang'),
(7, 'Victory Beef Supplier Daging Sapi'),
(8, 'Fadagi Supplier Daging Sapi'),
(9, 'Cahaya Putera Sejati'),
(10, 'Suri Nusantara Jaya'),
(11, 'Hijahfood Group'),
(12, 'Meat Hunter'),
(13, 'PT. Berkat Pangan Sejahtera Indonesia'),
(14, 'Sentosa Jaya Meat'),
(15, 'Puri Pangan Utama'),
(16, 'RUM Seafood'),
(17, 'CV. Karunia Abadi Semesta'),
(18, 'PT. Jaya Utama Santikah'),
(19, 'CV. Lemooin Jaya Abadi'),
(20, 'PT. Anugrah Abadi Bersaudara'),
(21, 'CV. Karya Bangun Sejati'),
(22, 'CV. Radjasa Dwijaya'),
(23, 'CV. Adhi Creative'),
(24, 'CV. Jati Sunda'),
(25, 'PT. Karya Citra Anugrah'),
(26, 'PT. Sukses Berniaga Mandiri'),
(27, 'PT. Tropical Mitra Nutrisi'),
(28, 'UD. Tripatri Banten'),
(29, 'UD. Hiban Industry Groups'),
(30, 'PT. Reydika Raya Utama'),
(31, 'PT. Seva Nusa Sinergi'),
(32, 'B2B Food Solution'),
(33, 'PT Sumber Makanan Sehat'),
(34, 'PT. Mitra Sarana Globalindo'),
(35, 'CV. Indo Kartawijaya'),
(36, 'PT. Global Anugrah Kuasa'),
(37, 'PD. Karya Mitra Usaha'),
(38, 'CV. Citresna Nuasih'),
(39, 'CV. Ahada Putra'),
(40, 'CV. Rizaly Pratama'),
(41, 'CV. KAMALA'),
(42, 'PT. Cipta Gemilang Abadi'),
(43, 'CV. Albantany Group Agen Daging Frash & Frozen'),
(44, 'PT. Ammar Pasifik Indonesia'),
(45, 'Spesialis Frozen'),
(46, 'Dagingnesia Bogor');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kriteria`
--

CREATE TABLE `tbl_kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `nama_kriteria` varchar(50) NOT NULL,
  `bobot_kriteria` double NOT NULL,
  `tipe_kriteria` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_kriteria`
--

INSERT INTO `tbl_kriteria` (`id_kriteria`, `nama_kriteria`, `bobot_kriteria`, `tipe_kriteria`) VALUES
(6, 'Harga', 0.4, 'Cost'),
(7, 'Kualitas', 0.3, 'Benefit'),
(8, 'Pelayanan', 0.2, 'Benefit'),
(10, 'Lokasi', 0.1, 'Benefit');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_penilaian`
--

CREATE TABLE `tbl_penilaian` (
  `id_penilaian` int(11) NOT NULL,
  `nilai_awal` double NOT NULL,
  `nilai_utility` double DEFAULT NULL,
  `id_alternatif` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_penilaian`
--

INSERT INTO `tbl_penilaian` (`id_penilaian`, `nilai_awal`, `nilai_utility`, `id_alternatif`, `id_kriteria`) VALUES
(143, 75, 0.2777777777777778, 3, 6),
(144, 100, 1, 3, 7),
(145, 50, 0.4444444444444444, 3, 8),
(146, 50, 0.4444444444444444, 3, 10),
(147, 100, 0, 4, 6),
(148, 50, 0.4444444444444444, 4, 7),
(149, 50, 0.4444444444444444, 4, 8),
(150, 50, 0.4444444444444444, 4, 10),
(151, 75, 0.2777777777777778, 5, 6),
(152, 50, 0.4444444444444444, 5, 7),
(153, 50, 0.4444444444444444, 5, 8),
(154, 50, 0.4444444444444444, 5, 10),
(155, 75, 0.2777777777777778, 6, 6),
(156, 50, 0.4444444444444444, 6, 7),
(157, 100, 1, 6, 8),
(158, 50, 0.4444444444444444, 6, 10),
(159, 10, 1, 7, 6),
(160, 100, 1, 7, 7),
(161, 50, 0.4444444444444444, 7, 8),
(162, 100, 1, 7, 10),
(163, 25, 0.8333333333333334, 8, 6),
(164, 100, 1, 8, 7),
(165, 10, 0, 8, 8),
(166, 75, 0.7222222222222222, 8, 10),
(167, 25, 0.8333333333333334, 9, 6),
(168, 100, 1, 9, 7),
(169, 10, 0, 9, 8),
(170, 50, 0.4444444444444444, 9, 10),
(171, 75, 0.2777777777777778, 10, 6),
(172, 10, 0, 10, 7),
(173, 10, 0, 10, 8),
(174, 75, 0.7222222222222222, 10, 10),
(175, 25, 0.8333333333333334, 11, 6),
(176, 10, 0, 11, 7),
(177, 10, 0, 11, 8),
(178, 100, 1, 11, 10),
(179, 100, 0, 12, 6),
(180, 50, 0.4444444444444444, 12, 7),
(181, 50, 0.4444444444444444, 12, 8),
(182, 100, 1, 12, 10),
(183, 10, 1, 13, 6),
(184, 10, 0, 13, 7),
(185, 10, 0, 13, 8),
(186, 75, 0.7222222222222222, 13, 10),
(187, 10, 1, 14, 6),
(188, 100, 1, 14, 7),
(189, 50, 0.4444444444444444, 14, 8),
(190, 100, 1, 14, 10),
(191, 10, 1, 15, 6),
(192, 50, 0.4444444444444444, 15, 7),
(193, 10, 0, 15, 8),
(194, 75, 0.7222222222222222, 15, 10),
(195, 100, 0, 16, 6),
(196, 10, 0, 16, 7),
(197, 10, 0, 16, 8),
(198, 50, 0.4444444444444444, 16, 10),
(199, 100, 0, 17, 6),
(200, 100, 1, 17, 7),
(201, 10, 0, 17, 8),
(202, 75, 0.7222222222222222, 17, 10),
(203, 100, 0, 18, 6),
(204, 50, 0.4444444444444444, 18, 7),
(205, 10, 0, 18, 8),
(206, 100, 1, 18, 10),
(207, 75, 0.2777777777777778, 19, 6),
(208, 100, 1, 19, 7),
(209, 100, 1, 19, 8),
(210, 75, 0.7222222222222222, 19, 10),
(211, 100, 0, 20, 6),
(212, 50, 0.4444444444444444, 20, 7),
(213, 10, 0, 20, 8),
(214, 75, 0.7222222222222222, 20, 10),
(215, 25, 0.8333333333333334, 21, 6),
(216, 50, 0.4444444444444444, 21, 7),
(217, 100, 1, 21, 8),
(218, 50, 0.4444444444444444, 21, 10),
(219, 100, 0, 22, 6),
(220, 25, 0.16666666666666666, 22, 7),
(221, 10, 0, 22, 8),
(222, 75, 0.7222222222222222, 22, 10),
(223, 75, 0.2777777777777778, 23, 6),
(224, 100, 1, 23, 7),
(225, 100, 1, 23, 8),
(226, 100, 1, 23, 10),
(227, 10, 1, 24, 6),
(228, 50, 0.4444444444444444, 24, 7),
(229, 10, 0, 24, 8),
(230, 75, 0.7222222222222222, 24, 10),
(231, 25, 0.8333333333333334, 25, 6),
(232, 25, 0.16666666666666666, 25, 7),
(233, 100, 1, 25, 8),
(234, 100, 1, 25, 10),
(235, 10, 1, 26, 6),
(236, 50, 0.4444444444444444, 26, 7),
(237, 10, 0, 26, 8),
(238, 75, 0.7222222222222222, 26, 10),
(239, 100, 0, 27, 6),
(240, 100, 1, 27, 7),
(241, 10, 0, 27, 8),
(242, 75, 0.7222222222222222, 27, 10),
(243, 25, 0.8333333333333334, 28, 6),
(244, 50, 0.4444444444444444, 28, 7),
(245, 10, 0, 28, 8),
(246, 50, 0.4444444444444444, 28, 10),
(247, 75, 0.2777777777777778, 29, 6),
(248, 25, 0.16666666666666666, 29, 7),
(249, 50, 0.4444444444444444, 29, 8),
(250, 75, 0.7222222222222222, 29, 10),
(251, 10, 1, 30, 6),
(252, 100, 1, 30, 7),
(253, 50, 0.4444444444444444, 30, 8),
(254, 50, 0.4444444444444444, 30, 10),
(255, 100, 0, 31, 6),
(256, 50, 0.4444444444444444, 31, 7),
(257, 10, 0, 31, 8),
(258, 75, 0.7222222222222222, 31, 10),
(259, 100, 0, 32, 6),
(260, 100, 1, 32, 7),
(261, 10, 0, 32, 8),
(262, 10, 0, 32, 10),
(263, 75, 0.2777777777777778, 33, 6),
(264, 50, 0.4444444444444444, 33, 7),
(265, 10, 0, 33, 8),
(266, 100, 1, 33, 10),
(267, 75, 0.2777777777777778, 34, 6),
(268, 100, 1, 34, 7),
(269, 50, 0.4444444444444444, 34, 8),
(270, 25, 0.16666666666666666, 34, 10),
(271, 75, 0.2777777777777778, 35, 6),
(272, 100, 1, 35, 7),
(273, 50, 0.4444444444444444, 35, 8),
(274, 25, 0.16666666666666666, 35, 10),
(275, 10, 1, 36, 6),
(276, 50, 0.4444444444444444, 36, 7),
(277, 50, 0.4444444444444444, 36, 8),
(278, 25, 0.16666666666666666, 36, 10),
(279, 100, 0, 37, 6),
(280, 100, 1, 37, 7),
(281, 10, 0, 37, 8),
(282, 75, 0.7222222222222222, 37, 10),
(283, 100, 0, 38, 6),
(284, 25, 0.16666666666666666, 38, 7),
(285, 100, 1, 38, 8),
(286, 50, 0.4444444444444444, 38, 10),
(287, 25, 0.8333333333333334, 39, 6),
(288, 50, 0.4444444444444444, 39, 7),
(289, 100, 1, 39, 8),
(290, 50, 0.4444444444444444, 39, 10),
(291, 25, 0.8333333333333334, 40, 6),
(292, 100, 1, 40, 7),
(293, 10, 0, 40, 8),
(294, 25, 0.16666666666666666, 40, 10),
(295, 100, 0, 41, 6),
(296, 25, 0.16666666666666666, 41, 7),
(297, 100, 1, 41, 8),
(298, 25, 0.16666666666666666, 41, 10),
(299, 10, 1, 42, 6),
(300, 50, 0.4444444444444444, 42, 7),
(301, 10, 0, 42, 8),
(302, 25, 0.16666666666666666, 42, 10),
(303, 75, 0.2777777777777778, 43, 6),
(304, 50, 0.4444444444444444, 43, 7),
(305, 50, 0.4444444444444444, 43, 8),
(306, 50, 0.4444444444444444, 43, 10),
(307, 100, 0, 44, 6),
(308, 25, 0.16666666666666666, 44, 7),
(309, 50, 0.4444444444444444, 44, 8),
(310, 100, 1, 44, 10),
(311, 100, 0, 45, 6),
(312, 100, 1, 45, 7),
(313, 100, 1, 45, 8),
(314, 100, 1, 45, 10),
(315, 100, 0, 46, 6),
(316, 50, 0.4444444444444444, 46, 7),
(317, 10, 0, 46, 8),
(318, 100, 1, 46, 10);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_perangkingan`
--

CREATE TABLE `tbl_perangkingan` (
  `id_perangkingan` int(11) NOT NULL,
  `total_perhitungan` double NOT NULL,
  `keterangan` varchar(25) NOT NULL,
  `id_alternatif` int(11) NOT NULL,
  `id_penilaian` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sub_kriteria`
--

CREATE TABLE `tbl_sub_kriteria` (
  `id_sub_kriteria` int(11) NOT NULL,
  `nama_sub_kriteria` varchar(50) NOT NULL,
  `nilai_sub_kriteria` double NOT NULL,
  `id_kriteria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_sub_kriteria`
--

INSERT INTO `tbl_sub_kriteria` (`id_sub_kriteria`, `nama_sub_kriteria`, `nilai_sub_kriteria`, `id_kriteria`) VALUES
(10, 'Mahal', 10, 6),
(11, 'Murah', 100, 6),
(12, 'Bagus', 100, 7),
(13, 'buruk', 10, 7),
(14, 'baik', 100, 8),
(15, 'buruk', 10, 8),
(16, 'Jauh', 20, 10),
(17, 'Dekat', 100, 10),
(18, '75', 75, 6),
(19, '25', 25, 6),
(20, '50', 50, 6),
(21, '50', 50, 7),
(22, '25', 25, 7),
(23, '75', 75, 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `tbl_alternatif`
--
ALTER TABLE `tbl_alternatif`
  ADD PRIMARY KEY (`id_alternatif`);

--
-- Indexes for table `tbl_kriteria`
--
ALTER TABLE `tbl_kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `tbl_penilaian`
--
ALTER TABLE `tbl_penilaian`
  ADD PRIMARY KEY (`id_penilaian`),
  ADD UNIQUE KEY `unique_alternatif_kriteria` (`id_alternatif`,`id_kriteria`),
  ADD KEY `FK_alternatif` (`id_alternatif`),
  ADD KEY `FK_kriteria` (`id_kriteria`);

--
-- Indexes for table `tbl_perangkingan`
--
ALTER TABLE `tbl_perangkingan`
  ADD PRIMARY KEY (`id_perangkingan`),
  ADD KEY `FK_penilaian` (`id_penilaian`),
  ADD KEY `FK_alternatif2` (`id_alternatif`);

--
-- Indexes for table `tbl_sub_kriteria`
--
ALTER TABLE `tbl_sub_kriteria`
  ADD PRIMARY KEY (`id_sub_kriteria`),
  ADD KEY `FK_kriteria2` (`id_kriteria`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_alternatif`
--
ALTER TABLE `tbl_alternatif`
  MODIFY `id_alternatif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `tbl_kriteria`
--
ALTER TABLE `tbl_kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_penilaian`
--
ALTER TABLE `tbl_penilaian`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=319;

--
-- AUTO_INCREMENT for table `tbl_perangkingan`
--
ALTER TABLE `tbl_perangkingan`
  MODIFY `id_perangkingan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_sub_kriteria`
--
ALTER TABLE `tbl_sub_kriteria`
  MODIFY `id_sub_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_penilaian`
--
ALTER TABLE `tbl_penilaian`
  ADD CONSTRAINT `FK_alternatif` FOREIGN KEY (`id_alternatif`) REFERENCES `tbl_alternatif` (`id_alternatif`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_kriteria` FOREIGN KEY (`id_kriteria`) REFERENCES `tbl_kriteria` (`id_kriteria`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_perangkingan`
--
ALTER TABLE `tbl_perangkingan`
  ADD CONSTRAINT `FK_alternatif2` FOREIGN KEY (`id_alternatif`) REFERENCES `tbl_alternatif` (`id_alternatif`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_penilaian` FOREIGN KEY (`id_penilaian`) REFERENCES `tbl_penilaian` (`id_penilaian`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_sub_kriteria`
--
ALTER TABLE `tbl_sub_kriteria`
  ADD CONSTRAINT `FK_kriteria2` FOREIGN KEY (`id_kriteria`) REFERENCES `tbl_kriteria` (`id_kriteria`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
