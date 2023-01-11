-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`kontinent`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`kontinent` (
  `k_id` INT NOT NULL AUTO_INCREMENT,
  `ime` VARCHAR(80) NULL,
  PRIMARY KEY (`k_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`drzava`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`drzava` (
  `d_id` INT NOT NULL AUTO_INCREMENT,
  `ime` VARCHAR(80) NULL,
  `k_id` INT NOT NULL,
  PRIMARY KEY (`d_id`),
  INDEX `fk_drzava_kontinent_idx` (`k_id` ASC) VISIBLE,
  CONSTRAINT `fk_drzava_kontinent`
    FOREIGN KEY (`k_id`)
    REFERENCES `mydb`.`kontinent` (`k_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`grad`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`grad` (
  `g_id` INT NOT NULL AUTO_INCREMENT,
  `ime` VARCHAR(80) NULL,
  `d_id` INT NOT NULL,
  PRIMARY KEY (`g_id`),
  INDEX `fk_grad_drzava1_idx` (`d_id` ASC) VISIBLE,
  CONSTRAINT `fk_grad_drzava1`
    FOREIGN KEY (`d_id`)
    REFERENCES `mydb`.`drzava` (`d_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`prevoz`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`prevoz` (
  `p_id` INT NOT NULL AUTO_INCREMENT,
  `tip` VARCHAR(80) NULL,
  `ime_komp` VARCHAR(80) NULL,
  PRIMARY KEY (`p_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`kupac`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`kupac` (
  `kupac_id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(80) NULL,
  `password` VARCHAR(80) NULL,
  `ime` VARCHAR(80) NULL,
  `prezime` VARCHAR(80) NULL,
  `adresa` VARCHAR(80) NULL,
  `br_kartice` VARCHAR(80) NULL,
  PRIMARY KEY (`kupac_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`zaposleni`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`zaposleni` (
  `zaposleni_id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(80) NULL,
  `password` VARCHAR(80) NULL,
  `ime` VARCHAR(80) NULL,
  `prezime` VARCHAR(80) NULL,
  PRIMARY KEY (`zaposleni_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`admin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`admin` (
  `admin_id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(80) NULL,
  `password` VARCHAR(80) NULL,
  `ime` VARCHAR(80) NULL,
  `prezime` VARCHAR(80) NULL,
  PRIMARY KEY (`admin_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`admin_uticao_zaposleni`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`admin_uticao_zaposleni` (
  `admin_id` INT NOT NULL,
  `zaposleni_id` INT NOT NULL,
  `kako` VARCHAR(255) NULL,
  PRIMARY KEY (`admin_id`, `zaposleni_id`),
  INDEX `fk_admin_has_zaposleni_zaposleni1_idx` (`zaposleni_id` ASC) VISIBLE,
  INDEX `fk_admin_has_zaposleni_admin1_idx` (`admin_id` ASC) VISIBLE,
  CONSTRAINT `fk_admin_has_zaposleni_admin1`
    FOREIGN KEY (`admin_id`)
    REFERENCES `mydb`.`admin` (`admin_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_admin_has_zaposleni_zaposleni1`
    FOREIGN KEY (`zaposleni_id`)
    REFERENCES `mydb`.`zaposleni` (`zaposleni_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`admin_uticao_kupac`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`admin_uticao_kupac` (
  `admin_id` INT NOT NULL,
  `kupac_id` INT NOT NULL,
  `kako` VARCHAR(255) NULL,
  PRIMARY KEY (`admin_id`, `kupac_id`),
  INDEX `fk_admin_has_kupac_kupac1_idx` (`kupac_id` ASC) VISIBLE,
  INDEX `fk_admin_has_kupac_admin1_idx` (`admin_id` ASC) VISIBLE,
  CONSTRAINT `fk_admin_has_kupac_admin1`
    FOREIGN KEY (`admin_id`)
    REFERENCES `mydb`.`admin` (`admin_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_admin_has_kupac_kupac1`
    FOREIGN KEY (`kupac_id`)
    REFERENCES `mydb`.`kupac` (`kupac_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`smestaj`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`smestaj` (
  `smestaj_id` INT NOT NULL AUTO_INCREMENT,
  `tip` VARCHAR(80) NULL,
  `naziv` VARCHAR(80) NULL,
  `kapacitet` INT NULL,
  `soba` VARCHAR(80) NULL,
  `ulica` VARCHAR(80) NULL,
  `br.ulice` INT NULL,
  `postbroj` VARCHAR(80) NULL,
  PRIMARY KEY (`smestaj_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`dodaci`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`dodaci` (
  `dodaci_id` INT NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(255) NULL,
  PRIMARY KEY (`dodaci_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Rezervacije`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Rezervacije` (
  `idRezervacije` INT NOT NULL AUTO_INCREMENT,
  `ime` VARCHAR(80) NULL,
  `prezime` VARCHAR(80) NULL,
  `brojclanova` VARCHAR(80) NULL,
  `korisnik_id` INT NOT NULL,
  `g_id` INT NOT NULL,
  `smestaj_id` INT NOT NULL,
  `p_id` INT NOT NULL,
  PRIMARY KEY (`idRezervacije`),
  INDEX `fk_Rezervacije_kupac1_idx` (`korisnik_id` ASC) VISIBLE,
  INDEX `fk_Rezervacije_grad1_idx` (`g_id` ASC) VISIBLE,
  INDEX `fk_Rezervacije_smestaj1_idx` (`smestaj_id` ASC) VISIBLE,
  INDEX `fk_Rezervacije_prevoz1_idx` (`p_id` ASC) VISIBLE,
  CONSTRAINT `fk_Rezervacije_kupac1`
    FOREIGN KEY (`korisnik_id`)
    REFERENCES `mydb`.`kupac` (`kupac_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Rezervacije_grad1`
    FOREIGN KEY (`g_id`)
    REFERENCES `mydb`.`grad` (`g_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Rezervacije_smestaj1`
    FOREIGN KEY (`smestaj_id`)
    REFERENCES `mydb`.`smestaj` (`smestaj_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Rezervacije_prevoz1`
    FOREIGN KEY (`p_id`)
    REFERENCES `mydb`.`prevoz` (`p_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`soba_sadrzi_dodatak`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`soba_sadrzi_dodatak` (
  `dodaci_id` INT NOT NULL,
  `smestaj_id` INT NOT NULL,
  PRIMARY KEY (`dodaci_id`, `smestaj_id`),
  INDEX `fk_soba_sadrzi_dodatak_smestaj1_idx` (`smestaj_id` ASC) VISIBLE,
  CONSTRAINT `fk_soba_sadrzi_dodatak_dodaci1`
    FOREIGN KEY (`dodaci_id`)
    REFERENCES `mydb`.`dodaci` (`dodaci_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_soba_sadrzi_dodatak_smestaj1`
    FOREIGN KEY (`smestaj_id`)
    REFERENCES `mydb`.`smestaj` (`smestaj_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
