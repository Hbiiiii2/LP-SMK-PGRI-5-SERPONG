-- MySQL dump 10.13  Distrib 8.4.11, for Linux (x86_64)
--
-- Host: localhost    Database: sekolahku_db
-- ------------------------------------------------------
-- Server version	8.4.11-0ubuntu0.26.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `achievements`
--

DROP TABLE IF EXISTS `achievements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `achievements` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `student_name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `class_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `achievement` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `level` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `year` year DEFAULT NULL,
  `medal` enum('gold','silver','bronze') COLLATE utf8mb4_general_ci DEFAULT 'gold',
  `sort_order` tinyint unsigned DEFAULT '0',
  `show` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `achievements`
--

LOCK TABLES `achievements` WRITE;
/*!40000 ALTER TABLE `achievements` DISABLE KEYS */;
INSERT INTO `achievements` VALUES (1,'Alya Putri',NULL,'Kelas XII IPA','Juara 1 Olimpiade Sains Nasional','Nasional',2026,'gold',1,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(2,'Raka Firmansyah',NULL,'Kelas XI IPS','Juara 2 Lomba Debat Bahasa Inggris Provinsi','Provinsi',2026,'silver',2,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(3,'Sinta Nurhaliza',NULL,'Kelas X IPA','Medali Emas Olimpiade Matematika','Nasional',2026,'gold',3,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(4,'Dimas Aditya',NULL,'Kelas XII IPA','Juara 1 Taekwondo Tingkat Kota','Kota',2026,'bronze',4,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(5,'Nindy Ayu',NULL,'Kelas XI IPA','Harapan 1 Lomba Karya Ilmiah Remaja','Provinsi',2025,'silver',5,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(6,'Bayu Prasetyo',NULL,'Kelas X IPS','Juara 3 Desain Grafis Tingkat Nasional','Nasional',2025,'gold',6,1,'2026-10-30 23:03:39','2026-10-30 23:03:39');
/*!40000 ALTER TABLE `achievements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_messages_read` (`is_read`),
  KEY `idx_messages_created` (`created_at`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `downloads`
--

DROP TABLE IF EXISTS `downloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `downloads` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `url` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `file_size` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort_order` int unsigned DEFAULT '0',
  `show` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_downloads_category` (`category`),
  KEY `idx_downloads_sort_order` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `downloads`
--

LOCK TABLES `downloads` WRITE;
/*!40000 ALTER TABLE `downloads` DISABLE KEYS */;
INSERT INTO `downloads` VALUES (1,'Kalender Akademik','Kalender Akademik 2026/2027','Kalender akademik tahun pelajaran 2026/2027 untuk seluruh jenjang pendidikan.','https://drive.google.com/example-kalender-akademik','1.2 MB',0,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(2,'Kalender Akademik','Jadwal UTS Semester Ganjil 2026/2027','Jadwal pelaksanaan Ujian Tengah Semester Ganjil tahun ajaran 2026/2027.','https://drive.google.com/example-jadwal-uts','850 KB',1,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(3,'Kurikulum','Kurikulum Merdeka - Panduan Implementasi','Panduan implementasi Kurikulum Merdeka untuk guru dan tenaga kependidikan.','https://drive.google.com/example-kurikulum-merdeka','3.5 MB',0,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(4,'Kurikulum','Capaian Pembelajaran per Fase','Dokumen capaian pembelajaran untuk setiap fase dalam Kurikulum Merdeka.','https://drive.google.com/example-capaian-pembelajaran','2.1 MB',1,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(5,'Formulir','Formulir Pendaftaran Siswa Baru','Formulir pendaftaran untuk calon siswa baru tahun ajaran 2026/2027.','https://drive.google.com/example-formulir-spmb','450 KB',0,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(6,'Formulir','Surat Pernyataan Orang Tua/Wali','Formulir surat pernyataan orang tua/wali untuk keperluan administrasi sekolah.','https://drive.google.com/example-surat-pernyataan','320 KB',1,1,'2026-10-30 23:03:39','2026-10-30 23:03:39');
/*!40000 ALTER TABLE `downloads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `location` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `event_date` date NOT NULL,
  `event_time` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort_order` tinyint unsigned DEFAULT '0',
  `status` enum('upcoming','ongoing','completed','cancelled') COLLATE utf8mb4_general_ci DEFAULT 'upcoming',
  `show` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_events_date` (`event_date`),
  KEY `idx_events_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'Upacara HUT RI ke-81','upacara-hut-ri-ke-81','Seluruh siswa dan guru mengikuti upacara peringatan Hari Kemerdekaan RI.','Lapangan Sekolah','2026-08-15','07.00 WIB',1,'upcoming',1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(2,'Lomba Antar Kelas','lomba-antar-kelas','Perlombaan antar kelas meliputi cerdas cermat, seni, dan olahraga.','Aula Sekolah','2026-08-22','08.00 WIB',2,'upcoming',1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(3,'Kegiatan Bakti Sosial','kegiatan-bakti-sosial','Kunjungan dan bakti sosial siswa ke panti asuhan di sekitar sekolah.','Panti Asuhan','2026-09-05','07.30 WIB',3,'upcoming',1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(4,'Pentas Seni Akhir Semester','pentas-seni-akhir-semester','Pertunjukan seni dari siswa sebagai puncak kegiatan akhir semester.','Gedung Serba Guna','2026-09-12','09.00 WIB',4,'upcoming',1,'2026-10-30 23:03:39','2026-10-30 23:03:39');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `extracurriculars`
--

DROP TABLE IF EXISTS `extracurriculars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `extracurriculars` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `icon` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'star',
  `icon_color` varchar(7) COLLATE utf8mb4_general_ci DEFAULT '#14b8a6',
  `sort_order` tinyint unsigned DEFAULT '0',
  `show` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `extracurriculars`
--

LOCK TABLES `extracurriculars` WRITE;
/*!40000 ALTER TABLE `extracurriculars` DISABLE KEYS */;
INSERT INTO `extracurriculars` VALUES (1,'Robotik','Belajar merakit dan memprogram robot untuk kompetisi nasional.','fas fa-microchip','#14b8a6',1,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(2,'Paduan Suara','Mengembangkan bakat vokal dan apresiasi musik.','fas fa-music','#f59e0b',2,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(3,'Olahraga','Futsal, basket, voli, taekwondo, dan atletik.','fas fa-futbol','#ef4444',3,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(4,'Seni Rupa','Melukis, menggambar, desain grafis, dan kriya.','fas fa-paint-brush','#8b5cf6',4,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(5,'Jurnalistik','Menulis, fotografi, dan publikasi majalah sekolah.','fas fa-book','#0ea5e9',5,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(6,'Pramuka','Pembentukan karakter lewat kegiatan kepramukaan.','fas fa-leaf','#10b981',6,1,'2026-10-30 23:03:39','2026-10-30 23:03:39');
/*!40000 ALTER TABLE `extracurriculars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faq` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `answer` text COLLATE utf8mb4_general_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort_order` tinyint unsigned DEFAULT '0',
  `show` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq`
--

LOCK TABLES `faq` WRITE;
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
INSERT INTO `faq` VALUES (1,'Bagaimana cara mendaftar di SMA Nusantara?','Pendaftaran SPMB Gelombang 1 dibuka 1 Juni–31 Juli 2026 melalui portal spmb.smanusantara.sch.id atau datang langsung ke kantor pendaftaran di Jl. Cendekia No. 45, Cakung. Pendaftar Gelombang 1 mendapat potongan 50% biaya pengembangan.',NULL,1,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(2,'Apa saja program unggulan yang tersedia?','SMA Nusantara memiliki tiga peminatan — MIPA, IPS, dan Bahasa — dengan pendalaman materi, bimbingan olimpiade, dan kelas persiapan UTBK sejak kelas XI. Tersedia pula 12 ekstrakurikuler, dari robotik dan paskibra hingga teater dan jurnalistik.',NULL,2,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(3,'Apakah SMA Nusantara menyediakan beasiswa?','Ya. Tersedia beasiswa prestasi akademik dan non-akademik (potongan hingga 100% biaya pengembangan), serta beasiswa afirmasi bagi siswa kurang mampu. Informasi lebih lanjut dapat menghubungi bagian kesiswaan di (021) 8790-4521.',NULL,3,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(4,'Bagaimana sistem pembelajaran di SMA Nusantara?','Kami menerapkan Kurikulum Merdeka dengan pembelajaran aktif, kolaboratif, dan berbasis proyek, didukung perpustakaan digital 4.000 e-book, lab IPA dan komputer 1:1, serta rapor daring yang bisa dipantau orang tua.',NULL,4,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(5,'Bagaimana cara menghubungi SMA Nusantara?','Anda dapat menghubungi kami melalui telepon (021) 8790-4521, email info@smanusantara.sch.id, atau datang langsung ke Jl. Cendekia No. 45, Cakung, Jakarta Timur. Open house diadakan setiap Sabtu pertama tiap bulan, pukul 08.00–11.00.',NULL,5,1,'2026-10-30 23:03:39','2026-10-30 23:03:39');
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galleries`
--

DROP TABLE IF EXISTS `galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `galleries` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `caption` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `sort_order` tinyint unsigned DEFAULT '0',
  `show` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galleries`
--

LOCK TABLES `galleries` WRITE;
/*!40000 ALTER TABLE `galleries` DISABLE KEYS */;
INSERT INTO `galleries` VALUES (1,'Kegiatan','https://placehold.co/800x600/0c4a6e/ffffff?text=Upacara+Bendera','Upacara bendera setiap hari Senin',1,1,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(2,'Kegiatan','https://placehold.co/800x600/14b8a6/ffffff?text=Kegiatan+Belajar','Suasana belajar mengajar di kelas',1,2,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(3,'Prestasi','https://placehold.co/800x600/f59e0b/ffffff?text=Penghargaan','Penyerahan penghargaan kepada siswa berprestasi',1,3,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(4,'Kegiatan','https://placehold.co/800x600/ef4444/ffffff?text=Olahraga','Kegiatan olahraga dan ekstrakurikuler',0,4,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(5,'Akademik','https://placehold.co/800x600/8b5cf6/ffffff?text=Lab+Komputer','Praktik di laboratorium komputer',0,5,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(6,'Kegiatan','https://placehold.co/800x600/0ea5e9/ffffff?text=Seni+Budaya','Pentas seni budaya siswa',0,6,1,'2026-10-30 23:03:39','2026-10-30 23:03:39');
/*!40000 ALTER TABLE `galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int unsigned DEFAULT NULL,
  `title` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `target` enum('_self','_blank') COLLATE utf8mb4_general_ci DEFAULT '_self',
  `icon` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `section_key` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort_order` int unsigned DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `type` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'custom',
  `page_id` int unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `page_id` (`page_id`),
  CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `menu_items_ibfk_2` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_items`
--

LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` VALUES (1,NULL,'Beranda','','_self',NULL,NULL,0,1,'system',NULL,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(2,NULL,'Profil','#profile','_self',NULL,'profile',1,1,'system',NULL,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(3,NULL,'Program','#programs','_self',NULL,'programs',2,1,'system',NULL,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(4,NULL,'Berita','news','_self',NULL,NULL,3,1,'system',NULL,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(5,NULL,'Kontak','#contact','_self',NULL,'contact',4,1,'system',NULL,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(6,NULL,'Lainnya','#','_self',NULL,NULL,5,1,'system',NULL,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(7,6,'Pengajar','#teachers','_self',NULL,'teachers',0,1,'system',NULL,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(8,6,'Prestasi','#achievements','_self',NULL,'achievements',1,1,'system',NULL,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(9,6,'Ekstrakurikuler','#extracurriculars','_self',NULL,'extracurriculars',2,1,'system',NULL,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(10,6,'Testimoni','#testimonials','_self',NULL,'testimonials',3,1,'system',NULL,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(11,6,'Galeri','#gallery','_self',NULL,'gallery',4,1,'system',NULL,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(12,6,'Agenda','#events','_self',NULL,'events',5,1,'system',NULL,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(13,6,'FAQ','#faq','_self',NULL,'faq',6,1,'system',NULL,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(14,6,'Download','downloads','_self',NULL,NULL,7,1,'system',NULL,'2026-10-30 23:03:39','2026-10-30 23:03:39');
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_general_ci,
  `content` longtext COLLATE utf8mb4_general_ci,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `author` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'Admin',
  `published_at` datetime DEFAULT NULL,
  `status` enum('draft','published','archived') COLLATE utf8mb4_general_ci DEFAULT 'draft',
  `is_featured` tinyint(1) DEFAULT '0',
  `meta_title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `view_count` int unsigned DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_news_status` (`status`),
  KEY `idx_news_published` (`published_at`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,'Prestasi','[\"prestasi\", \"olimpiade\", \"sains\"]','Siswa SMA Nusantara Raih Medali Emas Olimpiade Sains Nasional','siswa-sma-nusantara-raih-medali-emas-olimpiade-sains-nasional','Alya Putri, siswa kelas XII MIPA, berhasil meraih medali emas dalam ajang Olimpiade Sains Nasional yang diselenggarakan di Jakarta.','<p>Prestasi membanggakan kembali diraih oleh siswa SMA Nusantara. Alya Putri, siswa kelas XII MIPA, berhasil meraih <strong>medali emas</strong> dalam ajang Olimpiade Sains Nasional (OSN) 2026 yang diselenggarakan di Jakarta pada tanggal 15-20 Maret 2026.</p><blockquote>\"Saya sangat bersyukur dan bangga bisa mengharumkan nama SMA Nusantara. Terima kasih kepada guru pembimbing yang selalu mendukung saya.\"</blockquote><p>Kepala SMA Nusantara, Drs. Bambang Sutrisno, M.Pd., menyampaikan apresiasi setinggi-tingginya atas prestasi yang diraih. \"Semoga prestasi ini menjadi motivasi bagi siswa lainnya untuk terus berprestasi,\" ujarnya.</p>',NULL,'Admin','2026-03-22 08:00:00','published',1,NULL,NULL,0,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(2,'Kegiatan','[\"kegiatan\", \"bakti-sosial\", \"peduli\"]','Bakti Sosial Siswa SMA Nusantara ke Panti Asuhan','bakti-sosial-siswa-sma-nusantara-ke-panti-asuhan','Siswa SMA Nusantara mengadakan bakti sosial ke panti asuhan sebagai bagian dari program pembinaan karakter.','<p>Sebanyak 50 siswa SMA Nusantara mengadakan kunjungan dan bakti sosial ke Panti Asuhan Harapan Mulia pada Sabtu, 5 September 2026. Kegiatan ini merupakan bagian dari program pembinaan karakter yang rutin dilaksanakan setiap semester.</p><p>Dalam kegiatan tersebut, siswa menyalurkan donasi berupa sembako, alat tulis, dan pakaian layak pakai. Selain itu, siswa juga berinteraksi dan bermain bersama anak-anak panti asuhan.</p>',NULL,'Admin','2026-09-06 10:00:00','published',1,NULL,NULL,0,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(3,'Pengumuman','[\"spmb\", \"pendaftaran\", \"siswa-baru\"]','Pendaftaran SPMB Tahun Ajaran 2026/2027 Telah Dibuka','pendaftaran-spmb-tahun-ajaran-2026-2027-telah-dibuka','Penerimaan Peserta Didik Baru (SPMB) untuk tahun ajaran 2026/2027 telah resmi dibuka. Simak informasi lengkapnya di sini.','<p>SMA Nusantara resmi membuka pendaftaran Penerimaan Peserta Didik Baru (SPMB) untuk tahun ajaran 2026/2027. Pendaftaran Gelombang 1 dimulai tanggal 1 Juni hingga 31 Juli 2026 melalui portal SPMB atau datang langsung ke kampus di Jl. Cendekia No. 45, Cakung.</p><p>Calon siswa dapat mendaftar secara online atau datang langsung ke kantor pendaftaran. Persyaratan lengkap dan jadwal seleksi dapat diunduh di halaman Download Center website ini.</p>',NULL,'Admin','2026-06-01 07:00:00','published',0,NULL,NULL,0,'2026-10-30 23:03:39','2026-10-30 23:03:39');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_comments`
--

DROP TABLE IF EXISTS `news_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news_comments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `news_id` int unsigned NOT NULL,
  `parent_id` int unsigned DEFAULT NULL,
  `name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `is_approved` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`),
  KEY `parent_id` (`parent_id`),
  KEY `idx_comments_approved` (`is_approved`),
  KEY `idx_comments_created` (`created_at`,`id`),
  CONSTRAINT `news_comments_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE,
  CONSTRAINT `news_comments_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `news_comments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_comments`
--

LOCK TABLES `news_comments` WRITE;
/*!40000 ALTER TABLE `news_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `news_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_general_ci,
  `excerpt` text COLLATE utf8mb4_general_ci,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_general_ci DEFAULT 'draft',
  `meta_title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programs`
--

DROP TABLE IF EXISTS `programs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `icon` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'star',
  `link_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `link_text` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'Selengkapnya',
  `sort_order` tinyint unsigned DEFAULT '0',
  `show` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programs`
--

LOCK TABLES `programs` WRITE;
/*!40000 ALTER TABLE `programs` DISABLE KEYS */;
INSERT INTO `programs` VALUES (1,'Sains & Teknologi','Laboratorium modern dan pembelajaran berbasis proyek untuk riset dan inovasi.','fas fa-flask',NULL,'Selengkapnya',1,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(2,'Seni & Budaya','Wadah pengembangan bakat seni melalui musik, tari, teater, dan seni rupa.','fas fa-palette',NULL,'Selengkapnya',2,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(3,'Olahraga','Fasilitas lengkap dan pembinaan atlet muda di berbagai cabang olahraga.','fas fa-running',NULL,'Selengkapnya',3,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(4,'Bahasa Asing','Program bilingual dengan native speaker untuk menghadapi era globalisasi.','fas fa-globe',NULL,'Selengkapnya',4,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(5,'Karakter & Religi','Pendidikan karakter berbasis nilai agama untuk pribadi berakhlak mulia.','fas fa-hand-holding-heart',NULL,'Selengkapnya',5,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(6,'Digital Literacy','Pembelajaran coding, desain grafis, dan literasi digital untuk era 4.0.','fas fa-laptop-code',NULL,'Selengkapnya',6,1,'2026-10-30 23:03:39','2026-10-30 23:03:39');
/*!40000 ALTER TABLE `programs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `value` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site_name','SMA Nusantara','2026-10-30 23:03:39','2026-10-30 23:03:39'),(2,'site_tagline','Unggul dalam Prestasi, Luhur dalam Budi Pekerti','2026-10-30 23:03:39','2026-10-30 23:03:39'),(3,'site_description','SMA Nusantara adalah sekolah menengah atas unggulan di Jakarta Timur yang memadukan pengajaran disiplin, pembinaan karakter, dan budaya prestasi sejak 1987.','2026-10-30 23:03:39','2026-10-30 23:03:39'),(4,'site_logo_text','SMA Nusantara','2026-10-30 23:03:39','2026-10-30 23:03:39'),(5,'site_logo_icon','graduation-cap','2026-10-30 23:03:39','2026-10-30 23:03:39'),(6,'site_url','https://smanusantara.sch.id','2026-10-30 23:03:39','2026-10-30 23:03:39'),(7,'contact_phone','(021) 8790-4521','2026-10-30 23:03:39','2026-10-30 23:03:39'),(8,'contact_email','info@smanusantara.sch.id','2026-10-30 23:03:39','2026-10-30 23:03:39'),(9,'contact_address','Jl. Cendekia No. 45, Cakung, Jakarta Timur 13910','2026-10-30 23:03:39','2026-10-30 23:03:39'),(10,'contact_hours','Senin–Jumat: 07.00–15.30 WIB · Sabtu: 08.00–11.00 WIB','2026-10-30 23:03:39','2026-10-30 23:03:39'),(11,'social_facebook','https://facebook.com/smanusantara','2026-10-30 23:03:39','2026-10-30 23:03:39'),(12,'social_instagram','https://instagram.com/sma.nusantara','2026-10-30 23:03:39','2026-10-30 23:03:39'),(13,'social_youtube','https://youtube.com/@SMANusantara','2026-10-30 23:03:39','2026-10-30 23:03:39'),(14,'social_tiktok','https://tiktok.com/@smanusantara','2026-10-30 23:03:39','2026-10-30 23:03:39'),(15,'meta_author','SMA Nusantara','2026-10-30 23:03:39','2026-10-30 23:03:39'),(16,'meta_keywords','sma nusantara, sekolah jakarta timur, kurikulum merdeka, sekolah unggulan, SPMB, akreditasi A','2026-10-30 23:03:39','2026-10-30 23:03:39'),(17,'meta_description','SMA Nusantara adalah SMA unggulan terakreditasi A di Jakarta Timur. Kurikulum Merdeka dengan peminatan MIPA, IPS, dan Bahasa, 96% lulusan diterima PTN.','2026-10-30 23:03:39','2026-10-30 23:03:39'),(18,'hero_badge','SPMB 2026/2027 · Terakreditasi A','2026-10-30 23:03:39','2026-10-30 23:03:39'),(19,'hero_title','Tumbuh Berilmu, Tegak Berkarakter.','2026-10-30 23:03:39','2026-10-30 23:03:39'),(20,'hero_subtitle','Sejak 1987, SMA Nusantara mendampingi 1.240 siswa bertumbuh lewat pengajaran yang disiplin, pembinaan karakter yang konsisten, dan budaya prestasi yang hidup setiap hari.','2026-10-30 23:03:39','2026-10-30 23:03:39'),(21,'hero_btn_primary_text','Daftar SPMB','2026-10-30 23:03:39','2026-10-30 23:03:39'),(22,'hero_btn_primary_url','#spmb','2026-10-30 23:03:39','2026-10-30 23:03:39'),(23,'hero_btn_secondary_text','Jelajahi Sekolah','2026-10-30 23:03:39','2026-10-30 23:03:39'),(24,'hero_btn_secondary_url','profile','2026-10-30 23:03:39','2026-10-30 23:03:39'),(25,'spmb_url','https://spmb.smanusantara.sch.id','2026-10-30 23:03:39','2026-10-30 23:03:39'),(26,'hero_stats','[{\"label\":\"Siswa Aktif\",\"value\":\"1240+\",\"icon\":\"user-graduate\"},{\"label\":\"Guru & Tendik\",\"value\":\"86\",\"icon\":\"chalkboard-teacher\"},{\"label\":\"Medali & Piala\",\"value\":\"120+\",\"icon\":\"trophy\"}]','2026-10-30 23:03:39','2026-10-30 23:03:39'),(27,'principal','{\"name\":\"Drs. Bambang Sutrisno, M.Pd.\",\"photo\":\"\\/images\\/default-principal.jpg\",\"role\":\"Kepala Sekolah\",\"role_title\":\"Kepala Sekolah\",\"quote\":\"Setiap anak datang dengan bekal yang berbeda. Tugas kamilah memastikan mereka pulang dengan arah yang sama \\u2014 menjadi pribadi yang berilmu, jujur, dan berguna bagi sesama.\",\"welcome_message\":\"Assalamu\\u2019alaikum warahmatullahi wabarakatuh. Selamat datang di SMA Nusantara. Selama hampir empat dekade, kami memegang tiga hal sederhana: apel pagi yang tertib, guru yang hafal nama setiap siswanya, dan target belajar yang transparan bagi orang tua. Cara-cara sederhana inilah yang mengantar 96% lulusan kami menembus perguruan tinggi negeri. Pintu sekolah ini selalu terbuka \\u2014 datang, lihat kelas kami, dan berbicaralah dengan guru kami.\",\"sambutan\":\"Assalamu\\u2019alaikum warahmatullahi wabarakatuh. Selamat datang di SMA Nusantara. Selama hampir empat dekade, kami memegang tiga hal sederhana: apel pagi yang tertib, guru yang hafal nama setiap siswanya, dan target belajar yang transparan bagi orang tua. Cara-cara sederhana inilah yang mengantar 96% lulusan kami menembus perguruan tinggi negeri. Pintu sekolah ini selalu terbuka \\u2014 datang, lihat kelas kami, dan berbicaralah dengan guru kami.\",\"education\":\"Magister Manajemen Pendidikan, Universitas Negeri Jakarta\",\"years_of_service\":\"Mengabdi sejak 2015\"}','2026-10-30 23:03:39','2026-10-30 23:03:39'),(28,'about','{\"image\":\"\",\"content_title\":\"Mengenal Lebih Dekat SMA Nusantara\",\"content_1\":\"Didirikan tahun 1987 oleh Yayasan Cendekia Nusantara, SMA Nusantara tumbuh dari tiga ruang kelas di sebuah ruko di Cakung menjadi kampus seluas 2,1 hektar dengan 1.240 siswa dan 86 guru serta tenaga kependidikan. Satu hal yang tidak pernah berubah: setiap anak dikenal namanya.\",\"content_2\":\"Kami memadukan Kurikulum Merdeka dengan pendalaman materi, bimbingan olimpiade, dan kelas persiapan UTBK sejak kelas XI. Di luar kelas, 12 ekstrakurikuler aktif dan program pembiasaan karakter \\u2014 apel pagi, jurnal refleksi, dan mentoring wali kelas \\u2014 membentuk lulusan yang siap kuliah, siap kerja, dan siap bermasyarakat.\",\"visi\":\"Menjadi sekolah unggul yang melahirkan lulusan beriman, cerdas, terampil, dan berdaya saing global pada tahun 2030.\",\"misi\":\"Menyelenggarakan pembelajaran Kurikulum Merdeka yang aktif dan bermakna; menanamkan karakter disiplin, jujur, dan peduli melalui pembiasaan harian; mengembangkan potensi siswa lewat peminatan MIPA, IPS, dan Bahasa beserta pembinaan olimpiade dan UTBK; serta menjalin kemitraan erat dengan orang tua dan masyarakat.\",\"accreditation\":\"A\",\"accreditation_label\":\"Unggul \\u2014 BAN-S\\/M\",\"highlights\":[\"Terakreditasi A oleh BAN-S\\/M\",\"Kurikulum Merdeka dengan peminatan MIPA, IPS, dan Bahasa\",\"Rasio guru\\u2013siswa 1:15, 61% guru bergelar magister\",\"96% lulusan 2025 diterima perguruan tinggi negeri\",\"Lab IPA, perpustakaan digital, sport hall, dan makerspace robotik\"]}','2026-10-30 23:03:39','2026-10-30 23:03:39'),(29,'section_settings','{\"hero\":{\"title\":\"Tumbuh Berilmu, Tegak Berkarakter\",\"subtitle\":\"Profil SMA Nusantara \\u2014 SMA unggulan terakreditasi A di Jakarta Timur\",\"icon\":\"fa-school\",\"show\":true},\"profile\":{\"title\":\"Profil Sekolah\",\"subtitle\":\"Mengenal lebih dekat sejarah, visi-misi, dan budaya SMA Nusantara\",\"icon\":\"fa-building-columns\",\"show\":true},\"programs\":{\"title\":\"Program Unggulan\",\"subtitle\":\"Peminatan MIPA, IPS, dan Bahasa dengan pendampingan olimpiade dan UTBK\",\"icon\":\"fa-gem\",\"show\":true},\"extracurriculars\":{\"title\":\"Ekstrakurikuler\",\"subtitle\":\"12 kegiatan siswa \\u2014 dari robotik dan paskibra hingga teater dan jurnalistik\",\"icon\":\"fa-futbol\",\"show\":true},\"teachers\":{\"title\":\"Tenaga Pengajar\",\"subtitle\":\"86 guru dan tendik \\u2014 100% sarjana, 61% bergelar magister\",\"icon\":\"fa-chalkboard-user\",\"show\":true},\"achievements\":{\"title\":\"Prestasi Siswa\",\"subtitle\":\"120+ medali dan piala \\u2014 dari OSN dan robotik hingga tahfidz dan pencak silat\",\"icon\":\"fa-trophy\",\"show\":true},\"testimonials\":{\"title\":\"Testimoni\",\"subtitle\":\"Apa kata siswa, alumni, dan orang tua tentang SMA Nusantara?\",\"icon\":\"fa-comment\",\"show\":true},\"news\":{\"title\":\"Berita & Artikel\",\"subtitle\":\"Kabar kampus, prestasi, dan pengumuman SMA Nusantara\",\"icon\":\"fa-newspaper\",\"show\":true},\"events\":{\"title\":\"Agenda & Kegiatan\",\"subtitle\":\"Kalender resmi \\u2014 UTS, open house, Cakrawala Cup, dan pentas seni\",\"icon\":\"fa-calendar-days\",\"show\":true},\"gallery\":{\"title\":\"Galeri\",\"subtitle\":\"Suasana kampus, laboratorium, dan panggung prestasi\",\"icon\":\"fa-images\",\"show\":true},\"faq\":{\"title\":\"FAQ\",\"subtitle\":\"Jawaban atas pertanyaan seputar SPMB, biaya, dan kurikulum\",\"icon\":\"fa-circle-question\",\"show\":true},\"downloads\":{\"title\":\"Download Center\",\"subtitle\":\"Brosur SPMB, formulir pendaftaran, kalender akademik, dan tata tertib\",\"icon\":\"fa-download\",\"show\":true},\"contact\":{\"title\":\"Kontak Kami\",\"subtitle\":\"Jl. Cendekia No. 45, Cakung \\u2014 balasan maks. 1\\u00d724 jam hari kerja\",\"icon\":\"fa-address-card\",\"show\":true}}','2026-10-30 23:03:39','2026-10-30 23:03:39'),(30,'page_banners','{\"news\":{\"badge\":\"Berita & Artikel\",\"title\":\"Kabar Kampus\",\"subtitle\":\"Ditulis tim jurnalistik siswa, dikurasi kesiswaan \\u2014 terbit setiap Senin dan Kamis\"},\"single_post\":{\"badge\":\"Detail Berita\",\"title\":\"Baca Berita\",\"subtitle\":\"Liputan lengkap kegiatan dan prestasi warga SMA Nusantara\"},\"pages\":{\"badge\":\"Halaman\",\"title\":\"Baca Halaman\",\"subtitle\":\"Informasi resmi SMA Nusantara\"},\"downloads\":{\"badge\":\"Download Center\",\"title\":\"Dokumen Resmi\",\"subtitle\":\"Brosur SPMB, formulir, kalender akademik, dan tata tertib \\u2014 selalu versi terbaru\"}}','2026-10-30 23:03:39','2026-10-30 23:03:39'),(31,'footer_description','SMA Nusantara — sekolah menengah atas unggulan di Cakung, Jakarta Timur. Berdiri sejak 1987 di bawah Yayasan Cendekia Nusantara.','2026-10-30 23:03:39','2026-10-30 23:03:39'),(32,'footer_copyright','© 2026 SMA Nusantara — All rights reserved.','2026-10-30 23:03:39','2026-10-30 23:03:39'),(33,'footer_services','[{\"label\":\"MIPA Unggulan\",\"url\":\"program\"},{\"label\":\"IPS & Kewirausahaan\",\"url\":\"program\"},{\"label\":\"Bahasa & Budaya\",\"url\":\"program\"},{\"label\":\"Bimbingan UTBK\",\"url\":\"program\"},{\"label\":\"Beasiswa Prestasi\",\"url\":\"achievements\"}]','2026-10-30 23:03:39','2026-10-30 23:03:39'),(34,'footer_links','[{\"label\":\"SPMB Online\",\"url\":\"https:\\/\\/spmb.smanusantara.sch.id\"},{\"label\":\"E-Learning\",\"url\":\"#\"},{\"label\":\"Perpustakaan Digital\",\"url\":\"#\"},{\"label\":\"FAQ\",\"url\":\"faq\"},{\"label\":\"Kontak\",\"url\":\"contact\"}]','2026-10-30 23:03:39','2026-10-30 23:03:39'),(35,'active_theme','nusantara','2026-10-30 23:03:39','2026-10-30 23:03:39'),(36,'counter_stats','[{\"icon\":\"user-graduate\",\"number\":\"1240\",\"suffix\":\"+\",\"label\":\"Siswa Aktif\"},{\"icon\":\"chalkboard-teacher\",\"number\":\"86\",\"suffix\":\"\",\"label\":\"Guru & Tendik\"},{\"icon\":\"trophy\",\"number\":\"120\",\"suffix\":\"+\",\"label\":\"Medali & Piala\"},{\"icon\":\"graduation-cap\",\"number\":\"96\",\"suffix\":\"%\",\"label\":\"Lulusan Diterima PTN\"}]','2026-10-30 23:03:39','2026-10-30 23:03:39'),(37,'spmb_steps','[{\"title\":\"Identitas Diri\",\"desc\":\"Nama lengkap, TTL, jenis kelamin\"},{\"title\":\"Alamat Tinggal\",\"desc\":\"Alamat, kelurahan, kecamatan, kota, provinsi\"},{\"title\":\"Kontak\",\"desc\":\"Telepon, email, kontak darurat\"},{\"title\":\"Data Ayah & Ibu\",\"desc\":\"Nama, pekerjaan, pendidikan, penghasilan\"},{\"title\":\"Data Wali\",\"desc\":\"Nama, alamat, hubungan, kontak\"},{\"title\":\"Asal Sekolah\",\"desc\":\"Nama sekolah, alamat, NISN\"},{\"title\":\"Pilihan Program\",\"desc\":\"Pilih program\\/jurusan yang diminati\"},{\"title\":\"Input Nilai Rapor\",\"desc\":\"Isi nilai sesuai mata pelajaran\"},{\"title\":\"Upload Dokumen\",\"desc\":\"Foto, ijazah, KK, dokumen pendukung\"},{\"title\":\"Finalisasi & Cetak PDF\",\"desc\":\"Periksa kembali lalu cetak formulir\"}]','2026-10-30 23:03:39','2026-10-30 23:03:39');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teachers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `experience` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `education` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `social_facebook` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `social_instagram` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `social_linkedin` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort_order` tinyint unsigned DEFAULT '0',
  `show` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teachers`
--

LOCK TABLES `teachers` WRITE;
/*!40000 ALTER TABLE `teachers` DISABLE KEYS */;
INSERT INTO `teachers` VALUES (1,'Drs. Bambang Sutrisno, M.Pd.','','Kepala Sekolah','28 Tahun Pengalaman','S2 Manajemen Pendidikan','','','',1,1,'2026-10-30 16:03:39','2026-10-30 16:03:39'),(2,'Budi Santoso, S.Si., M.T.','','Guru Sains & Teknologi','15 Tahun Pengalaman','S2 Teknik','','','',2,1,'2026-10-30 16:03:39','2026-10-30 16:03:39'),(3,'Ani Rahmawati, S.Pd., M.Hum.','','Guru Bahasa & Sastra','12 Tahun Pengalaman','S2 Linguistik','','','',3,1,'2026-10-30 16:03:39','2026-10-30 16:03:39'),(4,'Hendra Pratama, S.Pd.','','Guru Olahraga','10 Tahun Pengalaman','S1 Pendidikan','','','',4,1,'2026-10-30 16:03:39','2026-10-30 16:03:39'),(5,'Dewi Lestari, S.Pd., M.Pd.','','Guru Matematika','18 Tahun Pengalaman','S2 Pendidikan','','','',5,1,'2026-10-30 16:03:39','2026-10-30 16:03:39'),(6,'Rudi Hermawan, S.Si., M.Kom.','','Guru Informatika','14 Tahun Pengalaman','S2 Komputer','','','',6,1,'2026-10-30 16:03:39','2026-10-30 16:03:39');
/*!40000 ALTER TABLE `teachers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `testimonials` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `quote` text COLLATE utf8mb4_general_ci NOT NULL,
  `sort_order` tinyint unsigned DEFAULT '0',
  `show` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (1,'Alya Putri',NULL,'Siswi Kelas XII MIPA · Medali Emas OSN 2026','Guru-guru di SMA Nusantara hafal nama setiap siswanya. Bimbingan olimpiadenya intensif dan fasilitas labnya lengkap — saya bangga menjadi bagian dari sekolah ini.',1,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(2,'H. Mahmud Efendi',NULL,'Orang Tua Siswa Kelas X','Pembiasaan karakter di SMA Nusantara sangat terasa. Anak saya jadi lebih disiplin, mandiri, dan rajin salat berjamaah. Komunikasi wali kelas dengan orang tua juga terbuka.',2,1,'2026-10-30 23:03:39','2026-10-30 23:03:39'),(3,'Raka Firmansyah',NULL,'Alumni 2024 · Kini di Universitas Indonesia','Berkat bimbingan intensif UTBK sejak kelas XI, saya lolos ke PTN impian lewat jalur SNBP. Suasana belajarnya suportif — tidak ada anak yang dibiarkan tertinggal sendirian.',3,1,'2026-10-30 23:03:39','2026-10-30 23:03:39');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `full_name` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` enum('superadmin','admin','editor') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'editor',
  `is_active` tinyint(1) DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'administrator','admin@smanusantara.sch.id','$2y$12$/6fVMqzamUsht1avGfImfe9ZSp7ZquSLlk/R2GFjbvBNaCOnijO0i','Administrator',NULL,'superadmin',1,NULL,'2026-10-30 23:03:39','2026-10-30 23:03:39');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'sekolahku_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-30 23:03:39
