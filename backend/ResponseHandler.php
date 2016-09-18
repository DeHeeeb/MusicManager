<?php

/**
 * Created by PhpStorm.
 * User: Ammonix
 * Date: 18.09.2016
 * Time: 03:45
 */
abstract class ResponseHandler
{
    private static $_errorMessages = array();
    private static $_successMessages = array();
    private static $_data = array();

    public static function getErrorMessages():array
    {
        return self::$_errorMessages;
    }

    public static function addErrorMessage(string $errorMessage)
    {
        self::$_errorMessages[] = array("errorMessage_Nr_" . (count(self::$_errorMessages) + 1) => $errorMessage);
    }

    public static function getSuccessMessages(): array
    {
        return self::$_successMessages;
    }

    public static function addSuccessMessages(string $successMessage)
    {
        self::$_successMessages[] = array("successMessage_Nr_" . (count(self::$_successMessages) + 1) => $successMessage);
    }

    public static function getData(): array
    {
        return self::$_data;
    }

    public static function addData(array $data)
    {
        self::$_data[] = array("data_Nr_" . (count(self::$_data) + 1) => $data);
    }

    public static function getResponse(): array
    {
        $response = null;
        if (!self::noMessages(self::getErrorMessages())) {
            $response [] = array("errorMessages" => self::getErrorMessages());
        }
        if (!self::noMessages(self::getSuccessMessages())) {
            $response [] = array("successMessages" => self::getSuccessMessages());
        }
        if (!self::noMessages(self::getData())) {
            $response [] = array("data" => self::getData());
        }
        if ($response == null) {
            self::addErrorMessage("No response");
            $response = self::getResponse();
        }
        return array("response" => $response);
    }

    private static function noMessages(array $message):bool
    {
        return !(!empty($message) || $message != null);
    }

}