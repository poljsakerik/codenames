<?php
namespace MyApp;
require_once "DBInit.php";

class GameDB {

    public static function createGame() {
        $dbh = DBInit::getInstance();


        $query = "INSERT games VALUES (NULL)";
        $stmt = $dbh->prepare($query);
        $stmt->execute();
        return self::getLatestId();
    }

    public static function getLatestId() {
        $dbh = DBInit::getInstance();


        $query = "SELECT MAX(gid) FROM games"; 
        $stmt = $dbh->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll()[0]["gid"];
    }
}
