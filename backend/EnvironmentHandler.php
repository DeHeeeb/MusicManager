<?php

/**
 * Created by PhpStorm.
 * User: Ammonix
 * Date: 20.09.2016
 * Time: 10:35
 */
abstract class EnvironmentHandler
{
    private static $_localhostUrl = "musicmanager.localhost";
    private static $_testtUrl = "musicmanager.dev.ammonix.ch";
    private static $_prodtUrl = "musicmanager.ammonix.ch";


    /**
     * @return int
     */
    public static function getEnvironmentName(): int
    {
        switch ($_SERVER["HTTP_HOST"]) {
            case self::$_localhostUrl:
                return Environment::LOCALHOST;
                break;
            case self::$_testtUrl:
                return Environment::TEST;
                break;
            case self::$_prodtUrl:
                return Environment::PROD;
                break;
            default:
                return Environment::__default;
                break;
        }
    }

    public static function getDBvalues() :array
    {
        switch (self::getEnvironmentName()) {

            case Environment::LOCALHOST:
                return array("host" => "localhost", "username" => "root", "password" => "", "database" => "ammonixc_MusicManager");
                break;
            case Environment::TEST:
                return array("host" => "ammonixc.mysql.db.internal", "username" => "ammonixc_techus", "password" => "fVMrEK5C", "database" => "ammonixc_MusicManager");
                break;
            case Environment::PROD:
                ResponseHandler::addErrorMessage("DB-Values for PROD are undefined!");
                return array("host" => "", "username" => "", "password" => "", "" => "");
                break;
            default:
                ResponseHandler::addErrorMessage("Environment undefined!");
                return array("host" => "", "username" => "", "password" => "", "" => "");
                break;
        }
    }
}

