-- --------------------------------------------------------
-- Хост:                         95.183.11.26
-- Версия сервера:               5.5.64-MariaDB - MariaDB Server
-- Операционная система:         Linux
-- HeidiSQL Версия:              9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры для таблица admin_test.brands
CREATE TABLE IF NOT EXISTS `brands` (
  `id_brand` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL,
  PRIMARY KEY (`id_brand`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы admin_test.brands: ~5 rows (приблизительно)
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` (`id_brand`, `name`) VALUES
	(1, 'Audi'),
	(5, 'Bentley'),
	(3, 'Jac'),
	(4, 'KIA'),
	(2, 'Skoda');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;

-- Дамп структуры для таблица admin_test.cars
CREATE TABLE IF NOT EXISTS `cars` (
  `id_car` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_model` int(10) unsigned NOT NULL,
  `mileage` char(100) DEFAULT NULL,
  `price` char(100) NOT NULL,
  `phone` char(100) NOT NULL,
  PRIMARY KEY (`id_car`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы admin_test.cars: ~1 rows (приблизительно)
/*!40000 ALTER TABLE `cars` DISABLE KEYS */;
INSERT INTO `cars` (`id_car`, `id_model`, `mileage`, `price`, `phone`) VALUES
	(25, 8, '', '1000000', '1111111111111');
/*!40000 ALTER TABLE `cars` ENABLE KEYS */;

-- Дамп структуры для таблица admin_test.cars_equipments
CREATE TABLE IF NOT EXISTS `cars_equipments` (
  `id_car` int(10) unsigned NOT NULL,
  `id_equipment` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_car`,`id_equipment`),
  CONSTRAINT `FK_cars_equipments_cars` FOREIGN KEY (`id_car`) REFERENCES `cars` (`id_car`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы admin_test.cars_equipments: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `cars_equipments` DISABLE KEYS */;
INSERT INTO `cars_equipments` (`id_car`, `id_equipment`) VALUES
	(25, 3),
	(25, 4);
/*!40000 ALTER TABLE `cars_equipments` ENABLE KEYS */;

-- Дамп структуры для таблица admin_test.cars_photos
CREATE TABLE IF NOT EXISTS `cars_photos` (
  `id_photo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_car` int(10) unsigned NOT NULL,
  `file_name` char(50) NOT NULL,
  `is_main` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_photo`),
  KEY `main` (`is_main`,`id_car`),
  KEY `FK_cars_photos_cars` (`id_car`),
  CONSTRAINT `FK_cars_photos_cars` FOREIGN KEY (`id_car`) REFERENCES `cars` (`id_car`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы admin_test.cars_photos: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `cars_photos` DISABLE KEYS */;
INSERT INTO `cars_photos` (`id_photo`, `id_car`, `file_name`, `is_main`) VALUES
	(38, 25, '6329ac1d86773a67995a34244c8060c4.jpg', 1),
	(39, 25, '9b7a5740f46b62a3a5480a514d50e1fd.jpg', 0);
/*!40000 ALTER TABLE `cars_photos` ENABLE KEYS */;

-- Дамп структуры для таблица admin_test.models
CREATE TABLE IF NOT EXISTS `models` (
  `id_model` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_brand` int(10) unsigned NOT NULL,
  `name` char(50) NOT NULL,
  PRIMARY KEY (`id_model`),
  KEY `brand` (`id_brand`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы admin_test.models: ~13 rows (приблизительно)
/*!40000 ALTER TABLE `models` DISABLE KEYS */;
INSERT INTO `models` (`id_model`, `id_brand`, `name`) VALUES
	(1, 1, 'A4'),
	(2, 1, 'A6'),
	(3, 1, 'A8'),
	(4, 1, 'Q3'),
	(5, 2, 'Fabia'),
	(6, 2, 'Kodiaq'),
	(7, 2, 'Rapid'),
	(8, 3, '245'),
	(9, 3, 'J5'),
	(10, 3, 'S5'),
	(11, 4, 'Rio'),
	(12, 4, 'Sorento'),
	(13, 5, 'Continental GT');
/*!40000 ALTER TABLE `models` ENABLE KEYS */;

-- Дамп структуры для таблица admin_test.optional_equipments
CREATE TABLE IF NOT EXISTS `optional_equipments` (
  `id_equipment` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(100) NOT NULL,
  PRIMARY KEY (`id_equipment`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы admin_test.optional_equipments: ~6 rows (приблизительно)
/*!40000 ALTER TABLE `optional_equipments` DISABLE KEYS */;
INSERT INTO `optional_equipments` (`id_equipment`, `name`) VALUES
	(1, 'ABS'),
	(2, 'Break assist'),
	(3, 'EBD'),
	(4, 'ESP'),
	(5, 'Датчик дождя'),
	(6, 'Подушка безопастности водителя');
/*!40000 ALTER TABLE `optional_equipments` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
