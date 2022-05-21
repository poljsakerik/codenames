<?php
namespace MyApp;

require_once "DBInit.php";

class UserDB {

    // Returns true if a valid combination of a username and a password are provided.
    public static function validLoginAttempt($username, $password) {
        $dbh = DBInit::getInstance();

        $query = "SELECT uid FROM users WHERE username = :username AND password = :password";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $password);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function add($username, $password) {
        $dbh = DBInit::getInstance();
        $query = "INSERT INTO users VALUES(NULL, :username, :password, NULL)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $password);
        $stmt->execute();
    }

    public static function getUsername($uid) {
        $dbh = DBInit::getInstance();

        $query = "SELECT username FROM users WHERE uid = :uid";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":uid", $uid);
        $stmt->execute();
        $x = $stmt->fetchAll();
        if (sizeof($x) == 0) {
            return;
        }
        return $x[0]["username"];
    }

    public static function setActive($uid, $active) {
        $dbh = DBInit::getInstance();
        $active = $active ? 1 : 0;
        $query = "UPDATE users 
        SET active = :active
        WHERE uid = :uid";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":active", $active);
        $stmt->bindParam(":uid", $uid);
        $stmt->execute();
    }

    public static function isActive($uid) {
        $dbh = DBInit::getInstance();
        $query = "SELECT active FROM users 
        WHERE uid = :uid";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":uid", $uid);
        $stmt->execute();

        $result = $stmt->fetchAll();

        if (sizeof($result) == 0) {
            return false;
        } else {
            return $result[0]["active"] == 1 ? true : false ;
        }
    }

    public static function validUsername($username) {
        $dbh = DBInit::getInstance();

        $query = "SELECT uid FROM users WHERE username = :username";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        $res = $stmt->fetchAll();
        return count($res) == 0;
    }
 }
