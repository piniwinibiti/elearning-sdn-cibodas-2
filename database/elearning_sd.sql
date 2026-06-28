-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 28, 2026 at 02:11 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elearning_sd`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensis`
--

CREATE TABLE `absensis` (
  `id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `id_kelas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mata_pelajaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `status` enum('hadir','terlambat','alpha') COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `foto_bukti` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gurus`
--

CREATE TABLE `gurus` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mapel_ajar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kelas_wali` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gurus`
--

INSERT INTO `gurus` (`id`, `user_id`, `nip`, `mapel_ajar`, `id_kelas_wali`, `created_at`, `updated_at`) VALUES
(1, 2, '1234567890', 'Matematika', '6A', '2026-06-27 10:23:03', '2026-06-27 10:23:03'),
(2, 3, '7475126451', 'Bahasa Indonesia', '6A', '2026-06-27 10:23:04', '2026-06-27 10:23:04'),
(3, 4, '4400111921', 'PJOK', NULL, '2026-06-27 10:23:04', '2026-06-27 10:23:04'),
(4, 5, '0993485667', 'IPA', NULL, '2026-06-27 10:23:04', '2026-06-27 10:23:04'),
(5, 6, '0653076316', 'Matematika', NULL, '2026-06-27 10:23:04', '2026-06-27 10:23:04'),
(6, 7, '6370569173', 'Matematika', NULL, '2026-06-27 10:23:04', '2026-06-27 10:23:04'),
(7, 8, '4475577569', 'IPA', NULL, '2026-06-27 10:23:05', '2026-06-27 10:23:05'),
(8, 9, '4991586074', 'PJOK', NULL, '2026-06-27 10:23:05', '2026-06-27 10:23:05'),
(9, 10, '9057885445', 'Seni Budaya', '3A', '2026-06-27 10:23:05', '2026-06-27 10:23:05'),
(10, 11, '3316373809', 'Seni Budaya', NULL, '2026-06-27 10:23:05', '2026-06-27 10:23:05'),
(11, 12, '2743059377', 'Seni Budaya', NULL, '2026-06-27 10:23:06', '2026-06-27 10:23:06'),
(12, 13, '2186351316', 'Bahasa Inggris', NULL, '2026-06-27 10:23:06', '2026-06-27 10:23:06'),
(13, 14, '2732278679', 'Bahasa Indonesia', NULL, '2026-06-27 10:23:06', '2026-06-27 10:23:06'),
(14, 15, '3367557322', 'IPA', '2A', '2026-06-27 10:23:06', '2026-06-27 10:23:06'),
(15, 16, '8687591106', 'Bahasa Inggris', '4B', '2026-06-27 10:23:06', '2026-06-27 10:23:06'),
(16, 17, '5228613723', 'IPS', NULL, '2026-06-27 10:23:07', '2026-06-27 10:23:07'),
(17, 18, '7267403849', 'Bahasa Inggris', NULL, '2026-06-27 10:23:07', '2026-06-27 10:23:07'),
(18, 19, '6135511215', 'PJOK', NULL, '2026-06-27 10:23:07', '2026-06-27 10:23:07'),
(19, 20, '2986449319', 'IPA', NULL, '2026-06-27 10:23:07', '2026-06-27 10:23:07'),
(20, 21, '5366605939', '-', NULL, '2026-06-27 10:23:08', '2026-06-27 12:02:22'),
(21, 22, '0236121351', 'Seni Budaya', NULL, '2026-06-27 10:23:08', '2026-06-27 10:23:08'),
(22, 74, '123456', '-', NULL, '2026-06-27 12:06:52', '2026-06-27 12:06:52');

-- --------------------------------------------------------

--
-- Table structure for table `guru_mapels`
--

CREATE TABLE `guru_mapels` (
  `id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `nama_mapel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwals`
--

CREATE TABLE `jadwals` (
  `id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `id_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_mapel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jawaban_tugas`
--

CREATE TABLE `jawaban_tugas` (
  `id` bigint UNSIGNED NOT NULL,
  `tugas_id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `file_jawaban` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nilai` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jawaban_ujian_essays`
--

CREATE TABLE `jawaban_ujian_essays` (
  `id` bigint UNSIGNED NOT NULL,
  `ujian_id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nilai` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jawaban_ujian_gandas`
--

CREATE TABLE `jawaban_ujian_gandas` (
  `id` bigint UNSIGNED NOT NULL,
  `ujian_id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `soal_ujian_id` bigint UNSIGNED NOT NULL,
  `jawaban_siswa` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_benar` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mapels`
--

CREATE TABLE `mapels` (
  `id` bigint UNSIGNED NOT NULL,
  `kode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_mapel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materis`
--

CREATE TABLE `materis` (
  `id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `id_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mata_pelajaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('pdf','video') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materis`
--

INSERT INTO `materis` (`id`, `guru_id`, `id_kelas`, `mata_pelajaran`, `judul`, `file_path`, `type`, `created_at`, `updated_at`) VALUES
(1, 1, '4A', NULL, 'Materi: Seni Tari Tradisional - Bagian 2', 'dummy/path/to/file.pdf', 'pdf', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(2, 1, '6B', NULL, 'Materi: Sistem Tata Surya - Bagian 2', 'dummy/path/to/file.pdf', 'pdf', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(3, 1, '6A', NULL, 'Materi: Sistem Tata Surya - Bagian 1', 'dummy/path/to/file.pdf', 'pdf', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(4, 1, '6B', NULL, 'Materi: Sistem Tata Surya - Bagian 1', 'dummy/path/to/file.pdf', 'video', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(5, 1, '6B', NULL, 'Materi: Mengenal Pahlawan Nasional - Bagian 1', 'dummy/path/to/file.pdf', 'video', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(6, 1, '4A', NULL, 'Materi: Kingdom Animalia - Bagian 1', 'dummy/path/to/file.pdf', 'pdf', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(7, 1, '4A', NULL, 'Materi: Aljabar Dasar - Bagian 2', 'dummy/path/to/file.pdf', 'pdf', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(8, 1, '6B', NULL, 'Materi: Sistem Tata Surya - Bagian 1', 'dummy/path/to/file.pdf', 'video', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(9, 1, '5A', NULL, 'Materi: Seni Tari Tradisional - Bagian 3', 'dummy/path/to/file.pdf', 'video', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(10, 1, '5A', NULL, 'Materi: Pecahan dan Desimal - Bagian 3', 'dummy/path/to/file.pdf', 'video', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(11, 1, '4A', NULL, 'Materi: Sistem Tata Surya - Bagian 3', 'dummy/path/to/file.pdf', 'pdf', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(12, 1, '4A', NULL, 'Materi: Kingdom Animalia - Bagian 2', 'dummy/path/to/file.pdf', 'video', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(13, 1, '6B', NULL, 'Materi: Puisi dan Pantun - Bagian 2', 'dummy/path/to/file.pdf', 'video', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(14, 1, '6A', NULL, 'Materi: Pecahan dan Desimal - Bagian 3', 'dummy/path/to/file.pdf', 'video', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(15, 1, '4A', NULL, 'Materi: Aljabar Dasar - Bagian 2', 'dummy/path/to/file.pdf', 'pdf', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(16, 1, '6B', NULL, 'Materi: Present Continuous Tense - Bagian 2', 'dummy/path/to/file.pdf', 'video', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(17, 1, '5A', NULL, 'Materi: Senam Irama - Bagian 1', 'dummy/path/to/file.pdf', 'pdf', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(18, 1, '6B', NULL, 'Materi: Seni Tari Tradisional - Bagian 1', 'dummy/path/to/file.pdf', 'pdf', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(19, 1, '4A', NULL, 'Materi: Mengenal Pahlawan Nasional - Bagian 1', 'dummy/path/to/file.pdf', 'video', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(20, 1, '5A', NULL, 'Materi: Puisi dan Pantun - Bagian 2', 'dummy/path/to/file.pdf', 'pdf', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(21, 1, '6A', NULL, 'Materi: Kingdom Animalia - Bagian 2', 'dummy/path/to/file.pdf', 'pdf', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(22, 1, '5A', NULL, 'Materi: Pecahan dan Desimal - Bagian 2', 'dummy/path/to/file.pdf', 'video', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(23, 1, '6B', NULL, 'Materi: Present Continuous Tense - Bagian 2', 'dummy/path/to/file.pdf', 'video', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(24, 1, '4A', NULL, 'Materi: Sejarah Kemerdekaan - Bagian 1', 'dummy/path/to/file.pdf', 'video', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(25, 1, '6A', NULL, 'Materi: Pecahan dan Desimal - Bagian 3', 'dummy/path/to/file.pdf', 'pdf', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(26, 1, '6A', NULL, 'Materi: Present Continuous Tense - Bagian 3', 'dummy/path/to/file.pdf', 'video', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(27, 1, '6A', NULL, 'Materi: Senam Irama - Bagian 3', 'dummy/path/to/file.pdf', 'pdf', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(28, 1, '6A', NULL, 'Materi: Sejarah Kemerdekaan - Bagian 2', 'dummy/path/to/file.pdf', 'pdf', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(29, 1, '5A', NULL, 'Materi: Sejarah Kemerdekaan - Bagian 1', 'dummy/path/to/file.pdf', 'video', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(30, 1, '5A', NULL, 'Materi: Puisi dan Pantun - Bagian 2', 'dummy/path/to/file.pdf', 'video', '2026-06-27 10:23:19', '2026-06-27 10:23:19');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_23_231248_create_siswas_table', 1),
(5, '2026_02_23_231623_create_gurus_table', 1),
(6, '2026_02_23_231624_create_absensis_table', 1),
(7, '2026_02_23_232914_create_materis_table', 1),
(8, '2026_02_23_232924_create_tugas_table', 1),
(9, '2026_02_23_232942_create_jawaban_tugas_table', 1),
(10, '2026_02_26_065059_add_id_kelas_wali_to_gurus_table', 1),
(11, '2026_03_08_212633_create_ujians_table', 1),
(12, '2026_03_08_212634_create_soal_ujians_table', 1),
(13, '2026_03_08_230518_add_tipe_to_ujians_table', 1),
(14, '2026_03_08_230837_create_jawaban_ujian_essays_table', 1),
(15, '2026_03_08_232550_add_file_soal_to_ujians_table', 1),
(16, '2026_04_15_041914_create_kelas_table', 1),
(17, '2026_04_15_053110_create_mapels_table', 1),
(18, '2026_04_15_053243_add_mata_pelajaran_to_study_tables', 1),
(19, '2026_04_15_120545_add_context_to_absensis_table', 1),
(20, '2026_04_15_120545_create_guru_mapels_table', 1),
(21, '2026_04_15_123455_create_jadwals_table', 1),
(22, '2026_04_15_131217_add_file_tugas_to_tugas_table', 1),
(23, '2026_04_16_014433_add_kode_to_mapels_table', 1),
(24, '2026_06_16_050413_create_jawaban_ujian_gandas_table', 1);

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
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('LMl11panxulWhulcrOrciUeL5VYcaHjrjXhxJF7t', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQU5SeFBPSHdzcTg4RFJUdklYMkdiZkNkYlo1dmJDYjZzTkFwQVlobiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1782590000),
('ZV73HwlKynTZbU7YRZQJlpd1CMBh7CegWGYJyFPq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.126.0 Chrome/148.0.7778.97 Electron/42.2.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS3lBbUxudzVpTUg2MkNpWXBwZXZ3eDF4OUFGWElOcXAwSVZ2N0FxMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1782586815);

-- --------------------------------------------------------

--
-- Table structure for table `siswas`
--

CREATE TABLE `siswas` (
  `siswa_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dataset_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `siswas`
--

INSERT INTO `siswas` (`siswa_id`, `user_id`, `nis`, `id_kelas`, `dataset_path`, `created_at`, `updated_at`) VALUES
(1, 23, '987654321', '6A', NULL, '2026-06-27 10:23:08', '2026-06-27 10:23:08'),
(2, 24, '787866625', '3A', NULL, '2026-06-27 10:23:08', '2026-06-27 10:23:08'),
(3, 25, '548827625', '3A', NULL, '2026-06-27 10:23:08', '2026-06-27 10:23:08'),
(4, 26, '713367208', '1B', NULL, '2026-06-27 10:23:09', '2026-06-27 10:23:09'),
(5, 27, '631291704', '6B', NULL, '2026-06-27 10:23:09', '2026-06-27 10:23:09'),
(6, 28, '254286984', '1A', NULL, '2026-06-27 10:23:09', '2026-06-27 10:23:09'),
(7, 29, '531525913', '6B', NULL, '2026-06-27 10:23:09', '2026-06-27 10:23:09'),
(8, 30, '890955250', '6B', NULL, '2026-06-27 10:23:10', '2026-06-27 10:23:10'),
(9, 31, '629255550', '1B', NULL, '2026-06-27 10:23:10', '2026-06-27 10:23:10'),
(10, 32, '069818505', '3A', NULL, '2026-06-27 10:23:10', '2026-06-27 10:23:10'),
(11, 33, '429453743', '6A', NULL, '2026-06-27 10:23:10', '2026-06-27 10:23:10'),
(12, 34, '904009352', '2B', NULL, '2026-06-27 10:23:10', '2026-06-27 10:23:10'),
(13, 35, '853343574', '2B', NULL, '2026-06-27 10:23:11', '2026-06-27 10:23:11'),
(14, 36, '291708321', '6B', NULL, '2026-06-27 10:23:11', '2026-06-27 10:23:11'),
(15, 37, '364460719', '2A', NULL, '2026-06-27 10:23:11', '2026-06-27 10:23:11'),
(16, 38, '813080523', '3B', NULL, '2026-06-27 10:23:11', '2026-06-27 10:23:11'),
(17, 39, '459658914', '4B', NULL, '2026-06-27 10:23:11', '2026-06-27 10:23:11'),
(18, 40, '915220502', '2B', NULL, '2026-06-27 10:23:12', '2026-06-27 10:23:12'),
(19, 41, '395675107', '4A', NULL, '2026-06-27 10:23:12', '2026-06-27 10:23:12'),
(20, 42, '229114580', '6A', NULL, '2026-06-27 10:23:12', '2026-06-27 10:23:12'),
(21, 43, '973445295', '3A', NULL, '2026-06-27 10:23:12', '2026-06-27 10:23:12'),
(22, 44, '830511560', '1A', NULL, '2026-06-27 10:23:13', '2026-06-27 10:23:13'),
(23, 45, '054030131', '1A', NULL, '2026-06-27 10:23:13', '2026-06-27 10:23:13'),
(24, 46, '306092222', '2B', NULL, '2026-06-27 10:23:13', '2026-06-27 10:23:13'),
(25, 47, '943470127', '2B', NULL, '2026-06-27 10:23:13', '2026-06-27 10:23:13'),
(26, 48, '738147867', '6B', NULL, '2026-06-27 10:23:13', '2026-06-27 10:23:13'),
(27, 49, '670647866', '1A', NULL, '2026-06-27 10:23:14', '2026-06-27 10:23:14'),
(28, 50, '489595323', '1B', NULL, '2026-06-27 10:23:14', '2026-06-27 10:23:14'),
(29, 51, '308784145', '1B', NULL, '2026-06-27 10:23:14', '2026-06-27 10:23:14'),
(30, 52, '327001720', '2A', NULL, '2026-06-27 10:23:14', '2026-06-27 10:23:14'),
(31, 53, '898788196', '4A', NULL, '2026-06-27 10:23:15', '2026-06-27 10:23:15'),
(32, 54, '328533151', '5A', NULL, '2026-06-27 10:23:15', '2026-06-27 10:23:15'),
(33, 55, '057711777', '6A', NULL, '2026-06-27 10:23:15', '2026-06-27 10:23:15'),
(34, 56, '442665442', '2A', NULL, '2026-06-27 10:23:15', '2026-06-27 10:23:15'),
(35, 57, '237103066', '4B', NULL, '2026-06-27 10:23:15', '2026-06-27 10:23:15'),
(36, 58, '721241186', '2A', NULL, '2026-06-27 10:23:16', '2026-06-27 10:23:16'),
(37, 59, '944579035', '1A', NULL, '2026-06-27 10:23:16', '2026-06-27 10:23:16'),
(38, 60, '949919143', '6A', NULL, '2026-06-27 10:23:16', '2026-06-27 10:23:16'),
(39, 61, '356295695', '6B', NULL, '2026-06-27 10:23:16', '2026-06-27 10:23:16'),
(40, 62, '173238747', '1B', NULL, '2026-06-27 10:23:17', '2026-06-27 10:23:17'),
(41, 63, '911907276', '3A', NULL, '2026-06-27 10:23:17', '2026-06-27 10:23:17'),
(42, 64, '531942379', '2A', NULL, '2026-06-27 10:23:17', '2026-06-27 10:23:17'),
(43, 65, '259766587', '3B', NULL, '2026-06-27 10:23:17', '2026-06-27 10:23:17'),
(44, 66, '063762623', '1A', NULL, '2026-06-27 10:23:17', '2026-06-27 10:23:17'),
(45, 67, '667768267', '1B', NULL, '2026-06-27 10:23:18', '2026-06-27 10:23:18'),
(46, 68, '074503019', '6A', NULL, '2026-06-27 10:23:18', '2026-06-27 10:23:18'),
(47, 69, '671790447', '3B', NULL, '2026-06-27 10:23:18', '2026-06-27 10:23:18'),
(48, 70, '063709757', '5B', NULL, '2026-06-27 10:23:18', '2026-06-27 10:23:18'),
(49, 71, '278143367', '3B', NULL, '2026-06-27 10:23:18', '2026-06-27 10:23:18'),
(50, 72, '743326301', '1A', NULL, '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(51, 73, '235596446', '1B', NULL, '2026-06-27 10:23:19', '2026-06-27 10:23:19');

-- --------------------------------------------------------

--
-- Table structure for table `soal_ujians`
--

CREATE TABLE `soal_ujians` (
  `id` bigint UNSIGNED NOT NULL,
  `ujian_id` bigint UNSIGNED NOT NULL,
  `pertanyaan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `opsi_a` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `opsi_b` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `opsi_c` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `opsi_d` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jawaban_benar` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `id_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mata_pelajaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instruksi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_tugas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deadline` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tugas`
--

INSERT INTO `tugas` (`id`, `guru_id`, `id_kelas`, `mata_pelajaran`, `judul`, `instruksi`, `file_tugas`, `deadline`, `created_at`, `updated_at`) VALUES
(1, 1, '6B', NULL, 'Tugas: Sistem Tata Surya - Latihan 5', 'Diskusikan dengan teman satu kelompokmu dan tuliskan hasil diskusinya dalam format PDF.49.\n\nCatatan tambahan: CHAPTER VIII. The Queen\'s argument was, that if something wasn\'t done about it while the rest of.', NULL, '2026-07-05 02:45:34', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(2, 1, '4A', NULL, 'Tugas: Sejarah Kemerdekaan - Latihan 2', 'Silakan kerjakan soal-soal berikut dari buku paket halaman .\n\nCatatan tambahan: Alice went on, \'if you don\'t like them!\' When the pie was all ridges and furrows; the balls were.', NULL, '2026-06-21 15:28:57', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(3, 1, '6A', NULL, 'Tugas: Aljabar Dasar - Latihan 4', 'Cari informasi tambahan di internet mengenai materi kita hari ini, lalu buatlah kesimpulan pribadimu..\n\nCatatan tambahan: Hatter: \'it\'s very interesting. I never was so much at this, that she had brought herself down to.', NULL, '2026-07-06 14:28:07', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(4, 1, '4A', NULL, 'Tugas: Present Continuous Tense - Latihan 4', 'Tontonlah video materi yang telah diunggah, lalu rangkum poin-poin pentingnya..\n\nCatatan tambahan: Alice caught the baby violently up and down in an offended tone, \'so I should have croqueted the.', NULL, '2026-07-09 00:44:33', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(5, 1, '6A', NULL, 'Tugas: Aljabar Dasar - Latihan 3', 'Silakan kerjakan soal-soal berikut dari buku paket halaman 24.\n\nCatatan tambahan: Cheshire Cat, she was quite pale (with passion, Alice thought), and it said in a trembling voice.', NULL, '2026-06-30 12:13:03', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(6, 1, '6A', NULL, 'Tugas: Sistem Tata Surya - Latihan 3', 'Cari informasi tambahan di internet mengenai materi kita hari ini, lalu buatlah kesimpulan pribadimu..\n\nCatatan tambahan: Heads below!\' (a loud crash)--\'Now, who did that?--It was Bill, I fancy--Who\'s to go on. \'And so.', NULL, '2026-06-21 07:19:02', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(7, 1, '6A', NULL, 'Tugas: Senam Irama - Latihan 3', 'Buatlah sebuah karangan singkat mengenai topik ini sepanjang minimal 3 paragraf.27.\n\nCatatan tambahan: White Rabbit. She was walking by the time they had any dispute with the tea,\' the March Hare had.', NULL, '2026-07-04 20:07:01', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(8, 1, '6A', NULL, 'Tugas: Kingdom Animalia - Latihan 4', 'Tontonlah video materi yang telah diunggah, lalu rangkum poin-poin pentingnya.48.\n\nCatatan tambahan: An obstacle that came between Him, and ourselves, and it. Don\'t let him know she liked them best.', NULL, '2026-06-25 13:52:03', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(9, 1, '4A', NULL, 'Tugas: Puisi dan Pantun - Latihan 2', 'Silakan kerjakan soal-soal berikut dari buku paket halaman .\n\nCatatan tambahan: WOULD not remember ever having heard of one,\' said Alice, as she was beginning to feel a little.', NULL, '2026-07-01 08:11:34', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(10, 1, '5A', NULL, 'Tugas: Seni Tari Tradisional - Latihan 1', 'Cari informasi tambahan di internet mengenai materi kita hari ini, lalu buatlah kesimpulan pribadimu.17.\n\nCatatan tambahan: So she set to work very carefully, nibbling first at one corner of it: \'No room! No room!\' they.', NULL, '2026-07-02 16:26:42', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(11, 1, '5A', NULL, 'Tugas: Senam Irama - Latihan 3', 'Kerjakan latihan soal di LKS Bab .\n\nCatatan tambahan: Fainting in Coils.\' \'What was THAT like?\' said Alice. \'Who\'s making personal remarks now?\' the.', NULL, '2026-06-24 06:38:30', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(12, 1, '6B', NULL, 'Tugas: Mengenal Pahlawan Nasional - Latihan 3', 'Kerjakan latihan soal di LKS Bab .\n\nCatatan tambahan: I beg your acceptance of this remark, and thought to herself. At this moment Alice appeared, she.', NULL, '2026-07-05 09:30:34', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(13, 1, '6A', NULL, 'Tugas: Senam Irama - Latihan 2', 'Cari informasi tambahan di internet mengenai materi kita hari ini, lalu buatlah kesimpulan pribadimu..\n\nCatatan tambahan: No, there were three little sisters--they were learning to draw, you know--\' She had just begun to.', NULL, '2026-06-27 16:17:37', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(14, 1, '6B', NULL, 'Tugas: Sejarah Kemerdekaan - Latihan 4', 'Diskusikan dengan teman satu kelompokmu dan tuliskan hasil diskusinya dalam format PDF..\n\nCatatan tambahan: I needn\'t be so stingy about it, and then dipped suddenly down, so suddenly that Alice said.', NULL, '2026-06-28 21:24:12', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(15, 1, '6A', NULL, 'Tugas: Puisi dan Pantun - Latihan 5', 'Cari informasi tambahan di internet mengenai materi kita hari ini, lalu buatlah kesimpulan pribadimu.49.\n\nCatatan tambahan: And yet I don\'t understand. Where did they live at the sides of the wood for fear of their wits!\'.', NULL, '2026-07-08 17:00:17', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(16, 1, '5A', NULL, 'Tugas: Senam Irama - Latihan 4', 'Diskusikan dengan teman satu kelompokmu dan tuliskan hasil diskusinya dalam format PDF..\n\nCatatan tambahan: NOT, being made entirely of cardboard.) \'All right, so far,\' thought Alice, \'to pretend to be.', NULL, '2026-06-29 02:22:08', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(17, 1, '5A', NULL, 'Tugas: Kingdom Animalia - Latihan 5', 'Kerjakan latihan soal di LKS Bab 22.\n\nCatatan tambahan: The Hatter opened his eyes. He looked anxiously over his shoulder as she came upon a little shriek.', NULL, '2026-07-04 05:25:32', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(18, 1, '4A', NULL, 'Tugas: Mengenal Pahlawan Nasional - Latihan 3', 'Silakan kerjakan soal-soal berikut dari buku paket halaman .\n\nCatatan tambahan: It means much the most interesting, and perhaps after all it might not escape again, and said.', NULL, '2026-06-26 08:38:18', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(19, 1, '5A', NULL, 'Tugas: Seni Tari Tradisional - Latihan 5', 'Silakan kerjakan soal-soal berikut dari buku paket halaman 44.\n\nCatatan tambahan: Alice to herself. (Alice had been broken to pieces. \'Please, then,\' said Alice, swallowing down.', NULL, '2026-07-10 06:13:44', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(20, 1, '4A', NULL, 'Tugas: Seni Tari Tradisional - Latihan 2', 'Buatlah sebuah karangan singkat mengenai topik ini sepanjang minimal 3 paragraf..\n\nCatatan tambahan: Queen shouted at the stick, and made believe to worry it; then Alice, thinking it was perfectly.', NULL, '2026-06-27 06:00:07', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(21, 1, '6B', NULL, 'Tugas: Sistem Tata Surya - Latihan 4', 'Tontonlah video materi yang telah diunggah, lalu rangkum poin-poin pentingnya.17.\n\nCatatan tambahan: Normans--\" How are you getting on now, my dear?\' it continued, turning to Alice, \'Have you guessed.', NULL, '2026-07-04 16:17:20', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(22, 1, '6A', NULL, 'Tugas: Puisi dan Pantun - Latihan 1', 'Cari informasi tambahan di internet mengenai materi kita hari ini, lalu buatlah kesimpulan pribadimu..\n\nCatatan tambahan: Rabbit say to this: so she took up the fan she was about a whiting to a lobster--\' (Alice began to.', NULL, '2026-06-29 17:40:28', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(23, 1, '6A', NULL, 'Tugas: Mengenal Pahlawan Nasional - Latihan 1', 'Tontonlah video materi yang telah diunggah, lalu rangkum poin-poin pentingnya.28.\n\nCatatan tambahan: HIM TO YOU,\"\' said Alice. \'Why, you don\'t know what to say it over) \'--yes, that\'s about the games.', NULL, '2026-07-06 00:05:31', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(24, 1, '5A', NULL, 'Tugas: Mengenal Pahlawan Nasional - Latihan 5', 'Kerjakan latihan soal di LKS Bab 13.\n\nCatatan tambahan: Cheshire Cat: now I shall be a Caucus-race.\' \'What IS a Caucus-race?\' said Alice; \'I must be kind.', NULL, '2026-07-08 16:14:23', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(25, 1, '5A', NULL, 'Tugas: Aljabar Dasar - Latihan 4', 'Diskusikan dengan teman satu kelompokmu dan tuliskan hasil diskusinya dalam format PDF.15.\n\nCatatan tambahan: Alice. \'Of course not,\' Alice replied in an offended tone. And the executioner myself,\' said the.', NULL, '2026-06-21 11:11:18', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(26, 1, '5A', NULL, 'Tugas: Puisi dan Pantun - Latihan 3', 'Cari informasi tambahan di internet mengenai materi kita hari ini, lalu buatlah kesimpulan pribadimu.46.\n\nCatatan tambahan: Quick, now!\' And Alice was more and more faintly came, carried on the second thing is to do this.', NULL, '2026-06-26 00:57:42', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(27, 1, '5A', NULL, 'Tugas: Aljabar Dasar - Latihan 3', 'Kerjakan latihan soal di LKS Bab .\n\nCatatan tambahan: Cat. \'I said pig,\' replied Alice; \'and I do it again and again.\' \'You are old,\' said the.', NULL, '2026-07-05 05:38:25', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(28, 1, '5A', NULL, 'Tugas: Sejarah Kemerdekaan - Latihan 3', 'Diskusikan dengan teman satu kelompokmu dan tuliskan hasil diskusinya dalam format PDF..\n\nCatatan tambahan: Alice looked down into its face in her brother\'s Latin Grammar, \'A mouse--of a mouse--to a.', NULL, '2026-07-02 21:33:38', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(29, 1, '4A', NULL, 'Tugas: Present Continuous Tense - Latihan 2', 'Cari informasi tambahan di internet mengenai materi kita hari ini, lalu buatlah kesimpulan pribadimu..\n\nCatatan tambahan: I shan\'t go, at any rate, there\'s no meaning in it, \'and what is the capital of Rome, and.', NULL, '2026-06-30 15:04:46', '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(30, 1, '6B', NULL, 'Tugas: Aljabar Dasar - Latihan 2', 'Silakan kerjakan soal-soal berikut dari buku paket halaman .\n\nCatatan tambahan: I gave her one, they gave him two, You gave us three or more; They all sat down and make one quite.', NULL, '2026-06-24 03:27:49', '2026-06-27 10:23:19', '2026-06-27 10:23:19');

-- --------------------------------------------------------

--
-- Table structure for table `ujians`
--

CREATE TABLE `ujians` (
  `id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `id_kelas` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mata_pelajaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` enum('ganda','essay') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ganda',
  `teks_essay` text COLLATE utf8mb4_unicode_ci,
  `file_soal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waktu_menit` int NOT NULL DEFAULT '60',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','guru','siswa') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$12$n3/PUQGxeEs1MBZ6wnFPeuHIPux9tnL1MR.vloeoddr83qCQj7Z02', 'Administrator', 'admin', NULL, '2026-06-27 10:23:03', '2026-06-27 10:23:03'),
(2, '1234567890', '$2y$12$nSBzGTjGYqafN0KZfJKbEexOCPK4up8krg23OHw89xefZQD9l5fZC', 'Budi Santoso, S.Pd', 'guru', NULL, '2026-06-27 10:23:03', '2026-06-27 10:23:03'),
(3, '7475126451', '$2y$12$lXR3DDXL9QEnD2ncF7mFTOyzyEzNH9kDaFP8TAtRSNstG2YHAd1sa', 'Yuliana Tiara Mardhiyah, S.Pd', 'guru', NULL, '2026-06-27 10:23:04', '2026-06-27 10:23:04'),
(4, '4400111921', '$2y$12$nDH4xWNrg6P2LNgcgDyDfeLTz8vcpWj8QyEB32DWI4VUW8yQHPlg6', 'Karsa Setiawan', 'guru', NULL, '2026-06-27 10:23:04', '2026-06-27 10:23:04'),
(5, '0993485667', '$2y$12$RwQozHLA7LofIx49IhoGYuy4bxMUmGk5w.Aot0tVLbbptNauxL.SG', 'Bakda Jaga Rajasa S.H., S.Pd', 'guru', NULL, '2026-06-27 10:23:04', '2026-06-27 10:23:04'),
(6, '0653076316', '$2y$12$gb4jdaFuobLw3ihh2AN4wuKRILvKSB.9L4MWhJ/UudcbUJo0T7tou', 'Adiarja Winarno', 'guru', NULL, '2026-06-27 10:23:04', '2026-06-27 10:23:04'),
(7, '6370569173', '$2y$12$GZzetnclZFW.gvLNkSFD4uQqDwDfVQZfKgYaQgr4w2rd8U3DEoZX2', 'Victoria Rahayu, S.Pd', 'guru', NULL, '2026-06-27 10:23:04', '2026-06-27 10:23:04'),
(8, '4475577569', '$2y$12$FuT97ICxCycBk7DM2egWWeCRYViMR47GS/GhJRt4gf5ySr5VXR.wC', 'Darmanto Firgantoro', 'guru', NULL, '2026-06-27 10:23:05', '2026-06-27 10:23:05'),
(9, '4991586074', '$2y$12$50ElkxLXAJ4466JgFJxKMe4uoUzVJjoj9ajiji.lpRykbRRndFV1e', 'Jarwa Maulana', 'guru', NULL, '2026-06-27 10:23:05', '2026-06-27 10:23:05'),
(10, '9057885445', '$2y$12$YlZK40yT0LVgAUitNCG1nuESDirb095aZuv3vuI20ia7l5IfOSVeK', 'Emin Widodo M.Farm, S.Pd', 'guru', NULL, '2026-06-27 10:23:05', '2026-06-27 10:23:05'),
(11, '3316373809', '$2y$12$tBTMo.G8fMAlVVst/TDZnetoN3ISn5lenNV2.c20dl2Gkflg4y/T6', 'Almira Lalita Mandasari S.Gz', 'guru', NULL, '2026-06-27 10:23:05', '2026-06-27 10:23:05'),
(12, '2743059377', '$2y$12$Tq2aoRBzFgatTwUy1KVOeuAZTmm.ILOsCb5yFRGegyNC6HfuJI6Uq', 'Laila Hartati, S.Pd', 'guru', NULL, '2026-06-27 10:23:06', '2026-06-27 10:23:06'),
(13, '2186351316', '$2y$12$UytyGSJrwC0K0INJOEj0w.wKQQFsGCcUPQS8Zo2mgGEKJBpxOaz9O', 'Martani Damanik M.TI., S.Pd', 'guru', NULL, '2026-06-27 10:23:06', '2026-06-27 10:23:06'),
(14, '2732278679', '$2y$12$AnpPJ/IcbPGt1kd4n.GTGeqByzfdtiloxc0og25iMJ1OkaF8nuZ9.', 'Saka Haryanto', 'guru', NULL, '2026-06-27 10:23:06', '2026-06-27 10:23:06'),
(15, '3367557322', '$2y$12$/tsPvPauYnYyXtuTWqdy8eqpCzYK3eh69dh.hMyuhc2gBlHcKpcMa', 'Lala Ophelia Palastri S.Kom', 'guru', NULL, '2026-06-27 10:23:06', '2026-06-27 10:23:06'),
(16, '8687591106', '$2y$12$GJnPSFw4JC4WSydVmNPi3u6NrCWHESO9SzlvCRQ/yZPNZtwWS8Gsy', 'Zulfa Wijayanti', 'guru', NULL, '2026-06-27 10:23:06', '2026-06-27 10:23:06'),
(17, '5228613723', '$2y$12$zu6qmwsK4r4xwSSzO8dVluDUaIaE9D3MCR9khT/lDf2lvY.TncPvu', 'Okta Budiyanto, S.Pd', 'guru', NULL, '2026-06-27 10:23:07', '2026-06-27 10:23:07'),
(18, '7267403849', '$2y$12$5uAplFWXPcOIK3tYbHez0OSLcnRu4Lb5luX50ewleMvypksI8vuGO', 'Galang Kenari Saragih, S.Pd', 'guru', NULL, '2026-06-27 10:23:07', '2026-06-27 10:23:07'),
(19, '6135511215', '$2y$12$kQFl.vyT4GHFPLPEwIIQ0uV4xI/TVflJ1aLbY1T9c4uJEZ7wTBxFS', 'Kalim Purwadi Maulana, S.Pd', 'guru', NULL, '2026-06-27 10:23:07', '2026-06-27 10:23:07'),
(20, '2986449319', '$2y$12$n8ZyUWrEpeW9XxRehkoFteF5CZRVBOtz/cS.nWSxNI1vUuvAfSKBK', 'Umi Yolanda, S.Pd', 'guru', NULL, '2026-06-27 10:23:07', '2026-06-27 10:23:07'),
(21, '5366605939', '$2y$12$qW/oRZa8t1rRODh0RV036exdj14Jk5jmK8POPBJbsSWu9wIrM.BsO', 'Mutia Hastuti, S.Pd', 'guru', NULL, '2026-06-27 10:23:08', '2026-06-27 10:23:08'),
(22, '0236121351', '$2y$12$dzDFsdsCXLx6TYIVItq2sOcnt4ZcIRAp4IZ.xqht2Wt3P8bSwBbLy', 'Raina Mayasari', 'guru', NULL, '2026-06-27 10:23:08', '2026-06-27 10:23:08'),
(23, '987654321', '$2y$12$zEkXWcBuJmhjkeQqXUthOOFlKlMcZ6kCRwOyGqOIkZcKRGpxaEp12', 'Ahmad Reza', 'siswa', NULL, '2026-06-27 10:23:08', '2026-06-27 10:23:08'),
(24, '787866625', '$2y$12$VWQiM60IXK9YBux0jmxcSuRoI0Oy07KPn8FnyHvJk7/BH3uYDp6Ce', 'Eko Zulkarnain', 'siswa', NULL, '2026-06-27 10:23:08', '2026-06-27 10:23:08'),
(25, '548827625', '$2y$12$22sJbkYS9PBhvNR7e2fzcuVmO0IJLv4HGqVvpMYGFjEOMj5nJOts6', 'Edi Himawan Anggriawan', 'siswa', NULL, '2026-06-27 10:23:08', '2026-06-27 10:23:08'),
(26, '713367208', '$2y$12$Jwd4sM3/9ItI/YILVlbyoeDYv1t0wMfKH4zIINwPwCKgGqFsbX06C', 'Zamira Farida', 'siswa', NULL, '2026-06-27 10:23:09', '2026-06-27 10:23:09'),
(27, '631291704', '$2y$12$Nb8ucsSlDKzQ1HccbgqlzODxQApHxuOLeQd2gt9aduzMDgeq98q.C', 'Prabawa Pranowo', 'siswa', NULL, '2026-06-27 10:23:09', '2026-06-27 10:23:09'),
(28, '254286984', '$2y$12$FB2GsxVq0maxqHf9l2Urj.ICd32uZaex54P1phlDYuHZKlu4WDTKC', 'Hani Usyi Sudiati', 'siswa', NULL, '2026-06-27 10:23:09', '2026-06-27 10:23:09'),
(29, '531525913', '$2y$12$tzxyVe.4.Q1hVPe7g6RwI.OGbwG7mh6f.6deIgYZhyl963920pK8y', 'Juli Unjani Lestari', 'siswa', NULL, '2026-06-27 10:23:09', '2026-06-27 10:23:09'),
(30, '890955250', '$2y$12$KRiUMtoytIdDmgB8.b87X.Pz1qXF2/mXzrI4Lljqe/R0PYNIqj26q', 'Syahrini Yuniar', 'siswa', NULL, '2026-06-27 10:23:10', '2026-06-27 10:23:10'),
(31, '629255550', '$2y$12$Nv.FpbjRIFDLmmlIKwSb0OSp/fmxsrSHi20RANaCeUfxMk81gEPwW', 'Hardana Hidayat M.Kom.', 'siswa', NULL, '2026-06-27 10:23:10', '2026-06-27 10:23:10'),
(32, '069818505', '$2y$12$LPFGef3UUE5ABajVcsHtZehaVqVTX39lUg82h6gdctbRPL4q7Nt4W', 'Salsabila Siti Lestari S.E.I', 'siswa', NULL, '2026-06-27 10:23:10', '2026-06-27 10:23:10'),
(33, '429453743', '$2y$12$zeTzdmhiBT3FLFhEn90QN.xIpa6bddrUqzV1.dTIyAuZeRCS8Umcq', 'Jelita Usamah', 'siswa', NULL, '2026-06-27 10:23:10', '2026-06-27 10:23:10'),
(34, '904009352', '$2y$12$EJ3epRVY43PcR/0IAhNcg.XBzy5BK/72aWRhHbJhE36Da/JxR06BW', 'Bagya Natsir', 'siswa', NULL, '2026-06-27 10:23:10', '2026-06-27 10:23:10'),
(35, '853343574', '$2y$12$kzCwJQgi1NOfsEl7ySGgROP5y4udyoLhhAF82h7TX/KiCcLEE6a2.', 'Uda Sitompul', 'siswa', NULL, '2026-06-27 10:23:11', '2026-06-27 10:23:11'),
(36, '291708321', '$2y$12$Oifp0wK.TloUO.q7NZaeouyd8CI.v6.FAuk.YEChFQfHhm8/0lbMG', 'Wawan Saragih S.Pt', 'siswa', NULL, '2026-06-27 10:23:11', '2026-06-27 10:23:11'),
(37, '364460719', '$2y$12$IHRCeQtTa2kpn3F4GW1yCuEKYJiv6U1wHENI5GOzUvvWX26tJSYeK', 'Marwata Hakim', 'siswa', NULL, '2026-06-27 10:23:11', '2026-06-27 10:23:11'),
(38, '813080523', '$2y$12$AvRjZ18ErjaFRwT6Hyia4O3f4IQEkFYIZEU3N/yu0BrOE5aqU12H.', 'Gatra Irawan', 'siswa', NULL, '2026-06-27 10:23:11', '2026-06-27 10:23:11'),
(39, '459658914', '$2y$12$7rJZQhMQLBiNLdw8.KASgOzXZoiypOX9oNknwlWPgx3ZpNUc6Ffe2', 'Cinta Pratiwi S.E.', 'siswa', NULL, '2026-06-27 10:23:11', '2026-06-27 10:23:11'),
(40, '915220502', '$2y$12$phlJZmsq122Iyl5VL3AFju7ngFzBG42t5lvEQxMNcKYhVSVQ87eTG', 'Septi Maryati', 'siswa', NULL, '2026-06-27 10:23:12', '2026-06-27 10:23:12'),
(41, '395675107', '$2y$12$K3PMWvCNJ/GG.o53YT3JquVQLWCnn6eZXuH2yZ2jZsNtz7W9YOaMO', 'Pranawa Hasan Suryono', 'siswa', NULL, '2026-06-27 10:23:12', '2026-06-27 10:23:12'),
(42, '229114580', '$2y$12$oeAl8gTZvjzJoKGvu6hijeHwdZ59ZLdRYe/LRMHj9v.NN9iCjeqWC', 'Intan Hani Lestari M.TI.', 'siswa', NULL, '2026-06-27 10:23:12', '2026-06-27 10:23:12'),
(43, '973445295', '$2y$12$QSBN9GE1HiPL0jrgTgURH.r3YTVWPWwwXd/NaSwocIpe4jFwPAWzK', 'Rika Namaga', 'siswa', NULL, '2026-06-27 10:23:12', '2026-06-27 10:23:12'),
(44, '830511560', '$2y$12$hf18U2CoZcIfXLc4MMtaM.eNuxDyf3E/6vuq.nhvSIh9uKXv1Y52W', 'Jaeman Gunawan S.Pd', 'siswa', NULL, '2026-06-27 10:23:13', '2026-06-27 10:23:13'),
(45, '054030131', '$2y$12$ewGEEGwFvR4WJuPUgyOmdepJWeH6e4BVghAG8XveRl7BAOj8YLmca', 'Ami Astuti', 'siswa', NULL, '2026-06-27 10:23:13', '2026-06-27 10:23:13'),
(46, '306092222', '$2y$12$enDZiy8NT.rUcdqs.4kRd.xXXSgmUPKqSqsU5yafTte9e1H/APDge', 'Muhammad Wacana', 'siswa', NULL, '2026-06-27 10:23:13', '2026-06-27 10:23:13'),
(47, '943470127', '$2y$12$QApBrGtckoSYRb1M0znbQ.HV71Ldq0xVQTEL17zWOzG.FOicAekgy', 'Ami Gabriella Aryani S.Kom', 'siswa', NULL, '2026-06-27 10:23:13', '2026-06-27 10:23:13'),
(48, '738147867', '$2y$12$ahHaruNWBdzzqUv3b1kQ0..MXdByHSTgi2bG0dQJsUNYcmDU6zQRe', 'Titi Oktaviani', 'siswa', NULL, '2026-06-27 10:23:13', '2026-06-27 10:23:13'),
(49, '670647866', '$2y$12$GftftB8i.DbnC3s6Qx8wQuZP0/gS6ZQfRepUQ6B9ByyyYnWVP007K', 'Tira Namaga', 'siswa', NULL, '2026-06-27 10:23:14', '2026-06-27 10:23:14'),
(50, '489595323', '$2y$12$zJV.AKaB.WZUwj4aszO9cevWDeAd1j5sZSvEA0v/7auL7D.unf48m', 'Raina Wijayanti S.Ked', 'siswa', NULL, '2026-06-27 10:23:14', '2026-06-27 10:23:14'),
(51, '308784145', '$2y$12$ZFVwEevYgaM1G0S.twGV5OrcKC/0gOpq0XzSEoHsddaYRjJl8THIm', 'Kurnia Taufan Winarno M.TI.', 'siswa', NULL, '2026-06-27 10:23:14', '2026-06-27 10:23:14'),
(52, '327001720', '$2y$12$VaG6gXFA3BVbhUEVjFFB6.MpI0qDCcVK/6l21rCSkA5TcnDS/AgQu', 'Vicky Wahyuni', 'siswa', NULL, '2026-06-27 10:23:14', '2026-06-27 10:23:14'),
(53, '898788196', '$2y$12$v0gcPeLkJrCc8ej/XKTKoOWW5juWowC91a9Ps7vWO17AqLPlc8WkW', 'Icha Nasyidah M.Farm', 'siswa', NULL, '2026-06-27 10:23:15', '2026-06-27 10:23:15'),
(54, '328533151', '$2y$12$dtxS5LaeNI.94U9lg55xK.779aTzJEjD06tnmwXtrL/OL/TD1XlwW', 'Gamanto Kasiran Prakasa M.TI.', 'siswa', NULL, '2026-06-27 10:23:15', '2026-06-27 10:23:15'),
(55, '057711777', '$2y$12$TJyIF0Slkx7/zzDjJXz/lutUtdQAIyQeDKre9KoXPNt.HLKivntRG', 'Amelia Agustina', 'siswa', NULL, '2026-06-27 10:23:15', '2026-06-27 10:23:15'),
(56, '442665442', '$2y$12$Eh5ERCQhDOdVBy1bil4cM.4pMDXzhamolYFfDiaTcKdXE6SLQNxHq', 'Malik Pradipta S.I.Kom', 'siswa', NULL, '2026-06-27 10:23:15', '2026-06-27 10:23:15'),
(57, '237103066', '$2y$12$LrnotYeF9.dEFFWGI9VECOxcMZ6uBzDaQHXY1Eo7LAZOhwZefbKJy', 'Yoga Megantara', 'siswa', NULL, '2026-06-27 10:23:15', '2026-06-27 10:23:15'),
(58, '721241186', '$2y$12$.Uuvj5dIv0dWLNJTZ.QLA.1mrYywVm00hYxli5c.HC8.CoAvzjynW', 'Violet Sudiati', 'siswa', NULL, '2026-06-27 10:23:16', '2026-06-27 10:23:16'),
(59, '944579035', '$2y$12$bct9sBt4S0qWKjRvBSWX2.jDwlsv8bIone7k4BNKaXTnGT7QRydBC', 'Gangsa Ade Maryadi', 'siswa', NULL, '2026-06-27 10:23:16', '2026-06-27 10:23:16'),
(60, '949919143', '$2y$12$28tTwHRebBbcKZY9uaEpCOWKjdqyVP3Aft18KihH2QEMeX.RThR1i', 'Ilsa Kuswandari', 'siswa', NULL, '2026-06-27 10:23:16', '2026-06-27 10:23:16'),
(61, '356295695', '$2y$12$jzRUnyqeemBMi1uP4V3iDOs2Q1.QCksPs4OZ2o1Q5TBK9NwZxrAh2', 'Irnanto Lukman Setiawan', 'siswa', NULL, '2026-06-27 10:23:16', '2026-06-27 10:23:16'),
(62, '173238747', '$2y$12$Ra.I/SUjm.Pw7RouzhMncuAnQRzKSwe7ySOW5nF11C5LyYWPC9s2u', 'Nalar Narpati', 'siswa', NULL, '2026-06-27 10:23:16', '2026-06-27 10:23:16'),
(63, '911907276', '$2y$12$n3GyBggmUqcg5z6ick5Ynes7zb9obwjsZfwEZnXkouZLUKd7x5OdS', 'Kawaca Nugroho M.TI.', 'siswa', NULL, '2026-06-27 10:23:17', '2026-06-27 10:23:17'),
(64, '531942379', '$2y$12$PSGyBZIWQcd.OCeJ1PEwQOA8TqcnrunN0q3Oy8C9epA6FkVRHqgfe', 'Emong Utama', 'siswa', NULL, '2026-06-27 10:23:17', '2026-06-27 10:23:17'),
(65, '259766587', '$2y$12$xal9mIV8UIULAple9PQxzOnlpnW9qccHTi9yXYL5MKvC/fX/XKLay', 'Prayogo Suwarno M.Farm', 'siswa', NULL, '2026-06-27 10:23:17', '2026-06-27 10:23:17'),
(66, '063762623', '$2y$12$K/mBYiJwDnhJ1Cvgq87M6ukgpn6HyzzgSiTSrlzJ5xQt/yx66yWxa', 'Endah Puspita', 'siswa', NULL, '2026-06-27 10:23:17', '2026-06-27 10:23:17'),
(67, '667768267', '$2y$12$kd/3EnyO.s7gK8w.tUdJ2e3cIKfdbzXCgg9dsltqMEqi4CagC4Vb2', 'Amelia Aryani S.H.', 'siswa', NULL, '2026-06-27 10:23:18', '2026-06-27 10:23:18'),
(68, '074503019', '$2y$12$Mi9/WdNKSAu3pzOFJaP6n.nislFTJORC4i0n3S6SBxg6.g/2t2fS2', 'Gara Mansur S.I.Kom', 'siswa', NULL, '2026-06-27 10:23:18', '2026-06-27 10:23:18'),
(69, '671790447', '$2y$12$E7hAYO.aXj0ACYOyUnndtuAdm8AItaIS5wIaHP63foomVdCQoMaq6', 'Dagel Firmansyah S.I.Kom', 'siswa', NULL, '2026-06-27 10:23:18', '2026-06-27 10:23:18'),
(70, '063709757', '$2y$12$nMBvSn7eB1UYg5g/2VjYouf3ys0gr0qOettGMa5yZWqWfjTmtGAMa', 'Carub Putra', 'siswa', NULL, '2026-06-27 10:23:18', '2026-06-27 10:23:18'),
(71, '278143367', '$2y$12$Ha0fqV9FVLTALTa0qPZuPOtzGOS3sOOHabaFjjxBLKm9xDTn2MZ9C', 'Melinda Padmasari', 'siswa', NULL, '2026-06-27 10:23:18', '2026-06-27 10:23:18'),
(72, '743326301', '$2y$12$Xx05lpXBG7skQFx9btek/eXCNodxMb.rsaLg9Mj.r3EpSSZPibqeW', 'Victoria Wulandari', 'siswa', NULL, '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(73, '235596446', '$2y$12$8ByICw9xQYR8Axxle2hWJ.dYrpLOTdMJLLnZwd2ILaW5bn2YPMyJu', 'Maida Novitasari', 'siswa', NULL, '2026-06-27 10:23:19', '2026-06-27 10:23:19'),
(74, '123456', '$2y$12$k/9anCzEE1MWNoZlt9DkPujQ1rH2vOJYl20BbQVSU7hlqMX1ubFP2', 'Vini Fahrezi Riyadi S.Kom', 'guru', NULL, '2026-06-27 12:06:52', '2026-06-27 12:06:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensis`
--
ALTER TABLE `absensis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absensis_siswa_id_foreign` (`siswa_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gurus`
--
ALTER TABLE `gurus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gurus_nip_unique` (`nip`),
  ADD KEY `gurus_user_id_foreign` (`user_id`);

--
-- Indexes for table `guru_mapels`
--
ALTER TABLE `guru_mapels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guru_mapels_guru_id_foreign` (`guru_id`);

--
-- Indexes for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwals_guru_id_foreign` (`guru_id`);

--
-- Indexes for table `jawaban_tugas`
--
ALTER TABLE `jawaban_tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jawaban_tugas_tugas_id_foreign` (`tugas_id`),
  ADD KEY `jawaban_tugas_siswa_id_foreign` (`siswa_id`);

--
-- Indexes for table `jawaban_ujian_essays`
--
ALTER TABLE `jawaban_ujian_essays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jawaban_ujian_essays_ujian_id_foreign` (`ujian_id`),
  ADD KEY `jawaban_ujian_essays_siswa_id_foreign` (`siswa_id`);

--
-- Indexes for table `jawaban_ujian_gandas`
--
ALTER TABLE `jawaban_ujian_gandas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jawaban_ujian_gandas_ujian_id_foreign` (`ujian_id`),
  ADD KEY `jawaban_ujian_gandas_siswa_id_foreign` (`siswa_id`),
  ADD KEY `jawaban_ujian_gandas_soal_ujian_id_foreign` (`soal_ujian_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kelas_nama_kelas_unique` (`nama_kelas`);

--
-- Indexes for table `mapels`
--
ALTER TABLE `mapels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mapels_nama_mapel_unique` (`nama_mapel`),
  ADD UNIQUE KEY `mapels_kode_unique` (`kode`);

--
-- Indexes for table `materis`
--
ALTER TABLE `materis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `materis_guru_id_foreign` (`guru_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
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
-- Indexes for table `siswas`
--
ALTER TABLE `siswas`
  ADD PRIMARY KEY (`siswa_id`) USING BTREE,
  ADD UNIQUE KEY `siswas_nis_unique` (`nis`),
  ADD KEY `siswas_user_id_foreign` (`user_id`);

--
-- Indexes for table `soal_ujians`
--
ALTER TABLE `soal_ujians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `soal_ujians_ujian_id_foreign` (`ujian_id`);

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugas_guru_id_foreign` (`guru_id`);

--
-- Indexes for table `ujians`
--
ALTER TABLE `ujians`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ujians_guru_id_foreign` (`guru_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensis`
--
ALTER TABLE `absensis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gurus`
--
ALTER TABLE `gurus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `guru_mapels`
--
ALTER TABLE `guru_mapels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwals`
--
ALTER TABLE `jadwals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jawaban_tugas`
--
ALTER TABLE `jawaban_tugas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jawaban_ujian_essays`
--
ALTER TABLE `jawaban_ujian_essays`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jawaban_ujian_gandas`
--
ALTER TABLE `jawaban_ujian_gandas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mapels`
--
ALTER TABLE `mapels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materis`
--
ALTER TABLE `materis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `siswas`
--
ALTER TABLE `siswas`
  MODIFY `siswa_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `soal_ujians`
--
ALTER TABLE `soal_ujians`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `ujians`
--
ALTER TABLE `ujians`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensis`
--
ALTER TABLE `absensis`
  ADD CONSTRAINT `absensis_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`siswa_id`) ON DELETE CASCADE;

--
-- Constraints for table `gurus`
--
ALTER TABLE `gurus`
  ADD CONSTRAINT `gurus_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `guru_mapels`
--
ALTER TABLE `guru_mapels`
  ADD CONSTRAINT `guru_mapels_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD CONSTRAINT `jadwals_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jawaban_tugas`
--
ALTER TABLE `jawaban_tugas`
  ADD CONSTRAINT `jawaban_tugas_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`siswa_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jawaban_tugas_tugas_id_foreign` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jawaban_ujian_essays`
--
ALTER TABLE `jawaban_ujian_essays`
  ADD CONSTRAINT `jawaban_ujian_essays_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`siswa_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jawaban_ujian_essays_ujian_id_foreign` FOREIGN KEY (`ujian_id`) REFERENCES `ujians` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jawaban_ujian_gandas`
--
ALTER TABLE `jawaban_ujian_gandas`
  ADD CONSTRAINT `jawaban_ujian_gandas_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`siswa_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jawaban_ujian_gandas_soal_ujian_id_foreign` FOREIGN KEY (`soal_ujian_id`) REFERENCES `soal_ujians` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jawaban_ujian_gandas_ujian_id_foreign` FOREIGN KEY (`ujian_id`) REFERENCES `ujians` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `materis`
--
ALTER TABLE `materis`
  ADD CONSTRAINT `materis_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `siswas`
--
ALTER TABLE `siswas`
  ADD CONSTRAINT `siswas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `soal_ujians`
--
ALTER TABLE `soal_ujians`
  ADD CONSTRAINT `soal_ujians_ujian_id_foreign` FOREIGN KEY (`ujian_id`) REFERENCES `ujians` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tugas`
--
ALTER TABLE `tugas`
  ADD CONSTRAINT `tugas_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ujians`
--
ALTER TABLE `ujians`
  ADD CONSTRAINT `ujians_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
