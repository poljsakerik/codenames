<?php
namespace MyApp;

require_once "DBInit.php";

class WordsDB {

    // Returns true if a valid combination of a username and a password are provided.
    public static function getRandom() {
        $dbh = DBInit::getInstance();

        $query = "SELECT * FROM words ORDER BY RAND() LIMIT 25";
        $stmt = $dbh->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

 }
