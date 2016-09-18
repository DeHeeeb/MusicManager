<?php

/**
 * Created by PhpStorm.
 * User: Kifkof
 * Date: 15.09.2016
 * Time: 22:26
 */
abstract class DBObject
{
    private $_state = State::None;

    private $_pk;

    protected function __construct(int $pk){
        $this->_pk = $pk;
    }

    public function SetState(State $state){
        $this->_state = $state;
    }

    public function GetState(): State{
        return $this->_state;
    }

    /**
     * @return int
     */
    public function getPk(): int
    {
        return $this->_pk;
    }

    /**
     * @param int $pk
     */
    public function setPk(int $pk)
    {
        $this->_pk = $pk;
    }

}

class State {
    const None = 0;
    const Created = 1;
    const Modified = 2;
    const Deleted = 3;
}