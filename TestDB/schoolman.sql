-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2022 at 08:25 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `schoolman`
--

-- --------------------------------------------------------

--
-- Table structure for table `logintokens`
--

CREATE TABLE `logintokens` (
  `token` varbinary(64) NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `noteID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `taskID` int(10) UNSIGNED DEFAULT NULL,
  `headin` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `colour` varbinary(3) NOT NULL DEFAULT '\0\0\0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `scheduleID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `scheduleentry`
--

CREATE TABLE `scheduleentry` (
  `scheduleID` int(10) UNSIGNED NOT NULL,
  `row` int(10) UNSIGNED NOT NULL,
  `day` int(10) UNSIGNED NOT NULL,
  `subjectID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subjectID` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `abbr` varchar(50) NOT NULL,
  `colour` varbinary(3) NOT NULL DEFAULT '\0\0\0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `taskID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `dueBy` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `isTiemed` tinyint(1) NOT NULL DEFAULT 0,
  `colour` varbinary(3) NOT NULL DEFAULT '\0\0\0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `email` text NOT NULL,
  `passwd` text NOT NULL,
  `salt` text CHARACTER SET armscii8 NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `name`, `email`, `passwd`, `salt`, `created`) VALUES
(1, 'testPerson', 'test@testmail.com', '123456789', '', '2022-04-21 11:23:53'),
(2, 'secondTest', 'second@test.com', '987654321', '', '2022-04-21 11:24:22'),
(3, 'test', 'test@test.test', 'dfasjfnasn', 'djakjsdl', '2022-05-10 08:11:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logintokens`
--
ALTER TABLE `logintokens`
  ADD PRIMARY KEY (`token`),
  ADD KEY `tokenUserID` (`userID`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`noteID`),
  ADD KEY `notesUserID` (`userID`),
  ADD KEY `notesTaskID` (`taskID`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`scheduleID`),
  ADD KEY `scheduleUserID` (`userID`);

--
-- Indexes for table `scheduleentry`
--
ALTER TABLE `scheduleentry`
  ADD PRIMARY KEY (`scheduleID`,`row`,`day`),
  ADD KEY `entrySubjectID` (`subjectID`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subjectID`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`taskID`),
  ADD KEY `taskUserID` (`userID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`name`),
  ADD UNIQUE KEY `useremail` (`email`) USING HASH;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `noteID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `scheduleID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subjectID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `taskID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logintokens`
--
ALTER TABLE `logintokens`
  ADD CONSTRAINT `tokenUserID_FK1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `noteTaskID_FK2` FOREIGN KEY (`taskID`) REFERENCES `tasks` (`taskID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `noteUserID_FK1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `scheduleUserID_FK1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `scheduleentry`
--
ALTER TABLE `scheduleentry`
  ADD CONSTRAINT `entryScheduleID_FK1` FOREIGN KEY (`scheduleID`) REFERENCES `schedule` (`scheduleID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subjectEntryID_FK2` FOREIGN KEY (`subjectID`) REFERENCES `subjects` (`subjectID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `taskUserID_FK1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
