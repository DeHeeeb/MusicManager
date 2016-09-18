<?php

/**
 * Created by PhpStorm.
 * User: Ammonix
 * Date: 14.09.2016
 * Time: 23:12
 */
include_once("db/DBconnect.php");
include_once("Artist.php");
include_once("Genre.php");
include_once("Subgenre.php");
include_once("PermissionHandler.php");
include_once("ResponseHandler.php");
include_once("LoginHandler.php");
include_once("User.php");
include_once("Role.php");


class Main
{
    private static $_instance; //The single instance
    private $_allArtists;
    private $_user;


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
        $this->initializeUser();
    }

    public function getUser():User
    {
        return $this->_user;
    }


    private function initializeUser()
    {

        if (!empty($_SESSION["user"])) {
            $this->_user = $_SESSION["user"];
        } else {
            $this->_user = $this->createVisitor();
        }
    }

    private function createVisitor():User
    {
        return new User("Visitor", "", time(), 0);
    }

    private function initializeAllArtists()
    {
        $allArtists = $this->getAllArtistsFromDB();
        $allArtistsObj = array();
        foreach ($allArtists as $artist) {
            $artistObj = new Artist($artist["artist_PK"], $artist["artistName"], $artist["year"], $artist["picturePath"], array());


            $subgenreKeys = $this->getArtistHasSubgenreKeys($artistObj->getPk());
            $genres = array();
            $subgenreObjArrLink = array();
            foreach ($subgenreKeys as $subgenreKey) {
                $subgenres = $this->getSubgenreByKey($subgenreKey["subgenreKey"]);
                $genre = $this->getGenreByKey($subgenres["genre_FK"]);;
                $subgenreObjArrLink[] = array("genreName" => $genre, "subgenreObj" => new Subgenre($subgenres["subgenreName"]));
                $genres[] = $genre;
            }
            $genres = array_unique($genres);
            $genresObjArr = array();
            $i = 0;
            foreach ($genres as $genre) {
                $i++;
                $genreObj = new Genre($genre, array());
                $genresObjArr [] = $genreObj;
                foreach ($subgenreObjArrLink as $subgenreObjLink) {
                    if ($genre == $subgenreObjLink["genreName"]) {
                        $genreObj->addSubgenre($subgenreObjLink["subgenreObj"]);
                    }
                }
                $artistObj->addGenre($genreObj);
            }


            $allArtistsObj[] = $artistObj;
        }

        $this->_allArtists = $allArtistsObj;
    }

    private function getAllArtistsFromDB() : array
    {
        $db = DBconnect::getInstance();
        $mysqli = $db->getConnection();

        $mysqli->autocommit(FALSE);
        $getArtistsResults = array();
        $query = "CALL getArtists();";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->execute();
            $stmt->bind_result($artist_PK, $artistName, $year, $picturePath);
            while ($stmt->fetch()) {
                $getArtistsResults[] = array("artist_PK" => $artist_PK, "artistName" => $artistName, "year" => $year, "picturePath" => $picturePath);
            }
            return $getArtistsResults;
        }
        ResponseHandler::addErrorMessage("Unknown error in prepare statement of query: " . $query);
        return array();
    }

    private function getSubgenreByKey(int $subGenreKey) : array
    {
        $db = DBconnect::getInstance();
        $mysqli = $db->getConnection();

        $mysqli->autocommit(FALSE);
        $query = "CALL getSubgenreByKey(?);";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("i", $subGenreKey);
            $stmt->execute();
            $stmt->bind_result($subGenreName, $genre_FK);
            while ($stmt->fetch()) {
                return array("subgenreName" => $subGenreName, "genre_FK" => $genre_FK);
            }
        }
        ResponseHandler::addErrorMessage("Unknown error in prepare statement of query: " . $query);
        return array();
    }

    private function getArtistHasSubgenreKeys(int $artistKey):array
    {
        $db = DBconnect::getInstance();
        $mysqli = $db->getConnection();

        $mysqli->autocommit(FALSE);
        $subgenres = array();
        $query = "CALL getArtistHasSubgenreKeys(?);";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("i", $artistKey);
            $stmt->execute();
            $stmt->bind_result($subgenreKey);
            while ($stmt->fetch()) {
                $subgenres[] = array("subgenreKey" => $subgenreKey);
            }
            return $subgenres;
        }
        ResponseHandler::addErrorMessage("Unknown error in prepare statement of query: " . $query);
        return array();
    }

    private function getGenreByKey(int $genreKey) : string
    {
        $db = DBconnect::getInstance();
        $mysqli = $db->getConnection();

        $mysqli->autocommit(FALSE);
        $query = "CALL getGenreByKey(?);";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("i", $genreKey);
            $stmt->execute();
            $stmt->bind_result($genreName);
            while ($stmt->fetch()) {
                return $genreName;
            }
        }
        ResponseHandler::addErrorMessage("Unknown error in prepare statement of query: " . $query);
        return "";
    }


    public function getAllArtists() :array
    {
        $this->initializeAllArtists();
        $artistsArr = array();
        foreach ($this->_allArtists as $artist) {

            $genres = array();
            foreach ($artist->getGenres() as $genre) {
                $subgenres = array();
                foreach ($genre->getSubgenres() as $subgenre) {
                    $subgenres [] = array("subgenreName" => $subgenre->getName());
                }
                $genres [] = array("genreName" => $genre->getName(), "subgenres" => $subgenres);
            }
            $artistsArr [] = array("artistName" => $artist->getName(),"year" => $artist->getYear(),"picturePath" => $artist->getPicturePath(), "genres" => $genres);
        }
        return array("artists" => $artistsArr);

    }


    private function getArtistsDreamArr():array
    {
        return array(
            array(
                "artistName" => "Borknagar",
                "genres" => array(
                    array(
                        "genreName" => "Metal",
                        "subgenres" => array(
                            array("subgenreName" => "Progressive Black Metal"),
                            array("subgenreName" => "Folk Metal")
                        )
                    )
                )
            ),
            array(
                "artistName" => "Hollywood Undead",
                "genres" => array(
                    array(
                        "genreName" => "Hip-Hop",
                        "subgenres" => array(
                            array("subgenreName" => "Rap")
                        )
                    ),
                    array(
                        "genreName" => "Rock",
                        "subgenres" => array(
                            array("subgenreName" => "Alternative Rock")
                        )
                    )
                )
            )
        );
    }

    public function login()
    {
        $username = $_POST ["username"];
        $password = $_POST["password"];
        if ($this->getUser()->getRole() == 0) {
            $userObj = LoginHandler::login($username, $password);

            if ($userObj->getUsername() == "") {
                $userObj = $this->createVisitor();
            }
            $this->_user = $userObj;
        } else {
            ResponseHandler::addErrorMessage("User '" . $this->getUser()->getUsername() . "' is already logged in.");
        }
    }

    public function logout()
    {
        LoginHandler::logout();
    }

    public function getCurrentUser()
    {
        ResponseHandler::addData(array("user" => array(
            "username" => $this->getUser()->getUsername(),
            "email" => $this->getUser()->getEmail(),
            "createtime" => $this->getUser()->getCreatetime(),
            "role" => $this->getUser()->getRole())
        ));
    }

    public function getArtists()
    {
        ResponseHandler::addData($this->getAllArtists());
    }
}


//Procedural Start

function callFunction(Main $main, string $function)
{
    if (method_exists($main, $function)) {
        $main->$function();
    } else {
        ResponseHandler::addErrorMessage("Function: '" . $_POST["function"] . "' does not exist");
    }
}

header('Content-Type: application/json');
session_start();
$main = Main::getInstance();

if (isset ($_POST["function"]) || !empty($_POST["function"])) {
    callFunction($main, $_POST["function"]);
} else {
    ResponseHandler::addErrorMessage("Function not defined");
}
echo json_encode(ResponseHandler::getResponse());


