Delimiter //

CREATE  PROCEDURE getArtists()
BEGIN
  SELECT artist_PK, name
  FROM artist;
END //


CREATE  PROCEDURE getSubgenreByKey(_subgenre_PK INT (11))
BEGIN
  SELECT  name, genre_FK
  FROM subgenre
  WHERE subgenre_PK = _subgenre_PK;
END //


CREATE  PROCEDURE getGenreByKey(_genre_PK INT (11))
BEGIN
  SELECT  name
  FROM genre
  WHERE genre_PK = _genre_PK;
END //


CREATE  PROCEDURE getArtistHasSubgenreKeys(_artist_PK INT (11))
  BEGIN
    SELECT  subgenre_PK
    FROM artist_has_subgenre
    WHERE artist_PK = _artist_PK;
  END //

CREATE  PROCEDURE getUserByUsername(_username VARCHAR(250))
  BEGIN
    SELECT email, password, create_time, role
    FROM  user
    WHERE username = _username;
  END //



Delimiter ;