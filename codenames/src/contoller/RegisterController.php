<?php
namespace MyApp;
header('Access-Control-Allow-Origin: *');
require_once "/Applications/XAMPP/xamppfiles/htdocs/codenames/src/model/UserDB.php";
$error_object = (object)[
    'error' => (object)[
      'username' => null,
      'password' => null,
    ],
  ];

if (!isset($_POST["password"]) || $_POST["password"] == "" || !isset($_POST["username"]) || $_POST["username"] == "") {
    return;
}
$username = $_POST["username"];
$password = $_POST["password"];

if(strlen($password) < 5) {
    $error_object->error->password = "This password is too short";
    echo(json_encode($error_object));
    return;
}

if (UserDB::validUsername($username)) {
    UserDB::add($username, $password);
} else {
    $error_object->error->username = "This username is already taken";
    echo(json_encode($error_object));
}


?>