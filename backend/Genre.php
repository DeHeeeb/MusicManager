<?php

/**
 * Created by PhpStorm.
 * User: Ammonix
 * Date: 14.09.2016
 * Time: 23:01
 */
class Genre
{
    private $_name;
    private $_subgenres;

    /**
     * Genre constructor.
     * @param string $_name
     * @param array $_subgenres
     */
    public function __construct(string $_name, array $_subgenres)
    {
        $this->_name = $_name;
        $this->_subgenres = $_subgenres;
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
    public function getSubgenres(): array
    {
        return $this->_subgenres;
    }

    /**
     * @param array $subgenres
     */
    public function setSubgenres(array $subgenres)
    {
        $this->_subgenres = $subgenres;
    }

    public function addSubgenre(Subgenre $subgenre)
    {
        $this->_subgenres [] = $subgenre;
    }

}