-- MySQL Script generated by MySQL Workbench
-- Fri Sep  9 08:54:24 2022
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema taskforce
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `taskforce` ;

-- -----------------------------------------------------
-- Schema taskforce
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `taskforce` DEFAULT CHARACTER SET utf8 ;
USE `taskforce` ;

-- -----------------------------------------------------
-- Table `taskforce`.`cities`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cities` ;

CREATE TABLE IF NOT EXISTS `cities` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `add_date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `name` VARCHAR(255) NOT NULL,
  `lat` DECIMAL(10,7) NULL,
  `lng` DECIMAL(10,7) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `users` ;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `reg_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(128) NOT NULL,
  `password` VARCHAR(64) NOT NULL,
  `is_worker` TINYINT NOT NULL DEFAULT 0,
  `avatar` VARCHAR(512) NULL,
  `birthday` DATE NULL,
  `city_id` INT NULL,
  `phone` INT NULL,
  `telegram` VARCHAR(64) NULL,
  `about_me` TEXT NULL,
  `show_contacts` TINYINT NOT NULL DEFAULT 1,
  `tasks_completed` INT NOT NULL DEFAULT 0,
  `tasks_failed` INT NOT NULL DEFAULT 0,
  `raiting` DECIMAL(3,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `user_city_idx` (`city_id` ASC),
  CONSTRAINT `user_city`
  FOREIGN KEY (`city_id`)
  REFERENCES `taskforce`.`cities` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION)
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`categories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `categories` ;

CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `add_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` VARCHAR(255) NOT NULL,
  `icon` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`user_categories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_categories` ;

CREATE TABLE IF NOT EXISTS `user_categories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `category_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `user_id_idx` (`user_id` ASC),
  INDEX `category_id_idx` (`category_id` ASC),
  CONSTRAINT `user_id`
  FOREIGN KEY (`user_id`)
  REFERENCES `taskforce`.`users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
  CONSTRAINT `user_category_id`
  FOREIGN KEY (`category_id`)
  REFERENCES `taskforce`.`categories` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION)
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`tasks`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tasks` ;

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `add_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `category_id` INT NOT NULL,
  `price` INT NULL,
  `end_date` DATE NULL,
  `author_id` INT NOT NULL,
  `worker_id` INT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'new',
  `address` VARCHAR(255) NULL,
  `city_id` INT NULL,
  `lat` DECIMAL(10,7) NULL,
  `lng` DECIMAL(10,7) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `author_id_idx` (`author_id` ASC),
  INDEX `worker_id_idx` (`worker_id` ASC),
  INDEX `category_id_idx` (`category_id` ASC),
  INDEX `task_city_id_idx` (`city_id` ASC),
  CONSTRAINT `task_author_id`
  FOREIGN KEY (`author_id`)
  REFERENCES `taskforce`.`users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
  CONSTRAINT `task_worker_id`
  FOREIGN KEY (`worker_id`)
  REFERENCES `taskforce`.`users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
  CONSTRAINT `task_category_id`
  FOREIGN KEY (`category_id`)
  REFERENCES `taskforce`.`categories` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
  CONSTRAINT `task_city_id`
  FOREIGN KEY (`city_id`)
  REFERENCES `taskforce`.`cities` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION)
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`task_files`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `task_files` ;

CREATE TABLE IF NOT EXISTS `task_files` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `task_id` INT NOT NULL,
  `path` VARCHAR(512) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `task_id_idx` (`task_id` ASC),
  CONSTRAINT `task_id`
  FOREIGN KEY (`task_id`)
  REFERENCES `taskforce`.`tasks` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION)
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`responses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `responses` ;

CREATE TABLE IF NOT EXISTS `responses` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `add_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `task_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `comment` VARCHAR(512) NULL,
  `price` INT NULL,
  `rejected` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `task_id_idx` (`task_id` ASC),
  INDEX `user_id_idx` (`user_id` ASC),
  CONSTRAINT `res_task_id`
  FOREIGN KEY (`task_id`)
  REFERENCES `taskforce`.`tasks` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
  CONSTRAINT `res_user_id`
  FOREIGN KEY (`user_id`)
  REFERENCES `taskforce`.`users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION)
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `taskforce`.`reviews`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reviews` ;

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `add_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `task_id` INT NOT NULL,
  `worker_id` INT NOT NULL,
  `author_id` INT NOT NULL,
  `review` TEXT NULL,
  `mark` TINYINT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `review_author_idx` (`author_id` ASC),
  INDEX `review_worker_idx` (`worker_id` ASC),
  INDEX `review_task_idx` (`task_id` ASC),
  CONSTRAINT `review_author`
  FOREIGN KEY (`author_id`)
  REFERENCES `taskforce`.`users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
  CONSTRAINT `review_worker`
  FOREIGN KEY (`worker_id`)
  REFERENCES `taskforce`.`users` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION,
  CONSTRAINT `review_task`
  FOREIGN KEY (`task_id`)
  REFERENCES `taskforce`.`tasks` (`id`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION)
  ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
