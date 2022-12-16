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
  `k_id` INT NOT NULL,
  `ime` VARCHAR(255) NULL,
  PRIMARY KEY (`k_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`drzava`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`drzava` (
  `d_id` INT NOT NULL,
  `ime` VARCHAR(255) NULL,
  `kontinent_k_id` INT NOT NULL,
  PRIMARY KEY (`d_id`),
  INDEX `fk_drzava_kontinent_idx` (`kontinent_k_id` ASC) VISIBLE,
  CONSTRAINT `fk_drzava_kontinent`
    FOREIGN KEY (`kontinent_k_id`)
    REFERENCES `mydb`.`kontinent` (`k_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`grad`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`grad` (
  `g_id` INT NOT NULL,
  `ime` VARCHAR(45) NULL,
  `drzava_d_id` INT NOT NULL,
  PRIMARY KEY (`g_id`),
  INDEX `fk_grad_drzava1_idx` (`drzava_d_id` ASC) VISIBLE,
  CONSTRAINT `fk_grad_drzava1`
    FOREIGN KEY (`drzava_d_id`)
    REFERENCES `mydb`.`drzava` (`d_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`prevoz`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`prevoz` (
  `p_id` INT NOT NULL,
  `tip` VARCHAR(45) NULL,
  `ime_komp` VARCHAR(45) NULL,
  PRIMARY KEY (`p_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`korisnik`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`korisnik` (
  `korisnik_id` INT NOT NULL,
  `email` VARCHAR(45) NULL,
  `password` VARCHAR(45) NULL,
  PRIMARY KEY (`korisnik_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`kupac`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`kupac` (
  `ime` VARCHAR(45) NULL,
  `prezime` VARCHAR(45) NULL,
  `adresa` VARCHAR(45) NULL,
  `br_kartice` VARCHAR(45) NULL,
  `korisnik_korisnik_id` INT NOT NULL,
  PRIMARY KEY (`korisnik_korisnik_id`),
  CONSTRAINT `fk_kupac_korisnik1`
    FOREIGN KEY (`korisnik_korisnik_id`)
    REFERENCES `mydb`.`korisnik` (`korisnik_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`zaposleni`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`zaposleni` (
  `ime` VARCHAR(45) NULL,
  `prezime` VARCHAR(45) NULL,
  `korisnik_korisnik_id` INT NOT NULL,
  PRIMARY KEY (`korisnik_korisnik_id`),
  CONSTRAINT `fk_zaposleni_korisnik1`
    FOREIGN KEY (`korisnik_korisnik_id`)
    REFERENCES `mydb`.`korisnik` (`korisnik_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`admin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`admin` (
  `korisnik_korisnik_id` INT NOT NULL,
  PRIMARY KEY (`korisnik_korisnik_id`),
  CONSTRAINT `fk_admin_korisnik1`
    FOREIGN KEY (`korisnik_korisnik_id`)
    REFERENCES `mydb`.`korisnik` (`korisnik_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`admin_uticao_zaposleni`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`admin_uticao_zaposleni` (
  `admin_korisnik_korisnik_id` INT NOT NULL,
  `zaposleni_korisnik_korisnik_id` INT NOT NULL,
  `kako` VARCHAR(255) NULL,
  PRIMARY KEY (`admin_korisnik_korisnik_id`, `zaposleni_korisnik_korisnik_id`),
  INDEX `fk_admin_has_zaposleni_zaposleni1_idx` (`zaposleni_korisnik_korisnik_id` ASC) VISIBLE,
  INDEX `fk_admin_has_zaposleni_admin1_idx` (`admin_korisnik_korisnik_id` ASC) VISIBLE,
  CONSTRAINT `fk_admin_has_zaposleni_admin1`
    FOREIGN KEY (`admin_korisnik_korisnik_id`)
    REFERENCES `mydb`.`admin` (`korisnik_korisnik_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_admin_has_zaposleni_zaposleni1`
    FOREIGN KEY (`zaposleni_korisnik_korisnik_id`)
    REFERENCES `mydb`.`zaposleni` (`korisnik_korisnik_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`admin_uticao_kupac`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`admin_uticao_kupac` (
  `admin_korisnik_korisnik_id` INT NOT NULL,
  `kupac_korisnik_korisnik_id` INT NOT NULL,
  `kako` VARCHAR(255) NULL,
  PRIMARY KEY (`admin_korisnik_korisnik_id`, `kupac_korisnik_korisnik_id`),
  INDEX `fk_admin_has_kupac_kupac1_idx` (`kupac_korisnik_korisnik_id` ASC) VISIBLE,
  INDEX `fk_admin_has_kupac_admin1_idx` (`admin_korisnik_korisnik_id` ASC) VISIBLE,
  CONSTRAINT `fk_admin_has_kupac_admin1`
    FOREIGN KEY (`admin_korisnik_korisnik_id`)
    REFERENCES `mydb`.`admin` (`korisnik_korisnik_id`)
    ON DELETE CASCADE 
    ON UPDATE CASCADE,
  CONSTRAINT `fk_admin_has_kupac_kupac1`
    FOREIGN KEY (`kupac_korisnik_korisnik_id`)
    REFERENCES `mydb`.`kupac` (`korisnik_korisnik_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`smestaj`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`smestaj` (
  `smestaj_id` INT NOT NULL,
  `tip` VARCHAR(45) NULL,
  `naziv` VARCHAR(45) NULL,
  `kapacitet` INT NULL,
  `soba` VARCHAR(45) NULL,
  `ulica` VARCHAR(45) NULL,
  `br.ulice` INT NULL,
  `postbroj` VARCHAR(45) NULL,
  PRIMARY KEY (`smestaj_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`dodaci`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`dodaci` (
  `dodaci_id` INT NOT NULL,
  `naziv` VARCHAR(255) NULL,
  PRIMARY KEY (`dodaci_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Rezervacije`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Rezervacije` (
  `idRezervacije` INT NOT NULL,
  `Ime` VARCHAR(45) NULL,
  `Prezime` VARCHAR(45) NULL,
  `brojclanova` VARCHAR(45) NULL,
  `kupac_korisnik_korisnik_id` INT NOT NULL,
  `grad_g_id` INT NOT NULL,
  `smestaj_smestaj_id` INT NOT NULL,
  `prevoz_p_id` INT NOT NULL,
  PRIMARY KEY (`idRezervacije`),
  INDEX `fk_Rezervacije_kupac1_idx` (`kupac_korisnik_korisnik_id` ASC) VISIBLE,
  INDEX `fk_Rezervacije_grad1_idx` (`grad_g_id` ASC) VISIBLE,
  INDEX `fk_Rezervacije_smestaj1_idx` (`smestaj_smestaj_id` ASC) VISIBLE,
  INDEX `fk_Rezervacije_prevoz1_idx` (`prevoz_p_id` ASC) VISIBLE,
  CONSTRAINT `fk_Rezervacije_kupac1`
    FOREIGN KEY (`kupac_korisnik_korisnik_id`)
    REFERENCES `mydb`.`kupac` (`korisnik_korisnik_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Rezervacije_grad1`
    FOREIGN KEY (`grad_g_id`)
    REFERENCES `mydb`.`grad` (`g_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Rezervacije_smestaj1`
    FOREIGN KEY (`smestaj_smestaj_id`)
    REFERENCES `mydb`.`smestaj` (`smestaj_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Rezervacije_prevoz1`
    FOREIGN KEY (`prevoz_p_id`)
    REFERENCES `mydb`.`prevoz` (`p_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`soba_sadrzi_dodatak`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`soba_sadrzi_dodatak` (
  `dodaci_dodaci_id` INT NOT NULL,
  `smestaj_smestaj_id` INT NOT NULL,
  PRIMARY KEY (`dodaci_dodaci_id`, `smestaj_smestaj_id`),
  INDEX `fk_soba_sadrzi_dodatak_smestaj1_idx` (`smestaj_smestaj_id` ASC) VISIBLE,
  CONSTRAINT `fk_soba_sadrzi_dodatak_dodaci1`
    FOREIGN KEY (`dodaci_dodaci_id`)
    REFERENCES `mydb`.`dodaci` (`dodaci_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_soba_sadrzi_dodatak_smestaj1`
    FOREIGN KEY (`smestaj_smestaj_id`)
    REFERENCES `mydb`.`smestaj` (`smestaj_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
