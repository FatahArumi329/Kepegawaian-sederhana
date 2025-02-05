-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2025 at 04:16 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kepegawaian`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_all_pegawai` ()   BEGIN
    SELECT p.id_pegawai, p.nama, p.alamat, p.email, p.tgl_masuk, d.nama_departemen
    FROM pegawai p
    LEFT JOIN departemen d ON p.id_departemen = d.id_departemen;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `departemen`
--

CREATE TABLE `departemen` (
  `id_departemen` int(11) NOT NULL,
  `nama_departemen` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departemen`
--

INSERT INTO `departemen` (`id_departemen`, `nama_departemen`) VALUES
(1, 'HRD'),
(2, 'IT'),
(3, 'Finance');

-- --------------------------------------------------------

--
-- Table structure for table `log_pegawai`
--

CREATE TABLE `log_pegawai` (
  `log_id` int(11) NOT NULL,
  `aksi` varchar(50) DEFAULT NULL,
  `id_pegawai` int(11) DEFAULT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_pegawai`
--

INSERT INTO `log_pegawai` (`log_id`, `aksi`, `id_pegawai`, `waktu`) VALUES
(1, 'INSERT', 16, '2025-02-05 01:38:20'),
(2, 'DELETE', 16, '2025-02-05 01:50:23'),
(3, 'INSERT', 17, '2025-02-05 01:50:39'),
(4, 'UPDATE', 17, '2025-02-05 01:51:07'),
(5, 'UPDATE', 17, '2025-02-05 01:52:00'),
(6, 'INSERT', 18, '2025-02-05 01:54:38'),
(7, 'UPDATE', 18, '2025-02-05 02:03:22'),
(8, 'DELETE', 17, '2025-02-05 03:15:52'),
(9, 'DELETE', 18, '2025-02-05 03:15:54');

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tgl_masuk` date DEFAULT NULL,
  `id_departemen` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `pegawai`
--
DELIMITER $$
CREATE TRIGGER `trg_after_delete_pegawai` AFTER DELETE ON `pegawai` FOR EACH ROW BEGIN
    INSERT INTO log_pegawai (aksi, id_pegawai) VALUES ('DELETE', OLD.id_pegawai);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_after_insert_pegawai` AFTER INSERT ON `pegawai` FOR EACH ROW BEGIN
    INSERT INTO log_pegawai (aksi, id_pegawai) VALUES ('INSERT', NEW.id_pegawai);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_after_update_pegawai` AFTER UPDATE ON `pegawai` FOR EACH ROW BEGIN
    INSERT INTO log_pegawai (aksi, id_pegawai) VALUES ('UPDATE', NEW.id_pegawai);
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departemen`
--
ALTER TABLE `departemen`
  ADD PRIMARY KEY (`id_departemen`);

--
-- Indexes for table `log_pegawai`
--
ALTER TABLE `log_pegawai`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`),
  ADD KEY `fk_departemen` (`id_departemen`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departemen`
--
ALTER TABLE `departemen`
  MODIFY `id_departemen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `log_pegawai`
--
ALTER TABLE `log_pegawai`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `fk_departemen` FOREIGN KEY (`id_departemen`) REFERENCES `departemen` (`id_departemen`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
