-- phpMyAdmin SQL Dump
-- Время создания: Сен 15 2025 г., 00:43
-- Версия PHP: 8.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `grades` (
  `id` int NOT NULL,
  `grade` tinyint NOT NULL,
  `litera` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `passes` (
  `id` int NOT NULL,
  `studentid` int NOT NULL,
  `purposeid` int NOT NULL,
  `gradeid` int NOT NULL,
  `date` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `perms` (
  `id` int NOT NULL,
  `teacherid` int NOT NULL,
  `gradeid` int NOT NULL,
  `main` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE `purpose` (
  `id` int NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `purpose` (`id`, `name`) VALUES
(1, 'Повышенная температура'),
(2, 'ОРЗ/ОРВИ'),
(3, 'Посещение врача'),
(4, 'Заявление'),
(5, 'Плохое самочувствие'),
(7, 'боль в животе'),
(8, 'Насморк и кашель'),
(9, 'Прогул'),
(11, 'Соревнования/конкурсы');

CREATE TABLE `students` (
  `id` int NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gradeid` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `teachers` (
  `id` int UNSIGNED NOT NULL,
  `login` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `teachers` (`id`, `login`, `password`, `name`) VALUES
(1, 'q', '8e35c2cd3bf6641bdb0e2050b76932cbb2e6034a0ddacc1d9bea82a6ba57f7cf', 'admin');

ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `passes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `perms`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `purpose`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`(20));

ALTER TABLE `grades`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

ALTER TABLE `passes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

ALTER TABLE `perms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

ALTER TABLE `purpose`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

ALTER TABLE `students`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1029;

ALTER TABLE `teachers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1099;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;