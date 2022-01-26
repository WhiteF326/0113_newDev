-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- ホスト: mysql640.db.sakura.ne.jp
-- 生成日時: 2021 年 9 月 15 日 17:27
-- サーバのバージョン： 5.7.33-log
-- PHP のバージョン: 7.1.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `fukuiohr2_wasurenai`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `comment`
--

CREATE TABLE `comment` (
  `from_id` int(10) UNSIGNED NOT NULL,
  `to_id` int(10) UNSIGNED NOT NULL,
  `comment` varchar(80) NOT NULL,
  `days` varchar(40) DEFAULT NULL,
  `notice_datetime` datetime DEFAULT NULL,
  `confilm_check` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- テーブルの構造 `family`
--

CREATE TABLE `family` (
  `id` int(10) UNSIGNED NOT NULL,
  `pass` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `family`
--

INSERT INTO `family` (`id`, `pass`) VALUES
(1, 'saki');

-- --------------------------------------------------------

--
-- テーブルの構造 `item`
--

CREATE TABLE `item` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `item`
--

INSERT INTO `item` (`id`, `name`) VALUES
(1, '携帯'),
(2, '弁当'),
(3, 'スマホの充電器'),
(4, '鍵'),
(5, 'PHPの教科書'),
(6, '車のカギ'),
(7, '時計'),
(8, '鞄'),
(9, '充電器'),
(10, '名札'),
(11, 'ノートパソコン'),
(12, '筆箱');

-- --------------------------------------------------------

--
-- テーブルの構造 `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `LINE_id` varchar(40) DEFAULT NULL,
  `Alexa_id` varchar(40) DEFAULT NULL,
  `family_id` int(10) UNSIGNED DEFAULT NULL,
  `notice_time` time DEFAULT NULL,
  `check_time` time DEFAULT NULL,
  `confilm_check` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `user`
--

INSERT INTO `user` (`id`, `name`, `LINE_id`, `Alexa_id`, `family_id`, `notice_time`, `check_time`, `confilm_check`) VALUES
(1, NULL, 'U35c186c6421462f401c0145b5ba42b7c', NULL, NULL, '09:40:00', '16:05:00', 1),
(2, NULL, 'Ucb132784437b29ef408647b7c01ef10e', NULL, NULL, NULL, NULL, 1),
(3, NULL, 'U6c5c4526913fe8bb90d55e7330d654d2', NULL, NULL, '07:00:00', '23:00:00', 1),
(4, NULL, 'U9ffa83f1466cb4f5e55de155ee1f7807', NULL, NULL, '07:00:00', '23:00:00', 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `user_item`
--

CREATE TABLE `user_item` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `days` varchar(40) DEFAULT NULL,
  `notice_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `user_item`
--

INSERT INTO `user_item` (`user_id`, `item_id`, `days`, `notice_datetime`) VALUES
(1, 1, NULL, NULL),
(1, 2, 'fri', '2021-09-04 23:39:52'),
(2, 1, 'ALL', NULL),
(2, 4, 'ALL', NULL),
(2, 11, 'montuewedthufri', NULL),
(2, 12, 'montuewedthufri', NULL),
(3, 2, NULL, NULL),
(3, 4, 'sat', NULL),
(3, 9, 'tue', NULL),
(3, 10, NULL, '2021-09-17 08:20:00'),
(4, 3, 'montuewedthufri', NULL),
(4, 5, 'tuewedfri', NULL),
(4, 6, 'montuewedthufri', NULL),
(4, 7, NULL, '2021-09-25 07:00:00');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `family`
--
ALTER TABLE `family`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pass` (`pass`);

--
-- テーブルのインデックス `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `LINE_id` (`LINE_id`) USING BTREE,
  ADD KEY `Alexa_id` (`Alexa_id`) USING BTREE;

--
-- テーブルのインデックス `user_item`
--
ALTER TABLE `user_item`
  ADD PRIMARY KEY (`user_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `family`
--
ALTER TABLE `family`
  ADD CONSTRAINT `family_ibfk_2` FOREIGN KEY (`id`) REFERENCES `family_id` (`id`);

--
-- テーブルの制約 `user_item`
--
ALTER TABLE `user_item`
  ADD CONSTRAINT `user_item_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `user_item_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
