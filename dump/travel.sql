-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema travel
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema travel
-- -----------------------------------------------------
DROP DATABASE IF EXISTS `travel`;
CREATE SCHEMA IF NOT EXISTS `travel` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `travel` ;

-- -----------------------------------------------------
-- Table `travel`.`korisnik`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`korisnik` (
  `korisnik_id` INT NOT NULL AUTO_INCREMENT,
  `tip` INT NULL DEFAULT NULL,
  PRIMARY KEY (`korisnik_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`admin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`admin` (
  `admin_id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(80) NOT NULL,
  `password` VARCHAR(80) NOT NULL,
  `ime` VARCHAR(80) NULL DEFAULT NULL,
  `prezime` VARCHAR(80) NULL DEFAULT NULL,
  `korisnik_id` INT NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE,
  INDEX `fk_admin_korisnik1_idx` (`korisnik_id` ASC) VISIBLE,
  CONSTRAINT `fk_admin_korisnik1`
    FOREIGN KEY (`korisnik_id`)
    REFERENCES `travel`.`korisnik` (`korisnik_id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`zaposleni`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`zaposleni` (
  `zaposleni_id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(80) NOT NULL,
  `password` VARCHAR(80) NOT NULL,
  `ime` VARCHAR(80) NULL DEFAULT NULL,
  `prezime` VARCHAR(80) NULL DEFAULT NULL,
  `korisnik_id` INT NOT NULL,
  PRIMARY KEY (`zaposleni_id`),
  UNIQUE INDEX `username_UNIQUE` (`email` ASC) VISIBLE,
  INDEX `fk_zaposleni_korisnik1_idx` (`korisnik_id` ASC) VISIBLE,
  CONSTRAINT `fk_zaposleni_korisnik1_idx`
    FOREIGN KEY (`korisnik_id`)
    REFERENCES `travel`.`korisnik` (`korisnik_id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`admin_uticao_zaposleni`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`admin_uticao_zaposleni` (
  `admin_id` INT NOT NULL,
  `zaposleni_id` INT NOT NULL,
  `kako` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`admin_id`, `zaposleni_id`),
  INDEX `fk_admin_has_zaposleni_zaposleni1_idx` (`zaposleni_id` ASC) VISIBLE,
  INDEX `fk_admin_has_zaposleni_admin1_idx` (`admin_id` ASC) VISIBLE,
  CONSTRAINT `fk_admin_has_zaposleni_admin1`
    FOREIGN KEY (`admin_id`)
    REFERENCES `travel`.`admin` (`admin_id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_admin_has_zaposleni_zaposleni1`
    FOREIGN KEY (`zaposleni_id`)
    REFERENCES `travel`.`zaposleni` (`zaposleni_id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`aktivnosti`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`aktivnosti` (
  `akt_id` INT NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(80) NULL DEFAULT NULL,
  PRIMARY KEY (`akt_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`kontinent`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`kontinent` (
  `k_id` INT NOT NULL AUTO_INCREMENT,
  `ime` VARCHAR(80) NULL DEFAULT NULL,
  PRIMARY KEY (`k_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`drzava`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`drzava` (
  `d_id` INT NOT NULL AUTO_INCREMENT,
  `ime` VARCHAR(80) NULL DEFAULT NULL,
  `k_id` INT NOT NULL,
  PRIMARY KEY (`d_id`),
  INDEX `fk_drzava_kontinent_idx` (`k_id` ASC) VISIBLE,
  CONSTRAINT `fk_drzava_kontinent`
    FOREIGN KEY (`k_id`)
    REFERENCES `travel`.`kontinent` (`k_id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`grad`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`grad` (
  `g_id` INT NOT NULL AUTO_INCREMENT,
  `ime` VARCHAR(80) NULL DEFAULT NULL,
  `d_id` INT NOT NULL,
  PRIMARY KEY (`g_id`),
  INDEX `fk_grad_drzava1_idx` (`d_id` ASC) VISIBLE,
  CONSTRAINT `fk_grad_drzava1`
    FOREIGN KEY (`d_id`)
    REFERENCES `travel`.`drzava` (`d_id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`smestaj`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`smestaj` (
  `smestaj_id` INT NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(80) NULL DEFAULT NULL,
  `adresa` VARCHAR(80) NULL DEFAULT NULL,
  `kapacitet` VARCHAR(45) NULL DEFAULT NULL,
  `br_zvezdica` VARCHAR(80) NULL DEFAULT NULL,
  `g_id` INT NOT NULL,
  PRIMARY KEY (`smestaj_id`),
  INDEX `fk_smestaj_grad1_idx` (`g_id` ASC) VISIBLE,
  CONSTRAINT `fk_smestaj_grad1`
    FOREIGN KEY (`g_id`)
    REFERENCES `travel`.`grad` (`g_id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`akt_u_gradu`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`akt_u_gradu` (
  `g_id` INT NOT NULL,
  `akt_id` INT NOT NULL,
  `smestaj_id` INT NULL DEFAULT NULL,
  INDEX `fk_aran_u_gradu_grad1_idx` (`g_id` ASC) VISIBLE,
  INDEX `fk_akt_u_gradu_aktivnosti1_idx` (`akt_id` ASC) VISIBLE,
  INDEX `fk_akt_u_gradu_smestaj1_idx` (`smestaj_id` ASC) VISIBLE,
  CONSTRAINT `fk_akt_u_gradu_aktivnosti1`
    FOREIGN KEY (`akt_id`)
    REFERENCES `travel`.`aktivnosti` (`akt_id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_akt_u_gradu_smestaj1`
    FOREIGN KEY (`smestaj_id`)
    REFERENCES `travel`.`smestaj` (`smestaj_id`),
  CONSTRAINT `fk_aran_u_gradu_grad1`
    FOREIGN KEY (`g_id`)
    REFERENCES `travel`.`grad` (`g_id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`prevoz`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`prevoz` (
  `p_id` INT NOT NULL AUTO_INCREMENT,
  `tip` VARCHAR(80) NULL DEFAULT NULL,
  `ime_komp` VARCHAR(80) NULL DEFAULT NULL,
  `cena` VARCHAR(80) NULL DEFAULT NULL,
  PRIMARY KEY (`p_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`aranzmani`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`aranzmani` (
  `aran_id` INT NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(255) NULL DEFAULT NULL,
  `krece` DATETIME NULL DEFAULT NULL,
  `vraca` DATETIME NULL DEFAULT NULL,
  `nap` VARCHAR(255) NULL DEFAULT NULL,
  `smestaj_id` INT NOT NULL,
  `p_id` INT NOT NULL,
  PRIMARY KEY (`aran_id`),
  INDEX `fk_aranzmani_smestaj1_idx` (`smestaj_id` ASC) VISIBLE,
  INDEX `fk_aranzmani_prevoz1_idx` (`p_id` ASC) VISIBLE,
  CONSTRAINT `fk_aranzmani_prevoz1`
    FOREIGN KEY (`p_id`)
    REFERENCES `travel`.`prevoz` (`p_id`),
  CONSTRAINT `fk_aranzmani_smestaj1`
    FOREIGN KEY (`smestaj_id`)
    REFERENCES `travel`.`smestaj` (`smestaj_id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`dodaci`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`dodaci` (
  `dodaci_id` INT NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(80) NULL DEFAULT NULL,
  `cena` VARCHAR(80) NULL DEFAULT NULL,
  PRIMARY KEY (`dodaci_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`grad_ima_sliku`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`grad_ima_sliku` (
  `grad_id` INT NOT NULL,
  `slika` VARCHAR(70) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_0900_ai_ci' NULL DEFAULT NULL,
  INDEX `grad_id` (`grad_id` ASC) VISIBLE)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`ima_aktivnost`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`ima_aktivnost` (
  `aran_id` INT NOT NULL,
  `akt_id` INT NOT NULL,
  INDEX `fk_ima_aktivnost_aranzmani1_idx` (`aran_id` ASC) VISIBLE,
  INDEX `fk_ima_aktivnost_aktivnosti1_idx` (`akt_id` ASC) VISIBLE,
  CONSTRAINT `fk_ima_aktivnost_aktivnosti1`
    FOREIGN KEY (`akt_id`)
    REFERENCES `travel`.`aktivnosti` (`akt_id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_ima_aktivnost_aranzmani1`
    FOREIGN KEY (`aran_id`)
    REFERENCES `travel`.`aranzmani` (`aran_id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`tagovi`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`tagovi` (
  `tag_id` INT NOT NULL AUTO_INCREMENT,
  `naziv` VARCHAR(80) NULL DEFAULT NULL,
  PRIMARY KEY (`tag_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`ima_tag`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`ima_tag` (
  `tag_id` INT NOT NULL,
  `smestaj_id` INT NOT NULL,
  INDEX `fk_ima_tag_tagovi1_idx` (`tag_id` ASC) VISIBLE,
  INDEX `fk_ima_tag_smestaj1_idx` (`smestaj_id` ASC) VISIBLE,
  CONSTRAINT `fk_ima_tag_smestaj1`
    FOREIGN KEY (`smestaj_id`)
    REFERENCES `travel`.`smestaj` (`smestaj_id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_ima_tag_tagovi1`
    FOREIGN KEY (`tag_id`)
    REFERENCES `travel`.`tagovi` (`tag_id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`rezervacije`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`rezervacije` (
  `rez_id` INT NOT NULL AUTO_INCREMENT,
  `ime` VARCHAR(80) NULL DEFAULT NULL,
  `prezime` VARCHAR(80) NULL DEFAULT NULL,
  `br_kartice` VARCHAR(80) NULL DEFAULT NULL,
  `email` VARCHAR(80) NULL DEFAULT NULL,
  `broj_odr` VARCHAR(80) NULL DEFAULT NULL,
  `broj_dece` VARCHAR(80) NULL DEFAULT NULL,
  `cena` VARCHAR(80) NULL DEFAULT NULL,
  `kom` VARCHAR(80) NULL DEFAULT NULL,
  `kontakt` VARCHAR(80) NULL DEFAULT NULL,
  `broj_soba` INT NULL,
  `aran_id` INT NOT NULL,
  `korisnik_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`rez_id`),
  INDEX `fk_rezervacije_aranzmani1_idx` (`aran_id` ASC) VISIBLE,
  INDEX `fk_rezervacije_korisnik1_idx` (`korisnik_id` ASC) VISIBLE,
  CONSTRAINT `fk_rezervacije_aranzmani1`
    FOREIGN KEY (`aran_id`)
    REFERENCES `travel`.`aranzmani` (`aran_id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_rezervacije_korisnik1`
    FOREIGN KEY (`korisnik_id`)
    REFERENCES `travel`.`korisnik` (`korisnik_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`soba`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`soba` (
  `soba_id` INT NOT NULL AUTO_INCREMENT,
  `tip` INT NULL DEFAULT NULL,
  `smestaj_id` INT NOT NULL,
  `rez_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`soba_id`),
  INDEX `fk_soba_smestaj1_idx` (`smestaj_id` ASC) VISIBLE,
  INDEX `fk_soba_rezervacije1_idx` (`rez_id` ASC) VISIBLE,
  CONSTRAINT `fk_soba_rezervacije1`
    FOREIGN KEY (`rez_id`)
    REFERENCES `travel`.`rezervacije` (`rez_id`),
  CONSTRAINT `fk_soba_smestaj1`
    FOREIGN KEY (`smestaj_id`)
    REFERENCES `travel`.`smestaj` (`smestaj_id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`soba_ima_sliku`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`soba_ima_sliku` (
  `soba_id` INT NOT NULL,
  `slika` VARCHAR(70) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_0900_ai_ci' NULL DEFAULT NULL,
  INDEX `soba_id` (`soba_id` ASC) VISIBLE)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`soba_sadrzi_dodatak`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`soba_sadrzi_dodatak` (
  `dodaci_id` INT NOT NULL,
  `soba_id` INT NOT NULL,
  `br_dod` INT NULL DEFAULT NULL,
  PRIMARY KEY (`dodaci_id`, `soba_id`),
  INDEX `fk_soba_sadrzi_dodatak_soba1_idx` (`soba_id` ASC) VISIBLE,
  CONSTRAINT `fk_soba_sadrzi_dodatak_dodaci1`
    FOREIGN KEY (`dodaci_id`)
    REFERENCES `travel`.`dodaci` (`dodaci_id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_soba_sadrzi_dodatak_soba1`
    FOREIGN KEY (`soba_id`)
    REFERENCES `travel`.`soba` (`soba_id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `travel`.`sobatip_hash`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `travel`.`sobatip_hash` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tip` VARCHAR(80) NULL DEFAULT NULL,
  `br_kreveta` VARCHAR(80) NULL DEFAULT NULL,
  `gen_cena` VARCHAR(80) NULL DEFAULT NULL,
  `opis` VARCHAR(500) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

INSERT INTO korisnik (korisnik_id, tip) VALUES (NULL, '2');
INSERT INTO admin (admin_id, email, password, ime, prezime, korisnik_id) VALUES (NULL, 'admin@1.1', '$2y$10$MUOykAR0LzKlpGeNGelH2ec74yMW1gULt8Eeqa9XQsu1t4jykE5Cy', NULL, NULL, '1');