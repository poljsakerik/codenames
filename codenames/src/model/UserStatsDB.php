<?php
namespace MyApp;
require_once "DBInit.php";

class UserStatsDB {

    public static function get($uid) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT * FROM user_stats WHERE uid = :uid");
        $statement->bindParam(":uid", $uid);
        $statement->execute();

        return $statement->fetchAll()[0];
    }

    public static function updateWord($uid, $team, $word) {
        $db = DBInit::getInstance();
        $statement = "";
        if ($team == "blue") {
            $field = $word->team == "blue" ? "correct_words_blue" : "wrong_words_blue";
            $statement = $db->prepare("UPDATE user_stats 
            SET $field = $field + 1
            WHERE uid = :uid");
        }
        else if ($team == "red") {
            $field = $word->team == "red" ? "correct_words_red" : "wrong_words_red";
            $statement = $db->prepare("UPDATE user_stats 
            SET $field = $field + 1
            WHERE uid = :uid");
        }
        else if ($word->team == "black") {
            $field = $team == "blue" ? "black_words_blue" : "black_words_red";
            $statement = $db->prepare("UPDATE user_stats 
            SET black_words = black_words + 1
            WHERE uid = :uid");
        }
        $statement->bindParam(":uid", $uid);
        $statement->execute();
        
    }

    public static function updateGameResult($uid, $team, $winner) {
        $db = DBInit::getInstance();
        $statement = "";
        if ($team == "blue") {
            $field = $winner == "blue" ? "wins_blue" : "losses_blue";
            $statement = $db->prepare("UPDATE user_stats 
            SET $field = $field + 1
            WHERE uid = :uid");
        }
        else if ($team == "red") {
            $field = $winner == "red" ? "wins_red" : "losses_red";
            $statement = $db->prepare("UPDATE user_stats 
            SET $field = $field + 1
            WHERE uid = :uid");
        }
        $statement->bindParam(":uid", $uid);
        $statement->execute();
    }
}
