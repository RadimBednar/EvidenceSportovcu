-- Adminer 4.7.9 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `akce`;
CREATE TABLE `akce` (
  `id_akce` int(3) NOT NULL AUTO_INCREMENT,
  `nazev_akce` varchar(30) NOT NULL,
  `datum_zahajeni` date NOT NULL,
  `datum_konce` date DEFAULT NULL,
  `misto_kon` varchar(100) NOT NULL,
  `poradatel` varchar(100) DEFAULT NULL,
  `popisek_akce` text NOT NULL,
  `pritomni_uc` varchar(70) DEFAULT NULL,
  `shrnuti` varchar(100) DEFAULT NULL,
  `archivovano` tinyint(1) DEFAULT NULL,
  `id_opak` int(3) DEFAULT NULL,
  `id_kolo` int(3) DEFAULT NULL,
  PRIMARY KEY (`id_akce`),
  KEY `fk_akce_opak` (`id_opak`),
  KEY `fk_akce_kolo` (`id_kolo`),
  CONSTRAINT `akce_ibfk_2` FOREIGN KEY (`id_opak`) REFERENCES `opakovanost` (`id_opak`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `akce_ibfk_3` FOREIGN KEY (`id_kolo`) REFERENCES `kolo` (`id_kolo`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `akce_disc`;
CREATE TABLE `akce_disc` (
  `id_akce_disc` int(3) NOT NULL AUTO_INCREMENT,
  `id_akce` int(3) NOT NULL,
  `id_disc` int(3) NOT NULL,
  PRIMARY KEY (`id_akce_disc`),
  KEY `id_akce` (`id_akce`),
  KEY `id_disc` (`id_disc`),
  CONSTRAINT `akce_disc_ibfk_1` FOREIGN KEY (`id_akce`) REFERENCES `akce` (`id_akce`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `akce_disc_ibfk_2` FOREIGN KEY (`id_disc`) REFERENCES `disciplina` (`id_disc`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `disciplina`;
CREATE TABLE `disciplina` (
  `id_disc` int(3) NOT NULL AUTO_INCREMENT,
  `id_sport` int(3) NOT NULL,
  `nazev_disc` varchar(50) NOT NULL,
  PRIMARY KEY (`id_disc`),
  KEY `id_sport` (`id_sport`),
  CONSTRAINT `disciplina_ibfk_1` FOREIGN KEY (`id_sport`) REFERENCES `sport` (`id_sport`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `disciplina` (`id_disc`, `id_sport`, `nazev_disc`) VALUES
(1,	2,	'hokej'),
(2,	1,	'fotbal'),
(4,	1,	'volejbal'),
(5,	9,	'bench'),
(6,	1,	'dřep'),
(7,	1,	'deadlift');

DROP TABLE IF EXISTS `disc_ucast`;
CREATE TABLE `disc_ucast` (
  `id_disc_ucast` int(3) NOT NULL AUTO_INCREMENT,
  `id_ucast` int(3) NOT NULL,
  `id_disc` int(3) NOT NULL,
  PRIMARY KEY (`id_disc_ucast`),
  KEY `id_uzivsoup` (`id_ucast`),
  KEY `id_disc` (`id_disc`),
  CONSTRAINT `disc_ucast_ibfk_1` FOREIGN KEY (`id_ucast`) REFERENCES `ucastnik` (`id_ucast`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `disc_ucast_ibfk_2` FOREIGN KEY (`id_disc`) REFERENCES `akce_disc` (`id_disc`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `dodatecne_info`;
CREATE TABLE `dodatecne_info` (
  `email` varchar(50) NOT NULL,
  `komentar_uziv` text DEFAULT NULL,
  `kontaktni_udaje` varchar(50) DEFAULT NULL,
  `odkaz_na_web` varchar(225) DEFAULT NULL,
  `zdravotni_omezeni` text DEFAULT NULL,
  PRIMARY KEY (`email`),
  CONSTRAINT `dodatecne_info_ibfk_1` FOREIGN KEY (`email`) REFERENCES `uzivatel` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `kolo`;
CREATE TABLE `kolo` (
  `id_kolo` int(3) NOT NULL AUTO_INCREMENT,
  `nazev_kolo` varchar(30) NOT NULL,
  PRIMARY KEY (`id_kolo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kolo` (`id_kolo`, `nazev_kolo`) VALUES
(1,	'liga'),
(2,	'školní'),
(3,	'městské'),
(4,	'okresní'),
(5,	'krajské'),
(6,	'republikové'),
(8,	'mezinárodní');

DROP TABLE IF EXISTS `opakovanost`;
CREATE TABLE `opakovanost` (
  `id_opak` int(3) NOT NULL AUTO_INCREMENT,
  `nazev_opak` varchar(30) NOT NULL,
  PRIMARY KEY (`id_opak`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `opakovanost` (`id_opak`, `nazev_opak`) VALUES
(1,	'jednorázově'),
(2,	'čtvrtletně'),
(3,	'pololetně'),
(5,	'ročně');

DROP TABLE IF EXISTS `pozice`;
CREATE TABLE `pozice` (
  `id_poz` int(3) NOT NULL AUTO_INCREMENT,
  `nazev_poz` varchar(30) NOT NULL,
  PRIMARY KEY (`id_poz`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pozice` (`id_poz`, `nazev_poz`) VALUES
(1,	'levé křídlo'),
(2,	'pravé křídlo');

DROP TABLE IF EXISTS `soupiska`;
CREATE TABLE `soupiska` (
  `id_soup` int(3) NOT NULL AUTO_INCREMENT,
  `id_akce` int(3) NOT NULL,
  `nazev_skupiny` varchar(50) NOT NULL,
  `vys_s` text DEFAULT NULL,
  PRIMARY KEY (`id_soup`),
  KEY `id_turn` (`id_akce`),
  CONSTRAINT `soupiska_ibfk_5` FOREIGN KEY (`id_akce`) REFERENCES `akce` (`id_akce`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `sport`;
CREATE TABLE `sport` (
  `id_sport` int(3) NOT NULL AUTO_INCREMENT,
  `nazev_sportu` varchar(30) NOT NULL,
  PRIMARY KEY (`id_sport`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `sport` (`id_sport`, `nazev_sportu`) VALUES
(1,	'míčové'),
(2,	'zimní'),
(9,	'silové'),
(11,	'atletika');

DROP TABLE IF EXISTS `sportuje`;
CREATE TABLE `sportuje` (
  `id_sportuje` int(3) NOT NULL AUTO_INCREMENT,
  `id_disc` int(3) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `id_poz` int(3) DEFAULT NULL,
  `id_urov` int(3) DEFAULT NULL,
  `tym` varchar(50) DEFAULT NULL,
  `rekord` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id_sportuje`),
  KEY `fk_sportuje_disc` (`id_disc`) USING BTREE,
  KEY `uroven` (`id_urov`),
  KEY `pozice` (`id_poz`),
  KEY `fk_email_sportuje` (`email`) USING BTREE,
  CONSTRAINT `sportuje_ibfk_10` FOREIGN KEY (`id_urov`) REFERENCES `uroven` (`id_urov`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `sportuje_ibfk_5` FOREIGN KEY (`id_disc`) REFERENCES `disciplina` (`id_disc`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sportuje_ibfk_8` FOREIGN KEY (`email`) REFERENCES `uzivatel` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sportuje_ibfk_9` FOREIGN KEY (`id_poz`) REFERENCES `pozice` (`id_poz`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `ucastnik`;
CREATE TABLE `ucastnik` (
  `id_ucast` int(3) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL,
  `id_soup` int(3) NOT NULL,
  `vys_u` text DEFAULT NULL,
  `potrvzeni` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_ucast`),
  KEY `id_soup` (`id_soup`),
  KEY `email` (`email`),
  CONSTRAINT `ucastnik_ibfk_2` FOREIGN KEY (`id_soup`) REFERENCES `soupiska` (`id_soup`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ucastnik_ibfk_3` FOREIGN KEY (`email`) REFERENCES `uzivatel` (`email`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `uroven`;
CREATE TABLE `uroven` (
  `id_urov` int(3) NOT NULL AUTO_INCREMENT,
  `nazev_urov` varchar(30) NOT NULL,
  PRIMARY KEY (`id_urov`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `uroven` (`id_urov`, `nazev_urov`) VALUES
(2,	'amatér'),
(3,	'poloprofesionál');

DROP TABLE IF EXISTS `uzivatel`;
CREATE TABLE `uzivatel` (
  `id_uziv` int(3) NOT NULL AUTO_INCREMENT,
  `id_trid` varchar(3) DEFAULT NULL,
  `isic` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `opravneni` tinyint(1) NOT NULL,
  `jmeno` varchar(25) DEFAULT NULL,
  `prijmeni` varchar(25) DEFAULT NULL,
  `dat_nar` date DEFAULT NULL,
  `pohlavi` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id_uziv`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `uzivatel` (`id_uziv`, `id_trid`, `isic`, `email`, `opravneni`, `jmeno`, `prijmeni`, `dat_nar`, `pohlavi`) VALUES
(1,	NULL,	NULL,	'o.mazurek@spseiostrava.cz',	1,	'Ondřej',	'Mazurek',	NULL,	NULL),
(2,	NULL,	NULL,	'd.kozakova@spseiostrava.cz',	1,	'Daniela',	'Kozáková',	NULL,	NULL),
(3,	NULL,	NULL,	'l.hudecova@spseiostrava.cz',	1,	'Lenka',	'Hudecová',	NULL,	NULL),
(4,	NULL,	NULL,	'j.hubacek@spseiostrava.cz',	1,	'Jakub',	'Hubáček',	NULL,	NULL),
(5,	NULL,	NULL,	'r.tesar@spseiostrava.cz',	1,	'Radek',	'Tesař ',	NULL,	NULL),
(6,	NULL,	NULL,	'a.kacerovsky@spseiostrava.cz',	1,	'Antonín',	'Kačerovský',	NULL,	NULL);

-- 2024-04-01 16:17:51
