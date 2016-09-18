<?php

/**
 * Created by PhpStorm.
 * User: Ammonix
 * Date: 15.09.2016
 * Time: 21:09
 */
class Subgenre
{
    private $_name;

    /**
     * Subgenre constructor.
     * @param $_name
     */
    public function __construct(string $_name)
    {
        $this->_name = $_name;
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


}