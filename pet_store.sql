-- MySQL dump 10.16  Distrib 10.1.38-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: pet_store
-- ------------------------------------------------------
-- Server version	10.1.38-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `pet_type` varchar(45) NOT NULL,
  `item_type` varchar(45) NOT NULL,
  `color` varchar(45) NOT NULL,
  `lifespan` int(11) NOT NULL,
  `age` int(11) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Twisty Rope','n/a','Toy','grey',2,0,20),(2,'Chihuahua','Dog','Pet','black',15,1,100),(3,'Rotweiler','Dog','Pet','pink',10,2,300),(4,'Mini Poodle','Dog','Pet','white',12,8,1000),(5,'Standard Poodle','Dog','Pet','black',12,1,1500),(6,'Bengal','Cat','Pet','spotted',9,4,250),(7,'Siamese','Cat','Pet','offwhite',8,3,350),(8,'Tiger','Cat','Pet','orange',18,7,2000),(9,'Bearded Dragon','Reptile','Pet','copper',18,10,400),(10,'Chamelion','Reptile','Pet','green',16,1,800),(11,'King Snake','Reptile','Pet','yellow',15,2,150),(12,'St. Bernard','Dog','Pet','brown',13,2,2500),(13,'Liger','Cat','Pet','gold',18,12,6500),(14,'Cerberus','Dog','Pet','black',25,12,6600),(15,'Liz Casa 3000','Reptile','Cage','brown',0,0,180),(16,'Snake on the Go','Reptile','Carrier','red',0,0,100),(17,'Turtle Cage','Reptile','Cage','blue',0,0,120),(18,'Kanine Kennel Large','Dog','Carrier','tan',0,0,180),(19,'Kanine Kennel Medium','Dog','Carrier','tan',0,0,180),(20,'Kanine Kennel Small','Dog','Carrier','tan',0,0,180),(21,'Kitty Kennel Small','Cat','Carrier','pink',0,0,110),(22,'Kitty Kennel Medium','Cat','Carrier','pink',0,0,120),(23,'Kitty Kennel Large','Cat','Carrier','pink',0,0,130),(24,'Cat Nip Ball','Cat','Toy','green',0,0,50),(25,'Toy Mouse','Cat','Toy','grey',0,0,20),(26,'Lizard Frisbee','Reptile','Toy','orange',0,0,15),(27,'King Cobra','Reptile','Pet','green',16,4,450),(28,'Reptar','Reptile','Pet','green',80,20,980),(29,'Doggie Doo','Dog','Toy','brown',4,0,35),(30,'Dog Ball','Dog','Toy','yellow',4,0,18);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-04-01 11:19:39
