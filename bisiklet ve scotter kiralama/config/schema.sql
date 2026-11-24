CREATE DATABASE IF NOT EXISTS `kiralama_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `kiralama_db`;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `fullname` VARCHAR(100) NOT NULL,
  `email` VARCHAR(120) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `role` ENUM('admin', 'musteri') DEFAULT 'musteri',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `vehicles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(120) NOT NULL,
  `slug` VARCHAR(150) NOT NULL,
  `type` ENUM('bisiklet', 'scooter') NOT NULL,
  `hourly_rate` DECIMAL(8,2) NOT NULL,
  `battery_or_gear` VARCHAR(50) DEFAULT NULL,
  `lat` DECIMAL(10,8) NOT NULL,
  `lng` DECIMAL(11,8) NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('musait', 'kirada', 'bakimda') DEFAULT 'musait'
);

CREATE TABLE `rentals` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `vehicle_id` INT NOT NULL,
  `start_time` DATETIME NOT NULL,
  `end_time` DATETIME DEFAULT NULL,
  `total_cost` DECIMAL(8,2) DEFAULT 0.00,
  `status` ENUM('aktif', 'tamamlandi', 'iptal') DEFAULT 'aktif',
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles`(`id`) ON DELETE CASCADE
);