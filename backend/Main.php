<?php

/**
 * Created by PhpStorm.
 * User: Ammonix
 * Date: 14.09.2016
 * Time: 23:12
 */
include_once("db/DBconnect.php");
include_once("db/Gorm/Gorm.Base/DBObject.php");
include_once("Artist.php");
include_once("Genre.php");
include_once("Subgenre.php");
include_once("PermissionHandler.php");
include_once("ResponseHandler.php");
include_once("LoginHandler.php");
include_once("User.php");
include_once("Role.php");
include_once("ArtistList.php");


class Main
{

    private static $_instance; //The single instance
    /* @var $_allArtists Artist[] */
    private $_allArtists;
    /* @var $_user User */
    private $_user;
    /* @var $_allArtistLists ArtistList[] */
    private $_allArtistLists;


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

    private function initializeAllArtistLists()
    {
        $this->initializeAllArtists();
        $allArtists = $this->_allArtists;
        $allArtistLists = $this->getArtistLists();
        $artistListObjArray = array();
        $artistsArrObj = array();
        $lastArtistList = array("artistlist_PK" => 0);
        foreach ($allArtistLists as $artistList) {
            $artistListObjArray[$artistList["artistlist_PK"]] = new ArtistList($artistList["artistlist_PK"], $artistList["listName"], array());
        }
        foreach ($allArtistLists as $artistList) {
            if ($lastArtistList["artistlist_PK"] != $artistList["artistlist_PK"]) {
                $artistsArrObj = array();
            }
            foreach ($allArtists as $artist) {
                if ($artistList["artist_PK"] == $artist->getPk()) {
                    $artistsArrObj [] = $artist;
                    break;
                }
            }
            $artistListObjArray[$artistList["artistlist_PK"]]->setArtists($artistsArrObj);
            $lastArtistList = $artistList;
        }
        $this->_allArtistLists = $artistListObjArray;
    }

    private function getArtistLists() : array
    {
        $db = DBconnect::getInstance();
        $mysqli = $db->getConnection();

        $mysqli->autocommit(FALSE);
        $getArtistListResults = array();
        $query = "CALL getArtistLists();";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->execute();
            $stmt->bind_result($artistlist_PK, $listName, $artist_PK);
            while ($stmt->fetch()) {
                $getArtistListResults[] = array("artistlist_PK" => $artistlist_PK, "listName" => $listName, "artist_PK" => $artist_PK);
            }
            return $getArtistListResults;
        }
        ResponseHandler::addErrorMessage("Unknown error in prepare statement of query: " . $query);
        return array();
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
        /* @var $genre Genre */
        /* @var $subgenre Subgenre */
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
            $artistsArr [] = array("artistName" => $artist->getName(), "year" => $artist->getYear(), "picturePath" => $artist->getPicturePath(), "genres" => $genres);
        }
        return array("artists" => $artistsArr);

    }

    public function getArtistsByList(int $listPk):array
    {
        /* @var $artist Artist */
        /* @var $genre Genre */
        /* @var $subgenre Subgenre */

        $this->initializeAllArtistLists();
        $artistLists = array();
        foreach ($this->_allArtistLists as $artistList) {
            if ($artistList->getPk() == $listPk) {
                $artistsArr = array();

                foreach ($artistList->getArtists() as $artist) {
                    $genres = array();

                    foreach ($artist->getGenres() as $genre) {
                        $subgenres = array();

                        foreach ($genre->getSubgenres() as $subgenre) {
                            $subgenres [] = array("subgenreName" => $subgenre->getName());
                        }
                        $genres [] = array("genreName" => $genre->getName(), "subgenres" => $subgenres);
                    }
                    $artistsArr [] = array("artistName" => $artist->getName(), "year" => $artist->getYear(), "picturePath" => $artist->getPicturePath(), "genres" => $genres);
                }
                $artistLists[] = array("listName" => $artistList->getName(), "artists" => $artistsArr);
            }
        }
        return array("artistList" => $artistLists);
    }

    /*
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
    */
    //Frontend request Functions

    public function login()
    {


        $username = $_POST ["username"];
        $password = $_POST["password"];
        if ($this->_user->getRole() == 0) {
            $userObj = LoginHandler::login($username, $password);

            if ($userObj->getUsername() == "") {
                $userObj = $this->createVisitor();
            }
            $this->_user = $userObj;
        } else {
            ResponseHandler::addErrorMessage("User '" . $this->_user->getUsername() . "' is already logged in.");
        }
    }

    public function logout()
    {
        LoginHandler::logout();
    }

    public function getUser()
    {
        ResponseHandler::addData(array("user" => array(
            "username" => $this->_user->getUsername(),
            "email" => $this->_user->getEmail(),
            "createtime" => $this->_user->getCreatetime(),
            "role" => $this->_user->getRole())
        ));
    }

    public function getArtists()
    {

        if (isset ($_POST["artistList"]) || !empty($_POST["artistList"])) {
            ResponseHandler::addData($this->getArtistsByList($_POST["artistList"]));
        } else {
            ResponseHandler::addData($this->getAllArtists());
        }
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


