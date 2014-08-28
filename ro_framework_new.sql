-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Waktu pembuatan: 28. Agustus 2014 jam 12:59
-- Versi Server: 5.5.16
-- Versi PHP: 5.5.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ro_framework_new`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `ro_action`
--

CREATE TABLE IF NOT EXISTS `ro_action` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `nama` varchar(125) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=63 ;

--
-- Dumping data untuk tabel `ro_action`
--

INSERT INTO `ro_action` (`id`, `nama`) VALUES
(51, 'actionIndex'),
(52, 'actionLog'),
(53, 'actionAdmin'),
(54, 'actionListAccess'),
(55, 'actionChangeAccess'),
(56, 'actionRefreshAccess'),
(57, 'actionAddTypeUser'),
(58, 'actionDeleteTypeUser'),
(59, 'actionChangeRedirect'),
(60, 'actionDeleteAccess'),
(61, 'actionUpdateTypeUser'),
(62, 'actionLogout');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ro_akses`
--

CREATE TABLE IF NOT EXISTS `ro_akses` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_tipe_user` int(3) NOT NULL,
  `id_controller` int(3) NOT NULL,
  `id_action` int(3) NOT NULL,
  `akses` enum('Y','N') NOT NULL,
  `redirect` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_action` (`id_action`),
  KEY `fk_tipe_user` (`id_tipe_user`),
  KEY `fk_controller` (`id_controller`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=371 ;

--
-- Dumping data untuk tabel `ro_akses`
--

INSERT INTO `ro_akses` (`id`, `id_tipe_user`, `id_controller`, `id_action`, `akses`, `redirect`) VALUES
(356, 7, 8, 51, 'Y', 3),
(357, 7, 9, 51, 'Y', 3),
(358, 7, 9, 52, 'Y', 3),
(359, 7, 9, 53, 'Y', 3),
(360, 7, 9, 54, 'Y', 3),
(361, 7, 9, 55, 'Y', 3),
(362, 7, 9, 56, 'Y', 3),
(363, 7, 9, 57, 'Y', 3),
(364, 7, 9, 58, 'Y', 3),
(365, 7, 9, 59, 'Y', 3),
(366, 7, 9, 60, 'Y', 3),
(367, 7, 9, 61, 'Y', 3),
(368, 7, 9, 62, 'Y', 3),
(369, 8, 9, 51, 'Y', 3),
(370, 8, 8, 51, 'Y', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ro_controller`
--

CREATE TABLE IF NOT EXISTS `ro_controller` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `nama` varchar(75) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data untuk tabel `ro_controller`
--

INSERT INTO `ro_controller` (`id`, `nama`) VALUES
(8, 'webController'),
(9, 'widget_admin_superAdmin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ro_log`
--

CREATE TABLE IF NOT EXISTS `ro_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `aktivitas` int(3) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ro_tipe_user`
--

CREATE TABLE IF NOT EXISTS `ro_tipe_user` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `nama` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama` (`nama`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data untuk tabel `ro_tipe_user`
--

INSERT INTO `ro_tipe_user` (`id`, `nama`) VALUES
(8, 'guest'),
(7, 'superAdmin');

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `ro_akses`
--
ALTER TABLE `ro_akses`
  ADD CONSTRAINT `fk_action` FOREIGN KEY (`id_action`) REFERENCES `ro_action` (`id`),
  ADD CONSTRAINT `fk_controller` FOREIGN KEY (`id_controller`) REFERENCES `ro_controller` (`id`),
  ADD CONSTRAINT `fk_tipe_user` FOREIGN KEY (`id_tipe_user`) REFERENCES `ro_tipe_user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
