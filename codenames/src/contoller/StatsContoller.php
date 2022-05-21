<?php
namespace MyApp;
header('Access-Control-Allow-Origin: *');
require_once "/Applications/XAMPP/xamppfiles/htdocs/codenames/src/model/UserStatsDB.php";
$error_object = (object) ['error' => "Hello"];

if (!isset($_POST["uid"]) || $_POST["uid"] == "") {
    return;
}
$res = UserStatsDB::get($_POST["uid"]);
echo(json_encode($res));

?>