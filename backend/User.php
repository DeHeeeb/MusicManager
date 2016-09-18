<?php

/**
 * Created by PhpStorm.
 * User: Ammonix
 * Date: 16.09.2016
 * Time: 16:22
 */
class User
{
    private $_username;
    private $_email;
    private $_createtime;
    private $_role;

    /**
     * User constructor.
     * @param $_username
     * @param $_email
     * @param $_createtime
     * @param $_role
     */
    public function __construct(string $_username,string $_email, string $_createtime, int $_role)
    {
        $this->_username = $_username;
        $this->_email = $_email;
        $this->_createtime = $_createtime;
        $this->_role = $_role;

    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->_username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->_username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->_email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->_email = $email;
    }

    /**
     * @return string
     */
    public function getCreatetime(): string
    {
        return $this->_createtime;
    }

    /**
     * @param string $createtime
     */
    public function setCreatetime(string $createtime)
    {
        $this->_createtime = $createtime;
    }

    /**
     * @return int
     */
    public function getRole(): int
    {
        return $this->_role;
    }

    /**
     * @param int $role
     */
    public function setRole(int $role)
    {
        $this->_role = $role;
    }

}