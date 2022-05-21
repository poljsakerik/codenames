<?php
namespace MyApp;
header('Access-Control-Allow-Origin: *');
require_once "/Applications/XAMPP/xamppfiles/htdocs/codenames/src/model/WordsDB.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/codenames/src/model/UserDB.php";

var_dump(UserDB::validUsername("Jaka Boži"));

?>