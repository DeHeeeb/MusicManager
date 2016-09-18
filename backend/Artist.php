<?php

/**
 * Created by PhpStorm.
 * User: Ammonix
 * Date: 14.09.2016
 * Time: 22:01
 */
include_once("db/Gorm/Gorm.Base/DBObject.php");

class Artist extends DBObject
{
    private $_name;
    private $_genres;

    /**
     * Artist constructor.
     * @param int $_pk
     * @param string $_name
     * @param array $_genres
     */
    public function __construct(int $_pk, string $_name, array $_genres)
    {
        parent::__construct($_pk);
        $this->_name = $_name;
        $this->_genres = $_genres;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->_name = $name;
    }

    /**
     * @return array
     */
    public function getGenres(): array
    {
        return $this->_genres;
    }

    /**
     * @param array $genres
     */
    public function setGenres(array $genres)
    {
        $this->_genres = $genres;
    }

    public function addGenre(Genre $genre)
    {
        $this->_genres[] = $genre;
    }

}