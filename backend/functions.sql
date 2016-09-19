ROP procedure IF EXISTS `ammonixc_musicmanager`.`getArtists`;

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

USE `ammonixc_musicmanager`;
DROP procedure IF EXISTS `ammonixc_musicmanager`.`getArtistLists`;

DELIMITER $$
USE `ammonixc_musicmanager`$$
CREATE  PROCEDURE getArtistLists()
BEGIN
 SELECT artistlist.artistlist_PK, artistlist.name, artist_has_artistlist.artist_PK
  FROM artistlist INNER JOIN artist_has_artistlist ON artistlist.artistlist_PK = artist_has_artistlist.artistlist_PK;
  END$$

DELIMITER ;