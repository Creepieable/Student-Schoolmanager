-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.13-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.0.0.6468
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for schoolman
DROP DATABASE IF EXISTS `schoolman`;
CREATE DATABASE IF NOT EXISTS `schoolman` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `schoolman`;

-- Dumping structure for table schoolman.logintokens
DROP TABLE IF EXISTS `logintokens`;
CREATE TABLE IF NOT EXISTS `logintokens` (
  `token` varbinary(64) NOT NULL,
  `userID` int(10) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `expiresIn` int(11) DEFAULT 2628000,
  PRIMARY KEY (`token`),
  KEY `tokenUserID` (`userID`),
  CONSTRAINT `tokenUserID_FK1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table schoolman.logintokens: ~3 rows (approximately)
REPLACE INTO `logintokens` (`token`, `userID`, `created`, `expiresIn`) VALUES
	(_binary 0x30383536633338326638396234616464663231353564336362363362636537656539326633356365323433366164646331363830376433616466383064363861, 1, '2022-05-30 12:13:21', 2628000),
	(_binary 0x30643262626331643933663233303564626161303031653436636132646166313064393738663262303163633461326161303138353539353430303063363539, 1, '2022-06-16 09:59:31', 2628000),
	(_binary 0x62616234653937646236653832356334386561643930386631343433396133643138663264663439346561346239363833323165366234303333323165366431, 1, '2022-05-28 17:02:39', 2628000),
	(_binary 0x65373263373334356364663166396431303335316430663764316639316365333130646564646536326265643366303835636634316639353966303637356638, 1, '2022-05-29 13:10:22', 2628000);

-- Dumping structure for table schoolman.notes
DROP TABLE IF EXISTS `notes`;
CREATE TABLE IF NOT EXISTS `notes` (
  `noteID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(10) unsigned NOT NULL,
  `taskID` int(10) unsigned DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `colour` varchar(6) NOT NULL DEFAULT 'ffffff',
  PRIMARY KEY (`noteID`),
  KEY `notesUserID` (`userID`),
  KEY `notesTaskID` (`taskID`),
  CONSTRAINT `noteTaskID_FK2` FOREIGN KEY (`taskID`) REFERENCES `tasks` (`taskID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `noteUserID_FK1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table schoolman.notes: ~6 rows (approximately)
REPLACE INTO `notes` (`noteID`, `userID`, `taskID`, `title`, `text`, `colour`) VALUES
	(6, 1, 12, 'Fahninani ist ein Flipsdraliliii', 'Mein herzallerliebstes, Fahnili.', 'ffe499'),
	(9, 1, 12, 'Pupsen tut er ab und zu.', 'Wenn er pupst um mich zu ärgern bekommt er einen Flaps auf seinen frechen Flipspopo.', '83fb85'),
	(11, 1, 19, 'ALS ICH FORTGING', 'Als ich fortging war die Straße steil - kehr wieder um<br />Nimm an ihrem Kummer teil, mach sie heil<br />Als ich fortging war der Asphalt heiß - kehr wieder um<br />Red ihr aus um jeden Preis, was sie weiß<br /><br />Nichts ist unendlich, so sieh das doch ein<br />Ich weiß, du willst unendlich sein - schwach und klein<br />Feuer brennt nieder, wenn′s keiner mehr nährt<br />Kenn ja selber, was dir heut widerfährt<br /><br />Als ich fortging warn die arme leer - kehr wieder um<br />Mach\'s ihr leichter, einmal mehr, nicht so schwer<br />Als ich fortging, kam ein Wind, so schwach, warf mich nicht um<br />Unter ihrem Tränendach war ich schwach<br /><br />Nichts ist unendlich, so sieh das doch ein<br />Ich weiß, du willst unendlich sein, schwach und klein<br />Nichts ist von Dauer, wenn′s keiner recht will<br />Auch die trauer wird da sein, schwach und klein', '171730'),
	(12, 1, 12, 'Mein Kleiner Drache Fahninan', 'Ich habe einen kleinen Drachen.<br />Er spuckt kein Feuer, sondern faucht.<br />Er hat viel Spaß am Unsinn machen.<br />Und muss ins Freie, wenn er raucht.', 'ff7b00'),
	(17, 2, 17, 'Knuddels', 'KNÖDDELS!', '00e1ff'),
	(23, 1, 12, 'Ich hab ihn ganz doll liiiieb 🥰🥰🥰', 'LIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEB', 'ff4d4d');

-- Dumping structure for table schoolman.salt
DROP TABLE IF EXISTS `salt`;
CREATE TABLE IF NOT EXISTS `salt` (
  `userID` int(10) unsigned NOT NULL,
  `Salt` text DEFAULT '',
  PRIMARY KEY (`userID`),
  CONSTRAINT `FK_salt_users` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table schoolman.salt: ~3 rows (approximately)
REPLACE INTO `salt` (`userID`, `Salt`) VALUES
	(1, ' ({2hL>vblz4l\'>ND4uf_'),
	(2, ' PSF]NQB:as3kE7d!-htW');

-- Dumping structure for table schoolman.schedule
DROP TABLE IF EXISTS `schedule`;
CREATE TABLE IF NOT EXISTS `schedule` (
  `scheduleID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`scheduleID`),
  KEY `scheduleUserID` (`userID`),
  CONSTRAINT `scheduleUserID_FK1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table schoolman.schedule: ~4 rows (approximately)
REPLACE INTO `schedule` (`scheduleID`, `userID`, `title`) VALUES
	(1, 1, 'A - Woche:'),
	(2, 1, 'A - Woche:'),
	(3, 1, 'A - Woche:'),
	(4, 1, 'A - Woche:');

-- Dumping structure for table schoolman.scheduleentry
DROP TABLE IF EXISTS `scheduleentry`;
CREATE TABLE IF NOT EXISTS `scheduleentry` (
  `scheduleID` int(10) unsigned NOT NULL,
  `row` int(10) unsigned NOT NULL,
  `monday` int(10) unsigned DEFAULT NULL,
  `tuesday` int(10) unsigned DEFAULT NULL,
  `wednesday` int(10) unsigned DEFAULT NULL,
  `thursday` int(10) unsigned DEFAULT NULL,
  `friday` int(10) unsigned DEFAULT NULL,
  `time` text NOT NULL DEFAULT '',
  PRIMARY KEY (`scheduleID`,`row`) USING BTREE,
  KEY `FK_scheduleentry_subjects` (`monday`),
  KEY `FK_scheduleentry_subjects_2` (`tuesday`),
  KEY `FK_scheduleentry_subjects_3` (`wednesday`),
  KEY `FK_scheduleentry_subjects_4` (`thursday`),
  KEY `FK_scheduleentry_subjects_5` (`friday`),
  CONSTRAINT `FK_scheduleentry_schedule` FOREIGN KEY (`scheduleID`) REFERENCES `schedule` (`scheduleID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_scheduleentry_subjects` FOREIGN KEY (`monday`) REFERENCES `subjects` (`subjectID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_scheduleentry_subjects_2` FOREIGN KEY (`tuesday`) REFERENCES `subjects` (`subjectID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_scheduleentry_subjects_3` FOREIGN KEY (`wednesday`) REFERENCES `subjects` (`subjectID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_scheduleentry_subjects_4` FOREIGN KEY (`thursday`) REFERENCES `subjects` (`subjectID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_scheduleentry_subjects_5` FOREIGN KEY (`friday`) REFERENCES `subjects` (`subjectID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table schoolman.scheduleentry: ~14 rows (approximately)
REPLACE INTO `scheduleentry` (`scheduleID`, `row`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `time`) VALUES
	(1, 0, 5, 5, 5, 5, 5, '00:00'),
	(1, 1, 5, 5, NULL, 5, 5, '00:00'),
	(1, 2, 5, 5, 5, 5, 5, '00:00'),
	(2, 0, 5, 5, 5, 5, 5, '00:00'),
	(2, 2, 5, 5, 5, 5, 5, '00:00'),
	(2, 3, 5, 5, NULL, 5, 5, '00:00'),
	(3, 0, 5, 5, 5, 5, 5, '00:00'),
	(3, 1, 5, 5, NULL, 5, 5, '00:00'),
	(3, 2, NULL, NULL, NULL, NULL, NULL, ''),
	(3, 3, 5, 5, NULL, 5, 5, '00:00'),
	(4, 0, 5, 5, 5, 5, 5, '00:00'),
	(4, 1, 5, 5, NULL, 5, 5, '00:00'),
	(4, 2, NULL, NULL, NULL, NULL, NULL, ''),
	(4, 3, 5, 5, NULL, 5, 5, '00:00');

-- Dumping structure for table schoolman.subjects
DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `subjectID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `abbr` varchar(50) NOT NULL,
  `colour` varchar(6) NOT NULL DEFAULT 'ffffff',
  PRIMARY KEY (`subjectID`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table schoolman.subjects: ~24 rows (approximately)
REPLACE INTO `subjects` (`subjectID`, `name`, `abbr`, `colour`) VALUES
	(1, 'Ehtik', 'ETH', 'f7f414'),
	(2, 'G/R/W', 'GRW', '5478b3'),
	(3, 'Geographie', 'GEO', '5fad6b'),
	(4, 'Geschichte', 'GE', '63351f'),
	(5, 'Religion', 'REL', 'f7f414'),
	(6, 'Astronomie', 'ASTRO', '000000'),
	(7, 'Biologie', 'BIO', '000000'),
	(8, 'Informatik', 'INFO', '5478b3'),
	(9, 'Mathematik', 'MA', '1a1fad'),
	(10, 'Physik', 'PH', '000000'),
	(11, 'Technik/Computer', 'TC', '000000'),
	(12, 'Deutsch', 'DE', '000000'),
	(13, 'Englisch', 'EN', '000000'),
	(14, 'Französisch', 'FR', '000000'),
	(15, 'Griechisch', 'GRIECH', '000000'),
	(16, 'Italienisch', 'ITAL', '000000'),
	(17, 'Latein', 'LAT', '000000'),
	(18, 'Polnisch', 'POL', '000000'),
	(19, 'Russisch', 'RU', '000000'),
	(20, 'Spanisch', 'SP', '000000'),
	(21, 'Tschechisch', 'TSCH', '000000'),
	(22, 'Musik', 'MU', '000000'),
	(23, 'Kunst', 'KU', '000000'),
	(24, 'Sport', 'SP', '000000');

-- Dumping structure for table schoolman.tasks
DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `taskID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `dueBy` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `isTimed` tinyint(1) NOT NULL DEFAULT 0,
  `colour` varchar(6) NOT NULL DEFAULT 'ffffff',
  PRIMARY KEY (`taskID`),
  KEY `taskUserID` (`userID`),
  CONSTRAINT `taskUserID_FK1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table schoolman.tasks: ~6 rows (approximately)
REPLACE INTO `tasks` (`taskID`, `userID`, `title`, `dueBy`, `isTimed`, `colour`) VALUES
	(12, 1, 'Fahninani', '2022-06-15 10:00:00', 0, 'ff9500'),
	(13, 1, 'Fußball dadada', '2022-06-15 10:00:00', 0, 'ff00f7'),
	(15, 2, 'Knuddeli mit Jay', '2022-06-03 20:00:00', 1, '800000'),
	(16, 2, 'Knuddeli mit Jay', '2022-06-04 20:00:00', 1, '800000'),
	(17, 2, 'Knuddeli mit Jay', '2022-06-05 20:00:00', 1, '800000'),
	(19, 1, 'Chorprobe', '2022-06-06 14:30:00', 1, '000c1f');

-- Dumping structure for table schoolman.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `email` text NOT NULL,
  `passwd` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`userID`),
  UNIQUE KEY `username` (`name`),
  UNIQUE KEY `useremail` (`email`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table schoolman.users: ~3 rows (approximately)
REPLACE INTO `users` (`userID`, `name`, `email`, `passwd`, `created`) VALUES
	(1, 'jay', 'jay@fufu.here', '5bc2b40b464bb129f215a89efe6b0f9041233749882f991ffd99b79e8d572d80', '2022-05-26 11:46:18'),
	(2, 'fahni', 'fahni@nani.com', '5c92b1874173fa3751a1a59817ad2dbcd3d656f06f0cb9448f4581859802324e', '2022-05-29 21:16:05');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
