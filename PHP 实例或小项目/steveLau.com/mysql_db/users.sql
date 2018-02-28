-- MySQL Script generated by MySQL Workbench
-- 02/20/18 16:54:41
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering 

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`user` ;

CREATE TABLE IF NOT EXISTS `mydb`.`user` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(20) NOT NULL,
  `password` CHAR(32) NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`user_id`),
  INDEX `username` (`username` ASC),
  INDEX `created_at` (`created_at` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`article`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`article` ;

CREATE TABLE IF NOT EXISTS `mydb`.`article` (
  `article_id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `content` TEXT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`article_id`),
  INDEX `fk_article_user_idx` (`user_id` ASC),
  CONSTRAINT `fk_article_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `mydb`.`user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
