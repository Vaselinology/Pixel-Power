-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 26 mai 2025 à 23:56
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `store`
--

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `GetUserOrders`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetUserOrders` (IN `userID` INT)   BEGIN
            -- Try different column names
            IF EXISTS (SELECT 1 FROM information_schema.columns 
                      WHERE table_name = 'orders' AND column_name = 'customer_id') THEN
                SELECT id, order_date as date, total_amount as total, status 
                FROM orders WHERE customer_id = userID
                ORDER BY order_date DESC LIMIT 5;
            ELSEIF EXISTS (SELECT 1 FROM information_schema.columns 
                         WHERE table_name = 'orders' AND column_name = 'user_id') THEN
                SELECT id, order_date as date, total_amount as total, status 
                FROM orders WHERE user_id = userID
                ORDER BY order_date DESC LIMIT 5;
            ELSE
                SIGNAL SQLSTATE '45000' 
                SET MESSAGE_TEXT = 'No valid user reference column found in orders table';
            END IF;
        END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `blogpost`
--

DROP TABLE IF EXISTS `blogpost`;
CREATE TABLE IF NOT EXISTS `blogpost` (
  `id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int DEFAULT NULL,
  `title` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_general_ci,
  `date_posted` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blogpost_ibfk_1` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cartitem`
--

DROP TABLE IF EXISTS `cartitem`;
CREATE TABLE IF NOT EXISTS `cartitem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cart_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_id` (`cart_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_customer_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `customer`
--

INSERT INTO `customer` (`id`, `user_id`, `address`, `created_at`, `name`) VALUES
(0, 8, '123 Gaming Street', '2025-05-25 17:02:05', 'salsa'),
(1, 15, '22 ariana tunisie', '2025-05-25 19:09:33', 'ali masmoudi'),
(2, 16, '26 ariana tunisie', '2025-05-26 16:15:46', 'dora masmoudi'),
(3, 5, '29 ariana tunisie', '2025-05-26 16:23:39', 'nour '),
(4, 9, '30 ariana tunisie', '2025-05-26 16:26:19', 'chams'),
(5, 17, '32 ariana tunisie', '2025-05-27 01:36:33', 'sandra'),
(6, 18, '33 ariana tunisie', '2025-05-27 01:41:09', 'roua');

-- --------------------------------------------------------

--
-- Structure de la table `gamecurrencyrequest`
--

DROP TABLE IF EXISTS `gamecurrencyrequest`;
CREATE TABLE IF NOT EXISTS `gamecurrencyrequest` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int DEFAULT NULL,
  `game_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','approved','declined','completed') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `date_requested` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('partnership','feedback') COLLATE utf8mb4_general_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_general_ci,
  `date_sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id`, `customer_id`, `name`, `email`, `type`, `subject`, `content`, `date_sent`) VALUES
(1, NULL, 'hjfbdnkn h,bsxnh', 'nour.masmoudi.2023@ihec.ucar.tn', 'feedback', 'Contact Form Submission', 'jsbhjbhsx', '2025-05-26 12:22:24'),
(2, NULL, 'nour masmoudi', 'nour.masmoudi.2023@ihec.ucar.tn', 'partnership', 'partnership', 'i want  to grow together', '2025-05-26 12:31:34'),
(3, NULL, 'nour masmoudi', 'nour.masmoudi.2023@ihec.ucar.tn', 'feedback', 'Contact Form Submission', 'hey , can you refill the stock of pc asus', '2025-05-26 12:44:09'),
(4, NULL, 'nour masmoudi', 'nour.masmoudi.2023@ihec.ucar.tn', 'feedback', 'Contact Form Submission', 'hey , can you refill the stock of pc asus', '2025-05-26 12:44:45'),
(5, NULL, 'fatma masmoudi', 'fatma.masmoudi.2023@ihec.ucar.tn', 'feedback', 'Contact Form Submission', 'hey , can you inform me about the date of arrival of pc asus', '2025-05-26 12:46:13'),
(18, NULL, 'ali masmoudi', 'ali.masmoudi.2023@ihec.ucar.tn', 'feedback', 'Contact Form Submission', 'heyy', '2025-05-26 13:10:07'),
(19, 0, 'ali masmoudi', 'ali.masmoudi.2023@ihec.ucar.tn', 'feedback', 'Contact Form Submission', 'heyy', '2025-05-26 13:14:34'),
(20, 0, 'ali masmoudi', 'ali.masmoudi.2023@ihec.ucar.tn', 'feedback', 'Contact Form Submission', 'heyyy', '2025-05-26 13:14:48'),
(23, NULL, 'fatma masmoudi', 'fatma.masmoudi.2023@ihec.ucar.tn', 'feedback', 'Contact Form', 'i want to', '2025-05-26 13:41:20'),
(24, 0, '', '', '', NULL, NULL, '2025-05-26 14:03:50'),
(25, NULL, 'fatma masmoudi', 'fatma.masmoudi.2023@ihec.ucar.tn', 'feedback', 'Contact Form', 'hello', '2025-05-26 14:09:45'),
(26, NULL, 'fatma masmoudi', 'fatma.masmoudi.2023@ihec.ucar.tn', 'partnership', 'partnership', 'want to collaborate', '2025-05-26 14:11:14'),
(27, NULL, 'fatma masmoudi', 'fatma.masmoudi.2023@ihec.ucar.tn', 'partnership', 'partnership', 'want to collaborate', '2025-05-26 14:12:34');

-- --------------------------------------------------------

--
-- Structure de la table `orderitem`
--

DROP TABLE IF EXISTS `orderitem`;
CREATE TABLE IF NOT EXISTS `orderitem` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `orderitem`
--

INSERT INTO `orderitem` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 3, 7, 23, '59.99'),
(2, 4, 2, 1, '119.99'),
(3, 5, 3, 1, '149.99'),
(4, 6, 4, 4, '79.99'),
(5, 7, 2, 4, '119.99'),
(6, 8, 3, 1, '149.99'),
(7, 9, 2, 1, '119.99'),
(8, 10, 2, 1, '119.99'),
(9, 11, 2, 1, '119.99'),
(10, 12, 3, 1, '149.99'),
(11, 13, 4, 1, '79.99'),
(12, 14, 4, 5, '79.99'),
(13, 15, 5, 2, '59.99'),
(14, 16, 22, 4, '36.99'),
(15, 17, 4, 1, '79.99'),
(16, 17, 1, 31, '49.99'),
(17, 18, 2, 5, '119.99'),
(18, 19, 2, 1, '119.99'),
(19, 19, 5, 1, '59.99'),
(20, 20, 3, 3, '149.99');

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int DEFAULT NULL,
  `order_date` date NOT NULL,
  `status` enum('placed','fulfilled','cancelled') COLLATE utf8mb4_general_ci DEFAULT 'placed',
  `total_amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `order_date`, `status`, `total_amount`) VALUES
(3, 0, '2025-05-25', 'placed', '1379.77'),
(4, 0, '2025-05-25', 'placed', '119.99'),
(5, 1, '2025-05-25', 'placed', '149.99'),
(6, 1, '2025-05-25', 'placed', '319.96'),
(7, 1, '2025-05-25', 'placed', '479.96'),
(8, 1, '2025-05-25', 'placed', '149.99'),
(9, 1, '2025-05-25', 'placed', '119.99'),
(10, 1, '2025-05-25', 'placed', '119.99'),
(11, 1, '2025-05-25', 'placed', '119.99'),
(12, 1, '2025-05-25', 'placed', '149.99'),
(13, 2, '2025-05-26', 'placed', '79.99'),
(14, 2, '2025-05-26', 'placed', '399.95'),
(15, 2, '2025-05-26', 'placed', '119.98'),
(16, 2, '2025-05-26', 'placed', '147.96'),
(17, 2, '2025-05-27', 'placed', '1629.68'),
(18, 2, '2025-05-27', 'placed', '599.95'),
(19, 2, '2025-05-27', 'placed', '179.98'),
(20, 5, '2025-05-27', 'placed', '449.97');

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

DROP TABLE IF EXISTS `panier`;
CREATE TABLE IF NOT EXISTS `panier` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `price` decimal(10,2) NOT NULL,
  `stock` int DEFAULT '0',
  `category` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_url` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`id`, `name`, `description`, `price`, `stock`, `category`, `image_url`) VALUES
(1, 'Logitech G502 HERO Mouse', 'High-performance gaming mouse with customizable DPI and RGB lighting.', '49.99', -11, 'Gaming Equipment', 'logitech_g502.jpg'),
(2, 'Razer BlackWidow V3 Keyboard', 'Mechanical keyboard with Razer Green switches and Chroma RGB.', '119.99', 1, 'Gaming Equipment', 'razer_blackwidow_v3.jpg'),
(3, 'SteelSeries Arctis 7 Headset', 'Wireless headset with surround sound and long battery life.', '149.99', 4, 'Gaming Equipment', 'steelseries_arctis7.jpg'),
(4, 'Elgato Stream Deck Mini', 'Compact control pad with 6 customizable LCD keys for streaming.', '79.99', -3, 'Gaming Equipment', 'elgato_stream_deck.jpg'),
(5, 'Xbox Wireless Controller', 'Bluetooth-enabled controller compatible with PC and Xbox.', '59.99', 22, 'Gaming Equipment', 'xbox_controller.jpg'),
(6, 'Razer BlackWidow V3 Mechanical Keyboard', 'RGB mechanical keyboard with Green switches, ideal for gamers.', '129.99', 10, 'Keyboard', 'keyboard1.jpg'),
(7, 'Logitech G502 HERO Gaming Mouse', 'High-performance gaming mouse with 16K DPI sensor.', '59.99', -8, 'Mouse', 'mouse1.jpg'),
(8, 'SteelSeries Arctis 7 Wireless Headset', 'Lag-free wireless headset with surround sound.', '149.99', 8, 'Headset', 'headset1.jpg'),
(9, 'ASUS TUF Gaming Monitor 27\"', '144Hz Full HD gaming monitor with ultra-low motion blur.', '239.99', 5, 'Monitor', 'monitor1.jpg'),
(10, 'Corsair K70 RGB TKL', 'Compact mechanical keyboard with per-key RGB lighting.', '139.99', 12, 'Keyboard', 'keyboard2.jpg'),
(11, 'HyperX Cloud II Headset', 'Comfortable headset with 7.1 surround sound.', '99.99', 18, 'Headset', 'headset2.jpg'),
(12, 'Xbox Series X Wireless Controller', 'Ergonomic controller with hybrid D-pad and textured grip.', '64.99', 20, 'Controller', 'controller1.jpg'),
(13, 'Elgato Stream Deck Mini', 'Customizable control pad for streamers.', '99.99', 6, 'Accessory', 'streamdeck1.jpg'),
(14, 'Logitech G920 Racing Wheel', 'Steering wheel and pedals for realistic racing simulation.', '299.99', 3, 'Accessory', 'racingwheel1.jpg'),
(15, 'Samsung 980 Pro 1TB NVMe SSD', 'Blazing fast storage for quicker game loads.', '129.99', 9, 'Storage', 'ssd1.jpg'),
(16, 'Master Chief Figurine', 'Highly detailed 6-inch figurine of Master Chief from Halo Infinite.', '34.99', 15, 'Figurine', 'masterchief.jpg'),
(17, 'Link – Breath of the Wild Figurine', 'Officially licensed Nintendo figurine of Link with sword and shield.', '29.99', 12, 'Figurine', 'link_botw.jpg'),
(18, 'Geralt of Rivia Figurine', 'The Witcher 3: Wild Hunt Geralt figure in battle stance.', '39.99', 8, 'Figurine', 'geralt.jpg'),
(19, 'Kratos and Atreus Diorama', 'Limited edition God of War diorama with Kratos and Atreus.', '59.99', 5, 'Figurine', 'kratos_atreus.jpg'),
(20, 'Pikachu PVC Statue', 'Cute Pikachu figurine with electric effect base.', '19.99', 20, 'Figurine', 'pikachu.jpg'),
(21, 'Aloy Horizon Zero Dawn Figurine', 'Detailed collectible of Aloy with bow and spear.', '44.99', 7, 'Figurine', 'aloy.jpg'),
(22, 'Lara Croft Tomb Raider Statue', 'Iconic figurine of Lara Croft in action pose.', '36.99', 6, 'Figurine', 'lara.jpg'),
(23, 'Doom Slayer Collectible Figure', 'Stylized Doom Slayer collectible from DOOM Eternal.', '24.99', 9, 'Figurine', 'doomslayer.jpg'),
(24, 'Ezio Auditore Figure', 'Assassin’s Creed figurine of Ezio with hidden blades.', '32.99', 11, 'Figurine', 'ezio.jpg'),
(25, 'Vault Boy Bobblehead', 'Fallout Vault-Tec bobblehead – Strength edition.', '14.99', 18, 'Figurine', 'vaultboy.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `review`
--

DROP TABLE IF EXISTS `review`;
CREATE TABLE IF NOT EXISTS `review` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `comment` text COLLATE utf8mb4_general_ci,
  `date_posted` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `product_id` (`product_id`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`) VALUES
(1, 'Gamer John', 'gamerjohn@example.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f'),
(2, 'Pixel Girl', 'pixelgirl@example.com', '89e01536ac207279409d4de1e5253e01f4a1769e696db0d6062ca9b8f56767c8'),
(3, 'Noob Master', 'noobmaster@example.com', '7626bc017df14acea4e7ff0b9ed9429d28ce664cbd0683c83c3bb8153b46f63c'),
(4, 'Retro King', 'retroking@example.com', 'fbb4a8a163ffa958b4f02bf9cabb30cfefb40de803f2c4c346a9d39b3be1b544'),
(5, 'nour masmoudi', 'nour.masmoudi.2023@ihec.ucar.tn', '$2y$10$7Xd/.3..95K9RyMizIXxH.DC2rTyhSoVuHN0ZrcP7Y1YtP6hqHwfa'),
(6, 'zeineb kooli', 'zeineb.kooli.2023@ihec.ucar.tn', '$2y$10$EvJwpoZGUasxsPSr5ZSZSOYiGUXs6ZAQvW5hk4CGHukvegRSDHhUm'),
(7, 'marwa', 'marwa.masmoudi.2023@ihec.ucar.tn', '$2y$10$xft64TWlgGTksPqumDvmSuKWYZ7w1lGwlAnY7aIRpvEz1Saif.pX.'),
(8, 'salsa', 'salsa.masmoudi.2023@ihec.ucar.tn', '$2y$10$HHkKBEpckO7PaPjTxDaHpOeYgpczhSsvJh9lxcDzt4Gxg3uFPT1hG'),
(9, 'chams', 'chams.masmoudi.2023@ihec.ucar.tn', '$2y$10$NCZux3UI8KA7PEoygg/ixO.DziKSIiQZFVrfPhH7OQDN1EX6lnfca'),
(14, 'fatma masmoudi', 'fatma.masmoudi.2023@ihec.ucar.tn', '$2y$10$Ch2tBu3LoaCW0Q73uxI93e2bEzJENOCrvhzRx6dW60JFyBifLF.xu'),
(15, 'ali masmoudi', 'alimasmoudi.2023@ihec.ucar.tn', '$2y$10$cmVOkAqZR0bvjCdxgQngLeORdEqpKH7GDEpjynxAOeduQqfVCDmSa'),
(16, 'dora masmoudi', 'dora.masmoudi.2023@ihec.ucar.tn', '$2y$10$qBZvdZUctpZsPNaDwv0HSe1DkY0xuIPBcusgIZpoYwPLSyi99np1m'),
(17, 'sandra', 'sandra.masmoudi.2023@ihec.ucar.tn', '$2y$10$IEUPzDfAwL7J9URnyEkTCu21gHCjRdVVrG9GYUBdVOnZSYNkWh8Ha'),
(18, 'roua', 'roua.masmoudi.2023@ihec.ucar.tn', '$2y$10$quUrtS2agzHUUcA4Q4/GV.xk1Ux21DH/DJN5p4F0oCMK8Y3hFIVTO');

-- --------------------------------------------------------

--
-- Structure de la table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `blogpost`
--
ALTER TABLE `blogpost`
  ADD CONSTRAINT `blogpost_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `cartitem`
--
ALTER TABLE `cartitem`
  ADD CONSTRAINT `cartitem_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cartitem_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `fk_customer_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `gamecurrencyrequest`
--
ALTER TABLE `gamecurrencyrequest`
  ADD CONSTRAINT `gamecurrencyrequest_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `orderitem`
--
ALTER TABLE `orderitem`
  ADD CONSTRAINT `orderitem_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderitem_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
