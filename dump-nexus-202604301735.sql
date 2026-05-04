-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: nexus
-- ------------------------------------------------------
-- Server version	8.0.45

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
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'Membresías Digitales','Suscripciones'),(2,'Hardware NFC','Dispositivos físicos (tarjetas, pulseras, tags) con tecnología NFC/RFID integrada.'),(3,'Servicios Especializados','Pases de estacionamiento, recargas de saldo y servicios complementarios.'),(4,'Comida','Para saciar tu antojo'),(5,'Educación','Aprendizaje y educación'),(6,'Servicios','Servicios'),(7,'Salud','Bienestar y cuidado personal'),(8,'Diversión','Plan con familia o amigos'),(9,'Transporte','Viajes y Transporte'),(10,'Compras y Entretenimiento','Compras y entretenimiento');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_pedido`
--

DROP TABLE IF EXISTS `detalle_pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_pedido` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pedido_id` int NOT NULL,
  `producto_id` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedido` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_pedido`
--

LOCK TABLES `detalle_pedido` WRITE;
/*!40000 ALTER TABLE `detalle_pedido` DISABLE KEYS */;
INSERT INTO `detalle_pedido` VALUES (1,1,4,3,450.00),(3,1,3,1,499.99),(5,3,1,1,149.99),(7,4,3,1,499.99),(8,4,6,2,99.00),(10,5,2,1,329.99),(11,5,6,10,99.00),(12,6,6,7,99.00),(13,6,2,1,329.99),(15,8,2,1,329.99),(16,8,6,1,99.00),(17,9,19,1,0.00),(18,9,24,1,0.00),(19,9,27,1,0.00),(20,9,16,1,0.00),(21,9,13,1,0.00),(22,9,1,1,149.99),(23,10,6,1,99.00),(24,11,19,1,0.00),(25,11,27,1,0.00),(26,11,30,1,0.00),(27,11,31,1,0.00),(28,11,2,1,329.99),(29,12,24,1,0.00),(30,12,29,1,0.00),(31,12,25,1,0.00),(32,12,22,1,0.00),(33,12,23,1,0.00),(34,12,26,1,0.00),(35,13,23,1,0.00),(36,13,16,1,0.00),(37,13,14,1,0.00),(38,13,26,1,0.00),(39,13,25,1,0.00),(40,13,15,1,0.00),(41,13,20,1,0.00),(42,13,24,1,0.00),(43,13,30,1,0.00),(44,13,31,1,0.00),(45,13,2,1,329.99);
/*!40000 ALTER TABLE `detalle_pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido`
--

DROP TABLE IF EXISTS `pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedido` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','completado','cancelado') DEFAULT 'pendiente',
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido`
--

LOCK TABLES `pedido` WRITE;
/*!40000 ALTER TABLE `pedido` DISABLE KEYS */;
INSERT INTO `pedido` VALUES (1,4,2311.99,'pendiente','2026-04-05 01:06:54'),(2,4,77.00,'pendiente','2026-04-05 02:12:25'),(3,4,2573.99,'pendiente','2026-04-05 03:34:52'),(4,4,4333.99,'pendiente','2026-04-05 03:37:50'),(5,4,1319.99,'pendiente','2026-04-05 03:56:57'),(6,4,1022.99,'pendiente','2026-04-04 22:15:57'),(7,4,77.00,'pendiente','2026-04-04 22:36:42'),(8,5,428.99,'pendiente','2026-04-04 23:50:48'),(9,4,149.99,'pendiente','2026-04-27 00:32:09'),(10,4,99.00,'pendiente','2026-04-27 00:37:23'),(11,9,329.99,'pendiente','2026-04-27 01:18:31'),(12,9,0.00,'pendiente','2026-04-27 01:33:38'),(13,10,329.99,'pendiente','2026-04-27 16:45:29');
/*!40000 ALTER TABLE `pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persona` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(150) NOT NULL,
  `calle` varchar(150) DEFAULT NULL,
  `numero_exterior` varchar(20) DEFAULT NULL,
  `numero_interior` varchar(20) DEFAULT NULL,
  `colonia` varchar(100) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `codigo_postal` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (3,'admin','administrador administrador','1234567890','admin@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(4,'usuario','usuario usuario','1234567890','user@gmail.com','For','123','234','the rest','your','life','of'),(5,'NOHACS','JUSTROBLOX','5610096623','escribir@gmail.com','For','123','234','the rest','your','life','of'),(6,'Alvaro de Campos','Caeiro Reis','5567369420','alvaro@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(7,'aefasfd','asdfasdf','234523453453452345','admin33@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(8,'Victor Guillermo','Acosta lasdkjsdaj','5610096623','victor@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(9,'MAC','People','1234123412','mac@gmail.com','123','234','234','awefawefafcawfawrdf','Estoy loco ✋🏻😛🤚🏻','awefawfawrfawrfaergaerwg','345'),(10,'aefaefaef','aefasefaf','1234123412341234','franco@gmail.com','asdfasf','','','asfdadsf','adfsdfadsdf','CDMX','123123');
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto`
--

DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `categoria_id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT 'default.png',
  `stock` int DEFAULT '-1',
  `activo` tinyint(1) DEFAULT '1',
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (1,1,'Nexus Bronce (30 días)','Acceso ilimitado al transporte público de la CDMX junto a la entrada a museos.',149.99,'tarjeta-bronce.jpeg',-1,1,'2026-04-04 04:31:46'),(2,1,'Nexus Plata (30 diás)','Un aumento de los beneficios anteriores, incluyendo un descuento porcentual a las casetas de las autopistas.',329.99,'tarjeta-plata.jpeg',-1,1,'2026-04-04 04:31:46'),(3,1,'Nexus Oro (30 diás)','El pase definitivo. Transporte ilimitado, acceso a eventos y descuento en todo tipo de tiendas y sitios de comida.',499.99,'tarjeta-oro.jpeg',-1,1,'2026-04-04 04:31:46'),(4,2,'Nexus Band (Black Edition)','Pulsera de silicona de alta resistencia con chip NFC integrado. Estética minimalista sin pantalla.',450.00,'nexus_band.jpg',147,1,'2026-04-04 04:31:46'),(5,2,'Nexus Card (Original)','Tarjeta física de policarbonato con acabado mate y tecnología RFID/NFC de doble banda.',150.00,'nexus_card.jpg',500,1,'2026-04-04 04:31:46'),(6,2,'Nexus Mini-Tag','Sticker NFC adhesivo y ultradelgado para colocar detrás de tu smartphone.',99.00,'nexus_tag.jpg',979,1,'2026-04-04 04:31:46'),(12,4,'McDonald\'s','20% de descuento en consumos mínimos de $150 pesos.',0.00,'mcdonalds_promo.png',-1,1,'2026-04-27 05:00:48'),(13,4,'Vips','20% de descuento en consumo mínimo de $199.',0.00,'vips_promo.png',-1,1,'2026-04-27 05:00:48'),(14,5,'Porrúa','10% de descuento en tu compra.',0.00,'porrua_promo.png',-1,1,'2026-04-27 05:00:53'),(15,9,'DIDI','50% de descuento en el primer viaje y 30% en los siguientes dos viajes.',0.00,'didi_promo.png',-1,1,'2026-04-27 05:01:35'),(16,6,'Walmart Pass','3 meses de envíos sin costo.',0.00,'walmart_promo.png',-1,1,'2026-04-27 05:01:35'),(17,6,'Koofr','Almacenamiento en la nube.',0.00,'koofr_promo.png',-1,1,'2026-04-27 05:01:35'),(18,7,'Fraiche','10% de descuento en toda la tienda.',0.00,'fraiche_promo.png',-1,1,'2026-04-27 05:01:35'),(19,6,'Ecobici','10% de descuento en membresía Ecobici+.',0.00,'ecobici_promo.png',-1,1,'2026-04-27 05:01:35'),(20,6,'Norton Antivirus','1 mes gratis en productos de suscripción.',0.00,'norton_promo.png',-1,1,'2026-04-27 05:01:35'),(21,7,'Smart Fit','Plan Black 12 meses por $479 por mes.',0.00,'smartfit_promo.png',-1,1,'2026-04-27 05:01:39'),(22,7,'Devlyn','20% de descuento en el total de tu compra.',0.00,'devlyn_promo.png',-1,1,'2026-04-27 05:01:39'),(23,7,'Dentalia','35% de descuento en limpieza dental.',0.00,'dentalia_promo.png',-1,1,'2026-04-27 05:01:39'),(24,7,'Terapify','60% de descuento en primera consulta.',0.00,'terapify_promo.png',-1,1,'2026-04-27 05:01:39'),(25,10,'Mister Tennis','15% de descuento en tienda o en línea.',0.00,'mistertennis_promo.png',-1,1,'2026-04-27 05:01:53'),(26,10,'Crunchyroll','1 Mes gratis de prueba.',0.00,'crunchyroll_promo.png',-1,1,'2026-04-27 05:01:53'),(27,8,'Capital Bus','10% de descuento en paquetes CDMX.',0.00,'capitalbus_promo.png',-1,1,'2026-04-27 05:01:53'),(28,10,'Free Fire','1 Ticket Luck Royale sin costo.',0.00,'freefire_promo.png',-1,1,'2026-04-27 05:01:53'),(29,9,'Hotel Piragua','15% de descuento en paquetes de viaje.',0.00,'hotelpiragua_promo.png',-1,1,'2026-04-27 05:01:53'),(30,8,'Cinemex Platino','Combo grande por $130.',0.00,'cinemex_promo.png',-1,1,'2026-04-27 05:01:53'),(31,10,'Apple Arcade','Hasta 4 meses gratis.',0.00,'apple_promo.png',-1,1,'2026-04-27 05:01:53');
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `persona_id` int NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `tipo_usuario` enum('admin','cliente') DEFAULT 'cliente',
  `activo` tinyint(1) DEFAULT '0',
  `password` varchar(255) NOT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `persona_id` (`persona_id`),
  UNIQUE KEY `usuario` (`usuario`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (3,3,'admin','admin',1,'$2y$10$RVnUoC2tTyQpmznKOf5n2O1/Y8lucRN92s6.fKQhDBqJVBYV.CCo2','2026-04-04 01:44:25'),(4,4,'user','cliente',1,'$2y$10$vYnsdq.GcPiw5Vpd/f1eWO9XEiVXKckknYZpLnBKr1tY1JSpFS73u','2026-04-04 01:53:43'),(5,5,'Puedo los mas esta','cliente',1,'$2y$10$gOqqBEFkA2lP9VP1yxdsIO4BNDIy/m1mMKMNeJYQoBatRfxkzYVay','2026-04-05 05:50:48'),(6,6,'Alvaro','cliente',1,'$2y$10$4ZmbPu5Wy2has4QTjEb/ku1mixJnjGD1jRBa9WdlQt/dJdjAF9ELK','2026-04-05 06:00:10'),(7,7,'aefasfef','cliente',1,'$2y$10$aIhv20P1sE5n1UtSvOnGXOxMskzEAo3F3.JtXAUYkesuh/Nut7zDK','2026-04-05 06:10:40'),(8,8,'asdasd','cliente',0,'$2y$10$GFW8pJaqDJil7xhNPAMYi.8DddOQrpRUBPMxBIontkgQrPft2YP.y','2026-04-08 02:22:23'),(9,9,'Mac','cliente',1,'$2y$10$5i2Vr6Uz3N2o9tPVLkl.P.skPgaViFUyeLIopakZg8I0C/ad/PdYy','2026-04-27 07:08:49'),(10,10,'Franco','cliente',1,'$2y$10$sx7YlfQlQeYahk6fe7rpr.ZIMDKloxp/X5/aMY.gAVHJP8bAwe4QO','2026-04-27 22:45:29');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'nexus'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-30 17:35:22
