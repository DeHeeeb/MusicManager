<?php

/**
 * Created by PhpStorm.
 * User: Ammonix
 * Date: 19.09.2016
 * Time: 11:28
 */
class ArtistList extends DBObject
{
    private $_name;
    private $_artists;

    /**
     * ArtistList constructor.
     * @param $_name
     * @param $_artists
     */
    public function __construct(int $pk, string $_name, array $_artists)
    {
        parent::__construct($pk);
        $this->_name = $_name;
        $this->_artists = $_artists;
    }

    /**
     * @return mixed
     */
    public function getName() : string
    {
        return $this->_name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return mixed
     */
    public function getArtists() : array
    {
        return $this->_artists;
    }

    /**
     * @param mixed $artists
     */
    public function setArtists($artists)
    {
        $this->_artists = $artists;
    }
}