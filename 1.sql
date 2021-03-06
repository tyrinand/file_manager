-- --------------------------------------------------------
-- Хост:                         localhost
-- Версия сервера:               5.7.24 - MySQL Community Server (GPL)
-- Операционная система:         Win64
-- HeidiSQL Версия:              9.5.0.5332
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Дамп структуры базы данных cloud3
CREATE DATABASE IF NOT EXISTS `cloud3` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `cloud3`;

-- Дамп структуры для таблица cloud3.files
CREATE TABLE IF NOT EXISTS `files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `server_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `server_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `public_url` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `files_slug_unique` (`slug`),
  UNIQUE KEY `files_server_path_unique` (`server_path`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы cloud3.files: ~5 rows (приблизительно)
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` (`id`, `created_at`, `updated_at`, `user_id`, `user_name`, `size`, `server_name`, `server_path`, `parent`, `slug`, `deleted_at`, `public_url`) VALUES
	(2, '2019-05-24 20:06:09', '2019-05-24 20:06:09', 1, '1.docx', 16988, '1.docx', 'public\\tyrinand\\11\\12\\15\\1.docx', 15, '3306eb2c-c747-4510-9a7a-76596729ae04', NULL, 0),
	(3, '2019-05-24 20:06:30', '2019-05-24 20:06:30', 1, '2.docx', 16481, '2.docx', 'public\\tyrinand\\11\\13\\2.docx', 13, '8c846baa-c0dd-4007-889c-1140e911fed3', NULL, 0),
	(4, '2019-05-24 20:06:45', '2019-05-24 20:06:45', 1, '3.docx', 23032, '3.docx', 'public\\tyrinand\\11\\14\\3.docx', 14, 'e6d73fac-6130-4209-b0c4-7980e8e0153c', NULL, 0),
	(5, '2019-05-24 20:06:56', '2019-05-27 09:29:59', 1, '1.docx', 16988, '1.docx', 'public\\tyrinand\\11\\1.docx', 11, '893ad8b0-cc53-4b72-ac43-beb5af4ac4f5', NULL, 1),
	(6, '2019-05-24 21:28:32', '2019-05-24 21:28:32', 1, '2.docx', 16481, '2.docx', 'public\\tyrinand\\11\\12\\2.docx', 12, 'd4eb75aa-d1b3-4a33-b461-75f93fbeafab', NULL, 0),
	(8, '2019-05-28 12:02:19', '2019-05-28 12:02:19', 5, '2.docx', 16481, '2.docx', 'public\\test\\26\\2.docx', 26, '0d22c199-9acd-4461-a343-20435ef00ba4', NULL, 0);
/*!40000 ALTER TABLE `files` ENABLE KEYS */;

-- Дамп структуры для таблица cloud3.folders
CREATE TABLE IF NOT EXISTS `folders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `server_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `root` tinyint(1) NOT NULL DEFAULT '0',
  `parent` int(10) unsigned DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `root_mount` tinyint(1) NOT NULL DEFAULT '0',
  `public_folder` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `folders_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы cloud3.folders: ~11 rows (приблизительно)
/*!40000 ALTER TABLE `folders` DISABLE KEYS */;
INSERT INTO `folders` (`id`, `user_name`, `user_id`, `server_name`, `root`, `parent`, `slug`, `created_at`, `updated_at`, `title`, `root_mount`, `public_folder`) VALUES
	(1, 'public\\tyrinand', 1, 'public\\tyrinand', 1, NULL, 'de21efbf-e555-4536-8744-2a6cd607a549', '2019-05-04 15:48:44', '2019-05-04 15:48:44', 'tyrinand', 0, 0),
	(6, 'public\\SuperUser', 3, 'public\\SuperUser', 1, NULL, '34502309-8208-457f-b48b-e6b252b68230', '2019-05-06 14:22:33', '2019-05-06 14:22:33', 'SuperUser', 0, 0),
	(7, '6545656', 1, 'public\\tyrinand\\7', 0, 1, '26c6928c-6a64-425d-b10a-f0d13efd98f3', '2019-05-21 16:01:11', '2019-05-21 16:01:11', 'tyrinand\\6545656', 0, 0),
	(8, '3TApBO', 1, 'public\\tyrinand\\8', 0, 1, 'b2266cdf-0f25-436c-90a6-3cc85c7f2bfd', '2019-05-21 17:40:25', '2019-05-21 17:40:25', 'tyrinand\\3TApBO', 0, 0),
	(9, 'public\\AVTyurin', 4, 'public\\AVTyurin', 1, NULL, 'bc5793fd-6e7b-4c89-ad60-682fc7fdf00e', '2019-05-22 09:40:07', '2019-05-22 09:40:07', 'AVTyurin', 0, 0),
	(11, 'диплом', 1, 'public\\tyrinand\\11', 0, 1, '982b279e-82b6-43ed-98ea-20cbfbb7efdb', '2019-05-24 20:05:08', '2019-05-28 11:24:16', 'tyrinand\\диплом', 1, 1),
	(12, 'пояснительная записка', 1, 'public\\tyrinand\\11\\12', 0, 11, 'fc9ce1b2-fa52-4712-b148-5381e901b938', '2019-05-24 20:05:21', '2019-05-28 11:24:16', 'tyrinand\\диплом\\пояснительная записка', 0, 1),
	(13, 'техническое задание', 1, 'public\\tyrinand\\11\\13', 0, 11, '1b46d773-dd26-4ea9-a59f-a9a479d41668', '2019-05-24 20:05:34', '2019-05-28 11:24:16', 'tyrinand\\диплом\\техническое задание', 0, 1),
	(14, 'код программы', 1, 'public\\tyrinand\\11\\14', 0, 11, 'b1bcb37f-cd77-477f-91c6-e38bde134ce4', '2019-05-24 20:05:45', '2019-05-28 11:24:16', 'tyrinand\\диплом\\код программы', 0, 1),
	(15, 'кек', 1, 'public\\tyrinand\\11\\12\\15', 0, 12, '4f8cb875-c459-4360-80e8-7fd90e81844b', '2019-05-24 20:05:55', '2019-05-28 11:24:16', 'tyrinand\\диплом\\пояснительная записка\\кек', 0, 1),
	(16, 'd', 1, 'public\\tyrinand\\16', 0, 1, '24c9d799-7ce6-4840-8365-68866e524755', '2019-05-27 08:39:05', '2019-05-27 08:39:05', 'tyrinand\\d', 0, 0),
	(17, 'temp', 1, 'public\\tyrinand\\11\\17', 0, 11, '0203315f-1ddc-4281-99b5-b6c7e053ffb8', '2019-05-28 10:01:34', '2019-05-28 11:24:16', 'tyrinand\\диплом\\temp', 0, 1),
	(18, 'fdsfs', 1, 'public\\tyrinand\\11\\18', 0, 11, 'c18dc56a-d38a-425f-9a83-fb2fd6c06733', '2019-05-28 10:10:01', '2019-05-28 11:24:16', 'tyrinand\\диплом\\fdsfs', 0, 1),
	(19, '1', 4, 'public\\AVTyurin\\19', 0, 9, '6bf30572-d9dc-4721-8a09-a961a2c8197d', '2019-05-28 10:17:33', '2019-05-28 10:17:33', 'AVTyurin\\1', 0, 0),
	(20, '3', 4, 'public\\AVTyurin\\20', 0, 9, '04dae28e-f931-49d6-8af9-39c984ea2f75', '2019-05-28 10:17:38', '2019-05-28 10:17:38', 'AVTyurin\\3', 0, 0),
	(21, 'test', 4, 'public\\AVTyurin\\21', 0, 9, 'd898cacf-7b7b-48f2-8aa7-c39945b9146c', '2019-05-28 10:17:42', '2019-05-28 10:17:42', 'AVTyurin\\test', 0, 0),
	(22, 'public\\test', 5, 'public\\test', 1, NULL, '111e4340-e451-45d4-8377-d3037c20d7b3', '2019-05-28 10:35:28', '2019-05-28 10:35:28', 'test', 0, 0),
	(26, 'dasdadasd', 5, 'public\\test\\26', 0, 22, '7703e45f-6342-4b6e-8380-678b9ac9deff', '2019-05-28 11:14:56', '2019-05-28 12:05:23', 'test\\dasdadasd', 1, 1);
/*!40000 ALTER TABLE `folders` ENABLE KEYS */;

-- Дамп структуры для таблица cloud3.groups
CREATE TABLE IF NOT EXISTS `groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `user_login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `public_folder_count` int(10) unsigned NOT NULL,
  `user_sub_count` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы cloud3.groups: ~1 rows (приблизительно)
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` (`id`, `title`, `user_id`, `user_login`, `slug`, `created_at`, `updated_at`, `public_folder_count`, `user_sub_count`) VALUES
	(3, 'Вторая группа', 4, 'AVTyurin', 'fc2281bf-6cb0-4409-8975-8e548aec8b90', '2019-05-27 09:18:11', '2019-05-28 12:02:39', 2, 2),
	(5, 'первая группа', 1, 'tyrinand', 'cce95a18-ff7c-46e3-b0a2-d0f320ab1324', '2019-05-28 10:37:05', '2019-05-28 11:16:16', 1, 1),
	(10, 'Дипломы 2015', 4, 'AVTyurin', '7cafa48c-f4af-47b0-85e1-d101015b7756', '2019-05-28 12:04:58', '2019-05-28 12:05:22', 1, 1);
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;

-- Дамп структуры для таблица cloud3.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы cloud3.migrations: ~15 rows (приблизительно)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2019_04_08_124603_add_use_size', 1),
	(4, '2019_04_09_091039_create_folders_table', 1),
	(5, '2019_04_09_192202_add_col', 1),
	(6, '2019_04_14_182912_create_files_table', 1),
	(7, '2019_05_03_123718_add_dete_file_col', 2),
	(8, '2019_05_04_154636_public_url', 3),
	(9, '2019_05_06_131621_set_uniqie_server_path', 4),
	(10, '2019_05_26_120052_create_groups_table', 5),
	(11, '2019_05_26_154119_add_public_count', 6),
	(12, '2019_05_26_172130_create_sub_users_table', 7),
	(13, '2019_05_27_083542_mount_bool', 8),
	(14, '2019_05_27_084109_create_public_folders_table', 9),
	(15, '2019_05_27_104817_folder_id', 10);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Дамп структуры для таблица cloud3.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы cloud3.password_resets: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;

-- Дамп структуры для таблица cloud3.public_folders
CREATE TABLE IF NOT EXISTS `public_folders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `holder_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `root_mount` int(10) unsigned NOT NULL,
  `sub_user` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `folder_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы cloud3.public_folders: ~5 rows (приблизительно)
/*!40000 ALTER TABLE `public_folders` DISABLE KEYS */;
INSERT INTO `public_folders` (`id`, `holder_id`, `group_id`, `root_mount`, `sub_user`, `created_at`, `updated_at`, `folder_id`) VALUES
	(71, 4, 3, 11, 1, '2019-05-28 11:24:16', '2019-05-28 11:24:16', 17),
	(72, 4, 3, 11, 1, '2019-05-28 11:24:16', '2019-05-28 11:24:16', 14),
	(73, 4, 3, 11, 1, '2019-05-28 11:24:16', '2019-05-28 11:24:16', 11),
	(74, 4, 3, 11, 1, '2019-05-28 11:24:16', '2019-05-28 11:24:16', 13),
	(75, 4, 3, 11, 1, '2019-05-28 11:24:16', '2019-05-28 11:24:16', 18),
	(76, 4, 3, 11, 1, '2019-05-28 11:24:16', '2019-05-28 11:24:16', 12),
	(77, 4, 3, 11, 1, '2019-05-28 11:24:16', '2019-05-28 11:24:16', 15),
	(78, 4, 3, 26, 5, '2019-05-28 12:02:39', '2019-05-28 12:02:39', 26),
	(79, 4, 10, 26, 5, '2019-05-28 12:05:23', '2019-05-28 12:05:23', 26);
/*!40000 ALTER TABLE `public_folders` ENABLE KEYS */;

-- Дамп структуры для таблица cloud3.sub_users
CREATE TABLE IF NOT EXISTS `sub_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы cloud3.sub_users: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `sub_users` DISABLE KEYS */;
INSERT INTO `sub_users` (`id`, `user_id`, `group_id`, `created_at`, `updated_at`) VALUES
	(3, 1, 3, '2019-05-27 09:18:42', '2019-05-27 09:18:42'),
	(5, 5, 5, '2019-05-28 10:37:33', '2019-05-28 10:37:33'),
	(7, 5, 3, '2019-05-28 12:02:08', '2019-05-28 12:02:08'),
	(8, 5, 10, '2019-05-28 12:05:12', '2019-05-28 12:05:12');
/*!40000 ALTER TABLE `sub_users` ENABLE KEYS */;

-- Дамп структуры для таблица cloud3.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  `size` int(11) NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `use_size` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_login_unique` (`login`),
  UNIQUE KEY `users_path_unique` (`path`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Дамп данных таблицы cloud3.users: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `login`, `path`, `enable`, `size`, `password`, `remember_token`, `created_at`, `updated_at`, `use_size`) VALUES
	(1, 'tyrinand', 'public\\tyrinand', 1, 100, '$2y$10$ROIQaIQ907QEyga6dl4AZ.QdE74fE09j582ikU.GDqL7Fht8xyICu', NULL, '2019-05-04 15:48:43', '2019-05-26 18:03:02', 89970),
	(3, 'SuperUser', 'public\\SuperUser', 1, 250, '$2y$10$/CS9xfTFaFpE2IE9hrTKQum9Ha7kPdi660RrFIKjuzc/DmU9Fq7Nm', NULL, '2019-05-06 14:22:32', '2019-05-07 11:49:39', 0),
	(4, 'AVTyurin', 'public\\AVTyurin', 1, 250, '$2y$10$Op7sQT1qKo1QH0bwAc5yCeveZrDQM6eYDgOT4Lv4hLWWKX3hna2fa', NULL, '2019-05-22 09:40:07', '2019-05-28 10:17:19', 0),
	(5, 'test', 'public\\test', 1, 250, '$2y$10$bCwrMuqEfnNgi7P4Cyw1Wewisr/FzG2F68syYiVcMs6FUalzjAAvG', NULL, '2019-05-28 10:35:28', '2019-05-28 12:02:19', 16481);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
