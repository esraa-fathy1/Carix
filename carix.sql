-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: carix
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Manager','System_admin','Customer_admin') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (2,'manager@gmail.com','$2y$10$R09.zB899gpsVN/Opg6Rve7e9wNP5TCeXfJ1KAR0W9YDG63.XOPLG','Manager'),(3,'customer_admin@gmail.com','$2y$10$mFSjB1KdV5KCBscxI2YaYOP9dnUGsWBlMg5ikKpug2MzzPD1Ctn9.','Customer_admin'),(4,'system_admin@gmail.com','$2y$10$PJ24.sDq1Xm2SRNU1zio/.kvmDdAV7rSblGInLsmX653CSAV8fCbK','System_admin');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appointments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `reason` longtext NOT NULL,
  `car_details` longtext NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `time` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `appointment_address` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` VALUES (15,'mennatullah khaled','mennatullah24@gmail.com','01062871829','Car stopped ','BMW / X6 / 1500 CC',NULL,'2024-06-24','10:00:00','accepted','New cairo '),(16,'mariam','mariam@gmail.com','01156769903','car stopped suddenly','Fiat',NULL,'2024-06-24','10:00:00','pending','El dokki'),(17,'Mennatullah Khaled','mennatullahkhaled21@gmail.com','01062871829','Car stopped ','mazda',NULL,'2024-06-24','10:00:00','cancelled','New cairo '),(18,'Mennatullah Khaled','khaledmena414@gmail.com','01062871829','car stopped suddenly','Fiat',NULL,'2024-06-24','10:00:00','pending','El Dokki');
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `areas`
--

DROP TABLE IF EXISTS `areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `areas`
--

LOCK TABLES `areas` WRITE;
/*!40000 ALTER TABLE `areas` DISABLE KEYS */;
INSERT INTO `areas` VALUES (5,'Downtown Cairo (Wust El Balad)','Cairo'),(6,'Zamalek','Cairo'),(7,'Maadi','Cairo'),(8,'Heliopolis','Cairo'),(9,'Nasr City','Cairo'),(10,'Garden City','Cairo'),(11,'New Cairo (Tagamo3 al Khames)','Cairo'),(12,'El Rehab City','Cairo'),(13,'6th of October City','Giza'),(14,'El Sheikh Zayed','Giza'),(15,'Sayeda Zeinab','Cairo'),(16,'Imbaba','Giza'),(17,'Bulaq','Cairo'),(18,'Ain Shams','Cairo'),(19,'Manial','Cairo'),(20,'Rod El Farag','Cairo'),(21,'Shubra','Cairo'),(22,'Basateen','Cairo'),(23,'Mohandessin','Giza'),(24,'Dokki','Giza'),(25,'Agouza','Giza'),(26,'Haram','Giza'),(27,'Nazlet El-Semman','Giza'),(28,'Kafr Nassar','Giza'),(29,'Al Omraneyah','Giza'),(30,'Hadayek Al Ahram','Giza'),(31,'El Warraq','Giza'),(32,'Bohoth','Giza'),(33,'Kasr Al Ainy','Giza');
/*!40000 ALTER TABLE `areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `logo` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (4,' Audi','Audi.svg'),(5,' BMW','BMW.svg'),(6,' BYD','BYD.svg'),(7,' Chevrolet','Chevrolet.svg'),(8,' Citroen','Citroen.svg'),(9,' Fiat','Fiat.svg'),(10,' Ford','Ford.svg'),(11,' Geely','Geely.svg'),(12,' Honda ','honda.svg'),(13,' Hyundai','Hyundai.svg'),(14,' Jeep','Jeep.svg'),(15,' Kia','Kia.svg'),(16,' Mazda','Mazda.svg'),(17,' Mercedes Benz','Mercedes Benz.svg'),(18,' Mitsubishi','Mitsubishi.svg'),(19,' Nissan','Nissan.svg'),(20,' Opel','Opel.svg'),(21,' Peugeot','Peugeot.svg'),(22,' Renault','Renault.svg'),(23,' Seat','Seat.svg'),(25,' Skoda','Skoda.svg'),(26,' Toyota','Toyota.svg'),(27,' Volkswagen','Volkswagen.svg');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cars`
--

DROP TABLE IF EXISTS `cars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cars` (
  `car_id` int(11) NOT NULL AUTO_INCREMENT,
  `model_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cc` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `details` longtext NOT NULL,
  PRIMARY KEY (`car_id`),
  KEY `model_id` (`model_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cars_ibfk_2` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cars_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cars`
--

LOCK TABLES `cars` WRITE;
/*!40000 ALTER TABLE `cars` DISABLE KEYS */;
INSERT INTO `cars` VALUES (11,8,7,1500,2020,'new car');
/*!40000 ALTER TABLE `cars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `car_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `service_id` (`service_id`),
  KEY `car_id` (`car_id`),
  CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`p_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`s_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `carts_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `carts_ibfk_4` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=235 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (220,5,NULL,11,NULL,NULL),(221,5,NULL,13,NULL,NULL),(227,5,NULL,17,NULL,NULL),(233,7,NULL,NULL,11,NULL),(234,7,NULL,10,11,NULL);
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emergency_car`
--

DROP TABLE IF EXISTS `emergency_car`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emergency_car` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emergency_car`
--

LOCK TABLES `emergency_car` WRITE;
/*!40000 ALTER TABLE `emergency_car` DISABLE KEYS */;
INSERT INTO `emergency_car` VALUES (1,'Not Available');
/*!40000 ALTER TABLE `emergency_car` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedbacks`
--

DROP TABLE IF EXISTS `feedbacks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` longtext NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedbacks`
--

LOCK TABLES `feedbacks` WRITE;
/*!40000 ALTER TABLE `feedbacks` DISABLE KEYS */;
INSERT INTO `feedbacks` VALUES (3,'perfect customer service\r\n',7);
/*!40000 ALTER TABLE `feedbacks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `models`
--

DROP TABLE IF EXISTS `models`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `models` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `brand_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `brand_id` (`brand_id`),
  CONSTRAINT `models_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `models`
--

LOCK TABLES `models` WRITE;
/*!40000 ALTER TABLE `models` DISABLE KEYS */;
INSERT INTO `models` VALUES (7,' A3',4),(8,' A4',4),(9,'A5 ',4),(10,' A6',4),(11,' A7',4),(12,' A8',4),(13,' Q3',4),(14,' Q5',4),(15,' Q7',4),(16,' Q8',4),(17,' i3',5),(18,' i4',5),(19,' ix',4),(20,' i7',4),(21,' X6',5),(22,' T3',6),(23,' K8',6),(24,' Spark',7),(25,' malibu',7),(26,' cruze',7),(27,' colorado',7),(28,' C1',8),(29,' C3',8),(30,' C4',8),(31,' C5',8),(32,' Renegade',14),(33,' Compass',14),(34,' Grand Cherokee',14),(35,' Rio',15),(36,' Soul',15),(37,' Forte',15),(38,' Elantra',13),(39,' Accent',13),(40,'  Ibiza',23),(41,'  Clio',22),(42,' 3008',21),(43,' 5008',4),(44,' corsa',20),(45,' astra',20),(46,' Altima',19),(47,' Sentra',19),(48,' CX-30',16),(49,' CX-5',16),(50,' GLS',17),(51,' C-class',17),(52,' A-class',17),(53,' Outlander',18),(54,' Golf',27),(55,' Passat',27),(56,' Corolla',26),(57,' Camry',26),(58,' Octavia',25),(59,' Superb',25);
/*!40000 ALTER TABLE `models` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_details` (
  `details_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`details_id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`p_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_details_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`s_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_details_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_details_ibfk_5` FOREIGN KEY (`order_id`) REFERENCES `orders` (`o_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=192 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_details`
--

LOCK TABLES `order_details` WRITE;
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
INSERT INTO `order_details` VALUES (171,67,7,NULL,23),(172,67,7,16,NULL),(174,67,7,NULL,11),(175,68,5,NULL,13),(176,68,5,NULL,27),(177,68,5,15,NULL),(178,69,5,NULL,11),(179,69,5,NULL,23),(180,69,5,15,NULL),(181,70,7,NULL,NULL),(182,70,7,NULL,NULL),(183,70,7,NULL,NULL),(184,70,7,NULL,NULL),(185,70,7,NULL,NULL),(186,70,7,NULL,10),(187,70,7,NULL,NULL),(188,70,7,NULL,NULL),(189,70,7,NULL,11),(190,70,7,NULL,23),(191,70,7,15,NULL);
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `o_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total_price` float NOT NULL,
  `address_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `service_type` enum('Emergency Car','At Carix Center','Deliver to your Home') NOT NULL,
  PRIMARY KEY (`o_id`),
  KEY `user_id` (`user_id`),
  KEY `address-id` (`address_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `user_address` (`add_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (67,7,6200,18,'2024-06-24','13:00 : 13:45','pending','Deliver to your Home'),(68,5,1922.5,21,'2024-06-16','16:59:31','active','At Carix Center'),(69,5,5885,21,'2024-06-16','19:54:30','active','At Carix Center'),(70,7,11055,22,'2024-06-28','14:00 : 14:45','pending','Deliver to your Home');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `paid` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`o_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (54,67,7,0),(55,68,5,1),(56,69,5,1),(57,70,7,0);
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `p_id` int(11) NOT NULL AUTO_INCREMENT,
  `p_name` varchar(255) NOT NULL,
  `p_price` float NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` longtext NOT NULL,
  PRIMARY KEY (`p_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (15,'Car Air Fresheners',35,43,'Car Air Fresheners.webp'),(16,'mini car charger',200,19,'Car Charger.webp'),(17,'Mobile Car Holder',80,19,'service43.webp'),(18,'Car Sun Shades',300,40,'Car Sun Shades.jpeg'),(20,'Tissue Box',150,40,'tissue.webp'),(21,'car reflective strip',80,100,'car reflective strip.jpeg'),(22,'black car mirror',150,50,'black car mirror.jpeg'),(23,'stickers',90,200,'stickers.jpg'),(24,'seet bilt cover',50,50,'seet bilt cover.jpeg');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `s_id` int(11) NOT NULL AUTO_INCREMENT,
  `s_name` varchar(255) NOT NULL,
  `s_price` float NOT NULL,
  `description` longtext NOT NULL,
  `type` enum('main','additional') NOT NULL,
  PRIMARY KEY (`s_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (10,'Oil Change 10,000 (Shell/Mobil)',2000,'Motor oil Shell (5Wِ-40 ultra) 10,000 4.0L and Oil Filter','main'),(11,'Replace Front Brake Pads ONLY',4950,'Front Brake Pads Brake Cleaner','main'),(12,'Replace Front Brake Pads & Disc',9450,'Front Brake + Pads Front + Disc Brakes Brake Cleaner','main'),(13,'Replace Rear Brake Pads ONLY',1739,'Rear Brake Pads + Brake Cleaner','main'),(14,'Replace Rear Brake Pads & Disc',4097,'Rear Brake Pads Rear Disc Brakes Brake Cleaner','main'),(15,'Advanced Service (Shell)',6495,'Motor oil Shell (5Wِ-40 ultra) 10,000 4.0L+  Air Filter + Cabin Filter+ Oil Filter + Motor oil Shell (5Wِ-40 Ultra) 10,000 1.0L + Lubricant','main'),(16,'Replace Battery',3969,'Battery','main'),(17,'Customer Supplied Parts (Intermediate)',550,'Package Service Intermediate','main'),(18,'Customer Supplied Parts (Advanced)',880,'Package Service Advanced','main'),(19,'Resale Car Inspection',770,'Inspection Report','main'),(20,'Air conditioning service',1045,'Freon','main'),(22,'Engine Bay Cleaning',400,'Engine Bay Cleaning','additional'),(23,'Replace Spark Plugs',900,'Replace Spark Plugs','additional'),(24,'Replace Coolant',550,'Replace Coolant','additional'),(25,'Replace Drive Belt',1200,'Replace Drive Belt','additional'),(26,'Engine Flush',500,'Engine Flush','additional'),(27,'Windshield Washer',148.5,'Windshield Washer','additional'),(28,'Free general checkup',0,'Free general checkup','additional'),(29,'A/C Deodorizer & Antibacterial',500,'A/C Deodorizer & Antibacterial','additional'),(30,'TIRES FIX &MAINTAIN',75,'Provide Top-notch tire services to ensure your vehicle’s safety and performance. Our experienced technicians are committed to delivering exceptional customer service and expert tire care, including repairs, maintenance, and replacements.','additional'),(31,'Oil Change 5000 (Shell/Mobil)',1200,'Motor oil Shell (5Wِ-40 ultra) 5000 4.0L and Oil Filter','main'),(32,'CAR CHECK',300,'we are dedicated to providing comprehensive vehicle inspection and maintenance services to ensure your car is in peak condition. Our team of skilled technicians uses the latest diagnostic tools and techniques to offer a thorough check-up, identifying potential issues before they become major problems.','additional'),(33,'ELECTERICTY FIX',280,'we specialize in providing reliable and professional electrical repair and maintenance services for residential, commercial, and industrial properties. Our team of certified electricians is dedicated to ensuring your electrical systems are safe, efficient, and up-to-date with the latest standards.','main'),(34,'AC & BATTERY FIX',400,'we specialize in providing top-notch air conditioning and battery repair and maintenance services for all types of vehicles. Our skilled technicians are dedicated to ensuring your car AC system keeps you cool and comfortable, while your battery delivers reliable power and performance.','main'),(35,'EMERGENCY CAR',1000,'we provide fast, reliable, and professional emergency automotive services to get you back on the road safely and quickly. Whether you’re dealing with a breakdown, flat tire, dead battery, or any other unexpected issue, our team of experienced technicians is ready to assist you 24/7.','additional'),(36,'CAR WASH',80,'we are committed to providing top-quality car cleaning services to keep your vehicle looking pristine inside and out. Our team of professionals uses the best products and techniques to ensure your car receives the care it deserves, leaving it sparkling clean and protected.','additional');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stations_area`
--

DROP TABLE IF EXISTS `stations_area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stations_area` (
  `area_station_id` int(11) NOT NULL AUTO_INCREMENT,
  `area_station_name` varchar(255) NOT NULL,
  `station_id` int(11) NOT NULL,
  PRIMARY KEY (`area_station_id`),
  KEY `station_id` (`station_id`),
  CONSTRAINT `stations_area_ibfk_1` FOREIGN KEY (`station_id`) REFERENCES `stations_city` (`station_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stations_area`
--

LOCK TABLES `stations_area` WRITE;
/*!40000 ALTER TABLE `stations_area` DISABLE KEYS */;
INSERT INTO `stations_area` VALUES (5,'Dokki',1),(6,'Haram',1),(7,'Feisal',1),(8,'Nazlet El Semman',1),(9,'New Cairo',2),(10,'El Katameya',2),(11,'El Salam ',2);
/*!40000 ALTER TABLE `stations_area` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stations_city`
--

DROP TABLE IF EXISTS `stations_city`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stations_city` (
  `station_id` int(11) NOT NULL AUTO_INCREMENT,
  `station_city` varchar(255) NOT NULL,
  PRIMARY KEY (`station_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stations_city`
--

LOCK TABLES `stations_city` WRITE;
/*!40000 ALTER TABLE `stations_city` DISABLE KEYS */;
INSERT INTO `stations_city` VALUES (1,'Giza'),(2,'Cairo');
/*!40000 ALTER TABLE `stations_city` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stations_locations`
--

DROP TABLE IF EXISTS `stations_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stations_locations` (
  `location_station_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_address` varchar(255) NOT NULL,
  `location_link` varchar(255) NOT NULL,
  `area_station_id` int(11) NOT NULL,
  PRIMARY KEY (`location_station_id`),
  KEY `area_station_id` (`area_station_id`),
  CONSTRAINT `stations_locations_ibfk_1` FOREIGN KEY (`area_station_id`) REFERENCES `stations_area` (`area_station_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stations_locations`
--

LOCK TABLES `stations_locations` WRITE;
/*!40000 ALTER TABLE `stations_locations` DISABLE KEYS */;
INSERT INTO `stations_locations` VALUES (5,'Ministry of Agriculture Street, in front of Dokki Bridge.','https://maps.app.goo.gl/4Wox2WvcJzZhsGUMA',5),(6,'Ring Rd., In Front Of Cairo Festival City Mall','https://maps.app.goo.gl/hnTzfcpBFT4xPykB7',9),(7,'Road 90 (Tes3een) at Fifth Settlement','https://maps.app.goo.gl/Z21GJQeoizGGU4ua8',9),(8,'El Shaheed Spine, El Marwaha Sq., In Front Of Mohamed Zaky Automotive','https://maps.app.goo.gl/aEWjzunjXmaKGPzd7',10),(9,'Cairo El Ain El Shokhna Rd.','https://maps.app.goo.gl/oENq8ejEBvNYFa5F6',10),(10,'10 Haram St., Beside Al Haram Cinema','https://maps.app.goo.gl/kswb6NBKNbPpsxt98',6),(11,'Feisal St., Beside El Tawhid & El Nour Store','https://maps.app.goo.gl/V8oM5EGpWG6T8xuq7',7),(12,'Nazlet El Semman St., Near The Pyramids','https://maps.app.goo.gl/jzwspBtT1EnaduEw5',8),(13,'Ring Rd., Beside Arab Co.','https://maps.app.goo.gl/EVfMw7dzm3fRgdLD8',11);
/*!40000 ALTER TABLE `stations_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_address`
--

DROP TABLE IF EXISTS `user_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_address` (
  `add_id` int(11) NOT NULL AUTO_INCREMENT,
  `area_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`add_id`),
  KEY `area_id` (`area_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_address_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_address_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_address`
--

LOCK TABLES `user_address` WRITE;
/*!40000 ALTER TABLE `user_address` DISABLE KEYS */;
INSERT INTO `user_address` VALUES (15,32,'15 hussein kamal',7),(18,5,'21',7),(20,6,'21 abo el feda',7),(21,24,'Dokki',5),(22,18,'smdaklsd',7),(23,17,'smdaklsd',7);
/*!40000 ALTER TABLE `user_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (5,'Carix',NULL,'carix2024@gmail.com','$2y$10$lRbjkB7.3IrSYOBstKpJ2uRagDkEkg3yFFVvbITxXu4u6RVVPJ9b6','carix805866'),(7,'Mennatullah Khaled','01062871829','mennatullahkhaled21@gmail.com','$2y$10$tgVg.xsPzA13D0ZBuafNHeQTUuUEMTo0qh2VGqgs0jh3.6g2xLM0m','carix149573');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallets`
--

DROP TABLE IF EXISTS `wallets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wallets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cash` float NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `wallets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallets`
--

LOCK TABLES `wallets` WRITE;
/*!40000 ALTER TABLE `wallets` DISABLE KEYS */;
INSERT INTO `wallets` VALUES (6,3879.6,7);
/*!40000 ALTER TABLE `wallets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `website`
--

DROP TABLE IF EXISTS `website`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `website` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `logo` longtext NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `working_hours` varchar(255) NOT NULL,
  `hotline` varchar(11) NOT NULL,
  `mail` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `website`
--

LOCK TABLES `website` WRITE;
/*!40000 ALTER TABLE `website` DISABLE KEYS */;
INSERT INTO `website` VALUES (1,'Carix','Screenshot_18-4-2024_125921_.jpeg','El Dokki','01153360661','9am : 12am','11223','carix2024@gmail.com');
/*!40000 ALTER TABLE `website` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-23  2:58:37
