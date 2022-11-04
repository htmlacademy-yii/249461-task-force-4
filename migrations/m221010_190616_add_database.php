<?php

use yii\db\Migration;

/**
 * Class m221030_190616_add_database
 */
class m221010_190616_add_database extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute($this->databaseStructure);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221030_190616_add_database cannot be reverted.\n";

        return false;
    }

    private $databaseStructure = "
        CREATE TABLE IF NOT EXISTS `cities` (
          `id` INT NOT NULL AUTO_INCREMENT,
          `add_date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
          `name` VARCHAR(255) NOT NULL,
          `lat` DECIMAL(10,7) NULL,
          `lng` DECIMAL(10,7) NULL,
          PRIMARY KEY (`id`),
          UNIQUE INDEX `id_UNIQUE` (`id` ASC))
          ENGINE = InnoDB;
        
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
          `phone` VARCHAR(20) NULL,
          `telegram` VARCHAR(64) NULL,
          `about_me` TEXT NULL,
          `show_contacts` TINYINT NOT NULL DEFAULT 1,
          `tasks_completed` INT NOT NULL DEFAULT 0,
          `tasks_failed` INT NOT NULL DEFAULT 0,
          `rating` DECIMAL(3,2) NOT NULL DEFAULT 0,
          PRIMARY KEY (`id`),
          INDEX `user_city_idx` (`city_id` ASC),
          CONSTRAINT `user_city`
          FOREIGN KEY (`city_id`)
          REFERENCES `taskforce`.`cities` (`id`)
          ON DELETE NO ACTION
          ON UPDATE NO ACTION)
          ENGINE = InnoDB;
        
        CREATE TABLE IF NOT EXISTS `categories` (
          `id` INT NOT NULL AUTO_INCREMENT,
          `add_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `name` VARCHAR(255) NOT NULL,
          `icon` VARCHAR(255) NOT NULL,
          PRIMARY KEY (`id`),
          UNIQUE INDEX `id_UNIQUE` (`id` ASC))
          ENGINE = InnoDB;
        
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
          ENGINE = InnoDB;";
}
