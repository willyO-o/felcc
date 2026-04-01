/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.7.2-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: felcc
-- ------------------------------------------------------
-- Server version	11.7.2-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `delito`
--

DROP TABLE IF EXISTS `delito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `delito` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_delito` varchar(255) NOT NULL,
  `estado_delito` enum('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
  `descripcion_delito` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delito`
--

LOCK TABLES `delito` WRITE;
/*!40000 ALTER TABLE `delito` DISABLE KEYS */;
INSERT INTO `delito` VALUES
(1,'ESTAFA','ACTIVO',NULL,'2026-04-01 16:18:57','2026-04-01 16:18:57'),
(2,'PATROCINIO INFIEL RECPTACION','ACTIVO',NULL,'2026-04-01 16:18:57','2026-04-01 16:18:57'),
(3,'ROBO','ACTIVO',NULL,'2026-04-01 16:18:57','2026-04-01 16:18:57'),
(4,'RAPTO IMPROPIO','ACTIVO',NULL,'2026-04-01 16:18:57','2026-04-01 16:18:57'),
(5,'ROBO AGRAVADO','ACTIVO',NULL,'2026-04-01 16:18:57','2026-04-01 16:18:57'),
(6,'EVASIÓN','ACTIVO',NULL,'2026-04-01 16:18:57','2026-04-01 16:18:57'),
(7,'EVASIÓN, FAVORECIMIENTO A LA EVASIÓN, INCUMPLIMIENTO DE DEBERES.','ACTIVO',NULL,'2026-04-01 16:18:57','2026-04-01 16:18:57'),
(8,'ASISTENCIA FAMILIAR','ACTIVO',NULL,'2026-04-01 16:18:57','2026-04-01 16:18:57'),
(9,'ASESINATO','ACTIVO',NULL,'2026-04-01 16:18:57','2026-04-01 16:18:57'),
(10,'VIOLACIÓN A NIÑO, NIÑA, ADOLESCENTE','ACTIVO',NULL,'2026-04-01 16:18:57','2026-04-01 16:18:57'),
(11,'VIOLACIÓN','ACTIVO',NULL,'2026-04-01 16:18:57','2026-04-01 16:18:57');
/*!40000 ALTER TABLE `delito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `division`
--

DROP TABLE IF EXISTS `division`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `division` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `division` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `division`
--

LOCK TABLES `division` WRITE;
/*!40000 ALTER TABLE `division` DISABLE KEYS */;
/*!40000 ALTER TABLE `division` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documento`
--

DROP TABLE IF EXISTS `documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `documento` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_documento` enum('CI','PASAPORTE','LICENCIA','OTRO') NOT NULL,
  `numero_documento` varchar(50) NOT NULL,
  `complemento` varchar(10) DEFAULT NULL,
  `expedido_en` varchar(50) DEFAULT NULL,
  `id_persona` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documento_tipo_documento_numero_documento_unique` (`tipo_documento`,`numero_documento`),
  KEY `documento_id_persona_foreign` (`id_persona`),
  CONSTRAINT `documento_id_persona_foreign` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento`
--

LOCK TABLES `documento` WRITE;
/*!40000 ALTER TABLE `documento` DISABLE KEYS */;
/*!40000 ALTER TABLE `documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fotos_registro`
--

DROP TABLE IF EXISTS `fotos_registro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `fotos_registro` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` enum('FRONTAL','LATERAL') NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `id_registro_criminal` bigint(20) unsigned NOT NULL,
  `id_usuario` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fotos_registro_id_registro_criminal_foreign` (`id_registro_criminal`),
  KEY `fotos_registro_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `fotos_registro_id_registro_criminal_foreign` FOREIGN KEY (`id_registro_criminal`) REFERENCES `registro_criminal` (`id`),
  CONSTRAINT `fotos_registro_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fotos_registro`
--

LOCK TABLES `fotos_registro` WRITE;
/*!40000 ALTER TABLE `fotos_registro` DISABLE KEYS */;
/*!40000 ALTER TABLE `fotos_registro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `juzgado`
--

DROP TABLE IF EXISTS `juzgado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `juzgado` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_juzgado` varchar(255) NOT NULL,
  `estado_juzgado` enum('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `juzgado`
--

LOCK TABLES `juzgado` WRITE;
/*!40000 ALTER TABLE `juzgado` DISABLE KEYS */;
INSERT INTO `juzgado` VALUES
(1,'PRIMERO DE INSTRUCCIÓN EN LO PENAL CAUTELAR','ACTIVO','2026-04-01 16:18:57','2026-04-01 16:18:57'),
(2,'FISCALIA','ACTIVO','2026-04-01 16:18:57','2026-04-01 16:18:57'),
(3,'JUZGADO PRIMERO DE EJECUCION PENAL','ACTIVO','2026-04-01 16:18:57','2026-04-01 16:18:57'),
(4,'JUZGADO DE TRABAJO Y SEGURIDAD SOCIAL No. 2 DEL DPTO DE CBBA.','ACTIVO','2026-04-01 16:18:57','2026-04-01 16:18:57'),
(5,'JUZGADO 3RO. DE EJECUCION PENAL','ACTIVO','2026-04-01 16:18:57','2026-04-01 16:18:57'),
(6,'JUZGADO DE PARTIDO Y SENTENCIA CHUQUISACA','ACTIVO','2026-04-01 16:18:57','2026-04-01 16:18:57'),
(7,'JUZGADO 5TO. PARTIDO DE FAMILIA','ACTIVO','2026-04-01 16:18:57','2026-04-01 16:18:57'),
(8,'JUZGADO 4TO DE TRABAJO Y SEGURIDAD SOCIAL','ACTIVO','2026-04-01 16:18:57','2026-04-01 16:18:57'),
(9,'JUZGADO PUBLICO DE MATERIA NIÑEZ Y ADOLESCENCIA','ACTIVO','2026-04-01 16:18:57','2026-04-01 16:18:57'),
(10,'JUZGADO PUBLICO DE FAMILIA','ACTIVO','2026-04-01 16:18:57','2026-04-01 16:18:57'),
(11,'JUZGADO DE INSTRUCCIÓN DE CULPINA - CHUQUISACA','ACTIVO','2026-04-01 16:18:57','2026-04-01 16:18:57'),
(12,'TRIBUNAL 7MO. DE SENTENCIA Y SUSTANCIAS CONTROLADAS','ACTIVO','2026-04-01 16:18:57','2026-04-01 16:18:57');
/*!40000 ALTER TABLE `juzgado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mandamiento`
--

DROP TABLE IF EXISTS `mandamiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mandamiento` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hoja_ruta` varchar(100) DEFAULT NULL,
  `estado` varchar(200) DEFAULT NULL,
  `fecha_ejecucion` date DEFAULT NULL,
  `detalle_ejecucion` text DEFAULT NULL,
  `asignado` text DEFAULT NULL,
  `tipo_documento` varchar(255) DEFAULT NULL,
  `actividades_realizadas` text DEFAULT NULL,
  `id_usuario` bigint(20) unsigned NOT NULL,
  `id_juzgado` bigint(20) unsigned DEFAULT NULL,
  `id_delito` bigint(20) unsigned DEFAULT NULL,
  `id_tipo_mandamiento` bigint(20) unsigned DEFAULT NULL,
  `id_persona` bigint(20) unsigned NOT NULL,
  `domicilio` text DEFAULT NULL,
  `vehiculos` text DEFAULT NULL,
  `telefono` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mandamiento_id_usuario_foreign` (`id_usuario`),
  KEY `mandamiento_id_juzgado_foreign` (`id_juzgado`),
  KEY `mandamiento_id_delito_foreign` (`id_delito`),
  KEY `mandamiento_id_tipo_mandamiento_foreign` (`id_tipo_mandamiento`),
  KEY `mandamiento_id_persona_foreign` (`id_persona`),
  CONSTRAINT `mandamiento_id_delito_foreign` FOREIGN KEY (`id_delito`) REFERENCES `delito` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mandamiento_id_juzgado_foreign` FOREIGN KEY (`id_juzgado`) REFERENCES `juzgado` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mandamiento_id_persona_foreign` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mandamiento_id_tipo_mandamiento_foreign` FOREIGN KEY (`id_tipo_mandamiento`) REFERENCES `tipo_mandamiento` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mandamiento_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mandamiento`
--

LOCK TABLES `mandamiento` WRITE;
/*!40000 ALTER TABLE `mandamiento` DISABLE KEYS */;
/*!40000 ALTER TABLE `mandamiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_01_12_201217_create_pais_table',1),
(5,'2026_01_12_211528_create_division_table',1),
(6,'2026_01_20_205416_create_juzgado_table',1),
(7,'2026_01_20_205431_create_delito_table',1),
(8,'2026_01_20_205501_create_tipo_mandamiento_table',1),
(9,'2026_01_20_205529_create_persona_table',1),
(10,'2026_01_20_205541_create_mandamiento_table',1),
(11,'2026_01_20_205604_create_multimedia_table',1),
(12,'2026_02_12_211529_create_registro_criminal_table',1),
(13,'2026_02_12_215110_create_informacion_registro_persona_table',1),
(14,'2026_03_02_222224_add_soft_delete_registro_climinal',1),
(15,'2026_03_02_300000_create_roles_table',1),
(16,'2026_03_02_310000_add_soft_deletes_to_users_table',1),
(17,'2026_03_30_234958_add_field_persona_table',1),
(18,'2026_03_30_235705_add_field_registro_criminal_table',1),
(19,'2026_03_31_132922_add_field_domicilio_mandamiento_table',1),
(20,'2026_03_31_145646_quitar_not_null_id_delito_mandamiento',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `multimedia`
--

DROP TABLE IF EXISTS `multimedia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `multimedia` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) NOT NULL,
  `ruta` varchar(255) NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `id_mandamiento` bigint(20) unsigned DEFAULT NULL,
  `id_persona` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `multimedia_id_mandamiento_foreign` (`id_mandamiento`),
  KEY `multimedia_id_persona_foreign` (`id_persona`),
  CONSTRAINT `multimedia_id_mandamiento_foreign` FOREIGN KEY (`id_mandamiento`) REFERENCES `mandamiento` (`id`) ON DELETE CASCADE,
  CONSTRAINT `multimedia_id_persona_foreign` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `multimedia`
--

LOCK TABLES `multimedia` WRITE;
/*!40000 ALTER TABLE `multimedia` DISABLE KEYS */;
/*!40000 ALTER TABLE `multimedia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pais`
--

DROP TABLE IF EXISTS `pais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pais` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pais` varchar(200) NOT NULL,
  `gentilicio` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=247 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pais`
--

LOCK TABLES `pais` WRITE;
/*!40000 ALTER TABLE `pais` DISABLE KEYS */;
INSERT INTO `pais` VALUES
(1,'Afganistán','Afganistán',NULL,NULL),
(2,'Albania','Albania',NULL,NULL),
(3,'Alemania','alemán/a',NULL,NULL),
(4,'Andorra','Andorra',NULL,NULL),
(5,'Angola','Angola',NULL,NULL),
(6,'Anguila','Anguila',NULL,NULL),
(7,'Antártida','Antártida',NULL,NULL),
(8,'Antigua y Barbuda','Antigua y Barbuda',NULL,NULL),
(9,'Arabia Saudita','Arabia Saudita',NULL,NULL),
(10,'Argelia','Argelia',NULL,NULL),
(11,'Argentina','argentino/a',NULL,NULL),
(12,'Armenia','Armenia',NULL,NULL),
(13,'Aruba','Aruba',NULL,NULL),
(14,'Australia','australiano/a',NULL,NULL),
(15,'Austria','Austria',NULL,NULL),
(16,'Azerbaiyán','Azerbaiyán',NULL,NULL),
(17,'Bélgica','belga',NULL,NULL),
(18,'Bahamas','Bahamas',NULL,NULL),
(19,'Bahrein','Bahrein',NULL,NULL),
(20,'Bangladesh','Bangladesh',NULL,NULL),
(21,'Barbados','Barbados',NULL,NULL),
(22,'Belice','Belice',NULL,NULL),
(23,'Benín','Benín',NULL,NULL),
(24,'Bhután','Bhután',NULL,NULL),
(25,'Bielorrusia','Bielorrusia',NULL,NULL),
(26,'Birmania','Birmania',NULL,NULL),
(27,'Bolivia','boliviano/a',NULL,NULL),
(28,'Bosnia y Herzegovina','Bosnia y Herzegovina',NULL,NULL),
(29,'Botsuana','Botsuana',NULL,NULL),
(30,'Brasil','brasileño/a',NULL,NULL),
(31,'Brunéi','Brunéi',NULL,NULL),
(32,'Bulgaria','Bulgaria',NULL,NULL),
(33,'Burkina Faso','Burkina Faso',NULL,NULL),
(34,'Burundi','Burundi',NULL,NULL),
(35,'Cabo Verde','Cabo Verde',NULL,NULL),
(36,'Camboya','Camboya',NULL,NULL),
(37,'Camerún','Camerún',NULL,NULL),
(38,'Canadá','canadiense',NULL,NULL),
(39,'Chad','Chad',NULL,NULL),
(40,'Chile','chileno/a',NULL,NULL),
(41,'China','chino/a',NULL,NULL),
(42,'Chipre','Chipre',NULL,NULL),
(43,'Ciudad del Vaticano','Ciudad del Vaticano',NULL,NULL),
(44,'Colombia','colombiano/a',NULL,NULL),
(45,'Comoras','Comoras',NULL,NULL),
(46,'República del Congo','República del Congo',NULL,NULL),
(47,'República Democrática del Congo','República Democrática del Congo',NULL,NULL),
(48,'Corea del Norte','Corea del Norte',NULL,NULL),
(49,'Corea del Sur','surcoreano/a',NULL,NULL),
(50,'Costa de Marfil','Costa de Marfil',NULL,NULL),
(51,'Costa Rica','costarricense',NULL,NULL),
(52,'Croacia','Croacia',NULL,NULL),
(53,'Cuba','cubano/a',NULL,NULL),
(54,'Curazao','Curazao',NULL,NULL),
(55,'Dinamarca','danés/a',NULL,NULL),
(56,'Dominica','Dominica',NULL,NULL),
(57,'Ecuador','ecuatoriano/a',NULL,NULL),
(58,'Egipto','Egipto',NULL,NULL),
(59,'El Salvador','salvadoreño/a',NULL,NULL),
(60,'Emiratos Árabes Unidos','Emiratos Árabes Unidos',NULL,NULL),
(61,'Eritrea','Eritrea',NULL,NULL),
(62,'Eslovaquia','Eslovaquia',NULL,NULL),
(63,'Eslovenia','Eslovenia',NULL,NULL),
(64,'España','español/a',NULL,NULL),
(65,'Estados Unidos de América','Estados Unidos de América',NULL,NULL),
(66,'Estonia','Estonia',NULL,NULL),
(67,'Etiopía','Etiopía',NULL,NULL),
(68,'Filipinas','Filipinas',NULL,NULL),
(69,'Finlandia','finlandés/a',NULL,NULL),
(70,'Fiyi','Fiyi',NULL,NULL),
(71,'Francia','francés/a',NULL,NULL),
(72,'Gabón','Gabón',NULL,NULL),
(73,'Gambia','Gambia',NULL,NULL),
(74,'Georgia','Georgia',NULL,NULL),
(75,'Ghana','Ghana',NULL,NULL),
(76,'Gibraltar','Gibraltar',NULL,NULL),
(77,'Granada','Granada',NULL,NULL),
(78,'Grecia','Grecia',NULL,NULL),
(79,'Groenlandia','Groenlandia',NULL,NULL),
(80,'Guadalupe','Guadalupe',NULL,NULL),
(81,'Guam','Guam',NULL,NULL),
(82,'Guatemala','Guatemala',NULL,NULL),
(83,'Guayana Francesa','Guayana Francesa',NULL,NULL),
(84,'Guernsey','Guernsey',NULL,NULL),
(85,'Guinea','Guinea',NULL,NULL),
(86,'Guinea Ecuatorial','Guinea Ecuatorial',NULL,NULL),
(87,'Guinea-Bissau','Guinea-Bissau',NULL,NULL),
(88,'Guyana','Guyana',NULL,NULL),
(89,'Haití','Haití',NULL,NULL),
(90,'Honduras','Honduras',NULL,NULL),
(91,'Hong kong','Hong kong',NULL,NULL),
(92,'Hungría','Hungría',NULL,NULL),
(93,'India','indio/a',NULL,NULL),
(94,'Indonesia','Indonesia',NULL,NULL),
(95,'Irán','Irán',NULL,NULL),
(96,'Irak','Irak',NULL,NULL),
(97,'Irlanda','Irlanda',NULL,NULL),
(98,'Isla Bouvet','Isla Bouvet',NULL,NULL),
(99,'Isla de Man','Isla de Man',NULL,NULL),
(100,'Isla de Navidad','Isla de Navidad',NULL,NULL),
(101,'Isla Norfolk','Isla Norfolk',NULL,NULL),
(102,'Islandia','Islandia',NULL,NULL),
(103,'Islas Bermudas','Islas Bermudas',NULL,NULL),
(104,'Islas Caimán','Islas Caimán',NULL,NULL),
(105,'Islas Cocos (Keeling)','Islas Cocos (Keeling)',NULL,NULL),
(106,'Islas Cook','Islas Cook',NULL,NULL),
(107,'Islas de Åland','Islas de Åland',NULL,NULL),
(108,'Islas Feroe','Islas Feroe',NULL,NULL),
(109,'Islas Georgias del Sur y Sandwich del Sur','Islas Georgias del Sur y Sandwich del Sur',NULL,NULL),
(110,'Islas Heard y McDonald','Islas Heard y McDonald',NULL,NULL),
(111,'Islas Maldivas','Islas Maldivas',NULL,NULL),
(112,'Islas Malvinas','Islas Malvinas',NULL,NULL),
(113,'Islas Marianas del Norte','Islas Marianas del Norte',NULL,NULL),
(114,'Islas Marshall','Islas Marshall',NULL,NULL),
(115,'Islas Pitcairn','Islas Pitcairn',NULL,NULL),
(116,'Islas Salomón','Islas Salomón',NULL,NULL),
(117,'Islas Turcas y Caicos','Islas Turcas y Caicos',NULL,NULL),
(118,'Islas Ultramarinas Menores de Estados Unidos','Islas Ultramarinas Menores de Estados Unidos',NULL,NULL),
(119,'Islas Vírgenes Británicas','Islas Vírgenes Británicas',NULL,NULL),
(120,'Islas Vírgenes de los Estados Unidos','Islas Vírgenes de los Estados Unidos',NULL,NULL),
(121,'Israel','Israel',NULL,NULL),
(122,'Italia','italiano/a',NULL,NULL),
(123,'Jamaica','Jamaica',NULL,NULL),
(124,'Japón','japonés/a',NULL,NULL),
(125,'Jersey','Jersey',NULL,NULL),
(126,'Jordania','Jordania',NULL,NULL),
(127,'Kazajistán','Kazajistán',NULL,NULL),
(128,'Kenia','Kenia',NULL,NULL),
(129,'Kirguistán','Kirguistán',NULL,NULL),
(130,'Kiribati','Kiribati',NULL,NULL),
(131,'Kuwait','Kuwait',NULL,NULL),
(132,'Líbano','Líbano',NULL,NULL),
(133,'Laos','Laos',NULL,NULL),
(134,'Lesoto','Lesoto',NULL,NULL),
(135,'Letonia','Letonia',NULL,NULL),
(136,'Liberia','Liberia',NULL,NULL),
(137,'Libia','Libia',NULL,NULL),
(138,'Liechtenstein','Liechtenstein',NULL,NULL),
(139,'Lituania','Lituania',NULL,NULL),
(140,'Luxemburgo','Luxemburgo',NULL,NULL),
(141,'México','mexicano/a',NULL,NULL),
(142,'Mónaco','Mónaco',NULL,NULL),
(143,'Macao','Macao',NULL,NULL),
(144,'Macedônia','Macedônia',NULL,NULL),
(145,'Madagascar','Madagascar',NULL,NULL),
(146,'Malasia','Malasia',NULL,NULL),
(147,'Malawi','Malawi',NULL,NULL),
(148,'Mali','Mali',NULL,NULL),
(149,'Malta','Malta',NULL,NULL),
(150,'Marruecos','Marruecos',NULL,NULL),
(151,'Martinica','Martinica',NULL,NULL),
(152,'Mauricio','Mauricio',NULL,NULL),
(153,'Mauritania','Mauritania',NULL,NULL),
(154,'Mayotte','Mayotte',NULL,NULL),
(155,'Micronesia','Micronesia',NULL,NULL),
(156,'Moldavia','Moldavia',NULL,NULL),
(157,'Mongolia','Mongolia',NULL,NULL),
(158,'Montenegro','Montenegro',NULL,NULL),
(159,'Montserrat','Montserrat',NULL,NULL),
(160,'Mozambique','Mozambique',NULL,NULL),
(161,'Namibia','Namibia',NULL,NULL),
(162,'Nauru','Nauru',NULL,NULL),
(163,'Nepal','Nepal',NULL,NULL),
(164,'Nicaragua','Nicaragua',NULL,NULL),
(165,'Niger','Niger',NULL,NULL),
(166,'Nigeria','Nigeria',NULL,NULL),
(167,'Niue','Niue',NULL,NULL),
(168,'Noruega','noruego/a',NULL,NULL),
(169,'Nueva Caledonia','Nueva Caledonia',NULL,NULL),
(170,'Nueva Zelanda','Nueva Zelanda',NULL,NULL),
(171,'Omán','Omán',NULL,NULL),
(172,'Países Bajos','neerlandés/a',NULL,NULL),
(173,'Pakistán','Pakistán',NULL,NULL),
(174,'Panamá','Panamá',NULL,NULL),
(175,'Papúa Nueva Guinea','Papúa Nueva Guinea',NULL,NULL),
(176,'Paraguay','paraguayo/a',NULL,NULL),
(177,'Perú','peruano/a',NULL,NULL),
(178,'Polinesia Francesa','Polinesia Francesa',NULL,NULL),
(179,'Polonia','Polonia',NULL,NULL),
(180,'Portugal','portugués/a',NULL,NULL),
(181,'Puerto Rico','Puerto Rico',NULL,NULL),
(182,'Qatar','Qatar',NULL,NULL),
(183,'Reino Unido','británico/a',NULL,NULL),
(184,'República Centroafricana','República Centroafricana',NULL,NULL),
(185,'República Checa','República Checa',NULL,NULL),
(186,'República Dominicana','dominicano/a',NULL,NULL),
(187,'República de Sudán del Sur','República de Sudán del Sur',NULL,NULL),
(188,'Reunión','Reunión',NULL,NULL),
(189,'Ruanda','Ruanda',NULL,NULL),
(190,'Rumanía','Rumanía',NULL,NULL),
(191,'Rusia','ruso/a',NULL,NULL),
(192,'Sahara Occidental','Sahara Occidental',NULL,NULL),
(193,'Samoa','Samoa',NULL,NULL),
(194,'Samoa Americana','Samoa Americana',NULL,NULL),
(195,'San Bartolomé','San Bartolomé',NULL,NULL),
(196,'San Cristóbal y Nieves','San Cristóbal y Nieves',NULL,NULL),
(197,'San Marino','San Marino',NULL,NULL),
(198,'San Martín (Francia)','San Martín (Francia)',NULL,NULL),
(199,'San Pedro y Miquelón','San Pedro y Miquelón',NULL,NULL),
(200,'San Vicente y las Granadinas','San Vicente y las Granadinas',NULL,NULL),
(201,'Santa Elena','Santa Elena',NULL,NULL),
(202,'Santa Lucía','Santa Lucía',NULL,NULL),
(203,'Santo Tomé y Príncipe','Santo Tomé y Príncipe',NULL,NULL),
(204,'Senegal','Senegal',NULL,NULL),
(205,'Serbia','Serbia',NULL,NULL),
(206,'Seychelles','Seychelles',NULL,NULL),
(207,'Sierra Leona','Sierra Leona',NULL,NULL),
(208,'Singapur','Singapur',NULL,NULL),
(209,'Sint Maarten','Sint Maarten',NULL,NULL),
(210,'Siria','Siria',NULL,NULL),
(211,'Somalia','Somalia',NULL,NULL),
(212,'Sri lanka','Sri lanka',NULL,NULL),
(213,'Sudáfrica','sudafricano/a',NULL,NULL),
(214,'Sudán','Sudán',NULL,NULL),
(215,'Suecia','sueco/a',NULL,NULL),
(216,'Suiza','suizo/a',NULL,NULL),
(217,'Surinám','Surinám',NULL,NULL),
(218,'Svalbard y Jan Mayen','Svalbard y Jan Mayen',NULL,NULL),
(219,'Swazilandia','Swazilandia',NULL,NULL),
(220,'Tayikistán','Tayikistán',NULL,NULL),
(221,'Tailandia','Tailandia',NULL,NULL),
(222,'Taiwán','Taiwán',NULL,NULL),
(223,'Tanzania','Tanzania',NULL,NULL),
(224,'Territorio Británico del Océano Índico','Territorio Británico del Océano Índico',NULL,NULL),
(225,'Territorios Australes y Antárticas Franceses','Territorios Australes y Antárticas Franceses',NULL,NULL),
(226,'Timor Oriental','Timor Oriental',NULL,NULL),
(227,'Togo','Togo',NULL,NULL),
(228,'Tokelau','Tokelau',NULL,NULL),
(229,'Tonga','Tonga',NULL,NULL),
(230,'Trinidad y Tobago','Trinidad y Tobago',NULL,NULL),
(231,'Tunez','Tunez',NULL,NULL),
(232,'Turkmenistán','Turkmenistán',NULL,NULL),
(233,'Turquía','turco/a',NULL,NULL),
(234,'Tuvalu','Tuvalu',NULL,NULL),
(235,'Ucrania','Ucrania',NULL,NULL),
(236,'Uganda','Uganda',NULL,NULL),
(237,'Uruguay','uruguayo/a',NULL,NULL),
(238,'Uzbekistán','Uzbekistán',NULL,NULL),
(239,'Vanuatu','Vanuatu',NULL,NULL),
(240,'Venezuela','venezolano/a',NULL,NULL),
(241,'Vietnam','Vietnam',NULL,NULL),
(242,'Wallis y Futuna','Wallis y Futuna',NULL,NULL),
(243,'Yemen','Yemen',NULL,NULL),
(244,'Yibuti','Yibuti',NULL,NULL),
(245,'Zambia','Zambia',NULL,NULL),
(246,'Zimbabue','Zimbabue',NULL,NULL);
/*!40000 ALTER TABLE `pais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `persona` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombres` varchar(150) NOT NULL,
  `apellidos` varchar(150) DEFAULT NULL,
  `ci` varchar(250) DEFAULT NULL,
  `domicilio` text DEFAULT NULL,
  `telefono` varchar(25) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `lugar_nacimiento` varchar(250) DEFAULT NULL,
  `complemento` varchar(40) DEFAULT NULL,
  `genero` enum('MASCULINO','FEMENINO') DEFAULT NULL,
  `estado_civil` enum('SOLTERO','CASADO','DIVORCIADO','VIUDO','CONYUGUE') DEFAULT NULL,
  `nombre_conyuge` varchar(250) DEFAULT NULL,
  `ocupacion` varchar(150) DEFAULT NULL,
  `id_pais` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `datos_segip` text DEFAULT NULL,
  `responsable` varchar(200) DEFAULT NULL,
  `estado_investigacion` varchar(200) DEFAULT NULL,
  `url_documento` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `persona_ci_unique` (`ci`),
  KEY `persona_id_pais_foreign` (`id_pais`),
  CONSTRAINT `persona_id_pais_foreign` FOREIGN KEY (`id_pais`) REFERENCES `pais` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registro_criminal`
--

DROP TABLE IF EXISTS `registro_criminal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `registro_criminal` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nro_registro` int(11) DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `nombre_supuesto` varchar(250) DEFAULT NULL,
  `alias` varchar(20) DEFAULT NULL,
  `especialidad` varchar(250) DEFAULT NULL,
  `edad_aproximada` varchar(20) DEFAULT NULL,
  `nombre_conyuge` varchar(250) DEFAULT NULL,
  `domicilio` varchar(250) DEFAULT NULL,
  `rasgos` text DEFAULT NULL,
  `modus_operandi` text DEFAULT NULL,
  `zonas_opera` text DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `id_persona` bigint(20) unsigned NOT NULL,
  `id_usuario` bigint(20) unsigned NOT NULL,
  `id_division` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `estatura` varchar(255) DEFAULT NULL,
  `peso` varchar(255) DEFAULT NULL,
  `cud` varchar(255) DEFAULT NULL,
  `caracteristicas_particulares` text DEFAULT NULL,
  `hijos` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `registro_criminal_id_persona_foreign` (`id_persona`),
  KEY `registro_criminal_id_usuario_foreign` (`id_usuario`),
  KEY `registro_criminal_id_division_foreign` (`id_division`),
  CONSTRAINT `registro_criminal_id_division_foreign` FOREIGN KEY (`id_division`) REFERENCES `division` (`id`),
  CONSTRAINT `registro_criminal_id_persona_foreign` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id`),
  CONSTRAINT `registro_criminal_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registro_criminal`
--

LOCK TABLES `registro_criminal` WRITE;
/*!40000 ALTER TABLE `registro_criminal` DISABLE KEYS */;
/*!40000 ALTER TABLE `registro_criminal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'superadmin','Super Administrador con acceso total al sistema','2026-04-01 16:18:56','2026-04-01 16:18:56'),
(2,'administrador','Administrador con acceso a la gestión general','2026-04-01 16:18:56','2026-04-01 16:18:56'),
(3,'tecnico','Técnico con acceso limitado a operaciones','2026-04-01 16:18:56','2026-04-01 16:18:56'),
(4,'consultor','Consultor con acceso limitado a operaciones','2026-04-01 16:18:56','2026-04-01 16:18:56');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_mandamiento`
--

DROP TABLE IF EXISTS `tipo_mandamiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_mandamiento` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_mandamiento` varchar(100) NOT NULL,
  `estado_tipo_mandamiento` enum('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
  `descripcion_tipo_mandamiento` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_mandamiento`
--

LOCK TABLES `tipo_mandamiento` WRITE;
/*!40000 ALTER TABLE `tipo_mandamiento` DISABLE KEYS */;
INSERT INTO `tipo_mandamiento` VALUES
(1,'MANDAMIENTO DE APREHENSION','ACTIVO','Mandamiento para la aprehensión de una persona.','2026-04-01 16:18:57','2026-04-01 16:18:57'),
(2,'ORDEN DE APREHENSION','ACTIVO','Orden emitida por una autoridad judicial para la aprehensión.','2026-04-01 16:18:57','2026-04-01 16:18:57'),
(3,'PROFUGO KL80','ACTIVO','Mandamiento para la captura de un prófugo.','2026-04-01 16:18:57','2026-04-01 16:18:57'),
(4,'MANDAMIENTO DE CAPTURA','ACTIVO','Mandamiento para la captura de una persona buscada por la justicia.','2026-04-01 16:18:57','2026-04-01 16:18:57'),
(5,'MANDAMIENTO DE APREMIO','ACTIVO','Mandamiento para ejercer presión legal sobre una persona.','2026-04-01 16:18:57','2026-04-01 16:18:57');
/*!40000 ALTER TABLE `tipo_mandamiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,1,'administrador','administrador@gmail.com',NULL,'$2y$12$ipU0y1Utj35L6ILhES.A5OvWWJhr4vb7I6MO23rtwHcLRF3GOQsde',NULL,'2026-04-01 16:18:57','2026-04-01 16:18:57',NULL),
(2,2,'admin','admin@gmail.com',NULL,'$2y$12$VCFuqMhaRMa6m.er7qkVyu2mHU/bqNouUQvnASTTT7x5LrC2OCew2',NULL,'2026-04-01 16:18:57','2026-04-01 16:18:57',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-04-01 12:43:59
