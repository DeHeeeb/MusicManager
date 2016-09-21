-- MySQL Workbench Synchronization
-- Generated: 2016-09-21 18:19
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: Ammonix

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


USE `ammonixc_musicmanager`;
DROP procedure IF EXISTS `ammonixc_musicmanager`.`getArtistLists`;

DELIMITER $$
USE `ammonixc_musicmanager`$$
CREATE  PROCEDURE getArtistLists()
BEGIN
SELECT artistlist.artistlist_PK, artistlist.name, artist_has_artistlist.artist_PK
  FROM artistlist INNER JOIN artist_has_artistlist ON artistlist.artistlist_PK = artist_has_artistlist.artistlist_PK ORDER BY artistlist.artistlist_PK ASC;
  END$$

DELIMITER ;

USE `ammonixc_musicmanager`;
DROP procedure IF EXISTS `ammonixc_musicmanager`.`getArtists`;

DELIMITER $$
USE `ammonixc_musicmanager`$$
CREATE  PROCEDURE getArtists()
BEGIN
  SELECT artist_PK, name, year, picturepath
  FROM artist;
END$$

DELIMITER ;

USE `ammonixc_musicmanager`;
DROP procedure IF EXISTS `ammonixc_musicmanager`.`getSubgenreByKey`;

DELIMITER $$
USE `ammonixc_musicmanager`$$
CREATE  PROCEDURE getSubgenreByKey(_subgenre_PK INT (11))
BEGIN
  SELECT  name, genre_FK
  FROM subgenre
  WHERE subgenre_PK = _subgenre_PK;
END$$

DELIMITER ;

USE `ammonixc_musicmanager`;
DROP procedure IF EXISTS `ammonixc_musicmanager`.`getGenreByKey`;

DELIMITER $$
USE `ammonixc_musicmanager`$$
CREATE  PROCEDURE getGenreByKey(_genre_PK INT (11))
BEGIN
  SELECT  name
  FROM genre
  WHERE genre_PK = _genre_PK;
END$$

DELIMITER ;

USE `ammonixc_musicmanager`;
DROP procedure IF EXISTS `ammonixc_musicmanager`.`getUserByUsername`;

DELIMITER $$
USE `ammonixc_musicmanager`$$
CREATE  PROCEDURE getUserByUsername(_username VARCHAR(250))
  BEGIN
    SELECT email, password, create_time, role
    FROM  user
    WHERE username = _username;
  END$$

DELIMITER ;

USE `ammonixc_musicmanager`;
DROP procedure IF EXISTS `ammonixc_musicmanager`.`getArtistHasSubgenreKeys`;

DELIMITER $$
USE `ammonixc_musicmanager`$$
CREATE  PROCEDURE getArtistHasSubgenreKeys(_artist_PK INT (11))
  BEGIN
    SELECT  subgenre_PK
    FROM artist_has_subgenre
    WHERE artist_PK = _artist_PK;
  END$$

DELIMITER ;

DELIMITER $$
USE `ammonixc_musicmanager`$$
CREATE PROCEDURE registerUser (_username VARCHAR(16),_email VARCHAR(250),_password VARCHAR(250))
BEGIN
INSERT INTO user (username, email, password, role)
VALUES (_username,_email,_password,1);
END$$

DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
