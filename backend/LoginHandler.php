<?php

/**
 * Created by PhpStorm.
 * User: Ammonix
 * Date: 16.09.2016
 * Time: 15:02
 */
abstract class LoginHandler
{
    public static function login($username, $pw) : User
    {
        $wrongusernameorpassword = true;
        /* PW CREATE
                $options = [
                    'cost' => 12,
                ];
                $hashedPWfromDB = password_hash("tstpw", PASSWORD_BCRYPT, $options);
                */
        $userData = LoginHandler::getUserByUsername($username);
        if (empty(ResponseHandler::getErrorMessages())) {
            if (password_verify($pw, $userData["password"])) {
                $wrongusernameorpassword = false;
                $userObj = new User($username, $userData["email"], $userData["createtime"], $userData["role"]);
                $_SESSION["user"] = $userObj;
                ResponseHandler::addSuccessMessages("User '" . $username . "' successfully logged in");
                return $userObj;
            }
        }
        if ($wrongusernameorpassword) {
            ResponseHandler::addErrorMessage("Wrong username or password");
        } else {
            ResponseHandler::addErrorMessage("An unknown error occurred while logging in");
        }
        return new User("", "", "", 0);
    }

    public static function logout()
    {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"],
                $params["domain"], $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        ResponseHandler::addSuccessMessages("Logout was successfully -> session destroyed");
    }

    private static function getUserByUsername($username) : array
    {
        $db = DBconnect::getInstance();
        $mysqli = $db->getConnection();
        $mysqli->autocommit(FALSE);
        $query = "CALL getUserByUsername(?);";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($email, $password, $create_time, $role);
            while ($stmt->fetch()) {
                return array("username" => $username, "email" => $email, "password" => $password, "createtime" => $create_time, "role" => $role);
            }
            ResponseHandler::addErrorMessage("User does not exist");
            return array();
        }
        ResponseHandler::addErrorMessage("Unknown error in prepare statement of query: " . $query);
        return array();
    }
}