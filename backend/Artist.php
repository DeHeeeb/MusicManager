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
    private $_year;
    private $_picturePath;

    public function __construct(int $_pk, string $_name, string $_year, string $_picturePath, array $_genres)
    {
        parent::__construct($_pk);
        $this->_name = $_name;
        $this->_genres = $_genres;
        $this->_year = $_year;
        $this->_picturePath = $_picturePath;
    }


    public function getName(): string
    {
        return $this->_name;
    }

    public function setName(string $name)
    {
        $this->_name = $name;
    }


    public function getGenres(): array
    {
        return $this->_genres;
    }


    public function setGenres(array $genres)
    {
        $this->_genres = $genres;
    }

    public function addGenre(Genre $genre)
    {
        $this->_genres[] = $genre;
    }


    public function getYear(): string
    {
        return $this->_year;
    }


    public function setYear(string $year)
    {
        $this->_year = $year;
    }


    public function getPicturePath(): string
    {
        return $this->_picturePath;
    }


    public function setPicturePath(string $picturePath)
    {
        $this->_picturePath = $picturePath;
    }

}