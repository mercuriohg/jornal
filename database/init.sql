-- MySQL initialization script for jornalgremio
-- Creates database, tables and inserts sample data similar to previous data setup

DROP DATABASE IF EXISTS `jornalgremio`;
CREATE DATABASE `jornalgremio` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `jornalgremio`;

-- Users table
CREATE TABLE `users` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(100) NOT NULL UNIQUE,
	`email` VARCHAR(255) NOT NULL UNIQUE,
	`password_hash` VARCHAR(255) NOT NULL,
	`role` ENUM('admin','editor','reader') NOT NULL DEFAULT 'reader',
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Categories table
CREATE TABLE `categories` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100) NOT NULL UNIQUE,
	`slug` VARCHAR(120) NOT NULL UNIQUE,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Articles/posts table
CREATE TABLE `posts` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` INT UNSIGNED NOT NULL,
	`category_id` INT UNSIGNED DEFAULT NULL,
	`title` VARCHAR(255) NOT NULL,
	`slug` VARCHAR(255) NOT NULL UNIQUE,
	`content` TEXT NOT NULL,
	`published` TINYINT(1) NOT NULL DEFAULT 0,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
	FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Comments table
CREATE TABLE `comments` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`post_id` INT UNSIGNED NOT NULL,
	`author_name` VARCHAR(150) NOT NULL,
	`author_email` VARCHAR(255) NOT NULL,
	`content` TEXT NOT NULL,
	`approved` TINYINT(1) NOT NULL DEFAULT 0,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Simple settings table
CREATE TABLE `settings` (
	`key` VARCHAR(100) NOT NULL,
	`value` TEXT NOT NULL,
	PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample seed data
INSERT INTO `users` (`username`,`email`,`password_hash`,`role`) VALUES
('admin','admin@example.com','$2y$10$examplehashreplace', 'admin'),
('editor','editor@example.com','$2y$10$examplehashreplace','editor');

INSERT INTO `categories` (`name`,`slug`) VALUES
('Notícias','noticias'),
('Opinião','opiniao');

INSERT INTO `posts` (`user_id`,`category_id`,`title`,`slug`,`content`,`published`) VALUES
(1,1,'Bem-vindo ao Jornal Grêmio','bem-vindo-ao-jornal-gremio','Conteúdo inicial do site.',1),
(2,2,'Coluna de Opinião','coluna-de-opiniao','Texto da coluna de opinião.',0);

INSERT INTO `comments` (`post_id`,`author_name`,`author_email`,`content`,`approved`) VALUES
(1,'Leitor','leitor@example.com','Ótimo artigo!',1);

INSERT INTO `settings` (`key`,`value`) VALUES
('site_name','Jornal Grêmio'),
('site_description','Portal de notícias do Grêmio');


