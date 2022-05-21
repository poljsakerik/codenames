<?php
namespace MyApp;
require_once "DBInit.php";

class GameParticipantDB {

    // Returns true if a valid combination of a username and a password are provided.
    public static function insert($uid, $gid, $team, $winner) {
        $dbh = DBInit::getInstance();


        $query = "INSERT INTO game_participant VALUES (:uid, :gid, :team, :winner)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":uid", $uid);
        $stmt->bindParam(":gid", $gid);
        $stmt->bindParam(":team", $team);
        $stmt->bindParam(":winner", $winner);
        $stmt->execute();
    }

    public static function getGamesPlayed($uid, $team) {
        $dbh = DBInit::getInstance();


        $query = "SELECT COUNT(uid) FROM game_participant WHERE uid = :uid AND team = :team";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":uid", $username);
        $stmt->bindParam(":team", $team);
        $stmt->execute();

        return $stmt->fetchColumn(0);
    }
}
