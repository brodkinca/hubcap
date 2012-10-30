SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `github_id` INT UNSIGNED NULL ,
  `access_token` CHAR(40) NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `access_key_UNIQUE` (`access_token` ASC) ,
  UNIQUE INDEX `github_id_UNIQUE` (`github_id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `repos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `repos` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT UNSIGNED NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  `branch` VARCHAR(45) NOT NULL DEFAULT 'master' ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_repos_users_idx` (`user_id` ASC) ,
  CONSTRAINT `fk_repos_users`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sessions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `sessions` (
  `session_id` VARCHAR(40) NOT NULL DEFAULT '0' ,
  `ip_address` VARCHAR(45) NOT NULL DEFAULT '0' ,
  `user_agent` VARCHAR(120) NOT NULL ,
  `last_activity` INT(10) UNSIGNED NOT NULL DEFAULT 0 ,
  `user_data` TEXT NOT NULL ,
  PRIMARY KEY (`session_id`) ,
  INDEX `last_activity_idx` (`last_activity` ASC) );



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
