<?php
namespace MyApp;
header('Access-Control-Allow-Origin: *');
require_once "/Applications/XAMPP/xamppfiles/htdocs/codenames/src/model/UserDB.php";
$error_object = (object) ['error' => "Hello"];

if (!isset($_POST["password"]) || $_POST["password"] == "" || !isset($_POST["username"]) || $_POST["username"] == "") {
    return;
}
$res = UserDB::validLoginAttempt($_POST["username"], $_POST["password"]);
if (sizeof($res) == 0) {
    $error_object->error = "Password doesn't match username";
    echo(json_encode($error_object));
} else {
    if (UserDB::isActive($res[0]["uid"])) {
        $error_object->error = "A user with this uid is already playing";
        echo(json_encode($error_object));
        return;
    }
    echo(json_encode($res[0]));
}

?>