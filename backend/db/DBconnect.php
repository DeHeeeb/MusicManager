<?php

/**
 * Created by PhpStorm.
 * User: Ammonix
 * Date: 14.09.2016
 * Time: 18:45
 */
class DBconnect
{
    private $_connection;
    private static $_instance; //The single instance

    private $_host;
    private $_username;
    private $_password;
    private $_database;


    /*
    Get an instance of the Database
    @return Instance
    */
    public static function getInstance()
    {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // Constructor
    private function __construct()
    {
        $dbValues = EnvironmentHandler::getDBvalues();
        $this->_host = $dbValues["host"];
        $this->_username = $dbValues["username"];
        $this->_password = $dbValues["password"];
        $this->_database = $dbValues["database"];


        $this->_connection = new mysqli($this->_host, $this->_username,
            $this->_password, $this->_database);

        // Error handling
        if (mysqli_connect_error()) {
            ResponseHandler::addErrorMessage("Failed to conencto to MySQL: " . $this->_connection->error);
        }
    }

    // Magic method clone is empty to prevent duplication of connection
    private function __clone()
    {
    }

    // Get mysqli connection
    public function getConnection()
    {
        return $this->_connection;
    }
}