-- MySQL dump 10.13  Distrib 8.4.10, for Linux (aarch64)
--
-- Host: localhost    Database: laravel_catalog
-- ------------------------------------------------------
-- Server version	8.4.10

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
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_login_unique` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','$2y$10$/m9xHIu/W2Y6gk6Z6mzO9uDcn.CxraU0.OjFINQ4Ikcm.uMhN25e.','Administrator',NULL,'2026-06-24 03:42:07','2026-06-24 03:42:07');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog`
--

DROP TABLE IF EXISTS `catalog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalog` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb3_unicode_ci,
  `keywords` text COLLATE utf8mb3_unicode_ci,
  `image` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT '',
  `parent_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `name` (`name`),
  CONSTRAINT `catalog_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `catalog` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog`
--

LOCK TABLES `catalog` WRITE;
/*!40000 ALTER TABLE `catalog` DISABLE KEYS */;
INSERT INTO `catalog` VALUES (1,'Технологии','Сайты и онлайн-ресурсы раздела «Технологии», собранные для демонстрационного каталога ссылок.','технологии, ссылки, каталог сайтов, каталог','test-technology.svg',NULL,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(2,'Разработка ПО','Ресурсы, компании и полезные сайты раздела «Разработка ПО», сгруппированные в категории «Технологии».','разработка, по, технологии, ссылки',NULL,1,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(3,'Хостинг','Ресурсы, компании и полезные сайты раздела «Хостинг», сгруппированные в категории «Технологии».','хостинг, технологии, ссылки',NULL,1,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(4,'Кибербезопасность','Ресурсы, компании и полезные сайты раздела «Кибербезопасность», сгруппированные в категории «Технологии».','кибербезопасность, технологии, ссылки',NULL,1,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(5,'ИИ и данные','Ресурсы, компании и полезные сайты раздела «ИИ и данные», сгруппированные в категории «Технологии».','ии, и, данные, технологии, ссылки',NULL,1,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(6,'Устройства и гаджеты','Ресурсы, компании и полезные сайты раздела «Устройства и гаджеты», сгруппированные в категории «Технологии».','устройства, и, гаджеты, технологии, ссылки',NULL,1,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(7,'Бизнес и финансы','Сайты и онлайн-ресурсы раздела «Бизнес и финансы», собранные для демонстрационного каталога ссылок.','бизнес, и, финансы, ссылки, каталог сайтов, каталог','test-business-and-finance.svg',NULL,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(8,'Банки и кредиты','Ресурсы, компании и полезные сайты раздела «Банки и кредиты», сгруппированные в категории «Бизнес и финансы».','банки, и, кредиты, бизнес и финансы, ссылки',NULL,7,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(9,'Бухгалтерия','Ресурсы, компании и полезные сайты раздела «Бухгалтерия», сгруппированные в категории «Бизнес и финансы».','бухгалтерия, бизнес и финансы, ссылки',NULL,7,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(10,'Страхование','Ресурсы, компании и полезные сайты раздела «Страхование», сгруппированные в категории «Бизнес и финансы».','страхование, бизнес и финансы, ссылки',NULL,7,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(11,'Недвижимость','Ресурсы, компании и полезные сайты раздела «Недвижимость», сгруппированные в категории «Бизнес и финансы».','недвижимость, бизнес и финансы, ссылки',NULL,7,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(12,'Инвестиции','Ресурсы, компании и полезные сайты раздела «Инвестиции», сгруппированные в категории «Бизнес и финансы».','инвестиции, бизнес и финансы, ссылки',NULL,7,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(13,'Покупки','Сайты и онлайн-ресурсы раздела «Покупки», собранные для демонстрационного каталога ссылок.','покупки, ссылки, каталог сайтов, каталог','test-shopping.svg',NULL,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(14,'Магазины одежды','Ресурсы, компании и полезные сайты раздела «Магазины одежды», сгруппированные в категории «Покупки».','магазины, одежды, покупки, ссылки',NULL,13,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(15,'Магазины электроники','Ресурсы, компании и полезные сайты раздела «Магазины электроники», сгруппированные в категории «Покупки».','магазины, электроники, покупки, ссылки',NULL,13,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(16,'Маркетплейсы','Ресурсы, компании и полезные сайты раздела «Маркетплейсы», сгруппированные в категории «Покупки».','маркетплейсы, покупки, ссылки',NULL,13,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(17,'Офисные товары','Ресурсы, компании и полезные сайты раздела «Офисные товары», сгруппированные в категории «Покупки».','офисные, товары, покупки, ссылки',NULL,13,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(18,'Подарки и цветы','Ресурсы, компании и полезные сайты раздела «Подарки и цветы», сгруппированные в категории «Покупки».','подарки, и, цветы, покупки, ссылки',NULL,13,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(19,'Здоровье и медицина','Сайты и онлайн-ресурсы раздела «Здоровье и медицина», собранные для демонстрационного каталога ссылок.','здоровье, и, медицина, ссылки, каталог сайтов, каталог','test-health-and-medicine.svg',NULL,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(20,'Клиники','Ресурсы, компании и полезные сайты раздела «Клиники», сгруппированные в категории «Здоровье и медицина».','клиники, здоровье и медицина, ссылки',NULL,19,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(21,'Аптеки','Ресурсы, компании и полезные сайты раздела «Аптеки», сгруппированные в категории «Здоровье и медицина».','аптеки, здоровье и медицина, ссылки',NULL,19,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(22,'Стоматология','Ресурсы, компании и полезные сайты раздела «Стоматология», сгруппированные в категории «Здоровье и медицина».','стоматология, здоровье и медицина, ссылки',NULL,19,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(23,'Психическое здоровье','Ресурсы, компании и полезные сайты раздела «Психическое здоровье», сгруппированные в категории «Здоровье и медицина».','психическое, здоровье, здоровье и медицина, ссылки',NULL,19,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(24,'Телемедицина','Ресурсы, компании и полезные сайты раздела «Телемедицина», сгруппированные в категории «Здоровье и медицина».','телемедицина, здоровье и медицина, ссылки',NULL,19,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(25,'Образование','Сайты и онлайн-ресурсы раздела «Образование», собранные для демонстрационного каталога ссылок.','образование, ссылки, каталог сайтов, каталог','test-education.svg',NULL,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(26,'Университеты','Ресурсы, компании и полезные сайты раздела «Университеты», сгруппированные в категории «Образование».','университеты, образование, ссылки',NULL,25,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(27,'Онлайн-курсы','Ресурсы, компании и полезные сайты раздела «Онлайн-курсы», сгруппированные в категории «Образование».','онлайн-курсы, образование, ссылки',NULL,25,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(28,'Языковые школы','Ресурсы, компании и полезные сайты раздела «Языковые школы», сгруппированные в категории «Образование».','языковые, школы, образование, ссылки',NULL,25,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(29,'Репетиторство','Ресурсы, компании и полезные сайты раздела «Репетиторство», сгруппированные в категории «Образование».','репетиторство, образование, ссылки',NULL,25,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(30,'Научные ресурсы','Ресурсы, компании и полезные сайты раздела «Научные ресурсы», сгруппированные в категории «Образование».','научные, ресурсы, образование, ссылки',NULL,25,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(31,'Путешествия и транспорт','Сайты и онлайн-ресурсы раздела «Путешествия и транспорт», собранные для демонстрационного каталога ссылок.','путешествия, и, транспорт, ссылки, каталог сайтов, каталог','test-travel-and-transport.svg',NULL,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(32,'Отели','Ресурсы, компании и полезные сайты раздела «Отели», сгруппированные в категории «Путешествия и транспорт».','отели, путешествия и транспорт, ссылки',NULL,31,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(33,'Авиакомпании','Ресурсы, компании и полезные сайты раздела «Авиакомпании», сгруппированные в категории «Путешествия и транспорт».','авиакомпании, путешествия и транспорт, ссылки',NULL,31,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(34,'Аренда авто','Ресурсы, компании и полезные сайты раздела «Аренда авто», сгруппированные в категории «Путешествия и транспорт».','аренда, авто, путешествия и транспорт, ссылки',NULL,31,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(35,'Логистика','Ресурсы, компании и полезные сайты раздела «Логистика», сгруппированные в категории «Путешествия и транспорт».','логистика, путешествия и транспорт, ссылки',NULL,31,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(36,'Путеводители','Ресурсы, компании и полезные сайты раздела «Путеводители», сгруппированные в категории «Путешествия и транспорт».','путеводители, путешествия и транспорт, ссылки',NULL,31,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(37,'Еда и рестораны','Сайты и онлайн-ресурсы раздела «Еда и рестораны», собранные для демонстрационного каталога ссылок.','еда, и, рестораны, ссылки, каталог сайтов, каталог','test-food-and-dining.svg',NULL,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(38,'Рестораны','Ресурсы, компании и полезные сайты раздела «Рестораны», сгруппированные в категории «Еда и рестораны».','рестораны, еда и рестораны, ссылки',NULL,37,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(39,'Кофейни','Ресурсы, компании и полезные сайты раздела «Кофейни», сгруппированные в категории «Еда и рестораны».','кофейни, еда и рестораны, ссылки',NULL,37,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(40,'Доставка продуктов','Ресурсы, компании и полезные сайты раздела «Доставка продуктов», сгруппированные в категории «Еда и рестораны».','доставка, продуктов, еда и рестораны, ссылки',NULL,37,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(41,'Рецепты','Ресурсы, компании и полезные сайты раздела «Рецепты», сгруппированные в категории «Еда и рестораны».','рецепты, еда и рестораны, ссылки',NULL,37,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(42,'Кейтеринг','Ресурсы, компании и полезные сайты раздела «Кейтеринг», сгруппированные в категории «Еда и рестораны».','кейтеринг, еда и рестораны, ссылки',NULL,37,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(43,'Дом и сад','Сайты и онлайн-ресурсы раздела «Дом и сад», собранные для демонстрационного каталога ссылок.','дом, и, сад, ссылки, каталог сайтов, каталог','test-home-and-garden.svg',NULL,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(44,'Мебель','Ресурсы, компании и полезные сайты раздела «Мебель», сгруппированные в категории «Дом и сад».','мебель, дом и сад, ссылки',NULL,43,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(45,'Дизайн интерьера','Ресурсы, компании и полезные сайты раздела «Дизайн интерьера», сгруппированные в категории «Дом и сад».','дизайн, интерьера, дом и сад, ссылки',NULL,43,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(46,'Ремонт дома','Ресурсы, компании и полезные сайты раздела «Ремонт дома», сгруппированные в категории «Дом и сад».','ремонт, дома, дом и сад, ссылки',NULL,43,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(47,'Садоводство','Ресурсы, компании и полезные сайты раздела «Садоводство», сгруппированные в категории «Дом и сад».','садоводство, дом и сад, ссылки',NULL,43,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(48,'Умный дом','Ресурсы, компании и полезные сайты раздела «Умный дом», сгруппированные в категории «Дом и сад».','умный, дом, дом и сад, ссылки',NULL,43,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(49,'Развлечения и искусство','Сайты и онлайн-ресурсы раздела «Развлечения и искусство», собранные для демонстрационного каталога ссылок.','развлечения, и, искусство, ссылки, каталог сайтов, каталог','test-entertainment-and-arts.svg',NULL,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(50,'Кино','Ресурсы, компании и полезные сайты раздела «Кино», сгруппированные в категории «Развлечения и искусство».','кино, развлечения и искусство, ссылки',NULL,49,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(51,'Музыка','Ресурсы, компании и полезные сайты раздела «Музыка», сгруппированные в категории «Развлечения и искусство».','музыка, развлечения и искусство, ссылки',NULL,49,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(52,'Игры','Ресурсы, компании и полезные сайты раздела «Игры», сгруппированные в категории «Развлечения и искусство».','игры, развлечения и искусство, ссылки',NULL,49,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(53,'Музеи','Ресурсы, компании и полезные сайты раздела «Музеи», сгруппированные в категории «Развлечения и искусство».','музеи, развлечения и искусство, ссылки',NULL,49,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(54,'События','Ресурсы, компании и полезные сайты раздела «События», сгруппированные в категории «Развлечения и искусство».','события, развлечения и искусство, ссылки',NULL,49,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(55,'Новости и медиа','Сайты и онлайн-ресурсы раздела «Новости и медиа», собранные для демонстрационного каталога ссылок.','новости, и, медиа, ссылки, каталог сайтов, каталог','test-news-and-media.svg',NULL,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(56,'Газеты','Ресурсы, компании и полезные сайты раздела «Газеты», сгруппированные в категории «Новости и медиа».','газеты, новости и медиа, ссылки',NULL,55,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(57,'Журналы','Ресурсы, компании и полезные сайты раздела «Журналы», сгруппированные в категории «Новости и медиа».','журналы, новости и медиа, ссылки',NULL,55,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(58,'Подкасты','Ресурсы, компании и полезные сайты раздела «Подкасты», сгруппированные в категории «Новости и медиа».','подкасты, новости и медиа, ссылки',NULL,55,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(59,'ТВ и видео','Ресурсы, компании и полезные сайты раздела «ТВ и видео», сгруппированные в категории «Новости и медиа».','тв, и, видео, новости и медиа, ссылки',NULL,55,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(60,'Блоги','Ресурсы, компании и полезные сайты раздела «Блоги», сгруппированные в категории «Новости и медиа».','блоги, новости и медиа, ссылки',NULL,55,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(61,'Профессиональные услуги','Сайты и онлайн-ресурсы раздела «Профессиональные услуги», собранные для демонстрационного каталога ссылок.','профессиональные, услуги, ссылки, каталог сайтов, каталог','test-professional-services.svg',NULL,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(62,'Юридические услуги','Ресурсы, компании и полезные сайты раздела «Юридические услуги», сгруппированные в категории «Профессиональные услуги».','юридические, услуги, профессиональные услуги, ссылки',NULL,61,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(63,'Консалтинг','Ресурсы, компании и полезные сайты раздела «Консалтинг», сгруппированные в категории «Профессиональные услуги».','консалтинг, профессиональные услуги, ссылки',NULL,61,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(64,'Маркетинг','Ресурсы, компании и полезные сайты раздела «Маркетинг», сгруппированные в категории «Профессиональные услуги».','маркетинг, профессиональные услуги, ссылки',NULL,61,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(65,'HR и рекрутинг','Ресурсы, компании и полезные сайты раздела «HR и рекрутинг», сгруппированные в категории «Профессиональные услуги».','hr, и, рекрутинг, профессиональные услуги, ссылки',NULL,61,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(66,'Печать','Ресурсы, компании и полезные сайты раздела «Печать», сгруппированные в категории «Профессиональные услуги».','печать, профессиональные услуги, ссылки',NULL,61,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(67,'Спорт и фитнес','Сайты и онлайн-ресурсы раздела «Спорт и фитнес», собранные для демонстрационного каталога ссылок.','спорт, и, фитнес, ссылки, каталог сайтов, каталог','test-sports-and-fitness.svg',NULL,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(68,'Тренажерные залы','Ресурсы, компании и полезные сайты раздела «Тренажерные залы», сгруппированные в категории «Спорт и фитнес».','тренажерные, залы, спорт и фитнес, ссылки',NULL,67,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(69,'Спортивные клубы','Ресурсы, компании и полезные сайты раздела «Спортивные клубы», сгруппированные в категории «Спорт и фитнес».','спортивные, клубы, спорт и фитнес, ссылки',NULL,67,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(70,'Снаряжение для активного отдыха','Ресурсы, компании и полезные сайты раздела «Снаряжение для активного отдыха», сгруппированные в категории «Спорт и фитнес».','снаряжение, для, активного, отдыха, спорт и фитнес, ссылки',NULL,67,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(71,'Тренерские услуги','Ресурсы, компании и полезные сайты раздела «Тренерские услуги», сгруппированные в категории «Спорт и фитнес».','тренерские, услуги, спорт и фитнес, ссылки',NULL,67,'2026-06-24 03:42:07','2026-06-24 03:42:07'),(72,'Велнес','Ресурсы, компании и полезные сайты раздела «Велнес», сгруппированные в категории «Спорт и фитнес».','велнес, спорт и фитнес, ссылки',NULL,67,'2026-06-24 03:42:07','2026-06-24 03:42:07');
/*!40000 ALTER TABLE `catalog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feedback` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
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
-- Table structure for table `links`
--

DROP TABLE IF EXISTS `links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT '',
  `phone` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT '',
  `city` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT '',
  `email` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `keywords` text COLLATE utf8mb3_unicode_ci,
  `full_description` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `catalog_id` int unsigned DEFAULT NULL,
  `status` varchar(10) COLLATE utf8mb3_unicode_ci NOT NULL,
  `views` int NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog_id` (`catalog_id`),
  KEY `url` (`url`),
  FULLTEXT KEY `name` (`name`),
  CONSTRAINT `links_catalog_id_foreign` FOREIGN KEY (`catalog_id`) REFERENCES `catalog` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `links`
--

LOCK TABLES `links` WRITE;
/*!40000 ALTER TABLE `links` DISABLE KEYS */;
/*!40000 ALTER TABLE `links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2019_08_19_000000_create_failed_jobs_table',1),(2,'2019_12_14_000001_create_personal_access_tokens_table',1),(3,'2021_05_10_160026_create_jobs_table',1),(4,'2026_06_22_000000_create_admin_table',1),(5,'2026_06_22_000100_create_catalog_table',1),(6,'2026_06_22_000200_create_feedback_table',1),(7,'2026_06_22_000300_create_settings_table',1),(8,'2026_06_22_000400_create_links_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-24  4:03:49
