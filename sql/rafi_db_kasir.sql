-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 14, 2024 at 03:57 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rafi_db_kasir`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `lihat_barang`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `lihat_barang` ()   SELECT * FROM rafi_barang$$

DROP PROCEDURE IF EXISTS `lihat_detail_trans`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `lihat_detail_trans` (IN `transaksi_id` VARCHAR(20))   BEGIN
    SELECT 
        rt.rafi_id_transaksi AS NoPesanan,
        ru.rafi_username AS PetugasMelayani,
        SUM(rdt.rafi_total_harga) AS TotalPembelian,
        rt.rafi_total_keseluruhan AS TotalTunai,
        rt.rafi_total_kembalian AS TotalKembalian,
        rt.rafi_date_transaksi AS WaktuPembelian
    FROM 
        rafi_transaksi rt
    JOIN rafi_detail_transaksi rdt ON rt.rafi_id_transaksi = rdt.rafi_id_transaksi
    JOIN rafi_users ru ON rt.rafi_id_users = ru.rafi_id_users
    WHERE 
        rt.rafi_id_transaksi = transaksi_id
    GROUP BY 
        rt.rafi_id_transaksi;

    SELECT 
        rb.rafi_nama_barang AS NamaBarang,
        rdt.rafi_jumlah_barang AS Jumlah,
        rb.rafi_harga_barang AS HargaBarang,
        (rdt.rafi_jumlah_barang * rb.rafi_harga_barang) AS Total
    FROM 
        rafi_detail_transaksi rdt
    JOIN rafi_barang rb ON rdt.rafi_id_barang = rb.rafi_id_barang
    WHERE 
        rdt.rafi_id_transaksi = transaksi_id;
END$$

DROP PROCEDURE IF EXISTS `lihat_trans`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `lihat_trans` ()   SELECT 
        t.rafi_id_transaksi AS id_transaksi,
        COUNT(dt.rafi_id_barang) AS jumlah_pesanan,
        SUM(dt.rafi_total_harga) AS total_belanja,
        u.rafi_username AS petugas_melayani,
        t.rafi_date_transaksi AS waktu_transaksi
    FROM rafi_transaksi t
    INNER JOIN rafi_detail_transaksi dt ON t.rafi_id_transaksi = dt.rafi_id_transaksi
    INNER JOIN rafi_users u ON t.rafi_id_users = u.rafi_id_users
    GROUP BY t.rafi_id_transaksi
    ORDER BY t.rafi_date_transaksi, t.rafi_id_transaksi$$

DROP PROCEDURE IF EXISTS `lihat_users`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `lihat_users` ()   SELECT * FROM rafi_users$$

DROP PROCEDURE IF EXISTS `login`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `login` (IN `username` VARCHAR(225), IN `password` VARCHAR(225))   SELECT * FROM rafi_users WHERE rafi_username = username AND rafi_password = password$$

DROP PROCEDURE IF EXISTS `search_barang_by_name`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `search_barang_by_name` (IN `search_name` VARCHAR(225))   BEGIN
    SELECT 
        rafi_id_barang,
        rafi_nama_barang,
        rafi_jumlah_barang,
        rafi_harga_barang,
        rafi_dateadd_barang,
        rafi_dateupdate_barang
    FROM 
        rafi_barang 
    WHERE 
        rafi_nama_barang OR rafi_id_barang LIKE CONCAT('%', search_name, '%');
END$$

DROP PROCEDURE IF EXISTS `search_riwayat_transaksi_by_date`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `search_riwayat_transaksi_by_date` (IN `start_date` DATETIME, IN `end_date` DATETIME)   BEGIN
    SELECT 
        rt.rafi_id_transaksi AS id_transaksi,
        COUNT(rdt.rafi_id_barang) AS jumlah_pesanan,
        SUM(rdt.rafi_total_harga) AS total_belanja,
        ru.rafi_username AS petugas_melayani,
        rt.rafi_date_transaksi AS waktu_transaksi
    FROM 
        rafi_transaksi rt
    INNER JOIN 
        rafi_users ru ON rt.rafi_id_users = ru.rafi_id_users
    INNER JOIN 
        rafi_detail_transaksi rdt ON rt.rafi_id_transaksi = rdt.rafi_id_transaksi
    INNER JOIN 
        rafi_barang rb ON rdt.rafi_id_barang = rb.rafi_id_barang
    WHERE 
        rt.rafi_date_transaksi BETWEEN start_date AND end_date
    GROUP BY 
        rt.rafi_id_transaksi, ru.rafi_username, rt.rafi_date_transaksi
    ORDER BY 
        rt.rafi_date_transaksi, rt.rafi_id_transaksi;
END$$

DROP PROCEDURE IF EXISTS `sp_generate_laporan_bulanan`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_generate_laporan_bulanan` (IN `_year` INT, IN `_month` INT)   BEGIN
    SELECT 
        rt.rafi_id_transaksi, 
        rd.rafi_id_barang, 
        rb.rafi_nama_barang, 
        rb.rafi_harga_barang, 
        rd.rafi_jumlah_barang, 
        (rb.rafi_harga_barang * rd.rafi_jumlah_barang) AS subtotal,
        rt.rafi_total_keseluruhan, 
        rt.rafi_total_pembayaran, 
        rt.rafi_total_kembalian, 
        ru.rafi_username, 
        rt.rafi_date_transaksi
    FROM rafi_transaksi rt
    INNER JOIN rafi_detail_transaksi rd ON rt.rafi_id_transaksi = rd.rafi_id_transaksi
    INNER JOIN rafi_barang rb ON rd.rafi_id_barang = rb.rafi_id_barang
    INNER JOIN rafi_users ru ON rt.rafi_id_users = ru.rafi_id_users
    WHERE YEAR(rt.rafi_date_transaksi) = _year AND MONTH(rt.rafi_date_transaksi) = _month
    ORDER BY rt.rafi_id_transaksi, rd.rafi_id_barang;
END$$

DROP PROCEDURE IF EXISTS `sp_generate_laporan_custom`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_generate_laporan_custom` (IN `start_date` DATETIME, IN `end_date` DATETIME)   BEGIN
    SELECT 
        rt.rafi_id_transaksi, 
        rd.rafi_id_barang, 
        rb.rafi_nama_barang, 
        rb.rafi_harga_barang, 
        rd.rafi_jumlah_barang, 
        (rb.rafi_harga_barang * rd.rafi_jumlah_barang) AS subtotal,
        rt.rafi_total_keseluruhan, 
        rt.rafi_total_pembayaran, 
        rt.rafi_total_kembalian, 
        ru.rafi_username, 
        rt.rafi_date_transaksi
    FROM rafi_transaksi rt
    INNER JOIN rafi_detail_transaksi rd ON rt.rafi_id_transaksi = rd.rafi_id_transaksi
    INNER JOIN rafi_barang rb ON rd.rafi_id_barang = rb.rafi_id_barang
    INNER JOIN rafi_users ru ON rt.rafi_id_users = ru.rafi_id_users
    WHERE rt.rafi_date_transaksi BETWEEN start_date AND end_date
    ORDER BY rt.rafi_date_transaksi, rt.rafi_id_transaksi;
END$$

DROP PROCEDURE IF EXISTS `sp_generate_laporan_harian`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_generate_laporan_harian` (IN `_date` DATE)   BEGIN
    SELECT 
        rt.rafi_id_transaksi, 
        rd.rafi_id_barang, 
        rb.rafi_nama_barang, 
        rb.rafi_harga_barang, 
        rd.rafi_jumlah_barang, 
        (rb.rafi_harga_barang * rd.rafi_jumlah_barang) AS subtotal,
        rt.rafi_total_keseluruhan, 
        rt.rafi_total_pembayaran, 
        rt.rafi_total_kembalian, 
        ru.rafi_username, 
        rt.rafi_date_transaksi
    FROM rafi_transaksi rt 
    INNER JOIN rafi_detail_transaksi rd ON rt.rafi_id_transaksi = rd.rafi_id_transaksi
    INNER JOIN rafi_barang rb ON rd.rafi_id_barang = rb.rafi_id_barang
    INNER JOIN rafi_users ru ON rt.rafi_id_users = ru.rafi_id_users
    WHERE DATE(rt.rafi_date_transaksi) = _date
    ORDER BY rt.rafi_id_transaksi, rd.rafi_id_barang; 
END$$

DROP PROCEDURE IF EXISTS `sp_generate_laporan_tahunan`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_generate_laporan_tahunan` (IN `_year` INT)   BEGIN
    SELECT 
        rt.rafi_id_transaksi, 
        rd.rafi_id_barang, 
        rb.rafi_nama_barang, 
        rb.rafi_harga_barang, 
        rd.rafi_jumlah_barang, 
        (rb.rafi_harga_barang * rd.rafi_jumlah_barang) AS subtotal,
        rt.rafi_total_keseluruhan, 
        rt.rafi_total_pembayaran, 
        rt.rafi_total_kembalian, 
        ru.rafi_username, 
        rt.rafi_date_transaksi
    FROM rafi_transaksi rt
    INNER JOIN rafi_detail_transaksi rd ON rt.rafi_id_transaksi = rd.rafi_id_transaksi
    INNER JOIN rafi_barang rb ON rd.rafi_id_barang = rb.rafi_id_barang
    INNER JOIN rafi_users ru ON rt.rafi_id_users = ru.rafi_id_users
    WHERE YEAR(rt.rafi_date_transaksi) = _year
    ORDER BY rt.rafi_id_transaksi, rd.rafi_id_barang;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `rafi_barang`
--

DROP TABLE IF EXISTS `rafi_barang`;
CREATE TABLE IF NOT EXISTS `rafi_barang` (
  `rafi_id_barang` varchar(9) NOT NULL,
  `rafi_nama_barang` varchar(225) NOT NULL,
  `rafi_jumlah_barang` int NOT NULL,
  `rafi_harga_barang` int NOT NULL,
  `rafi_dateadd_barang` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rafi_dateupdate_barang` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rafi_id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rafi_barang`
--

INSERT INTO `rafi_barang` (`rafi_id_barang`, `rafi_nama_barang`, `rafi_jumlah_barang`, `rafi_harga_barang`, `rafi_dateadd_barang`, `rafi_dateupdate_barang`) VALUES
('BRG001', 'Minyak Lintah', 60, 10000, '2024-04-14 15:10:18', '2024-04-14 15:54:37'),
('BRG002', 'Kecap', 50, 10000, '2024-04-14 15:10:31', '2024-04-14 15:22:54');

-- --------------------------------------------------------

--
-- Table structure for table `rafi_detail_transaksi`
--

DROP TABLE IF EXISTS `rafi_detail_transaksi`;
CREATE TABLE IF NOT EXISTS `rafi_detail_transaksi` (
  `rafi_id_detailtransaksi` int NOT NULL AUTO_INCREMENT,
  `rafi_id_transaksi` varchar(225) NOT NULL,
  `rafi_id_barang` varchar(9) NOT NULL,
  `rafi_jumlah_barang` int NOT NULL,
  `rafi_total_harga` int NOT NULL,
  PRIMARY KEY (`rafi_id_detailtransaksi`),
  KEY `rafi_id_barang` (`rafi_id_barang`),
  KEY `rafi_id_transaksi` (`rafi_id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rafi_detail_transaksi`
--

INSERT INTO `rafi_detail_transaksi` (`rafi_id_detailtransaksi`, `rafi_id_transaksi`, `rafi_id_barang`, `rafi_jumlah_barang`, `rafi_total_harga`) VALUES
(1, 'S58OY0120240414HHP', 'BRG001', 10, 100000),
(2, 'S58OY0120240414HHP', 'BRG002', 10, 100000),
(3, 'H00YO0120240414ZBX', 'BRG001', 5, 50000),
(4, 'A48OQ0120240414WBO', 'BRG001', 5, 50000),
(5, 'A48OQ0120240414WBO', 'BRG002', 10, 100000),
(6, 'I26NO0120240414MIE', 'BRG001', 5, 50000),
(7, 'Q67WX0120240414MFJ', 'BRG002', 30, 300000),
(8, 'Y92MS0120240414VNN', 'BRG001', 10, 100000),
(9, 'X32KG0120240414HSX', 'BRG001', 5, 50000);

--
-- Triggers `rafi_detail_transaksi`
--
DROP TRIGGER IF EXISTS `update_stok`;
DELIMITER $$
CREATE TRIGGER `update_stok` AFTER INSERT ON `rafi_detail_transaksi` FOR EACH ROW BEGIN 
    UPDATE rafi_barang 
    SET rafi_jumlah_barang = rafi_jumlah_barang - NEW.rafi_jumlah_barang 
    WHERE rafi_id_barang = NEW.rafi_id_barang;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `rafi_transaksi`
--

DROP TABLE IF EXISTS `rafi_transaksi`;
CREATE TABLE IF NOT EXISTS `rafi_transaksi` (
  `rafi_id_transaksi` varchar(255) NOT NULL,
  `rafi_id_users` int NOT NULL,
  `rafi_total_keseluruhan` int NOT NULL,
  `rafi_total_pembayaran` int DEFAULT NULL,
  `rafi_total_kembalian` int DEFAULT NULL,
  `rafi_date_transaksi` datetime NOT NULL,
  PRIMARY KEY (`rafi_id_transaksi`),
  KEY `rafi_id_users` (`rafi_id_users`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rafi_transaksi`
--

INSERT INTO `rafi_transaksi` (`rafi_id_transaksi`, `rafi_id_users`, `rafi_total_keseluruhan`, `rafi_total_pembayaran`, `rafi_total_kembalian`, `rafi_date_transaksi`) VALUES
('A48OQ0120240414WBO', 20, 150000, 200000, 50000, '2022-04-14 22:22:09'),
('H00YO0120240414ZBX', 20, 50000, 100000, 50000, '2023-04-14 22:21:49'),
('I26NO0120240414MIE', 20, 50000, 50000, 0, '2024-03-14 22:22:25'),
('Q67WX0120240414MFJ', 20, 300000, 300000, 0, '2024-04-12 22:22:54'),
('S58OY0120240414HHP', 20, 200000, 200000, 0, '2024-04-14 22:20:31'),
('X32KG0120240414HSX', 22, 50000, 100000, 50000, '2024-04-14 22:54:37'),
('Y92MS0120240414VNN', 24, 100000, 100000, 0, '2024-04-14 22:48:23');

-- --------------------------------------------------------

--
-- Table structure for table `rafi_users`
--

DROP TABLE IF EXISTS `rafi_users`;
CREATE TABLE IF NOT EXISTS `rafi_users` (
  `rafi_id_users` int NOT NULL AUTO_INCREMENT,
  `rafi_username` varchar(225) NOT NULL,
  `rafi_password` varchar(225) NOT NULL,
  `rafi_nama_lengkap` varchar(225) NOT NULL,
  `rafi_alamat` text NOT NULL,
  `rafi_role` enum('Administrator','Petugas') NOT NULL,
  `rafi_profile` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`rafi_id_users`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rafi_users`
--

INSERT INTO `rafi_users` (`rafi_id_users`, `rafi_username`, `rafi_password`, `rafi_nama_lengkap`, `rafi_alamat`, `rafi_role`, `rafi_profile`) VALUES
(1, 'Rafi', '21232f297a57a5a743894a0e4a801fc3', 'Muhammad Rafi Sanjaya', 'Jl. Dra. Hj. Djulaeha Karmita No.5, Cimahi, Kec. Cimahi Tengah, Kota Cimahi, Jawa Barat 40525', 'Administrator', '1713107094_th.jpg'),
(20, 'Sandra ', 'afb91ef692fd08c445e8cb1bab2ccf9c', 'Sandra Dewi', 'Gg. H. Sarin Mahi, Pabuaran, Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16916', 'Petugas', '1713110151_default.jpg'),
(21, 'Milo', '21232f297a57a5a743894a0e4a801fc3', 'Milo Coklat', 'Gg. H. Sarin Mahi, Pabuaran, Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16916', 'Administrator', '1713107920_Adolf-hitler.jpg'),
(22, 'Pataland', 'afb91ef692fd08c445e8cb1bab2ccf9c', 'Pataland Muharohmah', 'Gg. H. Sarin Mahi, Pabuaran, Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16916', 'Petugas', '1713110191_Adolf-hitler.jpg'),
(24, 'Casancra', 'afb91ef692fd08c445e8cb1bab2ccf9c', 'Casancra Parfum', 'Gg. H. Sarin Mahi, Pabuaran, Kec. Cibinong, Kabupaten Bogor, Jawa Barat 16916', 'Petugas', '1713109677_th (1).jpg');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rafi_detail_transaksi`
--
ALTER TABLE `rafi_detail_transaksi`
  ADD CONSTRAINT `rafi_detail_transaksi_ibfk_3` FOREIGN KEY (`rafi_id_transaksi`) REFERENCES `rafi_transaksi` (`rafi_id_transaksi`),
  ADD CONSTRAINT `rafi_detail_transaksi_ibfk_4` FOREIGN KEY (`rafi_id_barang`) REFERENCES `rafi_barang` (`rafi_id_barang`) ON UPDATE CASCADE;

--
-- Constraints for table `rafi_transaksi`
--
ALTER TABLE `rafi_transaksi`
  ADD CONSTRAINT `rafi_transaksi_ibfk_1` FOREIGN KEY (`rafi_id_users`) REFERENCES `rafi_users` (`rafi_id_users`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
