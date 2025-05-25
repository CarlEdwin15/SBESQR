-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 09:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sbesqr`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `house_no` varchar(255) DEFAULT NULL,
  `street_name` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `municipality_city` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'Philippines',
  `zip_code` varchar(255) DEFAULT NULL,
  `pob` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `house_no`, `street_name`, `barangay`, `municipality_city`, `province`, `country`, `zip_code`, `pob`, `created_at`, `updated_at`) VALUES
(1, '063', 'Zone 2', 'Sta. Barbara', 'Nabua', 'Camarines Sur', 'Philippines', '4434', 'Nabua Camarines Sur', '2025-05-23 23:08:43', '2025-05-24 16:21:26'),
(2, '063', 'Zone 2', 'Sta. Barbara', 'Nabua', 'Camarines Sur', 'Philippines', '4434', 'Nabua Camarines Sur', '2025-05-24 01:58:40', '2025-05-24 01:58:40'),
(3, '063', 'Zone 2', 'Sta. Barbara', 'Nabua', 'Camarines Sur', 'Philippines', '4434', 'Nabua Camarines Sur', '2025-05-24 02:19:34', '2025-05-24 02:19:34');

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `status` enum('present','absent','late') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('sbesqr_cache_c525a5357e97fef8d3db25841c86da1a', 'i:1;', 1748137327),
('sbesqr_cache_c525a5357e97fef8d3db25841c86da1a:timer', 'i:1748137327;', 1748137327),
('sbesqr_cache_conde@gmail.com|127.0.0.1', 'i:1;', 1748136332),
('sbesqr_cache_conde@gmail.com|127.0.0.1:timer', 'i:1748136332;', 1748136332),
('sbesqr_cache_d855a50e7824d116a934504aa5d16b69', 'i:1;', 1748136331),
('sbesqr_cache_d855a50e7824d116a934504aa5d16b69:timer', 'i:1748136331;', 1748136331),
('sbesqr_cache_dd4dfe1ace04fc91e561f719b71f131f', 'i:2;', 1748136360),
('sbesqr_cache_dd4dfe1ace04fc91e561f719b71f131f:timer', 'i:1748136360;', 1748136360);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `grade_level` enum('kindergarten','grade1','grade2','grade3','grade4','grade5','grade6') NOT NULL,
  `section` enum('A','B','C','D','E','F') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `grade_level`, `section`, `created_at`, `updated_at`) VALUES
(1, 'kindergarten', 'A', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(2, 'kindergarten', 'B', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(3, 'kindergarten', 'C', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(4, 'kindergarten', 'D', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(5, 'kindergarten', 'E', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(6, 'kindergarten', 'F', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(7, 'grade1', 'A', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(8, 'grade1', 'B', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(9, 'grade1', 'C', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(10, 'grade1', 'D', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(11, 'grade1', 'E', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(12, 'grade1', 'F', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(13, 'grade2', 'A', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(14, 'grade2', 'B', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(15, 'grade2', 'C', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(16, 'grade2', 'D', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(17, 'grade2', 'E', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(18, 'grade2', 'F', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(19, 'grade3', 'A', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(20, 'grade3', 'B', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(21, 'grade3', 'C', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(22, 'grade3', 'D', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(23, 'grade3', 'E', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(24, 'grade3', 'F', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(25, 'grade4', 'A', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(26, 'grade4', 'B', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(27, 'grade4', 'C', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(28, 'grade4', 'D', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(29, 'grade4', 'E', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(30, 'grade4', 'F', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(31, 'grade5', 'A', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(32, 'grade5', 'B', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(33, 'grade5', 'C', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(34, 'grade5', 'D', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(35, 'grade5', 'E', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(36, 'grade5', 'F', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(37, 'grade6', 'A', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(38, 'grade6', 'B', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(39, 'grade6', 'C', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(40, 'grade6', 'D', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(41, 'grade6', 'E', '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(42, 'grade6', 'F', '2025-05-23 23:05:46', '2025-05-23 23:05:46');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_00_01_235523_create_classes_table', 1),
(2, '0001_01_01_000000_create_users_table', 1),
(3, '0001_01_01_000001_create_cache_table', 1),
(4, '2025_03_18_113515_add_two_factor_columns_to_users_table', 1),
(5, '2025_03_18_113550_create_personal_access_tokens_table', 1),
(6, '2025_04_02_115400_create_students_table', 1),
(7, '2025_05_24_004803_create_student_ids_table', 1),
(8, '2025_05_24_005211_create_attendances_table', 1),
(9, '2025_05_24_005245_create_sf_reports_table', 1),
(10, '2025_05_24_005315_create_sms_logs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `parent_info`
--

CREATE TABLE `parent_info` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `father_fName` varchar(255) DEFAULT NULL,
  `father_mName` varchar(255) DEFAULT NULL,
  `father_lName` varchar(255) DEFAULT NULL,
  `father_phone` varchar(255) DEFAULT NULL,
  `mother_fName` varchar(255) DEFAULT NULL,
  `mother_mName` varchar(255) DEFAULT NULL,
  `mother_lName` varchar(255) DEFAULT NULL,
  `mother_phone` varchar(255) DEFAULT NULL,
  `guardian_fName` varchar(255) DEFAULT NULL,
  `guardian_mName` varchar(255) DEFAULT NULL,
  `guardian_lName` varchar(255) DEFAULT NULL,
  `guardian_phone` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parent_info`
--

INSERT INTO `parent_info` (`id`, `father_fName`, `father_mName`, `father_lName`, `father_phone`, `mother_fName`, `mother_mName`, `mother_lName`, `mother_phone`, `guardian_fName`, `guardian_mName`, `guardian_lName`, `guardian_phone`, `created_at`, `updated_at`) VALUES
(1, 'Edwin', 'Clemente', 'Conde', '12312435467', 'Juvy', 'Beato', 'Vasquez', '12323534667', NULL, NULL, NULL, NULL, '2025-05-23 23:08:43', '2025-05-24 16:21:26'),
(2, 'Edwin', 'Clemente', 'Conde', '12312435467', 'Juvy', 'Beato', 'Barrio', '12323534667', 'Carlo', 'Edwino', 'Conde', '12323455768', '2025-05-24 01:58:40', '2025-05-24 01:58:40'),
(3, 'Edwin', 'Clemente', 'Conde', '12312435467', 'Joeberta', 'Beato', 'Barrio', '12323534667', 'Carlo', 'Edwino', 'Conde', '12323455768', '2025-05-24 02:19:34', '2025-05-24 02:19:34');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ubssRTQxGmgOjocNrwKCtRXIrJYtIkFR9fxHMIfN', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZkoycHgzdWwwRTZJdklBRVM4UzNNYlNSUEZUV1pOMmZsMmozeTA4eSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1748155693);

-- --------------------------------------------------------

--
-- Table structure for table `sf_reports`
--

CREATE TABLE `sf_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `form_type` enum('SF1','SF2') NOT NULL,
  `school_year` varchar(255) NOT NULL,
  `month` enum('January','February','March','April','May','June','July','August','September','October','November','December') NOT NULL,
  `export_format` enum('PDF','Excel') NOT NULL,
  `generated_by` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(2048) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_logs`
--

CREATE TABLE `sms_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED NOT NULL,
  `parent_name` varchar(255) DEFAULT NULL,
  `parent_phone` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `student_lrn` varchar(20) NOT NULL,
  `student_lName` varchar(255) NOT NULL,
  `student_fName` varchar(255) NOT NULL,
  `student_mName` varchar(255) DEFAULT NULL,
  `student_extName` varchar(45) DEFAULT NULL,
  `student_dob` date DEFAULT NULL,
  `student_sex` enum('male','female') NOT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `student_photo` varchar(2048) DEFAULT NULL,
  `address_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `class_id`, `student_lrn`, `student_lName`, `student_fName`, `student_mName`, `student_extName`, `student_dob`, `student_sex`, `qr_code`, `student_photo`, `address_id`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 1, '112828080020', 'Conde', 'Carl Edwin', 'Vasquez', NULL, '2025-05-24', 'male', 'QR6831707b26074', 'student_profile_photos/cqm7gTmcDKIJUScnzCatqQxkHhPsd2ikKP2esu6P.jpg', 1, 1, '2025-05-23 23:08:43', '2025-05-24 16:21:26'),
(3, 1, '112828080900', 'Gavina', 'John Renz', 'Barrio', 'Jr', '2019-01-25', 'male', 'QR68319d36e11af', 'student_profile_photos/fNOmZw2gZpWHrDJplrIWi3ndmlvoSeUy9PSKyLzQ.jpg', 3, 3, '2025-05-24 02:19:34', '2025-05-24 02:19:34');

-- --------------------------------------------------------

--
-- Table structure for table `student_ids`
--

CREATE TABLE `student_ids` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `school_year` varchar(255) NOT NULL,
  `status` enum('active','inactive','expired') NOT NULL,
  `qr_code` varchar(255) NOT NULL,
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED DEFAULT NULL,
  `firstName` varchar(255) DEFAULT NULL,
  `lastName` varchar(255) DEFAULT NULL,
  `middleName` varchar(255) DEFAULT NULL,
  `extName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('teacher','admin') NOT NULL DEFAULT 'teacher',
  `gender` enum('male','female') DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `house_no` varchar(255) DEFAULT NULL,
  `street_name` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `municipality_city` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'Philippines',
  `zip_code` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `class_id`, `firstName`, `lastName`, `middleName`, `extName`, `email`, `role`, `gender`, `phone`, `house_no`, `street_name`, `barangay`, `municipality_city`, `province`, `country`, `zip_code`, `dob`, `email_verified_at`, `password`, `remember_token`, `current_team_id`, `profile_photo`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Admin', NULL, NULL, '', 'admin@gmail.com', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Philippines', NULL, NULL, NULL, '$2y$12$NGf7khbjq5yD9cBysM3Gx.8MNMvdUpvhckxrG4OXIHdWuMeaDY87y', NULL, NULL, NULL, '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(2, NULL, 'Admin 2', NULL, NULL, '', 'admin2@gmail.com', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Philippines', NULL, NULL, NULL, '$2y$12$TF1Z6tykhXPuYGwvspHdjeE/QiYaQrqUBl89fYSYZKNXJZXW.Zoq.', NULL, NULL, NULL, '2025-05-23 23:05:46', '2025-05-23 23:05:46'),
(7, 37, 'Carl Edwin', 'Conde', 'Vasquez', 'III', 'conde@gmail.com', 'teacher', 'male', '09519323506', '063', 'Zone 2', 'Sta. Barbara', 'Nabua', 'Camarines Sur', 'Philippines', '4434', '2003-02-15', NULL, '$2y$12$2Nwa0iH.JyQnX1O7bz/cv.dLWqCpPQEaKRfPVpjfuTvhrra5bDB2G', NULL, NULL, 'profile_photos/RfyTIrImxLzN9UuI1Q7LLmD4DHUwwQLmInaz8IWJ.jpg', '2025-05-24 20:56:22', '2025-05-24 20:56:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_student_id_foreign` (`student_id`),
  ADD KEY `attendances_teacher_id_foreign` (`teacher_id`),
  ADD KEY `attendances_class_id_foreign` (`class_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_class_section` (`grade_level`,`section`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parent_info`
--
ALTER TABLE `parent_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sf_reports`
--
ALTER TABLE `sf_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sf_reports_class_id_foreign` (`class_id`),
  ADD KEY `sf_reports_generated_by_foreign` (`generated_by`);

--
-- Indexes for table `sms_logs`
--
ALTER TABLE `sms_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sms_logs_student_id_foreign` (`student_id`),
  ADD KEY `sms_logs_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_student_lrn_unique` (`student_lrn`),
  ADD UNIQUE KEY `students_address_id_unique` (`address_id`),
  ADD UNIQUE KEY `students_parent_id_unique` (`parent_id`),
  ADD KEY `students_class_id_foreign` (`class_id`);

--
-- Indexes for table `student_ids`
--
ALTER TABLE `student_ids`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_ids_qr_code_unique` (`qr_code`),
  ADD KEY `student_ids_student_id_foreign` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_class_id_foreign` (`class_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `parent_info`
--
ALTER TABLE `parent_info`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sf_reports`
--
ALTER TABLE `sf_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_logs`
--
ALTER TABLE `sms_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_ids`
--
ALTER TABLE `student_ids`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sf_reports`
--
ALTER TABLE `sf_reports`
  ADD CONSTRAINT `sf_reports_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sf_reports_generated_by_foreign` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sms_logs`
--
ALTER TABLE `sms_logs`
  ADD CONSTRAINT `sms_logs_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `parent_info` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sms_logs_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `parent_info` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_ids`
--
ALTER TABLE `student_ids`
  ADD CONSTRAINT `student_ids_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
