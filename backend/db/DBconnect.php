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
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if ($actual_link == "http://musicmanager.localhost/backend/Main.php") {
            $this->_host = "localhost";
            $this->_username = "root";
            $this->_password = "";
            $this->_database = "ammonixc_MusicManager";
        } else {
            $this->_host = "ammonixc.mysql.db.internal";
            $this->_username = "ammonixc_techus";
            $this->_password = "fVMrEK5C";
            $this->_database = "ammonixc_MusicManager";
        }


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