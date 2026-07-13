-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 08, 2024 at 09:21 AM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school_live`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_02_01_095655_update_user_table', 2),
(6, '2024_02_01_100027_create_mst_chapters_table', 3),
(7, '2024_02_01_100251_create_mst_classes_table', 4),
(8, '2024_02_01_100339_update_mst_chapter_table', 5),
(9, '2024_02_01_100529_create_trn_chapter_assets_table', 6),
(10, '2024_02_01_100728_create_trn_chapter_teachers_table', 7),
(11, '2024_02_01_100844_create_trn_teacher_classes_table', 8),
(12, '2024_02_01_101059_update_trn_chapter_teachers_table', 9),
(13, '2014_10_12_100000_create_password_resets_table', 10),
(14, '2024_02_20_035130_change_foreign_key_to_json_on_my_table', 10),
(15, '2024_02_24_131924_add_column_to_table_name', 10),
(16, '2024_03_11_161018_create_jobs_table', 11);

-- --------------------------------------------------------

--
-- Table structure for table `mst_chapters`
--

CREATE TABLE `mst_chapters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `release_date` date NOT NULL,
  `chapter_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 0:Inactive',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `asset_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `class_data` json DEFAULT NULL,
  `visibility` int(11) NOT NULL DEFAULT '0' COMMENT '1:All Classes, 0:Class Specific'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mst_chapters`
--

INSERT INTO `mst_chapters` (`id`, `title`, `release_date`, `chapter_image`, `description`, `created_by`, `status`, `slug`, `asset_path`, `created_at`, `updated_at`, `class_data`, `visibility`) VALUES
(9, 'orientation', '2024-04-01', NULL, '<p><br></p><p><br></p><p>getting acquainted</p>', 1, 1, 'orientation-1711976076', 'QwMBKLPs', '2024-04-01 12:54:36', '2024-04-01 12:54:36', '[]', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mst_classes`
--

CREATE TABLE `mst_classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 0:Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mst_classes`
--

INSERT INTO `mst_classes` (`id`, `class_title`, `status`, `created_at`, `updated_at`) VALUES
(9, 'teacher orientation', 1, '2024-04-01 12:51:00', '2024-04-01 12:51:00');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trn_chapter_assets`
--

CREATE TABLE `trn_chapter_assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chapter_id` bigint(20) UNSIGNED NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 0:Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trn_chapter_teachers`
--

CREATE TABLE `trn_chapter_teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chapter_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 0:Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `seen_status` int(11) NOT NULL DEFAULT '0' COMMENT '1:Seen, 0:Unseen',
  `seen_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trn_teacher_classes`
--

CREATE TABLE `trn_teacher_classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 0:Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trn_teacher_classes`
--

INSERT INTO `trn_teacher_classes` (`id`, `teacher_id`, `class_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 10, 9, 1, '2024-04-01 07:19:38', '2024-04-01 07:19:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `role` int(11) NOT NULL DEFAULT '1' COMMENT '1:Super Admin, 2:Principal, 3:Teacher',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:Active, 0:Inactive',
  `created_by` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `phone_number`, `address`, `role`, `status`, `created_by`) VALUES
(1, 'Super Admin', 'admin@thenewschoolmdu.com', '2024-02-04 04:27:28', '$2y$10$pmYhx56vzIo1EWwosDVG/Oq0XpCCyU4KEh52ho4Z8APDbCpgg2pX6', NULL, '2024-02-04 04:27:28', '2024-02-04 04:27:28', '9011095147', 'Bizmo Technologies', 1, 1, 1),
(10, 'Abirami.T', 'oppilal20@gmail.com', NULL, '$2y$12$gl44EoZbTMJNnCVqLN6QTuAh426e9ex3KRBgIHcFd3A58cWlv8hEu', NULL, '2024-04-01 07:19:38', '2024-04-01 07:19:38', '7397749197', 'Kochadai,madurai.', 3, 1, 1),
(11, 'Oppilal', 'newschoolmdu@gmail.com', NULL, '$2y$12$cN61ZkdaFXSq3AoH9QyvweAbzW9AB.s3ceUEi2MnQvMzKJSbz8hIK', NULL, '2024-04-01 12:50:29', '2024-04-01 12:50:29', NULL, NULL, 2, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mst_chapters`
--
ALTER TABLE `mst_chapters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mst_chapters_created_by_foreign` (`created_by`);

--
-- Indexes for table `mst_classes`
--
ALTER TABLE `mst_classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `trn_chapter_assets`
--
ALTER TABLE `trn_chapter_assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trn_chapter_assets_chapter_id_foreign` (`chapter_id`);

--
-- Indexes for table `trn_chapter_teachers`
--
ALTER TABLE `trn_chapter_teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trn_chapter_teachers_chapter_id_foreign` (`chapter_id`),
  ADD KEY `trn_chapter_teachers_teacher_id_foreign` (`teacher_id`);

--
-- Indexes for table `trn_teacher_classes`
--
ALTER TABLE `trn_teacher_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trn_teacher_classes_teacher_id_foreign` (`teacher_id`),
  ADD KEY `trn_teacher_classes_class_id_foreign` (`class_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `mst_chapters`
--
ALTER TABLE `mst_chapters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `mst_classes`
--
ALTER TABLE `mst_classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trn_chapter_assets`
--
ALTER TABLE `trn_chapter_assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trn_chapter_teachers`
--
ALTER TABLE `trn_chapter_teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `trn_teacher_classes`
--
ALTER TABLE `trn_teacher_classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `mst_chapters`
--
ALTER TABLE `mst_chapters`
  ADD CONSTRAINT `mst_chapters_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trn_chapter_assets`
--
ALTER TABLE `trn_chapter_assets`
  ADD CONSTRAINT `trn_chapter_assets_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `mst_chapters` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trn_chapter_teachers`
--
ALTER TABLE `trn_chapter_teachers`
  ADD CONSTRAINT `trn_chapter_teachers_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `mst_chapters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trn_chapter_teachers_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trn_teacher_classes`
--
ALTER TABLE `trn_teacher_classes`
  ADD CONSTRAINT `trn_teacher_classes_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `mst_classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trn_teacher_classes_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
