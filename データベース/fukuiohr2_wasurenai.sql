-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- ホスト: mysql640.db.sakura.ne.jp
-- 生成日時: 2021 年 11 月 30 日 14:26
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
-- テーブルの構造 `Alexa_coop`
--

CREATE TABLE `Alexa_coop` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `pass_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `Alexa_coop`
--

INSERT INTO `Alexa_coop` (`user_id`, `pass_id`) VALUES
(1, 111111);

-- --------------------------------------------------------

--
-- テーブルの構造 `comment`
--

CREATE TABLE `comment` (
  `family_id` int(10) UNSIGNED NOT NULL,
  `from_id` int(10) UNSIGNED NOT NULL,
  `to_id` int(10) UNSIGNED NOT NULL,
  `comment` varchar(80) DEFAULT NULL,
  `LINE_check` tinyint(1) DEFAULT '0',
  `alert` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `comment`
--

INSERT INTO `comment` (`family_id`, `from_id`, `to_id`, `comment`, `LINE_check`, `alert`) VALUES
(1, 1, 4, NULL, 1, 0),
(1, 1, 8, 'お弁当持って行ってね', 1, 1),
(1, 4, 8, NULL, 1, 0),
(1, 8, 4, 'リンゴ買ってきて', 1, 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `family`
--

CREATE TABLE `family` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `pass` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `family`
--

INSERT INTO `family` (`id`, `name`, `pass`) VALUES
(1, 'Noneleave', 'wasurenai'),
(2, 'kaeru', 'kaeritai');

-- --------------------------------------------------------

--
-- テーブルの構造 `family_user`
--

CREATE TABLE `family_user` (
  `family_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `family_user`
--

INSERT INTO `family_user` (`family_id`, `user_id`) VALUES
(1, 1),
(1, 4),
(1, 8),
(2, 1);

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
(1, '弁当'),
(2, '鍵'),
(3, '傘'),
(4, '原稿'),
(5, 'クソ野郎'),
(6, '携帯'),
(7, '車のカギ'),
(8, '充電器'),
(9, '頭のネジ'),
(10, '誕生日'),
(11, 'こどものこころ'),
(12, '文化の日'),
(13, '昨日の記憶'),
(14, '夢'),
(15, '生きる理由'),
(16, '昨日忘れたもの'),
(17, '教科書'),
(18, '帽子'),
(19, '会議資料'),
(20, '体操服'),
(21, '家のカギ'),
(22, '応用情報合格書');

-- --------------------------------------------------------

--
-- テーブルの構造 `location`
--

CREATE TABLE `location` (
  `id` int(10) UNSIGNED NOT NULL,
  `lat` varchar(40) NOT NULL,
  `lon` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `location`
--

INSERT INTO `location` (`id`, `lat`, `lon`) VALUES
(1, '36.063249958713', '136.22261658324'),
(4, '36.217285168044', '136.15030288696'),
(7, '-73.680558', '6.799595'),
(8, '36.217285168044', '136.15030288696');

-- --------------------------------------------------------

--
-- テーブルの構造 `send_log`
--

CREATE TABLE `send_log` (
  `id` int(11) NOT NULL,
  `to_id` int(10) UNSIGNED NOT NULL,
  `message` varchar(80) NOT NULL,
  `datetime` datetime NOT NULL,
  `confirm_check` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `send_log`
--

INSERT INTO `send_log` (`id`, `to_id`, `message`, `datetime`, `confirm_check`) VALUES
(112, 1, '持ち物更新確認', '2021-10-22 23:00:01', 1),
(113, 1, '弁当\n持ち物確認', '2021-10-25 08:00:01', 1),
(114, 5, '傘\n指定時間の持ち物確認', '2021-10-25 17:20:00', 0),
(115, 1, '持ち物更新確認', '2021-10-25 23:00:00', 1),
(116, 1, '弁当\n持ち物確認', '2021-10-26 08:00:00', 1),
(117, 1, '持ち物更新確認', '2021-10-26 23:00:00', 1),
(118, 1, '弁当\n持ち物確認', '2021-10-27 08:00:00', 1),
(119, 1, '持ち物更新確認', '2021-10-27 23:00:00', 1),
(120, 1, '弁当\n持ち物確認', '2021-10-28 08:00:00', 1),
(121, 1, '持ち物更新確認', '2021-10-28 23:00:00', 0),
(122, 1, '弁当\n持ち物確認', '2021-10-29 08:00:00', 1),
(123, 1, '持ち物更新確認', '2021-10-29 23:00:00', 1),
(124, 1, '弁当\n持ち物確認', '2021-11-01 08:00:00', 1),
(127, 1, '持ち物更新確認', '2021-11-01 23:00:00', 1),
(128, 1, '弁当\n持ち物確認', '2021-11-02 08:00:00', 1),
(129, 7, '頭のネジ\n指定時間の持ち物確認', '2021-11-02 12:10:00', 0),
(130, 7, '誕生日\n指定時間の持ち物確認', '2021-11-02 12:45:00', 1),
(131, 7, '持ち物更新確認', '2021-11-02 13:00:00', 1),
(134, 1, '持ち物更新確認', '2021-11-02 23:00:00', 1),
(135, 1, '弁当\n持ち物確認', '2021-11-03 08:00:00', 0),
(136, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-03 12:50:00', 1),
(137, 7, '持ち物更新確認', '2021-11-03 13:00:00', 1),
(138, 7, '誕生日\n指定時間の持ち物確認', '2021-11-03 13:00:00', 1),
(139, 1, '文化の日\n指定時間の持ち物確認', '2021-11-03 13:20:00', 1),
(142, 1, '持ち物更新確認', '2021-11-03 23:00:00', 1),
(143, 1, '弁当\n持ち物確認', '2021-11-04 08:00:00', 1),
(144, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-04 12:50:01', 0),
(145, 7, '持ち物更新確認', '2021-11-04 13:00:00', 0),
(146, 0, '', '2021-11-04 13:07:00', 1),
(147, 0, '', '2021-11-04 13:08:15', 1),
(148, 0, '', '2021-11-04 14:50:29', 1),
(149, 0, '', '2021-11-04 14:50:31', 1),
(150, 0, '', '2021-11-04 14:55:16', 1),
(151, 0, '', '2021-11-04 14:56:37', 1),
(152, 0, '', '0000-00-00 00:00:00', 1),
(153, 0, '', '0000-00-00 00:00:00', 1),
(154, 0, '', '2021-11-04 15:38:26', 1),
(160, 1, '持ち物更新確認', '2021-11-04 23:00:00', 1),
(162, 1, '弁当\n持ち物確認', '2021-11-05 08:00:00', 1),
(163, 4, 'Alexa持ち物確認', '2021-11-05 12:10:51', 1),
(164, 4, 'Alexa持ち物確認', '2021-11-05 12:35:28', 1),
(165, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-05 12:50:01', 0),
(166, 7, '持ち物更新確認', '2021-11-05 13:00:00', 0),
(167, 8, '持ち物更新確認', '2021-11-05 22:00:01', 1),
(168, 1, '持ち物更新確認', '2021-11-05 23:00:00', 0),
(169, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-06 12:50:00', 0),
(170, 7, '持ち物更新確認', '2021-11-06 13:00:00', 0),
(171, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-07 12:50:00', 0),
(172, 7, '持ち物更新確認', '2021-11-07 13:00:00', 1),
(173, 8, '車のカギ\n充電器\n持ち物確認', '2021-11-08 07:30:00', 0),
(174, 1, '弁当\n持ち物確認', '2021-11-08 08:00:00', 1),
(175, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-08 12:50:01', 0),
(176, 7, '持ち物更新確認', '2021-11-08 13:00:00', 0),
(177, 4, 'Alexa持ち物確認', '2021-11-08 16:10:03', 1),
(178, 8, '弁当\n帽子\n持ち物確認', '2021-11-08 16:20:00', 1),
(179, 8, '持ち物更新確認', '2021-11-08 17:20:00', 0),
(180, 1, '持ち物更新確認', '2021-11-08 23:00:00', 1),
(181, 1, '弁当\n持ち物確認', '2021-11-09 08:00:00', 1),
(182, 8, '弁当\n体操服\n持ち物確認', '2021-11-09 12:25:00', 1),
(183, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-09 12:50:00', 0),
(184, 7, '持ち物更新確認', '2021-11-09 13:00:00', 0),
(185, 8, 'Alexa持ち物確認', '2021-11-09 15:36:43', 1),
(186, 4, 'Alexa持ち物確認', '2021-11-09 15:48:16', 1),
(187, 8, 'Alexa持ち物確認', '2021-11-09 15:48:46', 1),
(188, 8, 'Alexa持ち物確認', '2021-11-09 15:49:29', 1),
(189, 4, 'Alexa持ち物確認', '2021-11-09 16:06:00', 1),
(190, 8, '持ち物更新確認', '2021-11-09 18:00:00', 0),
(191, 1, '持ち物更新確認', '2021-11-09 23:00:00', 1),
(192, 1, '弁当\n持ち物確認', '2021-11-10 08:00:00', 1),
(193, 8, '弁当\n持ち物確認', '2021-11-10 12:25:00', 0),
(194, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-10 12:50:00', 1),
(195, 7, '持ち物更新確認', '2021-11-10 13:00:00', 1),
(196, 8, '持ち物更新確認', '2021-11-10 22:30:00', 1),
(197, 1, '持ち物更新確認', '2021-11-10 23:00:00', 1),
(198, 8, '弁当\n体操服\n持ち物確認', '2021-11-11 07:30:00', 1),
(199, 1, '弁当\n持ち物確認', '2021-11-11 08:00:01', 1),
(200, 4, 'Alexa持ち物確認', '2021-11-11 10:04:12', 1),
(201, 4, 'Alexa持ち物確認', '2021-11-11 11:08:48', 1),
(202, 4, 'Alexa持ち物確認', '2021-11-11 11:09:43', 1),
(203, 8, 'Alexa持ち物確認', '2021-11-11 11:12:35', 1),
(204, 4, 'Alexa持ち物確認', '2021-11-11 11:13:14', 1),
(205, 8, 'Alexa持ち物確認', '2021-11-11 14:23:16', 1),
(206, 8, 'Alexa持ち物確認', '2021-11-11 14:24:35', 1),
(207, 8, 'Alexa持ち物確認', '2021-11-11 14:25:55', 1),
(208, 4, 'Alexa持ち物確認', '2021-11-11 14:56:24', 1),
(209, 8, '持ち物更新確認', '2021-11-11 22:30:00', 0),
(210, 1, '持ち物更新確認', '2021-11-11 23:00:01', 1),
(211, 8, '弁当\n家のカギ\n持ち物確認', '2021-11-12 07:30:00', 0),
(212, 1, '弁当\n持ち物確認', '2021-11-12 08:00:00', 1),
(213, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-12 12:50:00', 1),
(214, 7, '持ち物更新確認', '2021-11-12 13:00:00', 1),
(215, 8, '持ち物更新確認', '2021-11-12 22:30:00', 0),
(216, 1, '持ち物更新確認', '2021-11-12 23:00:00', 0),
(217, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-13 12:50:00', 1),
(218, 7, '持ち物更新確認', '2021-11-13 13:00:00', 1),
(219, 1, '原稿\n指定時間の持ち物確認', '2021-11-13 13:00:00', 1),
(220, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-14 12:50:00', 1),
(221, 7, '持ち物更新確認', '2021-11-14 13:00:00', 1),
(222, 8, '弁当\n持ち物確認', '2021-11-15 07:30:00', 0),
(223, 1, '弁当\n持ち物確認', '2021-11-15 08:00:00', 0),
(224, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-15 12:50:00', 0),
(225, 7, '持ち物更新確認', '2021-11-15 13:00:00', 0),
(226, 8, '持ち物更新確認', '2021-11-15 22:30:00', 1),
(227, 1, '持ち物更新確認', '2021-11-15 23:00:00', 1),
(228, 8, '弁当\n体操服\n家のカギ\n持ち物確認', '2021-11-16 07:30:00', 0),
(229, 1, '弁当\n持ち物確認', '2021-11-16 08:00:00', 1),
(230, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-16 12:50:01', 0),
(231, 7, '持ち物更新確認', '2021-11-16 13:00:00', 0),
(232, 4, 'Alexa持ち物確認', '2021-11-16 17:00:20', 1),
(233, 4, 'Alexa持ち物確認', '2021-11-16 17:13:45', 1),
(234, 4, 'Alexa持ち物確認', '2021-11-16 17:14:09', 1),
(235, 8, '持ち物更新確認', '2021-11-16 22:30:00', 0),
(236, 1, '持ち物更新確認', '2021-11-16 23:00:00', 1),
(237, 8, '弁当\n家のカギ\n持ち物確認', '2021-11-17 07:30:01', 0),
(238, 1, '弁当\n持ち物確認', '2021-11-17 08:00:00', 1),
(239, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-17 12:50:00', 0),
(240, 7, '持ち物更新確認', '2021-11-17 13:00:00', 0),
(241, 4, 'Alexa持ち物確認', '2021-11-17 14:31:19', 1),
(242, 4, 'Alexa持ち物確認', '2021-11-17 14:31:49', 1),
(243, 4, 'Alexa持ち物確認', '2021-11-17 14:34:10', 1),
(244, 4, 'Alexa持ち物確認', '2021-11-17 14:44:28', 1),
(245, 4, 'Alexa持ち物確認', '2021-11-17 14:54:30', 1),
(246, 4, 'Alexa持ち物確認', '2021-11-17 14:57:26', 1),
(247, 4, 'Alexa持ち物確認', '2021-11-17 14:57:54', 1),
(248, 4, 'Alexa持ち物確認', '2021-11-17 15:00:25', 1),
(249, 4, 'Alexa持ち物確認', '2021-11-17 15:43:52', 1),
(250, 4, 'Alexa持ち物確認', '2021-11-17 15:49:24', 1),
(251, 4, 'Alexa持ち物確認', '2021-11-17 16:14:51', 1),
(252, 8, '持ち物更新確認', '2021-11-17 22:30:01', 0),
(253, 1, '持ち物更新確認', '2021-11-17 23:00:00', 1),
(254, 8, '弁当\n持ち物確認', '2021-11-18 07:30:00', 0),
(255, 1, '弁当\n持ち物確認', '2021-11-18 08:00:00', 1),
(256, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-18 12:50:00', 0),
(257, 7, '持ち物更新確認', '2021-11-18 13:00:00', 0),
(258, 8, '持ち物更新確認', '2021-11-18 22:30:00', 0),
(259, 1, '持ち物更新確認', '2021-11-18 23:00:00', 1),
(260, 8, '弁当\n家のカギ\n持ち物確認', '2021-11-19 07:30:01', 0),
(261, 1, '弁当\n持ち物確認', '2021-11-19 08:00:00', 1),
(262, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-19 12:50:01', 0),
(263, 7, '持ち物更新確認', '2021-11-19 13:00:00', 0),
(264, 1, '持ち物更新確認', '2021-11-19 23:00:00', 1),
(265, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-20 12:50:00', 0),
(266, 7, '持ち物更新確認', '2021-11-20 13:00:00', 0),
(267, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-21 12:50:01', 0),
(268, 7, '持ち物更新確認', '2021-11-21 13:00:00', 1),
(269, 1, '弁当\n持ち物確認', '2021-11-22 08:00:00', 1),
(270, 8, '弁当\n持ち物確認', '2021-11-22 11:16:00', 1),
(271, 4, 'Alexa持ち物確認', '2021-11-22 11:19:52', 1),
(272, 4, 'Alexa持ち物確認', '2021-11-22 11:22:23', 1),
(273, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-22 12:50:00', 0),
(274, 4, 'Alexa持ち物確認', '2021-11-22 12:51:30', 1),
(275, 4, 'Alexa持ち物確認', '2021-11-22 12:57:09', 1),
(276, 7, '持ち物更新確認', '2021-11-22 13:00:01', 0),
(277, 4, 'Alexa持ち物確認', '2021-11-22 15:30:18', 1),
(278, 4, 'Alexa持ち物確認', '2021-11-22 15:35:57', 1),
(279, 4, 'Alexa持ち物確認', '2021-11-22 15:37:45', 1),
(280, 4, 'Alexa持ち物確認', '2021-11-22 15:39:27', 1),
(281, 4, 'Alexa持ち物確認', '2021-11-22 15:44:24', 1),
(282, 8, '持ち物更新確認', '2021-11-22 22:30:00', 0),
(283, 1, '持ち物更新確認', '2021-11-22 23:00:00', 1),
(284, 1, '弁当\n持ち物確認', '2021-11-23 08:00:01', 0),
(285, 8, '弁当\n家のカギ\n持ち物確認', '2021-11-23 11:16:00', 0),
(286, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-23 12:50:01', 0),
(287, 7, '持ち物更新確認', '2021-11-23 13:00:00', 0),
(288, 8, '持ち物更新確認', '2021-11-23 22:30:00', 0),
(289, 1, '持ち物更新確認', '2021-11-23 23:00:01', 1),
(290, 1, '弁当\n持ち物確認', '2021-11-24 08:00:01', 1),
(291, 8, '弁当\n家のカギ\n持ち物確認', '2021-11-24 11:16:00', 0),
(292, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-24 12:50:00', 1),
(293, 7, '持ち物更新確認', '2021-11-24 13:00:00', 1),
(294, 4, 'Alexa持ち物確認', '2021-11-24 13:06:12', 1),
(295, 8, '持ち物更新確認', '2021-11-24 22:30:01', 1),
(296, 1, '持ち物更新確認', '2021-11-24 23:00:01', 1),
(297, 8, '弁当\n持ち物確認', '2021-11-25 07:40:00', 0),
(298, 1, '弁当\n持ち物確認', '2021-11-25 08:00:00', 1),
(299, 4, 'Alexa持ち物確認', '2021-11-25 11:25:37', 1),
(300, 4, 'Alexa持ち物確認', '2021-11-25 11:35:52', 1),
(301, 4, 'Alexa持ち物確認', '2021-11-25 11:36:27', 1),
(302, 4, 'Alexa持ち物確認', '2021-11-25 11:44:07', 1),
(303, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-25 12:50:00', 0),
(304, 7, '持ち物更新確認', '2021-11-25 13:00:00', 0),
(305, 8, '弁当\n持ち物確認', '2021-11-25 14:20:00', 1),
(306, 8, '弁当\n持ち物確認', '2021-11-25 14:28:00', 1),
(307, 8, '弁当\n持ち物確認', '2021-11-25 14:40:00', 1),
(308, 8, '弁当\n持ち物確認', '2021-11-25 16:08:00', 1),
(309, 8, '持ち物更新確認', '2021-11-25 22:30:00', 1),
(310, 1, '持ち物更新確認', '2021-11-25 23:00:01', 1),
(311, 1, '弁当\n応用情報合格書\n持ち物確認', '2021-11-26 08:00:00', 1),
(312, 8, '弁当\n家のカギ\n持ち物確認', '2021-11-26 08:00:00', 1),
(313, 8, 'Alexa持ち物確認', '2021-11-26 11:13:06', 1),
(314, 8, 'Alexa持ち物確認', '2021-11-26 11:14:59', 1),
(315, 4, 'Alexa持ち物確認', '2021-11-26 11:16:07', 1),
(316, 8, 'Alexa持ち物確認', '2021-11-26 11:16:45', 1),
(317, 8, 'Alexa持ち物確認', '2021-11-26 11:23:46', 1),
(318, 8, 'Alexa持ち物確認', '2021-11-26 11:24:25', 1),
(319, 8, 'Alexa持ち物確認', '2021-11-26 11:28:37', 1),
(320, 8, 'Alexa持ち物確認', '2021-11-26 11:33:18', 1),
(321, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-26 12:50:00', 0),
(322, 7, '持ち物更新確認', '2021-11-26 13:00:01', 1),
(323, 8, '持ち物更新確認', '2021-11-26 22:30:01', 0),
(324, 1, '持ち物更新確認', '2021-11-26 23:00:00', 0),
(325, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-27 12:50:00', 0),
(326, 7, '持ち物更新確認', '2021-11-27 13:00:00', 0),
(327, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-28 12:50:00', 1),
(328, 7, '持ち物更新確認', '2021-11-28 13:00:00', 1),
(329, 1, '弁当\n持ち物確認', '2021-11-29 08:00:00', 0),
(330, 8, '弁当\n持ち物確認', '2021-11-29 08:00:00', 0),
(331, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-29 12:50:00', 0),
(332, 7, '持ち物更新確認', '2021-11-29 13:00:00', 0),
(333, 8, '持ち物更新確認', '2021-11-29 22:30:00', 0),
(334, 1, '持ち物更新確認', '2021-11-29 23:00:00', 0),
(335, 1, '弁当\n持ち物確認', '2021-11-30 08:00:00', 1),
(336, 8, '弁当\n家のカギ\n持ち物確認', '2021-11-30 08:00:00', 0),
(337, 7, 'こどものこころ\n昨日の記憶\n夢\n生きる理由\n昨日忘れたもの\n持ち物確認', '2021-11-30 12:50:00', 0),
(338, 7, '持ち物更新確認', '2021-11-30 13:00:00', 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `LINE_id` varchar(40) DEFAULT NULL,
  `Alexa_id` varchar(130) DEFAULT NULL,
  `notice_time` time DEFAULT NULL,
  `return_time` time DEFAULT NULL,
  `check_time` time DEFAULT NULL,
  `Alexa_check` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `user`
--

INSERT INTO `user` (`id`, `name`, `LINE_id`, `Alexa_id`, `notice_time`, `return_time`, `check_time`, `Alexa_check`) VALUES
(1, '陽子', 'U35c186c6421462f401c0145b5ba42b7c', NULL, '08:00:00', NULL, '23:00:00', 0),
(4, '拓哉', 'Ucb132784437b29ef408647b7c01ef10e', 'amzn1.ask.person.ALVQI5F6NHYHCSPHCGSGZIMK2WY2PRSR4G6NVYYEKF7DXKPJDBVWGSUGFN4IQLUJP3TJJ6ZZVBBTYYPFWRGBX7M7MJJJFIYJDPNJXS6A', NULL, NULL, NULL, 0),
(5, NULL, 'Ubbc615afcf9bbfbe6241459b819dfa4e', NULL, NULL, NULL, NULL, 0),
(6, NULL, 'U6041016908f1817f0a23548b7d4782d0', NULL, NULL, NULL, NULL, 0),
(7, NULL, 'U335a52e4455bb07e03d2f7b75e732350', NULL, '12:50:00', NULL, '13:00:00', 0),
(8, '薫', 'U9ffa83f1466cb4f5e55de155ee1f7807', 'amzn1.ask.person.ALVQI5F6NHYHCSPHCGSGZIMK2WY2PRSR4G6NVYYEKF7DXKPJDBVWHBA3TXDTE72QDZ2JOFQ2QAESJHMHBISSLULGWV52FMFFR3OI25FS', '08:00:00', NULL, '22:30:00', 1),
(9, NULL, 'U6c5c4526913fe8bb90d55e7330d654d2', NULL, NULL, NULL, NULL, 0);

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
(1, 1, 'montuewedthufri', NULL),
(4, 1, 'montuewedthufrisat', NULL),
(4, 8, 'montue', NULL),
(4, 19, NULL, '2021-11-08 11:00:00'),
(5, 3, NULL, '2021-10-25 17:20:00'),
(7, 10, NULL, '2021-11-03 13:01:00'),
(7, 11, 'ALL', NULL),
(7, 13, 'ALL', NULL),
(7, 14, 'ALL', NULL),
(7, 15, 'ALL', NULL),
(7, 16, 'ALL', NULL),
(8, 1, 'montuewedthufri', NULL),
(8, 21, 'tuewedfri', NULL);

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `Alexa_coop`
--
ALTER TABLE `Alexa_coop`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `pass_id` (`pass_id`);

--
-- テーブルのインデックス `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`from_id`,`to_id`,`family_id`) USING BTREE;

--
-- テーブルのインデックス `family`
--
ALTER TABLE `family`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `pass` (`pass`);

--
-- テーブルのインデックス `family_user`
--
ALTER TABLE `family_user`
  ADD PRIMARY KEY (`family_id`,`user_id`);

--
-- テーブルのインデックス `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `send_log`
--
ALTER TABLE `send_log`
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
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `send_log`
--
ALTER TABLE `send_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=339;

--
-- ダンプしたテーブルの制約
--

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
